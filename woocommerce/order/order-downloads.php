<?php
/**
 * Order Downloads
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-downloads.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;
?>

<?php if ( $downloads ) : ?>
	
	<section class="woocommerce-order-downloads bg-white border border-gray-200 rounded-lg shadow-sm p-6 mb-6">
		<?php if ( isset( $show_title ) && $show_title ) : ?>
			<h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
				<svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
				</svg>
				<?php esc_html_e( 'Downloads', 'woocommerce' ); ?>
			</h2>
		<?php endif; ?>

		<!-- Downloads Grid -->
		<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
			<?php foreach ( $downloads as $download ) : ?>
				<div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
					
					<!-- Download Header -->
					<div class="flex items-start justify-between mb-3">
						<div class="flex-1 min-w-0">
							<h3 class="text-sm font-semibold text-gray-900 truncate">
								<?php echo esc_html( $download['download_name'] ); ?>
							</h3>
							
							<!-- Product Info -->
							<?php if ( ! empty( $download['product_name'] ) ) : ?>
								<p class="text-xs text-gray-600 mt-1">
									<?php echo esc_html( $download['product_name'] ); ?>
								</p>
							<?php endif; ?>
						</div>
						
						<!-- Download Icon -->
						<div class="flex-shrink-0 ml-3">
							<div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
								<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
								</svg>
							</div>
						</div>
					</div>

					<!-- Download Details -->
					<div class="space-y-2 text-xs text-gray-600 mb-4">
						<?php if ( ! empty( $download['access_expires'] ) ) : ?>
							<div class="flex justify-between">
								<span><?php esc_html_e( 'Expires:', 'woocommerce' ); ?></span>
								<time datetime="<?php echo esc_attr( date( 'Y-m-d', strtotime( $download['access_expires'] ) ) ); ?>">
									<?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $download['access_expires'] ) ) ); ?>
								</time>
							</div>
						<?php endif; ?>
						
						<div class="flex justify-between">
							<span><?php esc_html_e( 'Downloads remaining:', 'woocommerce' ); ?></span>
							<span class="<?php echo ( is_numeric( $download['downloads_remaining'] ) && $download['downloads_remaining'] <= 3 ) ? 'text-red-600 font-medium' : ''; ?>">
								<?php
								echo is_numeric( $download['downloads_remaining'] )
									? esc_html( $download['downloads_remaining'] )
									: esc_html__( 'Unlimited', 'woocommerce' );
								?>
							</span>
						</div>
					</div>

					<!-- Download Button -->
					<a href="<?php echo esc_url( $download['download_url'] ); ?>" 
					   class="inline-flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-white bg-primary hover:bg-blue-600 rounded-lg transition-colors duration-200">
						<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0L8 8m4-4v12"></path>
						</svg>
						<?php esc_html_e( 'Download', 'woocommerce' ); ?>
					</a>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Downloads Notice -->
		<div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
			<div class="flex items-start">
				<svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
					<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
				</svg>
				<div class="text-sm text-blue-900">
					<p class="font-medium mb-1"><?php esc_html_e( 'Download Information', 'tostishop' ); ?></p>
					<p><?php esc_html_e( 'Downloads are available for a limited time. Please save your files to a secure location.', 'tostishop' ); ?></p>
				</div>
			</div>
		</div>
	</section>

<?php endif;
