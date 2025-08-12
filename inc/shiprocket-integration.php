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
        
        wp_localize_script('tostishop-shiprocket', 'tostishopShiprocket', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('tostishop_shiprocket_nonce'),
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
    
    // Get Shiprocket token
    $token = get_option('tostishop_shiprocket_token', '');
    if (empty($token)) {
        wp_send_json_error('Shiprocket not configured. Please contact administrator.');
        return;
    }
    
    // Get product weight (default to 0.5 if not set)
    $weight = floatval($product->get_weight()) ?: 0.5;
    
    // Get pickup postcode from WooCommerce settings
    $pickup_postcode = get_option('woocommerce_store_postcode', '');
    
    // If no pickup postcode is set, use a default or return error
    if (empty($pickup_postcode)) {
        // Try to get from store address
        $pickup_postcode = get_option('woocommerce_default_country', '');
        if (empty($pickup_postcode)) {
            wp_send_json_error('Store pickup location not configured. Please contact administrator.');
            return;
        }
        // If country code format like IN:110001, extract postcode
        if (strpos($pickup_postcode, ':') !== false) {
            $parts = explode(':', $pickup_postcode);
            $pickup_postcode = end($parts);
        }
        // If still no valid postcode, use Delhi as default
        if (empty($pickup_postcode) || !preg_match('/^\d{6}$/', $pickup_postcode)) {
            $pickup_postcode = '110001'; // Default to Delhi
        }
    }
    
    // Shiprocket API endpoint (matching original plugin exactly)
    $endpoint_url = 'https://apiv2.shiprocket.in/v1/courier/ratingserviceability';
    
    // Build query parameters (matching original plugin)
    $params = array(
        'pickup_postcode' => $pickup_postcode,
        'delivery_postcode' => $pincode,
        'weight' => $weight,
        'cod' => '0',
        'declared_value' => floatval($product->get_price()) ?: 200,
        'rate_calculator' => '1',
        'blocked' => '1',
        'is_return' => '0',
        'is_web' => '1',
        'is_dg' => '0',
        'only_qc_couriers' => '0',
        'length' => '12',
        'breadth' => '15',
        'height' => '10',
    );
    
    // Construct full URL
    $full_url = $endpoint_url . '?' . http_build_query($params);
    
    // Make API request
    $response = wp_remote_get($full_url, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
        ),
        'timeout' => 15,
    ));
    
    // Check for API errors
    if (is_wp_error($response)) {
        wp_send_json_error('Unable to check serviceability. Please try again.');
        return;
    }
    
    // Parse response
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    // Check if API returned valid data
    if (!isset($data['data']['available_courier_companies'])) {
        // If API response doesn't have expected structure, log the response for debugging
        error_log('Shiprocket API Response: ' . $body);
        
        // For admins, show more detailed error
        if (current_user_can('manage_options')) {
            wp_send_json_error('Debug - API Response: ' . substr($body, 0, 500) . (strlen($body) > 500 ? '...' : ''));
        } else {
            // Check for common API error messages
            if (isset($data['message'])) {
                wp_send_json_error('Shiprocket API Error: ' . $data['message']);
            } else if (isset($data['error'])) {
                wp_send_json_error('Shiprocket API Error: ' . $data['error']);
            } else {
                wp_send_json_error('Service not available for this pincode.');
            }
        }
        return;
    }
    
    $available_couriers = $data['data']['available_courier_companies'];
    
    if (empty($available_couriers)) {
        wp_send_json_error('No courier companies available for your pincode.');
        return;
    }
    
    // Process the response (matching original plugin logic)
    $result = tostishop_process_shiprocket_response($available_couriers);
    
    wp_send_json_success(array(
        'message' => $result['message'],
        'type' => $result['type']
    ));
}

/**
 * Process Shiprocket API response (matching original plugin logic)
 */
function tostishop_process_shiprocket_response($available_couriers) {
    // Check for quick delivery options
    $quick_couriers = array('Quick-Ola', 'Quick-Borzo', 'Quick-Flash', 'Quick-Qwqer', 'Quick-Mover', 'Quick-Porter', 'Loadshare Hyperlocal');
    
    foreach ($available_couriers as $courier) {
        if (in_array($courier['courier_name'], $quick_couriers)) {
            return array(
                'message' => "🚀 Don't wait! Order now and get it delivered to your doorstep within the next 2 hours.",
                'type' => 'express'
            );
        }
    }
    
    // Filter couriers with valid delivery days
    $filtered_couriers = array_filter($available_couriers, function($courier) {
        return !empty($courier['estimated_delivery_days']) && $courier['estimated_delivery_days'] > 0;
    });
    
    if (!empty($filtered_couriers)) {
        $courier = reset($filtered_couriers); // Get first valid courier
        $city = isset($courier['city']) ? $courier['city'] : 'your location';
        $days = intval($courier['estimated_delivery_days']);
        
        return array(
            'message' => sprintf(
                "✅ Fast delivery to %s! Your order arrives in just %d %s with our expedited shipping.",
                esc_html($city),
                $days,
                $days === 1 ? 'day' : 'days'
            ),
            'type' => 'standard'
        );
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
