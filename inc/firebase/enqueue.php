<?php
/**
 * Firebase Script Enqueue
 * TostiShop Theme - Firebase JavaScript and CSS Loading
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue Firebase scripts and configuration
 */
function tostishop_enqueue_firebase_scripts() {
    // Only enqueue if Firebase is configured
    if (!tostishop_is_firebase_configured()) {
        return;
    }
    
    // Get Firebase configuration
    $firebase_config = tostishop_get_firebase_config();
    
    // Firebase SDK scripts
    wp_enqueue_script(
        'firebase-app', 
        'https://www.gstatic.com/firebasejs/10.7.0/firebase-app-compat.js', 
        array(), 
        '10.7.0', 
        true
    );
    
    wp_enqueue_script(
        'firebase-auth', 
        'https://www.gstatic.com/firebasejs/10.7.0/firebase-auth-compat.js', 
        array('firebase-app'), 
        '10.7.0', 
        true
    );
    
    // Custom Firebase authentication script (UPDATED VERSION FOR CUSTOM FORM)
    wp_enqueue_script(
        'tostishop-firebase-auth', 
        get_template_directory_uri() . '/assets/js/firebase-auth-updated.js', 
        array('firebase-app', 'firebase-auth', 'jquery'), 
        '1.0.1', 
        true
    );
    
    // Firebase custom CSS
    wp_enqueue_style(
        'tostishop-firebase-auth', 
        get_template_directory_uri() . '/assets/css/firebase-auth.css', 
        array(), 
        '1.0.0'
    );
    
    // Localize Firebase configuration and AJAX settings
    wp_localize_script('tostishop-firebase-auth', 'tostiShopFirebaseConfig', $firebase_config);
    
    wp_localize_script('tostishop-firebase-auth', 'tostiShopAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('tostishop_firebase_nonce'),
        'redirectUrl' => is_checkout() ? wc_get_checkout_url() : (is_account_page() ? wc_get_page_permalink('myaccount') : home_url()),
        'strings' => array(
            'loginSuccess' => __('Login successful! Redirecting...', 'tostishop'),
            'loginError' => __('Login failed. Please try again.', 'tostishop'),
            'otpSent' => __('OTP sent to your phone. Please check your messages.', 'tostishop'),
            'otpError' => __('Failed to send OTP. Please try again.', 'tostishop'),
            'invalidOtp' => __('Invalid OTP. Please try again.', 'tostishop'),
            'phoneRequired' => __('Please enter a valid phone number.', 'tostishop'),
            'emailRequired' => __('Please enter a valid email address.', 'tostishop'),
            'passwordRequired' => __('Please enter a password.', 'tostishop'),
        )
    ));
}

/**
 * Add Firebase scripts only on relevant pages
 */
function tostishop_firebase_conditional_scripts() {
    // Only load on checkout, account, and cart pages, or when user is not logged in
    if (is_checkout() || is_account_page() || is_cart() || !is_user_logged_in()) {
        tostishop_enqueue_firebase_scripts();
    }
}
add_action('wp_enqueue_scripts', 'tostishop_firebase_conditional_scripts');
