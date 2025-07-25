<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header(); ?>

<div class="max-w-7xl xl:max-w-[95rem] 2xl:max-w-[110rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Simplified Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900"><?php _e('Shopping Cart', 'tostishop'); ?></h1>
        <p class="text-gray-600 mt-2"><?php _e('Review your items before checkout', 'tostishop'); ?></p>
    </div>
    
    <?php do_action('woocommerce_before_cart'); ?>
    
    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
        
        <?php if (WC()->cart->is_empty()) : ?>
        
            <!-- Modern Empty Cart Design -->
            <div class="empty-cart-bg bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="empty-cart-content text-center px-6 py-12 md:py-20">
                    <!-- Cart Icon with Animation -->
                    <div class="empty-cart-icon relative mx-auto mb-8 w-32 h-32 md:w-40 md:h-40">
                        <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full animate-pulse"></div>
                        <div class="relative flex items-center justify-center h-full">
                            <svg class="w-16 h-16 md:w-20 md:h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Empty State Content -->
                    <div class="max-w-md mx-auto">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                            <?php _e('Your cart is empty', 'tostishop'); ?>
                        </h2>
                        <p class="text-gray-600 text-base md:text-lg mb-8 leading-relaxed">
                            <?php _e('Discover amazing products and start building your perfect order today!', 'tostishop'); ?>
                        </p>
                        
                        <!-- Action Buttons -->
                        <div class="space-y-4">
                            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
                               class="empty-cart-cta inline-flex items-center justify-center w-full sm:w-auto bg-gradient-to-r from-primary to-blue-600 text-white px-8 py-4 rounded-xl text-lg font-semibold hover:from-primary/90 hover:to-blue-600/90 transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <?php _e('Start Shopping', 'tostishop'); ?>
                            </a>
                            
                            <!-- Secondary Actions -->
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <?php if (get_option('woocommerce_enable_guest_checkout') !== 'yes' && !is_user_logged_in()) : ?>
                                <a href="<?php echo esc_url(wp_login_url()); ?>" 
                                   class="text-gray-600 hover:text-primary transition-colors duration-200 font-medium">
                                    <?php _e('Sign in to view saved items', 'tostishop'); ?>
                                </a>
                                <?php endif; ?>
                                
                                <?php if (wc_get_page_id('wishlist') > 0) : ?>
                                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('wishlist'))); ?>" 
                                   class="text-gray-600 hover:text-primary transition-colors duration-200 font-medium">
                                    <?php _e('View Wishlist', 'tostishop'); ?>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Trust Indicators -->
                    <div class="mt-12 pt-8 border-t border-gray-100">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
                            <div class="trust-indicator flex flex-col items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700"><?php _e('Quality Products', 'tostishop'); ?></span>
                            </div>
                            
                            <div class="trust-indicator flex flex-col items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700"><?php _e('Fast Delivery', 'tostishop'); ?></span>
                            </div>
                            
                            <div class="trust-indicator flex flex-col items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700"><?php _e('Secure Payment', 'tostishop'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Featured Categories or Products Section -->
            <?php if (function_exists('wc_get_product_category_list') && get_option('woocommerce_shop_page_display') !== '') : ?>
            <div class="mt-12">
                <div class="text-center mb-8">
                    <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">
                        <?php _e('Popular Categories', 'tostishop'); ?>
                    </h3>
                    <p class="text-gray-600">
                        <?php _e('Browse our most popular product categories', 'tostishop'); ?>
                    </p>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php
                    $product_categories = get_terms(array(
                        'taxonomy' => 'product_cat',
                        'number' => 4,
                        'hide_empty' => true,
                        'exclude' => array(get_option('default_product_cat'))
                    ));
                    
                    if (!empty($product_categories) && !is_wp_error($product_categories)) :
                        foreach ($product_categories as $category) :
                            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                            $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : wc_placeholder_img_src();
                    ?>
                    <a href="<?php echo esc_url(get_term_link($category)); ?>" 
                       class="category-card group bg-white rounded-xl border border-gray-200 p-6 text-center hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full overflow-hidden">
                            <img src="<?php echo esc_url($image_url); ?>" 
                                 alt="<?php echo esc_attr($category->name); ?>"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-200">
                        </div>
                        <h4 class="font-semibold text-gray-900 group-hover:text-primary transition-colors duration-200">
                            <?php echo esc_html($category->name); ?>
                        </h4>
                        <p class="text-sm text-gray-500 mt-1">
                            <?php printf(_n('%s product', '%s products', $category->count, 'tostishop'), $category->count); ?>
                        </p>
                    </a>
                    <?php 
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
            <?php endif; ?>
            
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
                                                        <!-- Mobile Cart Item -->
                            <div class="md:hidden p-6 cart-item" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
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
                                            <?php
                                            echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                                '<a href="%s" class="ml-4 text-gray-400 hover:text-red-500 transition-colors duration-200 cart-remove-btn" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </a>',
                                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                esc_html__( 'Remove this item', 'woocommerce' ),
                                                esc_attr( $product_id ),
                                                esc_attr( $_product->get_sku() ),
                                                esc_attr( $cart_item_key )
                                            ), $cart_item_key );
                                            ?>
                                        </div>
                                        
                                        <div class="flex items-center justify-between mt-4">
                                            <!-- Quantity -->
                                            <div class="quantity border border-gray-300 rounded-lg">
                                                <?php
                                                if ( $_product->is_sold_individually() ) {
                                                    $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                                } else {
                                                    $product_quantity = woocommerce_quantity_input(
                                                        array(
                                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                                            'input_value'  => $cart_item['quantity'],
                                                            'max_value'    => $_product->get_max_purchase_quantity(),
                                                            'min_value'    => '0',
                                                            'product_name' => $_product->get_name(),
                                                        ),
                                                        $_product,
                                                        false
                                                    );
                                                }
                                                echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
                                                ?>
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
                                                        <!-- Desktop Cart Item -->
                            <div class="hidden md:grid md:grid-cols-12 gap-4 p-6 items-center cart-item" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
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
                                    <div class="quantity">
                                    <?php
                                    if ( $_product->is_sold_individually() ) {
                                        $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                    } else {
                                        $product_quantity = woocommerce_quantity_input(
                                            array(
                                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                                'input_value'  => $cart_item['quantity'],
                                                'max_value'    => $_product->get_max_purchase_quantity(),
                                                'min_value'    => '0',
                                                'product_name' => $_product->get_name(),
                                            ),
                                            $_product,
                                            false
                                        );
                                    }
                                    echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
                                    ?>
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
                                    <?php
                                    echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                        '<a href="%s" class="ml-4 text-gray-400 hover:text-red-500 transition-colors duration-200 cart-remove-btn" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </a>',
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                        esc_html__( 'Remove this item', 'woocommerce' ),
                                        esc_attr( $product_id ),
                                        esc_attr( $_product->get_sku() ),
                                        esc_attr( $cart_item_key )
                                    ), $cart_item_key );
                                    ?>
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
                        
                        <!-- Cart Totals Table -->
                        <div class="space-y-3">
                            <!-- Subtotal -->
                            <div class="flex justify-between items-center text-base">
                                <span class="text-gray-600 font-medium"><?php _e('Subtotal', 'woocommerce'); ?></span>
                                <span class="font-semibold text-gray-900"><?php wc_cart_totals_subtotal_html(); ?></span>
                            </div>
                            
                            <!-- Coupon Code Section -->
                            <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-900 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <?php _e('Coupon Code', 'woocommerce'); ?>
                                    </h4>
                                    <button type="button" class="text-primary text-sm font-medium hover:text-primary/80 transition-colors" id="toggle-coupon">
                                        <?php _e('Add Code', 'tostishop'); ?>
                                    </button>
                                </div>
                                
                                <div class="coupon-form hidden" id="coupon-form">
                                    <div class="flex space-x-2">
                                        <div class="flex-1">
                                            <input type="text" name="coupon_code" id="coupon_code" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" placeholder="<?php _e('Enter coupon code', 'woocommerce'); ?>" value="">
                                        </div>
                                        <button type="submit" name="apply_coupon" value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>" class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary/90 transition-colors">
                                            <?php _e('Apply', 'woocommerce'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Applied Coupons -->
                            <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                                <div class="flex justify-between items-center text-base bg-green-50 p-3 rounded-lg">
                                    <span class="text-green-700 font-medium">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <?php echo apply_filters('woocommerce_cart_totals_coupon_label', esc_html($coupon->get_code()), $coupon); ?>
                                    </span>
                                    <span class="text-green-800 font-semibold">
                                        <?php wc_cart_totals_coupon_html($coupon); ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                            
                            <!-- Shipping -->
                            <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
                                <div class="flex justify-between items-center text-base border-t border-gray-200 pt-3">
                                    <span class="text-gray-600 font-medium"><?php _e('Shipping', 'woocommerce'); ?></span>
                                    <div class="text-right">
                                        <?php wc_cart_totals_shipping_html(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Tax -->
                            <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
                                <div class="flex justify-between items-center text-base">
                                    <span class="text-gray-600 font-medium"><?php echo esc_html($tax->label); ?></span>
                                    <span class="font-semibold text-gray-900"><?php echo wp_kses_post($tax->formatted_amount); ?></span>
                                </div>
                            <?php endforeach; ?>
                            
                            <!-- Order Total -->
                            <div class="flex justify-between items-center text-xl font-bold text-gray-900 bg-gray-50 p-4 rounded-lg border-t-2 border-primary mt-4">
                                <span><?php _e('Total', 'woocommerce'); ?></span>
                                <span class="text-primary"><?php wc_cart_totals_order_total_html(); ?></span>
                            </div>
                        </div>
                        
                        <!-- Proceed to Checkout -->
                        <div class="mt-6">
                            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" 
                               class="w-full bg-gradient-to-r from-primary to-blue-600 text-white py-4 px-6 rounded-lg text-center font-semibold hover:from-primary/90 hover:to-blue-600/90 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl block">
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

<style>
/* Custom styling for WooCommerce quantity inputs */
.woocommerce .quantity {
    display: flex;
    align-items: center;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    overflow: hidden;
}

.woocommerce .quantity .qty {
    width: 60px;
    height: 40px;
    text-align: center;
    border: none;
    outline: none;
    background: white;
    font-weight: 500;
    font-size: 0.875rem;
    -moz-appearance: textfield;
}

.woocommerce .quantity .qty::-webkit-outer-spin-button,
.woocommerce .quantity .qty::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Quantity button styling */
.woocommerce .quantity .plus,
.woocommerce .quantity .minus {
    width: 40px;
    height: 40px;
    background: #f9fafb;
    border: none;
    color: #6b7280;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    font-size: 1.2rem;
    font-weight: bold;
}

.woocommerce .quantity .plus:hover,
.woocommerce .quantity .minus:hover {
    background: #f3f4f6;
    color: #374151;
}

.woocommerce .quantity .minus {
    border-right: 1px solid #e5e7eb;
}

.woocommerce .quantity .plus {
    border-left: 1px solid #e5e7eb;
}

/* Remove button styling */
.woocommerce-cart-form .product-remove a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2rem;
    height: 2rem;
    color: #9ca3af;
    transition: color 0.2s ease;
    text-decoration: none;
}

.woocommerce-cart-form .product-remove a:hover {
    color: #ef4444;
}

/* Mobile responsive adjustments */
@media (max-width: 768px) {
    .woocommerce .quantity {
        justify-content: center;
        margin: 0 auto;
    }
    
    .woocommerce .quantity .qty {
        width: 50px;
        height: 36px;
        font-size: 0.8rem;
    }
    
    .woocommerce .quantity .plus,
    .woocommerce .quantity .minus {
        width: 36px;
        height: 36px;
        font-size: 1rem;
    }
}

/* Cart totals section styling */
.cart_totals {
    background: white;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
    padding: 1.5rem;
}

.cart_totals h2 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 1.5rem;
}

