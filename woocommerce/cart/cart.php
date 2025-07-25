<?php
/**
 * Cart Page Template
 */

get_header(); ?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breadcrumbs -->
    <?php tostishop_breadcrumbs(); ?>
    
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900"><?php _e('Shopping Cart', 'tostishop'); ?></h1>
        <p class="text-gray-600 mt-2"><?php _e('Review your items before checkout', 'tostishop'); ?></p>
    </div>
    
    <?php do_action('woocommerce_before_cart'); ?>
    
    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
        
        <?php if (WC()->cart->is_empty()) : ?>
        
            <!-- Empty Cart -->
            <div class="text-center py-16">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-900 mb-4"><?php _e('Your cart is empty', 'tostishop'); ?></h2>
                <p class="text-gray-600 mb-8"><?php _e('Looks like you haven\'t added anything to your cart yet.', 'tostishop'); ?></p>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
                   class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200">
                    <?php _e('Start Shopping', 'tostishop'); ?>
                </a>
            </div>
            
        <?php else : ?>
        
            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        
                        <!-- Cart Table Header (Desktop) -->
                        <div class="hidden md:grid md:grid-cols-12 gap-4 p-6 border-b border-gray-200 bg-gray-50">
                            <div class="col-span-6 text-sm font-medium text-gray-700"><?php _e('Product', 'tostishop'); ?></div>
                            <div class="col-span-2 text-sm font-medium text-gray-700 text-center"><?php _e('Quantity', 'tostishop'); ?></div>
                            <div class="col-span-2 text-sm font-medium text-gray-700 text-center"><?php _e('Price', 'tostishop'); ?></div>
                            <div class="col-span-2 text-sm font-medium text-gray-700 text-center"><?php _e('Total', 'tostishop'); ?></div>
                        </div>
                        
                        <!-- Cart Items -->
                        <div class="divide-y divide-gray-200">
                            <?php
                            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                                
                                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                            ?>
                            
                            <!-- Mobile Cart Item -->
                            <div class="md:hidden p-6">
                                <div class="flex space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden">
                                            <?php
                                            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                                            if (!$product_permalink) {
                                                echo $thumbnail;
                                            } else {
                                                printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h3 class="text-sm font-medium text-gray-900">
                                                    <?php
                                                    if (!$product_permalink) {
                                                        echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                                    } else {
                                                        echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                                    }
                                                    ?>
                                                </h3>
                                                <p class="text-sm text-gray-500 mt-1"><?php echo wc_get_formatted_cart_item_data($cart_item); ?></p>
                                            </div>
                                            
                                            <!-- Remove Button -->
                                            <button type="button" class="ml-4 text-gray-400 hover:text-red-500" 
                                                    onclick="removeCartItem('<?php echo esc_js($cart_item_key); ?>')">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <div class="flex items-center justify-between mt-4">
                                            <!-- Quantity -->
                                            <div class="flex items-center border border-gray-300 rounded-lg">
                                                <button type="button" class="p-2 text-gray-600 hover:text-gray-800" 
                                                        onclick="updateQuantity('<?php echo esc_js($cart_item_key); ?>', <?php echo $cart_item['quantity'] - 1; ?>)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                </button>
                                                <span class="px-4 py-2 text-sm font-medium"><?php echo $cart_item['quantity']; ?></span>
                                                <button type="button" class="p-2 text-gray-600 hover:text-gray-800" 
                                                        onclick="updateQuantity('<?php echo esc_js($cart_item_key); ?>', <?php echo $cart_item['quantity'] + 1; ?>)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <!-- Price -->
                                            <div class="text-lg font-bold text-gray-900">
                                                <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Desktop Cart Item -->
                            <div class="hidden md:grid md:grid-cols-12 gap-4 p-6 items-center">
                                <!-- Product -->
                                <div class="col-span-6 flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                                        <?php echo $thumbnail; ?>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">
                                            <?php
                                            if (!$product_permalink) {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                            } else {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                            }
                                            ?>
                                        </h3>
                                        <p class="text-sm text-gray-500"><?php echo wc_get_formatted_cart_item_data($cart_item); ?></p>
                                    </div>
                                </div>
                                
                                <!-- Quantity -->
                                <div class="col-span-2 flex justify-center">
                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                        <button type="button" class="p-2 text-gray-600 hover:text-gray-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <span class="px-4 py-2 text-sm font-medium"><?php echo $cart_item['quantity']; ?></span>
                                        <button type="button" class="p-2 text-gray-600 hover:text-gray-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Price -->
                                <div class="col-span-2 text-center">
                                    <span class="text-sm font-medium text-gray-900"><?php echo $_product->get_price_html(); ?></span>
                                </div>
                                
                                <!-- Total -->
                                <div class="col-span-2 flex items-center justify-between">
                                    <span class="text-sm font-bold text-gray-900">
                                        <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                                    </span>
                                    <button type="button" class="ml-4 text-gray-400 hover:text-red-500">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <?php
                                }
                            }
                            ?>
                        </div>
                        
                        <!-- Cart Actions -->
                        <div class="p-6 bg-gray-50 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
                                   class="text-blue-600 hover:text-blue-700 font-medium">
                                    ← <?php _e('Continue Shopping', 'tostishop'); ?>
                                </a>
                                
                                <button type="submit" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>" 
                                        class="bg-gray-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-gray-700 transition-colors duration-200">
                                    <?php _e('Update Cart', 'tostishop'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Cart Totals -->
                <div class="mt-8 lg:mt-0">
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6"><?php _e('Order Summary', 'tostishop'); ?></h3>
                        
                        <?php wc_cart_totals_subtotal_html(); ?>
                        
                        <!-- Shipping Calculator -->
                        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
                            <div class="border-t border-gray-200 pt-6 mt-6">
                                <h4 class="font-medium text-gray-900 mb-4"><?php _e('Shipping', 'tostishop'); ?></h4>
                                <?php wc_cart_totals_shipping_html(); ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Cart Totals -->
                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <?php wc_cart_totals_order_total_html(); ?>
                        </div>
                        
                        <!-- Proceed to Checkout -->
                        <div class="mt-6">
                            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" 
                               class="w-full bg-blue-600 text-white py-4 px-6 rounded-lg text-center font-semibold hover:bg-blue-700 transition-colors duration-200 block">
                                <?php _e('Proceed to Checkout', 'tostishop'); ?>
                            </a>
                        </div>
                        
                        <!-- Security Badge -->
                        <div class="mt-6 text-center">
                            <div class="flex items-center justify-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                                <?php _e('Secure checkout', 'tostishop'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        <?php endif; ?>
        
        <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
    </form>
    
    <?php do_action('woocommerce_after_cart'); ?>
</div>

<!-- Sticky Checkout Button (Mobile) -->
<?php if (!WC()->cart->is_empty()) : ?>
<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 lg:hidden z-20">
    <div class="flex items-center justify-between">
        <div>
            <div class="text-sm text-gray-600"><?php printf(__('%d items', 'tostishop'), WC()->cart->get_cart_contents_count()); ?></div>
            <div class="text-lg font-bold text-gray-900"><?php wc_cart_totals_order_total_html(); ?></div>
        </div>
        <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" 
           class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200">
            <?php _e('Checkout', 'tostishop'); ?>
        </a>
    </div>
</div>
<?php endif; ?>

<?php get_footer(); ?>
