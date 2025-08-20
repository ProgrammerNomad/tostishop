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
            TOSTISHOP_VERSION,
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
                    "Lightning fast delivery to %s! Get your order delivered within %d hours with %s for ₹%s.",
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
                        "Fast delivery to %s! Your order arrives in just %d days via %s for ₹%s.",
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

/**
 * Calculate shipping methods for cart/checkout
 * 
 * @param string $pincode Delivery pincode
 * @param array $cart_items Cart items for calculation
 * @return array Array of shipping methods with rates
 */
function tostishop_calculate_shiprocket_shipping_methods($pincode, $cart_items = null) {
    $token = get_option('tostishop_shiprocket_token', '');
    
    if (empty($token) || empty($pincode)) {
        return array();
    }
    
    // Get cart items if not provided
    if (is_null($cart_items)) {
        if (!WC()->cart || WC()->cart->is_empty()) {
            return array();
        }
        $cart_items = WC()->cart->get_cart();
    }
    
    if (empty($cart_items)) {
        return array();
    }
    
    // Calculate total weight and value
    $total_weight = 0;
    $total_value = 0;
    
    foreach ($cart_items as $cart_item) {
        $product = $cart_item['data'];
        $quantity = $cart_item['quantity'];
        
        $weight = floatval($product->get_weight()) ?: 0.2; // Default 200g
        $price = floatval($product->get_price()) ?: 100;
        
        $total_weight += ($weight * $quantity);
        $total_value += ($price * $quantity);
    }
    
    // Minimum values
    $total_weight = max($total_weight, 0.2);
    $total_value = max($total_value, 100);
    
    // Get pickup postcode
    $pickup_postcode = tostishop_get_pickup_postcode();
    
    // Prepare API parameters
    $api_params = array(
        'pickup_postcode' => $pickup_postcode,
        'delivery_postcode' => $pincode,
        'weight' => $total_weight,
        'cod' => '1', // Enable COD
        'declared_value' => $total_value,
        'rate_calculator' => '1',
        'blocked' => '1',
        'is_return' => '0',
        'is_web' => '1',
        'is_dg' => '0',
        'only_qc_couriers' => '0',
        'length' => '12',
        'breadth' => '10',
        'height' => '10'
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
    
    $couriers = isset($data['data']['available_courier_companies']) ? $data['data']['available_courier_companies'] : array();
    
    if (empty($couriers)) {
        return array();
    }
    
    // Process and format shipping methods
    return tostishop_process_shipping_methods($couriers, $total_value);
}

/**
 * Process courier data into shipping methods
 * 
 * @param array $couriers Raw courier data from API
 * @param float $total_value Cart total value
 * @return array Formatted shipping methods
 */
function tostishop_process_shipping_methods($couriers, $total_value = 0) {
    $shipping_methods = array();
    
    // Free shipping threshold
    $free_shipping_threshold = 500; // ₹500
    $is_free_shipping = ($total_value >= $free_shipping_threshold);
    
    foreach ($couriers as $courier) {
        // Skip blocked or disabled couriers
        if (isset($courier['blocked']) && $courier['blocked'] == 1) continue;
        if (isset($courier['courier_disabled']) && $courier['courier_disabled'] == 1) continue;
        
        $courier_name = $courier['courier_name'] ?? 'Unknown Courier';
        $rate = floatval($courier['rate'] ?? $courier['freight_charge'] ?? 0);
        $estimated_days = intval($courier['estimated_delivery_days'] ?? 0);
        $cod_charges = floatval($courier['cod_charges'] ?? 0);
        $other_charges = floatval($courier['other_charges'] ?? 0);
        
        // Calculate total shipping cost from API
        $total_cost = $rate + $cod_charges + $other_charges;
        
        // Determine shipping type
        $shipping_type = 'standard';
        $delivery_text = '';
        
        if (isset($courier['is_hyperlocal']) && $courier['is_hyperlocal'] === true) {
            $shipping_type = 'express';
            $delivery_text = 'Same day delivery';
        } elseif ($estimated_days <= 1) {
            $shipping_type = 'express';
            $delivery_text = 'Next day delivery';
        } elseif ($estimated_days <= 2) {
            $shipping_type = 'fast';
            $delivery_text = '2 day delivery';
        } else {
            $delivery_text = $estimated_days . ' days delivery';
        }
        
        // Apply free shipping logic
        $final_cost = $is_free_shipping ? 0 : $total_cost;
        
        // Create method title based on free shipping status
        $method_title = $courier_name . ' - ' . $delivery_text;
        if ($is_free_shipping) {
            $method_title .= ' (FREE)';
        }
        
        $shipping_methods[] = array(
            'id' => sanitize_title($courier_name . '_' . $rate),
            'courier_name' => $courier_name,
            'method_title' => $method_title,
            'cost' => $final_cost,
            'original_cost' => $total_cost,
            'estimated_days' => $estimated_days,
            'type' => $shipping_type,
            'delivery_text' => $delivery_text,
            'is_free' => $is_free_shipping,
            'cod_available' => isset($courier['cod']) ? $courier['cod'] : true,
            'rating' => floatval($courier['rating'] ?? 0),
            'etd_hours' => intval($courier['etd_hours'] ?? 24),
            'raw_data' => $courier
        );
    }
    
    // Sort by cost (free shipping first, then by price)
    usort($shipping_methods, function($a, $b) {
        // Free shipping first
        if ($a['is_free'] && !$b['is_free']) return -1;
        if (!$a['is_free'] && $b['is_free']) return 1;
        
        // Then by original cost (API price) for consistency
        if ($a['is_free'] && $b['is_free']) {
            // Both free, sort by delivery speed
            return $a['estimated_days'] - $b['estimated_days'];
        }
        
        // Sort by actual cost
        if ($a['cost'] == $b['cost']) {
            // If same cost, prefer faster delivery
            return $a['estimated_days'] - $b['estimated_days'];
        }
        
        return $a['cost'] - $b['cost'];
    });
    
    // Return top 5 methods
    return array_slice($shipping_methods, 0, 5);
}

/**
 * AJAX handler for calculating shipping methods
 */
function tostishop_ajax_calculate_shipping_methods() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_shiprocket_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    $pincode = sanitize_text_field($_POST['pincode'] ?? '');
    
    if (!$pincode) {
        wp_send_json_error('Pincode is required');
        return;
    }
    
    if (!preg_match('/^\d{6}$/', $pincode)) {
        wp_send_json_error('Invalid pincode format');
        return;
    }
    
    // Calculate shipping methods
    $shipping_methods = tostishop_calculate_shiprocket_shipping_methods($pincode);
    
    if (empty($shipping_methods)) {
        wp_send_json_error('No shipping methods available for this location');
        return;
    }
    
    // Get cart total for display
    $cart_total = WC()->cart ? WC()->cart->get_cart_contents_total() : 0;
    
    // Generate HTML using the same format as WooCommerce template
    $html = tostishop_generate_shipping_methods_html($shipping_methods, $cart_total);
    
    wp_send_json_success(array(
        'shipping_methods' => $shipping_methods,
        'cart_total' => $cart_total,
        'free_shipping_threshold' => 500,
        'html' => $html
    ));
}

