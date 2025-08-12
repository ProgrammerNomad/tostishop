<?php
/**
 * WooCommerce Customizations
 * 
 * @package TostiShop
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Amazon-style Address Picker for WooCommerce Checkout
 * Compact version with modal selection
 */
function tostishop_amazon_style_address_picker($checkout) {
    if (!is_user_logged_in()) {
        return;
    }
    
    // Get saved addresses from database using existing class
    global $tostishop_saved_addresses;
    if (!$tostishop_saved_addresses) {
        $tostishop_saved_addresses = new TostiShop_Saved_Addresses();
    }
    
    // Get addresses from database
    $billing_addresses = $tostishop_saved_addresses->get_user_addresses(null, 'billing');
    $shipping_addresses = $tostishop_saved_addresses->get_user_addresses(null, 'shipping');
    
    // Find default addresses
    $default_billing = null;
    $default_shipping = null;
    
    foreach ($billing_addresses as $addr) {
        if (!empty($addr->is_default)) {
            $default_billing = $addr;
            break;
        }
    }
    
    foreach ($shipping_addresses as $addr) {
        if (!empty($addr->is_default)) {
            $default_shipping = $addr;
            break;
        }
    }
    
    // If no default, use first address
    if (!$default_billing && !empty($billing_addresses)) {
        $default_billing = $billing_addresses[0];
    }
    
    if (!$default_shipping && !empty($shipping_addresses)) {
        $default_shipping = $shipping_addresses[0];
    }
    
    ?>
    <div id="tostishop-compact-address-picker" class="mb-6 bg-white border border-gray-200 rounded-lg shadow-sm" 
         x-data="compactAddressPicker()" x-init="init()">
        
        <!-- Billing Address Section - Compact -->
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-3 flex-1 min-w-0">
                    <!-- Icon -->
                    <div class="p-2 bg-red-100 rounded-lg flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    
                    <!-- Address Info -->
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-medium text-gray-900 mb-1">
                            <?php _e('Billing Address', 'tostishop'); ?>
                        </h3>
                        
                        <div x-show="selectedBillingAddress" class="text-sm text-gray-900">
                            <div class="font-medium" x-text="selectedBillingAddress?.first_name + ' ' + selectedBillingAddress?.last_name"></div>
                            <div class="text-gray-600 text-xs mt-1" x-text="getBillingAddressPreview()"></div>
                        </div>
                        
                        <div x-show="!selectedBillingAddress" class="text-sm text-gray-500">
                            <?php _e('No billing address selected', 'tostishop'); ?>
                        </div>
                    </div>
                </div>
                
                <!-- Change Button -->
                <button @click="openBillingModal()" type="button" 
                        class="ml-4 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 transition-colors duration-200 flex-shrink-0">
                    <?php _e('Change', 'tostishop'); ?>
                </button>
            </div>
        </div>
        
        <!-- Shipping Address Section - Compact -->
        <div class="p-4">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-3 flex-1 min-w-0">
                    <!-- Icon -->
                    <div class="p-2 bg-green-100 rounded-lg flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    
                    <!-- Address Info -->
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-medium text-gray-900 mb-1">
                            <?php _e('Shipping Address', 'tostishop'); ?>
                        </h3>
                        
                        <div x-show="useSameBilling" class="text-sm text-gray-600">
                            <?php _e('Same as billing address', 'tostishop'); ?>
                        </div>
                        
                        <div x-show="!useSameBilling && selectedShippingAddress" class="text-sm text-gray-900">
                            <div class="font-medium" x-text="selectedShippingAddress?.first_name + ' ' + selectedShippingAddress?.last_name"></div>
                            <div class="text-gray-600 text-xs mt-1" x-text="getShippingAddressPreview()"></div>
                        </div>
                        
                        <div x-show="!useSameBilling && !selectedShippingAddress" class="text-sm text-gray-500">
                            <?php _e('No shipping address selected', 'tostishop'); ?>
                        </div>
                    </div>
                </div>
                
                <!-- Change Button -->
                <button @click="openShippingModal()" type="button" 
                        class="ml-4 px-3 py-1.5 text-xs font-medium text-green-600 bg-green-50 border border-green-200 rounded-md hover:bg-green-100 transition-colors duration-200 flex-shrink-0">
                    <?php _e('Change', 'tostishop'); ?>
                </button>
            </div>
        </div>
        
        <!-- Billing Address Modal -->
        <div x-show="showBillingModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 overflow-hidden"
             style="display: none;"
             @click.self="closeBillingModal()"
             @keydown.escape="closeBillingModal()">
            
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
                <!-- Modal Header - Fixed -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 flex-shrink-0">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <?php _e('Choose Billing Address', 'tostishop'); ?>
                    </h2>
                    <button @click="closeBillingModal()" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Modal Body - Scrollable -->
                <div class="flex-1 overflow-y-auto p-6">
                    <div class="space-y-3">
                        <?php if (!empty($billing_addresses)) : ?>
                            <?php foreach ($billing_addresses as $index => $address) : ?>
                                <div @click="selectBillingAddress(<?php echo $index; ?>)" 
                                     class="border border-gray-200 rounded-lg p-4 cursor-pointer transition-all duration-200 hover:border-red-300 hover:bg-red-50"
                                     :class="selectedBillingIndex === <?php echo $index; ?> ? 'border-red-500 bg-red-50 ring-1 ring-red-200' : ''">
                                    
                                    <div class="flex items-start space-x-3">
                                        <!-- Radio Button -->
                                        <div class="w-4 h-4 border-2 rounded-full flex items-center justify-center mt-0.5 flex-shrink-0"
                                             :class="selectedBillingIndex === <?php echo $index; ?> ? 'border-red-500 bg-red-500' : 'border-gray-300'">
                                            <div x-show="selectedBillingIndex === <?php echo $index; ?>" class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                        </div>
                                        
                                        <!-- Address Details -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <span class="font-medium text-gray-900">
                                                    <?php echo esc_html($address->first_name . ' ' . $address->last_name); ?>
                                                </span>
                                                <?php if (!empty($address->is_default)) : ?>
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                        <?php _e('Default', 'tostishop'); ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($address->address_name)) : ?>
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                                        <?php echo esc_html($address->address_name); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="text-sm text-gray-600 space-y-1">
                                                <?php if (!empty($address->company)) : ?>
                                                    <div><?php echo esc_html($address->company); ?></div>
                                                <?php endif; ?>
                                                <div><?php echo esc_html($address->address_1); ?></div>
                                                <?php if (!empty($address->address_2)) : ?>
                                                    <div><?php echo esc_html($address->address_2); ?></div>
                                                <?php endif; ?>
                                                <div><?php echo esc_html($address->city . ', ' . $address->state . ' ' . $address->postcode); ?></div>
                                                <div><?php echo esc_html(WC()->countries->countries[$address->country] ?? $address->country); ?></div>
                                                <?php if (!empty($address->phone)) : ?>
                                                    <div><?php _e('Phone:', 'tostishop'); ?> <?php echo esc_html($address->phone); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <!-- Add New Address Option -->
                        <div @click="showAddBillingForm = true" 
                             class="border-2 border-dashed border-gray-300 rounded-lg p-4 cursor-pointer transition-all duration-200 hover:border-red-400 hover:bg-red-50 text-center">
                            <div class="flex items-center justify-center space-x-2 text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span class="font-medium">
                                    <?php _e('Add New Billing Address', 'tostishop'); ?>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Add New Address Form -->
                        <div x-show="showAddBillingForm" x-transition class="border-t border-gray-200 pt-6 mt-6">
                            <h3 class="text-md font-semibold text-gray-900 mb-4">
                                <?php _e('Add New Billing Address', 'tostishop'); ?>
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Address Label -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <?php _e('Address Label', 'tostishop'); ?>
                                    </label>
                                    <input type="text" x-model="newBillingForm.address_name" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                           placeholder="<?php _e('Home, Office, etc.', 'tostishop'); ?>">
                                </div>
                                
                                <!-- First Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <?php _e('First Name', 'tostishop'); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="newBillingForm.first_name" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                </div>
                                
                                <!-- Last Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <?php _e('Last Name', 'tostishop'); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="newBillingForm.last_name" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                </div>
                                
                                <!-- Address -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <?php _e('Address', 'tostishop'); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="newBillingForm.address_1" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                </div>
                                
                                <!-- City -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <?php _e('City', 'tostishop'); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="newBillingForm.city" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                </div>
                                
                                <!-- ZIP Code -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <?php _e('ZIP Code', 'tostishop'); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="newBillingForm.postcode" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                </div>
                                
                                <!-- Phone -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <?php _e('Phone', 'tostishop'); ?>
                                    </label>
                                    <input type="tel" x-model="newBillingForm.phone"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                </div>
                                
                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <?php _e('Email', 'tostishop'); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" x-model="newBillingForm.email" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                                </div>
                            </div>
                            
                            <!-- Error Message -->
                            <div x-show="errorMessage" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-600" x-text="errorMessage"></p>
                            </div>
                            
                            <div class="flex justify-end space-x-3 mt-6">
                                <button @click="showAddBillingForm = false" type="button" 
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                    <?php _e('Cancel', 'tostishop'); ?>
                                </button>
                                <button @click="saveNewBillingAddress()" type="button" 
                                        :disabled="isLoading"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 disabled:opacity-50">
                                    <span x-show="!isLoading"><?php _e('Save Address', 'tostishop'); ?></span>
                                    <span x-show="isLoading"><?php _e('Saving...', 'tostishop'); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Shipping Address Modal -->
        <div x-show="showShippingModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 overflow-hidden"
             style="display: none;"
             @click.self="closeShippingModal()"
             @keydown.escape="closeShippingModal()">
            
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
                <!-- Modal Header - Fixed -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 flex-shrink-0">
                    <h2 class="text-lg font-semibold text-gray-900">
                        <?php _e('Choose Shipping Address', 'tostishop'); ?>
                    </h2>
                    <button @click="closeShippingModal()" class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Modal Body - Scrollable -->
                <div class="flex-1 overflow-y-auto p-6">
                    <div class="space-y-3">
                        <!-- Same as Billing Option -->
                        <div @click="selectSameBilling()" 
                             class="border border-gray-200 rounded-lg p-4 cursor-pointer transition-all duration-200 hover:border-green-300 hover:bg-green-50"
                             :class="useSameBilling ? 'border-green-500 bg-green-50 ring-1 ring-green-200' : ''">
                            
                            <div class="flex items-start space-x-3">
                                <!-- Radio Button -->
                                <div class="w-4 h-4 border-2 rounded-full flex items-center justify-center mt-0.5 flex-shrink-0"
                                     :class="useSameBilling ? 'border-green-500 bg-green-500' : 'border-gray-300'">
                                    <div x-show="useSameBilling" class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                </div>
                                
                                <!-- Option Details -->
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900 mb-1">
                                        <?php _e('Same as billing address', 'tostishop'); ?>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <?php _e('Use your billing address for shipping', 'tostishop'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($shipping_addresses)) : ?>
                            <?php foreach ($shipping_addresses as $index => $address) : ?>
                                <div @click="selectShippingAddress(<?php echo $index; ?>)" 
                                     class="border border-gray-200 rounded-lg p-4 cursor-pointer transition-all duration-200 hover:border-green-300 hover:bg-green-50"
                                     :class="selectedShippingIndex === <?php echo $index; ?> && !useSameBilling ? 'border-green-500 bg-green-50 ring-1 ring-green-200' : ''">
                                    
                                    <div class="flex items-start space-x-3">
                                        <!-- Radio Button -->
                                        <div class="w-4 h-4 border-2 rounded-full flex items-center justify-center mt-0.5 flex-shrink-0"
                                             :class="selectedShippingIndex === <?php echo $index; ?> && !useSameBilling ? 'border-green-500 bg-green-500' : 'border-gray-300'">
                                            <div x-show="selectedShippingIndex === <?php echo $index; ?> && !useSameBilling" class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                        </div>
                                        
                                        <!-- Address Details -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <span class="font-medium text-gray-900">
                                                    <?php echo esc_html($address->first_name . ' ' . $address->last_name); ?>
                                                </span>
                                                <?php if (!empty($address->is_default)) : ?>
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                        <?php _e('Default', 'tostishop'); ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($address->address_name)) : ?>
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                                        <?php echo esc_html($address->address_name); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="text-sm text-gray-600 space-y-1">
                                                <?php if (!empty($address->company)) : ?>
                                                    <div><?php echo esc_html($address->company); ?></div>
                                                <?php endif; ?>
                                                <div><?php echo esc_html($address->address_1); ?></div>
                                                <?php if (!empty($address->address_2)) : ?>
                                                    <div><?php echo esc_html($address->address_2); ?></div>
                                                <?php endif; ?>
                                                <div><?php echo esc_html($address->city . ', ' . $address->state . ' ' . $address->postcode); ?></div>
                                                <div><?php echo esc_html(WC()->countries->countries[$address->country] ?? $address->country); ?></div>
                                                <?php if (!empty($address->phone)) : ?>
                                                    <div><?php _e('Phone:', 'tostishop'); ?> <?php echo esc_html($address->phone); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <!-- Add New Shipping Address Option -->
                        <div @click="showAddShippingForm = true" 
                             class="border-2 border-dashed border-gray-300 rounded-lg p-4 cursor-pointer transition-all duration-200 hover:border-green-400 hover:bg-green-50 text-center">
                            <div class="flex items-center justify-center space-x-2 text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span class="font-medium">
                                    <?php _e('Add New Shipping Address', 'tostishop'); ?>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Add New Shipping Address Form -->
                        <div x-show="showAddShippingForm" x-transition class="border-t border-gray-200 pt-6 mt-6">
                            <h3 class="text-md font-semibold text-gray-900 mb-4">
                                <?php _e('Add New Shipping Address', 'tostishop'); ?>
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Address Label -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <?php _e('Address Label', 'tostishop'); ?>
                                    </label>
                                    <input type="text" x-model="newShippingForm.address_name" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                           placeholder="<?php _e('Home, Office, etc.', 'tostishop'); ?>">
                                </div>
                                
                                <!-- First Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <?php _e('First Name', 'tostishop'); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="newShippingForm.first_name" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>
                                
                                <!-- Last Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <?php _e('Last Name', 'tostishop'); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="newShippingForm.last_name" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>
                                
                                <!-- Address -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <?php _e('Address', 'tostishop'); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="newShippingForm.address_1" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>
                                
                                <!-- City -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <?php _e('City', 'tostishop'); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="newShippingForm.city" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>
                                
                                <!-- ZIP Code -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <?php _e('ZIP Code', 'tostishop'); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" x-model="newShippingForm.postcode" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>
                                
                                <!-- Phone -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        <?php _e('Phone', 'tostishop'); ?>
                                    </label>
                                    <input type="tel" x-model="newShippingForm.phone"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>
                            </div>
                            
                            <!-- Error Message -->
                            <div x-show="errorMessage" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-600" x-text="errorMessage"></p>
                            </div>
                            
                            <div class="flex justify-end space-x-3 mt-6">
                                <button @click="showAddShippingForm = false" type="button" 
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                    <?php _e('Cancel', 'tostishop'); ?>
                                </button>
                                <button @click="saveNewShippingAddress()" type="button" 
                                        :disabled="isLoading"
                                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 disabled:opacity-50">
                                    <span x-show="!isLoading"><?php _e('Save Address', 'tostishop'); ?></span>
                                    <span x-show="isLoading"><?php _e('Saving...', 'tostishop'); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Hide original WooCommerce address fields
    document.addEventListener('DOMContentLoaded', function() {
        const billingWrapper = document.querySelector('.woocommerce-billing-fields__field-wrapper');
        const shippingWrapper = document.querySelector('.woocommerce-shipping-fields__field-wrapper');
        
        if (billingWrapper) billingWrapper.style.display = 'none';
        if (shippingWrapper) shippingWrapper.style.display = 'none';
    });

    // Complete Alpine.js Component Definition
    function compactAddressPicker() {
        return {
            // Modal states
            showBillingModal: false,
            showShippingModal: false,
            showAddBillingForm: false,
            showAddShippingForm: false,
            
            // Selection states
            selectedBillingIndex: <?php echo json_encode($default_billing ? 0 : null); ?>,
            selectedShippingIndex: <?php echo json_encode($default_shipping ? 0 : null); ?>,
            selectedBillingAddress: <?php echo $default_billing ? json_encode($default_billing) : 'null'; ?>,
            selectedShippingAddress: <?php echo $default_shipping ? json_encode($default_shipping) : 'null'; ?>,
            useSameBilling: <?php echo json_encode(!$default_shipping); ?>,
            
            // Loading and error states - PROPERLY INITIALIZED
            isLoading: false,
            errorMessage: '',
            
            // Address data
            billingAddresses: <?php echo json_encode($billing_addresses); ?>,
            shippingAddresses: <?php echo json_encode($shipping_addresses); ?>,
            
            // New address forms
            newBillingForm: {
                address_name: '',
                first_name: '',
                last_name: '',
                company: '',
                address_1: '',
                address_2: '',
                city: '',
                state: '',
                postcode: '',
                country: 'US',
                phone: '',
                email: ''
            },
            newShippingForm: {
                address_name: '',
                first_name: '',
                last_name: '',
                company: '',
                address_1: '',
                address_2: '',
                city: '',
                state: '',
                postcode: '',
                country: 'US',
                phone: ''
            },
            
            init() {
                console.log('Compact Address Picker initialized');
                this.populateFields();
                this.setupFieldWatchers();
            },
            
            // Modal Management with body scroll lock
            openBillingModal() {
                this.showBillingModal = true;
                this.showAddBillingForm = false;
                this.errorMessage = '';
                document.body.style.overflow = 'hidden';
            },
            
            closeBillingModal() {
                this.showBillingModal = false;
                this.showAddBillingForm = false;
                document.body.style.overflow = '';
            },
            
            openShippingModal() {
                this.showShippingModal = true;
                this.showAddShippingForm = false;
                this.errorMessage = '';
                document.body.style.overflow = 'hidden';
            },
            
            closeShippingModal() {
                this.showShippingModal = false;
                this.showAddShippingForm = false;
                document.body.style.overflow = '';
            },
            
            // Address Selection
            selectBillingAddress(index) {
                this.selectedBillingIndex = index;
                this.selectedBillingAddress = this.billingAddresses[index];
                this.populateBillingFields(this.billingAddresses[index]);
                
                if (this.useSameBilling) {
                    this.populateShippingFields(this.billingAddresses[index]);
                }
                
                this.closeBillingModal();
                this.triggerCheckoutUpdate();
            },
            
            selectShippingAddress(index) {
                this.selectedShippingIndex = index;
                this.selectedShippingAddress = this.shippingAddresses[index];
                this.useSameBilling = false;
                this.populateShippingFields(this.shippingAddresses[index]);
                this.closeShippingModal();
                this.triggerCheckoutUpdate();
            },
            
            selectSameBilling() {
                this.useSameBilling = true;
                this.selectedShippingIndex = null;
                this.selectedShippingAddress = null;
                
                if (this.selectedBillingAddress) {
                    this.populateShippingFields(this.selectedBillingAddress);
                }
                
                this.closeShippingModal();
                this.triggerCheckoutUpdate();
            },
            
            // Field Population
            populateFields() {
                if (this.selectedBillingAddress) {
                    this.populateBillingFields(this.selectedBillingAddress);
                }
                
                if (this.useSameBilling && this.selectedBillingAddress) {
                    this.populateShippingFields(this.selectedBillingAddress);
                } else if (this.selectedShippingAddress) {
                    this.populateShippingFields(this.selectedShippingAddress);
                }
            },
            
            populateBillingFields(address) {
                this.fillFormFields(address, 'billing');
            },
            
            populateShippingFields(address) {
                this.fillFormFields(address, 'shipping');
            },
            
            fillFormFields(address, type) {
                const fields = [
                    'first_name', 'last_name', 'company', 'address_1', 'address_2',
                    'city', 'state', 'postcode', 'country', 'phone'
                ];
                
                if (type === 'billing') {
                    fields.push('email');
                }
                
                fields.forEach(field => {
                    const fieldElement = document.getElementById(type + '_' + field) || 
                                       document.querySelector(`[name="${type}_${field}"]`);
                    if (fieldElement && address[field]) {
                        fieldElement.value = address[field];
                        fieldElement.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                });
            },
            
            // Address Preview Helpers
            getBillingAddressPreview() {
                if (!this.selectedBillingAddress) return '';
                
                const address = this.selectedBillingAddress;
                const parts = [
                    address.address_1,
                    address.city,
                    address.state,
                    address.postcode
                ].filter(Boolean);
                
                return parts.join(', ');
            },
            
            getShippingAddressPreview() {
                if (!this.selectedShippingAddress) return '';
                
                const address = this.selectedShippingAddress;
                const parts = [
                    address.address_1,
                    address.city,
                    address.state,
                    address.postcode
                ].filter(Boolean);
                
                return parts.join(', ');
            },
            
            // Save New Addresses
            async saveNewBillingAddress() {
                await this.saveNewAddress('billing');
            },
            
            async saveNewShippingAddress() {
                await this.saveNewAddress('shipping');
            },
            
            async saveNewAddress(type) {
                this.isLoading = true;
                this.errorMessage = '';

                try {
                    const formData = new FormData();
                    formData.append('action', 'tostishop_save_new_address');
                    formData.append('nonce', '<?php echo wp_create_nonce('tostishop_address_nonce'); ?>');
                    formData.append('type', type);

                    const form = type === 'billing' ? this.newBillingForm : this.newShippingForm;
                    
                    Object.keys(form).forEach(key => {
                        if (form[key]) {
                            formData.append(key, form[key]);
                        }
                    });

                    const response = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Reset form
                        if (type === 'billing') {
                            this.newBillingForm = {
                                address_name: '', first_name: '', last_name: '', company: '',
                                address_1: '', address_2: '', city: '', state: '', postcode: '',
                                country: 'US', phone: '', email: ''
                            };
                            this.showAddBillingForm = false;
                        } else {
                            this.newShippingForm = {
                                address_name: '', first_name: '', last_name: '', company: '',
                                address_1: '', address_2: '', city: '', state: '', postcode: '',
                                country: 'US', phone: ''
                            };
                            this.showAddShippingForm = false;
                        }

                        alert('Address saved successfully! The page will reload to show your new address.');
                        window.location.reload();
                    } else {
                        this.errorMessage = result.data?.message || 'Error saving address. Please check all required fields.';
                    }
                } catch (error) {
                    console.error('Error saving address:', error);
                    this.errorMessage = 'Network error. Please try again.';
                } finally {
                    this.isLoading = false;
                }
            },
            
            // Setup field watchers
            setupFieldWatchers() {
                const billingFields = document.querySelectorAll('[name^="billing_"]');
                const shippingFields = document.querySelectorAll('[name^="shipping_"]');
                
                [...billingFields, ...shippingFields].forEach(field => {
                    field.addEventListener('input', () => {
                        if (field.name.startsWith('billing_')) {
                            this.selectedBillingIndex = null;
                            this.selectedBillingAddress = null;
                        } else if (field.name.startsWith('shipping_')) {
                            this.selectedShippingIndex = null;
                            this.selectedShippingAddress = null;
                            this.useSameBilling = false;
                        }
                    });
                });
            },
            
            // Trigger WooCommerce checkout update
            triggerCheckoutUpdate() {
                if (typeof jQuery !== 'undefined' && jQuery('body').length) {
                    jQuery('body').trigger('update_checkout');
                }
            }
        }
    }
    </script>
    <?php
}

