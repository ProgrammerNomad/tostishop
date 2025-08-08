<?php
/**
 * TostiShop Wishlist Functionality
 * Complete wishlist system for WooCommerce
 * 
 * @package TostiShop
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize wishlist functionality
 */
function tostishop_init_wishlist() {
    // Create wishlist table on theme activation
    add_action('after_switch_theme', 'tostishop_create_wishlist_table');
    
    // Add wishlist endpoints to my account
    add_action('init', 'tostishop_add_wishlist_endpoint');
    add_filter('woocommerce_account_menu_items', 'tostishop_add_wishlist_menu_item');
    add_action('woocommerce_account_wishlist_endpoint', 'tostishop_wishlist_content');
    
    // AJAX handlers
    add_action('wp_ajax_tostishop_add_to_wishlist', 'tostishop_ajax_add_to_wishlist');
    add_action('wp_ajax_nopriv_tostishop_add_to_wishlist', 'tostishop_ajax_add_to_wishlist');
    add_action('wp_ajax_tostishop_remove_from_wishlist', 'tostishop_ajax_remove_from_wishlist');
    add_action('wp_ajax_nopriv_tostishop_remove_from_wishlist', 'tostishop_ajax_remove_from_wishlist');
    
    // Enqueue wishlist styles and scripts
    add_action('wp_enqueue_scripts', 'tostishop_enqueue_wishlist_assets');
}

/**
 * Create wishlist database table
 */
function tostishop_create_wishlist_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'tostishop_wishlist';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        product_id bigint(20) NOT NULL,
        date_added datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY user_product (user_id, product_id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

/**
 * Add wishlist endpoint to my account
 */
function tostishop_add_wishlist_endpoint() {
    add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);
}

/**
 * Add wishlist menu item to my account navigation
 */
function tostishop_add_wishlist_menu_item($items) {
    // Insert wishlist after dashboard
    $new_items = array();
    foreach ($items as $key => $item) {
        $new_items[$key] = $item;
        if ($key === 'dashboard') {
            $new_items['wishlist'] = __('Wishlist', 'tostishop');
        }
    }
    return $new_items;
}

/**
 * Display wishlist content in my account
 */
function tostishop_wishlist_content() {
    $wishlist_items = tostishop_get_user_wishlist();
    include get_template_directory() . '/woocommerce/myaccount/wishlist.php';
}

/**
 * Get user's wishlist items
 */
function tostishop_get_user_wishlist($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    if (!$user_id) {
        return array();
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'tostishop_wishlist';
    
    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT product_id, date_added FROM $table_name WHERE user_id = %d ORDER BY date_added DESC",
        $user_id
    ));
    
    $wishlist_items = array();
    foreach ($results as $item) {
        $product = wc_get_product($item->product_id);
        if ($product && $product->exists()) {
            $wishlist_items[] = array(
                'product' => $product,
                'date_added' => $item->date_added
            );
        }
    }
    
    return $wishlist_items;
}

/**
 * Check if product is in user's wishlist
 */
function tostishop_is_product_in_wishlist($product_id, $user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    if (!$user_id) {
        return false;
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'tostishop_wishlist';
    
    $result = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE user_id = %d AND product_id = %d",
        $user_id,
        $product_id
    ));
    
    return $result > 0;
}

/**
 * Add product to wishlist
 */
