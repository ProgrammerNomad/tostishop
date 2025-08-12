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
 * Injects address picker UI before billing and shipping forms
 * Uses existing TostiShop_Saved_Addresses database system
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
    <div id="tostishop-amazon-address-picker" class="mb-6 p-4 bg-white border border-gray-200 rounded-lg shadow-sm" x-data="amazonAddressPicker()">
        
        <!-- Billing Address Section -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center mb-4">
                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <?php _e('Billing Address', 'tostishop'); ?>
            </h3>
            
            <?php if (!empty($billing_addresses)) : ?>
                <!-- Address Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <?php foreach ($billing_addresses as $index => $address) : ?>
                        <div class="address-card border-2 border-gray-200 rounded-lg p-4 cursor-pointer transition-all duration-200 hover:border-red-300 hover:shadow-md"
                             :class="selectedBilling === '<?php echo $index; ?>' ? 'border-red-500 bg-red-50 ring-2 ring-red-200' : ''"
                             @click="selectBillingAddress('<?php echo $index; ?>')">
                            
                            <!-- Radio Button -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 mb-1 flex items-center">
                                        <?php echo esc_html($address->address_name ?? 'Address'); ?>
                                        <?php if (!empty($address->is_default)) : ?>
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <?php _e('Default', 'tostishop'); ?>
                                            </span>
                                        <?php endif; ?>
                                    </h4>
                                </div>
                                <div class="w-5 h-5 border-2 rounded-full flex items-center justify-center transition-all duration-200"
                                     :class="selectedBilling === '<?php echo $index; ?>' ? 'border-red-500 bg-red-500' : 'border-gray-300'">
                                    <div x-show="selectedBilling === '<?php echo $index; ?>'" class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                            </div>
                            
                            <!-- Address Details -->
                            <div class="text-sm text-gray-600 space-y-1">
                                <p class="font-medium text-gray-800"><?php echo esc_html($address->first_name . ' ' . $address->last_name); ?></p>
                                <p><?php echo esc_html($address->address_1); ?></p>
                                <?php if (!empty($address->address_2)) : ?>
                                    <p><?php echo esc_html($address->address_2); ?></p>
                                <?php endif; ?>
                                <p><?php echo esc_html($address->city . ', ' . $address->state . ' ' . $address->postcode); ?></p>
                                <p class="font-medium"><?php echo esc_html($address->country); ?></p>
                                <?php if (!empty($address->phone)) : ?>
                                    <p class="flex items-center mt-2">
                                        <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        <?php echo esc_html($address->phone); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Add New Address Card -->
                    <div class="address-card border-2 border-dashed border-gray-300 rounded-lg p-4 cursor-pointer transition-all duration-200 hover:border-red-400 hover:bg-red-50 flex items-center justify-center"
                         @click="showNewBillingForm = !showNewBillingForm">
                        <div class="text-center">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-600">
                                <?php _e('Add New Address', 'tostishop'); ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- New Address Form (Hidden by default) -->
                <div x-show="showNewBillingForm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="border border-gray-200 rounded-lg p-4 bg-gray-50" style="display: none;">
                    <h4 class="text-md font-semibold text-gray-900 mb-4"><?php _e('Add New Billing Address', 'tostishop'); ?></h4>
                    
                    <div id="new-billing-fields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Label Field -->
                        <div class="md:col-span-2">
                            <label for="new_billing_label" class="block text-sm font-medium text-gray-700 mb-1">
                                <?php _e('Address Label', 'tostishop'); ?>
                            </label>
                            <input type="text" id="new_billing_label" name="new_billing_label" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                   placeholder="<?php _e('Home, Office, etc.', 'tostishop'); ?>">
                        </div>
                        
                        <!-- WooCommerce fields will be injected here by JavaScript -->
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button @click="showNewBillingForm = false" type="button" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            <?php _e('Cancel', 'tostishop'); ?>
                        </button>
                        <button @click="saveNewAddress('billing')" type="button" 
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700">
                            <?php _e('Save Address', 'tostishop'); ?>
                        </button>
                    </div>
                </div>
            <?php else : ?>
                <!-- No saved addresses -->
                <div class="text-center py-12 text-gray-500 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                    <div class="max-w-sm mx-auto">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2"><?php _e('No Saved Addresses', 'tostishop'); ?></h4>
                        <p class="text-sm text-gray-600 mb-6">
                            <?php _e('Save your address for faster checkout next time. You can add multiple addresses and set a default.', 'tostishop'); ?>
                        </p>
                        <button @click="showNewBillingForm = true" type="button" 
                                class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <?php _e('Add Your First Billing Address', 'tostishop'); ?>
                        </button>
                    </div>
                </div>
                
                <!-- New Address Form for first address -->
                <div x-show="showNewBillingForm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="border border-gray-200 rounded-lg p-4 bg-gray-50 mt-4" style="display: none;">
                    <h4 class="text-md font-semibold text-gray-900 mb-4"><?php _e('Add Your First Billing Address', 'tostishop'); ?></h4>
                    
                    <div id="new-billing-fields-first" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Label Field -->
                        <div class="md:col-span-2">
                            <label for="new_billing_label_first" class="block text-sm font-medium text-gray-700 mb-1">
                                <?php _e('Address Label', 'tostishop'); ?>
                            </label>
                            <input type="text" id="new_billing_label_first" name="new_billing_label_first" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                   placeholder="<?php _e('Home, Office, etc.', 'tostishop'); ?>">
                        </div>
                        
                        <!-- WooCommerce fields will be injected here by JavaScript -->
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button @click="showNewBillingForm = false" type="button" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            <?php _e('Cancel', 'tostishop'); ?>
                        </button>
                        <button @click="saveNewAddress('billing')" type="button" 
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700">
                            <?php _e('Save Address', 'tostishop'); ?>
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Shipping Address Section -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center mb-4">
                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <?php _e('Shipping Address', 'tostishop'); ?>
            </h3>
            
            <?php if (!empty($shipping_addresses)) : ?>
                <!-- Address Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <!-- Same as billing checkbox -->
                    <div class="address-card border-2 border-gray-200 rounded-lg p-4 cursor-pointer transition-all duration-200 hover:border-green-300 hover:shadow-md"
                         :class="useSameBilling ? 'border-green-500 bg-green-50 ring-2 ring-green-200' : ''"
                         @click="useSameBilling = true; selectedShipping = null;">
                        
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 mb-1"><?php _e('Same as billing address', 'tostishop'); ?></h4>
                            </div>
                            <div class="w-5 h-5 border-2 rounded-full flex items-center justify-center transition-all duration-200"
                                 :class="useSameBilling ? 'border-green-500 bg-green-500' : 'border-gray-300'">
                                <div x-show="useSameBilling" class="w-2 h-2 bg-white rounded-full"></div>
                            </div>
                        </div>
                        
                        <div class="text-sm text-gray-600">
                            <p><?php _e('Use the same address for billing and shipping', 'tostishop'); ?></p>
                        </div>
                    </div>
                    
                    <?php foreach ($shipping_addresses as $index => $address) : ?>
                        <div class="address-card border-2 border-gray-200 rounded-lg p-4 cursor-pointer transition-all duration-200 hover:border-green-300 hover:shadow-md"
                             :class="selectedShipping === '<?php echo $index; ?>' ? 'border-green-500 bg-green-50 ring-2 ring-green-200' : ''"
                             @click="selectShippingAddress('<?php echo $index; ?>')">
                            
                            <!-- Radio Button -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 mb-1 flex items-center">
                                        <?php echo esc_html($address->address_name ?? 'Address'); ?>
                                        <?php if (!empty($address->is_default)) : ?>
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <?php _e('Default', 'tostishop'); ?>
                                            </span>
                                        <?php endif; ?>
                                    </h4>
                                </div>
                                <div class="w-5 h-5 border-2 rounded-full flex items-center justify-center transition-all duration-200"
                                     :class="selectedShipping === '<?php echo $index; ?>' ? 'border-green-500 bg-green-500' : 'border-gray-300'">
                                    <div x-show="selectedShipping === '<?php echo $index; ?>'" class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                            </div>
                            
                            <!-- Address Details -->
                            <div class="text-sm text-gray-600 space-y-1">
                                <p class="font-medium text-gray-800"><?php echo esc_html($address->first_name . ' ' . $address->last_name); ?></p>
                                <p><?php echo esc_html($address->address_1); ?></p>
                                <?php if (!empty($address->address_2)) : ?>
                                    <p><?php echo esc_html($address->address_2); ?></p>
                                <?php endif; ?>
                                <p><?php echo esc_html($address->city . ', ' . $address->state . ' ' . $address->postcode); ?></p>
                                <p class="font-medium"><?php echo esc_html($address->country); ?></p>
                                <?php if (!empty($address->phone)) : ?>
                                    <p class="flex items-center mt-2">
                                        <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        <?php echo esc_html($address->phone); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Add New Shipping Address Card -->
                    <div class="address-card border-2 border-dashed border-gray-300 rounded-lg p-4 cursor-pointer transition-all duration-200 hover:border-green-400 hover:bg-green-50 flex items-center justify-center"
                         @click="showNewShippingForm = !showNewShippingForm">
                        <div class="text-center">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-600">
                                <?php _e('Add New Address', 'tostishop'); ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- New Shipping Address Form -->
                <div x-show="showNewShippingForm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="border border-gray-200 rounded-lg p-4 bg-gray-50" style="display: none;">
                    <h4 class="text-md font-semibold text-gray-900 mb-4"><?php _e('Add New Shipping Address', 'tostishop'); ?></h4>
                    
                    <div id="new-shipping-fields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Label Field -->
                        <div class="md:col-span-2">
                            <label for="new_shipping_label" class="block text-sm font-medium text-gray-700 mb-1">
                                <?php _e('Address Label', 'tostishop'); ?>
                            </label>
                            <input type="text" id="new_shipping_label" name="new_shipping_label" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                   placeholder="<?php _e('Home, Office, etc.', 'tostishop'); ?>">
                        </div>
                        
                        <!-- WooCommerce fields will be injected here by JavaScript -->
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button @click="showNewShippingForm = false" type="button" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            <?php _e('Cancel', 'tostishop'); ?>
                        </button>
                        <button @click="saveNewAddress('shipping')" type="button" 
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700">
                            <?php _e('Save Address', 'tostishop'); ?>
                        </button>
                    </div>
                </div>
            <?php else : ?>
                <!-- No saved shipping addresses -->
                <div class="text-center py-8 text-gray-500 border border-gray-200 rounded-lg">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="mb-4"><?php _e('No saved shipping addresses found.', 'tostishop'); ?></p>
                    <button @click="showNewShippingForm = true; useSameBilling = false;" type="button" 
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <?php _e('Add Shipping Address', 'tostishop'); ?>
                    </button>
                </div>
                
                <!-- New Shipping Address Form -->
                <div x-show="showNewShippingForm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="border border-gray-200 rounded-lg p-4 bg-gray-50 mt-4" style="display: none;">
                    <h4 class="text-md font-semibold text-gray-900 mb-4"><?php _e('Add Your First Shipping Address', 'tostishop'); ?></h4>
                    
                    <div id="new-shipping-fields-first" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Label Field -->
                        <div class="md:col-span-2">
                            <label for="new_shipping_label_first" class="block text-sm font-medium text-gray-700 mb-1">
                                <?php _e('Address Label', 'tostishop'); ?>
                            </label>
                            <input type="text" id="new_shipping_label_first" name="new_shipping_label_first" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                   placeholder="<?php _e('Home, Office, etc.', 'tostishop'); ?>">
                        </div>
                        
                        <!-- WooCommerce fields will be injected here by JavaScript -->
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button @click="showNewShippingForm = false" type="button" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            <?php _e('Cancel', 'tostishop'); ?>
                        </button>
                        <button @click="saveNewAddress('shipping')" type="button" 
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700">
                            <?php _e('Save Address', 'tostishop'); ?>
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    // Hide original WooCommerce address fields
    document.addEventListener('DOMContentLoaded', function() {
        const billingWrapper = document.querySelector('.woocommerce-billing-fields__field-wrapper');
        const shippingWrapper = document.querySelector('.woocommerce-shipping-fields__field-wrapper');
        
        if (billingWrapper) {
            billingWrapper.style.display = 'none';
        }
        if (shippingWrapper) {
            shippingWrapper.style.display = 'none';
        }
    });

    // Alpine.js Component
    function amazonAddressPicker() {
        return {
            selectedBilling: <?php echo json_encode($default_billing ? 0 : null); ?>,
            selectedShipping: <?php echo json_encode($default_shipping ? 0 : null); ?>,
            useSameBilling: <?php echo json_encode(!$default_shipping); ?>,
            showNewBillingForm: false,
            showNewShippingForm: false,
            billingAddresses: <?php echo json_encode(array_map(function($addr) {
                return array(
                    'id' => $addr->id,
                    'address_name' => $addr->address_name,
                    'first_name' => $addr->first_name,
                    'last_name' => $addr->last_name,
                    'company' => $addr->company,
                    'address_1' => $addr->address_1,
                    'address_2' => $addr->address_2,
                    'city' => $addr->city,
                    'state' => $addr->state,
                    'postcode' => $addr->postcode,
                    'country' => $addr->country,
                    'phone' => $addr->phone,
                    'email' => $addr->email,
                    'is_default' => $addr->is_default
                );
            }, $billing_addresses)); ?>,
            shippingAddresses: <?php echo json_encode(array_map(function($addr) {
                return array(
                    'id' => $addr->id,
                    'address_name' => $addr->address_name,
                    'first_name' => $addr->first_name,
                    'last_name' => $addr->last_name,
                    'company' => $addr->company,
                    'address_1' => $addr->address_1,
                    'address_2' => $addr->address_2,
                    'city' => $addr->city,
                    'state' => $addr->state,
                    'postcode' => $addr->postcode,
                    'country' => $addr->country,
                    'phone' => $addr->phone,
                    'is_default' => $addr->is_default
                );
            }, $shipping_addresses)); ?>,
            
            init() {
                // Auto-fill default addresses on load
                if (this.selectedBilling !== null) {
                    this.fillBillingFields(this.billingAddresses[this.selectedBilling]);
                }
                
                if (this.selectedShipping !== null) {
                    this.fillShippingFields(this.shippingAddresses[this.selectedShipping]);
                } else if (this.useSameBilling && this.selectedBilling !== null) {
                    this.copyBillingToShipping();
                }
                
                // Move WooCommerce fields to new address forms
                this.setupNewAddressForms();
            },
            
            selectBillingAddress(index) {
                this.selectedBilling = index;
                this.fillBillingFields(this.billingAddresses[index]);
                
                if (this.useSameBilling) {
                    this.copyBillingToShipping();
                }
            },
            
            selectShippingAddress(index) {
                this.selectedShipping = index;
                this.useSameBilling = false;
                this.fillShippingFields(this.shippingAddresses[index]);
            },
            
            fillBillingFields(address) {
                this.fillAddressFields(address, 'billing');
            },
            
            fillShippingFields(address) {
                this.fillAddressFields(address, 'shipping');
            },
            
            fillAddressFields(address, type) {
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
            
            copyBillingToShipping() {
                if (this.selectedBilling !== null) {
                    const billingAddress = this.billingAddresses[this.selectedBilling];
                    this.fillShippingFields(billingAddress);
                }
            },
            
            setupNewAddressForms() {
                // Move WooCommerce billing fields to new address form
                const billingWrapper = document.querySelector('.woocommerce-billing-fields__field-wrapper');
                const shippingWrapper = document.querySelector('.woocommerce-shipping-fields__field-wrapper');
                
                if (billingWrapper) {
                    const newBillingContainer = document.getElementById('new-billing-fields');
                    const newBillingContainerFirst = document.getElementById('new-billing-fields-first');
                    
                    if (newBillingContainer) {
                        newBillingContainer.appendChild(billingWrapper.cloneNode(true));
                    }
                    if (newBillingContainerFirst) {
                        newBillingContainerFirst.appendChild(billingWrapper.cloneNode(true));
                    }
                }
                
                if (shippingWrapper) {
                    const newShippingContainer = document.getElementById('new-shipping-fields');
                    const newShippingContainerFirst = document.getElementById('new-shipping-fields-first');
                    
                    if (newShippingContainer) {
                        newShippingContainer.appendChild(shippingWrapper.cloneNode(true));
                    }
                    if (newShippingContainerFirst) {
                        newShippingContainerFirst.appendChild(shippingWrapper.cloneNode(true));
                    }
                }
            },
            
            async saveNewAddress(type) {
                const labelField = document.getElementById(`new_${type}_label`) || 
                                 document.getElementById(`new_${type}_label_first`);
                
                if (!labelField || !labelField.value.trim()) {
                    alert('<?php _e('Please enter an address label.', 'tostishop'); ?>');
                    return;
                }
                
                // Show loading state
                const saveButton = document.querySelector(`button[onclick*="saveNewAddress('${type}')"]`);
                const originalText = saveButton ? saveButton.textContent : '';
                if (saveButton) {
                    saveButton.disabled = true;
                    saveButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Saving...';
                }
                
                // Collect address data from form
                const formData = new FormData();
                formData.append('action', 'tostishop_save_new_address');
                formData.append('nonce', '<?php echo wp_create_nonce('tostishop_address_nonce'); ?>');
                formData.append('type', type);
                formData.append('label', labelField.value.trim());
                
                const fields = [
                    'first_name', 'last_name', 'company', 'address_1', 'address_2',
                    'city', 'state', 'postcode', 'country', 'phone'
                ];
                
                if (type === 'billing') {
                    fields.push('email');
                }
                
                // Collect field values from the appropriate container
                const container = document.getElementById(`new-${type}-fields`) || 
                                document.getElementById(`new-${type}-fields-first`);
                
                fields.forEach(field => {
                    // Try multiple ways to find the field
                    let input = document.getElementById(type + '_' + field) || 
                              document.querySelector(`[name="${type}_${field}"]`) ||
                              (container && container.querySelector(`[name="${type}_${field}"]`)) ||
                              (container && container.querySelector(`#${type}_${field}`));
                    
                    if (input && input.value) {
                        formData.append(field, input.value);
                        console.log(`Found ${field}:`, input.value); // Debug log
                    } else {
                        console.log(`Missing field: ${field}`); // Debug log
                    }
                });
                
                try {
                    console.log('Sending AJAX request...'); // Debug log
                    const response = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    console.log('AJAX response:', result); // Debug log
                    
                    if (result.success) {
                        // Show success message
                        alert('Address saved successfully! The page will reload to show your new address.');
                        // Reload the page to show the new address
                        window.location.reload();
                    } else {
                        alert(result.data.message || '<?php _e('Error saving address. Please check all required fields.', 'tostishop'); ?>');
                    }
                } catch (error) {
                    console.error('Error saving address:', error);
                    alert('<?php _e('Network error. Please try again.', 'tostishop'); ?>');
                } finally {
                    // Restore button state
                    if (saveButton) {
                        saveButton.disabled = false;
                        saveButton.innerHTML = originalText;
                    }
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
function tostishop_enqueue_checkout_address_scripts() {
    if (is_checkout()) {
        wp_enqueue_script('alpine-js', get_template_directory_uri() . '/assets/js/alpine.min.js', array(), '3.13.0', true);
    }
}

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