// Hook into WooCommerce checkout
add_action('woocommerce_before_checkout_billing_form', 'tostishop_amazon_style_address_picker', 5);

/**
 * Remove old Amazon-style address picker - replaced with simpler template approach
 */

/**
 * Enqueue scripts for checkout address picker
 */


/**
 * AJAX handler for saving new checkout address
 */
function tostishop_ajax_save_checkout_address() {
    check_ajax_referer('tostishop_checkout_nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('You must be logged in to save addresses.', 'tostishop')));
    }
    
    global $tostishop_saved_addresses;
    if (!$tostishop_saved_addresses) {
        $tostishop_saved_addresses = new TostiShop_Saved_Addresses();
    }
    
    $address_data = array(
        'address_type' => sanitize_text_field($_POST['address_type']),
        'address_name' => sanitize_text_field($_POST['address_name']),
        'first_name' => sanitize_text_field($_POST['first_name']),
        'last_name' => sanitize_text_field($_POST['last_name']),
        'company' => sanitize_text_field($_POST['company']),
        'address_1' => sanitize_text_field($_POST['address_1']),
        'address_2' => sanitize_text_field($_POST['address_2']),
        'city' => sanitize_text_field($_POST['city']),
        'state' => sanitize_text_field($_POST['state']),
        'postcode' => sanitize_text_field($_POST['postcode']),
        'country' => sanitize_text_field($_POST['country']),
        'phone' => sanitize_text_field($_POST['phone']),
        'email' => sanitize_email($_POST['email']),
        'is_default' => 0 // New addresses are not default by default
    );
    
    $result = $tostishop_saved_addresses->save_address($address_data);
    
    if ($result) {
        wp_send_json_success(array(
            'message' => __('Address saved successfully.', 'tostishop'),
            'address_id' => $result
        ));
    } else {
        wp_send_json_error(array('message' => __('Failed to save address.', 'tostishop')));
    }
}

