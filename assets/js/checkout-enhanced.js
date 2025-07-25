/**
 * Enhanced Checkout Functionality
 * Handles checkout form submission and validation
 */

jQuery(document).ready(function($) {
    
    // Initialize checkout functionality
    initializeCheckout();
    
    function initializeCheckout() {
        
        // Enhanced form validation
        enhanceFormValidation();
        
        // Place order button enhancement
        enhancePlaceOrderButton();
        
        // Payment method selection
        enhancePaymentMethods();
        
        // Terms and conditions validation
        enhanceTermsValidation();
        
        // Debug checkout issues
        debugCheckoutIssues();
    }
    
    /**
     * Enhanced form validation
     */
    function enhanceFormValidation() {
        
        // Validate required fields before submission
        $('.checkout').on('submit', function(e) {
            
            console.log('Checkout form submitted');
            
            let hasErrors = false;
            const $form = $(this);
            
            // Clear previous errors
            $('.field-error').remove();
            $('.woocommerce-error, .woocommerce-notice--error').remove();
            
            // Check required fields
            $form.find('input[required], select[required]').each(function() {
                const $field = $(this);
                const value = $field.val();
                
                if (!value || value.trim() === '') {
                    hasErrors = true;
                    showFieldError($field, 'This field is required');
                }
            });
            
            // Check email format
            const $emailField = $form.find('input[type="email"]');
            if ($emailField.length && $emailField.val()) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test($emailField.val())) {
                    hasErrors = true;
                    showFieldError($emailField, 'Please enter a valid email address');
                }
            }
            
            // Check payment method selection
            if ($('.wc_payment_methods input[type="radio"]').length > 0) {
                if (!$('.wc_payment_methods input[type="radio"]:checked').length) {
                    hasErrors = true;
                    showError('Please select a payment method');
                }
            }
            
            // Check terms and conditions
            const $termsCheckbox = $('#terms');
            if ($termsCheckbox.length && !$termsCheckbox.is(':checked')) {
                hasErrors = true;
                showError('You must accept the terms and conditions');
            }
            
            // If there are errors, prevent submission
            if (hasErrors) {
                e.preventDefault();
                
                // Scroll to first error
                const $firstError = $('.field-error, .woocommerce-error').first();
                if ($firstError.length) {
                    $('html, body').animate({
                        scrollTop: $firstError.offset().top - 100
                    }, 500);
                }
                
                return false;
            }
            
            // Show loading state
            showLoadingState();
        });
    }
    
    /**
     * Show field error
     */
    function showFieldError($field, message) {
        $field.addClass('error-field');
        $field.css('border-color', '#ef4444');
        
        const $errorDiv = $('<div class="field-error text-red-500 text-sm mt-1"></div>').text(message);
        $field.closest('.form-row, .form-group').append($errorDiv);
        
        // Remove error after 5 seconds
        setTimeout(() => {
            $field.removeClass('error-field');
            $field.css('border-color', '');
            $errorDiv.remove();
        }, 5000);
    }
    
    /**
     * Show general error
     */
    function showError(message) {
        const $errorDiv = $('<div class="woocommerce-error bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4"></div>').text(message);
        $('.checkout').prepend($errorDiv);
        
        // Remove error after 5 seconds
        setTimeout(() => {
            $errorDiv.remove();
        }, 5000);
    }
    
    /**
     * Enhanced place order button
     */
    function enhancePlaceOrderButton() {
        const $placeOrderBtn = $('#place_order');
        
        if ($placeOrderBtn.length) {
            
            // Add click handler
            $placeOrderBtn.on('click', function(e) {
                console.log('Place order button clicked');
                
                // Add processing class
                $(this).addClass('processing');
                
                // Show loading state
                const $text = $(this).find('.place-order-text');
                const $spinner = $(this).find('.place-order-spinner');
                
                if ($text.length) $text.css('opacity', '0');
                if ($spinner.length) $spinner.removeClass('hidden');
                
                // Disable button to prevent double submission
                $(this).prop('disabled', true);
            });
            
            // Reset button state on checkout error
            $(document.body).on('checkout_error', function() {
                resetPlaceOrderButton();
            });
        }
    }
    
    /**
     * Reset place order button
     */
    function resetPlaceOrderButton() {
        const $placeOrderBtn = $('#place_order');
        
        if ($placeOrderBtn.length) {
            $placeOrderBtn.removeClass('processing');
            $placeOrderBtn.prop('disabled', false);
            
            const $text = $placeOrderBtn.find('.place-order-text');
            const $spinner = $placeOrderBtn.find('.place-order-spinner');
            
            if ($text.length) $text.css('opacity', '1');
            if ($spinner.length) $spinner.addClass('hidden');
        }
    }
    
    /**
     * Show loading state
     */
    function showLoadingState() {
        console.log('Showing loading state');
        
        // Add overlay
        if (!$('.checkout-overlay').length) {
            $('body').append('<div class="checkout-overlay fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"><div class="bg-white rounded-lg p-6 flex items-center space-x-3"><div class="animate-spin h-6 w-6 border-2 border-primary border-t-transparent rounded-full"></div><span>Processing your order...</span></div></div>');
        }
    }
    
    /**
     * Hide loading state
     */
    function hideLoadingState() {
        $('.checkout-overlay').remove();
        resetPlaceOrderButton();
    }
    
    /**
     * Enhanced payment methods
     */
    function enhancePaymentMethods() {
        
        // Payment method selection handler
        $(document.body).on('change', '.wc_payment_methods input[type="radio"]', function() {
            
            console.log('Payment method changed:', $(this).val());
            
            // Remove selected class from all methods
            $('.wc_payment_method').removeClass('selected');
            
            // Add selected class to chosen method
            $(this).closest('.wc_payment_method').addClass('selected');
            
            // Update payment method
            $(document.body).trigger('update_checkout');
        });
        
        // Set initial selected state
        const $checkedRadio = $('.wc_payment_methods input[type="radio"]:checked');
        if ($checkedRadio.length) {
            $checkedRadio.closest('.wc_payment_method').addClass('selected');
        }
    }
    
    /**
     * Enhanced terms validation
     */
    function enhanceTermsValidation() {
        
        const $termsCheckbox = $('#terms');
        
        if ($termsCheckbox.length) {
            $termsCheckbox.on('change', function() {
                const $wrapper = $(this).closest('.woocommerce-terms-and-conditions-wrapper');
                
                if ($(this).is(':checked')) {
                    $wrapper.removeClass('error');
                } else {
                    $wrapper.addClass('error');
                }
            });
        }
    }
    
    /**
     * Debug checkout issues
     */
    function debugCheckoutIssues() {
        
        // Log checkout events for debugging
        $(document.body).on('checkout_error', function(event, error_message) {
            console.log('Checkout error:', error_message);
            hideLoadingState();
        });
        
        $(document.body).on('updated_checkout', function() {
            console.log('Checkout updated');
            hideLoadingState();
        });
        
        $(document.body).on('applied_coupon', function() {
            console.log('Coupon applied');
        });
        
        $(document.body).on('removed_coupon', function() {
            console.log('Coupon removed');
        });
        
        // Check for JavaScript errors
        window.addEventListener('error', function(e) {
            console.error('JavaScript error on checkout:', e.error);
        });
        
        // Log form submission
        $('.checkout').on('submit', function() {
            console.log('Checkout form submitting...');
            console.log('Form action:', $(this).attr('action'));
            console.log('Form method:', $(this).attr('method'));
        });
        
        // Check if WooCommerce checkout is properly loaded
        if (typeof wc_checkout_params === 'undefined') {
            console.error('WooCommerce checkout parameters not loaded!');
        } else {
            console.log('WooCommerce checkout loaded successfully');
        }
    }
});

// Additional vanilla JavaScript for form handling
document.addEventListener('DOMContentLoaded', function() {
    
    // Backup form submission handler
    const checkoutForm = document.querySelector('form.checkout');
    
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            console.log('Vanilla JS: Form submission detected');
            
            // Check if form has required fields filled
            const requiredFields = this.querySelectorAll('[required]');
            let allValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    allValid = false;
                    field.style.borderColor = '#ef4444';
                    
                    setTimeout(() => {
                        field.style.borderColor = '';
                    }, 3000);
                }
            });
            
            if (!allValid) {
                e.preventDefault();
                console.log('Form validation failed');
                return false;
            }
        });
    }
    
    // Monitor for AJAX errors
    window.addEventListener('beforeunload', function() {
        console.log('Page is being unloaded');
    });
});