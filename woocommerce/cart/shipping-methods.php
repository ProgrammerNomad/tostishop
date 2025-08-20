<?php
/**
 * Cart Shipping Methods
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/shipping-methods.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.3.0
 */

defined( 'ABSPATH' ) || exit;

$formatted_destination    = isset( $formatted_destination ) ? $formatted_destination : WC()->countries->get_formatted_address( $package['destination'], ', ' );
$has_calculated_shipping  = ! empty( $has_calculated_shipping );
$show_shipping_calculator = ! empty( $show_shipping_calculator );
$calculator_text          = '';
?>

<div class="woocommerce-shipping-totals shipping bg-white border border-gray-200 rounded-lg p-4 mb-4 shadow-sm">
	<?php if ( $available_methods ) : ?>
		<div class="border-b border-gray-200 pb-3 mb-3">
			<div class="flex items-center text-base font-bold text-navy-900">
				<div class="flex items-center justify-center w-8 h-8 bg-navy-100 rounded-lg mr-2">
					<svg class="w-4 h-4 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4-8-4m16 0v10l-8 4-8-4V7"></path>
					</svg>
				</div>
				<?php echo wp_kses_post( $package_name ); ?>
			</div>
			<?php if ( $show_package_details ) : ?>
				<?php echo '<p class="text-sm text-gray-600 mt-1">' . esc_html( $formatted_destination ) . '</p>'; ?>
			<?php endif; ?>
		</div>

		<ul id="shipping_method" class="woocommerce-shipping-methods space-y-2">
			<?php foreach ( $available_methods as $method ) : ?>
				<li class="shipping-method-item">
					<?php
					if ( 1 < count( $available_methods ) ) {
						printf( '<input type="radio" name="shipping_method[%1$s]" data-index="%1$s" id="shipping_method_%1$s_%2$s" value="%3$s" class="radio-standard mr-3 flex-shrink-0 shipping_method" %4$s />', $index, esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ), checked( $method->id, $chosen_method, false ) ); // WPCS: XSS ok.
					} else {
						printf( '<input type="hidden" name="shipping_method[%1$s]" data-index="%1$s" id="shipping_method_%1$s_%2$s" value="%3$s" class="shipping_method" />', $index, esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ) ); // WPCS: XSS ok.
					}
					?>
					
					<label for="shipping_method_<?php echo esc_attr( $index ); ?>_<?php echo esc_attr( sanitize_title( $method->id ) ); ?>" 
						   class="<?php echo tostishop_get_shipping_method_label_classes( $method->id, $chosen_method ); ?>">
						
						<div class="flex items-center flex-1">
							<?php if ( 1 < count( $available_methods ) ) : ?>
								<div class="flex items-center mr-3">
									<div class="<?php echo tostishop_get_shipping_radio_classes( $method->id, $chosen_method ); ?>">
										<?php if ( $method->id === $chosen_method ) : ?>
											<div class="w-1.5 h-1.5 bg-white rounded-full"></div>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>
							
							<div class="flex-1 min-w-0">
								<?php
								$method_data = tostishop_parse_shipping_method_data( $method );
								?>
								<div class="flex items-center gap-2 mb-1">
									<span class="font-semibold text-gray-900 text-sm truncate">
										<?php echo esc_html( $method_data['courier_name'] ); ?>
									</span>
									<?php if ( $method_data['is_recommended'] ) : ?>
										<span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-accent text-white flex-shrink-0">⭐ Best</span>
									<?php endif; ?>
									<?php if ( $method_data['is_free'] ) : ?>
										<span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 flex-shrink-0">FREE</span>
									<?php endif; ?>
								</div>
								<div class="text-xs <?php echo esc_attr( $method_data['delivery_type_class'] ); ?> flex items-center">
									<span class="mr-1"><?php echo esc_html( $method_data['delivery_icon'] ); ?></span>
									<span><?php echo esc_html( $method_data['delivery_type_text'] ); ?></span>
									<span class="mx-2">•</span>
									<span>COD Available</span>
								</div>
							</div>
						</div>
						
						<div class="text-right ml-3">
							<span class="text-lg font-bold <?php echo $method_data['is_free'] ? 'text-green-600' : 'text-navy-900'; ?>">
								<?php echo $method_data['display_price']; ?>
							</span>
						</div>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php if ( is_cart() ) : ?>
			<p class="woocommerce-shipping-destination text-sm text-gray-600 mt-3">
				<?php
				if ( $formatted_destination ) {
					// Translators: $s shipping destination.
					printf( esc_html__( 'Shipping to %s.', 'woocommerce' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' );
					$calculator_text = esc_html__( 'Change address', 'woocommerce' );
				} else {
					echo wp_kses_post( apply_filters( 'woocommerce_shipping_not_enabled_on_cart_html', __( 'Shipping options will be updated during checkout.', 'woocommerce' ) ) );
				}
				?>
			</p>
		<?php endif; ?>
		
	<?php elseif ( ! $has_calculated_shipping || ! $formatted_destination ) : ?>
		<?php
		if ( is_cart() && 'no' === get_option( 'woocommerce_enable_shipping_calc' ) ) {
			echo wp_kses_post( apply_filters( 'woocommerce_shipping_not_enabled_on_cart_html', __( 'Shipping options will be updated during checkout.', 'woocommerce' ) ) );
		} else {
			echo wp_kses_post( apply_filters( 'woocommerce_shipping_may_be_available_html', __( 'Enter your address to view shipping options.', 'woocommerce' ) ) );
		}
		?>
	<?php elseif ( ! is_cart() ) : ?>
		<?php echo wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', __( 'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woocommerce' ) ) ); ?>
	<?php else : ?>
		<?php
		// Translators: $s shipping destination.
		echo wp_kses_post( apply_filters( 'woocommerce_cart_no_shipping_available_html', sprintf( esc_html__( 'No shipping options were found for %s.', 'woocommerce' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' ) ) );
		$calculator_text = esc_html__( 'Enter a different address', 'woocommerce' );
		?>
	<?php endif; ?>

	<?php if ( $show_shipping_calculator ) : ?>
		<?php woocommerce_shipping_calculator( $calculator_text ); ?>
	<?php endif; ?>
</div>
