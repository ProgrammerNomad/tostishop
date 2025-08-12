<?php
/**
 * WooCommerce Customizations
 * 
 * @package TostiShop
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove default checkout hooks that conflict with our address system
 */
function tostishop_checkout_customizations() {
    // Remove checkout customizations that interfere with our address system
    remove_action('woocommerce_before_checkout_billing_form', 'tostishop_amazon_style_address_picker', 5);
}
add_action('init', 'tostishop_checkout_customizations');

/**
 * Remove old Amazon-style address picker - replaced with simpler template approach
 */

/**
 * Enqueue scripts for checkout address picker
 */
function tostishop_enqueue_checkout_address_scripts() {
    if (is_checkout()) {
        wp_enqueue_script('alpine-js', get_template_directory_uri() . '/assets/js/alpine.min.js', array(), '3.13.0', true);
    }
}

/**
 * AJAX handler for saving new checkout address
 */
function tostishop_ajax_save_checkout_address() {
    check_ajax_referer('tostishop_checkout_nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('You must be logged in to save addresses.', 'tostishop')));
    }
    
    global $tostishop_saved_addresses;
    if (!$tostishop_saved_addresses) {
        $tostishop_saved_addresses = new TostiShop_Saved_Addresses();
    }
    
    $address_data = array(
        'address_type' => sanitize_text_field($_POST['address_type']),
        'address_name' => sanitize_text_field($_POST['address_name']),
        'first_name' => sanitize_text_field($_POST['first_name']),
        'last_name' => sanitize_text_field($_POST['last_name']),
        'company' => sanitize_text_field($_POST['company']),
        'address_1' => sanitize_text_field($_POST['address_1']),
        'address_2' => sanitize_text_field($_POST['address_2']),
        'city' => sanitize_text_field($_POST['city']),
        'state' => sanitize_text_field($_POST['state']),
        'postcode' => sanitize_text_field($_POST['postcode']),
        'country' => sanitize_text_field($_POST['country']),
        'phone' => sanitize_text_field($_POST['phone']),
        'email' => sanitize_email($_POST['email']),
        'is_default' => 0 // New addresses are not default by default
    );
    
    $result = $tostishop_saved_addresses->save_address($address_data);
    
    if ($result) {
        wp_send_json_success(array(
            'message' => __('Address saved successfully.', 'tostishop'),
            'address_id' => $result
        ));
    } else {
        wp_send_json_error(array('message' => __('Failed to save address.', 'tostishop')));
    }
}

// Hook the AJAX handler
add_action('wp_ajax_tostishop_save_checkout_address', 'tostishop_ajax_save_checkout_address');

/**
 * Remove shipping fields from checkout
 */
function tostishop_remove_shipping_fields($fields) {
    // Remove the shipping fields if we don't want them displayed
    if (isset($fields['shipping'])) {
        unset($fields['shipping']);
    }
    return $fields;
}

/**
 * Auto copy billing address to shipping address
 */
function tostishop_auto_copy_billing_to_shipping() {
    if (!is_admin() && is_checkout()) {
        // Copy billing to shipping fields on checkout processing
        $billing_fields = array(
            'first_name', 'last_name', 'company', 'address_1', 'address_2',
            'city', 'state', 'postcode', 'country', 'phone'
        );
        
        foreach ($billing_fields as $field) {
            if (isset($_POST['billing_' . $field])) {
                $_POST['shipping_' . $field] = $_POST['billing_' . $field];
            }
        }
    }
}
add_action('woocommerce_checkout_process', 'tostishop_auto_copy_billing_to_shipping');

/**
 * Disable WooCommerce block styles properly
 */
function tostishop_disable_wc_block_styles() {
    wp_dequeue_style('wc-blocks-style');
    wp_deregister_style('wc-blocks-style');
}
add_action('wp_enqueue_scripts', 'tostishop_disable_wc_block_styles', 100);

/**
 * WooCommerce customizations for TostiShop
 */
function tostishop_woocommerce_customizations() {
    // Remove default WooCommerce styling
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    
    // Remove WooCommerce generator tag
    remove_action('wp_head', array('WC', 'generator'));
    
    // Modify breadcrumbs
    add_filter('woocommerce_breadcrumb_defaults', 'tostishop_breadcrumbs');
    
    // Custom add to cart functionality
    add_action('wp_ajax_woocommerce_add_to_cart', 'tostishop_ajax_add_to_cart');
    add_action('wp_ajax_nopriv_woocommerce_add_to_cart', 'tostishop_ajax_add_to_cart');
}
add_action('after_setup_theme', 'tostishop_woocommerce_customizations');

/**
 * Product link opening tag
 */
