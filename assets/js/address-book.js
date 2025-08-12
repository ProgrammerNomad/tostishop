// TostiShop Address Book JavaScript
// Alpine.js component for address management

// Ensure Alpine.js is available
function initAddressBook() {
    if (typeof Alpine === 'undefined') {
        console.log('Alpine.js not loaded yet, retrying...');
        setTimeout(initAddressBook, 100);
        return;
    }
    
    Alpine.data('addressBook', () => ({
        // State
        showAddForm: false,
        showEditForm: false,
        editingAddress: null,
        loading: false,
        errors: {},
        
        // Form data
        formData: {
            address_type: 'home',
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
        
        // Initialize
        init() {
            console.log('TostiShop Address Book initialized successfully');
            // Load addresses if user is logged in
            this.loadAddresses();
        },
        
        // Load user addresses
        async loadAddresses() {
            if (typeof tostishop_addresses === 'undefined') {
                console.log('AJAX data not available');
                return;
            }
            
            try {
                const response = await fetch(tostishop_addresses.ajax_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'tostishop_get_addresses',
                        nonce: tostishop_addresses.nonce
                    })
                });
                
                const data = await response.json();
                if (data.success) {
                    console.log('Addresses loaded:', data.data);
                } else {
                    console.log('Failed to load addresses:', data.message);
                }
            } catch (error) {
                console.error('Error loading addresses:', error);
            }
        },
        
        // Show add form
        showAdd() {
            this.resetForm();
            this.showAddForm = true;
            this.showEditForm = false;
        },
        
        // Show edit form
        showEdit(address) {
            this.editingAddress = address;
            this.formData = { ...address };
            this.showEditForm = true;
            this.showAddForm = false;
        },
        
        // Cancel forms
        cancelForm() {
            this.showAddForm = false;
            this.showEditForm = false;
            this.editingAddress = null;
            this.resetForm();
        },
        
        // Reset form
        resetForm() {
            this.formData = {
                address_type: 'home',
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
            };
            this.errors = {};
        },
        
        // Save address
        async saveAddress() {
            if (this.loading) return;
            
            this.loading = true;
            this.errors = {};
            
            try {
                const formData = new FormData();
                formData.append('action', 'tostishop_save_address');
                formData.append('nonce', tostishop_ajax.nonce);
                
                // Add all form fields
                for (const [key, value] of Object.entries(this.formData)) {
                    formData.append(key, value);
                }
                
                // If editing, add the address ID
                if (this.editingAddress) {
                    formData.append('address_id', this.editingAddress.id);
                }
                
                const response = await fetch(tostishop_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Reload the page to show updated addresses
                    window.location.reload();
                } else {
                    this.errors = result.data.errors || {};
                    console.error('Save failed:', result.data.message);
                }
            } catch (error) {
                console.error('Error saving address:', error);
                this.errors = { general: 'An error occurred while saving the address.' };
            } finally {
                this.loading = false;
            }
        },
        
        // Delete address
        async deleteAddress(addressId) {
            if (!confirm('Are you sure you want to delete this address?')) {
                return;
            }
            
            try {
                const formData = new FormData();
                formData.append('action', 'tostishop_delete_address');
                formData.append('nonce', tostishop_ajax.nonce);
                formData.append('address_id', addressId);
                
                const response = await fetch(tostishop_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Reload the page to show updated addresses
                    window.location.reload();
                } else {
                    console.error('Delete failed:', result.data.message);
                    alert('Failed to delete address: ' + result.data.message);
                }
            } catch (error) {
                console.error('Error deleting address:', error);
                alert('An error occurred while deleting the address.');
            }
        },
        
        // Set as default
        async setAsDefault(addressId) {
            try {
                const formData = new FormData();
                formData.append('action', 'tostishop_set_default_address');
                formData.append('nonce', tostishop_ajax.nonce);
                formData.append('address_id', addressId);
                
                const response = await fetch(tostishop_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Reload the page to show updated addresses
                    window.location.reload();
                } else {
                    console.error('Set default failed:', result.data.message);
                    alert('Failed to set as default: ' + result.data.message);
                }
            } catch (error) {
                console.error('Error setting default:', error);
                alert('An error occurred while setting the default address.');
            }
        },
        
        // Validate form
        validateForm() {
            this.errors = {};
            
            if (!this.formData.first_name.trim()) {
                this.errors.first_name = 'First name is required';
            }
            if (!this.formData.last_name.trim()) {
                this.errors.last_name = 'Last name is required';
            }
            if (!this.formData.address_1.trim()) {
                this.errors.address_1 = 'Address is required';
            }
            if (!this.formData.city.trim()) {
                this.errors.city = 'City is required';
            }
            if (!this.formData.postcode.trim()) {
                this.errors.postcode = 'ZIP/Postal code is required';
            }
            
            return Object.keys(this.errors).length === 0;
        }
    }));
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initAddressBook);

// Also try to initialize on alpine:init event if available
document.addEventListener('alpine:init', initAddressBook);
