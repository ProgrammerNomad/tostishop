<?php
/**
 * Review order table - Modern Mobile-First Design
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>

<!-- Modern Order Review -->
<div class="woocommerce-checkout-review-order-table">
	
	<?php do_action( 'woocommerce_review_order_before_cart_contents' ); ?>
	
	<!-- Products List -->
	<div class="space-y-4 mb-6">
		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'flex items-start space-x-4 p-4 bg-white rounded-lg border border-gray-100 hover:border-gray-200 transition-colors', $cart_item, $cart_item_key ) ); ?>">
					
					<!-- Product Image -->
					<div class="flex-shrink-0">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array( 72, 72 ) ), $cart_item, $cart_item_key );
						if ( ! $_product->is_visible() ) {
							echo '<div class="w-18 h-18 bg-gray-100 rounded-lg overflow-hidden">' . $thumbnail . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							printf( '<a href="%s" class="block w-18 h-18 bg-gray-100 rounded-lg overflow-hidden hover:opacity-75 transition-opacity">%s</a>', esc_url( $_product->get_permalink( $cart_item ) ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</div>

					<!-- Product Details -->
					<div class="flex-1 min-w-0">
						<div class="flex justify-between items-start">
							<div class="flex-1 min-w-0 pr-4">
								<h4 class="text-sm font-semibold text-gray-900 line-clamp-2 mb-1">
									<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' ); ?>
								</h4>
								
								<!-- Product Meta -->
								<?php if ( wc_get_formatted_cart_item_data( $cart_item ) ) : ?>
								<div class="text-xs text-gray-500 mb-2">
									<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
								<?php endif; ?>
								
								<!-- Quantity Badge -->
								<div class="inline-flex items-center">
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
										<?php echo sprintf( __( 'Qty: %s', 'tostishop' ), '<span class="font-semibold">' . $cart_item['quantity'] . '</span>' ); ?>
									</span>
								</div>
							</div>
							
							<!-- Price -->
							<div class="text-right flex-shrink-0">
								<span class="text-base font-bold text-gray-900">
									<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</span>
								<?php if ( $cart_item['quantity'] > 1 ) : ?>
								<div class="text-xs text-gray-500 mt-1">
									<?php echo wc_price( $_product->get_price() ); ?> <?php _e('each', 'tostishop'); ?>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
		}
		?>
	</div>

	<?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>

	<!-- Order Totals -->
	<div class="border-t-2 border-gray-100 pt-6 space-y-4">

		<!-- Subtotal -->
		<div class="flex justify-between items-center text-base">
			<span class="text-gray-600 font-medium"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span>
			<span class="font-semibold text-gray-900"><?php wc_cart_totals_subtotal_html(); ?></span>
		</div>

		<!-- Coupons -->
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="flex justify-between items-center text-sm bg-green-50 p-3 rounded-lg border border-green-200">
				<span class="text-green-700 font-medium flex items-center">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
					</svg>
					<?php wc_cart_totals_coupon_label( $coupon ); ?>
				</span>
				<span class="text-green-700 font-semibold">
					<?php wc_cart_totals_coupon_html( $coupon ); ?>
				</span>
			</div>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

		<!-- Shipping -->
		<div class="flex justify-between items-center text-base">
			<span class="text-gray-600 font-medium"><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></span>
			<span class="font-semibold text-gray-900">
				<?php wc_cart_totals_shipping_html(); ?>
			</span>
		</div>

		<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>

		<!-- Fees -->
		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="flex justify-between items-center text-sm">
				<span class="text-gray-600"><?php echo esc_html( $fee->name ); ?></span>
				<span class="font-medium text-gray-900"><?php wc_cart_totals_fee_html( $fee ); ?></span>
			</div>
		<?php endforeach; ?>

		<!-- Tax -->
		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
					<div class="flex justify-between items-center text-sm">
						<span class="text-gray-600"><?php echo esc_html( $tax->label ); ?></span>
						<span class="font-medium text-gray-900"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="flex justify-between items-center text-sm">
					<span class="text-gray-600"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
					<span class="font-medium text-gray-900"><?php wc_cart_totals_taxes_total_html(); ?></span>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<!-- Order Total -->
		<div class="flex justify-between items-center text-xl font-bold text-gray-900 bg-gray-50 p-4 rounded-lg border-t-2 border-primary mt-4">
			<span><?php esc_html_e( 'Total', 'woocommerce' ); ?></span>
			<span class="text-primary"><?php wc_cart_totals_order_total_html(); ?></span>
		</div>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</div>
</div>
