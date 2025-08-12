<?php
/**
 * Checkout shipping information form - Properly handling shipping address
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
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
<div class="woocommerce-shipping-fields">
    <?php if ( true === WC()->cart->needs_shipping_address() ) : ?>

        <h3 id="ship-to-different-address">
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox flex items-center cursor-pointer">
                <input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox mr-3" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" />
                <span class="text-lg font-medium text-gray-900 flex items-center">
                    <div class="p-2 bg-primary/10 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <?php esc_html_e( 'Ship to a different address?', 'woocommerce' ); ?>
                </span>
            </label>
        </h3>

        <div class="shipping_address" id="shipping_address_section" style="display: none;">

            <?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

            <div class="woocommerce-shipping-fields__field-wrapper bg-gray-50 rounded-lg p-6 mt-4">
                <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <?php esc_html_e( 'Shipping Address', 'woocommerce' ); ?>
                </h4>
                
                <div class="space-y-4">
                    <?php
                    $fields = $checkout->get_checkout_fields( 'shipping' );
                    foreach ( $fields as $key => $field ) {
                        woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                    }
                    ?>
                </div>
            </div>

            <?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

        </div>

    <?php endif; ?>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    // Show/hide shipping fields based on checkbox
    function toggleShippingFields() {
        if ($('#ship-to-different-address-checkbox').is(':checked')) {
            $('#shipping_address_section').slideDown(300);
        } else {
            $('#shipping_address_section').slideUp(300);
        }
    }
    
    // Initial state
    toggleShippingFields();
    
    // On checkbox change
    $(document).on('change', '#ship-to-different-address-checkbox', function() {
        toggleShippingFields();
        $('body').trigger('update_checkout');
    });
    
    // Update checkout when shipping fields change
    $(document).on('change', 'input[name^="shipping_"], select[name^="shipping_"]', function() {
        $('body').trigger('update_checkout');
    });
});
</script>

<style>
.woocommerce-shipping-fields .form-row {
    margin-bottom: 1rem;
}

.woocommerce-shipping-fields .form-row label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.woocommerce-shipping-fields .form-row input,
.woocommerce-shipping-fields .form-row select,
.woocommerce-shipping-fields .form-row textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.woocommerce-shipping-fields .form-row input:focus,
.woocommerce-shipping-fields .form-row select:focus,
.woocommerce-shipping-fields .form-row textarea:focus {
    outline: none;
    border-color: #e42029;
    box-shadow: 0 0 0 3px rgba(228, 32, 41, 0.1);
}

.woocommerce-shipping-fields .validate-required.woocommerce-invalid input {
    border-color: #ef4444;
}

.woocommerce-shipping-fields .woocommerce-input-wrapper {
    position: relative;
}

.woocommerce-shipping-fields .select2-container {
    width: 100% !important;
}
</style>
