/**
 * Enhanced Compact Address Picker for TostiShop Checkout
 * Now uses modals like Amazon for address selection
 */

// Debug: Add console log to see if script is loading
console.log('Enhanced Compact Address Picker script loaded');

// Register component when Alpine is ready
document.addEventListener('alpine:init', () => {
    console.log('Alpine:init event fired, registering enhanced component');
    
    window.Alpine.data('compactAddressPicker', () => ({
        // Modal states
        showBillingModal: false,
        showShippingModal: false,
        showAddBillingForm: false,
        showAddShippingForm: false,
        
        // Selection states
        selectedBillingIndex: null,
        selectedShippingIndex: null,
        selectedBillingAddress: null,
        selectedShippingAddress: null,
        useSameBilling: true,
        
        // Loading and error states
        isLoading: false,
        errorMessage: '',
        
        // Address data
        billingAddresses: [],
        shippingAddresses: [],
        
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

        // Initialize component
        init() {
            console.log('Enhanced Compact Address Picker initialized');
            this.loadExistingAddresses();
            this.setupFieldWatchers();
            this.hideOriginalForms();
        },

        // Hide original WooCommerce forms
        hideOriginalForms() {
            const billingWrapper = document.querySelector('.woocommerce-billing-fields__field-wrapper');
            const shippingWrapper = document.querySelector('.woocommerce-shipping-fields__field-wrapper');
            
            if (billingWrapper) billingWrapper.style.display = 'none';
            if (shippingWrapper) shippingWrapper.style.display = 'none';
        },

        // Load existing addresses
        loadExistingAddresses() {
            // This will be populated from the PHP data in the main function
            // The addresses are passed via the main PHP function's JSON encoding
        },

        // Modal Management
        openBillingModal() {
            this.showBillingModal = true;
            this.showAddBillingForm = false;
            this.errorMessage = '';
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        },

        closeBillingModal() {
            this.showBillingModal = false;
            this.showAddBillingForm = false;
            // Restore body scroll
            document.body.style.overflow = '';
        },

        openShippingModal() {
            this.showShippingModal = true;
            this.showAddShippingForm = false;
            this.errorMessage = '';
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        },

        closeShippingModal() {
            this.showShippingModal = false;
            this.showAddShippingForm = false;
            // Restore body scroll
            document.body.style.overflow = '';
        },

        // Address Selection
        selectBillingAddress(index) {
            this.selectedBillingIndex = index;
            this.selectedBillingAddress = this.billingAddresses[index];
            this.populateBillingFields(this.billingAddresses[index]);
            
            // If using same billing for shipping, update shipping too
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
            
            // Copy billing to shipping if billing is selected
            if (this.selectedBillingAddress) {
                this.populateShippingFields(this.selectedBillingAddress);
            }
            
            this.closeShippingModal();
            this.triggerCheckoutUpdate();
        },

        // Field Population
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
                formData.append('nonce', tostishop_ajax?.nonce || '');
                formData.append('type', type);

                const form = type === 'billing' ? this.newBillingForm : this.newShippingForm;
                
                // Add form data
                Object.keys(form).forEach(key => {
                    if (form[key]) {
                        formData.append(key, form[key]);
                    }
                });

                // Also try to get data from the modal form fields
                const container = document.getElementById(`new-${type}-modal-fields`);
                if (container) {
                    const fields = container.querySelectorAll('input, select, textarea');
                    fields.forEach(field => {
                        if (field.name && field.value) {
                            formData.append(field.name.replace(`${type}_`, ''), field.value);
                        }
                    });
                }

                const response = await fetch(tostishop_ajax?.ajax_url || '/wp-admin/admin-ajax.php', {
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

                    // Show success and reload
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
            // Watch for manual changes to detect when user edits fields directly
            const billingFields = document.querySelectorAll('[name^="billing_"]');
            const shippingFields = document.querySelectorAll('[name^="shipping_"]');
            
            [...billingFields, ...shippingFields].forEach(field => {
                field.addEventListener('input', () => {
                    // User is manually editing - clear selections
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
    }));

    console.log('Enhanced compactAddressPicker component registered successfully');
});

// Export for potential external use
window.TostiShopCompactAddressPicker = {
    version: '2.0.0'
};