// Hook the AJAX handler
add_action('wp_ajax_tostishop_save_checkout_address', 'tostishop_ajax_save_checkout_address');

/**
 * Enable shipping calculation based on shipping address (not billing)
 * This ensures shipping costs are calculated correctly based on where items are being shipped
 */
function tostishop_enable_shipping_calculation() {
    // Allow shipping address to be different from billing
    add_filter('woocommerce_cart_needs_shipping_address', '__return_true');
    
    // Ensure shipping calculations use shipping address
    add_filter('woocommerce_package_rates', 'tostishop_ensure_shipping_address_calculation', 10, 2);
    
    // Ensure India is available as shipping country
    add_filter('woocommerce_countries_allowed_countries', 'tostishop_ensure_india_shipping');
    add_filter('woocommerce_countries_shipping_countries', 'tostishop_ensure_india_shipping');
    
    // Force WooCommerce to calculate shipping based on shipping address, not billing
    add_filter('woocommerce_cart_ready_to_calc_shipping', 'tostishop_force_shipping_calculation', 10, 1);
}
add_action('init', 'tostishop_enable_shipping_calculation');

/**
 * Force shipping calculation to use shipping address
 */
function tostishop_force_shipping_calculation($show_shipping_calc) {
    // Override the shipping destination setting to ensure proper calculation
    if (is_admin()) {
        return $show_shipping_calc;
    }
    
    // Set shipping destination to shipping address
    add_filter('pre_option_woocommerce_ship_to_destination', function() {
        return 'shipping';
    });
    
    return $show_shipping_calc;
}

