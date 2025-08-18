/**
 * TostiShop Shipping Calculator JavaScript
 * Handles Shiprocket shipping method calculation and display
 */

document.addEventListener('DOMContentLoaded', function() {
    initShippingCalculator();
});

/**
 * Initialize shipping calculator functionality
 */
function initShippingCalculator() {
    const pincodeInput = document.getElementById('shipping-pincode-input');
    const calculateBtn = document.getElementById('calculate-shipping-btn');
    const resultsDiv = document.getElementById('shipping-methods-results');
    const methodsList = document.getElementById('shipping-methods-list');
    const loadingDiv = document.getElementById('shipping-loading');
    const errorDiv = document.getElementById('shipping-error');
    const errorMessage = document.getElementById('shipping-error-message');
    
    if (!pincodeInput || !calculateBtn) {
        return;
    }
    
    // Add click event listener
    calculateBtn.addEventListener('click', function(e) {
        e.preventDefault();
        calculateShippingMethods();
    });
    
    // Add enter key support
    pincodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            calculateShippingMethods();
        }
    });
    
    // Input validation and formatting
    pincodeInput.addEventListener('input', function(e) {
        // Only allow numbers
        let value = e.target.value.replace(/[^0-9]/g, '');
        
        // Limit to 6 digits
        if (value.length > 6) {
            value = value.substring(0, 6);
        }
        
        e.target.value = value;
        
        // Hide results when user starts typing
        if (value.length === 0) {
            hideResults();
        }
        
        // Auto-calculate when 6 digits are entered
        if (value.length === 6) {
            setTimeout(function() {
                calculateShippingMethods();
            }, 500);
        }
    });
    
    // Load saved pincode from localStorage
    const savedPincode = localStorage.getItem('tostishop_shipping_pincode');
    if (savedPincode && savedPincode.length === 6) {
        pincodeInput.value = savedPincode;
        
        // Auto-calculate for saved pincode
        setTimeout(function() {
            calculateShippingMethods();
        }, 1000);
    }
    
    /**
     * Calculate shipping methods via AJAX
     */
    function calculateShippingMethods() {
        const pincode = pincodeInput.value.trim();
        
        // Validate pincode
        if (!pincode) {
            showError('Please enter a pincode');
            return;
        }
        
        if (!/^\d{6}$/.test(pincode)) {
            showError('Please enter a valid 6-digit pincode');
            return;
        }
        
        // Check if we have the required AJAX configuration
        if (!window.tostishopShipping || !tostishopShipping.ajaxUrl || !tostishopShipping.nonce) {
            showError('Configuration error. Please refresh the page.');
            return;
        }
        
        setLoadingState(true);
        
        // Make AJAX request
        fetch(tostishopShipping.ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'tostishop_calculate_shipping_methods',
                nonce: tostishopShipping.nonce,
                pincode: pincode
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayShippingMethods(data.data.shipping_methods, data.data.cart_total, data.data.free_shipping_threshold);
                
                // Save pincode to localStorage
                localStorage.setItem('tostishop_shipping_pincode', pincode);
            } else {
                showError(data.data || 'Failed to calculate shipping methods');
            }
        })
        .catch(error => {
            console.error('Shipping calculation error:', error);
            showError('Unable to calculate shipping. Please try again.');
        })
        .finally(() => {
            setLoadingState(false);
        });
    }
    
    /**
     * Display shipping methods in the UI
     */
    function displayShippingMethods(methods, cartTotal, freeShippingThreshold) {
        if (!methods || methods.length === 0) {
            showError('No shipping methods available for this location');
            return;
        }
        
        hideError();
        
        let html = '';
        
        // Show free shipping notice if applicable
        if (cartTotal < freeShippingThreshold) {
            const remaining = freeShippingThreshold - cartTotal;
            html += `
                <div class="p-4 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-lg mb-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-amber-800">
                                Add ₹${remaining.toFixed(0)} more for free shipping on all methods!
                            </p>
                        </div>
                    </div>
                </div>
            `;
        } else {
            // Show free shipping applied message
            html += `
                <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg mb-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                🎉 Free shipping applied to all methods!
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        methods.forEach(function(method, index) {
            const isRecommended = index === 0;
            const isFreeShipping = method.is_free;
            
            // Display cost logic
            let costDisplay = '';
            let originalCostDisplay = '';
            
            if (isFreeShipping) {
                costDisplay = '<span class="text-2xl font-bold text-green-600">FREE</span>';
                if (method.original_cost > 0) {
                    originalCostDisplay = `<span class="text-sm text-gray-400 line-through ml-2">₹${method.original_cost.toFixed(0)}</span>`;
                }
            } else {
                costDisplay = `<span class="text-2xl font-bold text-navy-900">₹${method.cost.toFixed(0)}</span>`;
            }
            
            // Determine shipping icon and colors based on type
            let iconHtml = '';
            let cardBorderClass = 'border-gray-200';
            let cardBgClass = 'bg-white';
            
            if (method.type === 'express') {
                iconHtml = `
                    <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-xl">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                `;
                if (isRecommended) {
                    cardBorderClass = 'border-green-300 ring-2 ring-green-100';
                    cardBgClass = 'bg-gradient-to-r from-green-50 to-emerald-50';
                }
            } else {
                iconHtml = `
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4-8-4m16 0v10l-8 4-8-4V7"></path>
                        </svg>
                    </div>
                `;
                if (isRecommended) {
                    cardBorderClass = 'border-blue-300 ring-2 ring-blue-100';
                    cardBgClass = 'bg-gradient-to-r from-blue-50 to-indigo-50';
                }
            }
            
            const recommendedBadge = isRecommended ? 
                '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-accent text-white shadow-sm">⭐ RECOMMENDED</span>' : '';
            
            const freeShippingBadge = isFreeShipping && cartTotal >= freeShippingThreshold ? 
                '<div class="mt-2"><span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">🎉 Free shipping applied!</span></div>' : '';
            
            const codBadge = method.cod_available ? 
                '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 ml-2">💵 COD Available</span>' : '';
            
            html += `
                <div class="shipping-method-item ${cardBgClass} border ${cardBorderClass} rounded-xl p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start flex-1">
                            ${iconHtml}
                            <div class="ml-4 flex-1">
                                <div class="flex items-center flex-wrap gap-2 mb-2">
                                    <h5 class="text-lg font-bold text-gray-900">${method.courier_name}</h5>
                                    ${recommendedBadge}
                                </div>
                                <p class="text-base text-gray-700 font-medium mb-1">${method.delivery_text}</p>
                                <div class="flex flex-wrap items-center gap-2">
                                    ${codBadge}
                                    ${method.estimated_days > 0 ? `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">📅 ${method.estimated_days} day${method.estimated_days > 1 ? 's' : ''}</span>` : ''}
                                </div>
                                ${freeShippingBadge}
                            </div>
                        </div>
                        <div class="text-right ml-4">
                            <div class="flex flex-col items-end">
                                <div class="flex items-baseline">
                                    ${costDisplay}
                                    ${originalCostDisplay}
                                </div>
                                ${isFreeShipping ? '<p class="text-xs text-green-600 font-medium mt-1">You saved ₹' + method.original_cost.toFixed(0) + '!</p>' : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        methodsList.innerHTML = html;
        resultsDiv.classList.remove('hidden');
        
        // Add smooth scroll to results
        setTimeout(() => {
            resultsDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 100);
    }
    
    /**
     * Set loading state with improved button styling
     */
    function setLoadingState(loading) {
        const calculateBtn = document.getElementById('calculate-shipping-btn');
        const btnText = calculateBtn.querySelector('.calculate-btn-text');
        const btnIcon = calculateBtn.querySelector('.calculate-btn-icon');
        const loadingDiv = document.getElementById('shipping-loading');
        
        if (loading) {
            loadingDiv.classList.remove('hidden');
            calculateBtn.disabled = true;
            calculateBtn.classList.add('opacity-75');
            btnText.textContent = 'Calculating...';
            btnIcon.classList.remove('hidden');
            hideError();
            hideResults();
        } else {
            loadingDiv.classList.add('hidden');
            calculateBtn.disabled = false;
            calculateBtn.classList.remove('opacity-75');
            btnText.textContent = 'Calculate';
            btnIcon.classList.add('hidden');
        }
    }
    
    /**
     * Show error message
     */
    function showError(message) {
        errorMessage.textContent = message;
        errorDiv.classList.remove('hidden');
        hideResults();
    }
    
    /**
     * Hide error message
     */
    function hideError() {
        errorDiv.classList.add('hidden');
    }
    
    /**
     * Hide results
     */
    function hideResults() {
        resultsDiv.classList.add('hidden');
    }
}