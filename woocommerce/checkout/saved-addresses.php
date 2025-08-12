<?php
/**
 * Checkout Saved Addresses
 * Display saved addresses during checkout for quick selection
 *
 * @package TostiShop
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="mb-8 p-6 bg-white border border-gray-200 rounded-xl shadow-sm" x-data="checkoutAddresses()">
    
    <!-- Billing Addresses -->
    <?php if (!empty($billing_addresses)) : ?>
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">
                    <?php _e('Choose Billing Address', 'tostishop'); ?>
                </h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <?php foreach ($billing_addresses as $address) : ?>
                    <div class="group relative border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-red-300 hover:shadow-md transition-all duration-200"
                         :class="selectedBilling === <?php echo $address->id; ?> ? 'border-red-500 bg-red-50 shadow-md' : 'hover:bg-gray-50'"
                         @click="selectBillingAddress(<?php echo esc_attr(json_encode($address)); ?>)">
                        
                        <div class="flex justify-between items-start mb-3">
                            <!-- Address Name & Type -->
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 mb-1">
                                    <?php echo esc_html($address->address_name); ?>
                                </h4>
                                <?php if ($address->is_default) : ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <?php _e('Default', 'tostishop'); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Radio Button -->
                            <div class="flex-shrink-0 ml-3">
                                <div class="w-5 h-5 border-2 rounded-full flex items-center justify-center transition-all duration-200"
                                     :class="selectedBilling === <?php echo $address->id; ?> ? 'border-red-500 bg-red-500' : 'border-gray-300 group-hover:border-red-400'">
                                    <div x-show="selectedBilling === <?php echo $address->id; ?>" class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Address Details -->
                        <div class="text-sm text-gray-600 space-y-1 leading-relaxed">
                            <p class="font-medium text-gray-800"><?php echo esc_html($address->first_name . ' ' . $address->last_name); ?></p>
                            <?php if ($address->company) : ?>
                                <p class="text-gray-500"><?php echo esc_html($address->company); ?></p>
                            <?php endif; ?>
                            <p><?php echo esc_html($address->address_1); ?></p>
                            <?php if ($address->address_2) : ?>
                                <p><?php echo esc_html($address->address_2); ?></p>
                            <?php endif; ?>
                            <p><?php echo esc_html($address->city . ', ' . $address->state . ' ' . $address->postcode); ?></p>
                            <p class="font-medium"><?php echo esc_html(WC()->countries->countries[$address->country] ?? $address->country); ?></p>
                        </div>
                        
                        <!-- Selected Indicator -->
                        <div x-show="selectedBilling === <?php echo $address->id; ?>" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <!-- Add New Address Option -->
                <div class="group border-2 border-dashed border-gray-300 rounded-lg p-4 cursor-pointer hover:border-red-400 hover:bg-red-50 transition-all duration-200 flex flex-col items-center justify-center text-center min-h-[140px]"
                     @click="showNewBillingForm = true">
                    <svg class="w-8 h-8 text-gray-400 group-hover:text-red-500 mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-600 group-hover:text-red-600 transition-colors">
                        <?php _e('Add New Address', 'tostishop'); ?>
                    </span>
                </div>
            </div>
            
            <!-- Use Different Address Toggle -->
            <div class="p-4 bg-gray-50 rounded-lg">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" x-model="useNewBilling" @change="toggleNewBillingForm()"
                           class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 focus:ring-2">
                    <span class="ml-3 text-sm font-medium text-gray-700">
                        <?php _e('Use a different billing address', 'tostishop'); ?>
                    </span>
                </label>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Shipping Addresses (if different from billing) -->
    <?php if (!empty($shipping_addresses) && !apply_filters('woocommerce_cart_needs_shipping_address', false)) : ?>
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <?php _e('Choose Shipping Address', 'tostishop'); ?>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                <?php foreach ($shipping_addresses as $address) : ?>
                    <div class="address-option border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-red-500 transition-colors"
                         :class="selectedShipping === <?php echo $address->id; ?> ? 'border-red-500 bg-red-50' : ''"
                         @click="selectShippingAddress(<?php echo esc_attr(json_encode($address)); ?>)">
                        
                        <!-- Default Badge -->
                        <?php if ($address->is_default) : ?>
                            <div class="flex justify-between items-start mb-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <?php _e('Default', 'tostishop'); ?>
                                </span>
                                <div class="w-4 h-4 border-2 border-red-500 rounded-full flex items-center justify-center"
                                     :class="selectedShipping === <?php echo $address->id; ?> ? 'bg-red-500' : ''">
                                    <div x-show="selectedShipping === <?php echo $address->id; ?>" class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="flex justify-end mb-2">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center"
                                     :class="selectedShipping === <?php echo $address->id; ?> ? 'border-red-500 bg-red-500' : ''">
                                    <div x-show="selectedShipping === <?php echo $address->id; ?>" class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Address Name -->
                        <h4 class="font-medium text-gray-900 mb-2">
                            <?php echo esc_html($address->address_name); ?>
                        </h4>
                        
                        <!-- Address Details -->
                        <div class="text-sm text-gray-600 space-y-1">
                            <p class="font-medium"><?php echo esc_html($address->first_name . ' ' . $address->last_name); ?></p>
                            <?php if ($address->company) : ?>
                                <p><?php echo esc_html($address->company); ?></p>
                            <?php endif; ?>
                            <p><?php echo esc_html($address->address_1); ?></p>
                            <?php if ($address->address_2) : ?>
                                <p><?php echo esc_html($address->address_2); ?></p>
                            <?php endif; ?>
                            <p><?php echo esc_html($address->city . ', ' . $address->state . ' ' . $address->postcode); ?></p>
                            <p><?php echo esc_html(WC()->countries->countries[$address->country] ?? $address->country); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function checkoutAddresses() {
    return {
        selectedBilling: <?php echo !empty($billing_addresses) && $billing_addresses[0]->is_default ? $billing_addresses[0]->id : 'null'; ?>,
        selectedShipping: <?php echo !empty($shipping_addresses) && $shipping_addresses[0]->is_default ? $shipping_addresses[0]->id : 'null'; ?>,
        useNewBilling: false,
        useNewShipping: false,
        showNewBillingForm: false,
        showNewShippingForm: false,
        
        init() {
            // Auto-select default addresses
            <?php if (!empty($billing_addresses)) : ?>
                <?php foreach ($billing_addresses as $address) : ?>
                    <?php if ($address->is_default) : ?>
                        this.selectBillingAddress(<?php echo json_encode($address); ?>);
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if (!empty($shipping_addresses)) : ?>
                <?php foreach ($shipping_addresses as $address) : ?>
                    <?php if ($address->is_default) : ?>
                        this.selectShippingAddress(<?php echo json_encode($address); ?>);
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        },
        
        selectBillingAddress(address) {
            this.selectedBilling = address.id;
            this.useNewBilling = false;
            
            // Fill form fields
            this.fillBillingFields(address);
        },
        
        selectShippingAddress(address) {
            this.selectedShipping = address.id;
            this.useNewShipping = false;
            
            // Fill form fields
            this.fillShippingFields(address);
        },
        
        fillBillingFields(address) {
            const fields = {
                'billing_first_name': address.first_name,
                'billing_last_name': address.last_name,
                'billing_company': address.company,
                'billing_address_1': address.address_1,
                'billing_address_2': address.address_2,
                'billing_city': address.city,
                'billing_state': address.state,
                'billing_postcode': address.postcode,
                'billing_country': address.country,
                'billing_phone': address.phone,
                'billing_email': address.email
            };
            
            Object.keys(fields).forEach(fieldName => {
                const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.value = fields[fieldName] || '';
                    // Trigger change event
                    field.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        },
        
        fillShippingFields(address) {
            const fields = {
                'shipping_first_name': address.first_name,
                'shipping_last_name': address.last_name,
                'shipping_company': address.company,
                'shipping_address_1': address.address_1,
                'shipping_address_2': address.address_2,
                'shipping_city': address.city,
                'shipping_state': address.state,
                'shipping_postcode': address.postcode,
                'shipping_country': address.country
            };
            
            Object.keys(fields).forEach(fieldName => {
                const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.value = fields[fieldName] || '';
                    // Trigger change event
                    field.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        },
        
        toggleNewBillingForm() {
            if (this.useNewBilling) {
                this.selectedBilling = null;
                this.clearBillingFields();
            }
        },
        
        toggleNewShippingForm() {
            if (this.useNewShipping) {
                this.selectedShipping = null;
                this.clearShippingFields();
            }
        },
        
        clearBillingFields() {
            const fieldNames = [
                'billing_first_name', 'billing_last_name', 'billing_company',
                'billing_address_1', 'billing_address_2', 'billing_city',
                'billing_state', 'billing_postcode', 'billing_country',
                'billing_phone', 'billing_email'
            ];
            
            fieldNames.forEach(fieldName => {
                const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.value = '';
                    field.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        },
        
        clearShippingFields() {
            const fieldNames = [
                'shipping_first_name', 'shipping_last_name', 'shipping_company',
                'shipping_address_1', 'shipping_address_2', 'shipping_city',
                'shipping_state', 'shipping_postcode', 'shipping_country'
            ];
            
            fieldNames.forEach(fieldName => {
                const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.value = '';
                    field.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }
    }
}
</script>
