/**
 * TostiShop Shiprocket Integration JavaScript
 * Pure class-based approach - works universally on mobile & desktop
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if we have the required configuration
    if (typeof tostishopShiprocket === 'undefined') {
        return;
    }
    
    initPincodeCheck();
});

/**
 * Initialize pincode checking functionality - Pure class-based approach
 */
function initPincodeCheck() {
    const maxRetries = 25;
    let retryCount = 0;
    
    function findAndSetupElements() {
        // Find all pincode checker containers by class
        const pincodeContainers = document.querySelectorAll('.shiprocket-pincode-container');
        
        if (pincodeContainers.length === 0) {
            retryCount++;
            if (retryCount < maxRetries) {
                setTimeout(findAndSetupElements, 300);
                return;
            } else {
                return;
            }
        }
        
        // Setup each pincode checker container
        pincodeContainers.forEach(function(container) {
            setupSinglePincodeContainer(container);
        });
    }
    
    findAndSetupElements();
}

/**
 * Set up a single pincode checker container using class names only
 */
function setupSinglePincodeContainer(container) {
    // Find elements within this specific container using class names
    const pincodeInput = container.querySelector('.shiprocket-pincode-input');
    const checkButton = container.querySelector('.shiprocket-check-button');
    const responseArea = container.querySelector('.shiprocket-response-area');
    
    if (!pincodeInput || !checkButton || !responseArea) {
        return;
    }
    
    // Mark this container as initialized to avoid duplicate setup
    if (container.classList.contains('shiprocket-initialized')) {
        return;
    }
    container.classList.add('shiprocket-initialized');
    
    // Store container reference in each element for easy access
    pincodeInput.shiprockeContainer = container;
    checkButton.shiprockeContainer = container;
    responseArea.shiprockeContainer = container;
    
    // Add click event listener to button
    checkButton.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        handlePincodeCheckForContainer(this.shiprockeContainer);
    });
    
    // Add enter key support for input
    pincodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            handlePincodeCheckForContainer(this.shiprockeContainer);
        }
    });
    
    // Add input validation and formatting
    pincodeInput.addEventListener('input', function(e) {
        // Only allow numbers
        let value = e.target.value.replace(/[^0-9]/g, '');
        
        // Limit to 6 digits
        if (value.length > 6) {
            value = value.substring(0, 6);
        }
        
        e.target.value = value;
        
        // Clear response when user starts typing (ENHANCED - always clear on input)
        if (value.length === 0) {
            hideResponseForContainer(this.shiprockeContainer);
        } else if (value.length > 0 && value.length < 6) {
            // Hide previous responses when user starts typing a new pincode
            const savedPincode = localStorage.getItem('tostishop_pincode');
            if (savedPincode && value !== savedPincode.substring(0, value.length)) {
                hideResponseForContainer(this.shiprockeContainer);
            }
        }
        
        // Auto-check when 6 digits are entered (with delay)
        if (value.length === 6) {
            const container = this.shiprockeContainer;
            setTimeout(function() {
                handlePincodeCheckForContainer(container);
            }, 800);
        }
    });
    
    // Load saved pincode from localStorage
    const savedPincode = localStorage.getItem('tostishop_pincode');
    if (savedPincode && savedPincode.length === 6) {
        pincodeInput.value = savedPincode;
        
        // Show saved response if available
        const savedResponse = localStorage.getItem('tostishop_pincode_response');
        if (savedResponse) {
            try {
                const data = JSON.parse(savedResponse);
                
                // Check if it's multiple responses
                if (data.multiple_options && data.responses) {
                    showMultipleResponsesForContainer(container, data.responses);
                } else {
                    // Single response
                    showResponseForContainer(container, data.message, data.type || 'info');
                }
            } catch (e) {
                // Silent fail - just don't show saved response
            }
        }
    }
}

/**
 * Show response message for specific container - Enhanced for multiple delivery options
 */
function showResponseForContainer(container, message, type) {
    const responseArea = container.querySelector('.shiprocket-response-area');
    if (!responseArea) return;
    
    // Base Tailwind classes for all message types
    let classes = 'shiprocket-response-area mt-4 p-4 rounded-lg text-sm border-l-4 slide-in-up';
    let icon = '';
    
    // Add type-specific Tailwind classes and icons using TostiShop brand colors
    switch (type) {
        case 'express':
            classes += ' bg-blue-50 text-blue-900 border-blue-500';
            icon = '<svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>';
            break;
        case 'standard':
            classes += ' bg-green-50 text-green-900 border-green-500';
            icon = '<svg class="w-5 h-5 text-green-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            break;
        case 'error':
            classes += ' bg-red-50 text-red-900 border-red-500';
            icon = '<svg class="w-5 h-5 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            break;
        case 'info':
        default:
            classes += ' bg-gray-50 text-gray-900 border-gray-400';
            icon = '<svg class="w-5 h-5 text-gray-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            break;
    }
    
    responseArea.className = classes;
    responseArea.innerHTML = `
        <div class="flex items-start">
            ${icon}
            <div class="flex-1">
                <p class="font-medium">${message}</p>
                ${type === 'express' ? '<p class="text-xs mt-1 opacity-75">⚡ Premium delivery service available</p>' : ''}
                ${type === 'standard' ? '<p class="text-xs mt-1 opacity-75">📦 Reliable delivery service</p>' : ''}
            </div>
        </div>
    `;
    
    // Show the response area with animation
    responseArea.classList.remove('hidden');
    responseArea.classList.add('show');
}

/**
 * Show multiple delivery options for container
 */
