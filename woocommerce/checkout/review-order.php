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
<div class="woocommerce-checkout-review-order-table px-1 sm:px-0">
	
	<?php do_action( 'woocommerce_review_order_before_cart_contents' ); ?>
	
	<!-- Products List -->
	<div class="space-y-3 mb-6 px-1 sm:px-0">
		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'flex items-start space-x-2 sm:space-x-3 p-2 sm:p-4 bg-white rounded-lg border border-gray-100 hover:border-gray-200 transition-colors', $cart_item, $cart_item_key ) ); ?>">
					
					<!-- Product Image -->
					<div class="flex-shrink-0">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( array( 50, 50 ) ), $cart_item, $cart_item_key );
						if ( ! $_product->is_visible() ) {
							echo '<div class="w-12 h-12 sm:w-14 sm:h-14 bg-gray-100 rounded-lg overflow-hidden">' . $thumbnail . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							printf( '<a href="%s" class="block w-12 h-12 sm:w-14 sm:h-14 bg-gray-100 rounded-lg overflow-hidden hover:opacity-75 transition-opacity">%s</a>', esc_url( $_product->get_permalink( $cart_item ) ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</div>

					<!-- Product Details -->
					<div class="flex-1 min-w-0">
						<div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-2">
							<div class="flex-1 min-w-0">
								<h4 class="text-xs sm:text-sm font-semibold text-gray-900 line-clamp-2 mb-1">
									<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' ); ?>
								</h4>
								
								<!-- Product Meta -->
								<?php if ( wc_get_formatted_cart_item_data( $cart_item ) ) : ?>
								<div class="text-xs text-gray-500 mb-1">
									<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
								<?php endif; ?>
								
								<!-- Quantity Badge -->
								<div class="inline-flex items-center mb-1 sm:mb-0">
									<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
										<?php echo sprintf( __( 'Qty: %s', 'tostishop' ), '<span class="font-semibold">' . $cart_item['quantity'] . '</span>' ); ?>
									</span>
								</div>
							</div>
							
							<!-- Price -->
							<div class="text-left sm:text-right flex-shrink-0">
								<span class="text-sm sm:text-base font-bold text-gray-900">
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

		<!-- Coupon Code Section -->
		<?php if ( wc_coupons_enabled() ) : ?>
		<div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
			<div class="flex items-center justify-between mb-3">
				<h4 class="text-sm font-medium text-gray-900 flex items-center">
					<svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
					</svg>
					<?php _e('Promo Code', 'tostishop'); ?>
				</h4>
				<button type="button" class="text-primary text-sm font-medium hover:text-primary/80 transition-colors" id="toggle-coupon">
					<?php _e('Add Code', 'tostishop'); ?>
				</button>
			</div>
			
			<form class="coupon-form hidden" method="post" id="coupon-form">
				<div class="flex space-x-2">
					<div class="flex-1">
						<input type="text" 
							   name="coupon_code" 
							   id="coupon_code" 
							   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" 
							   placeholder="<?php esc_attr_e( 'Enter promo code', 'tostishop' ); ?>" 
							   value="" />
					</div>
					<button type="submit" 
							name="apply_coupon" 
							value="<?php esc_attr_e( 'Apply', 'tostishop' ); ?>"
							class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary/90 transition-colors">
						<?php esc_html_e( 'Apply', 'tostishop' ); ?>
					</button>
				</div>
			</form>
		</div>
		<?php endif; ?>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
	const toggleBtn = document.getElementById('toggle-coupon');
	const couponForm = document.getElementById('coupon-form');
	
	if (toggleBtn && couponForm) {
		toggleBtn.addEventListener('click', function() {
			couponForm.classList.toggle('hidden');
			toggleBtn.textContent = couponForm.classList.contains('hidden') ? 
				'Add Code' : 'Cancel';
		});
	}
	
	// Handle coupon form submission
	const form = document.getElementById('coupon-form');
	if (form) {
		form.addEventListener('submit', function(e) {
			e.preventDefault();
			const couponCode = document.getElementById('coupon_code').value;
			if (couponCode.trim()) {
				// Trigger WooCommerce coupon application
				const checkoutForm = document.querySelector('form.checkout');
				const input = document.createElement('input');
				input.type = 'hidden';
				input.name = 'coupon_code';
				input.value = couponCode;
				checkoutForm.appendChild(input);
				
				const button = document.createElement('input');
				button.type = 'hidden';
				button.name = 'apply_coupon';
				button.value = 'Apply coupon';
				checkoutForm.appendChild(button);
				
				checkoutForm.submit();
			}
		});
	}
});
</script>

<style>
/* Enhanced Review Order Styling */
.coupon-form.hidden {
	display: none;
}

.coupon-form {
	animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
	from {
		opacity: 0;
		transform: translateY(-10px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

/* Enhanced Coupon Form */
.coupon-form input[type="text"] {
	border: 2px solid #e5e7eb;
	transition: all 0.2s ease;
}

.coupon-form input[type="text"]:focus {
	border-color: #14175b;
	box-shadow: 0 0 0 3px rgba(20, 23, 91, 0.1);
	outline: none;
}

.coupon-form button[type="submit"] {
	background: linear-gradient(135deg, #14175b 0%, #1e3a8a 100%);
	border: none;
	cursor: pointer;
	transition: all 0.2s ease;
}

.coupon-form button[type="submit"]:hover {
	background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%);
	transform: translateY(-1px);
	box-shadow: 0 4px 6px -1px rgba(20, 23, 91, 0.3);
}

/* Mobile Responsive Optimizations */
@media (max-width: 640px) {
	/* Product Cards Mobile Optimization */
	.lg\\:col-span-1 {
		padding-left: 0.25rem !important;
		padding-right: 0.25rem !important;
	}
	
	/* Container spacing fix */
	.woocommerce-checkout-review-order-table {
		margin-left: 0 !important;
		margin-right: 0 !important;
		padding-left: 0.25rem !important;
		padding-right: 0.25rem !important;
	}
	
	/* Product item spacing */
	.woocommerce-checkout-review-order-table .flex.items-start {
		padding: 0.5rem !important;
		margin-bottom: 0.5rem !important;
	}
	
	/* Smaller images on mobile */
	.woocommerce-checkout-review-order-table .w-12,
	.woocommerce-checkout-review-order-table .h-12 {
		width: 2.5rem !important;
		height: 2.5rem !important;
	}
	
	/* Reduce horizontal spacing */
	.woocommerce-checkout-review-order-table .space-x-2 > * + * {
		margin-left: 0.25rem !important;
	}
	
	/* Coupon form mobile optimization */
	.coupon-form .flex {
		flex-direction: column;
		gap: 0.5rem;
	}
	
	.coupon-form button[type="submit"] {
		width: 100%;
	}
	
	/* Order totals mobile optimization */
	.border-t-2.pt-6 {
		padding-top: 1rem !important;
	}
	
	/* Better text sizing on mobile */
	.text-xs {
		font-size: 0.7rem !important;
	}
	
	.text-sm {
		font-size: 0.8rem !important;
	}
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const toggleButton = document.getElementById('toggle-coupon');
	const couponForm = document.getElementById('coupon-form');
	
	if (toggleButton && couponForm) {
		toggleButton.addEventListener('click', function() {
			if (couponForm.classList.contains('hidden')) {
				couponForm.classList.remove('hidden');
				toggleButton.textContent = '<?php echo esc_js( __( 'Cancel', 'tostishop' ) ); ?>';
			} else {
				couponForm.classList.add('hidden');
				toggleButton.textContent = '<?php echo esc_js( __( 'Add Code', 'tostishop' ) ); ?>';
			}
		});
	}
	
	// Handle coupon form submission
	const couponFormElement = document.getElementById('coupon-form');
	if (couponFormElement) {
		couponFormElement.addEventListener('submit', function(e) {
			e.preventDefault();
			
			const couponCode = document.getElementById('coupon_code').value.trim();
			if (!couponCode) {
				alert('<?php echo esc_js( __( 'Please enter a coupon code', 'tostishop' ) ); ?>');
				return;
			}
			
			// Add loading state
			const submitButton = this.querySelector('button[type="submit"]');
			const originalText = submitButton.textContent;
			submitButton.textContent = '<?php echo esc_js( __( 'Applying...', 'tostishop' ) ); ?>';
			submitButton.disabled = true;
			
			// Create form data
			const formData = new FormData();
			formData.append('coupon_code', couponCode);
			formData.append('apply_coupon', '1');
			
			// Submit via AJAX or form
			fetch(window.location.href, {
				method: 'POST',
				body: formData
			}).then(response => {
				// Reload page to show updated totals
				window.location.reload();
			}).catch(error => {
				console.error('Error:', error);
				submitButton.textContent = originalText;
				submitButton.disabled = false;
			});
		});
	}
});
</script>