function tostishop_add_to_wishlist($product_id, $user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    if (!$user_id) {
        return false;
    }
    
    // Check if product exists
    $product = wc_get_product($product_id);
    if (!$product || !$product->exists()) {
        return false;
    }
    
    // Check if already in wishlist
    if (tostishop_is_product_in_wishlist($product_id, $user_id)) {
        return false;
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'tostishop_wishlist';
    
    $result = $wpdb->insert(
        $table_name,
        array(
            'user_id' => $user_id,
            'product_id' => $product_id,
            'date_added' => current_time('mysql')
        ),
        array('%d', '%d', '%s')
    );
    
    return $result !== false;
}

/**
 * Remove product from wishlist
 */
function tostishop_remove_from_wishlist($product_id, $user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    if (!$user_id) {
        return false;
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'tostishop_wishlist';
    
    $result = $wpdb->delete(
        $table_name,
        array(
            'user_id' => $user_id,
            'product_id' => $product_id
        ),
        array('%d', '%d')
    );
    
    return $result !== false;
}

/**
 * Get wishlist count for user
 */
function tostishop_get_wishlist_count($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    if (!$user_id) {
        return 0;
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'tostishop_wishlist';
    
    return (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE user_id = %d",
        $user_id
    ));
}

/**
 * AJAX handler for adding to wishlist
 */
function tostishop_ajax_add_to_wishlist() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_nonce')) {
        wp_send_json_error(__('Security check failed', 'tostishop'));
        return;
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error(__('Please log in to add items to your wishlist', 'tostishop'));
        return;
    }
    
    $product_id = intval($_POST['product_id']);
    
    if (!$product_id) {
        wp_send_json_error(__('Invalid product', 'tostishop'));
        return;
    }
    
    $result = tostishop_add_to_wishlist($product_id);
    
    if ($result) {
        wp_send_json_success(array(
            'message' => __('Product added to wishlist', 'tostishop'),
            'wishlist_count' => tostishop_get_wishlist_count()
        ));
    } else {
        wp_send_json_error(__('Product is already in your wishlist', 'tostishop'));
    }
}

/**
 * AJAX handler for removing from wishlist
 */
function tostishop_ajax_remove_from_wishlist() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_nonce')) {
        wp_send_json_error(__('Security check failed', 'tostishop'));
        return;
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error(__('Please log in to manage your wishlist', 'tostishop'));
        return;
    }
    
    $product_id = intval($_POST['product_id']);
    
    if (!$product_id) {
        wp_send_json_error(__('Invalid product', 'tostishop'));
        return;
    }
    
    $result = tostishop_remove_from_wishlist($product_id);
    
    if ($result) {
        wp_send_json_success(array(
            'message' => __('Product removed from wishlist', 'tostishop'),
            'wishlist_count' => tostishop_get_wishlist_count()
        ));
    } else {
        wp_send_json_error(__('Failed to remove product from wishlist', 'tostishop'));
    }
}

/**
 * Enqueue wishlist assets
 */
function tostishop_enqueue_wishlist_assets() {
    wp_enqueue_script(
        'tostishop-wishlist',
        get_template_directory_uri() . '/assets/js/wishlist.js',
        array('jquery'),
        '1.0.0',
        true
    );
    
    wp_localize_script('tostishop-wishlist', 'tostishop_wishlist_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('tostishop_nonce'),
        'strings' => array(
            'add_to_wishlist' => __('Add to Wishlist', 'tostishop'),
            'remove_from_wishlist' => __('Remove from Wishlist', 'tostishop'),
            'in_wishlist' => __('In Wishlist', 'tostishop'),
            'login_required' => __('Please log in to use wishlist', 'tostishop')
        )
    ));
}

/**
 * Generate wishlist button HTML
 */
function tostishop_get_wishlist_button($product_id, $classes = '') {
    if (!is_user_logged_in()) {
        return sprintf(
            '<button class="wishlist-btn wishlist-login-required %s" data-product-id="%d" title="%s">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <span class="sr-only">%s</span>
            </button>',
            esc_attr($classes),
            intval($product_id),
            esc_attr__('Add to Wishlist', 'tostishop'),
            esc_html__('Add to Wishlist', 'tostishop')
        );
    }
    
    $is_in_wishlist = tostishop_is_product_in_wishlist($product_id);
    $button_class = $is_in_wishlist ? 'wishlist-btn in-wishlist' : 'wishlist-btn';
    $title = $is_in_wishlist ? __('Remove from Wishlist', 'tostishop') : __('Add to Wishlist', 'tostishop');
    $fill = $is_in_wishlist ? 'currentColor' : 'none';
    
    return sprintf(
        '<button class="%s %s" data-product-id="%d" title="%s">
            <svg class="w-5 h-5" fill="%s" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <span class="sr-only">%s</span>
        </button>',
        esc_attr($button_class),
        esc_attr($classes),
        intval($product_id),
        esc_attr($title),
        esc_attr($fill),
        esc_html($title)
    );
}

// Initialize wishlist functionality
tostishop_init_wishlist();