/**
 * Ensure India is available in shipping countries
 */
function tostishop_ensure_india_shipping($countries) {
    // Make sure India (IN) is always available for shipping
    if (!isset($countries['IN'])) {
        $countries['IN'] = __('India', 'woocommerce');
    }
    return $countries;
}

/**
 * Ensure shipping calculations use shipping address
 */
function tostishop_ensure_shipping_address_calculation($rates, $package) {
    // Make sure shipping calculation is based on shipping address
    if (WC()->customer && WC()->customer->get_shipping_country()) {
        // Force WooCommerce to use shipping address for calculations
        $package['destination']['country'] = WC()->customer->get_shipping_country();
        $package['destination']['state'] = WC()->customer->get_shipping_state();
        $package['destination']['postcode'] = WC()->customer->get_shipping_postcode();
        $package['destination']['city'] = WC()->customer->get_shipping_city();
    }
    
    return $rates;
}

/**
 * Only copy billing to shipping when explicitly requested (checkbox checked)
 */
function tostishop_conditional_copy_billing_to_shipping() {
    // Only copy if the "ship to different address" checkbox is NOT checked
    // This respects WooCommerce's built-in shipping address functionality
    if (isset($_POST['ship_to_different_address']) && $_POST['ship_to_different_address'] == '1') {
        // User wants different shipping address - don't copy billing
        return;
    }
    
    if (!is_admin() && is_checkout()) {
        // Copy billing to shipping only when shipping to same address
        $billing_fields = array(
            'first_name', 'last_name', 'company', 'address_1', 'address_2',
            'city', 'state', 'postcode', 'country', 'phone'
        );
        
        foreach ($billing_fields as $field) {
            if (isset($_POST['billing_' . $field])) {
                $_POST['shipping_' . $field] = $_POST['billing_' . $field];
            }
        }
    }
}
add_action('woocommerce_checkout_process', 'tostishop_conditional_copy_billing_to_shipping');

