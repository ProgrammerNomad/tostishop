<?php
/**
 * Debug script to test shop filters functionality
 * Load this in WordPress admin or via URL parameter for testing
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Test shop filters functionality
 */
function tostishop_debug_shop_filters() {
    if (!current_user_can('manage_options')) {
        return 'Access denied';
    }

    $debug_output = array();
    
    // Test 1: Check if WooCommerce is active
    $debug_output['woocommerce_active'] = function_exists('wc_get_products');
    
    // Test 2: Get total product count
    if ($debug_output['woocommerce_active']) {
        $all_products = wc_get_products(array('status' => 'publish', 'limit' => -1));
        $debug_output['total_products'] = count($all_products);
        
        // Get sample product prices
        $sample_prices = array();
        foreach (array_slice($all_products, 0, 10) as $product) {
            $sample_prices[] = array(
                'name' => $product->get_name(),
                'regular_price' => $product->get_regular_price(),
                'sale_price' => $product->get_sale_price(),
                'price' => $product->get_price()
            );
        }
        $debug_output['sample_prices'] = $sample_prices;
    }
    
    // Test 3: Test price ranges function
    if (function_exists('tostishop_get_price_ranges_with_counts')) {
        $debug_output['price_ranges'] = tostishop_get_price_ranges_with_counts();
    }
    
    // Test 4: Test categories function
    if (function_exists('tostishop_get_filter_categories')) {
        $debug_output['filter_categories'] = tostishop_get_filter_categories();
    }
    
    // Test 5: Test brands function (if exists)
    if (function_exists('tostishop_get_filter_brands')) {
        $debug_output['filter_brands'] = tostishop_get_filter_brands();
    }
    
    return $debug_output;
}

/**
 * Output debug info as JSON (for AJAX testing)
 */
function tostishop_debug_filters_ajax() {
    if (!current_user_can('manage_options')) {
        wp_die('Access denied');
    }
    
    $debug_data = tostishop_debug_shop_filters();
    
    wp_send_json_success($debug_data);
}

// Add AJAX handlers
add_action('wp_ajax_tostishop_debug_filters', 'tostishop_debug_filters_ajax');

/**
 * Display debug info if URL parameter is set
 * Usage: yoursite.com/shop?debug_filters=1 (must be logged in as admin)
 */
function tostishop_maybe_show_debug_filters() {
    if (isset($_GET['debug_filters']) && $_GET['debug_filters'] == '1' && current_user_can('manage_options')) {
        $debug_data = tostishop_debug_shop_filters();
        
        echo '<div style="background: #f9f9f9; border: 2px solid #ddd; padding: 20px; margin: 20px 0; font-family: monospace; white-space: pre-wrap; max-height: 400px; overflow-y: auto;">';
        echo '<h3>TostiShop Filters Debug Information</h3>';
        echo json_encode($debug_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        echo '</div>';
    }
}

// Hook into shop pages
add_action('woocommerce_before_shop_loop', 'tostishop_maybe_show_debug_filters');
add_action('woocommerce_before_single_product_summary', 'tostishop_maybe_show_debug_filters');
