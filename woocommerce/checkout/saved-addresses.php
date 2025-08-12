<?php
/**
 * Amazon-Style Checkout Addresses
 * Simplified address selection like Amazon checkout
 *
 * @package TostiShop
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="mb-6 bg-white border border-gray-200 rounded-lg shadow-sm" x-data="amazonCheckoutAddresses()">
    
    <!-- Billing Address Section (Default Only - Like Amazon) -->
    <?php 
    $default_billing = null;
    foreach ($billing_addresses as $address) {
        if ($address->is_default) {
            $default_billing = $address;
            break;
        }
    }
    if (!$default_billing && !empty($billing_addresses)) {
        $default_billing = $billing_addresses[0];
    }
    ?>
    
    <?php if ($default_billing) : ?>
        <div class="p-4 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 flex items-center mb-2">
                        <svg class="w-4 h-4 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <?php _e('Billing Address', 'tostishop'); ?>
                    </h3>
                    
                    <!-- Single Default Billing Address -->
                    <div class="text-sm text-gray-600">
                        <span class="font-medium text-gray-900"><?php echo esc_html($default_billing->first_name . ' ' . $default_billing->last_name); ?></span><br>
                        <?php echo esc_html($default_billing->address_1); ?><?php if ($default_billing->address_2) : ?>, <?php echo esc_html($default_billing->address_2); ?><?php endif; ?><br>
                        <?php echo esc_html($default_billing->city . ', ' . $default_billing->state . ' ' . $default_billing->postcode); ?>
                    </div>
                </div>
                
                <button @click="showBillingSelector = true" type="button" 
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium underline">
                    <?php _e('Change', 'tostishop'); ?>
                </button>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Shipping Address Section (Prominent - Like Amazon) -->
    <?php 
    $default_shipping = null;
    foreach ($shipping_addresses as $address) {
        if ($address->is_default) {
            $default_shipping = $address;
            break;
        }
    }
    if (!$default_shipping && !empty($shipping_addresses)) {
        $default_shipping = $shipping_addresses[0];
    }
    ?>
    
    <div class="p-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 flex items-center mb-2">
                    <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <?php _e('Shipping Address', 'tostishop'); ?>
                </h3>
                
                <?php if ($default_shipping) : ?>
                    <!-- Default Shipping Address -->
                    <div class="text-sm text-gray-600">
                        <span class="font-medium text-gray-900"><?php echo esc_html($default_shipping->first_name . ' ' . $default_shipping->last_name); ?></span><br>
                        <?php echo esc_html($default_shipping->address_1); ?><?php if ($default_shipping->address_2) : ?>, <?php echo esc_html($default_shipping->address_2); ?><?php endif; ?><br>
                        <?php echo esc_html($default_shipping->city . ', ' . $default_shipping->state . ' ' . $default_shipping->postcode); ?>
                    </div>
                <?php else : ?>
                    <div class="text-sm text-gray-600">
                        <?php _e('Same as billing address', 'tostishop'); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <button @click="showShippingSelector = true" type="button" 
                    class="px-3 py-1.5 text-sm bg-yellow-500 text-white font-medium rounded-md hover:bg-yellow-600 transition-colors">
                <?php _e('Change', 'tostishop'); ?>
            </button>
        </div>
    </div>
    
    <!-- Billing Address Selector Modal -->
    <div x-show="showBillingSelector" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4" style="display: none;">
        
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-y-auto"
             @click.away="showBillingSelector = false">
            
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900"><?php _e('Choose Billing Address', 'tostishop'); ?></h3>
                    <button @click="showBillingSelector = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-4 space-y-3">
                <?php foreach ($billing_addresses as $address) : ?>
                    <div class="border border-gray-200 rounded-lg p-3 cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all"
                         :class="selectedBilling === <?php echo $address->id; ?> ? 'border-blue-500 bg-blue-50' : ''"
                         @click="selectBillingAddress(<?php echo esc_attr(json_encode($address)); ?>); showBillingSelector = false">
                        
                        <div class="flex items-center">
                            <div class="w-4 h-4 border-2 rounded-full flex items-center justify-center mr-3"
                                 :class="selectedBilling === <?php echo $address->id; ?> ? 'border-blue-500 bg-blue-500' : 'border-gray-300'">
                                <div x-show="selectedBilling === <?php echo $address->id; ?>" class="w-1.5 h-1.5 bg-white rounded-full"></div>
                            </div>
                            
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 flex items-center">
                                    <?php echo esc_html($address->address_name); ?>
                                    <?php if ($address->is_default) : ?>
                                        <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-800 text-xs rounded-full">
                                            <?php _e('Default', 'tostishop'); ?>
                                        </span>
                                    <?php endif; ?>
                                </h4>
                                <p class="text-sm text-gray-600">
                                    <?php echo esc_html($address->first_name . ' ' . $address->last_name); ?><br>
                                    <?php echo esc_html($address->address_1); ?><?php if ($address->address_2) : ?>, <?php echo esc_html($address->address_2); ?><?php endif; ?><br>
                                    <?php echo esc_html($address->city . ', ' . $address->state . ' ' . $address->postcode); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Shipping Address Selector Modal -->
    <div x-show="showShippingSelector" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4" style="display: none;">
        
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-y-auto"
             @click.away="showShippingSelector = false">
            
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900"><?php _e('Choose Shipping Address', 'tostishop'); ?></h3>
                    <button @click="showShippingSelector = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-4 space-y-3">
                <!-- Same as billing option -->
                <div class="border border-gray-200 rounded-lg p-3 cursor-pointer hover:border-green-500 hover:bg-green-50 transition-all"
                     :class="useSameBilling ? 'border-green-500 bg-green-50' : ''"
                     @click="selectSameBilling(); showShippingSelector = false">
                    
                    <div class="flex items-center">
                        <div class="w-4 h-4 border-2 rounded-full flex items-center justify-center mr-3"
                             :class="useSameBilling ? 'border-green-500 bg-green-500' : 'border-gray-300'">
                            <div x-show="useSameBilling" class="w-1.5 h-1.5 bg-white rounded-full"></div>
                        </div>
                        
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900"><?php _e('Same as billing address', 'tostishop'); ?></h4>
                            <p class="text-sm text-gray-600"><?php _e('Use the same address for shipping', 'tostishop'); ?></p>
                        </div>
                    </div>
                </div>
                
                <?php foreach ($shipping_addresses as $address) : ?>
                    <div class="border border-gray-200 rounded-lg p-3 cursor-pointer hover:border-green-500 hover:bg-green-50 transition-all"
                         :class="selectedShipping === <?php echo $address->id; ?> ? 'border-green-500 bg-green-50' : ''"
                         @click="selectShippingAddress(<?php echo esc_attr(json_encode($address)); ?>); showShippingSelector = false">
                        
                        <div class="flex items-center">
                            <div class="w-4 h-4 border-2 rounded-full flex items-center justify-center mr-3"
                                 :class="selectedShipping === <?php echo $address->id; ?> ? 'border-green-500 bg-green-500' : 'border-gray-300'">
                                <div x-show="selectedShipping === <?php echo $address->id; ?>" class="w-1.5 h-1.5 bg-white rounded-full"></div>
                            </div>
                            
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 flex items-center">
                                    <?php echo esc_html($address->address_name); ?>
                                    <?php if ($address->is_default) : ?>
                                        <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-800 text-xs rounded-full">
                                            <?php _e('Default', 'tostishop'); ?>
                                        </span>
                                    <?php endif; ?>
                                </h4>
                                <p class="text-sm text-gray-600">
                                    <?php echo esc_html($address->first_name . ' ' . $address->last_name); ?><br>
                                    <?php echo esc_html($address->address_1); ?><?php if ($address->address_2) : ?>, <?php echo esc_html($address->address_2); ?><?php endif; ?><br>
                                    <?php echo esc_html($address->city . ', ' . $address->state . ' ' . $address->postcode); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
function amazonCheckoutAddresses() {
    return {
        selectedBilling: <?php echo $default_billing ? $default_billing->id : 'null'; ?>,
        selectedShipping: <?php echo $default_shipping ? $default_shipping->id : 'null'; ?>,
        useSameBilling: <?php echo $default_shipping ? 'false' : 'true'; ?>,
        showBillingSelector: false,
        showShippingSelector: false,
        
        init() {
            // Auto-fill default addresses
            <?php if ($default_billing) : ?>
                this.fillBillingFields(<?php echo json_encode($default_billing); ?>);
            <?php endif; ?>
            
            <?php if ($default_shipping) : ?>
                this.fillShippingFields(<?php echo json_encode($default_shipping); ?>);
            <?php else : ?>
                this.copyBillingToShipping();
            <?php endif; ?>
        },
        
        selectBillingAddress(address) {
            this.selectedBilling = address.id;
            this.fillBillingFields(address);
            
            // If using same billing, update shipping too
            if (this.useSameBilling) {
                this.copyBillingToShipping();
            }
        },
        
        selectShippingAddress(address) {
            this.selectedShipping = address.id;
            this.useSameBilling = false;
            this.fillShippingFields(address);
        },
        
        selectSameBilling() {
            this.useSameBilling = true;
            this.selectedShipping = null;
            this.copyBillingToShipping();
        },
        
        fillBillingFields(address) {
            const fields = {
                'billing_first_name': address.first_name,
                'billing_last_name': address.last_name,
                'billing_company': address.company || '',
                'billing_address_1': address.address_1,
                'billing_address_2': address.address_2 || '',
                'billing_city': address.city,
                'billing_state': address.state,
                'billing_postcode': address.postcode,
                'billing_country': address.country,
                'billing_phone': address.phone || '',
                'billing_email': address.email || ''
            };
            
            Object.keys(fields).forEach(fieldName => {
                const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.value = fields[fieldName];
                    field.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        },
        
        fillShippingFields(address) {
            const fields = {
                'shipping_first_name': address.first_name,
                'shipping_last_name': address.last_name,
                'shipping_company': address.company || '',
                'shipping_address_1': address.address_1,
                'shipping_address_2': address.address_2 || '',
                'shipping_city': address.city,
                'shipping_state': address.state,
                'shipping_postcode': address.postcode,
                'shipping_country': address.country
            };
            
            Object.keys(fields).forEach(fieldName => {
                const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.value = fields[fieldName];
                    field.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        },
        
        copyBillingToShipping() {
            // Copy billing to shipping
            const billingFields = ['first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'state', 'postcode', 'country'];
            
            billingFields.forEach(fieldSuffix => {
                const billingField = document.getElementById('billing_' + fieldSuffix) || document.querySelector(`[name="billing_${fieldSuffix}"]`);
                const shippingField = document.getElementById('shipping_' + fieldSuffix) || document.querySelector(`[name="shipping_${fieldSuffix}"]`);
                
                if (billingField && shippingField) {
                    shippingField.value = billingField.value;
                    shippingField.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }
    };
}
</script>

<!-- Billing Address Modal -->

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
