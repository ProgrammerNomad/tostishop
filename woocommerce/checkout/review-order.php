<?php
/**
 * Review order table - Mobile-First Design
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>

<!-- Mobile-First Order Review -->
<div class="woocommerce-checkout-review-order-table">
    
    <?php do_action( 'woocommerce_review_order_before_cart_contents' ); ?>
    
    <!-- Products List - Mobile Optimized -->
    <div class="space-y-3 mb-6">
        <?php
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                ?>
                <div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'p-3 bg-white rounded-lg border border-gray-100', $cart_item, $cart_item_key ) ); ?>">
                    
                    <!-- Mobile: Stacked Layout -->
                    <div class="flex items-center space-x-3">
                        <!-- Product Image - Smaller on Mobile -->
                        <div class="flex-shrink-0">
                            <?php
                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array( 60, 60 ) ), $cart_item, $cart_item_key );
                            if ( ! $_product->is_visible() ) {
                                echo '<div class="w-15 h-15 bg-gray-100 rounded-lg overflow-hidden">' . $thumbnail . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            } else {
                                printf( '<a href="%s" class="block w-15 h-15 bg-gray-100 rounded-lg overflow-hidden">%s</a>', esc_url( $_product->get_permalink( $cart_item ) ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            }
                            ?>
                        </div>

                        <!-- Product Details - Mobile Optimized -->
                        <div class="flex-1 min-w-0">
                            <!-- Product Name -->
                            <h4 class="text-sm font-medium text-gray-900 mb-1 line-clamp-2">
                                <?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?>
                            </h4>
                            
                            <!-- Product Meta - Compact on Mobile -->
                            <?php if ( wc_get_formatted_cart_item_data( $cart_item ) ) : ?>
                            <div class="text-xs text-gray-500 mb-2">
                                <?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Quantity and Price Row - Mobile Layout -->
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                    <?php echo sprintf( __( 'Qty: %s', 'tostishop' ), $cart_item['quantity'] ); ?>
                                </span>
                                <span class="text-sm font-bold text-gray-900">
                                    <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </span>
                            </div>
                            
                            <!-- Unit Price - Show on Mobile if Multiple Quantity -->
                            <?php if ( $cart_item['quantity'] > 1 ) : ?>
                            <div class="text-xs text-gray-500 mt-1 text-right">
                                <?php echo wc_price( $_product->get_price() ); ?> <?php _e('each', 'tostishop'); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>

    <?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>

    <!-- Order Totals - Mobile Optimized -->
    <div class="border-t border-gray-200 pt-4 space-y-3">

        <!-- Mobile-First Coupon Section -->
        <?php if ( wc_coupons_enabled() ) : ?>
        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-medium text-gray-900 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <?php _e('Promo Code', 'tostishop'); ?>
                </h4>
                <button type="button" 
                        class="text-primary text-sm font-medium hover:text-blue-600 transition-colors touch-target" 
                        id="toggle-coupon"
                        aria-expanded="false"
                        aria-controls="coupon-form">
                    <span id="coupon-toggle-text"><?php _e('Add Code', 'tostishop'); ?></span>
                </button>
            </div>
            
            <!-- Mobile-Optimized Coupon Form -->
            <div class="coupon-form hidden" id="coupon-form">
                <form method="post" class="space-y-3">
                    <div class="space-y-2">
                        <input type="text" 
                               name="coupon_code" 
                               id="coupon_code" 
                               class="w-full px-3 py-3 border border-gray-300 rounded-lg text-base focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all touch-target" 
                               placeholder="<?php esc_attr_e( 'Enter promo code', 'tostishop' ); ?>" 
                               autocomplete="off"
                               value="" />
                        <button type="submit" 
                                name="apply_coupon" 
                                value="<?php esc_attr_e( 'Apply', 'tostishop' ); ?>"
                                class="w-full px-4 py-3 bg-primary text-white text-base font-medium rounded-lg hover:bg-blue-600 transition-colors touch-target">
                            <?php esc_html_e( 'Apply Coupon', 'tostishop' ); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- Subtotal -->
        <div class="flex justify-between items-center py-2">
            <span class="text-sm text-gray-600"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span>
            <span class="text-sm font-semibold text-gray-900"><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>

        <!-- Applied Coupons - Mobile Friendly -->
        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
            <div class="flex justify-between items-center py-2 px-3 bg-green-50 rounded-lg border border-green-200">
                <span class="text-sm text-green-700 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="truncate"><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
                </span>
                <span class="text-sm text-green-700 font-semibold ml-2 flex-shrink-0">
                    <?php wc_cart_totals_coupon_html( $coupon ); ?>
                </span>
            </div>
        <?php endforeach; ?>

        <!-- Shipping - Mobile Layout -->
        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
            <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
            <div class="flex justify-between items-start py-2">
                <span class="text-sm text-gray-600"><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></span>
                <div class="text-right">
                    <span class="text-sm font-semibold text-gray-900">
                        <?php wc_cart_totals_shipping_html(); ?>
                    </span>
                </div>
            </div>
            <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
        <?php endif; ?>

        <!-- Fees -->
        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div class="flex justify-between items-center py-2">
                <span class="text-sm text-gray-600"><?php echo esc_html( $fee->name ); ?></span>
                <span class="text-sm font-semibold text-gray-900"><?php wc_cart_totals_fee_html( $fee ); ?></span>
            </div>
        <?php endforeach; ?>

        <!-- Tax -->
        <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
            <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-600"><?php echo esc_html( $tax->label ); ?></span>
                        <span class="text-sm font-semibold text-gray-900"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm text-gray-600"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
                    <span class="text-sm font-semibold text-gray-900"><?php wc_cart_totals_taxes_total_html(); ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

        <!-- Mobile-Optimized Total -->
        <div class="border-t-2 border-primary/20 pt-3 mt-3">
            <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-primary/5 to-primary/10 rounded-lg">
                <span class="text-lg font-bold text-gray-900"><?php esc_html_e( 'Total', 'woocommerce' ); ?></span>
                <span class="text-lg font-bold text-primary"><?php wc_cart_totals_order_total_html(); ?></span>
            </div>
        </div>

        <?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

    </div>
</div>

<style>
/* Mobile-First CSS - Touch-Friendly Design */
.touch-target {
    min-height: 44px;
    min-width: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Coupon Form Mobile Styles */
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

/* Mobile-First Form Inputs */
.coupon-form input[type="text"] {
    font-size: 16px; /* Prevents zoom on iOS */
    -webkit-appearance: none;
    appearance: none;
}

.coupon-form input[type="text"]:focus {
    outline: none;
    border-color: #14175b;
    box-shadow: 0 0 0 3px rgba(20, 23, 91, 0.1);
}

.coupon-form button[type="submit"] {
    font-size: 16px; /* Prevents zoom on iOS */
    -webkit-appearance: none;
    appearance: none;
    background: linear-gradient(135deg, #14175b 0%, #1e3a8a 100%);
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.coupon-form button[type="submit"]:hover,
.coupon-form button[type="submit"]:active {
    background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%);
    transform: translateY(-1px);
}

/* Mobile Product Card Optimizations */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Mobile Responsive Adjustments */
@media (max-width: 640px) {
    /* Ensure proper touch targets */
    .woocommerce-checkout-review-order-table button,
    .woocommerce-checkout-review-order-table input[type="submit"] {
        min-height: 44px;
        padding: 12px 16px;
        font-size: 16px;
    }
    
    /* Stack coupon form elements */
    .coupon-form .space-y-2 > * + * {
        margin-top: 0.5rem;
    }
    
    /* Optimize spacing for mobile */
    .woocommerce-checkout-review-order-table .space-y-3 > * + * {
        margin-top: 0.75rem;
    }
    
    /* Better text sizing for mobile */
    .woocommerce-checkout-review-order-table .text-sm {
        font-size: 14px;
    }
    
    .woocommerce-checkout-review-order-table .text-xs {
        font-size: 12px;
    }
}

/* Tablet and Up Enhancements */
@media (min-width: 768px) {
    /* Slightly larger product images on tablets */
    .w-15 {
        width: 4rem;
        height: 4rem;
    }
    
    /* Better spacing on larger screens */
    .woocommerce-checkout-review-order-table .p-3 {
        padding: 1rem;
    }
}

/* Loading States for Mobile */
.coupon-form button[type="submit"]:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.coupon-form button[type="submit"].loading {
    position: relative;
    color: transparent;
}

.coupon-form button[type="submit"].loading::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 16px;
    height: 16px;
    margin: -8px 0 0 -8px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggle-coupon');
    const couponForm = document.getElementById('coupon-form');
    const toggleText = document.getElementById('coupon-toggle-text');
    
    if (toggleButton && couponForm && toggleText) {
        toggleButton.addEventListener('click', function() {
            const isHidden = couponForm.classList.contains('hidden');
            
            if (isHidden) {
                couponForm.classList.remove('hidden');
                toggleText.textContent = '<?php echo esc_js( __( 'Cancel', 'tostishop' ) ); ?>';
                toggleButton.setAttribute('aria-expanded', 'true');
                
                // Focus on input for better UX
                const input = couponForm.querySelector('input[type="text"]');
                if (input) {
                    setTimeout(() => input.focus(), 100);
                }
            } else {
                couponForm.classList.add('hidden');
                toggleText.textContent = '<?php echo esc_js( __( 'Add Code', 'tostishop' ) ); ?>';
                toggleButton.setAttribute('aria-expanded', 'false');
            }
        });
    }
    
    // Enhanced mobile-friendly form submission
    const couponFormElement = couponForm?.querySelector('form');
    if (couponFormElement) {
        couponFormElement.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const couponCode = document.getElementById('coupon_code').value.trim();
            if (!couponCode) {
                // Mobile-friendly alert
                const input = document.getElementById('coupon_code');
                input.focus();
                input.style.borderColor = '#ef4444';
                setTimeout(() => {
                    input.style.borderColor = '';
                }, 3000);
                return;
            }
            
            // Mobile-friendly loading state
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.textContent = '<?php echo esc_js( __( 'Applying...', 'tostishop' ) ); ?>';
            submitButton.disabled = true;
            submitButton.classList.add('loading');
            
            // Create form data for submission
            const formData = new FormData();
            formData.append('coupon_code', couponCode);
            formData.append('apply_coupon', '1');
            
            // Submit coupon
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            }).then(response => {
                // Reload page to show updated totals
                window.location.reload();
            }).catch(error => {
                console.error('Error:', error);
                submitButton.textContent = originalText;
                submitButton.disabled = false;
                submitButton.classList.remove('loading');
                
                // Show error on mobile
                alert('<?php echo esc_js( __( 'Error applying coupon. Please try again.', 'tostishop' ) ); ?>');
            });
        });
    }
    
    // Mobile scroll behavior for long order summaries
    if (window.innerWidth <= 768) {
        const orderReview = document.querySelector('.woocommerce-checkout-review-order-table');
        if (orderReview && orderReview.scrollHeight > window.innerHeight * 0.6) {
            orderReview.style.maxHeight = (window.innerHeight * 0.6) + 'px';
            orderReview.style.overflowY = 'auto';
            orderReview.style.webkitOverflowScrolling = 'touch';
        }
    }
});
</script>
