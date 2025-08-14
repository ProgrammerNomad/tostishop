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
            array('jquery'),
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
    
    if (!$product) {
        return;
    }
    
    $product_id = $product->get_id();
    ?>
    <div class="shiprocket-pincode-container mb-6">
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Check Delivery Availability
            </h3>
            
            <div class="flex gap-2">
                <input 
                    type="text" 
                    class="shiprocket-pincode-input flex-1 h-10 px-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-500 focus:border-transparent text-sm placeholder-gray-500" 
                    placeholder="Enter 6-digit pincode"
                    maxlength="6"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    autocomplete="postal-code"
                />
                <button 
                    type="button" 
                    class="shiprocket-check-button h-10 px-4 bg-navy-600 text-white rounded-md hover:bg-navy-700 focus:ring-2 focus:ring-navy-500 focus:ring-offset-2 transition-colors duration-200 text-sm font-medium whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Check
                </button>
            </div>
            
            <!-- Response area for delivery options -->
            <div class="shiprocket-response-area mt-4 hidden"></div>
        </div>
    </div>
    
    <script>
        // Pass product ID to JavaScript
        window.tostishopShiprocket = window.tostishopShiprocket || {};
        window.tostishopShiprocket.productId = <?php echo esc_js($product_id); ?>;
    </script>
    <?php
}

/**
 * Get pickup postcode from WooCommerce store settings
 * Checks multiple possible locations for the store postcode
 */
function tostishop_get_pickup_postcode() {
    // Try multiple WooCommerce postcode locations
    $pickup_postcode = '';
    
    // 1. Try woocommerce_store_postcode (most common)
    $pickup_postcode = get_option('woocommerce_store_postcode', '');
    
    // 2. If empty, try the general settings
    if (empty($pickup_postcode)) {
        $general_settings = get_option('woocommerce_general_settings', array());
        $pickup_postcode = isset($general_settings['woocommerce_store_postcode']) ? $general_settings['woocommerce_store_postcode'] : '';
    }
    
    // 3. If still empty, try store address postcode
    if (empty($pickup_postcode)) {
        $pickup_postcode = get_option('woocommerce_store_address_2', '');
    }
    
    // 4. Try the full store address options
    if (empty($pickup_postcode)) {
        $store_postcode_options = array(
            'woocommerce_store_postcode',
            'woocommerce_default_postcode',
            'woocommerce_calc_shipping_postcode',
        );
        
        foreach ($store_postcode_options as $option) {
            $postcode = get_option($option, '');
            if (!empty($postcode)) {
                $pickup_postcode = $postcode;
                break;
            }
        }
    }
    
    // 5. Last resort: Check WooCommerce countries/states settings
    if (empty($pickup_postcode)) {
        $base_location = wc_get_base_location();
        if (isset($base_location['postcode']) && !empty($base_location['postcode'])) {
            $pickup_postcode = $base_location['postcode'];
        }
    }
    
    // Final fallback to your working example
    if (empty($pickup_postcode)) {
        $pickup_postcode = '201009'; // Your working example pickup postcode
    }
    
    return $pickup_postcode;
}

/**
 * Get city name from Shiprocket postcode details API
 * 
 * @param string $pincode The pincode to lookup
 * @return string City name or fallback text
 */
function tostishop_get_city_from_pincode($pincode) {
    $token = get_option('tostishop_shiprocket_token', '');
    
    if (empty($token) || empty($pincode)) {
        return 'your location';
    }
    
    // API endpoint for postcode details
    $api_url = 'https://apiv2.shiprocket.co/v1/postcode/details?postcode=' . $pincode;
    
    // Make API request
    $response = wp_remote_get($api_url, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ),
        'timeout' => 10,
    ));
    
    // Handle errors
    if (is_wp_error($response)) {
        return 'your location';
    }
    
    $response_code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    // Validate response and extract city name
    if ($response_code === 200 && 
        isset($data['success']) && 
        $data['success'] === true && 
        isset($data['postcode_details']['city'])) {
        
        return $data['postcode_details']['city'];
    }
    
    // Fallback if API call fails
    return 'your location';
}

/**
 * CORE SHIPROCKET API FUNCTION - Single point for all API calls
 * 
 * @param string $pincode Delivery pincode
 * @param WC_Product|null $product Product object (optional)
 * @param array $options Additional options
 * @return array API response data or empty array on failure
 */
