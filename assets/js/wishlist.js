/**
 * TostiShop Wishlist JavaScript
 * Handles add/remove wishlist functionality with Tailwind CSS styling
 * 
 * @package TostiShop
 * @version 1.0.0
 */

document.addEventListener('DOMContentLoaded', function() {
    initWishlistFunctionality();
});

function initWishlistFunctionality() {
    // Handle wishlist button clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.wishlist-btn')) {
            e.preventDefault();
            handleWishlistClick(e.target.closest('.wishlist-btn'));
        }
        
        if (e.target.closest('.wishlist-login-required')) {
            e.preventDefault();
            handleLoginRequired();
        }
        
        if (e.target.closest('.remove-from-wishlist')) {
            e.preventDefault();
            handleRemoveFromWishlist(e.target.closest('.remove-from-wishlist'));
        }
        
        if (e.target.closest('.add-to-cart-from-wishlist')) {
            e.preventDefault();
            handleAddToCartFromWishlist(e.target.closest('.add-to-cart-from-wishlist'));
        }
    });
}

function handleWishlistClick(button) {
    const productId = button.getAttribute('data-product-id');
    const isInWishlist = button.classList.contains('in-wishlist');
    
    if (isInWishlist) {
        removeFromWishlist(productId, button);
    } else {
        addToWishlist(productId, button);
    }
}

function handleLoginRequired() {
    if (typeof tostishop_wishlist_ajax !== 'undefined' && tostishop_wishlist_ajax.strings) {
        showNotification(tostishop_wishlist_ajax.strings.login_required, 'warning');
        
        // Redirect to login page after a short delay
        setTimeout(function() {
            if (tostishop_wishlist_ajax.login_url) {
                window.location.href = tostishop_wishlist_ajax.login_url;
            }
        }, 1500);
    }
}

function addToWishlist(productId, button) {
    if (typeof tostishop_wishlist_ajax === 'undefined') {
        console.error('Wishlist AJAX object not found');
        return;
    }
    
    // Add loading state with Tailwind classes
    button.disabled = true;
    button.classList.add('opacity-50', 'cursor-not-allowed');
    
    const formData = new FormData();
    formData.append('action', 'tostishop_add_to_wishlist');
    formData.append('product_id', productId);
    formData.append('nonce', tostishop_wishlist_ajax.nonce);
    
    fetch(tostishop_wishlist_ajax.ajax_url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateWishlistButton(button, true);
            showNotification(data.data.message || tostishop_wishlist_ajax.strings.added_to_wishlist, 'success');
        } else {
            showNotification(data.data.message || tostishop_wishlist_ajax.strings.error_occurred, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(tostishop_wishlist_ajax.strings.error_occurred, 'error');
    })
    .finally(() => {
        // Remove loading state
        button.disabled = false;
        button.classList.remove('opacity-50', 'cursor-not-allowed');
    });
}

function removeFromWishlist(productId, button) {
    if (typeof tostishop_wishlist_ajax === 'undefined') {
        console.error('Wishlist AJAX object not found');
        return;
    }
    
    // Add loading state with Tailwind classes
    button.disabled = true;
    button.classList.add('opacity-50', 'cursor-not-allowed');
    
    const formData = new FormData();
    formData.append('action', 'tostishop_remove_from_wishlist');
    formData.append('product_id', productId);
    formData.append('nonce', tostishop_wishlist_ajax.nonce);
    
    fetch(tostishop_wishlist_ajax.ajax_url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateWishlistButton(button, false);
            showNotification(data.data.message || tostishop_wishlist_ajax.strings.removed_from_wishlist, 'success');
            
            // If we're on the wishlist page, remove the item
            const wishlistItem = button.closest('.wishlist-item');
            if (wishlistItem) {
                wishlistItem.remove();
                updateWishlistCount();
            }
        } else {
            showNotification(data.data.message || tostishop_wishlist_ajax.strings.error_occurred, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(tostishop_wishlist_ajax.strings.error_occurred, 'error');
    })
    .finally(() => {
        // Remove loading state
        button.disabled = false;
        button.classList.remove('opacity-50', 'cursor-not-allowed');
    });
}

function handleRemoveFromWishlist(button) {
    const productId = button.getAttribute('data-product-id');
    removeFromWishlist(productId, button);
}

