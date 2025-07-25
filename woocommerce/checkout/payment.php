<?php
/**
 * Checkout Payment Section - Modern Mobile-First Design
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>

<!-- Modern Payment Section -->
<div id="payment" class="woocommerce-checkout-payment mt-8">
	
	<!-- Payment Methods -->
	<?php if ( WC()->cart && WC()->cart->needs_payment() ) : ?>
		<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
			<h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
				<div class="p-2 bg-primary/10 rounded-lg mr-3">
					<svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
					</svg>
				</div>
				<?php _e('Payment Method', 'tostishop'); ?>
			</h3>
			
			<ul class="wc_payment_methods payment_methods methods space-y-3">
				<?php
				if ( ! empty( $available_gateways ) ) {
					foreach ( $available_gateways as $gateway ) {
						wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
					}
				} else {
					echo '<li class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">';
					wc_print_notice( apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ), 'notice' ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment
					echo '</li>';
				}
				?>
			</ul>
		</div>
	<?php endif; ?>
	
	<!-- Order Actions -->
	<div class="form-row place-order bg-white rounded-xl border border-gray-200 shadow-sm p-6">
		
		<!-- No JavaScript Warning -->
		<noscript>
			<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
				<p class="text-yellow-800 text-sm">
					<?php
					/* translators: $1 and $2 opening and closing emphasis tags respectively */
					printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
					?>
				</p>
				<button type="submit" class="mt-3 w-full bg-gray-600 text-white px-4 py-3 rounded-lg font-medium hover:bg-gray-700 transition-colors<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>">
					<?php esc_html_e( 'Update totals', 'woocommerce' ); ?>
				</button>
			</div>
		</noscript>

		<!-- Terms and Conditions -->
		<div class="mb-6">
			<?php wc_get_template( 'checkout/terms.php' ); ?>
		</div>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<!-- Enhanced Place Order Button -->
		<div class="space-y-4">
			<?php echo apply_filters( 'woocommerce_order_button_html', 
				'<button type="submit" class="w-full bg-gradient-to-r from-primary to-blue-700 hover:from-primary/90 hover:to-blue-700/90 text-white font-bold py-4 px-6 rounded-xl text-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center space-x-3' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) . '" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
					</svg>
					<span>' . esc_html( $order_button_text ) . '</span>
				</button>' 
			); // @codingStandardsIgnoreLine ?>
			
			<!-- Order Processing Notice -->
			<p class="text-xs text-gray-500 text-center">
				<?php _e('By placing your order, you agree to our terms and conditions. Your payment information is secure and encrypted.', 'tostishop'); ?>
			</p>
		</div>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
</div>

<style>
/* Enhanced Payment Method Styling */
.wc_payment_methods li {
	background: #ffffff;
	border: 2px solid #e5e7eb;
	border-radius: 0.75rem;
	padding: 1rem;
	margin-bottom: 0.75rem;
	transition: all 0.2s ease;
	cursor: pointer;
}

.wc_payment_methods li:hover {
	border-color: #14175b;
	box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.wc_payment_methods li input[type="radio"] {
	margin-right: 0.75rem;
	accent-color: #14175b;
	transform: scale(1.2);
}

.wc_payment_methods li label {
	display: flex;
	align-items: center;
	cursor: pointer;
	font-weight: 500;
	color: #374151;
	width: 100%;
}

.wc_payment_methods li.payment_method_selected,
.wc_payment_methods li:has(input:checked) {
	border-color: #14175b;
	background-color: #f8fafc;
	box-shadow: 0 0 0 3px rgba(20, 23, 91, 0.1);
}

.payment_box {
	background: #f9fafb;
	border: 1px solid #e5e7eb;
	border-radius: 0.5rem;
	padding: 1rem;
	margin-top: 0.75rem;
	font-size: 0.875rem;
	line-height: 1.5;
}

.payment_box::before {
	content: '';
	position: absolute;
	top: -8px;
	left: 20px;
	width: 0;
	height: 0;
	border-left: 8px solid transparent;
	border-right: 8px solid transparent;
	border-bottom: 8px solid #e5e7eb;
}

/* Terms and Conditions Styling */
.woocommerce-terms-and-conditions-wrapper {
	margin-bottom: 1.5rem;
}

.woocommerce-terms-and-conditions-checkbox-text {
	display: flex;
	align-items: flex-start;
	font-size: 0.875rem;
	line-height: 1.5;
}

.woocommerce-terms-and-conditions-checkbox-text input[type="checkbox"] {
	margin-right: 0.75rem;
	margin-top: 0.125rem;
	accent-color: #14175b;
	transform: scale(1.1);
}

/* Loading State */
.processing #place_order {
	opacity: 0.7;
	cursor: not-allowed;
	transform: scale(1) !important;
}

.processing #place_order::after {
	content: '';
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	width: 20px;
	height: 20px;
	border: 2px solid #ffffff;
	border-radius: 50%;
	border-top-color: transparent;
	animation: spin 1s linear infinite;
}

@keyframes spin {
	to {
		transform: translate(-50%, -50%) rotate(360deg);
	}
}

/* Mobile Responsive */
@media (max-width: 640px) {
	.wc_payment_methods li label {
		font-size: 0.875rem;
	}
	
	#place_order {
		font-size: 1rem;
		padding: 1rem 1.5rem;
	}
}
</style>

<?php
if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
?>
