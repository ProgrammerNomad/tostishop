/**
 * TostiShop Shiprocket Integration - Minimal JS
 * 
 * Handles pincode checking with Tailwind CSS styling and minimal JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    initPincodeCheck();
});

/**
 * Initialize pincode checking functionality
 */
function initPincodeCheck() {
    const pincodeInput = document.getElementById('tostishop-pincode-input');
    const checkButton = document.getElementById('tostishop-check-pincode-btn');
    const responseDiv = document.getElementById('tostishop-pincode-response');
    
    if (!pincodeInput || !checkButton || !responseDiv) {
        return;
    }
    
    // Add event listeners
    checkButton.addEventListener('click', handlePincodeCheck);
    
    // Enter key support
    pincodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            handlePincodeCheck();
        }
    });
    
    // Input validation
    pincodeInput.addEventListener('input', function(e) {
        // Only allow numbers
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
        
        // Clear response when user types
        if (e.target.value.length === 0) {
            responseDiv.classList.add('hidden');
        }
        
        // Auto-check when 6 digits entered
        if (e.target.value.length === 6) {
            setTimeout(handlePincodeCheck, 300);
        }
    });
    
    // Load saved pincode
    const savedPincode = localStorage.getItem('tostishop_pincode');
    if (savedPincode) {
        pincodeInput.value = savedPincode;
        
        // Show saved response if available
        const savedResponse = localStorage.getItem('tostishop_pincode_response');
        if (savedResponse) {
            try {
                const data = JSON.parse(savedResponse);
                showResponse(data.message, data.type || 'info');
            } catch (e) {
                // Ignore parse errors
            }
        }
    }
}

/**
 * Handle pincode check
 */
function handlePincodeCheck() {
    const pincodeInput = document.getElementById('tostishop-pincode-input');
    const checkButton = document.getElementById('tostishop-check-pincode-btn');
    const responseDiv = document.getElementById('tostishop-pincode-response');
    
    const pincode = pincodeInput.value.trim();
    
    // Validate pincode
    if (!pincode) {
        showResponse(tostishopShiprocket.messages.enterPincode, 'error');
        return;
    }
    
    if (!/^\d{6}$/.test(pincode)) {
        showResponse(tostishopShiprocket.messages.validPincode, 'error');
        return;
    }
    
    // Set loading state
    setLoadingState(true);
    
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
            showResponse(data.data.message, data.data.type || 'success');
            
            // Save to localStorage
            localStorage.setItem('tostishop_pincode', pincode);
            localStorage.setItem('tostishop_pincode_response', JSON.stringify(data.data));
        } else {
            showResponse(data.data.message || tostishopShiprocket.messages.error, 'error');
        }
    })
    .catch(error => {
        console.error('Pincode check error:', error);
        showResponse(tostishopShiprocket.messages.error, 'error');
    })
    .finally(() => {
        setLoadingState(false);
    });
}

/**
 * Set loading state
 */
function setLoadingState(loading) {
    const checkButton = document.getElementById('tostishop-check-pincode-btn');
    const pincodeInput = document.getElementById('tostishop-pincode-input');
    
    if (loading) {
        checkButton.disabled = true;
        checkButton.textContent = tostishopShiprocket.messages.checking;
        checkButton.classList.add('opacity-50', 'cursor-not-allowed');
        pincodeInput.disabled = true;
        
        showResponse('Checking pincode serviceability...', 'info');
    } else {
        checkButton.disabled = false;
        checkButton.textContent = 'Check';
        checkButton.classList.remove('opacity-50', 'cursor-not-allowed');
        pincodeInput.disabled = false;
    }
}

/**
 * Show response message
 */
function showResponse(message, type = 'info') {
    const responseDiv = document.getElementById('tostishop-pincode-response');
    
    // Clear previous classes
    responseDiv.className = 'mt-3 p-3 rounded-md text-sm flex items-start gap-2';
    
    // Add type-specific classes and icon
    let icon = '';
    switch (type) {
        case 'success':
        case 'standard':
            responseDiv.classList.add('bg-green-50', 'text-green-800', 'border', 'border-green-200');
            icon = '✅';
            break;
        case 'express':
            responseDiv.classList.add('bg-blue-50', 'text-blue-800', 'border', 'border-blue-200');
            icon = '🚀';
            break;
        case 'error':
            responseDiv.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
            icon = '❌';
            break;
        case 'info':
        default:
            responseDiv.classList.add('bg-gray-50', 'text-gray-800', 'border', 'border-gray-200');
            icon = 'ℹ️';
            break;
    }
    
    responseDiv.innerHTML = `
        <span class="flex-shrink-0">${icon}</span>
        <span class="flex-1">${message}</span>
    `;
    
    // Show the response
    responseDiv.classList.remove('hidden');
    
    // Smooth scroll to response
    responseDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Export for potential use by other scripts
window.TostiShopShiprocket = {
    handlePincodeCheck: handlePincodeCheck,
    clearSaved: function() {
        localStorage.removeItem('tostishop_pincode');
        localStorage.removeItem('tostishop_pincode_response');
    }
};
