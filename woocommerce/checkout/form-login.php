<?php
/**
 * Checkout login form - Enhanced Mobile-First Design
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-login.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;

if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
    return;
}

?>

<div class="woocommerce-form-login-toggle bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-blue-800 font-medium">
                <?php esc_html_e( 'Returning customer?', 'woocommerce' ); ?>
            </span>
        </div>
        <a href="javascript:void(0)" class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
            <?php esc_html_e( 'Click here to login', 'woocommerce' ); ?>
        </a>
    </div>
</div>

<form class="woocommerce-form woocommerce-form-login login bg-white border border-gray-200 rounded-lg p-6 mb-6" method="post" style="display:none;">
    
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            <?php esc_html_e( 'Login to your account', 'woocommerce' ); ?>
        </h3>
        <p class="text-sm text-gray-600">
            <?php esc_html_e( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'woocommerce' ); ?>
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div class="form-row form-row-first">
            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                <?php esc_html_e( 'Username or email', 'woocommerce' ); ?>
                <span class="required text-red-500">*</span>
            </label>
            <input type="text" 
                   class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" 
                   name="username" 
                   id="username" 
                   autocomplete="username" 
                   value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
        </div>

        <div class="form-row form-row-last">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                <?php esc_html_e( 'Password', 'woocommerce' ); ?>
                <span class="required text-red-500">*</span>
            </label>
            <input type="password" 
                   class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" 
                   name="password" 
                   id="password" 
                   autocomplete="current-password" />
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 mb-4">
        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline-flex items-center cursor-pointer">
            <input class="woocommerce-form__input woocommerce-form__input-checkbox mr-2" 
                   name="rememberme" 
                   type="checkbox" 
                   id="rememberme" 
                   value="forever" />
            <span class="text-sm text-gray-700"><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
        </label>

        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" 
           class="text-sm text-primary hover:text-blue-600 transition-colors">
            <?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?>
        </a>
    </div>

    <div class="clear"></div>

    <?php do_action( 'woocommerce_login_form' ); ?>

    <div class="form-row">
        <button type="submit" 
                class="w-full bg-primary text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-600 transition-colors" 
                name="login" 
                value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>">
            <?php esc_html_e( 'Login', 'woocommerce' ); ?>
        </button>
    </div>

    <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
</form>
