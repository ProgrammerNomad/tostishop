<?php
/**
 * TostiShop Shiprocket Integration
 * 
 * Integrates Shiprocket shipping services with TostiShop theme
 * Features: Pincode serviceability check, rate calculation, and checkout integration
 * 
 * @package TostiShop
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize Shiprocket Integration
 */
function tostishop_shiprocket_init() {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    // Add pincode check to single product page
    add_action('woocommerce_single_product_summary', 'tostishop_shiprocket_pincode_check', 25);
    
    // Add AJAX handlers
    add_action('wp_ajax_tostishop_check_pincode', 'tostishop_ajax_check_pincode');
    add_action('wp_ajax_nopriv_tostishop_check_pincode', 'tostishop_ajax_check_pincode');
    
    // Enqueue scripts for pincode check
    add_action('wp_enqueue_scripts', 'tostishop_shiprocket_scripts');
}
add_action('init', 'tostishop_shiprocket_init');

/**
 * Enqueue Shiprocket scripts and styles
 */
function tostishop_shiprocket_scripts() {
    if (is_product()) {
        wp_enqueue_script(
            'tostishop-shiprocket',
            get_template_directory_uri() . '/assets/js/shiprocket.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
        
        wp_localize_script('tostishop-shiprocket', 'tostishop_shiprocket_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('tostishop_shiprocket_nonce'),
        ));
    }
}

/**
 * Display pincode serviceability check on product pages
 */
function tostishop_shiprocket_pincode_check() {
    // Check if pincode check is enabled
    $show_pincode_check = get_option('tostishop_shiprocket_show_pincode_check', 'no');
    if ($show_pincode_check !== 'yes') {
        return;
    }
    
    // Check if we have a valid token
    $token = get_option('tostishop_shiprocket_token', '');
    if (empty($token)) {
        return;
    }
    
    global $product;
    
    // Only show for products that need shipping
    if (!$product || !$product->needs_shipping()) {
        return;
    }
    ?>
    
    <div id="tostishop-pincode-check" class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <div class="flex items-center mb-3">
            <svg class="w-5 h-5 text-navy-900 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-navy-900">Check Delivery Availability</h3>
        </div>
        
        <p class="text-sm text-gray-600 mb-4">
            Enter your pincode to check if delivery is available in your area and get estimated delivery time.
        </p>
        
        <div class="flex gap-3 items-end">
            <div class="flex-1">
                <label for="shiprocket-pincode" class="block text-sm font-medium text-gray-700 mb-1">
                    Enter Pincode
                </label>
                <input 
                    type="text" 
                    id="shiprocket-pincode" 
                    name="shiprocket_pincode"
                    placeholder="e.g., 110001"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-900 focus:border-transparent text-center font-mono text-lg"
                />
            </div>
            <button 
                type="button" 
                id="check-pincode-btn"
                class="px-6 py-2 bg-navy-900 text-white font-medium rounded-md hover:bg-navy-800 focus:outline-none focus:ring-2 focus:ring-navy-900 focus:ring-offset-2 transition-colors duration-200 min-w-[120px]"
            >
                <span class="btn-text">Check</span>
                <svg class="btn-spinner hidden animate-spin w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
        
        <div id="pincode-result" class="mt-4 hidden">
            <!-- Results will be populated via JavaScript -->
        </div>
    </div>
    
    <?php
}

/**
 * AJAX handler for pincode serviceability check
 */
function tostishop_ajax_check_pincode() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_shiprocket_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    $pincode = sanitize_text_field($_POST['pincode']);
    $product_id = intval($_POST['product_id']);
    
    // Validate pincode
    if (empty($pincode) || !preg_match('/^[0-9]{6}$/', $pincode)) {
        wp_send_json_error('Please enter a valid 6-digit pincode');
        return;
    }
    
    // Get product
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error('Product not found');
        return;
    }
    
    // Check serviceability
    $result = tostishop_check_pincode_serviceability($pincode, $product);
    
    if ($result['success']) {
        wp_send_json_success($result['data']);
    } else {
        wp_send_json_error($result['message']);
    }
}

/**
 * Check pincode serviceability with Shiprocket API
 * 
 * @param string $pincode The pincode to check
 * @param WC_Product $product The product object
 * @return array Result array with success status and data/message
 */