/**
 * Disable WooCommerce block styles properly
 */
function tostishop_disable_wc_block_styles() {
    wp_dequeue_style('wc-blocks-style');
    wp_deregister_style('wc-blocks-style');
}
add_action('wp_enqueue_scripts', 'tostishop_disable_wc_block_styles', 100);

/**
 * WooCommerce customizations for TostiShop
 */
function tostishop_woocommerce_customizations() {
    // Remove default WooCommerce styling
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    
    // Remove WooCommerce generator tag
    remove_action('wp_head', array('WC', 'generator'));
    
    // Modify breadcrumbs
    add_filter('woocommerce_breadcrumb_defaults', 'tostishop_breadcrumbs');
    
    // Custom add to cart functionality
    add_action('wp_ajax_woocommerce_add_to_cart', 'tostishop_ajax_add_to_cart');
    add_action('wp_ajax_nopriv_woocommerce_add_to_cart', 'tostishop_ajax_add_to_cart');
}
add_action('after_setup_theme', 'tostishop_woocommerce_customizations');

/**
 * Product link opening tag
 */
function tostishop_product_link_open() {
    global $product;
    
    $link = apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product);
    
    echo '<a href="' . esc_url($link) . '" class="tostishop-product-link">';
}

/**
 * Product link closing tag
 */
