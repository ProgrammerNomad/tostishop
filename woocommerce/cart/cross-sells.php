<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $cross_sells ) : ?>

	<div class="cross-sells p-8 lg:pt-12" aria-labelledby="cross-sells-heading">
		
		<header class="mb-6 text-center lg:text-left">
			<h2 id="cross-sells-heading" class="text-xl lg:text-2xl font-bold text-gray-900 mb-2">
				<?php esc_html_e( 'You may also need&hellip;', 'woocommerce' ); ?>
			</h2>
			<p class="text-sm text-gray-600">
				<?php esc_html_e( 'Frequently bought together with items in your cart', 'tostishop' ); ?>
			</p>
		</header>

		<?php
		woocommerce_product_loop_start();

		foreach ( $cross_sells as $cross_sell ) :
			$post_object = get_post( $cross_sell->get_id() );

			setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

			wc_get_template_part( 'content', 'product' );
		endforeach;

		woocommerce_product_loop_end();

		wp_reset_postdata();
		?>

	</div>

<?php endif;