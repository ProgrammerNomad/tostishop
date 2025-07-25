<?php
/**
 * Checkout Form - Optimized for Conversion
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

<div class="min-h-screen bg-gray-50 py-8">
	<div class="max-w-7xl xl:max-w-[95rem] 2xl:max-w-[110rem] mx-auto px-4 sm:px-6 lg:px-8">
		
		<!-- Simplified Page Header -->
		<div class="mb-8">
			<div class="flex items-center justify-between">
				<div>
					<h1 class="text-3xl font-bold text-gray-900"><?php _e('Checkout', 'tostishop'); ?></h1>
					<p class="text-gray-600 mt-2"><?php _e('Complete your order below', 'tostishop'); ?></p>
				</div>
				<!-- Back to Cart -->
				<a href="<?php echo esc_url(wc_get_cart_url()); ?>" 
				   class="text-sm text-gray-600 hover:text-gray-800 flex items-center">
					<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
					</svg>
					<?php _e('Back to Cart', 'tostishop'); ?>
				</a>
			</div>
			
			<!-- Security Trust Badge -->
			<div class="mt-4 flex items-center justify-center">
				<div class="flex items-center space-x-2 text-sm text-gray-600">
					<svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
						<path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
					</svg>
					<span><?php _e('Secure checkout protected by SSL encryption', 'tostishop'); ?></span>
				</div>
			</div>
		</div>

		<?php do_action( 'woocommerce_before_checkout_form_cart_notices' ); ?>

		<?php
		if ( ! is_user_logged_in() && get_option( 'woocommerce_enable_checkout_login_reminder' ) === 'yes' ) {
			?>
			<!-- Login Reminder -->
			<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
				<div class="flex items-start">
					<div class="flex-shrink-0">
						<svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
						</svg>
					</div>
					<div class="ml-3 flex-1">
						<h4 class="text-sm font-medium text-blue-900"><?php _e('Returning customer?', 'tostishop'); ?></h4>
						<div class="mt-1 text-sm text-blue-700">
							<a href="#" class="font-medium hover:text-blue-600" id="showLoginForm">
								<?php _e('Click here to login', 'tostishop'); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Collapsible Login Form -->
			<div id="loginForm" class="hidden bg-white border border-gray-200 rounded-lg p-6 mb-8">
				<form class="woocommerce-form woocommerce-form-login login" method="post">
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<div>
							<label for="username" class="block text-sm font-medium text-gray-700 mb-2">
								<?php _e('Username or email address', 'woocommerce'); ?> <span class="text-red-500">*</span>
							</label>
							<input type="text" 
								   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary sm:text-sm" 
								   name="username" 
								   id="username" 
								   autocomplete="username" 
								   value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
						</div>
						<div>
							<label for="password" class="block text-sm font-medium text-gray-700 mb-2">
								<?php _e('Password', 'woocommerce'); ?> <span class="text-red-500">*</span>
							</label>
							<input class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary sm:text-sm" 
								   type="password" 
								   name="password" 
								   id="password" 
								   autocomplete="current-password" />
						</div>
					</div>
					
					<div class="flex items-center justify-between mt-6">
						<label class="flex items-center">
							<input type="checkbox" name="rememberme" class="rounded border-gray-300 text-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" value="forever" />
							<span class="ml-2 text-sm text-gray-600"><?php _e('Remember me', 'woocommerce'); ?></span>
						</label>
						
						<button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-600 transition-colors duration-200" name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>">
							<?php _e('Login', 'woocommerce'); ?>
						</button>
					</div>
					
					<div class="mt-4 text-center">
						<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="text-sm text-primary hover:text-blue-600">
							<?php _e('Lost your password?', 'woocommerce'); ?>
						</a>
					</div>
					
					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				</form>
			</div>
			<?php
		}
		?>

		<?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

		<?php
		// If checkout registration is disabled and not logged in, the user cannot checkout.
		if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
			echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
			return;
		}
		?>

		<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

			<div class="lg:grid lg:grid-cols-12 lg:gap-8 xl:gap-12">
				
				<!-- Left Column - Checkout Form -->
				<div class="lg:col-span-7 xl:col-span-8">
					<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 lg:p-8">
						
						<?php if ( $checkout->get_checkout_fields() ) : ?>
							<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

							<!-- Customer Information -->
							<div class="space-y-8">
								
								<!-- Billing Details -->
								<?php if ( $checkout->get_checkout_fields( 'billing' ) ) : ?>
									<div>
										<h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
											<svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
											</svg>
											<?php _e('Billing details', 'woocommerce'); ?>
										</h3>
										
										<div class="woocommerce-billing-fields__field-wrapper grid grid-cols-1 md:grid-cols-2 gap-6">
											<?php
											$fields = $checkout->get_checkout_fields( 'billing' );
											foreach ( $fields as $key => $field ) {
												// Customize field classes for better styling
												$field['input_class'] = array( 'block', 'w-full', 'px-3', 'py-2', 'border', 'border-gray-300', 'rounded-lg', 'shadow-sm', 'focus:ring-primary', 'focus:border-primary', 'sm:text-sm' );
												$field['label_class'] = array( 'block', 'text-sm', 'font-medium', 'text-gray-700', 'mb-2' );
												
												// Full width for certain fields
												if ( in_array( $key, array( 'billing_email', 'billing_phone', 'billing_address_1', 'billing_address_2', 'billing_company' ) ) ) {
													$field['class'] = array( 'form-row', 'form-row-wide', 'md:col-span-2' );
												} else {
													$field['class'] = array( 'form-row', 'form-row-wide', 'md:col-span-1' );
												}
												
												woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
											}
											?>
										</div>
									</div>
								<?php endif; ?>

								<!-- Shipping Details -->
								<?php if ( $checkout->get_checkout_fields( 'shipping' ) ) : ?>
									<div class="shipping-fields border-t border-gray-200 pt-8">
										<div class="flex items-center justify-between mb-6">
											<h3 class="text-lg font-semibold text-gray-900 flex items-center">
												<svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
												</svg>
												<?php _e('Shipping details', 'tostishop'); ?>
											</h3>
											
											<!-- Ship to different address checkbox -->
											<label class="flex items-center" for="ship-to-different-address-checkbox">
												<input id="ship-to-different-address-checkbox" class="rounded border-gray-300 text-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" type="checkbox" name="ship_to_different_address" value="1" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> />
												<span class="ml-2 text-sm font-medium text-gray-700"><?php _e('Ship to a different address?', 'woocommerce'); ?></span>
											</label>
										</div>

										<div class="shipping_address" <?php if ( ! apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ) ) : ?>style="display: none"<?php endif; ?>>
											<div class="woocommerce-shipping-fields__field-wrapper grid grid-cols-1 md:grid-cols-2 gap-6">
												<?php
												$fields = $checkout->get_checkout_fields( 'shipping' );
												foreach ( $fields as $key => $field ) {
													$field['input_class'] = array( 'block', 'w-full', 'px-3', 'py-2', 'border', 'border-gray-300', 'rounded-lg', 'shadow-sm', 'focus:ring-primary', 'focus:border-primary', 'sm:text-sm' );
													$field['label_class'] = array( 'block', 'text-sm', 'font-medium', 'text-gray-700', 'mb-2' );
													
													if ( in_array( $key, array( 'shipping_address_1', 'shipping_address_2', 'shipping_company' ) ) ) {
														$field['class'] = array( 'form-row', 'form-row-wide', 'md:col-span-2' );
													} else {
														$field['class'] = array( 'form-row', 'form-row-wide', 'md:col-span-1' );
													}
													
													woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
												}
												?>
											</div>
										</div>
									</div>
								<?php endif; ?>

								<!-- Additional Fields -->
								<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>
									<div class="account-fields border-t border-gray-200 pt-8">
										<h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
											<svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
											</svg>
											<?php _e('Account information', 'tostishop'); ?>
										</h3>
										
										<div class="woocommerce-account-fields">
											<?php
											$fields = $checkout->get_checkout_fields( 'account' );
											foreach ( $fields as $key => $field ) {
												$field['input_class'] = array( 'block', 'w-full', 'px-3', 'py-2', 'border', 'border-gray-300', 'rounded-lg', 'shadow-sm', 'focus:ring-primary', 'focus:border-primary', 'sm:text-sm' );
												$field['label_class'] = array( 'block', 'text-sm', 'font-medium', 'text-gray-700', 'mb-2' );
												$field['class'] = array( 'form-row', 'form-row-wide' );
												
												woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
											}
											?>
										</div>
									</div>
								<?php endif; ?>
								
								<!-- Order Notes -->
								<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>
									<div class="order-notes border-t border-gray-200 pt-8">
										<h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
											<svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
											</svg>
											<?php _e('Additional information', 'tostishop'); ?>
										</h3>
										
										<div class="woocommerce-additional-fields__field-wrapper">
											<?php
											$order_comments_field = array(
												'type'        => 'textarea',
												'class'       => array( 'form-row-wide', 'notes' ),
												'input_class' => array( 'block', 'w-full', 'px-3', 'py-2', 'border', 'border-gray-300', 'rounded-lg', 'shadow-sm', 'focus:ring-primary', 'focus:border-primary', 'sm:text-sm' ),
												'label_class' => array( 'block', 'text-sm', 'font-medium', 'text-gray-700', 'mb-2' ),
												'label'       => __( 'Order notes', 'woocommerce' ),
												'placeholder' => esc_attr__( 'Notes about your order, e.g. special notes for delivery.', 'woocommerce' ),
											);

											woocommerce_form_field( 'order_comments', $order_comments_field, $checkout->get_value( 'order_comments' ) );
											?>
										</div>
									</div>
								<?php endif; ?>
							</div>

							<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

						<?php endif; ?>
					</div>
				</div>

				<!-- Right Column - Order Review -->
				<div class="lg:col-span-5 xl:col-span-4 mt-8 lg:mt-0">
					<div class="lg:sticky lg:top-8">
						<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 lg:p-8">
							<h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
								<svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
								</svg>
								<?php _e('Your order', 'tostishop'); ?>
							</h3>

							<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

							<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

							<div id="order_review" class="woocommerce-checkout-review-order">
								<?php do_action( 'woocommerce_checkout_order_review' ); ?>
							</div>

							<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
						</div>
					</div>
				</div>
			</div>
		</form>

		<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
	</div>
</div>

<!-- Mobile Sticky Order Summary -->
<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 z-50">
	<div class="flex items-center justify-between">
		<div>
			<div class="text-sm text-gray-600"><?php printf( __('%d items', 'tostishop'), WC()->cart->get_cart_contents_count() ); ?></div>
			<div class="text-lg font-bold text-gray-900"><?php wc_cart_totals_order_total_html(); ?></div>
		</div>
		<button type="button" onclick="document.querySelector('form.checkout').scrollIntoView({behavior: 'smooth', block: 'start'})" 
				class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition-colors duration-200">
			<?php _e('Review Order', 'tostishop'); ?>
		</button>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Toggle login form
	const showLoginForm = document.getElementById('showLoginForm');
	const loginForm = document.getElementById('loginForm');
	
	if (showLoginForm && loginForm) {
		showLoginForm.addEventListener('click', function(e) {
			e.preventDefault();
			loginForm.classList.toggle('hidden');
		});
	}
	
	// Auto-scroll to errors
	const errors = document.querySelector('.woocommerce-error, .woocommerce-message');
	if (errors) {
		errors.scrollIntoView({ behavior: 'smooth', block: 'center' });
	}
	
	// Form validation enhancements
	const form = document.querySelector('form.checkout');
	if (form) {
		form.addEventListener('submit', function(e) {
			const requiredFields = form.querySelectorAll('[required]');
			let hasErrors = false;
			
			requiredFields.forEach(field => {
				if (!field.value.trim()) {
					field.classList.add('border-red-500');
					hasErrors = true;
				} else {
					field.classList.remove('border-red-500');
				}
			});
			
			if (hasErrors) {
				e.preventDefault();
				const firstError = form.querySelector('.border-red-500');
				if (firstError) {
					firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
					firstError.focus();
				}
			}
		});
	}
});
</script>

<?php get_footer(); ?>