function tostishop_get_shiprocket_couriers($pincode, $product = null, $options = array()) {
    $token = get_option('tostishop_shiprocket_token', '');
    
    if (empty($token) || empty($pincode)) {
        return array();
    }
    
    // Get pickup postcode
    $pickup_postcode = tostishop_get_pickup_postcode();
    
    // Set default parameters
    $defaults = array(
        'weight' => 0.2,
        'declared_value' => 150,
        'cod' => '0',
        'length' => '12',
        'breadth' => '10',
        'height' => '10'
    );
    
    // Override with product data if available
    if ($product) {
        $defaults['weight'] = floatval($product->get_weight()) ?: 0.2;
        $defaults['declared_value'] = floatval($product->get_price()) ?: 150;
    }
    
    // Merge with provided options
    $params = wp_parse_args($options, $defaults);
    
    // Build API parameters
    $api_params = array(
        'pickup_postcode' => $pickup_postcode,
        'delivery_postcode' => $pincode,
        'weight' => $params['weight'],
        'cod' => $params['cod'],
        'declared_value' => $params['declared_value'],
        'rate_calculator' => '1',
        'blocked' => '1',
        'is_return' => '0',
        'is_web' => '1',
        'is_dg' => '0',
        'only_qc_couriers' => '0',
        'length' => $params['length'],
        'breadth' => $params['breadth'],
        'height' => $params['height'],
    );
    
    // API endpoint
    $api_url = 'https://serviceability.shiprocket.in/courier/ratingserviceability';
    $full_url = $api_url . '?' . http_build_query($api_params);
    
    // Make API request
    $response = wp_remote_get($full_url, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ),
        'timeout' => 15,
    ));
    
    // Handle errors
    if (is_wp_error($response)) {
        return array();
    }
    
    $response_code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    // Validate response
    if ($response_code !== 200 || !isset($data['status']) || $data['status'] !== 200) {
        return array();
    }
    
    // Return courier companies
    return isset($data['data']['available_courier_companies']) ? $data['data']['available_courier_companies'] : array();
}

/**
 * AJAX handler for pincode serviceability check - Now uses single API function with city lookup
 */
function tostishop_ajax_check_pincode() {
    // Verify nonce for security
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
    
    // FIRST: Get the correct city name from pincode
    $city_name = tostishop_get_city_from_pincode($pincode);
    
    // THEN: Use the centralized API function to get courier data
    $available_couriers = tostishop_get_shiprocket_couriers($pincode, $product);
    
    if (empty($available_couriers)) {
        wp_send_json_error('No courier companies available for this pincode.');
        return;
    }
    
    // Process the response with correct city name
    $results = tostishop_process_shiprocket_response($available_couriers, $city_name);
    
    // Send all delivery options to frontend
    wp_send_json_success(array(
        'multiple_options' => true,
        'responses' => $results
    ));
}

/**
 * Process Shiprocket API response - Updated to use correct city name
 */
