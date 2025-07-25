<?php
/**
 * Checkout terms and conditions area.
 *
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( apply_filters( 'woocommerce_checkout_show_terms', true ) && function_exists( 'wc_terms_and_conditions_checkbox_enabled' ) ) {
	do_action( 'woocommerce_checkout_before_terms_and_conditions' );

	?>
	<div class="woocommerce-terms-and-conditions-wrapper border-t border-gray-200 pt-4 mt-4">
		<?php
		/**
		 * Terms and conditions hook used to inject content.
		 *
		 * @since 3.4.0
		 * @hooked wc_checkout_privacy_policy_text() - 20
		 * @hooked wc_terms_and_conditions_page_content() - 30
		 */
		do_action( 'woocommerce_checkout_terms_and_conditions' );
		?>

		<?php if ( wc_terms_and_conditions_checkbox_enabled() ) : ?>
			<div class="validate-required mt-4" id="terms-field" data-priority="">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox flex items-start space-x-3">
					<input type="checkbox" 
						   class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox mt-1 rounded border-gray-300 text-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" 
						   name="terms" 
						   <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); // WPCS: input var ok, csrf ok. ?> 
						   id="terms" />
					<span class="woocommerce-form__label-for-checkbox-text text-sm text-gray-700 leading-5">
						<?php
						if ( wc_terms_and_conditions_checkbox_text() ) {
							echo wp_kses_post( wc_terms_and_conditions_checkbox_text() );
						} else {
							printf(
								/* translators: %s terms and conditions page name and link */
								esc_html__( 'I have read and agree to the website %s', 'woocommerce' ),
								'<a href="' . esc_url( wc_get_page_permalink( 'terms' ) ) . '" class="woocommerce-terms-and-conditions-link text-primary hover:text-blue-600 underline" target="_blank">' . esc_html__( 'terms and conditions', 'woocommerce' ) . '</a>'
							);
						}
						?>
						<span class="text-red-500">*</span>
					</span>
				</label>
			</div>
		<?php endif; ?>
	</div>
	<?php

	do_action( 'woocommerce_checkout_after_terms_and_conditions' );
}
