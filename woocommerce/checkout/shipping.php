<?php
/**
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/shipping.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-shipping-fields">
	<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>
		<h3 id="ship-to-different-address" class="text-lg font-semibold text-gray-900 mb-4">
			<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox flex items-center cursor-pointer">
				<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox mr-2" 
					   <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> 
					   type="checkbox" name="ship_to_different_address" value="1" />
				<span class="text-base"><?php esc_html_e( 'Ship to a different address?', 'woocommerce' ); ?></span>
			</label>
		</h3>

		<div class="shipping_address" style="display: none;">
			<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

			<div class="woocommerce-shipping-fields__field-wrapper bg-gray-50 p-4 rounded-lg border border-gray-200">
				<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
					<?php
					$fields = $checkout->get_checkout_fields( 'shipping' );

					foreach ( $fields as $key => $field ) {
						if ( isset( $field['country_field'], $fields[ $field['country_field'] ] ) ) {
							$field['country'] = $checkout->get_value( $field['country_field'] );
						}
						
						// Add custom classes for better styling
						$field['class'] = array_merge(
							isset( $field['class'] ) ? $field['class'] : array(),
							array( 'form-row-wide' )
						);
						
						// Style required fields
						if ( isset( $field['required'] ) && $field['required'] ) {
							$field['class'][] = 'validate-required';
						}
						
						woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
					}
					?>
				</div>
			</div>

			<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>
		</div>

	<?php endif; ?>
</div>

<div class="woocommerce-additional-fields">
	<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

	<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>
		<?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>
			<h3 class="text-lg font-semibold text-gray-900 mb-4"><?php esc_html_e( 'Additional information', 'woocommerce' ); ?></h3>
		<?php endif; ?>

		<div class="woocommerce-additional-fields__field-wrapper bg-white border border-gray-200 rounded-lg p-4">
			<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
				<?php 
				// Enhanced textarea styling for order notes
				if ( $key === 'order_comments' ) {
					$field['class'] = array( 'form-row-wide' );
					$field['input_class'] = array( 'input-text', 'w-full', 'p-3', 'border', 'border-gray-300', 'rounded-lg', 'focus:ring-2', 'focus:ring-navy-500', 'focus:border-navy-500', 'resize-none' );
					$field['custom_attributes'] = array(
						'rows' => '3',
						'placeholder' => __( 'Notes about your order, e.g. special notes for delivery.', 'woocommerce' )
					);
				}
				
				woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); 
				?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
</div>
