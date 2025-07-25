<?php
/**
 * Review order table - Modern Design
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-checkout-review-order-table">
    
    <!-- Products List -->
    <div class="space-y-4 mb-6">
        <?php
        do_action( 'woocommerce_review_order_before_cart_contents' );

        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                ?>
                <div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'flex items-start space-x-4 p-4 bg-white rounded-lg border border-gray-100 hover:border-gray-200 transition-colors', $cart_item, $cart_item_key ) ); ?>">
                    
                    <!-- Product Image -->
                    <div class="flex-shrink-0">
                        <?php
                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array( 72, 72 ) ), $cart_item, $cart_item_key );

                        if ( ! $_product->is_visible() ) {
                            echo '<div class="w-18 h-18 bg-gray-100 rounded-lg overflow-hidden">' . $thumbnail . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        } else {
                            printf( '<a href="%s" class="block w-18 h-18 bg-gray-100 rounded-lg overflow-hidden hover:opacity-75 transition-opacity">%s</a>', esc_url( $_product->get_permalink( $cart_item ) ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        }
                        ?>
                    </div>

                    <!-- Product Details -->
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <div class="flex-1 min-w-0 pr-4">
                                <h4 class="text-sm font-semibold text-gray-900 line-clamp-2 mb-1">
                                    <?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?>
                                </h4>
                                
                                <?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                
                                <div class="flex items-center mt-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                        <?php echo sprintf( 'Qty: %s', $cart_item['quantity'] ); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Price -->
                            <div class="text-right flex-shrink-0">
                                <span class="text-base font-bold text-gray-900">
                                    <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </span>
                                <?php if ( $cart_item['quantity'] > 1 ) : ?>
                                <div class="text-xs text-gray-500 mt-1">
                                    <?php echo wc_price( $_product->get_price() ); ?> <?php _e('each', 'tostishop'); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }

        do_action( 'woocommerce_review_order_after_cart_contents' );
        ?>
    </div>

    <!-- Order Totals -->
    <div class="border-t-2 border-gray-100 pt-6 space-y-4">

        <!-- Coupon Code Section -->
        <?php if ( wc_coupons_enabled() ) : ?>
        <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-medium text-gray-900 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <?php _e('Have a coupon code?', 'tostishop'); ?>
                </h4>
                <button type="button" 
                        id="toggleCouponForm" 
                        class="text-sm text-primary hover:text-blue-600 font-medium focus:outline-none">
                    <span id="couponToggleText"><?php _e('Add Code', 'tostishop'); ?></span>
                    <svg id="couponToggleIcon" class="w-4 h-4 inline-block ml-1 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
            
            <div id="couponFormContainer" class="hidden">
                <form class="checkout_coupon woocommerce-form-coupon" method="post">
                    <div class="flex space-x-2">
                        <input type="text" 
                               name="coupon_code" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-primary" 
                               placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" 
                               id="coupon_code" 
                               value="" />
                        <button type="submit" 
                                class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1" 
                                name="apply_coupon" 
                                value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>">
                            <?php esc_html_e( 'Apply', 'woocommerce' ); ?>
                        </button>
                    </div>
                    <?php do_action( 'woocommerce_cart_coupon' ); ?>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- Subtotal -->
        <div class="flex justify-between items-center text-base">
            <span class="text-gray-600 font-medium"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span>
            <span class="font-semibold text-gray-900"><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>

        <!-- Applied Coupons -->
        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
            <div class="flex justify-between items-center text-sm bg-green-50 p-3 rounded-lg border border-green-200">
                <span class="text-green-700 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <?php wc_cart_totals_coupon_label( $coupon ); ?>
                </span>
                <span class="text-green-700 font-semibold">
                    <?php wc_cart_totals_coupon_html( $coupon ); ?>
                </span>
            </div>
        <?php endforeach; ?>

        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

            <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

        <!-- Shipping -->
        <div class="flex justify-between items-center text-base">
            <span class="text-gray-600 font-medium"><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></span>
            <span class="font-semibold text-gray-900">
                <?php wc_cart_totals_shipping_html(); ?>
            </span>
        </div>

        <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

        <?php endif; ?>

        <!-- Fees -->
        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600"><?php echo esc_html( $fee->name ); ?></span>
                <span class="font-medium text-gray-900"><?php wc_cart_totals_fee_html( $fee ); ?></span>
            </div>
        <?php endforeach; ?>

        <!-- Tax -->
        <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
            <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600"><?php echo esc_html( $tax->label ); ?></span>
                        <span class="font-medium text-gray-900"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
                    <span class="font-medium text-gray-900"><?php wc_cart_totals_taxes_total_html(); ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

        <!-- Total -->
        <div class="border-t border-gray-200 pt-3">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-900"><?php esc_html_e( 'Total', 'woocommerce' ); ?></span>
                <span class="text-lg font-bold text-primary"><?php wc_cart_totals_order_total_html(); ?></span>
            </div>
        </div>

        <?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

    </div>

</div>

<script>
// Coupon toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleCouponForm');
    const formContainer = document.getElementById('couponFormContainer');
    const toggleText = document.getElementById('couponToggleText');
    const toggleIcon = document.getElementById('couponToggleIcon');
    
    if (toggleButton && formContainer) {
        toggleButton.addEventListener('click', function() {
            const isHidden = formContainer.classList.contains('hidden');
            
            if (isHidden) {
                formContainer.classList.remove('hidden');
                toggleText.textContent = 'Hide';
                toggleIcon.style.transform = 'rotate(180deg)';
            } else {
                formContainer.classList.add('hidden');
                toggleText.textContent = 'Add Code';
                toggleIcon.style.transform = 'rotate(0deg)';
            }
        });
    }
});
</script>

<style>
/* Review Order Styling */
.woocommerce-checkout-review-order-table .shipping-section ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.woocommerce-checkout-review-order-table .shipping-section li {
    margin-bottom: 0.5rem;
}

.woocommerce-checkout-review-order-table .shipping-section input[type="radio"] {
    margin-right: 0.5rem;
    accent-color: #14175b;
}

.woocommerce-checkout-review-order-table .shipping-section label {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 0.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    background-color: #ffffff;
    transition: all 0.2s ease;
}

.woocommerce-checkout-review-order-table .shipping-section label:hover {
    border-color: #14175b;
    background-color: #f8fafc;
}

.woocommerce-checkout-review-order-table .shipping-section input[type="radio"]:checked + label,
.woocommerce-checkout-review-order-table .shipping-section label:has(input[type="radio"]:checked) {
    border-color: #14175b;
    background-color: #eef2ff;
}

/* Mobile Responsive Product Cards */
@media (max-width: 640px) {
    .woocommerce-checkout-review-order-table .flex.items-start {
        flex-direction: column;
    }
    
    .woocommerce-checkout-review-order-table .flex-shrink-0 {
        margin-bottom: 1rem;
    }
    
    .woocommerce-checkout-review-order-table .flex.justify-between {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