function tostishop_product_link_close() {
    echo '</a>';
}

/**
 * Custom product title
 */
function tostishop_product_title() {
    echo '<h3 class="tostishop-product-title">' . get_the_title() . '</h3>';
}

/**
 * Remove default add to cart buttons from shop page
 */
function tostishop_remove_add_to_cart_buttons() {
    if (is_shop() || is_product_category() || is_product_tag()) {
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    }
}
add_action('init', 'tostishop_remove_add_to_cart_buttons');

/**
 * Custom breadcrumbs
 */
function tostishop_breadcrumbs($args) {
    $args['delimiter'] = ' <span class="breadcrumb-separator">/</span> ';
    $args['wrap_before'] = '<nav class="tostishop-breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
    $args['wrap_after'] = '</nav>';
    $args['before'] = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
    $args['after'] = '</span>';
    $args['home'] = _x('Home', 'breadcrumb', 'tostishop');
    
    return $args;
}

/**
 * Get cart count
 */
function tostishop_cart_count() {
    if (WC()->cart) {
        return WC()->cart->get_cart_contents_count();
    }
    return 0;
}

/**
 * Get cart total
 */
function tostishop_cart_total() {
    if (WC()->cart) {
        return WC()->cart->get_cart_total();
    }
    return wc_price(0);
}

