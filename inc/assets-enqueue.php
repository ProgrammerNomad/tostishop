<?php
/**
 * TostiShop Assets & Scripts Enqueue
 * Handle all CSS and JavaScript loading
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue scripts and styles
 */
function tostishop_scripts() {
    // Main stylesheet (compiled Tailwind CSS)
    wp_enqueue_style('tostishop-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Custom CSS for enhanced functionality
    wp_enqueue_style('tostishop-custom', get_template_directory_uri() . '/assets/css/custom.css', array('tostishop-style'), '1.0.0');
    
    // Account components CSS for My Account pages
    if (is_account_page()) {
        wp_enqueue_style('tostishop-account-components', get_template_directory_uri() . '/assets/css/components/account-components.css', array('tostishop-style'), '1.0.0');
    }
    
    // Alpine.js for interactivity
    wp_enqueue_script('alpinejs', 'https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js', array(), '3.0.0', true);
    wp_script_add_data('alpinejs', 'defer', true);
    
    // Custom JS
    wp_enqueue_script('tostishop-ui', get_template_directory_uri() . '/assets/js/ui.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('tostishop-theme', get_template_directory_uri() . '/assets/js/theme.js', array('jquery', 'tostishop-ui'), '1.0.0', true);
    
    // Page-specific assets
    tostishop_enqueue_page_specific_assets();
    
    // Firebase Authentication (India-specific)
    if (is_account_page() || is_checkout()) {
        tostishop_enqueue_firebase_auth();
    }
}
add_action('wp_enqueue_scripts', 'tostishop_scripts');

/**
 * Enqueue page-specific assets
 */
function tostishop_enqueue_page_specific_assets() {
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
}

/**
 * Enqueue Firebase Authentication Scripts for India - Updated v3.0
 */
function tostishop_enqueue_firebase_auth() {
    wp_enqueue_script('firebase-app', 'https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js', [], '9.6.1', true);
    wp_enqueue_script('firebase-auth', 'https://www.gstatic.com/firebasejs/9.6.1/firebase-auth-compat.js', ['firebase-app'], '9.6.1', true);
    
    wp_enqueue_script('tostishop-firebase-auth', get_template_directory_uri() . '/assets/js/firebase-auth-updated.js', ['jquery', 'firebase-auth'], '1.0.1', true);
    
    // Firebase configuration
    $firebase_config = [
        'apiKey'            => get_option('tostishop_firebase_api_key', ''),
        'authDomain'        => get_option('tostishop_firebase_auth_domain', ''),
        'projectId'         => get_option('tostishop_firebase_project_id', ''),
        'messagingSenderId' => get_option('tostishop_firebase_sender_id', ''),
        'appId'             => get_option('tostishop_firebase_app_id', ''),
        'devMode'           => defined('WP_DEBUG') && WP_DEBUG,
    ];
    
    // Create dev mode flag for test phone numbers - FIX: Use array for boolean value
    $dev_mode = (defined('WP_DEBUG') && WP_DEBUG) || isset($_GET['dev_mode']);
    
    wp_localize_script('tostishop-firebase-auth', 'tostiShopFirebaseConfig', $firebase_config);
    wp_localize_script('tostishop-firebase-auth', 'tostiShopDevMode', ['enabled' => $dev_mode]);
    wp_localize_script('tostishop-firebase-auth', 'tostiShopAjax', [
        'ajaxurl'      => admin_url('admin-ajax.php'),
        'redirectUrl'  => get_permalink(get_option('woocommerce_myaccount_page_id')),
        'nonce'        => wp_create_nonce('tostishop_firebase_nonce'),
        'strings'      => [
            'invalidOtp'     => __('Invalid OTP. Please try again.', 'tostishop'),
            'phoneRequired'  => __('Please enter a valid phone number.', 'tostishop'),
        ]
    ]);
}
