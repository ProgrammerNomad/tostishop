<?php
/**
 * View Order - Modern Mobile-First Design
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

$notes = $order->get_customer_order_notes();
?>

<div class="woocommerce-MyAccount-view-order">
	
	<!-- Page Header -->
	<div class="mb-8">
		<div class="flex items-center space-x-4 mb-4">
			<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" 
			   class="inline-flex items-center text-sm text-gray-600 hover:text-primary transition-colors">
				<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
				<?php _e( 'Back to orders', 'tostishop' ); ?>
			</a>
		</div>
		
		<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
			<div>
				<h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">
					<?php printf( __( 'Order #%s', 'woocommerce' ), '<span class="text-primary">' . $order->get_order_number() . '</span>' ); ?>
				</h1>
				<p class="text-gray-600">
					<?php printf( __( 'Placed on %s', 'woocommerce' ), '<time datetime="' . $order->get_date_created()->format( 'c' ) . '">' . wc_format_datetime( $order->get_date_created() ) . '</time>' ); ?>
				</p>
			</div>
			
			<!-- Order Status Badge -->
			<div class="mt-4 sm:mt-0">
				<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
					<?php 
					$status_class = '';
					switch ( $order->get_status() ) {
						case 'completed':
							$status_class = 'bg-green-100 text-green-800 border border-green-200';
							break;
						case 'processing':
							$status_class = 'bg-blue-100 text-blue-800 border border-blue-200';
							break;
						case 'on-hold':
							$status_class = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
							break;
						case 'cancelled':
							$status_class = 'bg-red-100 text-red-800 border border-red-200';
							break;
						case 'refunded':
							$status_class = 'bg-gray-100 text-gray-800 border border-gray-200';
							break;
						case 'failed':
							$status_class = 'bg-red-100 text-red-800 border border-red-200';
							break;
						default:
							$status_class = 'bg-gray-100 text-gray-800 border border-gray-200';
					}
					echo $status_class;
					?>">
					<!-- Status Icon -->
					<?php if ( $order->get_status() === 'completed' ) : ?>
						<svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
						</svg>
					<?php elseif ( $order->get_status() === 'processing' ) : ?>
						<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
						</svg>
					<?php elseif ( $order->get_status() === 'on-hold' ) : ?>
						<svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
						</svg>
					<?php elseif ( in_array( $order->get_status(), array( 'cancelled', 'failed' ) ) ) : ?>
						<svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
						</svg>
					<?php endif; ?>
					<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
				</span>
			</div>
		</div>
	</div>

	<!-- Order Summary Grid -->
	<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-8">
		
		<!-- Order Items - Takes 2 columns on large screens -->
		<div class="lg:col-span-2">
			<div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
				<h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
					<svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
					</svg>
					<?php _e( 'Order Items', 'tostishop' ); ?>
				</h2>

				<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>

				<div class="woocommerce-order-details">
					<?php
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
					
					<!-- Order Items List -->
					<div class="space-y-4">
						<?php
						foreach ( $order_items as $item_id => $item ) {
							$product = $item->get_product();

							if ( ! $product ) {
								continue;
							}
							?>
							<div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
								<!-- Product Image -->
								<div class="flex-shrink-0">
									<?php
									$thumbnail = $product->get_image( array( 80, 80 ), array( 'class' => 'w-20 h-20 object-cover rounded-lg' ) );
									if ( $thumbnail ) {
										echo $thumbnail;
									} else {
										echo '<div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center"><svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>';
									}
									?>
								</div>

								<!-- Product Details -->
								<div class="flex-1 min-w-0">
									<div class="flex justify-between items-start">
										<div class="flex-1 min-w-0 pr-4">
											<h3 class="text-base font-semibold text-gray-900 mb-1">
												<?php
												if ( $product && ! $product->is_visible() ) {
													echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );
												} else {
													echo wp_kses_post( sprintf( '<a href="%s" class="hover:text-primary transition-colors">%s</a>', get_permalink( $product->get_id() ), apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) ) );
												}
												?>
											</h3>

											<!-- Product Meta -->
											<?php
											$item_meta = wc_display_item_meta( $item, array(
												'before'    => '<div class="text-sm text-gray-600 mb-2">',
												'after'     => '</div>',
												'separator' => '<br>',
												'echo'      => false,
											) );
											echo $item_meta;
											?>

											<!-- Quantity -->
											<div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
												<?php printf( __( 'Qty: %s', 'woocommerce' ), '<span class="font-semibold">' . $item->get_quantity() . '</span>' ); ?>
											</div>

											<!-- Purchase Note -->
											<?php if ( $show_purchase_note && $product && ( $purchase_note = $product->get_purchase_note() ) ) : ?>
												<div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded text-sm text-blue-800">
													<?php echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) ); ?>
												</div>
											<?php endif; ?>
										</div>

										<!-- Price -->
										<div class="text-right flex-shrink-0">
											<span class="text-base font-bold text-gray-900">
												<?php echo $order->get_formatted_line_subtotal( $item ); ?>
											</span>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</div>

				<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
			</div>
		</div>

		<!-- Order Summary Sidebar -->
		<div class="lg:col-span-1">
			<div class="space-y-6">
				
				<!-- Order Totals -->
				<div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
					<h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
						<svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
						</svg>
						<?php _e( 'Order Summary', 'tostishop' ); ?>
					</h3>

					<div class="space-y-3">
						<?php
						foreach ( $order->get_order_item_totals() as $key => $total ) {
							?>
							<div class="flex justify-between items-center <?php echo ( $key === 'order_total' ) ? 'pt-3 border-t border-gray-200 font-bold text-lg' : 'text-sm'; ?>">
								<span class="<?php echo ( $key === 'order_total' ) ? 'text-gray-900' : 'text-gray-600'; ?>"><?php echo $total['label']; ?></span>
								<span class="<?php echo ( $key === 'order_total' ) ? 'text-primary' : 'text-gray-900'; ?>"><?php echo $total['value']; ?></span>
							</div>
							<?php
						}
						?>
					</div>
				</div>

				<!-- Billing Address -->
				<div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
					<h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
						<svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
						</svg>
						<?php _e( 'Billing Address', 'woocommerce' ); ?>
					</h3>
					
					<address class="text-sm text-gray-700 leading-relaxed not-italic">
						<?php echo ( $address = $order->get_formatted_billing_address() ) ? $address : __( 'N/A', 'woocommerce' ); ?>
					</address>

					<?php if ( $order->get_billing_phone() ) : ?>
						<div class="mt-3 pt-3 border-t border-gray-100">
							<div class="flex items-center text-sm text-gray-600">
								<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
								</svg>
								<span><?php echo esc_html( $order->get_billing_phone() ); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( $order->get_billing_email() ) : ?>
						<div class="mt-2">
							<div class="flex items-center text-sm text-gray-600">
								<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
								</svg>
								<span><?php echo esc_html( $order->get_billing_email() ); ?></span>
							</div>
						</div>
					<?php endif; ?>
				</div>

				<!-- Shipping Address (if different from billing) -->
				<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && ( $shipping = $order->get_formatted_shipping_address() ) ) : ?>
					<div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
						<h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
							<svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
							</svg>
							<?php _e( 'Shipping Address', 'woocommerce' ); ?>
						</h3>
						
						<address class="text-sm text-gray-700 leading-relaxed not-italic">
							<?php echo $shipping; ?>
						</address>
					</div>
				<?php endif; ?>

				<!-- Order Actions -->
				<div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
					<h3 class="text-lg font-semibold text-gray-900 mb-4"><?php _e( 'Actions', 'tostishop' ); ?></h3>
					
					<div class="space-y-3">
						<?php
						$actions = wc_get_account_orders_actions( $order );

						if ( ! empty( $actions ) ) {
							foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
								echo '<a href="' . esc_url( $action['url'] ) . '" class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200 ' . esc_attr( $key ) . ' ';
								
								// Style different action types
								if ( $key === 'pay' ) {
									echo 'bg-primary text-white hover:bg-blue-600';
								} elseif ( $key === 'cancel' ) {
									echo 'bg-red-100 text-red-700 hover:bg-red-200 border border-red-200';
								} else {
									echo 'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200';
								}
								
								echo '">' . esc_html( $action['name'] ) . '</a>';
							}
						}
						?>
						
						<!-- Always show "Back to Orders" -->
						<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" 
						   class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium bg-gray-50 text-gray-700 hover:bg-gray-100 border border-gray-200 rounded-lg transition-colors duration-200">
							<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
							</svg>
							<?php _e( 'All Orders', 'tostishop' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Customer Notes -->
	<?php if ( $notes ) : ?>
		<div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
			<h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
				<svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
				</svg>
				<?php _e( 'Order Updates', 'woocommerce' ); ?>
			</h3>
			
			<div class="space-y-4">
				<?php foreach ( $notes as $note ) : ?>
					<div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
						<div class="flex items-start">
							<div class="flex-shrink-0">
								<svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
								</svg>
							</div>
							<div class="ml-3 flex-1">
								<div class="text-sm text-blue-900">
									<?php echo wp_kses_post( wpautop( wptexturize( $note->comment_content ) ) ); ?>
								</div>
								<div class="mt-2 text-xs text-blue-700">
									<?php echo esc_html( wc_format_datetime( $note->comment_date_gmt ) ); ?>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

</div>

<?php
/**
 * Action hook fired after the order details.
 */
do_action( 'woocommerce_view_order', $order_id );
