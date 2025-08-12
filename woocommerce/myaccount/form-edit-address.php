<?php
/**
 * Edit address form - TostiShop Address Book Override
 * 
 * This template replaces the default WooCommerce address editing
 * with our custom saved addresses system.
 * 
 * @package TostiShop
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

// Debug: Check if we're in the right context
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('TostiShop: form-edit-address.php loaded');
}

// Check if our saved addresses class exists
if (class_exists('TostiShop_Saved_Addresses')) {
    // Initialize saved addresses
    global $tostishop_saved_addresses;
    if (!$tostishop_saved_addresses) {
        $tostishop_saved_addresses = new TostiShop_Saved_Addresses();
    }
    
    // Get user addresses
    $addresses = $tostishop_saved_addresses->get_user_addresses();
    ?>
    
    <!-- Address Book Content -->
    <div class="woocommerce-MyAccount-content">
        <div class="address-book-wrapper">
            
            <!-- Debug Info (remove in production) -->
            <?php if (defined('WP_DEBUG') && WP_DEBUG): ?>
                <div class="bg-yellow-50 border border-yellow-200 rounded p-2 mb-4 text-xs">
                    <strong>Debug:</strong> Address Book Loaded | JS File: 
                    <span class="font-mono"><?php echo wp_script_is('tostishop-address-book', 'enqueued') ? 'Enqueued' : 'Not Enqueued'; ?></span>
                </div>
            <?php endif; ?>
            
            <!-- Simple Address Book Interface -->
            <div class="address-book-simple">
                
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2"><?php _e('Address Book', 'tostishop'); ?></h2>
                    <p class="text-gray-600"><?php _e('Manage your saved addresses for faster checkout', 'tostishop'); ?></p>
                </div>
                
                <!-- Add New Address Button -->
                <div class="mb-6">
                    <button onclick="toggleAddForm()" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <?php _e('Add New Address', 'tostishop'); ?>
                    </button>
                </div>
    
} else {
    // Fallback: show a message that the address book is not available
    ?>
    <div class="woocommerce-notices-wrapper">
        <div class="woocommerce-message woocommerce-message--info bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-blue-800"><?php _e('The address book feature is currently unavailable. Please contact support.', 'tostishop'); ?></p>
        </div>
    </div>
    
    <div class="woocommerce-MyAccount-content">
        <div class="text-center py-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4"><?php _e('Address Management Unavailable', 'tostishop'); ?></h3>
            <p class="text-gray-600 mb-6"><?php _e('We are currently experiencing technical difficulties with the address book feature.', 'tostishop'); ?></p>
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="inline-flex items-center px-6 py-3 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <?php _e('Go to Dashboard', 'tostishop'); ?>
            </a>
        </div>
    </div>
    <?php
}
