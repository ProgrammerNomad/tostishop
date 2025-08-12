/**
 * TostiShop Shiprocket Integration JavaScript
 * Pure class-based approach - works universally on mobile & desktop
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('TostiShop Shiprocket: DOM loaded, initializing...');
    
    // Check if we have the required configuration
    if (typeof tostishopShiprocket === 'undefined') {
        console.error('TostiShop Shiprocket: Configuration not found');
        return;
    }
    
    console.log('TostiShop Shiprocket: Config found, initializing...');
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
        
        console.log('TostiShop Shiprocket: Found ' + pincodeContainers.length + ' pincode container(s)');
        
        if (pincodeContainers.length === 0) {
            retryCount++;
            if (retryCount < maxRetries) {
                console.log('TostiShop Shiprocket: No containers found, retrying... (' + retryCount + '/' + maxRetries + ')');
                setTimeout(findAndSetupElements, 300);
                return;
            } else {
                console.error('TostiShop Shiprocket: No pincode containers found after ' + maxRetries + ' attempts');
                return;
            }
        }
        
        // Setup each pincode checker container
        pincodeContainers.forEach(function(container, index) {
            console.log('TostiShop Shiprocket: Setting up container instance ' + (index + 1));
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
    
    console.log('TostiShop Shiprocket: Elements found in container - Input:', !!pincodeInput, 'Button:', !!checkButton, 'Response:', !!responseArea);
    
    if (!pincodeInput || !checkButton || !responseArea) {
        console.error('TostiShop Shiprocket: Required elements not found in container');
        return;
    }
    
    // Mark this container as initialized to avoid duplicate setup
    if (container.classList.contains('shiprocket-initialized')) {
        console.log('TostiShop Shiprocket: Container already initialized, skipping');
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
        console.log('TostiShop Shiprocket: Check button clicked in container');
        handlePincodeCheckForContainer(this.shiprockeContainer);
    });
    
    // Add enter key support for input
    pincodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            console.log('TostiShop Shiprocket: Enter key pressed in container');
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
        
        // Clear response when user starts typing
        if (value.length === 0) {
            hideResponseForContainer(this.shiprockeContainer);
        }
        
        // Auto-check when 6 digits are entered (with delay)
        if (value.length === 6) {
            const container = this.shiprockeContainer;
            setTimeout(function() {
                console.log('TostiShop Shiprocket: Auto-checking after 6 digits entered');
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
                showResponseForContainer(container, data.message, data.type || 'info');
            } catch (e) {
                console.log('TostiShop Shiprocket: Could not parse saved response');
            }
        }
    }
    
    console.log('TostiShop Shiprocket: Container setup complete');
}

/**
 * Handle pincode check for a specific container
 */
function handlePincodeCheckForContainer(container) {
    console.log('TostiShop Shiprocket: handlePincodeCheck called for container');
    
    const pincodeInput = container.querySelector('.shiprocket-pincode-input');
    const checkButton = container.querySelector('.shiprocket-check-button');
    const responseArea = container.querySelector('.shiprocket-response-area');
    
    if (!pincodeInput || !checkButton || !responseArea) {
        console.error('TostiShop Shiprocket: Required elements not found in container');
        return;
    }
    
    let pincode = pincodeInput.value.trim();
    console.log('TostiShop Shiprocket: Checking pincode:', pincode);
    
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
        console.error('TostiShop Shiprocket: AJAX configuration missing');
        showResponseForContainer(container, 'Configuration error. Please refresh the page.', 'error');
        return;
    }
    
    console.log('TostiShop Shiprocket: Making AJAX request to check pincode');
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
    .then(response => {
        console.log('TostiShop Shiprocket: Received response from server');
        return response.json();
    })
    .then(data => {
        console.log('TostiShop Shiprocket: Response data:', data);
        
        if (data.success) {
            showResponseForContainer(container, data.data.message, data.data.type || 'standard');
            
            // Save to localStorage
            localStorage.setItem('tostishop_pincode', pincode);
            localStorage.setItem('tostishop_pincode_response', JSON.stringify(data.data));
        } else {
            showResponseForContainer(container, data.data || 'Service check failed', 'error');
        }
    })
    .catch(error => {
        console.error('TostiShop Shiprocket: AJAX error:', error);
        showResponseForContainer(container, 'Unable to check serviceability. Please try again.', 'error');
    })
    .finally(() => {
        setLoadingStateForContainer(container, false);
    });
}

/**
 * Show response message for specific container
 */
function showResponseForContainer(container, message, type) {
    const responseArea = container.querySelector('.shiprocket-response-area');
    if (!responseArea) return;
    
    // Base Tailwind classes for all message types
    let classes = 'mt-4 p-3 rounded-md text-sm border';
    
    // Add type-specific Tailwind classes using theme colors
    switch (type) {
        case 'express':
            classes += ' bg-blue-50 text-blue-800 border-blue-200';
            break;
        case 'standard':
            classes += ' bg-green-50 text-green-800 border-green-200';
            break;
        case 'error':
            classes += ' bg-red-50 text-red-800 border-red-200';
            break;
        case 'info':
        default:
            classes += ' bg-gray-50 text-gray-800 border-gray-200';
            break;
    }
    
    responseArea.className = 'shiprocket-response-area ' + classes;
    responseArea.innerHTML = message;
    
    // Auto-hide success messages after 10 seconds
    if (type === 'express' || type === 'standard') {
        setTimeout(function() {
            if (responseArea.innerHTML === message) {
                hideResponseForContainer(container);
            }
        }, 10000);
    }
}

/**
 * Hide response message for specific container
 */
function hideResponseForContainer(container) {
    const responseArea = container.querySelector('.shiprocket-response-area');
    if (!responseArea) return;
    
    responseArea.className = 'shiprocket-response-area mt-4 hidden';
    responseArea.innerHTML = '';
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

// Legacy function for backwards compatibility and debugging
function handlePincodeCheck() {
    // Find any pincode container and trigger it
    const firstContainer = document.querySelector('.shiprocket-pincode-container');
    
    if (firstContainer) {
        handlePincodeCheckForContainer(firstContainer);
    } else {
        console.error('TostiShop Shiprocket: No pincode container found for legacy call');
    }
}

// Make functions globally accessible for debugging
window.tostishopHandlePincodeCheck = handlePincodeCheck;
window.tostishopInitPincodeCheck = initPincodeCheck;

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
            console.log('TostiShop Shiprocket: Containers lost after resize, reinitializing...');
            initPincodeCheck();
        }
    }, 400);
});

// Debug function to manually trigger setup
window.debugShiprockeSetup = function() {
    console.log('Manual Shiprocket setup triggered');
    const containers = document.querySelectorAll('.shiprocket-pincode-container');
    console.log('Found containers:', containers.length);
    containers.forEach(function(container, index) {
        console.log('Container ' + (index + 1) + ':', container);
        setupSinglePincodeContainer(container);
    });
};
