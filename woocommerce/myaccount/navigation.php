<?php
/**
 * My Account Navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation" x-data="{ mobileMenuOpen: false }">
	
	<!-- Mobile Navigation Toggle -->
	<div class="lg:hidden mb-6">
		<button @click="mobileMenuOpen = !mobileMenuOpen" 
				class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-left text-gray-700 hover:bg-gray-100 transition-colors duration-200">
			<span class="font-medium"><?php _e('Account Menu', 'tostishop'); ?></span>
			<svg class="w-5 h-5 transform transition-transform duration-200" :class="{ 'rotate-180': mobileMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
			</svg>
		</button>
	</div>

	<!-- Navigation Menu -->
	<ul class="woocommerce-MyAccount-navigation-list lg:block space-y-2 lg:space-y-1" 
		:class="{ 'hidden': !mobileMenuOpen }" 
		x-show="mobileMenuOpen || window.innerWidth >= 1024"
		x-transition:enter="transition ease-out duration-200"
		x-transition:enter-start="opacity-0 transform scale-95"
		x-transition:enter-end="opacity-100 transform scale-100"
		x-transition:leave="transition ease-in duration-75"
		x-transition:leave-start="opacity-100 transform scale-100"
		x-transition:leave-end="opacity-0 transform scale-95"
		style="display: block;"
		class="lg:!block"
		>
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" 
				   class="account-nav-link flex items-center px-4 py-3 lg:py-3 xl:py-4 text-sm lg:text-base font-medium rounded-lg transition-all duration-200 group
				          <?php echo wc_get_account_menu_item_classes( $endpoint ) === 'is-active' ? 'bg-primary text-white shadow-sm' : 'text-gray-700 hover:bg-gray-50 hover:text-primary'; ?>">
					
					<!-- Icons for each menu item -->
					<?php if ( $endpoint === 'dashboard' ) : ?>
						<svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z"></path>
						</svg>
					<?php elseif ( $endpoint === 'orders' ) : ?>
						<svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
						</svg>
					<?php elseif ( $endpoint === 'downloads' ) : ?>
						<svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
						</svg>
					<?php elseif ( $endpoint === 'edit-address' ) : ?>
						<svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
						</svg>
					<?php elseif ( $endpoint === 'edit-account' ) : ?>
						<svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
						</svg>
					<?php elseif ( $endpoint === 'customer-logout' ) : ?>
						<svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
						</svg>
					<?php else : ?>
						<svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
						</svg>
					<?php endif; ?>
					
					<span class="flex-1"><?php echo esc_html( $label ); ?></span>
					
					<!-- Desktop: Show active indicator and hover arrow -->
					<div class="hidden lg:flex items-center ml-2">
						<?php if ( wc_get_account_menu_item_classes( $endpoint ) === 'is-active' ) : ?>
							<svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
							</svg>
						<?php else : ?>
							<svg class="w-4 h-4 flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
							</svg>
						<?php endif; ?>
					</div>
					
					<!-- Mobile: Show active indicator only -->
					<div class="lg:hidden">
						<?php if ( wc_get_account_menu_item_classes( $endpoint ) === 'is-active' ) : ?>
							<svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
							</svg>
						<?php endif; ?>
					</div>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
