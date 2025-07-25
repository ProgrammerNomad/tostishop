<?php
/**
 * Minimal Checkout Form
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
    return;
}
?>

<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8"><?php _e('Checkout', 'tostishop'); ?></h1>
    
    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

        <?php if ( $checkout->get_checkout_fields() ) : ?>

            <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

            <div class="grid lg:grid-cols-3 gap-8">
                
                <!-- Customer Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-lg border border-gray-200">
                        <h2 class="text-xl font-semibold mb-6"><?php _e('Billing Details', 'tostishop'); ?></h2>
                        <div id="customer_details">
                            <div>
                                <?php do_action( 'woocommerce_checkout_billing' ); ?>
                            </div>
                            <div>
                                <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Review -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg border border-gray-200">
                        <h2 class="text-xl font-semibold mb-6"><?php _e('Your Order', 'tostishop'); ?></h2>
                        
                        <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                        </div>

                        <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </form>

    <?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
</div>