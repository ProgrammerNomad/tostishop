<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>

<div class="woocommerce-MyAccount-dashboard bg-white">
	
	<!-- Welcome Header -->
	<div class="bg-gradient-to-r from-primary to-blue-600 text-white rounded-lg p-6 lg:p-8 mb-8">
		<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
			<div class="flex-1">
				<h1 class="text-2xl lg:text-3xl font-bold mb-2">
					<?php printf( __( 'Hello %1$s', 'tostishop' ), '<span class="font-normal">' . esc_html( $current_user->display_name ) . '</span>' ); ?>
				</h1>
				<p class="text-blue-100 text-sm lg:text-base">
					<?php _e( 'Welcome to your account dashboard', 'tostishop' ); ?>
				</p>
			</div>
			<div class="mt-4 lg:mt-0 lg:ml-8">
				<div class="bg-white bg-opacity-20 rounded-lg p-3 lg:p-4 text-center">
					<div class="text-sm lg:text-base text-blue-100"><?php _e( 'Member since', 'tostishop' ); ?></div>
					<div class="font-semibold text-lg lg:text-xl"><?php echo date_i18n( 'F Y', strtotime( $current_user->user_registered ) ); ?></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Quick Stats -->
	<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
		
		<!-- Total Orders -->
		<div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
			<div class="text-2xl font-bold text-gray-900 mb-1">
				<?php
				$orders_count = wc_get_customer_order_count( get_current_user_id() );
				echo $orders_count;
				?>
			</div>
			<div class="text-sm text-gray-600"><?php _e( 'Total Orders', 'tostishop' ); ?></div>
		</div>

		<!-- Recent Orders -->
		<div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
			<div class="text-2xl font-bold text-gray-900 mb-1">
				<?php
				$recent_orders = wc_get_orders( array(
					'customer' => get_current_user_id(),
					'limit' => -1,
					'date_created' => '>' . ( time() - 30 * DAY_IN_SECONDS ),
				) );
				echo count( $recent_orders );
				?>
			</div>
			<div class="text-sm text-gray-600"><?php _e( 'This Month', 'tostishop' ); ?></div>
		</div>

		<!-- Total Spent -->
		<div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
			<div class="text-2xl font-bold text-gray-900 mb-1">
				<?php echo wc_price( wc_get_customer_total_spent( get_current_user_id() ) ); ?>
			</div>
			<div class="text-sm text-gray-600"><?php _e( 'Total Spent', 'tostishop' ); ?></div>
		</div>

		<!-- Downloads -->
		<div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
			<div class="text-2xl font-bold text-gray-900 mb-1">
				<?php
				$downloads = WC()->customer->get_downloadable_products();
				echo count( $downloads );
				?>
			</div>
			<div class="text-sm text-gray-600"><?php _e( 'Downloads', 'tostishop' ); ?></div>
		</div>
	</div>

	<!-- Recent Orders Section -->
	<?php 
	$recent_orders = wc_get_orders( array(
		'customer' => get_current_user_id(),
		'limit' => 5,
	) );
	
	if ( $recent_orders ) : ?>
	<div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
		<div class="flex items-center justify-between mb-4">
			<h3 class="text-lg font-semibold text-gray-900"><?php _e( 'Recent Orders', 'tostishop' ); ?></h3>
			<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" 
			   class="text-sm text-primary hover:text-blue-600 font-medium">
				<?php _e( 'View all orders', 'tostishop' ); ?>
			</a>
		</div>
		
		<div class="space-y-3">
			<?php foreach ( $recent_orders as $order ) : ?>
			<div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-gray-50 rounded-lg">
				<div class="flex-1">
					<div class="flex items-center space-x-3 mb-2 sm:mb-0">
						<span class="text-sm font-medium text-gray-900">
							#<?php echo $order->get_order_number(); ?>
						</span>
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
								default:
									echo 'bg-gray-100 text-gray-800';
							}
							?>">
							<?php echo wc_get_order_status_name( $status ); ?>
						</span>
					</div>
					<div class="text-sm text-gray-600">
						<?php echo $order->get_date_created()->date_i18n( 'F j, Y' ); ?> • 
						<?php echo $order->get_formatted_order_total(); ?>
					</div>
				</div>
				<div class="mt-3 sm:mt-0">
					<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" 
					   class="inline-flex items-center px-3 py-1 text-sm font-medium text-primary hover:text-blue-600">
						<?php _e( 'View', 'tostishop' ); ?>
						<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
						</svg>
					</a>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>

	<!-- Quick Actions -->
	<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-8">
		
		<!-- Browse Products -->
		<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" 
		   class="block p-6 lg:p-8 bg-white border border-gray-200 rounded-lg hover:shadow-lg transition-all duration-200 group">
			<div class="flex items-center">
				<div class="flex-shrink-0">
					<div class="w-12 h-12 lg:w-14 lg:h-14 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center group-hover:bg-opacity-20 transition-colors duration-200">
						<svg class="w-6 h-6 lg:w-7 lg:h-7 text-primary group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
						</svg>
					</div>
				</div>
				<div class="ml-4 lg:ml-5">
					<h4 class="text-lg lg:text-xl font-medium text-gray-900 group-hover:text-primary transition-colors duration-200"><?php _e( 'Browse Products', 'tostishop' ); ?></h4>
					<p class="text-sm lg:text-base text-gray-600"><?php _e( 'Discover our latest products', 'tostishop' ); ?></p>
				</div>
			</div>
		</a>

		<!-- Edit Profile -->
		<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>" 
		   class="block p-6 lg:p-8 bg-white border border-gray-200 rounded-lg hover:shadow-lg transition-all duration-200 group">
			<div class="flex items-center">
				<div class="flex-shrink-0">
					<div class="w-12 h-12 lg:w-14 lg:h-14 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center group-hover:bg-opacity-20 transition-colors duration-200">
						<svg class="w-6 h-6 lg:w-7 lg:h-7 text-primary group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
						</svg>
					</div>
				</div>
				<div class="ml-4 lg:ml-5">
					<h4 class="text-lg lg:text-xl font-medium text-gray-900 group-hover:text-primary transition-colors duration-200"><?php _e( 'Edit Profile', 'tostishop' ); ?></h4>
					<p class="text-sm lg:text-base text-gray-600"><?php _e( 'Update your account details', 'tostishop' ); ?></p>
				</div>
			</div>
		</a>

		<!-- Manage Addresses -->
		<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>" 
		   class="block p-6 lg:p-8 bg-white border border-gray-200 rounded-lg hover:shadow-lg transition-all duration-200 group">
			<div class="flex items-center">
				<div class="flex-shrink-0">
					<div class="w-12 h-12 lg:w-14 lg:h-14 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center group-hover:bg-opacity-20 transition-colors duration-200">
						<svg class="w-6 h-6 lg:w-7 lg:h-7 text-primary group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
						</svg>
					</div>
				</div>
				<div class="ml-4 lg:ml-5">
					<h4 class="text-lg lg:text-xl font-medium text-gray-900 group-hover:text-primary transition-colors duration-200"><?php _e( 'Manage Addresses', 'tostishop' ); ?></h4>
					<p class="text-sm lg:text-base text-gray-600"><?php _e( 'Update billing & shipping', 'tostishop' ); ?></p>
				</div>
			</div>
		</a>
	</div>

	<!-- Default WooCommerce Content -->
	<div class="prose max-w-none">
		<?php
		/* translators: 1: user display name 2: logout url */
		printf(
			wp_kses( __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' ), $allowed_html ),
			esc_url( wc_get_account_endpoint_url( 'orders' ) ),
			esc_url( wc_get_account_endpoint_url( 'edit-address' ) ),
			esc_url( wc_get_account_endpoint_url( 'edit-account' ) )
		);
		?>
	</div>

</div>

<?php
/**
 * My Account dashboard.
 */
do_action( 'woocommerce_account_dashboard' );

/**
 * Deprecated woocommerce_before_my_account action.
 */
do_action( 'woocommerce_before_my_account' );

/**
 * Deprecated woocommerce_after_my_account action.
 */
do_action( 'woocommerce_after_my_account' );
?>
