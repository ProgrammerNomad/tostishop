<?php
/**
 * Checkout billing information form - Only Billing Fields
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;
?>

<!-- Billing Fields Only -->
<div class="woocommerce-billing-fields">

    <?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

    <div class="woocommerce-billing-fields__field-wrapper">
        <?php
        // Only render billing fields - no shipping fields
        $billing_fields = $checkout->get_checkout_fields( 'billing' );
        
        foreach ( $billing_fields as $key => $field ) {
            woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
        }
        ?>
    </div>

    <?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
    
    <!-- Order Notes Section -->
    <?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>
        <div class="woocommerce-additional-fields mt-6">
            <?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>
            
            <div class="border-t border-gray-100 pt-6">
                <h4 class="text-md font-medium text-gray-900 mb-4">
                    <?php esc_html_e( 'Additional Information', 'woocommerce' ); ?>
                </h4>
                <div class="woocommerce-additional-fields__field-wrapper">
                    <?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
                        <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
        </div>
    <?php endif; ?>
</div>

<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
    <div class="woocommerce-account-fields mt-6">
        <?php if ( ! $checkout->is_registration_required() ) : ?>
            <div class="border-t border-gray-100 pt-6">
                <h4 class="text-md font-medium text-gray-900 mb-4">
                    <?php esc_html_e( 'Account Creation', 'woocommerce' ); ?>
                </h4>
                <p class="form-row form-row-wide create-account">
                    <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox flex items-center">
                        <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox mr-3" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" />
                        <span class="text-sm text-gray-700"><?php esc_html_e( 'Create an account for faster checkout next time?', 'woocommerce' ); ?></span>
                    </label>
                </p>
            </div>
        <?php endif; ?>

        <?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

        <?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>
            <div class="create-account mt-4">
                <?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
                    <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                <?php endforeach; ?>
                <div class="clear"></div>
            </div>
        <?php endif; ?>

        <?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
    </div>
<?php endif; ?>
