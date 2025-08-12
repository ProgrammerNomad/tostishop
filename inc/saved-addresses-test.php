<?php
/**
 * Saved Addresses Feature Test File
 * 
 * This file contains test functions to verify the saved addresses feature
 * is working correctly with the TostiShop theme.
 * 
 * @package TostiShop
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Test the saved addresses feature functionality
 */
function tostishop_test_saved_addresses() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    echo '<div class="notice notice-info"><p>';
    echo '<strong>TostiShop Saved Addresses Feature Test Results:</strong><br>';
    
    // Test 1: Check if main class exists
    if (class_exists('TostiShop_Saved_Addresses')) {
        echo '✅ Main TostiShop_Saved_Addresses class exists<br>';
    } else {
        echo '❌ Main TostiShop_Saved_Addresses class missing<br>';
    }
    
    // Test 2: Check if database table exists
    global $wpdb;
    $table_name = $wpdb->prefix . 'tostishop_saved_addresses';
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
    
    if ($table_exists) {
        echo '✅ Database table exists<br>';
    } else {
        echo '❌ Database table missing<br>';
    }
    
    // Test 3: Check if installation class exists
    if (class_exists('TostiShop_Saved_Addresses_Install')) {
        echo '✅ Installation class exists<br>';
    } else {
        echo '❌ Installation class missing<br>';
    }
    
    // Test 4: Check if templates exist
    $template_files = [
        'woocommerce/myaccount/address-book.php',
        'woocommerce/checkout/saved-addresses.php'
    ];
    
    foreach ($template_files as $template) {
        $file_path = get_template_directory() . '/' . $template;
        if (file_exists($file_path)) {
            echo "✅ Template exists: $template<br>";
        } else {
            echo "❌ Template missing: $template<br>";
        }
    }
    
    // Test 5: Check if JavaScript file exists
    $js_file = get_template_directory() . '/assets/js/saved-addresses.js';
    if (file_exists($js_file)) {
        echo '✅ JavaScript file exists<br>';
    } else {
        echo '❌ JavaScript file missing<br>';
    }
    
    // Test 6: Check if AJAX actions are registered
    if (has_action('wp_ajax_tostishop_save_address')) {
        echo '✅ AJAX actions registered<br>';
    } else {
        echo '❌ AJAX actions not registered<br>';
    }
    
    // Test 7: Check if WooCommerce integration is active
    if (function_exists('WC') && is_plugin_active('woocommerce/woocommerce.php')) {
        echo '✅ WooCommerce is active<br>';
    } else {
        echo '❌ WooCommerce is not active<br>';
    }
    
    echo '</p></div>';
}

/**
 * Add test function to admin menu for easy access
 */
function tostishop_add_test_menu() {
    if (current_user_can('manage_options')) {
        add_action('admin_notices', 'tostishop_test_saved_addresses');
    }
}

// Only run tests in admin and if specifically requested
if (is_admin() && isset($_GET['test_saved_addresses']) && current_user_can('manage_options')) {
    add_action('admin_init', 'tostishop_add_test_menu');
}
