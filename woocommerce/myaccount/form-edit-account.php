<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<div class="woocommerce-MyAccount-edit-account">
	
	<!-- Page Header -->
	<div class="mb-8">
		<h2 class="text-2xl font-bold text-gray-900 mb-2"><?php _e( 'Account details', 'tostishop' ); ?></h2>
		<p class="text-gray-600"><?php _e( 'Update your personal information and account settings', 'tostishop' ); ?></p>
	</div>

	<form class="woocommerce-EditAccountForm edit-account space-y-6" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

		<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

		<!-- Personal Information Section -->
		<div class="bg-white border border-gray-200 rounded-lg p-6">
			<h3 class="text-lg font-semibold text-gray-900 mb-4"><?php _e( 'Personal Information', 'tostishop' ); ?></h3>
			
			<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
				<!-- First Name -->
				<div class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
					<label for="account_first_name" class="block text-sm font-medium text-gray-700 mb-2">
						<?php _e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="required text-red-500">*</span>
					</label>
					<input type="text" 
						   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary sm:text-sm" 
						   name="account_first_name" 
						   id="account_first_name" 
						   autocomplete="given-name" 
						   value="<?php echo esc_attr( $user->first_name ); ?>" 
						   required />
				</div>

				<!-- Last Name -->
				<div class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
					<label for="account_last_name" class="block text-sm font-medium text-gray-700 mb-2">
						<?php _e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="required text-red-500">*</span>
					</label>
					<input type="text" 
						   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary sm:text-sm" 
						   name="account_last_name" 
						   id="account_last_name" 
						   autocomplete="family-name" 
						   value="<?php echo esc_attr( $user->last_name ); ?>" 
						   required />
				</div>

				<!-- Display Name -->
				<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide md:col-span-2">
					<label for="account_display_name" class="block text-sm font-medium text-gray-700 mb-2">
						<?php _e( 'Display name', 'woocommerce' ); ?>&nbsp;<span class="required text-red-500">*</span>
					</label>
					<input type="text" 
						   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary sm:text-sm" 
						   name="account_display_name" 
						   id="account_display_name" 
						   value="<?php echo esc_attr( $user->display_name ); ?>" 
						   required />
					<p class="mt-1 text-sm text-gray-500">
						<?php _e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?>
					</p>
				</div>

				<!-- Email -->
				<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide md:col-span-2">
					<label for="account_email" class="block text-sm font-medium text-gray-700 mb-2">
						<?php _e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required text-red-500">*</span>
					</label>
					<input type="email" 
						   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary sm:text-sm" 
						   name="account_email" 
						   id="account_email" 
						   autocomplete="email" 
						   value="<?php echo esc_attr( $user->user_email ); ?>" 
						   required />
				</div>
			</div>
		</div>

		<!-- Password Section -->
		<div class="bg-white border border-gray-200 rounded-lg p-6">
			<h3 class="text-lg font-semibold text-gray-900 mb-2"><?php _e( 'Password change', 'woocommerce' ); ?></h3>
			<p class="text-sm text-gray-600 mb-4"><?php _e( 'Leave blank to leave unchanged', 'woocommerce' ); ?></p>
			
			<div class="space-y-6">
				<!-- Current Password -->
				<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="password_current" class="block text-sm font-medium text-gray-700 mb-2">
						<?php _e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?>
					</label>
					<input type="password" 
						   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary sm:text-sm" 
						   name="password_current" 
						   id="password_current" 
						   autocomplete="current-password" />
				</div>

				<!-- New Password -->
				<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="password_1" class="block text-sm font-medium text-gray-700 mb-2">
						<?php _e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?>
					</label>
					<input type="password" 
						   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary sm:text-sm" 
						   name="password_1" 
						   id="password_1" 
						   autocomplete="new-password" />
				</div>

				<!-- Confirm Password -->
				<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="password_2" class="block text-sm font-medium text-gray-700 mb-2">
						<?php _e( 'Confirm new password', 'woocommerce' ); ?>
					</label>
					<input type="password" 
						   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary focus:border-primary sm:text-sm" 
						   name="password_2" 
						   id="password_2" 
						   autocomplete="new-password" />
				</div>
			</div>
		</div>

		<!-- Additional Account Fields -->
		<?php do_action( 'woocommerce_edit_account_form' ); ?>

		<!-- Security Notice -->
		<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
			<div class="flex items-start">
				<div class="flex-shrink-0">
					<svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
						<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
					</svg>
				</div>
				<div class="ml-3">
					<h4 class="text-sm font-medium text-yellow-900"><?php _e( 'Security Notice', 'tostishop' ); ?></h4>
					<div class="mt-1 text-sm text-yellow-700">
						<p><?php _e( 'For your security, please enter your current password to make changes to your account.', 'tostishop' ); ?></p>
					</div>
				</div>
			</div>
		</div>

		<!-- Submit Button -->
		<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center pt-6 border-t border-gray-200">
			<div class="mb-4 sm:mb-0">
				<p class="text-sm text-gray-600">
					<?php _e( 'Changes will take effect immediately after saving.', 'tostishop' ); ?>
				</p>
			</div>
			
			<div class="flex space-x-3">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" 
				   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
					<?php _e( 'Cancel', 'tostishop' ); ?>
				</a>
				
				<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
				<button type="submit" 
						class="inline-flex items-center px-6 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors duration-200" 
						name="save_account_details" 
						value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
					</svg>
					<?php _e( 'Save changes', 'woocommerce' ); ?>
				</button>
			</div>
		</div>

		<?php do_action( 'woocommerce_edit_account_form_end' ); ?>

	</form>

</div>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
