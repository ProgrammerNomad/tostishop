<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
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

$order = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

if ( ! $order ) {
	return;
}

$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads             = $order->get_downloadable_items();
$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();

if ( $show_downloads ) {
	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads'  => $downloads,
			'show_title' => true,
		)
	);
}
?>

<section class="woocommerce-order-details bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
	<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>

	<!-- Order Details Header -->
	<div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
		<h2 class="text-lg font-semibold text-gray-900 flex items-center">
			<svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
			</svg>
			<?php esc_html_e( 'Order details', 'woocommerce' ); ?>
		</h2>
	</div>

	<!-- Order Items Table -->
	<div class="overflow-x-auto">
		<table class="woocommerce-table woocommerce-table--order-details shop_table order_details min-w-full">
			<thead class="bg-gray-50">
				<tr>
					<th class="woocommerce-table__product-name product-name px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
						<?php esc_html_e( 'Product', 'woocommerce' ); ?>
					</th>
					<th class="woocommerce-table__product-total product-total px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
						<?php esc_html_e( 'Total', 'woocommerce' ); ?>
					</th>
				</tr>
			</thead>

			<tbody class="bg-white divide-y divide-gray-200">
				<?php
				do_action( 'woocommerce_order_details_before_order_table_items', $order );

				foreach ( $order_items as $item_id => $item ) {
					$product = $item->get_product();

					wc_get_template(
						'order/order-details-item.php',
						array(
							'order'              => $order,
							'order_id'           => $order_id,
							'item'               => $item,
							'item_id'            => $item_id,
							'product'            => $product,
							'show_purchase_note' => $show_purchase_note,
							'purchase_note'      => $product ? $product->get_purchase_note() : '',
						)
					);
				}

				do_action( 'woocommerce_order_details_after_order_table_items', $order );
				?>
			</tbody>

			<tfoot class="bg-gray-50">
				<?php
				foreach ( $order->get_order_item_totals() as $key => $total ) {
					?>
					<tr class="<?php echo esc_attr( $key ); ?>">
						<th scope="row" class="px-6 py-3 text-left text-sm font-medium text-gray-600">
							<?php echo esc_html( $total['label'] ); ?>
						</th>
						<td class="px-6 py-3 text-right text-sm <?php echo ( 'order_total' === $key ) ? 'font-bold text-lg text-primary' : 'font-medium text-gray-900'; ?>">
							<?php echo wp_kses_post( $total['value'] ); ?>
						</td>
					</tr>
					<?php
				}
				?>
				<?php if ( $order->get_customer_note() ) : ?>
					<tr class="order-note">
						<th scope="row" class="px-6 py-3 text-left text-sm font-medium text-gray-600">
							<?php esc_html_e( 'Note:', 'woocommerce' ); ?>
						</th>
						<td class="px-6 py-3 text-right text-sm text-gray-700">
							<?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?>
						</td>
					</tr>
				<?php endif; ?>
			</tfoot>
		</table>
	</div>

	<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
</section>

<?php
/**
 * Action hook fired after the order details.
 */
do_action( 'woocommerce_order_details_after_order_table', $order );
