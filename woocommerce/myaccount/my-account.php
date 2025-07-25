<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

get_header(); ?>

<div class="min-h-screen bg-gray-50 py-8">
	<div class="max-w-7xl xl:max-w-[95rem] 2xl:max-w-[110rem] mx-auto px-4 sm:px-6 lg:px-8">
		
		<!-- Page Title -->
		<div class="mb-8">
			<h1 class="text-3xl font-bold text-gray-900"><?php _e( 'My Account', 'tostishop' ); ?></h1>
			<p class="text-gray-600 mt-2"><?php _e( 'Manage your account settings and view your order history', 'tostishop' ); ?></p>
		</div>
		
		<!-- Account Layout -->
		<div class="lg:grid lg:grid-cols-12 lg:gap-8 xl:gap-12 2xl:gap-16">
			
			<!-- Sidebar Navigation -->
			<div class="lg:col-span-3 xl:col-span-2 mb-8 lg:mb-0">
				<div class="lg:sticky lg:top-8">
					<?php
					/**
					 * My Account navigation.
					 */
					do_action( 'woocommerce_account_navigation' ); ?>
				</div>
			</div>
			
			<!-- Main Content -->
			<div class="lg:col-span-9 xl:col-span-10">
				<div class="bg-white rounded-lg shadow-sm border border-gray-200 min-h-[600px] lg:min-h-[700px] xl:min-h-[800px]">
					<div class="p-6 lg:p-8 xl:p-12 2xl:p-16">
						<?php
							/**
							 * My Account content.
							 */
							do_action( 'woocommerce_account_content' );
						?>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>

<?php get_footer(); ?>
