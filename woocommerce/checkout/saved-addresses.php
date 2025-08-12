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

<div class="mb-4 p-3 bg-white border border-gray-200 rounded-lg shadow-sm" x-data="checkoutAddresses()">
    
    <!-- Billing Addresses -->
    <?php if (!empty($billing_addresses)) : ?>
        <div class="mb-4">
            <div class="flex items-center mb-3">
                <svg class="w-4 h-4 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <h3 class="text-sm font-semibold text-gray-900">
                    <?php _e('Billing Address', 'tostishop'); ?>
                </h3>
            </div>
            
            <!-- Ultra Compact Address Selection -->
            <div class="space-y-2">
                <?php foreach ($billing_addresses as $index => $address) : ?>
                    <div class="border border-gray-200 rounded-md p-2 cursor-pointer hover:border-red-300 hover:bg-red-50 transition-all duration-200"
                         :class="selectedBilling === <?php echo $address->id; ?> ? 'border-red-500 bg-red-50 ring-1 ring-red-100' : ''"
                         @click="selectBillingAddress(<?php echo esc_attr(json_encode($address)); ?>)">
                        
                        <div class="flex items-center justify-between">
                            <!-- Address Info (Ultra Compact) -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2">
                                    <h4 class="font-medium text-gray-900 text-sm">
                                        <?php echo esc_html($address->address_name); ?>
                                    </h4>
                                    <?php if ($address->is_default) : ?>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">
                                            <?php _e('Default', 'tostishop'); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Ultra Compact Address Details - Single Line -->
                                <div class="text-xs text-gray-600 truncate">
                                    <?php echo esc_html($address->first_name . ' ' . $address->last_name . ' • ' . $address->address_1 . ' • ' . $address->city . ', ' . $address->state . ' ' . $address->postcode); ?>
                                </div>
                            </div>
                            
                            <!-- Radio Button -->
                            <div class="flex-shrink-0 ml-2">
                                <div class="w-4 h-4 border-2 rounded-full flex items-center justify-center transition-all duration-200"
                                     :class="selectedBilling === <?php echo $address->id; ?> ? 'border-red-500 bg-red-500' : 'border-gray-300'">
                                    <div x-show="selectedBilling === <?php echo $address->id; ?>" class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <!-- Add New Address (Ultra Compact) -->
                <div class="border-2 border-dashed border-gray-300 rounded-md p-2 cursor-pointer hover:border-red-400 hover:bg-red-50 transition-all duration-200"
                     @click="showNewBillingForm = true">
                    <div class="flex items-center justify-center text-center">
                        <svg class="w-3 h-3 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-600">
                            <?php _e('Add New', 'tostishop'); ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Use Different Address Toggle (Ultra Compact) -->
            <div class="mt-3 p-2 bg-gray-50 rounded-md">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" x-model="useNewBilling" @change="toggleNewBillingForm()"
                           class="w-3 h-3 text-red-600 border-gray-300 rounded focus:ring-red-500 focus:ring-1">
                    <span class="ml-2 text-xs text-gray-700">
                        <?php _e('Use different address', 'tostishop'); ?>
                    </span>
                </label>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Shipping Addresses (Ultra Compact) -->
    <?php if (!empty($shipping_addresses) && !apply_filters('woocommerce_cart_needs_shipping_address', false)) : ?>
        <div>
            <div class="flex items-center mb-3">
                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 class="text-sm font-semibold text-gray-900">
                    <?php _e('Shipping Address', 'tostishop'); ?>
                </h3>
            </div>
            
            <div class="space-y-2">
                <?php foreach ($shipping_addresses as $address) : ?>
                    <div class="border border-gray-200 rounded-md p-2 cursor-pointer hover:border-green-300 hover:bg-green-50 transition-all duration-200"
                         :class="selectedShipping === <?php echo $address->id; ?> ? 'border-green-500 bg-green-50 ring-1 ring-green-100' : ''"
                         @click="selectShippingAddress(<?php echo esc_attr(json_encode($address)); ?>)">
                        
                        <div class="flex items-center justify-between">
                            <!-- Address Info (Ultra Compact) -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2">
                                    <h4 class="font-medium text-gray-900 text-sm">
                                        <?php echo esc_html($address->address_name); ?>
                                    </h4>
                                    <?php if ($address->is_default) : ?>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">
                                            <?php _e('Default', 'tostishop'); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Ultra Compact Address Details - Single Line -->
                                <div class="text-xs text-gray-600 truncate">
                                    <?php echo esc_html($address->first_name . ' ' . $address->last_name . ' • ' . $address->address_1 . ' • ' . $address->city . ', ' . $address->state . ' ' . $address->postcode); ?>
                                </div>
                            </div>
                            
                            <!-- Radio Button -->
                            <div class="flex-shrink-0 ml-2">
                                <div class="w-4 h-4 border-2 rounded-full flex items-center justify-center transition-all duration-200"
                                     :class="selectedShipping === <?php echo $address->id; ?> ? 'border-green-500 bg-green-500' : 'border-gray-300'">
                                    <div x-show="selectedShipping === <?php echo $address->id; ?>" class="w-1.5 h-1.5 bg-white rounded-full"></div>
                                </div>
                            </div>
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