.cart_totals table {
    width: 100%;
    border-collapse: collapse;
}

.cart_totals th,
.cart_totals td {
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.cart_totals th {
    text-align: left;
    font-weight: 500;
    color: #6b7280;
}

.cart_totals td {
    text-align: right;
    font-weight: 600;
    color: #111827;
}

.cart_totals .order-total th,
.cart_totals .order-total td {
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    border-top: 2px solid #e5e7eb;
    padding-top: 1rem;
}

/* Shipping calculator styling */
.shipping-calculator-form {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-top: 1rem;
}

.shipping-calculator-form .form-row {
    margin-bottom: 1rem;
}

.shipping-calculator-form label {
    display: block;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.25rem;
}

.shipping-calculator-form select,
.shipping-calculator-form input[type="text"] {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
}

.shipping-calculator-form button {
    background: #3b82f6;
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.shipping-calculator-form button:hover {
    background: #2563eb;
}

/* WooCommerce messages styling */
.woocommerce-message,
.woocommerce-error,
.woocommerce-info {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 0.5rem;
    border-left: 4px solid;
}

.woocommerce-message {
    background: #f0fdf4;
    border-left-color: #22c55e;
    color: #15803d;
}

.woocommerce-error {
    background: #fef2f2;
    border-left-color: #ef4444;
    color: #dc2626;
}

.woocommerce-info {
    background: #eff6ff;
    border-left-color: #3b82f6;
    color: #1d4ed8;
}

/* Loading states */
.woocommerce-cart-form.processing {
    opacity: 0.6;
    pointer-events: none;
}

.woocommerce-cart-form.processing::after {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

/* Update cart button styling */
.actions .button {
    background: #6b7280;
    color: white;
    padding: 0.5rem 1.5rem;
    border: none;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.actions .button:hover {
    background: #4b5563;
}

/* Coupon form styling */
.coupon-form.hidden {
    display: none;
}

.coupon-form {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Enhanced Coupon Form */
.coupon-form input[type="text"] {
    border: 2px solid #e5e7eb;
    transition: all 0.2s ease;
}

.coupon-form input[type="text"]:focus {
    border-color: #14175b;
    box-shadow: 0 0 0 3px rgba(20, 23, 91, 0.1);
    outline: none;
}

.coupon-form button[type="submit"] {
    background: linear-gradient(135deg, #14175b 0%, #1e3a8a 100%);
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.coupon-form button[type="submit"]:hover {
    background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(20, 23, 91, 0.3);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Basic cart functionality
    initializeCouponForm();
    
    // Handle quantity input validation
    const quantityInputs = document.querySelectorAll('.woocommerce-cart-form .qty');
    quantityInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            const min = parseInt(this.getAttribute('min')) || 0;
            const max = parseInt(this.getAttribute('max')) || 9999;
            let value = parseInt(this.value) || min;
            
            if (value < min) value = min;
            if (value > max) value = max;
            
            this.value = value;
        });
    });
});

/**
 * Initialize coupon form functionality
 */
function initializeCouponForm() {
    const toggleBtn = document.getElementById('toggle-coupon');
    const couponForm = document.getElementById('coupon-form');
    
    if (toggleBtn && couponForm) {
        toggleBtn.addEventListener('click', function() {
            couponForm.classList.toggle('hidden');
            toggleBtn.textContent = couponForm.classList.contains('hidden') ? 
                'Add Code' : 'Cancel';
        });
    }
    
    // Handle coupon form submission
    const form = document.getElementById('coupon-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const couponCode = document.getElementById('coupon_code').value;
            if (couponCode.trim()) {
                // Add coupon input to main form
                const checkoutForm = document.querySelector('.woocommerce-cart-form');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'coupon_code';
                input.value = couponCode;
                checkoutForm.appendChild(input);
                
                const button = document.createElement('input');
                button.type = 'hidden';
                button.name = 'apply_coupon';
                button.value = 'Apply coupon';
                checkoutForm.appendChild(button);
                
                checkoutForm.submit();
            }
        });
    }
}
</script>
