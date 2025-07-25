<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<div class="woocommerce-MyAccount-orders">
	
	<!-- Page Header -->
	<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
		<div>
			<h2 class="text-2xl font-bold text-gray-900 mb-2"><?php _e( 'Orders', 'tostishop' ); ?></h2>
			<p class="text-gray-600"><?php _e( 'View and track your order history', 'tostishop' ); ?></p>
		</div>
		<div class="mt-4 sm:mt-0">
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" 
			   class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors duration-200">
				<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
				</svg>
				<?php _e( 'Continue Shopping', 'tostishop' ); ?>
			</a>
		</div>
	</div>

	<?php if ( $has_orders ) : ?>

		<!-- Orders Table - Desktop -->
		<div class="hidden lg:block bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
			<table class="woocommerce-orders-table min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-50">
					<tr>
						<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
							<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
								<?php echo esc_html( $column_name ); ?>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody class="bg-white divide-y divide-gray-200">
					<?php
					foreach ( $customer_orders->orders as $customer_order ) {
						$order      = wc_get_order( $customer_order );
						$item_count = $order->get_item_count() - $order->get_item_count_refunded();
						?>
						<tr class="hover:bg-gray-50 transition-colors duration-150">
							<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
								<td class="px-6 py-4 whitespace-nowrap text-sm" data-title="<?php echo esc_attr( $column_name ); ?>">
									<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
										<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

									<?php elseif ( 'order-number' === $column_id ) : ?>
										<div>
											<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" 
											   class="font-medium text-primary hover:text-blue-600">
												#<?php echo $order->get_order_number(); ?>
											</a>
										</div>

									<?php elseif ( 'order-date' === $column_id ) : ?>
										<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>" 
											  class="text-gray-900">
											<?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?>
										</time>

									<?php elseif ( 'order-status' === $column_id ) : ?>
										<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
											<?php 
											$status = $order->get_status();
											switch ( $status ) {
												case 'completed':
													echo 'bg-green-100 text-green-800';
													break;
												case 'processing':
													echo 'bg-blue-100 text-blue-800';
													break;
												case 'pending':
													echo 'bg-yellow-100 text-yellow-800';
													break;
												case 'cancelled':
													echo 'bg-red-100 text-red-800';
													break;
												default:
													echo 'bg-gray-100 text-gray-800';
											}
											?>">
											<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
										</span>

									<?php elseif ( 'order-total' === $column_id ) : ?>
										<div class="font-medium text-gray-900">
											<?php
											/* translators: 1: formatted order total 2: total order items */
											printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );
											?>
										</div>

									<?php elseif ( 'order-actions' === $column_id ) : ?>
										<?php
										$actions = wc_get_account_orders_actions( $order );
										if ( ! empty( $actions ) ) {
											foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
												echo '<a href="' . esc_url( $action['url'] ) . '" class="inline-flex items-center px-3 py-1 text-xs font-medium text-primary hover:text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors duration-200 mr-2 mb-1">' . esc_html( $action['name'] ) . '</a>';
											}
										}
										?>
									<?php endif; ?>
								</td>
							<?php endforeach; ?>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>

		<!-- Orders Cards - Mobile -->
		<div class="lg:hidden space-y-4">
			<?php
			foreach ( $customer_orders->orders as $customer_order ) {
				$order      = wc_get_order( $customer_order );
				$item_count = $order->get_item_count() - $order->get_item_count_refunded();
				?>
				<div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
					
					<!-- Order Header -->
					<div class="flex items-start justify-between mb-3">
						<div>
							<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" 
							   class="text-lg font-semibold text-primary hover:text-blue-600">
								#<?php echo $order->get_order_number(); ?>
							</a>
							<p class="text-sm text-gray-600 mt-1">
								<?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?>
							</p>
						</div>
						<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
							<?php 
							$status = $order->get_status();
							switch ( $status ) {
								case 'completed':
									echo 'bg-green-100 text-green-800';
									break;
								case 'processing':
									echo 'bg-blue-100 text-blue-800';
									break;
								case 'pending':
									echo 'bg-yellow-100 text-yellow-800';
									break;
								case 'cancelled':
									echo 'bg-red-100 text-red-800';
									break;
								default:
									echo 'bg-gray-100 text-gray-800';
							}
							?>">
							<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
						</span>
					</div>

					<!-- Order Details -->
					<div class="border-t border-gray-100 pt-3">
						<div class="flex justify-between items-center mb-3">
							<span class="text-sm text-gray-600"><?php _e( 'Total', 'tostishop' ); ?></span>
							<span class="font-medium text-gray-900">
								<?php
								printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );
								?>
							</span>
						</div>

						<!-- Actions -->
						<?php
						$actions = wc_get_account_orders_actions( $order );
						if ( ! empty( $actions ) ) : ?>
							<div class="flex flex-wrap gap-2 pt-2">
								<?php foreach ( $actions as $key => $action ) : ?>
									<a href="<?php echo esc_url( $action['url'] ); ?>" 
									   class="inline-flex items-center px-3 py-1 text-xs font-medium text-primary hover:text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors duration-200">
										<?php echo esc_html( $action['name'] ); ?>
									</a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<?php
			}
			?>
		</div>

		<!-- Pagination -->
		<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
			<div class="mt-8 flex justify-center">
				<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
					<?php if ( 1 !== $current_page ) : ?>
						<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50" 
						   href="<?php echo esc_url( wc_get_account_endpoint_url( $current_page - 1 ) ); ?>">
							<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
							</svg>
							<?php _e( 'Previous', 'woocommerce' ); ?>
						</a>
					<?php endif; ?>

					<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
						<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50" 
						   href="<?php echo esc_url( wc_get_account_endpoint_url( $current_page + 1 ) ); ?>">
							<?php _e( 'Next', 'woocommerce' ); ?>
							<svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
							</svg>
						</a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

	<?php else : ?>
		
		<!-- No Orders State -->
		<div class="text-center py-16">
			<svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
			</svg>
			<h3 class="text-xl font-medium text-gray-900 mb-2"><?php _e( 'No orders found', 'tostishop' ); ?></h3>
			<p class="text-gray-600 mb-6"><?php _e( 'You haven\'t placed any orders yet. Start shopping to see your orders here.', 'tostishop' ); ?></p>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" 
			   class="inline-flex items-center px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-blue-600 transition-colors duration-200">
				<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
				</svg>
				<?php _e( 'Start Shopping', 'tostishop' ); ?>
			</a>
		</div>

	<?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