function tostishop_process_shiprocket_response($available_couriers, $city_name = 'your location') {
    // Separate quick delivery and regular delivery couriers
    $quick_couriers = array();
    $regular_couriers = array();
    
    foreach ($available_couriers as $courier) {
        // Skip blocked or disabled couriers
        if (isset($courier['blocked']) && $courier['blocked'] == 1) continue;
        if (isset($courier['courier_disabled']) && $courier['courier_disabled'] == 1) continue;
        
        // Check for quick delivery (hyperlocal or empty estimated_delivery_days)
        $is_quick_delivery = false;
        
        // Method 1: Check if hyperlocal delivery
        if (isset($courier['is_hyperlocal']) && $courier['is_hyperlocal'] === true) {
            $is_quick_delivery = true;
        }
        
        // Method 2: Check if estimated_delivery_days is empty or very short ETD
        if (empty($courier['estimated_delivery_days']) && isset($courier['etd_hours']) && $courier['etd_hours'] <= 12) {
            $is_quick_delivery = true;
        }
        
        // Method 3: Check courier name patterns for quick delivery
        $quick_patterns = array('Quick-', 'Shadowfax Marketplace Quick', 'SROla_QUICK', 'Hyperlocal');
        foreach ($quick_patterns as $pattern) {
            if (strpos($courier['courier_name'], $pattern) !== false) {
                $is_quick_delivery = true;
                break;
            }
        }
        
        if ($is_quick_delivery) {
            $quick_couriers[] = $courier;
        } else {
            $regular_couriers[] = $courier;
        }
    }
    
    // Show BOTH quick and regular if available
    $responses = array();
    
    // Process quick delivery couriers first
    if (!empty($quick_couriers)) {
        // Sort quick couriers by price (cheapest first)
        usort($quick_couriers, function($a, $b) {
            $rate_a = floatval($a['rate'] ?? $a['freight_charge'] ?? 0);
            $rate_b = floatval($b['rate'] ?? $b['freight_charge'] ?? 0);
            return $rate_a - $rate_b;
        });
        
        $best_quick = $quick_couriers[0];
        $courier_name = $best_quick['courier_name'];
        $hours = isset($best_quick['etd_hours']) ? $best_quick['etd_hours'] : 3;
        $rate = floatval($best_quick['rate'] ?? $best_quick['freight_charge'] ?? 0);
        
        // Format quick delivery message with correct city name
        if ($hours <= 3) {
            $responses[] = array(
                'message' => sprintf(
                    "🚀 Lightning fast delivery to %s! Get your order delivered within %d hours with %s for ₹%s.",
                    esc_html($city_name),
                    $hours,
                    esc_html($courier_name),
                    number_format($rate, 0)
                ),
                'type' => 'express',
                'priority' => 1
            );
        } else {
            $responses[] = array(
                'message' => sprintf(
                    "⚡ Same-day delivery available to %s! Order now with %s for ₹%s and get it today.",
                    esc_html($city_name),
                    esc_html($courier_name),
                    number_format($rate, 0)
                ),
                'type' => 'express',
                'priority' => 1
            );
        }
    }
    
    // Process regular delivery couriers
    if (!empty($regular_couriers)) {
        // Filter and sort regular couriers by delivery days (fastest first)
        $filtered_couriers = array_filter($regular_couriers, function($courier) {
            return !empty($courier['estimated_delivery_days']) && 
                   $courier['estimated_delivery_days'] > 0;
        });
        
        if (!empty($filtered_couriers)) {
            usort($filtered_couriers, function($a, $b) {
                return intval($a['estimated_delivery_days']) - intval($b['estimated_delivery_days']);
            });
            
            $courier = $filtered_couriers[0]; // Get fastest regular courier
            $days = intval($courier['estimated_delivery_days']);
            $courier_name = $courier['courier_name'];
            $rate = floatval($courier['rate'] ?? $courier['freight_charge'] ?? 0);
            
            // Format regular delivery message with correct city name
            if ($days <= 1) {
                $responses[] = array(
                    'message' => sprintf(
                        "🚀 Next-day delivery available to %s! Order now with %s for ₹%s.",
                        esc_html($city_name),
                        esc_html($courier_name),
                        number_format($rate, 0)
                    ),
                    'type' => 'express',
                    'priority' => 2
                );
            } elseif ($days <= 2) {
                $responses[] = array(
                    'message' => sprintf(
                        "✅ Fast delivery to %s! Your order arrives in just %d days via %s for ₹%s.",
                        esc_html($city_name),
                        $days,
                        esc_html($courier_name),
                        number_format($rate, 0)
                    ),
                    'type' => 'standard',
                    'priority' => 2
                );
            } else {
                $responses[] = array(
                    'message' => sprintf(
                        "📦 Standard delivery to %s! Your order arrives in %d days via %s for ₹%s.",
                        esc_html($city_name),
                        $days,
                        esc_html($courier_name),
                        number_format($rate, 0)
                    ),
                    'type' => 'standard',
                    'priority' => 2
                );
            }
        }
    }
    
    // Return both responses if available, otherwise fallback
    if (!empty($responses)) {
        return $responses;
    }
    
    // Fallback message if no suitable couriers found
    return array(
        array(
            'message' => sprintf("✅ Delivery available to %s.", esc_html($city_name)),
            'type' => 'standard',
            'priority' => 1
        )
    );
}

/**
 * Get Shiprocket shipping rates for checkout - Now uses centralized API function
 * 
 * @param string $pincode Delivery pincode
 * @param float $weight Package weight
 * @param array $dimensions Package dimensions
 * @param float $declared_value Package value
 * @return array Array of shipping rates
 */
function tostishop_get_shiprocket_rates($pincode, $weight, $dimensions, $declared_value) {
    // Prepare options for API call
    $options = array(
        'weight' => $weight,
        'declared_value' => $declared_value,
        'cod' => '1',
        'length' => $dimensions['length'] ?? '12',
        'breadth' => $dimensions['breadth'] ?? '10',
        'height' => $dimensions['height'] ?? '10'
    );
    
    // Use centralized API function
    $couriers = tostishop_get_shiprocket_couriers($pincode, null, $options);
    
    if (empty($couriers)) {
        return array();
    }
    
    // Optional: Filter to show only top couriers
    $show_top_courier = get_option('tostishop_shiprocket_show_top_courier', 'yes');
    
    if ($show_top_courier === 'yes') {
        // Sort by rating and limit to top 5
        usort($couriers, function($a, $b) {
            return ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0);
        });
        $couriers = array_slice($couriers, 0, 5);
    }
    
    // Format rates for WooCommerce
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