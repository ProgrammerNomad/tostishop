<?php
/**
 * TostiShop AJAX Handlers
 * All AJAX functionality for cart, products, and other interactions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add to cart via AJAX
 */
function tostishop_ajax_add_to_cart() {
    // Check nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tostishop_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    // Check if required fields are present
    if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
        wp_send_json_error('Missing required fields');
        return;
    }
    
    $product_id = absint($_POST['product_id']);
    $quantity = absint($_POST['quantity']);
    
    // Validate product ID
    if ($product_id <= 0) {
        wp_send_json_error('Invalid product ID');
        return;
    }
    
    // Validate quantity
    if ($quantity <= 0) {
        $quantity = 1;
    }
    
    try {
        $result = WC()->cart->add_to_cart($product_id, $quantity);
        
        if ($result) {
            wp_send_json_success(array(
                'message' => 'Product added to cart successfully',
                'cart_count' => WC()->cart->get_cart_contents_count(),
                'cart_total' => WC()->cart->get_cart_total(),
            ));
        } else {
            wp_send_json_error('Failed to add product to cart');
        }
    } catch (Exception $e) {
        wp_send_json_error('Error: ' . $e->getMessage());
    }
}
add_action('wp_ajax_tostishop_add_to_cart', 'tostishop_ajax_add_to_cart');
add_action('wp_ajax_nopriv_tostishop_add_to_cart', 'tostishop_ajax_add_to_cart');

/**
 * Update cart item quantity via AJAX
 */
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
add_action('wp_ajax_tostishop_update_cart_item', 'tostishop_ajax_update_cart_item');
add_action('wp_ajax_nopriv_tostishop_update_cart_item', 'tostishop_ajax_update_cart_item');

/**
 * Remove cart item via AJAX
 */
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
add_action('wp_ajax_tostishop_remove_cart_item', 'tostishop_ajax_remove_cart_item');
add_action('wp_ajax_nopriv_tostishop_remove_cart_item', 'tostishop_ajax_remove_cart_item');

/**
 * Homepage Newsletter AJAX Handler
 */
function tostishop_newsletter_signup() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    $email = sanitize_email($_POST['email']);
    
    if (!is_email($email)) {
        wp_send_json_error('Invalid email address');
        return;
    }
    
    // Check if email already exists
    $existing_subscriber = get_user_by('email', $email);
    if ($existing_subscriber) {
        wp_send_json_error('Email already subscribed');
        return;
    }
    
    // Store newsletter subscription (you can integrate with Mailchimp, ConvertKit, etc.)
    $newsletter_data = array(
        'email' => $email,
        'date_subscribed' => current_time('mysql'),
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
        __('Thank you for subscribing to TostiShop newsletter! You\'ll receive updates about our latest products and exclusive offers.', 'tostishop'),
        $email
    );
    
    wp_mail($email, $subject, $message);
    
    // Track newsletter signup event
    do_action('tostishop_newsletter_signup', $email, $newsletter_data);
    
    wp_send_json_success(__('Thank you for subscribing to our newsletter!', 'tostishop'));
}
add_action('wp_ajax_newsletter_signup', 'tostishop_newsletter_signup');
add_action('wp_ajax_nopriv_newsletter_signup', 'tostishop_newsletter_signup');