function handleAddToCartFromWishlist(button) {
    const productId = button.getAttribute('data-product-id');
    
    // Add loading state with Tailwind classes
    button.disabled = true;
    button.classList.add('opacity-50', 'cursor-not-allowed');
    
    // Get the original text
    const originalText = button.innerHTML;
    button.innerHTML = '<span class="flex items-center justify-center"><svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Adding...</span>';
    
    // Use WooCommerce AJAX add to cart
    const formData = new FormData();
    formData.append('action', 'woocommerce_add_to_cart');
    formData.append('product_id', productId);
    formData.append('quantity', '1');
    
    fetch(tostishop_wishlist_ajax.ajax_url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.fragments) {
            // Update cart fragments
            Object.keys(data.fragments).forEach(key => {
                const element = document.querySelector(key);
                if (element) {
                    element.outerHTML = data.fragments[key];
                }
            });
            
            showNotification('Product added to cart!', 'success');
        } else {
            showNotification('Error adding product to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding product to cart', 'error');
    })
    .finally(() => {
        // Remove loading state
        button.disabled = false;
        button.classList.remove('opacity-50', 'cursor-not-allowed');
        button.innerHTML = originalText;
    });
}

function updateWishlistButton(button, isInWishlist) {
    const heartIcon = button.querySelector('svg');
    const span = button.querySelector('.sr-only');
    
    if (isInWishlist) {
        button.classList.add('in-wishlist');
        button.classList.add('text-red-500');
        if (heartIcon) {
            heartIcon.setAttribute('fill', 'currentColor');
        }
        if (span && typeof tostishop_wishlist_ajax !== 'undefined') {
            span.textContent = tostishop_wishlist_ajax.strings.remove_from_wishlist;
        }
        if (typeof tostishop_wishlist_ajax !== 'undefined') {
            button.setAttribute('title', tostishop_wishlist_ajax.strings.remove_from_wishlist);
        }
    } else {
        button.classList.remove('in-wishlist');
        button.classList.remove('text-red-500');
        if (heartIcon) {
            heartIcon.setAttribute('fill', 'none');
        }
        if (span && typeof tostishop_wishlist_ajax !== 'undefined') {
            span.textContent = tostishop_wishlist_ajax.strings.add_to_wishlist;
        }
        if (typeof tostishop_wishlist_ajax !== 'undefined') {
            button.setAttribute('title', tostishop_wishlist_ajax.strings.add_to_wishlist);
        }
    }
}

function updateWishlistCount() {
    const remainingItems = document.querySelectorAll('.wishlist-item').length;
    const headerText = document.querySelector('.wishlist-header p');
    
    if (headerText) {
        if (remainingItems === 0) {
            headerText.textContent = 'Your wishlist is empty';
            
            // Show empty state message
            const container = document.querySelector('.wishlist-items-container');
            if (container) {
                container.innerHTML = `
                    <div class="text-center py-12">
                        <div class="w-24 h-24 mx-auto mb-4 text-gray-300">
                            <svg fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Your wishlist is empty</h3>
                        <p class="text-gray-600 mb-6">Save items you like to your wishlist and review them anytime.</p>
                        <a href="${window.location.origin}/shop" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Continue Shopping
                        </a>
                    </div>
                `;
            }
        } else {
            const itemText = remainingItems === 1 ? 'item' : 'items';
            headerText.textContent = `You have ${remainingItems} ${itemText} in your wishlist`;
        }
    }
}

function showNotification(message, type = 'info') {
    // Create notification element with Tailwind classes
    const notification = document.createElement('div');
    
    let bgColor, textColor, iconPath;
    switch(type) {
        case 'success':
            bgColor = 'bg-green-50 border-green-200';
            textColor = 'text-green-800';
            iconPath = 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
            break;
        case 'error':
            bgColor = 'bg-red-50 border-red-200';
            textColor = 'text-red-800';
            iconPath = 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z';
            break;
        case 'warning':
            bgColor = 'bg-yellow-50 border-yellow-200';
            textColor = 'text-yellow-800';
            iconPath = 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.232 16.5c-.77.833.192 2.5 1.732 2.5z';
            break;
        default:
            bgColor = 'bg-blue-50 border-blue-200';
            textColor = 'text-blue-800';
            iconPath = 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
    }
    
    notification.className = `fixed top-4 right-4 z-50 flex items-center p-4 border rounded-lg shadow-lg ${bgColor} ${textColor} max-w-sm transition-all duration-300 transform translate-x-full`;
    notification.innerHTML = `
        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="${iconPath}" clip-rule="evenodd"/>
        </svg>
        <span class="font-medium">${message}</span>
        <button class="ml-4 p-1 hover:bg-black hover:bg-opacity-10 rounded" onclick="this.parentElement.remove()">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M6 6l12 12m0-12L6 18"/>
            </svg>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}
