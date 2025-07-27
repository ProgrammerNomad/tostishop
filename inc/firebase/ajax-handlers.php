<?php
/**
 * Firebase Authentication AJAX Handlers
 * Handle Firebase authentication AJAX requests
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle Firebase login AJAX request
 */
function tostishop_handle_firebase_login() {
    // Add debug logging
    error_log('Firebase login attempt with data: ' . print_r($_POST, true));
    
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tostishop_firebase_nonce')) {
        wp_send_json_error(array(
            'message' => 'Security check failed',
            'code' => 'nonce_failed'
        ));
        return;
    }
    
    // Check if token exists
    if (!isset($_POST['firebase_token']) || empty($_POST['firebase_token'])) {
        wp_send_json_error(array(
            'message' => 'Authentication token missing',
            'code' => 'token_missing'
        ));
        return;
    }
    
    // Get request data
    $firebase_token = sanitize_text_field($_POST['firebase_token']);
    $auth_method = isset($_POST['auth_method']) ? sanitize_text_field($_POST['auth_method']) : 'unknown';
    $from_checkout = isset($_POST['from_checkout']) && $_POST['from_checkout'] === 'true';
    
    try {
        // Verify Firebase token
        $user_data = tostishop_verify_firebase_token($firebase_token);
        
        if (!$user_data) {
            wp_send_json_error(array(
                'message' => 'Invalid authentication token',
                'code' => 'token_invalid'
            ));
            return;
        }
        
        // Create or get WordPress user
        $wp_user = tostishop_get_or_create_user_from_firebase($user_data, $auth_method);
        
        if (is_wp_error($wp_user)) {
            wp_send_json_error(array(
                'message' => $wp_user->get_error_message(),
                'code' => $wp_user->get_error_code()
            ));
            return;
        }
        
        // Log the user in
        wp_set_auth_cookie($wp_user->ID, true);
        
        // Set user metadata
        update_user_meta($wp_user->ID, 'last_login', current_time('mysql'));
        
        // Determine redirect URL
        $redirect_url = $from_checkout ? wc_get_checkout_url() : get_permalink(get_option('woocommerce_myaccount_page_id'));
        
        // Send success response
        wp_send_json_success(array(
            'message' => 'Login successful',
            'redirect_url' => $redirect_url,
            'user_id' => $wp_user->ID
        ));
        
    } catch (Exception $e) {
        error_log('Firebase authentication error: ' . $e->getMessage());
        wp_send_json_error(array(
            'message' => 'Authentication failed: ' . $e->getMessage(),
            'code' => 'firebase_auth_failed'
        ));
    }
}
// Register AJAX handlers
add_action('wp_ajax_nopriv_tostishop_firebase_login', 'tostishop_handle_firebase_login');
add_action('wp_ajax_tostishop_firebase_login', 'tostishop_handle_firebase_login');
