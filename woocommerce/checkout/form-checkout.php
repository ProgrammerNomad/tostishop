<?php
/**
 * Checkout Form - Modern Mobile-First Design
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo '<div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">';
	echo '<p class="text-red-700 text-sm">' . esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) ) . '</p>';
	echo '</div>';
	return;
}

?>

<!-- Modern Checkout Container -->
<div class="min-h-screen bg-gray-50">
	<div class="max-w-7xl xl:max-w-[95rem] 2xl:max-w-[110rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">
		
		<!-- Checkout Header -->
		<div class="text-center mb-8">
			<h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
				<?php _e('Checkout', 'tostishop'); ?>
			</h1>
			<p class="text-gray-600 text-sm">
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



	<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>">

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

						<div id="order_review" class="woocommerce-checkout-review-order bg-white rounded-lg p-4 border border-gray-100">
							<?php do_action( 'woocommerce_checkout_order_review' ); ?>
						</div>

						<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
						
						<!-- Trust Indicators in Sidebar -->
						<div class="mt-6 pt-4 border-t border-gray-200">
							<div class="text-center space-y-3">
								<div class="flex items-center justify-center text-xs text-gray-600">
									<svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
									</svg>
									<?php _e('256-bit SSL Encryption', 'tostishop'); ?>
								</div>
								<div class="flex items-center justify-center text-xs text-gray-600">
									<svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
									</svg>
									<?php _e('Money Back Guarantee', 'tostishop'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>			</div>

		<?php endif; ?>

	</form>

	<!-- Enhanced Security Trust Badges -->
	<div class="mt-12 text-center bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
		<h3 class="text-lg font-semibold text-gray-900 mb-4">
			<?php _e('Your Information is Safe & Secure', 'tostishop'); ?>
		</h3>
		<div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-2xl mx-auto">
			<div class="text-center">
				<div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg mb-3">
					<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
					</svg>
				</div>
				<h4 class="font-medium text-gray-900 mb-1"><?php _e('SSL Encrypted', 'tostishop'); ?></h4>
				<p class="text-sm text-gray-600"><?php _e('256-bit security encryption', 'tostishop'); ?></p>
			</div>
			<div class="text-center">
				<div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg mb-3">
					<svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
					</svg>
				</div>
				<h4 class="font-medium text-gray-900 mb-1"><?php _e('Secure Payments', 'tostishop'); ?></h4>
				<p class="text-sm text-gray-600"><?php _e('Multiple payment options', 'tostishop'); ?></p>
			</div>
			<div class="text-center">
				<div class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg mb-3">
					<svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
					</svg>
				</div>
				<h4 class="font-medium text-gray-900 mb-1"><?php _e('Privacy Protected', 'tostishop'); ?></h4>
				<p class="text-sm text-gray-600"><?php _e('We never share your data', 'tostishop'); ?></p>
			</div>
		</div>
	</div>

</div>

<style>
/* Enhanced Modern Checkout Form Styling */
.woocommerce-checkout .form-row {
	margin-bottom: 1.5rem;
}

.woocommerce-checkout .form-row label {
	display: block;
	margin-bottom: 0.5rem;
	font-weight: 600;
	color: #374151;
	font-size: 0.875rem;
}

.woocommerce-checkout .form-row .required {
	color: #e42029;
}

/* Enhanced Input Styling */
.woocommerce-checkout input[type="text"],
.woocommerce-checkout input[type="email"],
.woocommerce-checkout input[type="tel"],
.woocommerce-checkout input[type="password"],
.woocommerce-checkout textarea,
.woocommerce-checkout select {
	width: 100%;
	padding: 0.875rem 1rem;
	border: 2px solid #e5e7eb;
	border-radius: 0.75rem;
	font-size: 0.875rem;
	transition: all 0.2s ease;
	background-color: #ffffff;
	box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
}

.woocommerce-checkout input:focus,
.woocommerce-checkout textarea:focus,
.woocommerce-checkout select:focus {
	outline: none;
	border-color: #14175b;
	box-shadow: 0 0 0 3px rgba(20, 23, 91, 0.1), 0 1px 3px 0 rgba(0, 0, 0, 0.1);
	transform: translateY(-1px);
}

.woocommerce-checkout input:hover,
.woocommerce-checkout textarea:hover,
.woocommerce-checkout select:hover {
	border-color: #9ca3af;
}

/* Form Layout Enhancements */
.woocommerce-checkout .form-row-wide {
	width: 100%;
}

.woocommerce-checkout .form-row-first,
.woocommerce-checkout .form-row-last {
	width: 100%;
}

@media (min-width: 768px) {
	.woocommerce-checkout .form-row-first {
		width: 48%;
		float: left;
		margin-right: 4%;
	}
	
	.woocommerce-checkout .form-row-last {
		width: 48%;
		float: right;
	}
}

