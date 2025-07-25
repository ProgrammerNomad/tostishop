<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => __( 'Billing address', 'woocommerce' ),
			'shipping' => __( 'Shipping address', 'woocommerce' ),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'Billing address', 'woocommerce' ),
		),
		$customer_id
	);
}

$oldcol = 1;
$col    = 1;
?>

<div class="woocommerce-MyAccount-addresses">
	
	<!-- Page Header -->
	<div class="mb-8">
		<h2 class="text-2xl font-bold text-gray-900 mb-2"><?php _e( 'Addresses', 'tostishop' ); ?></h2>
		<p class="text-gray-600"><?php _e( 'The following addresses will be used on the checkout page by default.', 'tostishop' ); ?></p>
	</div>

	<?php if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) : ?>
		<p class="mb-6 text-sm text-gray-600">
			<?php echo apply_filters( 'woocommerce_my_account_my_address_description', __( 'The following addresses will be used on the checkout page by default.', 'woocommerce' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</p>
	<?php endif; ?>

	<!-- Addresses Grid -->
	<div class="grid grid-cols-1 <?php echo ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) ? 'lg:grid-cols-2' : ''; ?> gap-6">
		
		<?php foreach ( $get_addresses as $name => $address_title ) : ?>
			<?php
				$address = wc_get_account_formatted_address( $name );
				$col     = $col * -1;
				$oldcol  = $oldcol * -1;
			?>
			
			<div class="woocommerce-Address">
				<div class="bg-white border border-gray-200 rounded-lg p-6 h-full">
					
					<!-- Address Header -->
					<div class="flex items-center justify-between mb-4">
						<h3 class="text-lg font-semibold text-gray-900">
							<?php echo esc_html( $address_title ); ?>
						</h3>
						
						<!-- Address Status -->
						<?php if ( $address ) : ?>
							<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
								<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
								</svg>
								<?php _e( 'Set', 'tostishop' ); ?>
							</span>
						<?php else : ?>
							<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
								<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
								</svg>
								<?php _e( 'Not Set', 'tostishop' ); ?>
							</span>
						<?php endif; ?>
					</div>

					<!-- Address Content -->
					<div class="mb-6 flex-1">
						<?php if ( $address ) : ?>
							<address class="text-gray-700 leading-relaxed not-italic">
								<?php echo wp_kses_post( $address ); ?>
							</address>
						<?php else : ?>
							<div class="text-center py-8">
								<svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
								</svg>
								<p class="text-gray-500 text-sm">
									<?php _e( 'You have not set up this type of address yet.', 'woocommerce' ); ?>
								</p>
							</div>
						<?php endif; ?>
					</div>

					<!-- Edit Button -->
					<div class="border-t border-gray-100 pt-4">
						<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name, wc_get_page_permalink( 'myaccount' ) ) ); ?>" 
						   class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-primary hover:text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors duration-200">
							<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
							</svg>
							<?php printf( __( 'Edit %s', 'woocommerce' ), esc_html( $address_title ) ); ?>
						</a>
					</div>
				</div>
			</div>

		<?php endforeach; ?>
	</div>

	<!-- Additional Info -->
	<div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
		<div class="flex items-start">
			<div class="flex-shrink-0">
				<svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
					<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
				</svg>
			</div>
			<div class="ml-3 flex-1">
				<h4 class="text-sm font-medium text-blue-900"><?php _e( 'Address Information', 'tostishop' ); ?></h4>
				<div class="mt-1 text-sm text-blue-700">
					<p><?php _e( 'These addresses will be used during checkout to calculate shipping costs and tax rates. Make sure they are accurate and up to date.', 'tostishop' ); ?></p>
				</div>
			</div>
		</div>
	</div>

</div>
