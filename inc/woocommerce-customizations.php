<?php
/**
 * TostiShop WooCommerce Customizations
 * All WooCommerce-related modifications and enhancements
 * 
 * @package TostiShop
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove add to cart buttons
 */
function tostishop_remove_add_to_cart_buttons() {
    // Check if WooCommerce is available and functions exist
    if (!class_exists('WooCommerce') || !function_exists('is_product')) {
        return;
    }
    
    // Remove ALL add to cart buttons from archive/shop pages
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    
    // Keep the add to cart functionality only on single product pages
    if (!is_product()) {
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    }
}

/**
 * Checkout Customizations
 * Remove separate shipping address and combine with billing
 */
function tostishop_checkout_customizations() {
    // Force shipping address to be same as billing address
    add_filter('woocommerce_cart_needs_shipping_address', '__return_false');
    
    // Remove shipping fields from checkout
    add_filter('woocommerce_checkout_fields', 'tostishop_remove_shipping_fields');
    
    // Auto-copy billing to shipping on checkout
    add_action('woocommerce_checkout_process', 'tostishop_auto_copy_billing_to_shipping');
}
add_action('init', 'tostishop_checkout_customizations');

/**
 * Remove shipping fields from checkout
 */
function tostishop_remove_shipping_fields($fields) {
    // Remove all shipping fields
    unset($fields['shipping']);
    return $fields;
}

/**
 * Auto-copy billing address to shipping address
 */
function tostishop_auto_copy_billing_to_shipping() {
    // Copy billing data to shipping
    $_POST['shipping_first_name'] = $_POST['billing_first_name'];
    $_POST['shipping_last_name'] = $_POST['billing_last_name'];
    $_POST['shipping_company'] = $_POST['billing_company'];
    $_POST['shipping_address_1'] = $_POST['billing_address_1'];
    $_POST['shipping_address_2'] = $_POST['billing_address_2'];
    $_POST['shipping_city'] = $_POST['billing_city'];
    $_POST['shipping_postcode'] = $_POST['billing_postcode'];
    $_POST['shipping_country'] = $_POST['billing_country'];
    $_POST['shipping_state'] = $_POST['billing_state'];
}

/**
 * Customize WooCommerce display
 */
function tostishop_woocommerce_customizations() {
    // Check if WooCommerce is available
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    // Remove default WooCommerce styles
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');

    // Modify WooCommerce loop
    remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
    remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

    // Add custom WooCommerce actions
    add_action('woocommerce_before_shop_loop_item_title', 'tostishop_product_link_open', 10);
    add_action('woocommerce_shop_loop_item_title', 'tostishop_product_title', 10);
    add_action('woocommerce_after_shop_loop_item', 'tostishop_product_link_close', 5);
    
    // Remove add to cart buttons - hook this to a later action when WooCommerce is ready
    add_action('template_redirect', 'tostishop_remove_add_to_cart_buttons');
}
add_action('after_setup_theme', 'tostishop_woocommerce_customizations');

/**
 * Custom product link open
 */
function tostishop_product_link_open() {
    global $product;
    echo '<a href="' . get_permalink($product->get_id()) . '" class="group block">';
}

/**
 * Custom product link close
 */
function tostishop_product_link_close() {
    echo '</a>';
}

/**
 * Custom product title
 */
function tostishop_product_title() {
    echo '<h3 class="text-sm font-medium text-gray-900 group-hover:text-navy-600 transition-colors duration-200">' . get_the_title() . '</h3>';
}

/**
 * Custom breadcrumbs
 */
function tostishop_breadcrumbs() {
    if (function_exists('woocommerce_breadcrumb')) {
        woocommerce_breadcrumb(array(
            'delimiter' => ' / ',
            'wrap_before' => '<nav class="breadcrumbs text-sm text-gray-600 mb-4">',
            'wrap_after' => '</nav>',
            'before' => '',
            'after' => '',
            'home' => __('Home', 'tostishop'),
        ));
    }
}

/**
 * Get cart count for header
 */
function tostishop_cart_count() {
    if (function_exists('WC')) {
        return WC()->cart->get_cart_contents_count();
    }
    return 0;
}

/**
 * Get cart total for header
 */
function tostishop_cart_total() {
    if (function_exists('WC')) {
        return WC()->cart->get_cart_total();
    }
    return '';
}

/**
 * Customize order confirmation email link
 */
