<?php
/**
 * TostiShop Theme Functions
 * Mobile-first WooCommerce theme with Firebase Authentication
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function tostishop_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    // Custom logo support with TostiShop branding
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array('site-title', 'site-description'),
        'unlink-homepage-logo' => false,
    ));
    
    // WooCommerce support
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'tostishop'),
        'mobile' => __('Mobile Menu', 'tostishop'),
        'footer' => __('Footer Menu', 'tostishop'),
    ));
    
    // Add custom image sizes
    add_image_size('tostishop-hero', 1200, 600, true);
    add_image_size('tostishop-product-thumb', 300, 300, true);
    add_image_size('tostishop-category-thumb', 150, 150, true);
    
    // Set custom logo on theme activation
    if (!get_theme_mod('custom_logo')) {
        $logo_path = get_template_directory() . '/assets/images/logo.png';
        if (file_exists($logo_path)) {
            $logo_attachment_id = tostishop_upload_logo();
            if ($logo_attachment_id) {
                set_theme_mod('custom_logo', $logo_attachment_id);
            }
        }
    }
}
add_action('after_setup_theme', 'tostishop_setup');

/**
 * Upload and set custom logo
 */
function tostishop_upload_logo() {
    $logo_url = get_template_directory_uri() . '/assets/images/logo.png';
    $logo_path = get_template_directory() . '/assets/images/logo.png';
    
    if (!file_exists($logo_path)) {
        return false;
    }
    
    // Check if logo already exists in media library
    $existing_logo = get_posts(array(
        'post_type' => 'attachment',
        'meta_query' => array(
            array(
                'key' => '_wp_attached_file',
                'value' => 'tostishop-logo.png',
                'compare' => 'LIKE'
            )
        ),
        'posts_per_page' => 1
    ));
    
    if (!empty($existing_logo)) {
        return $existing_logo[0]->ID;
    }
    
    // Upload logo to media library
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    
    $tmp = download_url($logo_url);
    if (is_wp_error($tmp)) {
        return false;
    }
    
    $file_array = array(
        'name' => 'tostishop-logo.png',
        'tmp_name' => $tmp
    );
    
    $attachment_id = media_handle_sideload($file_array, 0);
    
    if (is_wp_error($attachment_id)) {
        @unlink($tmp);
        return false;
    }
    
    @unlink($tmp);
    return $attachment_id;
}

/**
 * Checkout Customizations
 * Remove separate shipping address and combine with billing
 */
function tostishop_checkout_customizations() {
    // Force shipping address to be same as billing address
    add_filter('woocommerce_cart_needs_shipping_address', '__return_false');
    
    // Remove shipping fields from checkout
    add_filter('woocommerce_checkout_fields', 'tostishop_remove_shipping_fields');
    
    // Auto-copy billing to shipping on checkout
    add_action('woocommerce_checkout_process', 'tostishop_auto_copy_billing_to_shipping');
}
add_action('init', 'tostishop_checkout_customizations');

/**
 * Remove shipping fields from checkout
 */
function tostishop_remove_shipping_fields($fields) {
    // Remove all shipping fields
    unset($fields['shipping']);
    return $fields;
}

/**
 * Auto-copy billing address to shipping address
 */
function tostishop_auto_copy_billing_to_shipping() {
    // Copy billing data to shipping
    $_POST['shipping_first_name'] = $_POST['billing_first_name'];
    $_POST['shipping_last_name'] = $_POST['billing_last_name'];
    $_POST['shipping_company'] = $_POST['billing_company'];
    $_POST['shipping_address_1'] = $_POST['billing_address_1'];
    $_POST['shipping_address_2'] = $_POST['billing_address_2'];
    $_POST['shipping_city'] = $_POST['billing_city'];
    $_POST['shipping_postcode'] = $_POST['billing_postcode'];
    $_POST['shipping_country'] = $_POST['billing_country'];
    $_POST['shipping_state'] = $_POST['billing_state'];
}

/**
 * Enqueue scripts and styles
 */
