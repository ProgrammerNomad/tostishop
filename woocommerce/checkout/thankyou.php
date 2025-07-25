<?php
/**
 * Thankyou page - Modern Mobile-First Design
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="max-w-lg mx-auto px-4 py-8 sm:px-6 lg:max-w-2xl lg:px-8">
	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>
			<!-- Failed Order Design -->
			<div class="text-center mb-8">
				<div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
					<svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
					</svg>
				</div>
				<h1 class="text-2xl font-bold text-gray-900 mb-2"><?php esc_html_e( 'Payment Failed', 'woocommerce' ); ?></h1>
				<p class="text-gray-600 mb-6"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>
			</div>

			<div class="space-y-3">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" 
				   class="w-full bg-accent text-white py-3 px-4 rounded-lg font-medium text-center block hover:bg-red-700 transition-colors duration-200">
					<?php esc_html_e( 'Try Payment Again', 'woocommerce' ); ?>
				</a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" 
					   class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-medium text-center block hover:bg-gray-200 transition-colors duration-200">
						<?php esc_html_e( 'My Account', 'woocommerce' ); ?>
					</a>
				<?php endif; ?>
			</div>

		<?php else : ?>
			<!-- Success Order Design -->
			<div class="text-center mb-8">
				<!-- Success Icon -->
				<div class="w-20 h-20 mx-auto mb-6 bg-green-100 rounded-full flex items-center justify-center">
					<svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
					</svg>
				</div>
				
				<!-- Success Message -->
				<h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2"><?php esc_html_e( 'Order Confirmed!', 'tostishop' ); ?></h1>
				<p class="text-gray-600 text-lg mb-6">
					<?php 
					$message = apply_filters(
						'woocommerce_thankyou_order_received_text',
						esc_html( __( 'Thank you for your order. We\'ll send you a confirmation email shortly.', 'tostishop' ) ),
						$order
					);
					echo $message;
					?>
				</p>
			</div>

			<!-- Order Details Card -->
			<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
				<div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
					<h2 class="text-lg font-semibold text-gray-900"><?php esc_html_e( 'Order Details', 'tostishop' ); ?></h2>
				</div>
				
				<div class="p-6 space-y-4">
					<!-- Order Number -->
					<div class="flex justify-between items-center py-2">
						<span class="text-gray-600"><?php esc_html_e( 'Order Number', 'woocommerce' ); ?></span>
						<span class="font-semibold text-gray-900">#<?php echo $order->get_order_number(); ?></span>
					</div>
					
					<!-- Order Date -->
					<div class="flex justify-between items-center py-2 border-t border-gray-100">
						<span class="text-gray-600"><?php esc_html_e( 'Date', 'woocommerce' ); ?></span>
						<span class="font-medium text-gray-900"><?php echo wc_format_datetime( $order->get_date_created() ); ?></span>
					</div>
					
					<!-- Email -->
					<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
					<div class="flex justify-between items-center py-2 border-t border-gray-100">
						<span class="text-gray-600"><?php esc_html_e( 'Email', 'woocommerce' ); ?></span>
						<span class="font-medium text-gray-900"><?php echo $order->get_billing_email(); ?></span>
					</div>
					<?php endif; ?>
					
					<!-- Payment Method -->
					<?php if ( $order->get_payment_method_title() ) : ?>
					<div class="flex justify-between items-center py-2 border-t border-gray-100">
						<span class="text-gray-600"><?php esc_html_e( 'Payment Method', 'woocommerce' ); ?></span>
						<span class="font-medium text-gray-900"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></span>
					</div>
					<?php endif; ?>
					
					<!-- Total -->
					<div class="flex justify-between items-center py-3 border-t-2 border-gray-200">
						<span class="text-lg font-semibold text-gray-900"><?php esc_html_e( 'Total', 'woocommerce' ); ?></span>
						<span class="text-xl font-bold text-primary"><?php echo $order->get_formatted_order_total(); ?></span>
					</div>
				</div>
			</div>

			<!-- Shipping Information -->
			<?php if ( $order->needs_shipping_address() && ! $order->has_status( array( 'failed', 'cancelled' ) ) ) : ?>
			<div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
				<div class="flex items-start space-x-3">
					<div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
						<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
						</svg>
					</div>
					<div>
						<h3 class="text-sm font-semibold text-blue-900 mb-1"><?php esc_html_e( 'Shipping Information', 'tostishop' ); ?></h3>
						<p class="text-sm text-blue-800">
							<?php esc_html_e( 'Your order will be shipped to:', 'tostishop' ); ?>
						</p>
						<div class="mt-2 text-sm text-blue-900 font-medium">
							<?php echo wp_kses_post( $order->get_formatted_shipping_address() ?: $order->get_formatted_billing_address() ); ?>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<!-- Action Buttons -->
			<div class="space-y-3 mb-8">
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" 
				   class="w-full bg-primary text-white py-3 px-4 rounded-lg font-medium text-center block hover:bg-navy-800 transition-colors duration-200">
					<?php esc_html_e( 'Continue Shopping', 'tostishop' ); ?>
				</a>
				
				<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" 
				   class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-medium text-center block hover:bg-gray-200 transition-colors duration-200">
					<?php esc_html_e( 'View Order History', 'tostishop' ); ?>
				</a>
				<?php endif; ?>
			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>
		<!-- No Order Found -->
		<div class="text-center py-12">
			<div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
				<svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
				</svg>
			</div>
			<h2 class="text-xl font-semibold text-gray-900 mb-2"><?php esc_html_e( 'Order Not Found', 'tostishop' ); ?></h2>
			<p class="text-gray-600 mb-6"><?php esc_html_e( 'We couldn\'t find your order. Please check your order number and try again.', 'tostishop' ); ?></p>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" 
			   class="inline-block bg-primary text-white py-3 px-6 rounded-lg font-medium hover:bg-navy-800 transition-colors duration-200">
				<?php esc_html_e( 'Continue Shopping', 'tostishop' ); ?>
			</a>
		</div>

	<?php endif; ?>
</div>
