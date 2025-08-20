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
    // Main stylesheet (compiled Tailwind CSS with integrated components)
    wp_enqueue_style('tostishop-style', get_stylesheet_uri(), array(), TOSTISHOP_VERSION);
    
    // Custom CSS for essential WooCommerce overrides only
    wp_enqueue_style('tostishop-custom', get_template_directory_uri() . '/assets/css/custom.css', array('tostishop-style'), TOSTISHOP_VERSION);
    
    // Alpine.js for interactivity (local version) - deferred loading reduces preload warnings
    wp_enqueue_script('alpinejs', get_template_directory_uri() . '/assets/js/alpine.min.js', array(), '3.14.9', true);
    wp_script_add_data('alpinejs', 'defer', true);
    
    // Custom JS
    wp_enqueue_script('tostishop-ui', get_template_directory_uri() . '/assets/js/ui.js', array('jquery'), TOSTISHOP_VERSION, true);
    wp_enqueue_script('tostishop-theme', get_template_directory_uri() . '/assets/js/theme.js', array('jquery', 'tostishop-ui'), TOSTISHOP_VERSION, true);
    
    // Add shipping calculator script ONLY on cart page (not checkout)
    if (is_cart()) {
        wp_enqueue_script(
            'tostishop-shipping-calculator',
            get_template_directory_uri() . '/assets/js/shipping-calculator.js',
            array(),
            TOSTISHOP_VERSION,
            true
        );
    }
    
    // Localize AJAX data for main UI script
    wp_localize_script('tostishop-ui', 'tostishop_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('tostishop_nonce'),
    ));
    
    // Page-specific assets
    tostishop_enqueue_page_specific_assets();
    
    // Firebase Authentication is now handled by /inc/firebase/enqueue.php
    // when Firebase module is loaded conditionally from functions.php
}
add_action('wp_enqueue_scripts', 'tostishop_scripts');

/**
 * Enqueue page-specific assets
 */
function tostishop_enqueue_page_specific_assets() {
    // Homepage specific styles and scripts
    if (is_page_template('page-home.php') || is_front_page()) {
        wp_enqueue_style('tostishop-homepage', get_template_directory_uri() . '/assets/css/homepage.css', array('tostishop-style'), TOSTISHOP_VERSION);
        wp_enqueue_script('tostishop-homepage', get_template_directory_uri() . '/assets/js/homepage.js', array('jquery'), TOSTISHOP_VERSION, true);
    }
    
    // Checkout specific JS (no shipping calculator)
    if (is_checkout()) {
        wp_enqueue_script('tostishop-checkout', get_template_directory_uri() . '/assets/js/checkout.js', array('jquery', 'wc-checkout'), TOSTISHOP_VERSION, true);
    }
    
    // Cart specific JS (with shipping calculator)
    if (is_cart()) {
        wp_enqueue_script('tostishop-cart', get_template_directory_uri() . '/assets/js/cart.js', array('jquery'), TOSTISHOP_VERSION, true);
        
        // Localize cart script specifically
        wp_localize_script('tostishop-cart', 'tostishop_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('tostishop_nonce'),
        ));
    }
    
    // Order confirmation specific JS
    if (is_order_received_page() || is_wc_endpoint_url('order-received')) {
        wp_enqueue_script('tostishop-order-confirmation', get_template_directory_uri() . '/assets/js/order-confirmation.js', array(), TOSTISHOP_VERSION, true);
    }
}

// Firebase Authentication is now handled by /inc/firebase/enqueue.php
// when Firebase module is loaded conditionally from functions.php
