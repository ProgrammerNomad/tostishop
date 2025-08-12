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
            array('jquery'), // Add jQuery dependency
            wp_get_theme()->get('Version'),
            true
        );
        
        wp_localize_script('tostishop-shiprocket', 'tostishopShiprocket', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('tostishop_shiprocket_nonce'), // Fixed: Use consistent nonce name
            'productId' => get_the_ID(),
            'messages' => array(
                'checking' => __('Checking...', 'tostishop'),
                'enterPincode' => __('Please enter a pincode', 'tostishop'),
                'validPincode' => __('Please enter a valid 6-digit pincode', 'tostishop'),
                'error' => __('Error checking pincode. Please try again.', 'tostishop'),
            ),
        ));
    }
}

/**
 * Display pincode serviceability check on product pages
 */
function tostishop_shiprocket_pincode_check() {
    global $product;
    
    // Check if setting is enabled
    $show_pincode_check = get_option('tostishop_shiprocket_show_pincode_check', 'no');
    $token = get_option('tostishop_shiprocket_token', '');
    
    if ($show_pincode_check !== 'yes' || empty($token)) {
        return;
    }
    ?>
    
    <!-- Pure Class-Based Pincode Checker - Universal for Mobile & Desktop -->
    <div class="shiprocket-pincode-container mt-4 sm:mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200 w-full">
        <!-- Header with Icon -->
        <div class="flex items-center mb-3">
            <svg class="w-5 h-5 text-gray-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <h3 class="text-base font-medium text-gray-900">Check Delivery Availability</h3>
        </div>
        
        <!-- Description -->
        <p class="text-sm text-gray-600 mb-4">
            Enter your pincode to check if delivery is available in your area and get estimated delivery time.
        </p>
        
        <!-- Input and Button - Responsive Layout -->
        <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-3 sm:items-end">
            <!-- Input Field -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Enter Pincode
                </label>
                <input 
                    type="text" 
                    class="shiprocket-pincode-input w-full px-3 py-3 border border-gray-300 rounded-md text-center font-mono text-lg focus:outline-none focus:ring-2 focus:ring-navy-900 focus:border-transparent transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    name="shiprocket_pincode"
                    placeholder="e.g., 110001"
                    maxlength="6"
                    pattern="[0-9]{6}"
                />
            </div>
            
            <!-- Check Button -->
            <button 
                type="button" 
                class="shiprocket-check-button w-full sm:w-auto px-6 py-3 bg-navy-900 text-white font-medium rounded-md hover:bg-navy-800 focus:outline-none focus:ring-2 focus:ring-navy-900 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed min-w-[120px] flex items-center justify-center"
            >
                Check
            </button>
        </div>
        
        <!-- Response Area -->
        <div class="shiprocket-response-area mt-4 hidden">
            <!-- Results populated via JavaScript -->
        </div>
    </div>
    
    <?php
}

/**
 * AJAX handler for pincode serviceability check
 */
function tostishop_ajax_check_pincode() {
    // Verify nonce for security - FIXED: Use consistent nonce name
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_shiprocket_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    $pincode = sanitize_text_field($_POST['pincode']);
    $product_id = intval($_POST['product_id']);
    
    if (!$pincode || !$product_id) {
        wp_send_json_error('Invalid parameters');
        return;
    }
    
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error('Product not found');
        return;
    }
    
    // Get Shiprocket token
    $token = get_option('tostishop_shiprocket_token', '');
    if (empty($token)) {
        wp_send_json_error('Shiprocket not configured');
        return;
    }
    
    // Get product weight (default to 0.2 if not set) - FIXED: Proper fallback
    $weight = floatval($product->get_weight());
    if ($weight <= 0) {
        $weight = 0.2; // Default weight matching your API example
    }
    
    // Get pickup postcode from WooCommerce settings
    $pickup_postcode = get_option('woocommerce_store_postcode', '');
    
    // If no pickup postcode is set, use default - FIXED: Better fallback
    if (empty($pickup_postcode)) {
        $pickup_postcode = '201009'; // Use your working example pickup postcode
    }
    
    // Use the CORRECT Shiprocket API endpoint (matching your example)
    $endpoint_url = 'https://serviceability.shiprocket.in/courier/ratingserviceability';
    
    // Build query parameters EXACTLY matching your working example
    $params = array(
        'pickup_postcode' => $pickup_postcode,
        'delivery_postcode' => $pincode,
        'weight' => $weight,
        'cod' => '0',                    // Prepaid (matching your example)
        'declared_value' => floatval($product->get_price()) ?: 150, // Match example value
        'rate_calculator' => '1',
        'blocked' => '1',
        'is_return' => '0',
        'is_web' => '1',
        'is_dg' => '0',
        'only_qc_couriers' => '0',
        'length' => '12',               // Match your example dimensions
        'breadth' => '10',              // Match your example
        'height' => '10',
    );
    
    // Construct full URL
    $full_url = $endpoint_url . '?' . http_build_query($params);
    
    // Make API request with EXACT headers from your example
    $response = wp_remote_get($full_url, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ),
        'timeout' => 15,
    ));
    
    // Check for API errors
    if (is_wp_error($response)) {
        // Better error handling
        wp_send_json_error('Unable to check serviceability. Please try again.');
        return;
    }
    
    // Get response code
    $response_code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    
    // For debugging - log the full response for admins
    if (current_user_can('manage_options')) {
        error_log('Shiprocket API Response Code: ' . $response_code);
        error_log('Shiprocket API Response Body: ' . $body);
    }
    
    // Parse response
    $data = json_decode($body, true);
    
    // Check if API returned valid data (matching your response structure)
    if ($response_code !== 200 || !isset($data['status']) || $data['status'] !== 200) {
        // For admins, show detailed API response for debugging
        if (current_user_can('manage_options')) {
            wp_send_json_error('Debug - API Response Code: ' . $response_code . ' | Response: ' . substr($body, 0, 500) . (strlen($body) > 500 ? '...' : ''));
        } else {
            wp_send_json_error('Service not available for this pincode.');
        }
        return;
    }
    
    // Check if courier companies are available
    if (!isset($data['data']['available_courier_companies']) || empty($data['data']['available_courier_companies'])) {
        wp_send_json_error('No courier companies available for this pincode.');
        return;
    }
    
    $available_couriers = $data['data']['available_courier_companies'];
    
    // Process the response (matching original plugin logic)
    $result = tostishop_process_shiprocket_response($available_couriers);
    
    wp_send_json_success(array(
        'message' => $result['message'],
        'type' => $result['type']
    ));
}