/**
 * Custom order confirmation email text
 */
function tostishop_order_confirmation_email_text($text, $order) {
    if ($order && is_a($order, 'WC_Order')) {
        $custom_text = sprintf(
            __('Thank you for your order #%s! We\'ll send you tracking information once your order ships.', 'tostishop'),
            $order->get_order_number()
        );
        return $custom_text;
    }
    return $text;
}
add_filter('woocommerce_thankyou_order_received_text', 'tostishop_order_confirmation_email_text', 10, 2);

/**
 * Add order tracking information
 */
function tostishop_add_order_tracking_info($order_id) {
    if (!$order_id) return;
    
    $order = wc_get_order($order_id);
    if (!$order) return;
    
    // Add custom tracking info section
    echo '<div class="tostishop-tracking-info">';
    echo '<h3>' . __('Track Your Order', 'tostishop') . '</h3>';
    echo '<p>' . __('You will receive an email with tracking information once your order ships.', 'tostishop') . '</p>';
    
    // Add estimated delivery date
    $estimated_delivery = date('F j, Y', strtotime('+5 business days'));
    echo '<p><strong>' . __('Estimated Delivery:', 'tostishop') . '</strong> ' . $estimated_delivery . '</p>';
    echo '</div>';
}
add_action('woocommerce_thankyou', 'tostishop_add_order_tracking_info', 20);

