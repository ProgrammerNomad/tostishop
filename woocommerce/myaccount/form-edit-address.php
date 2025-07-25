<?php
/**
 * Edit address form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-address.php.
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

$page_title = ( 'billing' === $load_address ) ? __( 'Billing address', 'woocommerce' ) : __( 'Shipping address', 'woocommerce' );

do_action( 'woocommerce_before_edit_address_form_' . $load_address, $load_address ); ?>

<div class="woocommerce-MyAccount-edit-address">
	
	<!-- Page Header -->
	<div class="mb-8">
		<div class="flex items-center space-x-4 mb-4">
			<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>" 
			   class="inline-flex items-center text-sm text-gray-600 hover:text-primary">
				<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
				<?php _e( 'Back to addresses', 'tostishop' ); ?>
			</a>
		</div>
		<h2 class="text-2xl font-bold text-gray-900 mb-2"><?php echo esc_html( $page_title ); ?></h2>
		<p class="text-gray-600">
			<?php printf( __( 'Edit your %s details below', 'tostishop' ), strtolower( $page_title ) ); ?>
		</p>
	</div>

	<form method="post" class="woocommerce-address-fields space-y-6">
		
		<!-- Address Form Fields -->
		<div class="bg-white border border-gray-200 rounded-lg p-6">
			<h3 class="text-lg font-semibold text-gray-900 mb-6"><?php echo esc_html( $page_title ); ?></h3>
			
			<div class="woocommerce-address-fields__field-wrapper">
				<?php
				foreach ( $address as $key => $field ) {
					$field_key = $load_address . '_' . $key;
					$field['return'] = true;
					$field['id'] = $field_key;
					$field['input_class'] = array( 'block', 'w-full', 'px-3', 'py-2', 'border', 'border-gray-300', 'rounded-lg', 'shadow-sm', 'focus:ring-primary', 'focus:border-primary', 'sm:text-sm' );
					$field['label_class'] = array( 'block', 'text-sm', 'font-medium', 'text-gray-700', 'mb-2' );
					
					// Add grid classes for responsive layout
					if ( in_array( $key, array( 'first_name', 'last_name' ) ) ) {
						$field['class'] = array( 'form-row', 'form-row-wide', 'md:col-span-1' );
					} else {
						$field['class'] = array( 'form-row', 'form-row-wide', 'md:col-span-2' );
					}
					
					echo '<div class="' . implode( ' ', $field['class'] ) . '">';
					echo woocommerce_form_field( $field_key, $field, wc_get_post_data_by_key( $field_key, $field['value'] ) );
					echo '</div>';
				}
				?>
			</div>
		</div>

		<!-- Form Actions -->
		<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center pt-6 border-t border-gray-200">
			<div class="mb-4 sm:mb-0">
				<p class="text-sm text-gray-600">
					<?php _e( 'Make sure your address is accurate for shipping and billing purposes.', 'tostishop' ); ?>
				</p>
			</div>
			
			<div class="flex space-x-3">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>" 
				   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
					<?php _e( 'Cancel', 'tostishop' ); ?>
				</a>
				
				<button type="submit" 
						class="inline-flex items-center px-6 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors duration-200" 
						name="save_address" 
						value="<?php esc_attr_e( 'Save address', 'woocommerce' ); ?>">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
					</svg>
					<?php esc_html_e( 'Save address', 'woocommerce' ); ?>
				</button>
			</div>
		</div>

		<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
		<input type="hidden" name="action" value="edit_address" />

	</form>

</div>

<?php do_action( 'woocommerce_after_edit_address_form_' . $load_address, $load_address ); ?>
