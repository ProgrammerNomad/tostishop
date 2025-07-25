<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="shop_table woocommerce-checkout-review-order-table">
	
	<!-- Order Items -->
	<div class="space-y-4 mb-6">
		<h4 class="font-medium text-gray-900 border-b border-gray-200 pb-2"><?php _e('Order Items', 'tostishop'); ?></h4>
		
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<div class="flex items-start space-x-3 py-3 border-b border-gray-100 last:border-b-0">
					<!-- Product Image -->
					<div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
						<?php echo $_product->get_image( array( 64, 64 ), array( 'class' => 'w-full h-full object-cover' ) ); ?>
					</div>
					
					<!-- Product Details -->
					<div class="flex-1 min-w-0">
						<div class="flex items-start justify-between">
							<div class="flex-1">
								<h5 class="text-sm font-medium text-gray-900 leading-tight">
									<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?>
								</h5>
								
								<!-- Product Meta -->
								<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
								
								<!-- Quantity -->
								<div class="text-xs text-gray-500 mt-1">
									<?php printf( __('Qty: %s', 'tostishop'), $cart_item['quantity'] ); ?>
								</div>
							</div>
							
							<!-- Price -->
							<div class="text-sm font-medium text-gray-900 ml-4">
								<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</div>

	<!-- Cart Totals -->
	<div class="space-y-3 border-t border-gray-200 pt-4">
		
		<!-- Subtotal -->
		<div class="flex items-center justify-between text-sm">
			<span class="text-gray-600"><?php _e( 'Subtotal', 'woocommerce' ); ?></span>
			<span class="font-medium text-gray-900"><?php wc_cart_totals_subtotal_html(); ?></span>
		</div>

		<!-- Shipping -->
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="flex items-center justify-between text-sm">
				<span class="text-gray-600"><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
				<span class="font-medium text-green-600"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
			</div>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
			<div class="shipping-section border-t border-gray-200 pt-3">
				<div class="flex items-center justify-between text-sm mb-3">
					<span class="font-medium text-gray-900"><?php _e( 'Shipping', 'woocommerce' ); ?></span>
				</div>
				
				<div class="space-y-2">
					<?php wc_cart_totals_shipping_html(); ?>
				</div>
			</div>
			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="flex items-center justify-between text-sm">
				<span class="text-gray-600"><?php echo esc_html( $fee->name ); ?></span>
				<span class="font-medium text-gray-900"><?php wc_cart_totals_fee_html( $fee ); ?></span>
			</div>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<div class="flex items-center justify-between text-sm">
						<span class="text-gray-600"><?php echo esc_html( $tax->label ); ?></span>
						<span class="font-medium text-gray-900"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="flex items-center justify-between text-sm">
					<span class="text-gray-600"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
					<span class="font-medium text-gray-900"><?php wc_cart_totals_taxes_total_html(); ?></span>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<!-- Total -->
		<div class="flex items-center justify-between text-lg font-bold text-gray-900 border-t border-gray-200 pt-3">
			<span><?php _e( 'Total', 'woocommerce' ); ?></span>
			<span><?php wc_cart_totals_order_total_html(); ?></span>
		</div>
	</div>

	<!-- Shipping Methods -->
	<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
		<div class="shipping-methods-section border-t border-gray-200 pt-6 mt-6">
			<h4 class="font-medium text-gray-900 mb-4"><?php _e('Shipping Methods', 'tostishop'); ?></h4>
			<div class="space-y-3">
				<?php woocommerce_order_review_shipping(); ?>
			</div>
		</div>
	<?php endif; ?>

	<!-- Payment Methods -->
	<div class="payment-methods-section border-t border-gray-200 pt-6 mt-6">
		<h4 class="font-medium text-gray-900 mb-4"><?php _e('Payment Method', 'tostishop'); ?></h4>
		
		<?php if ( WC()->cart->needs_payment() ) : ?>
			<div id="payment" class="woocommerce-checkout-payment">
				<?php woocommerce_checkout_payment(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