function tostishop_product_link_open() {
    global $product;
    
    $link = apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product);
    
    echo '<a href="' . esc_url($link) . '" class="tostishop-product-link">';
}

/**
 * Product link closing tag
 */
function tostishop_product_link_close() {
    echo '</a>';
}

/**
 * Custom product title
 */
function tostishop_product_title() {
    echo '<h3 class="tostishop-product-title">' . get_the_title() . '</h3>';
}

/**
 * Remove default add to cart buttons from shop page
 */
function tostishop_remove_add_to_cart_buttons() {
    if (is_shop() || is_product_category() || is_product_tag()) {
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    }
}
add_action('init', 'tostishop_remove_add_to_cart_buttons');

/**
 * Custom breadcrumbs
 */
function tostishop_breadcrumbs($args) {
    $args['delimiter'] = ' <span class="breadcrumb-separator">/</span> ';
    $args['wrap_before'] = '<nav class="tostishop-breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
    $args['wrap_after'] = '</nav>';
    $args['before'] = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
    $args['after'] = '</span>';
    $args['home'] = _x('Home', 'breadcrumb', 'tostishop');
    
    return $args;
}

/**
 * Get cart count
 */
function tostishop_cart_count() {
    if (WC()->cart) {
        return WC()->cart->get_cart_contents_count();
    }
    return 0;
}

/**
 * Get cart total
 */
function tostishop_cart_total() {
    if (WC()->cart) {
        return WC()->cart->get_cart_total();
    }
    return wc_price(0);
}

/**
 * Custom order confirmation email text
 */
function tostishop_order_confirmation_email_text($text, $order) {
    if ($order && is_a($order, 'WC_Order')) {
        $custom_text = sprintf(
            __('Thank you for your order #%s! We\'ll send you tracking information once your order ships.', 'tostishop'),
            $order->get_order_number()
        );
        return $custom_text;
    }
    return $text;
}
add_filter('woocommerce_thankyou_order_received_text', 'tostishop_order_confirmation_email_text', 10, 2);

/**
 * Add order tracking information
 */
function tostishop_add_order_tracking_info($order_id) {
    if (!$order_id) return;
    
    $order = wc_get_order($order_id);
    if (!$order) return;
    
    // Add custom tracking info section
    echo '<div class="tostishop-tracking-info">';
    echo '<h3>' . __('Track Your Order', 'tostishop') . '</h3>';
    echo '<p>' . __('You will receive an email with tracking information once your order ships.', 'tostishop') . '</p>';
    
    // Add estimated delivery date
    $estimated_delivery = date('F j, Y', strtotime('+5 business days'));
    echo '<p><strong>' . __('Estimated Delivery:', 'tostishop') . '</strong> ' . $estimated_delivery . '</p>';
    echo '</div>';
}
add_action('woocommerce_thankyou', 'tostishop_add_order_tracking_info', 20);

/**
 * Custom pagination for WooCommerce
 */
function tostishop_woocommerce_pagination_args($args) {
    $args['prev_text'] = __('← Previous', 'tostishop');
    $args['next_text'] = __('Next →', 'tostishop');
    $args['type'] = 'list';
    
    return $args;
}
add_filter('woocommerce_pagination_args', 'tostishop_woocommerce_pagination_args');

/**
 * Order confirmation page enhancements
 */
function tostishop_order_confirmation_enhancements() {
    if (!is_wc_endpoint_url('order-received')) {
        return;
    }
    
    // Add custom CSS for order confirmation
    wp_add_inline_style('tostishop-style', '
        .tostishop-tracking-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #e42029;
        }
        .tostishop-tracking-info h3 {
            margin-top: 0;
            color: #14175b;
        }
    ');
}
add_action('wp_footer', 'tostishop_order_confirmation_enhancements');

/**
 * Add structured data for order confirmation
 */
function tostishop_order_confirmation_structured_data() {
    if (is_wc_endpoint_url('order-received') && isset($_GET['key'])) {
        $order_id = wc_get_order_id_by_order_key($_GET['key']);
        $order = wc_get_order($order_id);
        
        if ($order && $order->get_status() !== 'failed') {
            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'Order',
                'orderNumber' => $order->get_order_number(),
                'orderStatus' => 'https://schema.org/OrderProcessing',
                'orderDate' => $order->get_date_created()->format('c'),
                'customer' => array(
                    '@type' => 'Person',
                    'name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                    'email' => $order->get_billing_email()
                ),
                'merchant' => array(
                    '@type' => 'Organization',
                    'name' => get_bloginfo('name'),
                    'url' => home_url()
                )
            );
            
            echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
        }
    }
}
add_action('wp_footer', 'tostishop_order_confirmation_structured_data');