/* Enhanced Account Fields */
.woocommerce-account-fields {
	background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
	border: 1px solid #e5e7eb;
	border-radius: 1rem;
	padding: 2rem;
	margin-top: 2rem;
	box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.woocommerce-account-fields h3 {
	margin-bottom: 1.5rem;
	color: #374151;
	font-size: 1.125rem;
	font-weight: 700;
}

/* Enhanced Checkbox Styling */
.woocommerce-checkout input[type="checkbox"] {
	width: 1.25rem;
	height: 1.25rem;
	margin-right: 0.75rem;
	accent-color: #14175b;
	border-radius: 0.375rem;
}

.woocommerce-checkout .checkbox label {
	display: inline-flex;
	align-items: flex-start;
	cursor: pointer;
	font-weight: normal;
	line-height: 1.5;
}

/* Enhanced Mobile Responsive */
@media (max-width: 1023px) {
	.lg\\:grid {
		display: block !important;
	}
	
	.lg\\:col-span-2,
	.lg\\:col-span-1 {
		width: 100% !important;
	}
	
	.sticky {
		position: relative !important;
		top: auto !important;
	}
	
	/* Mobile form adjustments */
	.woocommerce-checkout .form-row-first,
	.woocommerce-checkout .form-row-last {
		width: 100%;
		float: none;
		margin-right: 0;
		margin-bottom: 1rem;
	}
}

/* Enhanced Error Styling */
.woocommerce-error,
.woocommerce-notice--error {
	background: linear-gradient(135deg, #fef2f2 0%, #fde8e8 100%);
	border: 2px solid #fecaca;
	color: #dc2626;
	padding: 1rem 1.25rem;
	border-radius: 0.75rem;
	margin-bottom: 1.5rem;
	box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.1);
}

/* Enhanced Success Styling */
.woocommerce-message,
.woocommerce-notice--success {
	background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
	border: 2px solid #bbf7d0;
	color: #16a34a;
	padding: 1rem 1.25rem;
	border-radius: 0.75rem;
	margin-bottom: 1.5rem;
	box-shadow: 0 4px 6px -1px rgba(22, 163, 74, 0.1);
}

/* Enhanced Order Review Styling */
.woocommerce-checkout-review-order {
	border-radius: 0.75rem;
	overflow: hidden;
}

.woocommerce-checkout-review-order table {
	margin: 0;
	border-collapse: separate;
	border-spacing: 0;
}

.woocommerce-checkout-review-order th,
.woocommerce-checkout-review-order td {
	padding: 1rem;
	border-bottom: 1px solid #f3f4f6;
}

.woocommerce-checkout-review-order .order-total th,
.woocommerce-checkout-review-order .order-total td {
	font-weight: 700;
	font-size: 1.125rem;
	background-color: #f9fafb;
}

/* Enhanced Payment Section */
.woocommerce-checkout-payment {
	margin-top: 2rem;
}

.wc_payment_methods {
	list-style: none;
	padding: 0;
	margin: 0;
}

.wc_payment_methods li {
	margin-bottom: 1rem;
	padding: 1rem;
	border: 2px solid #e5e7eb;
	border-radius: 0.75rem;
	transition: all 0.2s ease;
}

.wc_payment_methods li:hover {
	border-color: #14175b;
	box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.wc_payment_methods li.payment_method_selected {
	border-color: #14175b;
	background-color: #f8fafc;
}

/* Enhanced Place Order Button */
#place_order {
	width: 100%;
	background: linear-gradient(135deg, #14175b 0%, #1e3a8a 100%);
	color: white;
	padding: 1rem 2rem;
	border: none;
	border-radius: 0.75rem;
	font-size: 1.125rem;
	font-weight: 700;
	cursor: pointer;
	transition: all 0.2s ease;
	box-shadow: 0 4px 6px -1px rgba(20, 23, 91, 0.3);
	margin-top: 1.5rem;
}

#place_order:hover {
	background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%);
	transform: translateY(-1px);
	box-shadow: 0 6px 8px -1px rgba(20, 23, 91, 0.4);
}

#place_order:active {
	transform: translateY(0);
}

/* Loading States */
.processing #place_order {
	opacity: 0.7;
	cursor: not-allowed;
}

/* Additional Animations */
@keyframes fadeInUp {
	from {
		opacity: 0;
		transform: translateY(30px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

.woocommerce-checkout .form-row {
	animation: fadeInUp 0.3s ease-out;
}

/* Print Styles */
@media print {
	.woocommerce-checkout {
		background: white !important;
	}
	
	.bg-gray-50,
	.bg-gradient-to-br {
		background: white !important;
	}
	
	.shadow-sm,
	.shadow-lg {
		box-shadow: none !important;
	}
}
</style>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>