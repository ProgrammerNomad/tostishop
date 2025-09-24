<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $upsells ) : ?>

	<section class="up-sells upsells products pb-8 lg:pt-16" aria-labelledby="upsells-heading">
		<div class="px-4 sm:px-6 lg:px-8">
			
		<header class="mb-8 text-center">
			<h2 id="upsells-heading" class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">
				<?php esc_html_e( 'You may also like&hellip;', 'woocommerce' ); ?>
			</h2>
			<p class="text-gray-600 max-w-2xl mx-auto">
				<?php esc_html_e( 'Complete your purchase with these recommended items', 'tostishop' ); ?>
			</p>
		</header>

		<?php
		woocommerce_product_loop_start();

		foreach ( $upsells as $upsell ) :
			$post_object = get_post( $upsell->get_id() );

			setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

			wc_get_template_part( 'content', 'product' );
		endforeach;

		woocommerce_product_loop_end();

		wp_reset_postdata();
		?>		</div>
	</section>

<?php endif;

wc_set_loop_prop( 'is_shortcode', false );