function tostishop_scripts() {
    // Main stylesheet (compiled Tailwind CSS)
    wp_enqueue_style('tostishop-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Custom CSS for enhanced functionality
    wp_enqueue_style('tostishop-custom', get_template_directory_uri() . '/assets/css/custom.css', array('tostishop-style'), '1.0.0');
    
    // Alpine.js for interactivity
    wp_enqueue_script('alpinejs', 'https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js', array(), '3.0.0', true);
    wp_script_add_data('alpinejs', 'defer', true);
    
    // Custom JS
    wp_enqueue_script('tostishop-ui', get_template_directory_uri() . '/assets/js/ui.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('tostishop-theme', get_template_directory_uri() . '/assets/js/theme.js', array('jquery', 'tostishop-ui'), '1.0.0', true);
    
    // Homepage specific styles and scripts
    if (is_page_template('page-home.php') || is_front_page()) {
        wp_enqueue_style('tostishop-homepage', get_template_directory_uri() . '/assets/css/homepage.css', array('tostishop-style'), '1.0.0');
        wp_enqueue_script('tostishop-homepage', get_template_directory_uri() . '/assets/js/homepage.js', array('jquery'), '1.0.0', true);
    }
    
    // Checkout specific JS
    if (is_checkout()) {
        wp_enqueue_script('tostishop-checkout', get_template_directory_uri() . '/assets/js/checkout.js', array('jquery', 'wc-checkout'), '1.0.0', true);
    }
    
    // Cart specific JS
    if (is_cart()) {
        wp_enqueue_script('tostishop-cart', get_template_directory_uri() . '/assets/js/cart.js', array('jquery'), '1.0.0', true);
        
        // Localize cart script specifically
        wp_localize_script('tostishop-cart', 'tostishop_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('tostishop_nonce'),
        ));
    }
    
    // Order confirmation specific JS
    if (is_order_received_page() || is_wc_endpoint_url('order-received')) {
        wp_enqueue_script('tostishop-order-confirmation', get_template_directory_uri() . '/assets/js/order-confirmation.js', array(), '1.0.0', true);
    }
    
    // Firebase Authentication (India-specific)
    if (is_account_page() || is_checkout()) {
        tostishop_enqueue_firebase_auth();
    }
}
add_action('wp_enqueue_scripts', 'tostishop_scripts');

/**
 * Enqueue Firebase Authentication Scripts for India - Updated v3.0
 */
function tostishop_enqueue_firebase_auth() {
    $firebase_settings = get_option('tostishop_firebase_settings', array());
    $general_settings = get_option('tostishop_general_settings', array());
    
    // Only load if Firebase is enabled and configured
    if (empty($general_settings['enable_firebase_auth']) || empty($firebase_settings['api_key'])) {
        return;
    }
    
    // Firebase SDK
    wp_enqueue_script('firebase-app', 'https://www.gstatic.com/firebasejs/10.7.0/firebase-app-compat.js', array(), '10.7.0', true);
    wp_enqueue_script('firebase-auth', 'https://www.gstatic.com/firebasejs/10.7.0/firebase-auth-compat.js', array('firebase-app'), '10.7.0', true);
    
    // Updated Firebase authentication for comprehensive login/register UI
    wp_enqueue_script('tostishop-firebase-auth', get_template_directory_uri() . '/assets/js/firebase-auth-updated.js', array('firebase-app', 'firebase-auth'), '3.0.0', true);
    
    // Enhanced Firebase CSS
    wp_enqueue_style('tostishop-firebase-auth', get_template_directory_uri() . '/assets/css/firebase-auth-enhanced.css', array(), '3.0.0');
    
    // Localize Firebase configuration
    wp_localize_script('tostishop-firebase-auth', 'tostiShopFirebaseConfig', array(
        'apiKey' => $firebase_settings['api_key'],
        'authDomain' => $firebase_settings['auth_domain'],
        'projectId' => $firebase_settings['project_id'],
        'storageBucket' => $firebase_settings['project_id'] . '.appspot.com',
        'messagingSenderId' => $firebase_settings['sender_id'],
        'appId' => $firebase_settings['app_id'],
        'measurementId' => !empty($firebase_settings['measurement_id']) ? $firebase_settings['measurement_id'] : null
    ));
    
    // Localize AJAX settings
    wp_localize_script('tostishop-firebase-auth', 'tostiShopAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('tostishop_firebase_nonce'),
        'redirectUrl' => is_checkout() ? wc_get_checkout_url() : wc_get_account_endpoint_url('dashboard'),
        'strings' => array(
            'loginSuccess' => __('Login successful! Redirecting...', 'tostishop'),
            'loginError' => __('Login failed. Please try again.', 'tostishop'),
            'otpSent' => __('OTP sent to your phone. Please check your messages.', 'tostishop'),
            'otpError' => __('Failed to send OTP. Please try again.', 'tostishop'),
            'invalidOtp' => __('Invalid OTP. Please try again.', 'tostishop'),
            'phoneRequired' => __('Please enter a valid phone number.', 'tostishop'),
        )
    ));
}