/**
 * Generate shipping methods HTML consistent with WooCommerce templates
 */
function tostishop_generate_shipping_methods_html($shipping_methods, $cart_total = 0) {
    if (empty($shipping_methods)) {
        return '';
    }
    
    $html = '<div class="shipping-methods-container bg-white border border-gray-200 rounded-lg p-4 shadow-sm">';
    
    // Header
    $html .= '<div class="border-b border-gray-200 pb-3 mb-3">';
    $html .= '<div class="flex items-center text-base font-bold text-navy-900">';
    $html .= '<div class="flex items-center justify-center w-8 h-8 bg-navy-100 rounded-lg mr-2">';
    $html .= '<svg class="w-4 h-4 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
    $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4-8-4m16 0v10l-8 4-8-4V7"></path>';
    $html .= '</svg>';
    $html .= '</div>';
    $html .= 'Available Shipping Methods';
    $html .= '</div>';
    $html .= '</div>';
    
    // Shipping methods list
    $html .= '<ul class="shipping-methods-list space-y-2">';
    
    foreach ($shipping_methods as $index => $method) {
        $method_id = 'shipping_method_' . $index;
        $is_checked = $index === 0 ? 'checked' : ''; // First one selected by default
        
        // Create mock method object for consistency
        $mock_method = (object) array(
            'id' => $method_id,
            'label' => $method['courier_name'] . ' - ' . $method['delivery_text'] . ': ' . ($method['is_free'] ? 'FREE' : '₹' . number_format($method['cost'], 0)),
            'cost' => $method['cost']
        );
        
        $method_data = tostishop_parse_shipping_method_data($mock_method);
        
        $html .= '<li class="shipping-method-item">';
        $html .= sprintf(
            '<input type="radio" id="%s" name="shipping_method_ajax" value="%s" class="shipping_method sr-only" %s data-cost="%s" data-courier="%s" />',
            esc_attr($method_id),
            esc_attr($method_id),
            $is_checked,
            esc_attr($method['cost']),
            esc_attr($method['courier_name'])
        );
        
        $label_classes = tostishop_get_shipping_method_label_classes($method_id, $is_checked ? $method_id : '');
        
        $html .= sprintf('<label for="%s" class="%s">', esc_attr($method_id), esc_attr($label_classes));
        
        $html .= '<div class="flex items-center flex-1">';
        
        // Radio button
        $html .= '<div class="flex items-center mr-3">';
        $radio_classes = tostishop_get_shipping_radio_classes($method_id, $is_checked ? $method_id : '');
        $html .= '<div class="' . esc_attr($radio_classes) . '">';
        if ($is_checked) {
            $html .= '<div class="w-1.5 h-1.5 bg-white rounded-full"></div>';
        }
        $html .= '</div>';
        $html .= '</div>';
        
        $html .= '<div class="flex-1 min-w-0">';
        $html .= '<div class="flex items-center gap-2 mb-1">';
        $html .= '<span class="font-semibold text-gray-900 text-sm truncate">' . esc_html($method['courier_name']) . '</span>';
        
        if ($index === 0) {
            $html .= '<span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-accent text-white flex-shrink-0">⭐ Best</span>';
        }
        
        if ($method['is_free']) {
            $html .= '<span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 flex-shrink-0">FREE</span>';
        }
        
        $html .= '</div>';
        
        $html .= '<div class="text-xs ' . esc_attr($method_data['delivery_type_class']) . ' flex items-center">';
        $html .= '<span class="mr-1">' . esc_html($method_data['delivery_icon']) . '</span>';
        $html .= '<span>' . esc_html($method['delivery_text']) . '</span>';
        $html .= '<span class="mx-2">•</span>';
        $html .= '<span>COD Available</span>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        // Price
        $html .= '<div class="text-right ml-3">';
        $price_class = $method['is_free'] ? 'text-green-600' : 'text-navy-900';
        $price_text = $method['is_free'] ? 'FREE' : '₹' . number_format($method['cost'], 0);
        $html .= '<span class="text-lg font-bold ' . esc_attr($price_class) . '">' . esc_html($price_text) . '</span>';
        $html .= '</div>';
        
        $html .= '</label>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    $html .= '</div>';
    
    return $html;
}

// Register the new AJAX handler
add_action('wp_ajax_tostishop_calculate_shipping_methods', 'tostishop_ajax_calculate_shipping_methods');
add_action('wp_ajax_nopriv_tostishop_calculate_shipping_methods', 'tostishop_ajax_calculate_shipping_methods');

// Initialize all Shiprocket hooks and functionality
tostishop_shiprocket_init();

/**
 * Display shipping methods calculator on cart/checkout pages
 */
function tostishop_shipping_methods_calculator() {
    if (!WC()->cart || WC()->cart->is_empty()) {
        return;
    }
    
    $cart_total = WC()->cart->get_cart_contents_total();
    $free_shipping_threshold = 500;
    $is_free_shipping = ($cart_total >= $free_shipping_threshold);
    
    ?>
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm mb-8">
        <h3 class="text-xl font-bold text-navy-900 mb-6 flex items-center">
            <div class="flex items-center justify-center w-10 h-10 bg-navy-100 rounded-lg mr-3">
                <svg class="w-5 h-5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4-8-4m16 0v10l-8 4-8-4V7"></path>
                </svg>
            </div>
            <?php _e('Calculate Shipping', 'tostishop'); ?>
        </h3>
        
        <!-- Free Shipping Status -->
        <?php if ($is_free_shipping): ?>
            <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            <?php printf(__('Congratulations! You qualify for free shipping on all methods!', 'tostishop')); ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php $remaining = $free_shipping_threshold - $cart_total; ?>
            <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800">
                            <?php printf(__('Add ₹%s more for free shipping on all methods!', 'tostishop'), number_format($remaining, 0)); ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Pincode Input Section -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input 
                        type="text" 
                        id="shipping-pincode-input"
                        class="w-full h-12 px-4 text-base border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-navy-500 focus:border-navy-500 placeholder-gray-500 transition-all duration-200" 
                        placeholder="<?php esc_attr_e('Enter 6-digit pincode', 'tostishop'); ?>"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        autocomplete="postal-code"
                    />
                </div>
                <button 
                    type="button" 
                    id="calculate-shipping-btn"
                    class="h-12 px-6 bg-navy-600 text-white rounded-lg hover:bg-navy-700 focus:ring-2 focus:ring-navy-500 focus:ring-offset-2 transition-all duration-200 text-base font-semibold whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[120px]"
                >
                    <span class="calculate-btn-text"><?php _e('Calculate', 'tostishop'); ?></span>
                    <svg class="calculate-btn-icon hidden animate-spin ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Shipping Methods Results -->
        <div id="shipping-methods-results" class="hidden">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-bold text-gray-900"><?php _e('Available Shipping Methods', 'tostishop'); ?></h4>
                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Top 5 Methods</span>
            </div>
            <div id="shipping-methods-list" class="space-y-3"></div>
        </div>
        
        <!-- Loading State -->
        <div id="shipping-loading" class="hidden">
            <div class="text-center py-8">
                <div class="inline-flex items-center justify-center">
                    <svg class="animate-spin h-8 w-8 text-navy-600 mr-3" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <div>
                        <p class="text-base font-medium text-gray-900"><?php _e('Calculating shipping options...', 'tostishop'); ?></p>
                        <p class="text-sm text-gray-500"><?php _e('Please wait while we find the best rates', 'tostishop'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Error Message -->
        <div id="shipping-error" class="hidden">
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800" id="shipping-error-message"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Pass AJAX configuration to JavaScript
        window.tostishopShipping = window.tostishopShipping || {};
        window.tostishopShipping.ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
        window.tostishopShipping.nonce = '<?php echo wp_create_nonce('tostishop_shiprocket_nonce'); ?>';
        window.tostishopShipping.cartTotal = <?php echo $cart_total; ?>;
        window.tostishopShipping.freeShippingThreshold = <?php echo $free_shipping_threshold; ?>;
        window.tostishopShipping.isFreeShipping = <?php echo $is_free_shipping ? 'true' : 'false'; ?>;
    </script>
    <?php
}

/**
 * Display simple free shipping notification for checkout
 */
function tostishop_checkout_free_shipping_notice() {
    if (!WC()->cart || WC()->cart->is_empty()) {
        return;
    }
    
    $cart_total = WC()->cart->get_cart_contents_total();
    $free_shipping_threshold = 500;
    $is_free_shipping = ($cart_total >= $free_shipping_threshold);
    
    if ($is_free_shipping) {
        ?>
        <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        <?php printf(__('Congratulations! You qualify for free shipping on all methods!', 'tostishop')); ?>
                    </p>
                </div>
            </div>
        </div>
        <?php
    } else {
        $remaining = $free_shipping_threshold - $cart_total;
        ?>
        <div class="p-4 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-lg mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-amber-800">
                        <?php printf(__('Add ₹%s more for free shipping on all methods!', 'tostishop'), number_format($remaining, 0)); ?>
                    </p>
                </div>
            </div>
        </div>
        <?php
    }
}

// Add shipping calculator to cart page ONLY
add_action('woocommerce_cart_collaterals', 'tostishop_shipping_methods_calculator', 5);

// Add simple free shipping notice to checkout page ONLY
add_action('woocommerce_checkout_before_order_review', 'tostishop_checkout_free_shipping_notice');

/**
 * Initialize shipping method after plugins loaded
 */
function tostishop_init_shiprocket_shipping_method() {
    if (!class_exists('WC_Shipping_Method')) {
        return;
    }
    
    // Load our shipping method class
    if (!class_exists('TostiShop_Shiprocket_Shipping_Method')) {
        
        /**
         * Custom WooCommerce Shipping Method for Shiprocket
         */
        class TostiShop_Shiprocket_Shipping_Method extends WC_Shipping_Method {
            
            public function __construct() {
                $this->id = 'tostishop_shiprocket';
                $this->method_title = __('Shiprocket Shipping', 'tostishop');
                $this->method_description = __('Dynamic shipping rates from Shiprocket with free shipping above ₹500', 'tostishop');
                
                $this->init();
            }
    
    public function init() {
        $this->init_form_fields();
        $this->init_settings();
        
        $this->title = $this->get_option('title');
        $this->enabled = $this->get_option('enabled');
        
        add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
    }
    
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'tostishop'),
                'type' => 'checkbox',
                'description' => __('Enable Shiprocket shipping', 'tostishop'),
                'default' => 'yes'
            ),
            'title' => array(
                'title' => __('Title', 'tostishop'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'tostishop'),
                'default' => __('Shiprocket Shipping', 'tostishop'),
                'desc_tip' => true,
            ),
            'free_shipping_threshold' => array(
                'title' => __('Free Shipping Threshold', 'tostishop'),
                'type' => 'number',
                'description' => __('Minimum order amount for free shipping (in ₹)', 'tostishop'),
                'default' => '500',
                'desc_tip' => true,
            )
        );
    }
    
    public function calculate_shipping($package = array()) {
        if (empty($package['destination']['postcode'])) {
            return;
        }
        
        $pincode = $package['destination']['postcode'];
        $cart_items = WC()->cart->get_cart();
        
        $shipping_methods = tostishop_calculate_shiprocket_shipping_methods($pincode, $cart_items);
        
        foreach ($shipping_methods as $method) {
            $rate = array(
                'id' => $this->id . '_' . $method['id'],
                'label' => $method['method_title'],
                'cost' => $method['cost'], // This will be 0 if free shipping applies
                'meta_data' => array(
                    'courier_name' => $method['courier_name'],
                    'estimated_days' => $method['estimated_days'],
                    'delivery_text' => $method['delivery_text'],
                    'type' => $method['type'],
                    'original_cost' => $method['original_cost'],
                    'is_free' => $method['is_free']
                )
            );
            
            $this->add_rate($rate);
        }
    }
        } // End class definition
    } // End if class_exists check
}

/**
 * Register Shiprocket shipping method
 */
function tostishop_add_shiprocket_shipping_method($methods) {
    $methods['tostishop_shiprocket'] = 'TostiShop_Shiprocket_Shipping_Method';
    return $methods;
}
add_filter('woocommerce_shipping_methods', 'tostishop_add_shiprocket_shipping_method');

add_action('woocommerce_shipping_init', 'tostishop_init_shiprocket_shipping_method');