/**
 * Process Shiprocket API response (updated for correct API structure)
 */
function tostishop_process_shiprocket_response($available_couriers) {
    // Check for quick delivery options (matching original plugin logic)
    $quick_couriers = array('Quick-Ola', 'Quick-Borzo', 'Quick-Flash', 'Quick-Qwqer', 'Quick-Mover', 'Quick-Porter', 'Loadshare Hyperlocal');
    
    foreach ($available_couriers as $courier) {
        if (in_array($courier['courier_name'], $quick_couriers)) {
            return array(
                'message' => "🚀 Don't wait! Order now and get it delivered to your doorstep within the next 2 hours.",
                'type' => 'express'
            );
        }
    }
    
    // Filter couriers with valid delivery days and not blocked/disabled
    $filtered_couriers = array_filter($available_couriers, function($courier) {
        return !empty($courier['estimated_delivery_days']) && 
               $courier['estimated_delivery_days'] > 0 &&
               $courier['blocked'] == 0 &&
               $courier['courier_disabled'] == 0;
    });
    
    if (!empty($filtered_couriers)) {
        // Sort by estimated delivery days (fastest first)
        usort($filtered_couriers, function($a, $b) {
            return intval($a['estimated_delivery_days']) - intval($b['estimated_delivery_days']);
        });
        
        $courier = $filtered_couriers[0]; // Get fastest courier
        $city = isset($courier['city']) ? $courier['city'] : 'your location';
        $days = intval($courier['estimated_delivery_days']);
        $courier_name = $courier['courier_name'];
        
        // Format the response message
        if ($days <= 1) {
            return array(
                'message' => sprintf(
                    "🚀 Same-day delivery available to %s! Order now with %s.",
                    esc_html($city),
                    esc_html($courier_name)
                ),
                'type' => 'express'
            );
        } else {
            return array(
                'message' => sprintf(
                    "✅ Fast delivery to %s! Your order arrives in just %d %s via %s.",
                    esc_html($city),
                    $days,
                    $days === 1 ? 'day' : 'days',
                    esc_html($courier_name)
                ),
                'type' => 'standard'
            );
        }
    }
    
    // Fallback message
    return array(
        'message' => "✅ Delivery available to your location.",
        'type' => 'standard'
    );
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
    $pickup_postcode = get_option('woocommerce_store_postcode', '201009'); // Use working postcode as fallback
    $weight = $product->get_weight() ?: 0.2; // Default weight if not set
    $cod = 1; // Assume COD is available
    $declared_value = $product->get_price();
    
    // FIXED: Use correct API endpoint
    $api_url = 'https://serviceability.shiprocket.in/courier/ratingserviceability';
    
    $response = wp_remote_get($api_url . '?' . http_build_query(array(
        'pickup_postcode' => $pickup_postcode,
        'delivery_postcode' => $pincode,
        'weight' => $weight,
        'cod' => $cod,
        'declared_value' => $declared_value,
        'rate_calculator' => '1',
        'blocked' => '1',
        'is_return' => '0',
        'is_web' => '1',
        'is_dg' => '0',
        'only_qc_couriers' => '0',
        'length' => '12',
        'breadth' => '10',
        'height' => '10',
    )), array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
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
    
    // FIXED: Check correct response structure
    if (!isset($data['status']) || $data['status'] !== 200 || empty($data['data']['available_courier_companies'])) {
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
    
    $pickup_postcode = get_option('woocommerce_store_postcode', '201009'); // Use working postcode as fallback
    
    // FIXED: Use correct API endpoint
    $api_url = 'https://serviceability.shiprocket.in/courier/ratingserviceability';
    
    $response = wp_remote_get($api_url . '?' . http_build_query(array(
        'pickup_postcode' => $pickup_postcode,
        'delivery_postcode' => $pincode,
        'weight' => $weight,
        'cod' => 1,
        'declared_value' => $declared_value,
        'rate_calculator' => '1',
        'blocked' => '1',
        'is_return' => '0',
        'is_web' => '1',
        'is_dg' => '0',
        'only_qc_couriers' => '0',
        'length' => '12',
        'breadth' => '10',
        'height' => '10',
    )), array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ),
        'timeout' => 15
    ));
    
    if (is_wp_error($response)) {
        return array();
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    // FIXED: Check correct response structure
    if (!isset($data['status']) || $data['status'] !== 200 || empty($data['data']['available_courier_companies'])) {
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