/**
 * Include Theme Components
 */
// Theme Options Panel
require_once get_template_directory() . '/inc/theme-options.php';

// Firebase Authentication (backward compatibility)
if (file_exists(get_template_directory() . '/inc/firebase/init.php')) {
    require_once get_template_directory() . '/inc/firebase/init.php';
}

/**
 * Register widget areas
 */
function tostishop_widgets_init() {
    register_sidebar(array(
        'name' => __('Footer Widget 1', 'tostishop'),
        'id' => 'footer-1',
        'description' => __('Add widgets here to appear in your footer.', 'tostishop'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title text-lg font-semibold mb-4">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer Widget 2', 'tostishop'),
        'id' => 'footer-2',
        'description' => __('Add widgets here to appear in your footer.', 'tostishop'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title text-lg font-semibold mb-4">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer Widget 3', 'tostishop'),
        'id' => 'footer-3',
        'description' => __('Add widgets here to appear in your footer.', 'tostishop'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title text-lg font-semibold mb-4">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'tostishop_widgets_init');

/**
 * Customize WooCommerce
 */
// Remove default WooCommerce styles
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Modify WooCommerce loop
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

// Add custom WooCommerce actions
add_action('woocommerce_before_shop_loop_item_title', 'tostishop_product_link_open', 10);
add_action('woocommerce_shop_loop_item_title', 'tostishop_product_title', 10);
add_action('woocommerce_after_shop_loop_item', 'tostishop_product_link_close', 5);

function tostishop_product_link_open() {
    global $product;
    echo '<a href="' . get_permalink($product->get_id()) . '" class="group block">';
}

function tostishop_product_link_close() {
    echo '</a>';
}

function tostishop_product_title() {
    echo '<h3 class="text-sm font-medium text-gray-900 group-hover:text-navy-600 transition-colors duration-200">' . get_the_title() . '</h3>';
}

/**
 * Custom functions
 */

// Get cart count for header
function tostishop_cart_count() {
    if (function_exists('WC')) {
        return WC()->cart->get_cart_contents_count();
    }
    return 0;
}

// Get cart total for header
function tostishop_cart_total() {
    if (function_exists('WC')) {
        return WC()->cart->get_cart_total();
    }
    return '';
}

// Custom breadcrumbs
function tostishop_breadcrumbs() {
    if (function_exists('woocommerce_breadcrumb')) {
        woocommerce_breadcrumb(array(
            'delimiter' => ' / ',
            'wrap_before' => '<nav class="breadcrumbs text-sm text-gray-600 mb-4">',
            'wrap_after' => '</nav>',
            'before' => '',
            'after' => '',
            'home' => __('Home', 'tostishop'),
        ));
    }
}

/**
 * AJAX handlers
 */

// Order confirmation enhancements
function tostishop_order_confirmation_enhancements() {
    // Add special styling to order confirmation pages
    if (is_order_received_page() || is_wc_endpoint_url('order-received')) {
        // Add viewport meta for better mobile experience
        add_action('wp_head', function() {
            echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">';
        }, 1);
        
        // Add structured data for order confirmation
        add_action('wp_head', 'tostishop_order_confirmation_structured_data');
        
        // Add print styles
        add_action('wp_head', function() {
            echo '<style media="print">
                .no-print, header, footer, .order-action-btn { display: none !important; }
                .order-details-card { box-shadow: none !important; border: 1px solid #000 !important; }
            </style>';
        });
    }
}
add_action('template_redirect', 'tostishop_order_confirmation_enhancements');

/**
 * Add structured data for order confirmation
 */
function tostishop_order_confirmation_structured_data() {
    global $wp;
    
    if (isset($wp->query_vars['order-received']) && !empty($wp->query_vars['order-received'])) {
        $order_id = absint($wp->query_vars['order-received']);
        $order = wc_get_order($order_id);
        
        if ($order && $order->get_status() !== 'failed') {
            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'Order',
                'orderNumber' => $order->get_order_number(),
                'orderDate' => $order->get_date_created()->format('c'),
                'orderStatus' => 'https://schema.org/OrderProcessing',
                'priceCurrency' => $order->get_currency(),
                'price' => $order->get_total(),
                'url' => $order->get_view_order_url(),
                'merchant' => array(
                    '@type' => 'Organization',
                    'name' => get_bloginfo('name'),
                    'url' => home_url()
                ),
                'customer' => array(
                    '@type' => 'Person',
                    'name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                    'email' => $order->get_billing_email()
                )
            );
            
            echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
        }
    }
}

/**
 * Customize order confirmation email link
 */
function tostishop_order_confirmation_email_text($text, $order) {
    if ($order && !$order->has_status('failed')) {
        return __('We\'ve sent a confirmation email to your inbox. Please check your email for order details and tracking information.', 'tostishop');
    }
    return $text;
}
add_filter('woocommerce_thankyou_order_received_text', 'tostishop_order_confirmation_email_text', 10, 2);

/**
 * Add order tracking information to confirmation page
 */
function tostishop_add_order_tracking_info($order_id) {
    $order = wc_get_order($order_id);
    
    if ($order && !$order->has_status(array('failed', 'cancelled'))) {
        echo '<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mt-6">';
        echo '<div class="flex items-start space-x-3">';
        echo '<div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">';
        echo '<svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
        echo '</svg>';
        echo '</div>';
        echo '<div>';
        echo '<h3 class="text-sm font-semibold text-yellow-900 mb-1">' . __('What\'s Next?', 'tostishop') . '</h3>';
        echo '<p class="text-sm text-yellow-800">';
        echo __('We\'ll start processing your order right away. You\'ll receive tracking information once your order ships.', 'tostishop');
        echo '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
add_action('woocommerce_thankyou', 'tostishop_add_order_tracking_info', 20);

// Add to cart via AJAX
add_action('wp_ajax_tostishop_add_to_cart', 'tostishop_ajax_add_to_cart');
add_action('wp_ajax_nopriv_tostishop_add_to_cart', 'tostishop_ajax_add_to_cart');

function tostishop_ajax_add_to_cart() {
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_nonce')) {
        wp_die('Security check failed');
    }
    
    $product_id = absint($_POST['product_id']);
    $quantity = absint($_POST['quantity']);
    
    $result = WC()->cart->add_to_cart($product_id, $quantity);
    
    if ($result) {
        wp_send_json_success(array(
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_total' => WC()->cart->get_cart_total(),
        ));
    } else {
        wp_send_json_error();
    }
}

// Update cart item quantity via AJAX
add_action('wp_ajax_tostishop_update_cart_item', 'tostishop_ajax_update_cart_item');
add_action('wp_ajax_nopriv_tostishop_update_cart_item', 'tostishop_ajax_update_cart_item');

function tostishop_ajax_update_cart_item() {
    // Check if WooCommerce is available
    if (!class_exists('WooCommerce')) {
        wp_send_json_error('WooCommerce not available');
        return;
    }
    
    // Check nonce
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    // Get and validate parameters
    if (!isset($_POST['cart_item_key']) || !isset($_POST['quantity'])) {
        wp_send_json_error('Missing required parameters');
        return;
    }
    
    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    $quantity = absint($_POST['quantity']);
    
    // Check if cart item exists
    if (!WC()->cart->get_cart_item($cart_item_key)) {
        wp_send_json_error('Cart item not found');
        return;
    }
    
    try {
        if ($quantity == 0) {
            $result = WC()->cart->remove_cart_item($cart_item_key);
        } else {
            $result = WC()->cart->set_quantity($cart_item_key, $quantity);
        }
        
        if ($result) {
            WC()->cart->calculate_totals();
            
            wp_send_json_success(array(
                'cart_count' => WC()->cart->get_cart_contents_count(),
                'cart_total' => WC()->cart->get_cart_total(),
                'cart_subtotal' => WC()->cart->get_cart_subtotal(),
                'fragments' => apply_filters('woocommerce_add_to_cart_fragments', array())
            ));
        } else {
            wp_send_json_error('Failed to update cart item');
        }
    } catch (Exception $e) {
        wp_send_json_error('Error updating cart: ' . $e->getMessage());
    }
}

// Remove cart item via AJAX
add_action('wp_ajax_tostishop_remove_cart_item', 'tostishop_ajax_remove_cart_item');
add_action('wp_ajax_nopriv_tostishop_remove_cart_item', 'tostishop_ajax_remove_cart_item');

function tostishop_ajax_remove_cart_item() {
    // Check if WooCommerce is available
    if (!class_exists('WooCommerce')) {
        wp_send_json_error('WooCommerce not available');
        return;
    }
    
    // Check nonce
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    // Get and validate parameters
    if (!isset($_POST['cart_item_key'])) {
        wp_send_json_error('Missing cart item key');
        return;
    }
    
    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    
    // Check if cart item exists
    if (!WC()->cart->get_cart_item($cart_item_key)) {
        wp_send_json_error('Cart item not found');
        return;
    }
    
    try {
        $result = WC()->cart->remove_cart_item($cart_item_key);
        
        if ($result) {
            WC()->cart->calculate_totals();
            
            wp_send_json_success(array(
                'cart_count' => WC()->cart->get_cart_contents_count(),
                'cart_total' => WC()->cart->get_cart_total(),
                'cart_subtotal' => WC()->cart->get_cart_subtotal(),
                'fragments' => apply_filters('woocommerce_add_to_cart_fragments', array())
            ));
        } else {
            wp_send_json_error('Failed to remove cart item');
        }
    } catch (Exception $e) {
        wp_send_json_error('Error removing cart item: ' . $e->getMessage());
    }
}

/**
 * Set default custom logo on theme activation
 */
function tostishop_set_default_logo() {
    // Check if custom logo is not already set
    if (!has_custom_logo()) {
        $logo_path = get_template_directory() . '/assets/images/logo.png';
        $logo_url = get_template_directory_uri() . '/assets/images/logo.png';
        
        // Check if logo file exists
        if (file_exists($logo_path)) {
            // Get the upload directory
            $upload_dir = wp_upload_dir();
            
            // Check if attachment already exists
            $attachment_id = attachment_url_to_postid($logo_url);
            
            if (!$attachment_id) {
                // Create attachment
                $filename = basename($logo_path);
                $file_type = wp_check_filetype($filename, null);
                
                $attachment = array(
                    'guid' => $logo_url,
                    'post_mime_type' => $file_type['type'],
                    'post_title' => 'TostiShop Logo',
                    'post_content' => '',
                    'post_status' => 'inherit'
                );
                
                $attachment_id = wp_insert_attachment($attachment, $logo_path);
                
                if (!is_wp_error($attachment_id)) {
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    $attachment_data = wp_generate_attachment_metadata($attachment_id, $logo_path);
                    wp_update_attachment_metadata($attachment_id, $attachment_data);
                }
            }
            
            // Set as custom logo
            if ($attachment_id) {
                set_theme_mod('custom_logo', $attachment_id);
            }
        }
    }
}

// Run on theme activation
add_action('after_switch_theme', 'tostishop_set_default_logo');

/**
 * Theme customizer
 */
function tostishop_customize_register($wp_customize) {
    // Hero section
    $wp_customize->add_section('tostishop_hero', array(
        'title' => __('Hero Section', 'tostishop'),
        'priority' => 30,
    ));
    
    $wp_customize->add_setting('hero_title', array(
        'default' => __('Welcome to TostiShop', 'tostishop'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('hero_title', array(
        'label' => __('Hero Title', 'tostishop'),
        'section' => 'tostishop_hero',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('hero_subtitle', array(
        'default' => __('Discover amazing products at great prices', 'tostishop'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('hero_subtitle', array(
        'label' => __('Hero Subtitle', 'tostishop'),
        'section' => 'tostishop_hero',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('hero_image', array(
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_image', array(
        'label' => __('Hero Background Image', 'tostishop'),
        'section' => 'tostishop_hero',
    )));
}
add_action('customize_register', 'tostishop_customize_register');

/**
 * Enhanced Pagination Function
 */
function tostishop_enhanced_pagination() {
    global $wp_query;
    
    $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
    $max_pages = $wp_query->max_num_pages;
    
    if ($max_pages <= 1) {
        return;
    }
    
    echo '<nav class="tostishop-pagination" role="navigation" aria-label="' . __('Pagination Navigation', 'tostishop') . '">';
    echo '<div class="pagination-wrapper flex flex-wrap justify-center items-center gap-2">';
    
    // Previous button
    if ($paged > 1) {
        echo '<a href="' . get_pagenum_link($paged - 1) . '" class="pagination-btn pagination-prev flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-navy-500 focus:ring-offset-1">';
        echo '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>';
        echo __('Previous', 'tostishop');
        echo '</a>';
    }
    
    // Page numbers
    $range = 2; // Number of pages to show on each side
    $start = max(1, $paged - $range);
    $end = min($max_pages, $paged + $range);
    
    // First page + dots
    if ($start > 1) {
        echo '<a href="' . get_pagenum_link(1) . '" class="pagination-btn page-number inline-flex items-center justify-center min-w-[40px] h-10 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">1</a>';
        if ($start > 2) {
            echo '<span class="pagination-dots inline-flex items-center justify-center min-w-[40px] h-10 px-3 py-2 text-gray-400">...</span>';
        }
    }
    
    // Page range
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $paged) {
            echo '<span class="pagination-btn page-number current inline-flex items-center justify-center min-w-[40px] h-10 px-3 py-2 text-sm font-medium text-white bg-navy-600 border border-navy-600 rounded-lg shadow-md">' . $i . '</span>';
        } else {
            echo '<a href="' . get_pagenum_link($i) . '" class="pagination-btn page-number inline-flex items-center justify-center min-w-[40px] h-10 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">' . $i . '</a>';
        }
    }
    
    // Last page + dots
    if ($end < $max_pages) {
        if ($end < $max_pages - 1) {
            echo '<span class="pagination-dots inline-flex items-center justify-center min-w-[40px] h-10 px-3 py-2 text-gray-400">...</span>';
        }
        echo '<a href="' . get_pagenum_link($max_pages) . '" class="pagination-btn page-number inline-flex items-center justify-center min-w-[40px] h-10 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">' . $max_pages . '</a>';
    }
    
    // Next button
    if ($paged < $max_pages) {
        echo '<a href="' . get_pagenum_link($paged + 1) . '" class="pagination-btn pagination-next flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-navy-500 focus:ring-offset-1">';
        echo __('Next', 'tostishop');
        echo '<svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
        echo '</a>';
    }
    
    echo '</div>';
    echo '</nav>';
}

/**
 * Filter WooCommerce pagination to use our enhanced version
 */
add_filter('woocommerce_pagination_args', 'tostishop_woocommerce_pagination_args');
function tostishop_woocommerce_pagination_args($args) {
    $args['prev_text'] = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>' . __('Previous', 'tostishop');
    $args['next_text'] = __('Next', 'tostishop') . '<svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
    $args['type'] = 'array';
    return $args;
}

// Remove add to cart buttons from all pages except single product
function tostishop_remove_add_to_cart_buttons() {
    // Remove ALL add to cart buttons from archive/shop pages
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    
    // Keep the add to cart functionality only on single product pages
    if (!is_product()) {
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    }
}
add_action('init', 'tostishop_remove_add_to_cart_buttons');

/**
 * Homepage Newsletter AJAX Handler
 */
function tostishop_newsletter_signup() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_nonce')) {
        wp_die(__('Security check failed', 'tostishop'));
    }
    
    $email = sanitize_email($_POST['email']);
    
    if (!is_email($email)) {
        wp_send_json_error(__('Please enter a valid email address', 'tostishop'));
        return;
    }
    
    // Check if email already exists
    $existing_subscriber = get_user_by('email', $email);
    if ($existing_subscriber) {
        wp_send_json_error(__('This email is already subscribed', 'tostishop'));
        return;
    }
    
    // Store newsletter subscription (you can integrate with Mailchimp, ConvertKit, etc.)
    $newsletter_data = array(
        'email' => $email,
        'date_subscribed' => current_time('mysql'),
        'source' => 'homepage_newsletter',
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'user_agent' => $_SERVER['HTTP_USER_AGENT']
    );
    
    // Save to WordPress options (basic storage) or your preferred newsletter service
    $newsletters = get_option('tostishop_newsletter_subscribers', array());
    $newsletters[] = $newsletter_data;
    update_option('tostishop_newsletter_subscribers', $newsletters);
    
    // Send welcome email (optional)
    $subject = __('Welcome to TostiShop Newsletter!', 'tostishop');
    $message = sprintf(
        __('Thank you for subscribing to our newsletter! You will receive updates about new products, offers, and exclusive deals at %s.', 'tostishop'),
        get_bloginfo('name')
    );
    
    wp_mail($email, $subject, $message);
    
    // Track newsletter signup event
    do_action('tostishop_newsletter_signup', $email, $newsletter_data);
    
    wp_send_json_success(__('Thank you for subscribing to our newsletter!', 'tostishop'));
}
add_action('wp_ajax_newsletter_signup', 'tostishop_newsletter_signup');
add_action('wp_ajax_nopriv_newsletter_signup', 'tostishop_newsletter_signup');

/**
 * Get Featured Products for Homepage
 */
function tostishop_get_featured_products($limit = 8) {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => '_featured',
                'value' => 'yes'
            )
        ),
        'post_status' => 'publish'
    );
    
    $featured_products = new WP_Query($args);
    
    // If no featured products, get latest products
    if (!$featured_products->have_posts()) {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $limit,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish'
        );
        $featured_products = new WP_Query($args);
    }
    
    return $featured_products;
}

/**
 * Get Products on Sale for Homepage
 */
function tostishop_get_sale_products($limit = 8) {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $limit,
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_sale_price',
                'value' => 0,
                'compare' => '>',
                'type' => 'NUMERIC'
            ),
            array(
                'key' => '_min_variation_sale_price',
                'value' => 0,
                'compare' => '>',
                'type' => 'NUMERIC'
            )
        ),
        'post_status' => 'publish'
    );
    
    return new WP_Query($args);
}

/**
 * Auto-setup theme logo on activation
 */
function tostishop_setup_logo() {
    $logo_path = get_template_directory() . '/assets/images/logo.png';
    
    if (file_exists($logo_path) && !get_theme_mod('custom_logo')) {
        // Upload logo to media library
        $upload_dir = wp_upload_dir();
        $image_data = file_get_contents($logo_path);
        $filename = basename($logo_path);
        
        if (wp_mkdir_p($upload_dir['path'])) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }
        
        file_put_contents($file, $image_data);
        
        $wp_filetype = wp_check_filetype($filename, null);
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        
        $attach_id = wp_insert_attachment($attachment, $file);
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);
        wp_update_attachment_metadata($attach_id, $attach_data);
        
        // Set as custom logo
        set_theme_mod('custom_logo', $attach_id);
    }
}
add_action('after_switch_theme', 'tostishop_setup_logo');

/**
 * Include Firebase Authentication Module
 */
require_once get_template_directory() . '/inc/firebase/init.php';
