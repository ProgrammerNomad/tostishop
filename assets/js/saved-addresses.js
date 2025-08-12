/**
 * TostiShop Saved Addresses JavaScript
 * Handles saved addresses functionality
 * 
 * @package TostiShop
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Initialize when DOM is ready
    $(document).ready(function() {
        initSavedAddresses();
    });

    /**
     * Initialize saved addresses functionality
     */
    function initSavedAddresses() {
        // Handle address selection on checkout
        handleCheckoutAddressSelection();
        
        // Handle address book management
        handleAddressBookManagement();
        
        // Handle quick address save during checkout
        handleQuickAddressSave();
    }

    /**
     * Handle address selection on checkout page
     */
    function handleCheckoutAddressSelection() {
        if (!$('.saved-addresses-section').length) {
            return;
        }

        // Handle address card clicks
        $(document).on('click', '.address-option:not(.add-new)', function(e) {
            e.preventDefault();
            
            const $this = $(this);
            const addressData = $this.data('address');
            const addressType = $this.closest('.saved-addresses-section').find('[data-address-type]').data('address-type') || 'billing';
            
        // Remove active state from siblings
        $this.siblings('.address-option').each(function() {
            $(this).removeClass('ring-2 ring-blue-500').addClass('ring-1 ring-gray-200');
        });

        // Add active state to clicked address
        $this.removeClass('ring-1 ring-gray-200').addClass('ring-2 ring-blue-500');            // Fill form fields
            fillAddressFields(addressData, addressType);
            
            // Hide manual entry form if visible
            $this.closest('.address-section').find('.manual-address-form').slideUp();
        });

        // Handle "Use different address" toggle
        $(document).on('change', '.use-different-address', function() {
            const $this = $(this);
            const $section = $this.closest('.address-section');
            const $manualForm = $section.find('.manual-address-form');
            
            if ($this.is(':checked')) {
                $section.find('.address-option').each(function() {
                    $(this).removeClass('ring-2 ring-blue-500').addClass('ring-1 ring-gray-200');
                });
                $manualForm.slideDown();
                clearAddressFields($this.data('type') || 'billing');
            } else {
                $manualForm.slideUp();
            }
        });
    }

    /**
     * Handle address book management on My Account page
     */
    function handleAddressBookManagement() {
        if (!$('.address-book-container').length) {
            return;
        }

        // Handle edit address
        $(document).on('click', '.edit-address-btn', function(e) {
            e.preventDefault();
            
            const addressId = $(this).data('address-id');
            showAddressForm(addressId);
        });

        // Handle delete address
        $(document).on('click', '.delete-address-btn', function(e) {
            e.preventDefault();
            
            if (!confirm(tostishop_addresses.i18n.confirm_delete)) {
                return;
            }
            
            const addressId = $(this).data('address-id');
            deleteAddress(addressId);
        });

        // Handle set default address
        $(document).on('click', '.set-default-btn', function(e) {
            e.preventDefault();
            
            const addressId = $(this).data('address-id');
            const addressType = $(this).data('address-type');
            setDefaultAddress(addressId, addressType);
        });

        // Handle address form submission
        $(document).on('submit', '.address-form', function(e) {
            e.preventDefault();
            saveAddress($(this));
        });

        // Handle form modal close
        $(document).on('click', '.close-form-btn, .form-overlay', function(e) {
            if (e.target === this) {
                closeAddressForm();
            }
        });
    }

    /**
     * Handle quick address save during checkout
     */
    function handleQuickAddressSave() {
        // Handle save address checkbox during checkout
        $(document).on('change', '.save-address-checkbox', function() {
            const $this = $(this);
            const $nameField = $this.closest('.save-address-option').find('.address-name-field');
            
            if ($this.is(':checked')) {
                $nameField.show().find('input').prop('required', true);
            } else {
                $nameField.hide().find('input').prop('required', false);
            }
        });
    }

    /**
     * Fill address fields with saved address data
     */
    function fillAddressFields(address, type) {
        const prefix = type === 'billing' ? 'billing_' : 'shipping_';
        
        const fields = {
            [prefix + 'first_name']: address.first_name,
            [prefix + 'last_name']: address.last_name,
            [prefix + 'company']: address.company,
            [prefix + 'address_1']: address.address_1,
            [prefix + 'address_2']: address.address_2,
            [prefix + 'city']: address.city,
            [prefix + 'state']: address.state,
            [prefix + 'postcode']: address.postcode,
            [prefix + 'country']: address.country,
            [prefix + 'phone']: address.phone
        };

        // Add email for billing
        if (type === 'billing') {
            fields[prefix + 'email'] = address.email;
        }

        // Fill each field
        Object.keys(fields).forEach(function(fieldName) {
            const $field = $('#' + fieldName + ', [name="' + fieldName + '"]');
            if ($field.length && fields[fieldName]) {
                $field.val(fields[fieldName]).trigger('change');
            }
        });

        // Update country and state dropdowns if they exist
        updateCountryStateFields(address, type);
    }

    /**
     * Clear address fields
     */
    function clearAddressFields(type) {
        const prefix = type === 'billing' ? 'billing_' : 'shipping_';
        
        const fieldNames = [
            prefix + 'first_name',
            prefix + 'last_name',
            prefix + 'company',
            prefix + 'address_1',
            prefix + 'address_2',
            prefix + 'city',
            prefix + 'state',
            prefix + 'postcode',
            prefix + 'country',
            prefix + 'phone'
        ];

        if (type === 'billing') {
            fieldNames.push(prefix + 'email');
        }

        fieldNames.forEach(function(fieldName) {
            const $field = $('#' + fieldName + ', [name="' + fieldName + '"]');
            if ($field.length) {
                $field.val('').trigger('change');
            }
        });
    }

    /**
     * Update country and state dropdown fields
     */
    function updateCountryStateFields(address, type) {
        const prefix = type === 'billing' ? 'billing_' : 'shipping_';
        const $countryField = $('#' + prefix + 'country');
        const $stateField = $('#' + prefix + 'state');

        if ($countryField.length && address.country) {
            $countryField.val(address.country).trigger('change');
            
            // Wait for country change to load states, then set state
            setTimeout(function() {
                if ($stateField.length && address.state) {
                    $stateField.val(address.state).trigger('change');
                }
            }, 500);
        }
    }

    /**
     * Show address form modal
     */
    function showAddressForm(addressId = null) {
        if (addressId) {
            // Load existing address data
            loadAddressData(addressId);
        } else {
            // Show empty form using Alpine.js
            const modal = document.querySelector('[x-data*="showModal"]');
            if (modal) {
                modal.__x.$data.showModal = true;
            }
            const form = document.querySelector('.address-form');
            if (form) form.reset();
        }
    }

    /**
     * Close address form modal
     */
    function closeAddressForm() {
        const modal = document.querySelector('[x-data*="showModal"]');
        if (modal) {
            modal.__x.$data.showModal = false;
        }
        const form = document.querySelector('.address-form');
        if (form) form.reset();
    }

    /**
     * Load address data for editing
     */
    function loadAddressData(addressId) {
        const data = {
            action: 'tostishop_get_saved_address',
            nonce: tostishop_addresses.nonce,
            address_id: addressId
        };

        $.post(tostishop_addresses.ajax_url, data)
            .done(function(response) {
                if (response.success) {
                    fillFormFields(response.data);
                    const modal = document.querySelector('[x-data*="showModal"]');
                    if (modal) {
                        modal.__x.$data.showModal = true;
                    }
                } else {
                    showNotification(response.data.message || tostishop_addresses.i18n.error, 'error');
                }
            })
            .fail(function() {
                showNotification(tostishop_addresses.i18n.error, 'error');
            });
    }

    /**
     * Fill form fields with address data
     */
    function fillFormFields(address) {
        const $form = $('.address-form');
        
        Object.keys(address).forEach(function(key) {
            const $field = $form.find('[name="' + key + '"]');
            if ($field.length) {
                if ($field.attr('type') === 'checkbox') {
                    $field.prop('checked', Boolean(address[key]));
                } else {
                    $field.val(address[key]);
                }
            }
        });
    }

    /**
     * Save address
     */
    function saveAddress($form) {
        const $submitBtn = $form.find('[type="submit"]');
        const originalText = $submitBtn.text();
        
        // Show loading state
        $submitBtn.text(tostishop_addresses.i18n.saving).prop('disabled', true);
        
        const formData = $form.serialize() + '&action=tostishop_save_address&nonce=' + tostishop_addresses.nonce;
        
        $.post(tostishop_addresses.ajax_url, formData)
            .done(function(response) {
                if (response.success) {
                    showNotification(response.data.message, 'success');
                    closeAddressForm();
                    
                    // Reload page to show updated addresses
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification(response.data.message || tostishop_addresses.i18n.error, 'error');
                }
            })
            .fail(function() {
                showNotification(tostishop_addresses.i18n.error, 'error');
            })
            .always(function() {
                $submitBtn.text(originalText).prop('disabled', false);
            });
    }

    /**
     * Delete address
     */
    function deleteAddress(addressId) {
        const data = {
            action: 'tostishop_delete_address',
            nonce: tostishop_addresses.nonce,
            address_id: addressId
        };

        $.post(tostishop_addresses.ajax_url, data)
            .done(function(response) {
                if (response.success) {
                    showNotification(response.data.message, 'success');
                    
                    // Remove address card from DOM
                    $('.address-card[data-address-id="' + addressId + '"]').fadeOut(function() {
                        $(this).remove();
                        
                        // Check if no addresses left
                        if (!$('.address-card').length) {
                            location.reload();
                        }
                    });
                } else {
                    showNotification(response.data.message || tostishop_addresses.i18n.error, 'error');
                }
            })
            .fail(function() {
                showNotification(tostishop_addresses.i18n.error, 'error');
            });
    }

    /**
     * Set default address
     */
    function setDefaultAddress(addressId, addressType) {
        const data = {
            action: 'tostishop_set_default_address',
            nonce: tostishop_addresses.nonce,
            address_id: addressId,
            address_type: addressType
        };

        $.post(tostishop_addresses.ajax_url, data)
            .done(function(response) {
                if (response.success) {
                    showNotification(response.data.message, 'success');
                    
                    // Update UI to reflect new default
                    $('.address-card').find('.default-badge').remove();
                    $('.address-card').find('.set-default-btn').show();
                    
                    const $currentCard = $('.address-card[data-address-id="' + addressId + '"]');
                    $currentCard.prepend('<span class="default-badge">Default</span>');
                    $currentCard.find('.set-default-btn').hide();
                } else {
                    showNotification(response.data.message || tostishop_addresses.i18n.error, 'error');
                }
            })
            .fail(function() {
                showNotification(tostishop_addresses.i18n.error, 'error');
            });
    }

    /**
     * Show notification message
     */
    function showNotification(message, type) {
        // Create notification element with Tailwind classes
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 max-w-sm w-full bg-white shadow-lg rounded-lg border-l-4 p-4 z-50 transform translate-x-full transition-transform duration-300 ${
            type === 'success' ? 'border-green-400' : 'border-red-400'
        }`;
        
        notification.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    ${type === 'success' 
                        ? '<svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
                        : '<svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>'
                    }
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto hide after 4 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 4000);
    }

    /**
     * Auto-fill default addresses on checkout
     */
    function autoFillDefaultAddresses() {
        if (!$('body').hasClass('woocommerce-checkout')) {
            return;
        }

        // Auto-select first default address for each type
        $('.saved-addresses-section .address-option.default').each(function() {
            $(this).trigger('click');
        });
    }

    // Auto-fill default addresses when checkout page loads
    $(window).on('load', function() {
        autoFillDefaultAddresses();
    });

    // Handle WooCommerce checkout updates
    $(document.body).on('updated_checkout', function() {
        autoFillDefaultAddresses();
    });

})(jQuery);
