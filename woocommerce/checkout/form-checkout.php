<?php
/**
 * Checkout Form - Enhanced Mobile-First Design
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
    return;
}

?>

<div class="container mx-auto px-4 py-8">
    
    <!-- Page Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            <?php _e('Checkout', 'tostishop'); ?>
        </h1>
        <p class="text-gray-600">
            <?php _e('Complete your order securely and safely', 'tostishop'); ?>
        </p>
    </div>
    
    <!-- Back to Cart Link -->
    <div class="mb-6">
        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" 
           class="inline-flex items-center text-sm text-gray-600 hover:text-primary transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <?php _e('Back to Cart', 'tostishop'); ?>
        </a>
    </div>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

        <?php if ( $checkout->get_checkout_fields() ) : ?>

            <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

            <!-- Modern Checkout Layout -->
            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                
                <!-- Left Column - Customer Details (Mobile: full width, Desktop: 2 columns) -->
                <div class="lg:col-span-2 mb-8 lg:mb-0">
                    
                    <!-- Enhanced Customer Information Card -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6 transition-all duration-200 hover:shadow-md">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <div class="p-2 bg-primary/10 rounded-lg mr-3">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <?php _e('Billing and Shipping Details', 'tostishop'); ?>
                            <span class="ml-2 text-sm bg-primary/10 text-primary px-2 py-1 rounded-full">
                                <?php _e('Required', 'tostishop'); ?>
                            </span>
                        </h2>

                        <div class="w-full" id="customer_details">
                            <div class="space-y-1">
                                <?php do_action( 'woocommerce_checkout_billing' ); ?>
                            </div>

                            <!-- Shipping form is now empty as fields are handled in billing -->
                            <div class="hidden">
                                <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                            </div>
                        </div>
                    </div>

                    <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

                </div>

                <!-- Enhanced Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 shadow-sm p-6 sticky top-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <div class="p-2 bg-primary/10 rounded-lg mr-3">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <?php _e('Order Summary', 'tostishop'); ?>
                        </h2>
                        
                        <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

                        <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                        </div>

                        <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                        
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

    </form>

    <?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
</div>