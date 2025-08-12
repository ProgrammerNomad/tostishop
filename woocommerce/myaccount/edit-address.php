<?php
/**
 * TostiShop Custom Address Book - Edit Address Endpoint Override
 * 
 * This template handles the edit-address endpoint and redirects to our
 * custom address book system.
 * 
 * @package TostiShop
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

// Check if our saved addresses class exists and is active
if (class_exists('TostiShop_Saved_Addresses')) {
    // Use our custom address book system
    global $tostishop_saved_addresses;
    if (!$tostishop_saved_addresses) {
        $tostishop_saved_addresses = new TostiShop_Saved_Addresses();
    }
    
    // Get user addresses for our system
    $addresses = $tostishop_saved_addresses->get_user_addresses();
    
    // Include our custom address book template
    include get_template_directory() . '/woocommerce/myaccount/address-book.php';
    
} else {
    // Fallback: show a message that the address book is not available
    ?>
    <div class="woocommerce-notices-wrapper">
        <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-blue-800"><?php _e('The address book feature is currently unavailable. Please contact support.', 'tostishop'); ?></p>
        </div>
    </div>
    
    <div class="woocommerce-MyAccount-content">
        <div class="text-center py-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4"><?php _e('Address Management Unavailable', 'tostishop'); ?></h3>
            <p class="text-gray-600 mb-6"><?php _e('We are currently experiencing technical difficulties with the address book feature.', 'tostishop'); ?></p>
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="inline-flex items-center px-6 py-3 bg-primary text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <?php _e('Go to Dashboard', 'tostishop'); ?>
            </a>
        </div>
    </div>
    <?php
}
