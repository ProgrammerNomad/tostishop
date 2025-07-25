/**
 * Modern Checkout JavaScript
 * Handles coupon functionality and checkout interactions
 */

jQuery(document).ready(function($) {
    
    // Initialize coupon toggle functionality
    function initCouponToggle() {
        const toggleButton = $('#toggle-coupon');
        const couponForm = $('#coupon-form');
        
        if (toggleButton.length && couponForm.length) {
            toggleButton.on('click', function(e) {
                e.preventDefault();
                
                if (couponForm.hasClass('hidden')) {
                    couponForm.removeClass('hidden').hide().slideDown(300);
                    toggleButton.text('Cancel');
                } else {
                    couponForm.slideUp(300, function() {
                        $(this).addClass('hidden');
                    });
                    toggleButton.text('Add Code');
                }
            });
        }
    }
    
    // Handle coupon form submission
    function initCouponSubmission() {
        $(document).on('submit', '#coupon-form', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const couponCode = form.find('#coupon_code').val().trim();
            const submitButton = form.find('button[type="submit"]');
            const originalText = submitButton.text();
            
            if (!couponCode) {
                alert('Please enter a coupon code');
                return;
            }
            
            // Add loading state
            submitButton.text('Applying...').prop('disabled', true);
            
            // Add coupon code to hidden form field for WooCommerce processing
            $('<input>').attr({
                type: 'hidden',
                name: 'coupon_code',
                value: couponCode
            }).appendTo('form.checkout');
            
            $('<input>').attr({
                type: 'hidden',
                name: 'apply_coupon',
                value: '1'
            }).appendTo('form.checkout');
            
            // Trigger checkout update
            $('body').trigger('update_checkout');
            
            // Reset button after a delay
            setTimeout(function() {
                submitButton.text(originalText).prop('disabled', false);
            }, 2000);
        });
    }
    
    // Enhanced checkout form styling
    function enhanceFormElements() {
        // Add modern styling to form elements
        $('.woocommerce-checkout input[type="text"], .woocommerce-checkout input[type="email"], .woocommerce-checkout input[type="tel"], .woocommerce-checkout select').each(function() {
            $(this).addClass('modern-input');
        });
        
        // Add focus effects
        $('.modern-input').on('focus', function() {
            $(this).closest('.form-row').addClass('focused');
        }).on('blur', function() {
            $(this).closest('.form-row').removeClass('focused');
        });
    }
    
    // Mobile-friendly enhancements
    function initMobileEnhancements() {
        // Auto-scroll to error fields on mobile
        $(document).on('checkout_error', function() {
            const firstError = $('.woocommerce-error, .woocommerce-notice--error').first();
            if (firstError.length && $(window).width() <= 768) {
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 100
                }, 500);
            }
        });
        
        // Sticky place order button on mobile
        if ($(window).width() <= 768) {
            const placeOrderButton = $('#place_order');
            if (placeOrderButton.length) {
                $(window).on('scroll', function() {
                    const scrollTop = $(window).scrollTop();
                    const documentHeight = $(document).height();
                    const windowHeight = $(window).height();
                    
                    if (scrollTop + windowHeight >= documentHeight - 100) {
                        placeOrderButton.removeClass('mobile-sticky');
                    } else {
                        placeOrderButton.addClass('mobile-sticky');
                    }
                });
            }
        }
    }
    
    // Enhanced Payment Methods
    function enhancePaymentMethods() {
        // Add event listeners to payment method radio buttons
        document.querySelectorAll('.wc_payment_methods input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected class from all methods
                document.querySelectorAll('.wc_payment_method').forEach(method => {
                    method.classList.remove('selected');
                });
                
                // Add selected class to chosen method
                if (this.checked) {
                    this.closest('.wc_payment_method').classList.add('selected');
                }
            });
        });
        
        // Set initial selected state
        const checkedRadio = document.querySelector('.wc_payment_methods input[type="radio"]:checked');
        if (checkedRadio) {
            checkedRadio.closest('.wc_payment_method').classList.add('selected');
        }
    }
    
    // Enhanced Place Order Button
    function enhancePlaceOrderButton() {
        const placeOrderBtn = document.getElementById('place_order');
        const placeOrderText = placeOrderBtn?.querySelector('.place-order-text');
        const placeOrderSpinner = placeOrderBtn?.querySelector('.place-order-spinner');
        
        if (placeOrderBtn) {
            // Add click handler for loading state
            placeOrderBtn.addEventListener('click', function(e) {
                // Basic validation before showing loading
                const requiredFields = document.querySelectorAll('.woocommerce-checkout [required]');
                let hasErrors = false;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        hasErrors = true;
                        field.style.borderColor = '#ef4444';
                        setTimeout(() => {
                            field.style.borderColor = '';
                        }, 3000);
                    }
                });
                
                if (!hasErrors) {
                    this.classList.add('processing');
                    if (placeOrderText) placeOrderText.style.opacity = '0';
                    if (placeOrderSpinner) placeOrderSpinner.classList.remove('hidden');
                    
                    // Disable the button to prevent double submission
                    this.disabled = true;
                }
            });
            
            // Mobile sticky behavior
            if (window.innerWidth <= 640) {
                placeOrderBtn.classList.add('mobile-sticky');
            }
            
            // Update on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth <= 640) {
                    placeOrderBtn.classList.add('mobile-sticky');
                } else {
                    placeOrderBtn.classList.remove('mobile-sticky');
                }
            });
        }
    }
    
    // Initialize all functions
    initCouponToggle();
    initCouponSubmission();
    enhanceFormElements();
    initMobileEnhancements();
    enhancePaymentMethods();
    enhancePlaceOrderButton();
    
    // Re-initialize after checkout updates
    $(document.body).on('updated_checkout', function() {
        initCouponToggle();
        enhanceFormElements();
        enhancePaymentMethods();
        enhancePlaceOrderButton();
    });
});

// Add modern styles via JavaScript
const modernCheckoutStyles = `
<style id="modern-checkout-styles">
/* Enhanced Modern Input Styling */
.form-row.focused label {
    color: #14175b;
    transform: translateY(-2px);
    transition: all 0.2s ease;
}

.form-row.focused .modern-input {
    border-color: #14175b;
    box-shadow: 0 0 0 3px rgba(20, 23, 91, 0.1);
}

/* Mobile Sticky Button */
#place_order.mobile-sticky {
    position: fixed;
    bottom: 20px;
    left: 20px;
    right: 20px;
    z-index: 999;
    margin: 0;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(20, 23, 91, 0.3);
}

/* Enhanced Coupon Section */
.coupon-form {
    transition: all 0.3s ease;
}

.coupon-form.hidden {
    display: none;
}

/* Loading States */
.processing #place_order {
    position: relative;
    color: transparent;
}

.processing #place_order::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 20px;
    height: 20px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to {
        transform: translate(-50%, -50%) rotate(360deg);
    }
}

/* Error Animations */
.woocommerce-error, .woocommerce-notice--error {
    animation: shakeError 0.5s ease-in-out;
}

@keyframes shakeError {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Success Animations */
.woocommerce-message, .woocommerce-notice--success {
    animation: slideInSuccess 0.5s ease-out;
}

@keyframes slideInSuccess {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
`;

// Inject styles
document.head.insertAdjacentHTML('beforeend', modernCheckoutStyles);
