<?php
/**
 * Downloads
 *
 * Shows downloads on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/downloads.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$downloads     = WC()->customer->get_downloadable_products();
$has_downloads = (bool) $downloads;

do_action( 'woocommerce_before_account_downloads', $has_downloads ); ?>

<div class="woocommerce-MyAccount-downloads">
	
	<!-- Page Header -->
	<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
		<div>
			<h2 class="text-2xl font-bold text-gray-900 mb-2"><?php _e( 'Downloads', 'tostishop' ); ?></h2>
			<p class="text-gray-600"><?php _e( 'Access your downloadable products', 'tostishop' ); ?></p>
		</div>
	</div>

	<?php if ( $has_downloads ) : ?>

		<!-- Downloads Grid -->
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
			<?php foreach ( $downloads as $download ) : ?>
				<div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
					
					<!-- Download Icon -->
					<div class="flex items-start justify-between mb-4">
						<div class="flex-shrink-0">
							<div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
								<svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
								</svg>
							</div>
						</div>
						
						<!-- Download Status -->
						<?php if ( is_numeric( $download['downloads_remaining'] ) ) : ?>
							<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
								<?php printf( _n( '%s download left', '%s downloads left', $download['downloads_remaining'], 'woocommerce' ), $download['downloads_remaining'] ); ?>
							</span>
						<?php else : ?>
							<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
								<?php _e( 'Unlimited', 'woocommerce' ); ?>
							</span>
						<?php endif; ?>
					</div>

					<!-- Product Info -->
					<div class="mb-4">
						<h3 class="text-lg font-semibold text-gray-900 mb-2">
							<?php echo esc_html( $download['product_name'] ); ?>
						</h3>
						
						<?php if ( ! empty( $download['file']['name'] ) ) : ?>
							<p class="text-sm text-gray-600 mb-2">
								<span class="font-medium"><?php _e( 'File:', 'tostishop' ); ?></span>
								<?php echo esc_html( $download['file']['name'] ); ?>
							</p>
						<?php endif; ?>

						<p class="text-sm text-gray-600">
							<span class="font-medium"><?php _e( 'Order:', 'tostishop' ); ?></span>
							#<?php echo esc_html( $download['order_id'] ); ?>
						</p>
					</div>

					<!-- Download Details -->
					<div class="border-t border-gray-100 pt-4 mb-4">
						<div class="space-y-2 text-sm text-gray-600">
							<?php if ( ! empty( $download['access_expires'] ) ) : ?>
								<div class="flex justify-between">
									<span><?php _e( 'Expires:', 'tostishop' ); ?></span>
									<time datetime="<?php echo esc_attr( date( 'Y-m-d', strtotime( $download['access_expires'] ) ) ); ?>">
										<?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $download['access_expires'] ) ) ); ?>
									</time>
								</div>
							<?php endif; ?>
							
							<div class="flex justify-between">
								<span><?php _e( 'Downloaded:', 'tostishop' ); ?></span>
								<span><?php echo esc_html( $download['download_count'] ); ?> <?php _e( 'times', 'tostishop' ); ?></span>
							</div>
						</div>
					</div>

					<!-- Download Button -->
					<a href="<?php echo esc_url( $download['download_url'] ); ?>" 
					   class="block w-full text-center px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-blue-600 transition-colors duration-200">
						<svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
						</svg>
						<?php _e( 'Download', 'tostishop' ); ?>
					</a>
				</div>
			<?php endforeach; ?>
		</div>

	<?php else : ?>
		
		<!-- No Downloads State -->
		<div class="text-center py-16">
			<svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
			</svg>
			<h3 class="text-xl font-medium text-gray-900 mb-2"><?php _e( 'No downloads available', 'tostishop' ); ?></h3>
			<p class="text-gray-600 mb-6"><?php _e( 'You don\'t have any downloadable products yet. Purchase digital products to access downloads here.', 'tostishop' ); ?></p>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" 
			   class="inline-flex items-center px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-blue-600 transition-colors duration-200">
				<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
				</svg>
				<?php _e( 'Browse Products', 'tostishop' ); ?>
			</a>
		</div>

	<?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_account_downloads', $has_downloads ); ?>