function tostishop_order_confirmation_email_text($text, $order) {
    if ($order && !$order->has_status('failed')) {
        return __('We\'ve sent a confirmation email to your inbox. Please check your email for order details and tracking information.', 'tostishop');
    }
    return $text;
}
add_filter('woocommerce_thankyou_order_received_text', 'tostishop_order_confirmation_email_text', 10, 2);

/**
 * Add order tracking information to confirmation page
 */
function tostishop_add_order_tracking_info($order_id) {
    $order = wc_get_order($order_id);
    
    if ($order && !$order->has_status(array('failed', 'cancelled'))) {
        echo '<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mt-6">';
        echo '<div class="flex items-start space-x-3">';
        echo '<div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">';
        echo '<svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
        echo '</svg>';
        echo '</div>';
        echo '<div>';
        echo '<h3 class="text-sm font-semibold text-yellow-900 mb-1">' . __('What\'s Next?', 'tostishop') . '</h3>';
        echo '<p class="text-sm text-yellow-800">';
        echo __('We\'ll start processing your order right away. You\'ll receive tracking information once your order ships.', 'tostishop');
        echo '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
add_action('woocommerce_thankyou', 'tostishop_add_order_tracking_info', 20);

/**
 * Filter WooCommerce pagination to use our enhanced version
 */
function tostishop_woocommerce_pagination_args($args) {
    $args['prev_text'] = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>' . __('Previous', 'tostishop');
    $args['next_text'] = __('Next', 'tostishop') . '<svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
    $args['type'] = 'array';
    return $args;
}
add_filter('woocommerce_pagination_args', 'tostishop_woocommerce_pagination_args');

/**
 * Order confirmation enhancements
 */
function tostishop_order_confirmation_enhancements() {
    // Add special styling to order confirmation pages
    if (is_order_received_page() || is_wc_endpoint_url('order-received')) {
        // Add viewport meta for better mobile experience
        add_action('wp_head', function() {
            echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">';
        }, 1);
        
        // Add structured data for order confirmation
        add_action('wp_head', 'tostishop_order_confirmation_structured_data');
        
        // Add print styles
        add_action('wp_head', function() {
            echo '<style media="print">
                .no-print, header, footer, .order-action-btn { display: none !important; }
                .order-details-card { box-shadow: none !important; border: 1px solid #000 !important; }
            </style>';
        });
    }
}
add_action('template_redirect', 'tostishop_order_confirmation_enhancements');

/**
 * Add structured data for order confirmation
 */
function tostishop_order_confirmation_structured_data() {
    global $wp;
    
    if (isset($wp->query_vars['order-received']) && !empty($wp->query_vars['order-received'])) {
        $order_id = absint($wp->query_vars['order-received']);
        $order = wc_get_order($order_id);
        
        if ($order && $order->get_status() !== 'failed') {
            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'Order',
                'orderNumber' => $order->get_order_number(),
                'orderDate' => $order->get_date_created()->format('c'),
                'orderStatus' => 'https://schema.org/OrderProcessing',
                'priceCurrency' => $order->get_currency(),
                'price' => $order->get_total(),
                'url' => $order->get_view_order_url(),
                'merchant' => array(
                    'url' => home_url()
                )
            );
            
            echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
        }
    }
}

/**
 * Helper functions for WooCommerce template customizations
 */

/**
 * Parse shipping method data for consistent display
 */
function tostishop_parse_shipping_method_data($method) {
    $label_text = $method->get_label();
    $cost = $method->get_cost();
    $is_free = ($cost == 0);
    
    // Extract courier name and delivery info
    $parts = explode(':', $label_text);
    $method_info = trim($parts[0]);
    
    // Parse courier name and delivery details
    $courier_parts = explode(' - ', $method_info);
    $courier_name = isset($courier_parts[0]) ? $courier_parts[0] : $method_info;
    $delivery_info = isset($courier_parts[1]) ? $courier_parts[1] : '';
    
    // Extract delivery days
    preg_match('/(\d+)\s+days?\s+delivery/i', $delivery_info, $delivery_matches);
    $delivery_days = isset($delivery_matches[1]) ? (int)$delivery_matches[1] : null;
    
    // Determine delivery type and styling
    $delivery_type_text = 'Standard delivery';
    $delivery_icon = '📦';
    $delivery_type_class = 'text-gray-600';
    
    if ($delivery_days === 1) {
        $delivery_type_text = 'Next day delivery';
        $delivery_icon = '⚡';
        $delivery_type_class = 'text-green-600';
    } elseif ($delivery_days === 2) {
        $delivery_type_text = '2 day delivery';
        $delivery_icon = '🚚';
        $delivery_type_class = 'text-blue-600';
    } elseif ($delivery_days === 3) {
        $delivery_type_text = '3 day delivery';
        $delivery_icon = '📦';
        $delivery_type_class = 'text-gray-600';
    } elseif ($delivery_days === 4) {
        $delivery_type_text = '4 day delivery';
        $delivery_icon = '📦';
        $delivery_type_class = 'text-gray-600';
    } elseif ($delivery_days >= 5) {
        $delivery_type_text = $delivery_days . ' day delivery';
        $delivery_icon = '📦';
        $delivery_type_class = 'text-gray-600';
    }
    
    // Check for express services
    $express_keywords = ['quick', 'hyperlocal', 'same-day', 'sameday', 'instant'];
    $is_express = false;
    foreach ($express_keywords as $keyword) {
        if (stripos($courier_name, $keyword) !== false) {
            $is_express = true;
            break;
        }
    }
    
    if ($is_express && $delivery_days <= 1) {
        $delivery_type_text = 'Same day delivery';
        $delivery_icon = '🚀';
        $delivery_type_class = 'text-green-600';
    }
    
    // Clean courier name
    $clean_courier_name = preg_replace('/\b(surface|air|stressed)\b/i', '', $courier_name);
    $clean_courier_name = str_replace('_', ' ', $clean_courier_name);
    $clean_courier_name = trim($clean_courier_name);
    
    // Determine if recommended (first method)
    static $method_count = 0;
    $method_count++;
    $is_recommended = ($method_count === 1);
    
    // Format price
    $display_price = $is_free ? 'FREE' : wc_price($cost);
    
    return array(
        'courier_name' => $clean_courier_name,
        'delivery_type_text' => $delivery_type_text,
        'delivery_icon' => $delivery_icon,
        'delivery_type_class' => $delivery_type_class,
        'is_free' => $is_free,
        'display_price' => $display_price,
        'delivery_days' => $delivery_days,
        'is_recommended' => $is_recommended,
        'is_express' => $is_express
    );
}

/**
 * Get shipping method label classes
 */
function tostishop_get_shipping_method_label_classes($method_id, $chosen_method) {
    $base_classes = 'flex items-center justify-between p-3 bg-white border-2 rounded-lg cursor-pointer transition-all duration-200 hover:border-navy-300 hover:shadow-sm w-full mb-2';
    $checked_classes = ($method_id === $chosen_method) ? 'border-navy-500 bg-navy-50 ring-1 ring-navy-200' : 'border-gray-200';
    
    return $base_classes . ' ' . $checked_classes;
}

/**
 * Get shipping radio button classes
 */
function tostishop_get_shipping_radio_classes($method_id, $chosen_method) {
    $is_checked = ($method_id === $chosen_method);
    
    if ($is_checked) {
        return 'w-4 h-4 border-2 border-navy-500 bg-navy-500 rounded-full flex items-center justify-center';
    } else {
        return 'w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center';
    }
}

/**
 * Template-based shipping display (replaces JavaScript approach)
 * Templates: woocommerce/cart/shipping-methods.php and woocommerce/checkout/shipping.php
 */

/**
 * Style shipping methods output with Tailwind classes
 */
function tostishop_style_shipping_methods_output() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Style shipping methods lists on both cart and checkout pages
        const shippingSelectors = [
            '.shipping-methods-wrapper ul.woocommerce-shipping-methods',
            'ul#shipping_method.woocommerce-shipping-methods'
        ];
        
        shippingSelectors.forEach(function(selector) {
            const shippingLists = document.querySelectorAll(selector);
            
            shippingLists.forEach(function(list) {
                // Style the list container
                list.className = 'space-y-2 mt-3';
                
                // Style each list item
                list.querySelectorAll('li').forEach(function(item) {
                    item.className = '';
                    
                    const label = item.querySelector('label');
                    const radio = item.querySelector('input[type="radio"]');
                    
                    if (label && radio) {
                        // Style radio button
                        radio.className = 'w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500 focus:ring-2 mr-3';
                        
                        // Style label
                        label.className = 'flex items-center justify-between w-full p-3 bg-white border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-200 transition-all duration-200 cursor-pointer';
                        
                        // Get label text and extract price
                        const labelText = label.textContent.trim();
                        const priceRegex = /\((.*?)\)$/;
                        const priceMatch = labelText.match(priceRegex);
                        const price = priceMatch ? priceMatch[1] : '';
                        const methodName = priceMatch ? labelText.replace(priceMatch[0], '').trim() : labelText;
                        
                        // Rebuild label with better structure
                        label.innerHTML = `
                            <div class="flex items-center flex-1">
                                <span class="text-sm font-medium text-gray-900">${methodName}</span>
                            </div>
                            <div class="text-sm font-semibold text-purple-600">${price}</div>
                        `;
                        
                        // Insert radio button at the beginning
                        label.insertBefore(radio, label.firstChild);
                        
                        // Add checked state styling
                        if (radio.checked) {
                            label.classList.add('bg-purple-50', 'border-purple-300');
                            label.classList.remove('bg-white', 'border-gray-200');
                        }
                        
                        // Add click event for dynamic styling
                        radio.addEventListener('change', function() {
                            // Remove checked styling from all labels in all shipping lists
                            document.querySelectorAll('.woocommerce-shipping-methods label').forEach(function(lbl) {
                                lbl.classList.remove('bg-purple-50', 'border-purple-300');
                                lbl.classList.add('bg-white', 'border-gray-200');
                            });
                            
                            // Add checked styling to selected label
                            if (this.checked) {
                                label.classList.remove('bg-white', 'border-gray-200');
                                label.classList.add('bg-purple-50', 'border-purple-300');
                            }
                        });
                    }
                });
            });
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'tostishop_style_shipping_methods_output');

/**
 * Customize Cross-sells and Upsells Display
 */
function tostishop_customize_upsells_cross_sells() {
    // Customize upsells display
    remove_action('woocommerce_output_upsells', 'woocommerce_upsell_display');
    add_action('woocommerce_output_upsells', 'tostishop_output_upsells');
    
    // Customize cross-sells display 
    remove_action('woocommerce_output_cross_sells', 'woocommerce_cross_sell_display');
    add_action('woocommerce_output_cross_sells', 'tostishop_output_cross_sells');
}
add_action('init', 'tostishop_customize_upsells_cross_sells');

/**
 * Custom Upsells Output
 */
function tostishop_output_upsells() {
    $limit = apply_filters('woocommerce_upsells_total', 4);
    $columns = apply_filters('woocommerce_upsells_columns', 4);
    $orderby = apply_filters('woocommerce_upsells_orderby', 'rand');
    $order = apply_filters('woocommerce_upsells_order', 'desc');
    
    // Output upsells using our custom grid layout
    woocommerce_upsell_display($limit, $columns, $orderby, $order);
}

/**
 * Custom Cross-sells Output  
 */
function tostishop_output_cross_sells() {
    $limit = apply_filters('woocommerce_cross_sells_total', 4);
    $columns = apply_filters('woocommerce_cross_sells_columns', 2);
    $orderby = apply_filters('woocommerce_cross_sells_orderby', 'rand');
    $order = apply_filters('woocommerce_cross_sells_order', 'desc');
    
    // Output cross-sells using our custom layout
    woocommerce_cross_sell_display($limit, $columns, $orderby, $order);
}

/**
 * Set limits for upsells and cross-sells display
 */
function tostishop_set_upsells_cross_sells_limits() {
    // Set upsells limit to 4 products
    add_filter('woocommerce_upsells_total', function() { return 4; });
    add_filter('woocommerce_upsells_columns', function() { return 4; });
    
    // Set cross-sells limit to 4 products for better layout
    add_filter('woocommerce_cross_sells_total', function() { return 4; });
    add_filter('woocommerce_cross_sells_columns', function() { return 2; });
}
add_action('init', 'tostishop_set_upsells_cross_sells_limits');

/**
 * Add CSS classes to upsells and cross-sells product items
 */
function tostishop_add_upsells_cross_sells_classes($classes, $class, $product_id) {
    global $woocommerce_loop;
    
    // Check if we're in upsells or cross-sells loop
    if (isset($woocommerce_loop['name']) && 
        ($woocommerce_loop['name'] === 'up-sells' || $woocommerce_loop['name'] === 'cross-sells')) {
        $classes[] = 'group';
    }
    
    return $classes;
}
add_filter('post_class', 'tostishop_add_upsells_cross_sells_classes', 10, 3);
