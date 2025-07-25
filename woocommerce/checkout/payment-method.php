<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
	<div class="border border-gray-200 rounded-lg p-4 hover:border-primary transition-colors duration-200">
		<div class="flex items-start space-x-3">
			<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" 
				   type="radio" 
				   class="payment_method mt-1 text-primary focus:ring-primary border-gray-300" 
				   name="payment_method" 
				   value="<?php echo esc_attr( $gateway->id ); ?>" 
				   <?php checked( $gateway->chosen, true ); ?> 
				   data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
			
			<div class="flex-1 min-w-0">
				<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>" class="flex items-center cursor-pointer">
					<span class="text-sm font-medium text-gray-900">
						<?php echo $gateway->get_title(); ?>
					</span>
					
					<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
						<svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
						</svg>
					<?php endif; ?>
				</label>
				
				<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
					<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?> mt-3" <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
						<div class="bg-gray-50 rounded-lg p-3 text-sm text-gray-700">
							<?php $gateway->payment_fields(); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</li>