/**
 * Custom pagination for WooCommerce
 */
function tostishop_woocommerce_pagination_args($args) {
    $args['prev_text'] = __('← Previous', 'tostishop');
    $args['next_text'] = __('Next →', 'tostishop');
    $args['type'] = 'list';
    
    return $args;
}
add_filter('woocommerce_pagination_args', 'tostishop_woocommerce_pagination_args');

/**
 * Order confirmation page enhancements
 */
function tostishop_order_confirmation_enhancements() {
    if (!is_wc_endpoint_url('order-received')) {
        return;
    }
    
    // Add custom CSS for order confirmation
    wp_add_inline_style('tostishop-style', '
        .tostishop-tracking-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #e42029;
        }
        .tostishop-tracking-info h3 {
            margin-top: 0;
            color: #14175b;
        }
    ');
}
add_action('wp_footer', 'tostishop_order_confirmation_enhancements');

/**
 * Add structured data for order confirmation
 */
function tostishop_order_confirmation_structured_data() {
    if (is_wc_endpoint_url('order-received') && isset($_GET['key'])) {
        $order_id = wc_get_order_id_by_order_key($_GET['key']);
        $order = wc_get_order($order_id);
        
        if ($order && $order->get_status() !== 'failed') {
            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'Order',
                'orderNumber' => $order->get_order_number(),
                'orderStatus' => 'https://schema.org/OrderProcessing',
                'orderDate' => $order->get_date_created()->format('c'),
                'customer' => array(
                    '@type' => 'Person',
                    'name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                    'email' => $order->get_billing_email()
                ),
                'merchant' => array(
                    '@type' => 'Organization',
                    'name' => get_bloginfo('name'),
                    'url' => home_url()
                )
            );
            
            echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
        }
    }
}
add_action('wp_footer', 'tostishop_order_confirmation_structured_data');