function tostishop_check_pincode_serviceability($pincode, $product) {
    $token = get_option('tostishop_shiprocket_token', '');
    
    if (empty($token)) {
        return array(
            'success' => false,
            'message' => 'Shiprocket not configured'
        );
    }
    
    // Prepare API request
    $pickup_postcode = get_option('woocommerce_store_postcode', '');
    $weight = $product->get_weight() ?: 0.5; // Default weight if not set
    $cod = 1; // Assume COD is available
    $declared_value = $product->get_price();
    
    $api_url = 'https://apiv2.shiprocket.in/v1/external/courier/serviceability/';
    
    $response = wp_remote_get($api_url . '?' . http_build_query(array(
        'pickup_postcode' => $pickup_postcode,
        'delivery_postcode' => $pincode,
        'weight' => $weight,
        'cod' => $cod,
        'declared_value' => $declared_value
    )), array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ),
        'timeout' => 15
    ));
    
    if (is_wp_error($response)) {
        return array(
            'success' => false,
            'message' => 'Unable to check serviceability. Please try again.'
        );
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    if (empty($data['data']['available_courier_companies'])) {
        return array(
            'success' => false,
            'message' => 'Delivery not available to this pincode'
        );
    }
    
    // Process courier data
    $couriers = $data['data']['available_courier_companies'];
    $show_top_courier = get_option('tostishop_shiprocket_show_top_courier', 'yes');
    
    if ($show_top_courier === 'yes') {
        // Sort by rating and limit to top 5
        usort($couriers, function($a, $b) {
            return ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0);
        });
        $couriers = array_slice($couriers, 0, 5);
    }
    
    // Find the best option (lowest cost with good rating)
    $best_courier = null;
    $min_cost = PHP_INT_MAX;
    
    foreach ($couriers as $courier) {
        $cost = floatval($courier['rate'] ?? 0);
        $rating = floatval($courier['rating'] ?? 0);
        
        if ($cost < $min_cost && $rating >= 3.5) {
            $min_cost = $cost;
            $best_courier = $courier;
        }
    }
    
    // Fallback to first courier if no good rated courier found
    if (!$best_courier && !empty($couriers)) {
        $best_courier = $couriers[0];
    }
    
    if (!$best_courier) {
        return array(
            'success' => false,
            'message' => 'No suitable courier service found'
        );
    }
    
    return array(
        'success' => true,
        'data' => array(
            'available' => true,
            'pincode' => $pincode,
            'courier_name' => $best_courier['courier_name'] ?? 'Standard Delivery',
            'estimated_delivery_days' => $best_courier['estimated_delivery_days'] ?? '3-5',
            'shipping_cost' => $best_courier['rate'] ?? 0,
            'cod_available' => !empty($best_courier['cod']),
            'all_couriers' => $couriers
        )
    );
}

/**
 * Get Shiprocket shipping rates for checkout
 * 
 * @param string $pincode Delivery pincode
 * @param float $weight Package weight
 * @param array $dimensions Package dimensions
 * @param float $declared_value Order value
 * @return array Array of shipping rates
 */
function tostishop_get_shiprocket_rates($pincode, $weight, $dimensions, $declared_value) {
    $token = get_option('tostishop_shiprocket_token', '');
    
    if (empty($token)) {
        return array();
    }
    
    $pickup_postcode = get_option('woocommerce_store_postcode', '');
    
    $api_url = 'https://apiv2.shiprocket.in/v1/external/courier/serviceability/';
    
    $response = wp_remote_get($api_url . '?' . http_build_query(array(
        'pickup_postcode' => $pickup_postcode,
        'delivery_postcode' => $pincode,
        'weight' => $weight,
        'cod' => 1,
        'declared_value' => $declared_value
    )), array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ),
        'timeout' => 15
    ));
    
    if (is_wp_error($response)) {
        return array();
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    if (empty($data['data']['available_courier_companies'])) {
        return array();
    }
    
    $couriers = $data['data']['available_courier_companies'];
    $show_top_courier = get_option('tostishop_shiprocket_show_top_courier', 'yes');
    
    if ($show_top_courier === 'yes') {
        // Sort by rating and limit to top 5
        usort($couriers, function($a, $b) {
            return ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0);
        });
        $couriers = array_slice($couriers, 0, 5);
    }
    
    $rates = array();
    foreach ($couriers as $courier) {
        $rates[] = array(
            'id' => sanitize_title($courier['courier_name'] ?? 'shiprocket'),
            'name' => $courier['courier_name'] ?? 'Shiprocket Shipping',
            'cost' => floatval($courier['rate'] ?? 0),
            'estimated_days' => $courier['estimated_delivery_days'] ?? '3-5'
        );
    }
    
    return $rates;
}

// Initialize all Shiprocket hooks and functionality
tostishop_shiprocket_init();
