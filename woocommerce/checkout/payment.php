<?php
/**
 * Checkout Payment Section - Minimal Structure for Form Submission
 */

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
    do_action( 'woocommerce_review_order_before_payment' );
}
?>

<div id="payment" class="woocommerce-checkout-payment">
    
    <?php if ( WC()->cart->needs_payment() ) : ?>
        <!-- Payment Methods -->
        <ul class="wc_payment_methods payment_methods methods">
            <?php
            if ( ! empty( $available_gateways ) ) {
                foreach ( $available_gateways as $gateway ) {
                    wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
                }
            } else {
                echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>';
            }
            ?>
        </ul>
        
        <div class="form-row place-order">
            <noscript>
                <?php
                printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
                ?>
                <br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
            </noscript>
        </div>
    <?php endif; ?>

    <!-- Terms and Conditions -->
    <?php if ( wc_get_page_id( 'terms' ) > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) : ?>
        <div class="woocommerce-terms-and-conditions-wrapper">
            <div class="woocommerce-terms-and-conditions" style="display: none; max-height: 200px; overflow: auto;"><?php wc_get_template( 'checkout/terms.php' ); ?></div>
            <p class="form-row validate-required">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                    <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); ?> id="terms" />
                    <span class="woocommerce-terms-and-conditions-checkbox-text"><?php printf( __( 'I have read and agree to the website %sterms and conditions%s', 'woocommerce' ), '<a href="' . esc_url( wc_get_page_permalink( 'terms' ) ) . '" class="woocommerce-terms-and-conditions-link" target="_blank">', '</a>' ); ?></span>&nbsp;<span class="required">*</span>
                </label>
            </p>
        </div>
    <?php endif; ?>

    <?php do_action( 'woocommerce_review_order_before_submit' ); ?>

    <!-- CRITICAL: Minimal Place Order Button -->
    <button type="submit" 
            class="button alt wc-forward" 
            name="woocommerce_checkout_place_order" 
            id="place_order" 
            value="<?php esc_attr_e( 'Place order', 'woocommerce' ); ?>" 
            data-value="<?php esc_attr_e( 'Place order', 'woocommerce' ); ?>">
        <?php esc_html_e( 'Place order', 'woocommerce' ); ?>
    </button>

    <?php do_action( 'woocommerce_review_order_after_submit' ); ?>

    <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>

</div>

<?php
if ( ! wp_doing_ajax() ) {
    do_action( 'woocommerce_review_order_after_payment' );
}
?>
