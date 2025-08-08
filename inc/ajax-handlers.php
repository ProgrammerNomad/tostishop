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
    
    // Get the product to check if it exists and is valid
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error('Product not found');
        return;
    }
    
    // Check if product is purchasable
    if (!$product->is_purchasable()) {
        wp_send_json_error('This product cannot be purchased');
        return;
    }
    
    // Check stock
    if (!$product->is_in_stock()) {
        wp_send_json_error('This product is out of stock');
        return;
    }
    
    // Check if we can add the requested quantity
    if ($product->managing_stock()) {
        $stock_quantity = $product->get_stock_quantity();
        $cart_quantity = WC()->cart->get_cart_item_quantities();
        $current_cart_quantity = isset($cart_quantity[$product_id]) ? $cart_quantity[$product_id] : 0;
        
        if (($current_cart_quantity + $quantity) > $stock_quantity) {
            $available = $stock_quantity - $current_cart_quantity;
            if ($available <= 0) {
                wp_send_json_error(sprintf('You cannot add another "%s" to your cart.', $product->get_name()));
                return;
            } else {
                wp_send_json_error(sprintf('You can only add %d more of "%s" to your cart.', $available, $product->get_name()));
                return;
            }
        }
    }
    
    try {
        $result = WC()->cart->add_to_cart($product_id, $quantity);
        
        if ($result) {
            // Success response
            wp_send_json_success(array(
                'message' => sprintf('"%s" has been added to your cart.', $product->get_name()),
                'cart_count' => WC()->cart->get_cart_contents_count(),
                'cart_total' => WC()->cart->get_cart_total(),
            ));
        } else {
            // Check for any WooCommerce notices that might explain the failure
            $notices = wc_get_notices('error');
            $error_message = 'Failed to add product to cart';
            
            if (!empty($notices)) {
                $error_message = $notices[0]['notice'];
                wc_clear_notices(); // Clear the notices since we're handling them via AJAX
            }
            
            wp_send_json_error($error_message);
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
