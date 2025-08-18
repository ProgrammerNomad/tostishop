<?php
/**
 * TostiShop WooCommerce Customizations
 * All WooCommerce-related modifications and enhancements
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
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
    
    // Remove add to cart buttons from all pages except single product
    tostishop_remove_add_to_cart_buttons();
}
add_action('init', 'tostishop_woocommerce_customizations');

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
 * Remove add to cart buttons from all pages except single product
 */
function tostishop_remove_add_to_cart_buttons() {
    // Remove ALL add to cart buttons from archive/shop pages
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    
    // Keep the add to cart functionality only on single product pages
    if (!is_product()) {
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    }
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
 * Improve checkout shipping methods display with pure Tailwind CSS
 */
function tostishop_improve_checkout_shipping_display() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add a small delay to ensure DOM is fully loaded
        setTimeout(function() {
            styleShippingMethods();
        }, 100);
        
        // Re-run when checkout updates
        if (typeof jQuery !== 'undefined') {
            jQuery(document.body).on('updated_checkout', function() {
                setTimeout(function() {
                    styleShippingMethods();
                }, 100);
            });
        }
        
        function styleShippingMethods() {
            const shippingMethods = document.getElementById('shipping_method');
            if (!shippingMethods) return;
            
            // Find and style the parent shipping section - PURE TAILWIND
            const shippingContainer = shippingMethods.closest('.flex.justify-between.items-center.text-base');
            if (shippingContainer) {
                // Pure Tailwind utility classes - no custom CSS needed
                shippingContainer.className = 'bg-white border border-gray-200 rounded-lg p-4 mb-4 shadow-sm';
                
                // Find and style the shipping label with pure Tailwind
                const shippingLabel = shippingContainer.querySelector('.text-gray-600.font-medium');
                if (shippingLabel) {
                    shippingLabel.className = 'border-b border-gray-200 pb-3 mb-3';
                    shippingLabel.innerHTML = `
                        <div class="flex items-center text-base font-bold text-navy-900">
                            <div class="flex items-center justify-center w-8 h-8 bg-navy-100 rounded-lg mr-2">
                                <svg class="w-4 h-4 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4-8-4m16 0v10l-8 4-8-4V7"></path>
                                </svg>
                            </div>
                            Choose Shipping Method
                        </div>
                    `;
                }
                
                // Remove duplicate "Shipping" text
                const duplicateShippingSpan = shippingContainer.querySelector('.font-semibold.text-gray-900');
                if (duplicateShippingSpan && duplicateShippingSpan.textContent.trim() === 'Shipping') {
                    duplicateShippingSpan.style.display = 'none';
                }
            }
            
            // Style shipping methods with pure Tailwind
            if (shippingMethods) {
                shippingMethods.className = 'space-y-2';
                
                // Style each shipping method item
                const shippingItems = shippingMethods.querySelectorAll('li');
                shippingItems.forEach((item, index) => {
                    const input = item.querySelector('input[type="radio"]');
                    const label = item.querySelector('label');
                    
                    if (input && label) {
                        // Pure Tailwind styling - no custom classes
                        item.className = '';
                        input.className = 'sr-only shipping_method';
                        
                        const isChecked = input.checked;
                        const baseClasses = 'flex items-center justify-between p-3 bg-white border-2 rounded-lg cursor-pointer transition-all duration-200 hover:border-navy-300 hover:shadow-sm w-full';
                        const checkedClasses = isChecked ? 'border-navy-500 bg-navy-50 ring-1 ring-navy-200' : 'border-gray-200';
                        
                        label.className = `${baseClasses} ${checkedClasses}`;
                        
                        // Parse label content and extract delivery information
                        const labelText = label.textContent || label.innerText;
                        const parts = labelText.split(':');
                        const methodInfo = parts[0]?.trim() || '';
                        const priceMatch = labelText.match(/₹[\d,]+/);
                        const priceText = priceMatch ? priceMatch[0] : '';
                        
                        // Extract courier name and delivery days from method info
                        const courierParts = methodInfo.split(' - ');
                        const courierName = courierParts[0] || '';
                        const deliveryInfo = courierParts[1] || '';
                        
                        // Parse delivery days from the API data
                        const deliveryDaysMatch = deliveryInfo.match(/(\d+)\s+days?\s+delivery/i);
                        const deliveryDays = deliveryDaysMatch ? parseInt(deliveryDaysMatch[1]) : null;
                        
                        // Determine delivery type and text based on actual API data
                        let deliveryTypeText = '';
                        let deliveryIcon = '';
                        let deliveryTypeClass = 'text-blue-600';
                        
                        if (deliveryDays === 1) {
                            deliveryTypeText = 'Next day delivery';
                            deliveryIcon = '⚡';
                            deliveryTypeClass = 'text-green-600';
                        } else if (deliveryDays === 2) {
                            deliveryTypeText = '2 day delivery';
                            deliveryIcon = '🚚';
                            deliveryTypeClass = 'text-blue-600';
                        } else if (deliveryDays === 3) {
                            deliveryTypeText = '3 day delivery';
                            deliveryIcon = '📦';
                            deliveryTypeClass = 'text-gray-600';
                        } else if (deliveryDays === 4) {
                            deliveryTypeText = '4 day delivery';
                            deliveryIcon = '📦';
                            deliveryTypeClass = 'text-gray-600';
                        } else if (deliveryDays >= 5) {
                            deliveryTypeText = `${deliveryDays} day delivery`;
                            deliveryIcon = '📦';
                            deliveryTypeClass = 'text-gray-600';
                        } else {
                            // Fallback for unclear delivery info
                            deliveryTypeText = 'Standard delivery';
                            deliveryIcon = '📦';
                            deliveryTypeClass = 'text-gray-600';
                        }
                        
                        // Check for same-day delivery keywords in courier name (rare case)
                        const sameDayKeywords = ['quick', 'hyperlocal', 'same-day', 'sameday', 'instant'];
                        const isSameDayService = sameDayKeywords.some(keyword => 
                            courierName.toLowerCase().includes(keyword)
                        );
                        
                        if (isSameDayService && deliveryDays <= 1) {
                            deliveryTypeText = 'Same day delivery';
                            deliveryIcon = '🚀';
                            deliveryTypeClass = 'text-green-600';
                        }
                        
                        // Determine if this is a free shipping method
                        const isFreeMethod = priceText === '₹0' || labelText.includes('(FREE)');
                        const displayPrice = isFreeMethod ? 'FREE' : priceText;
                        
                        // Clean courier name (remove unnecessary parts)
                        const cleanCourierName = courierName
                            .replace(/surface/i, '')
                            .replace(/air/i, '')
                            .replace(/stressed/i, '')
                            .replace(/_/g, ' ')
                            .trim();
                        
                        // Create new label structure with pure Tailwind
                        label.innerHTML = `
                            <div class="flex items-center flex-1">
                                <div class="flex items-center mr-3">
                                    <div class="w-4 h-4 border-2 ${isChecked ? 'border-navy-500 bg-navy-500' : 'border-gray-300'} rounded-full flex items-center justify-center">
                                        ${isChecked ? '<div class="w-1.5 h-1.5 bg-white rounded-full"></div>' : ''}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-semibold text-gray-900 text-sm truncate">${cleanCourierName}</span>
                                        ${index === 0 ? '<span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-accent text-white flex-shrink-0">⭐</span>' : ''}
                                        ${isFreeMethod ? '<span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 flex-shrink-0">FREE</span>' : ''}
                                    </div>
                                    <div class="text-xs ${deliveryTypeClass} flex items-center">
                                        <span class="mr-1">${deliveryIcon}</span>
                                        <span>${deliveryTypeText}</span>
                                        <span class="mx-2">•</span>
                                        <span>COD Available</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right ml-3">
                                <span class="text-lg font-bold ${isFreeMethod ? 'text-green-600' : 'text-navy-900'}">${displayPrice}</span>
                            </div>
                        `;
                        
                        // Add change event listener for dynamic updates
                        input.addEventListener('change', function() {
                            updateShippingMethodStyles();
                        });
                    }
                });
            }
        }
        
        function updateShippingMethodStyles() {
            const shippingItems = document.querySelectorAll('#shipping_method li');
            shippingItems.forEach((item) => {
                const input = item.querySelector('input[type="radio"]');
                const label = item.querySelector('label');
                const radioButton = label?.querySelector('.w-4.h-4');
                
                if (input && label && radioButton) {
                    if (input.checked) {
                        // Checked state - pure Tailwind classes
                        label.className = label.className.replace('border-gray-200', 'border-navy-500 bg-navy-50 ring-1 ring-navy-200');
                        radioButton.className = 'w-4 h-4 border-2 border-navy-500 bg-navy-500 rounded-full flex items-center justify-center';
                        radioButton.innerHTML = '<div class="w-1.5 h-1.5 bg-white rounded-full"></div>';
                    } else {
                        // Unchecked state - pure Tailwind classes
                        label.className = label.className.replace('border-navy-500 bg-navy-50 ring-1 ring-navy-200', 'border-gray-200');
                        radioButton.className = 'w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center';
                        radioButton.innerHTML = '';
                    }
                }
            });
        }
    });
    </script>
    
    
    <?php
}
add_action('wp_footer', 'tostishop_improve_checkout_shipping_display');
