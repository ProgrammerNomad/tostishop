<?php
/**
 * Checkout terms and conditions area.
 *
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( apply_filters( 'woocommerce_checkout_show_terms', true ) && function_exists( 'wc_terms_and_conditions_checkbox_enabled' ) ) {
    do_action( 'woocommerce_checkout_before_terms_and_conditions' );

    ?>
    <div class="woocommerce-terms-and-conditions-wrapper">
        <?php
        /**
         * Terms and conditions hook used to inject content.
         *
         * @since 3.4.0.
         * @hooked wc_checkout_privacy_policy_text() Shows custom privacy policy text. Priority 20.
         * @hooked wc_terms_and_conditions_page_content() Shows t&c page content. Priority 30.
         */
        do_action( 'woocommerce_checkout_terms_and_conditions' );
        ?>

        <?php if ( wc_terms_and_conditions_checkbox_enabled() ) : ?>
            <div class="form-row validate-required bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4 mt-4">
                <label class="flex items-center space-x-3 cursor-pointer group">
                    <input type="checkbox" 
                           class="w-4 h-4 text-primary bg-white border-gray-300 rounded focus:ring-primary focus:ring-2 transition-all duration-200 flex-shrink-0" 
                           name="terms" 
                           <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); ?> 
                           id="terms" />
                    <span class="text-sm text-gray-700 group-hover:text-gray-900 transition-colors duration-200 flex-1 leading-relaxed">
                        <?php wc_terms_and_conditions_checkbox_text(); ?>
                        <span class="text-red-500 ml-1 font-medium" aria-label="required">*</span>
                    </span>
                </label>
                <input type="hidden" name="terms-field" value="1" />
            </div>
        <?php endif; ?>
    </div>
    <?php

    do_action( 'woocommerce_checkout_after_terms_and_conditions' );
}
