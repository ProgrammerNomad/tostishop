<?php
/**
 * Checkout Payment Section - Mobile-First Design
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
    do_action( 'woocommerce_review_order_before_payment' );
}
?>

<div id="payment" class="woocommerce-checkout-payment">
    
    <!-- Payment Methods Section -->
    <?php if ( WC()->cart->needs_payment() ) : ?>
        <div class="payment-methods-section bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                <div class="p-2 bg-primary/10 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <?php esc_html_e( 'Payment Method', 'woocommerce' ); ?>
                <span class="ml-2 text-sm bg-primary/10 text-primary px-2 py-1 rounded-full">
                    <?php _e('Required', 'tostishop'); ?>
                </span>
            </h3>
            
            <ul class="wc_payment_methods payment_methods methods checkout-payment-methods">
                <?php
                if ( ! empty( $available_gateways ) ) {
                    foreach ( $available_gateways as $gateway ) {
                        wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ), '', WC()->plugin_path() . '/templates/' );
                    }
                } else {
                    echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>';
                }
                ?>
            </ul>
            
            <div class="form-row place-order">
                <noscript>
                    <?php
                    /* translators: $1 and $2 opening and closing emphasis tags respectively */
                    printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
                    ?>
                    <br/><button type="submit" class="button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
                </noscript>
            </div>
        </div>
    <?php endif; ?>

    <!-- Terms and Conditions + Place Order Section -->
    <div class="checkout-terms-and-place-order bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        
        <?php if ( wc_get_page_id( 'terms' ) > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) : ?>
            <div class="woocommerce-terms-and-conditions-wrapper mb-6">
                <div class="woocommerce-terms-and-conditions checkout-terms-conditions">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox-label flex items-start cursor-pointer">
                            <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox mr-3 mt-1" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); ?> id="terms" />
                            <span class="woocommerce-terms-and-conditions-checkbox-text text-sm text-gray-700 leading-relaxed">
                                <?php
                                printf( __( 'I have read and agree to the website %sterms and conditions%s', 'woocommerce' ), '<a href="' . esc_url( wc_get_page_permalink( 'terms' ) ) . '" class="woocommerce-terms-and-conditions-link text-primary hover:text-blue-600 underline" target="_blank">', '</a>' );
                                ?>
                                <span class="required text-red-500 ml-1">*</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php do_action( 'woocommerce_review_order_before_submit' ); ?>

        <!-- CRITICAL: Fixed Place Order Button with all required WooCommerce classes -->
        <button type="submit" 
                class="button alt wc-forward checkout-place-order-btn w-full<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" 
                name="woocommerce_checkout_place_order" 
                id="place_order" 
                value="<?php esc_attr_e( 'Place order', 'woocommerce' ); ?>" 
                data-value="<?php esc_attr_e( 'Place order', 'woocommerce' ); ?>">
            <span class="place-order-text"><?php esc_html_e( 'Place Order', 'woocommerce' ); ?></span>
            <div class="place-order-spinner hidden">
                <svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </button>

        <?php do_action( 'woocommerce_review_order_after_submit' ); ?>

        <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
        
        <!-- Security Trust Badges -->
        <div class="mt-6 pt-4 border-t border-gray-100">
            <div class="flex items-center justify-center space-x-6 text-xs text-gray-500">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <?php _e('SSL Secure', 'tostishop'); ?>
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <?php _e('Safe & Secure', 'tostishop'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if ( ! wp_doing_ajax() ) {
    do_action( 'woocommerce_review_order_after_payment' );
}
?>