function showMultipleResponsesForContainer(container, responses) {
    const responseArea = container.querySelector('.shiprocket-response-area');
    if (!responseArea) return;
    
    // Sort responses by priority (quick delivery first)
    responses.sort((a, b) => a.priority - b.priority);
    
    let combinedHTML = '';
    
    responses.forEach(function(response, index) {
        let classes = 'mb-3 p-3 rounded-lg text-sm border-l-4';
        let icon = '';
        let title = '';
        
        // Add type-specific styling
        switch (response.type) {
            case 'express':
                classes += ' bg-blue-50 text-blue-900 border-blue-500';
                icon = '<svg class="w-4 h-4 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>';
                title = index === 0 && response.priority === 1 ? 'Quick Delivery' : 'Express Delivery';
                break;
            case 'standard':
                classes += ' bg-green-50 text-green-900 border-green-500';
                icon = '<svg class="w-4 h-4 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                title = 'Standard Delivery';
                break;
        }
        
        // Remove the last margin bottom for the last item
        if (index === responses.length - 1) {
            classes = classes.replace('mb-3', 'mb-0');
        }
        
        combinedHTML += `
            <div class="${classes}">
                <div class="flex items-start">
                    ${icon}
                    <div class="flex-1">
                        <p class="font-semibold text-xs uppercase tracking-wide mb-1">${title}</p>
                        <p class="font-medium">${response.message}</p>
                    </div>
                </div>
            </div>
        `;
    });
    
    responseArea.className = 'shiprocket-response-area mt-4 slide-in-up';
    responseArea.innerHTML = combinedHTML;
    
    // Show the response area
    responseArea.classList.remove('hidden');
    responseArea.classList.add('show');
}

/**
 * Handle pincode check for a specific container - Updated for multiple responses
 */
function handlePincodeCheckForContainer(container) {
    const pincodeInput = container.querySelector('.shiprocket-pincode-input');
    const checkButton = container.querySelector('.shiprocket-check-button');
    const responseArea = container.querySelector('.shiprocket-response-area');
    
    if (!pincodeInput || !checkButton || !responseArea) {
        return;
    }
    
    let pincode = pincodeInput.value.trim();
    
    // Validate pincode
    if (!pincode) {
        showResponseForContainer(container, 'Please enter a pincode', 'error');
        return;
    }
    
    if (!/^\d{6}$/.test(pincode)) {
        showResponseForContainer(container, 'Please enter a valid 6-digit pincode', 'error');
        return;
    }
    
    // Check if we have the required AJAX configuration
    if (!window.tostishopShiprocket || !tostishopShiprocket.ajaxUrl || !tostishopShiprocket.nonce) {
        showResponseForContainer(container, 'Configuration error. Please refresh the page.', 'error');
        return;
    }
    
    setLoadingStateForContainer(container, true);
    
    // Make AJAX request
    fetch(tostishopShiprocket.ajaxUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'tostishop_check_pincode',
            nonce: tostishopShiprocket.nonce,
            pincode: pincode,
            product_id: tostishopShiprocket.productId || 0
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Check if we have multiple delivery options
            if (data.data.multiple_options && data.data.responses) {
                showMultipleResponsesForContainer(container, data.data.responses);
                
                // Save to localStorage
                localStorage.setItem('tostishop_pincode', pincode);
                localStorage.setItem('tostishop_pincode_response', JSON.stringify({
                    multiple_options: true,
                    responses: data.data.responses
                }));
            } else {
                // Single response (backwards compatibility)
                showResponseForContainer(container, data.data.message, data.data.type || 'standard');
                
                // Save to localStorage
                localStorage.setItem('tostishop_pincode', pincode);
                localStorage.setItem('tostishop_pincode_response', JSON.stringify(data.data));
            }
        } else {
            showResponseForContainer(container, data.data || 'Service check failed', 'error');
        }
    })
    .catch(error => {
        showResponseForContainer(container, 'Unable to check serviceability. Please try again.', 'error');
    })
    .finally(() => {
        setLoadingStateForContainer(container, false);
    });
}

/**
 * Hide response message for specific container
 */
function hideResponseForContainer(container) {
    const responseArea = container.querySelector('.shiprocket-response-area');
    if (!responseArea) return;
    
    // Add fade out animation
    responseArea.classList.remove('show');
    responseArea.classList.add('opacity-0', 'translate-y-2');
    
    // Hide after animation completes
    setTimeout(function() {
        responseArea.classList.add('hidden');
        responseArea.innerHTML = '';
        responseArea.className = 'shiprocket-response-area mt-4 hidden';
    }, 300);
}

/**
 * Set loading state for specific container
 */
function setLoadingStateForContainer(container, loading) {
    const checkButton = container.querySelector('.shiprocket-check-button');
    const pincodeInput = container.querySelector('.shiprocket-pincode-input');
    
    if (!checkButton || !pincodeInput) return;
    
    if (loading) {
        checkButton.disabled = true;
        checkButton.innerHTML = '<svg class="animate-spin w-4 h-4 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        checkButton.classList.add('opacity-50', 'cursor-not-allowed');
        pincodeInput.disabled = true;
        
        showResponseForContainer(container, 'Checking pincode serviceability...', 'info');
    } else {
        checkButton.disabled = false;
        checkButton.innerHTML = 'Check';
        checkButton.classList.remove('opacity-50', 'cursor-not-allowed');
        pincodeInput.disabled = false;
    }
}

// Touch support for mobile devices
if ('ontouchstart' in window) {
    document.addEventListener('touchstart', function() {}, { passive: true });
}

// Additional mobile optimizations
window.addEventListener('resize', function() {
    // Recheck elements on orientation change
    setTimeout(function() {
        const containers = document.querySelectorAll('.shiprocket-pincode-container');
        if (containers.length === 0) {
            initPincodeCheck();
        }
    }, 400);
});