/**
 * TostiShop Theme JavaScript
 * Mobile-first interactivity with Alpine.js
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize theme components
    initializeTheme();
    
    // WooCommerce specific functionality
    if (typeof wc_add_to_cart_params !== 'undefined') {
        initializeWooCommerce();
    }
});

/**
 * Initialize main theme functionality
 */
function initializeTheme() {
    
    // Mobile menu functionality (backup if Alpine.js fails)
    const mobileMenuBtn = document.querySelector('[data-mobile-menu-btn]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Search functionality
    initializeSearch();
    
    // Product gallery
    initializeProductGallery();
    
    // Smooth scrolling for anchor links
    initializeSmoothScrolling();
    
    // Lazy loading for images
    initializeLazyLoading();
}

/**
 * Initialize WooCommerce functionality
 */
function initializeWooCommerce() {
    
    // AJAX Add to Cart
    initializeAjaxAddToCart();
    
    // Quantity controls
    initializeQuantityControls();
    
    // Product filters
    initializeProductFilters();
    
    // Cart functionality
    initializeCartFunctionality();
}

/**
 * AJAX Add to Cart functionality
 */
function initializeAjaxAddToCart() {
    
    // Add to cart buttons in product grid
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-to-cart-btn')) {
            e.preventDefault();
            
            const button = e.target;
            const productId = button.dataset.productId;
            const quantity = button.dataset.quantity || 1;
            
            addToCartAjax(productId, quantity, button);
        }
    });
}

/**
 * Add product to cart via AJAX
 */
function addToCartAjax(productId, quantity, button) {
    
    // Show loading state
    const originalText = button.textContent;
    button.textContent = 'Adding...';
    button.disabled = true;
    button.classList.add('loading');
    
    // Create form data
    const formData = new FormData();
    formData.append('action', 'tostishop_add_to_cart');
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    formData.append('nonce', tostishop_ajax.nonce);
    
    // Send AJAX request
    fetch(tostishop_ajax.ajax_url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count in header
            updateCartCount(data.data.cart_count);
            
            // Show success message
            showNotification('Product added to cart!', 'success');
            
            // Reset button
            button.textContent = 'Added!';
            setTimeout(() => {
                button.textContent = originalText;
                button.disabled = false;
                button.classList.remove('loading');
            }, 2000);
        } else {
            // Show error message
            showNotification('Error adding product to cart.', 'error');
            
            // Reset button
            button.textContent = originalText;
            button.disabled = false;
            button.classList.remove('loading');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Show error message
        showNotification('Error adding product to cart.', 'error');
        
        // Reset button
        button.textContent = originalText;
        button.disabled = false;
        button.classList.remove('loading');
    });
}

/**
 * Update cart count in header
 */
function updateCartCount(count) {
    const cartCountElements = document.querySelectorAll('[data-cart-count]');
    cartCountElements.forEach(element => {
        element.textContent = count;
        if (count > 0) {
            element.classList.remove('hidden');
        } else {
            element.classList.add('hidden');
        }
    });
}

/**
 * Initialize quantity controls
 */
function initializeQuantityControls() {
    
    // Quantity increase/decrease buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('quantity-btn')) {
            e.preventDefault();
            
            const button = e.target;
            const input = button.parentElement.querySelector('input[type="number"]');
            const action = button.dataset.action;
            
            if (input && action) {
                let currentValue = parseInt(input.value) || 1;
                
                if (action === 'increase') {
                    input.value = currentValue + 1;
                } else if (action === 'decrease' && currentValue > 1) {
                    input.value = currentValue - 1;
                }
                
                // Trigger change event
                input.dispatchEvent(new Event('change'));
            }
        }
    });
}

/**
 * Initialize search functionality
 */
function initializeSearch() {
    
    const searchInput = document.querySelector('[data-search-input]');
    const searchResults = document.querySelector('[data-search-results]');
    
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length >= 3) {
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            } else {
                hideSearchResults();
            }
        });
        
        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults?.contains(e.target)) {
                hideSearchResults();
            }
        });
    }
}

/**
 * Perform product search
 */
function performSearch(query) {
    // Implementation for AJAX search
    // This would typically search products and show results in a dropdown
    console.log('Searching for:', query);
}

/**
 * Hide search results
 */
function hideSearchResults() {
    const searchResults = document.querySelector('[data-search-results]');
    if (searchResults) {
        searchResults.classList.add('hidden');
    }
}

/**
 * Initialize product gallery
 */
function initializeProductGallery() {
    
    // Gallery thumbnail clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('[data-gallery-thumbnail]')) {
            e.preventDefault();
            
            const thumbnail = e.target.closest('[data-gallery-thumbnail]');
            const imageUrl = thumbnail.dataset.imageUrl;
            const mainImage = document.querySelector('[data-main-image]');
            
            if (imageUrl && mainImage) {
                mainImage.src = imageUrl;
                
                // Update active thumbnail
                document.querySelectorAll('[data-gallery-thumbnail]').forEach(thumb => {
                    thumb.classList.remove('ring-2', 'ring-primary');
                });
                thumbnail.classList.add('ring-2', 'ring-primary');
            }
        }
    });
    
    // Initialize touch gallery for mobile
    initializeTouchGallery();
}

/**
 * Initialize touch gallery for mobile
 */
function initializeTouchGallery() {
    
    const gallery = document.querySelector('[data-product-gallery]');
    if (!gallery) return;
    
    let startX = 0;
    let currentX = 0;
    let isDragging = false;
    
    gallery.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
        isDragging = true;
    });
    
    gallery.addEventListener('touchmove', function(e) {
        if (!isDragging) return;
        currentX = e.touches[0].clientX;
    });
    
    gallery.addEventListener('touchend', function(e) {
        if (!isDragging) return;
        
        const diffX = startX - currentX;
        
        if (Math.abs(diffX) > 50) { // Minimum swipe distance
            if (diffX > 0) {
                nextGalleryImage();
            } else {
                previousGalleryImage();
            }
        }
        
        isDragging = false;
    });
}

/**
 * Navigate to next gallery image
 */
function nextGalleryImage() {
    const thumbnails = document.querySelectorAll('[data-gallery-thumbnail]');
    const activeThumbnail = document.querySelector('[data-gallery-thumbnail].ring-primary');
    
    if (thumbnails.length > 0) {
        let currentIndex = Array.from(thumbnails).indexOf(activeThumbnail);
        let nextIndex = (currentIndex + 1) % thumbnails.length;
        
        thumbnails[nextIndex].click();
    }
}

/**
 * Navigate to previous gallery image
 */
function previousGalleryImage() {
    const thumbnails = document.querySelectorAll('[data-gallery-thumbnail]');
    const activeThumbnail = document.querySelector('[data-gallery-thumbnail].ring-primary');
    
    if (thumbnails.length > 0) {
        let currentIndex = Array.from(thumbnails).indexOf(activeThumbnail);
        let prevIndex = currentIndex === 0 ? thumbnails.length - 1 : currentIndex - 1;
        
        thumbnails[prevIndex].click();
    }
}

/**
 * Initialize product filters
 */
function initializeProductFilters() {
    
    // Filter form submission
    const filterForm = document.querySelector('[data-product-filters]');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });
        
        // Filter changes
        filterForm.addEventListener('change', function() {
            applyFilters();
        });
    }
}

/**
 * Apply selected filters
 */
function applyFilters() {
    
    const filterForm = document.querySelector('[data-product-filters]');
    if (!filterForm) return;
    
    const formData = new FormData(filterForm);
    const filters = {};
    
    for (let [key, value] of formData.entries()) {
        if (filters[key]) {
            if (Array.isArray(filters[key])) {
                filters[key].push(value);
            } else {
                filters[key] = [filters[key], value];
            }
        } else {
            filters[key] = value;
        }
    }
    
    applyFiltersToProducts(filters);
}

/**
 * Apply filters to product grid
 */
function applyFiltersToProducts(filters) {
    
    const products = document.querySelectorAll('[data-product-item]');
    
    products.forEach(product => {
        let shouldShow = true;
        
        // Check each filter
        for (let [filterKey, filterValue] of Object.entries(filters)) {
            const productValue = product.dataset[filterKey];
            
            if (filterValue && productValue) {
                const filterValues = Array.isArray(filterValue) ? filterValue : [filterValue];
                if (!filterValues.includes(productValue)) {
                    shouldShow = false;
                    break;
                }
            }
        }
        
        // Show/hide product
        if (shouldShow) {
            product.style.display = '';
        } else {
            product.style.display = 'none';
        }
    });
}

/**
 * Initialize cart functionality
 */
function initializeCartFunctionality() {
    
    // Cart item quantity updates
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('cart-quantity-input')) {
            updateCartItemQuantity(e.target);
        }
    });
    
    // Remove cart item buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-cart-item')) {
            e.preventDefault();
            removeCartItem(e.target);
        }
    });
}

/**
 * Update cart item quantity
 */
function updateCartItemQuantity(input) {
    const cartKey = input.dataset.cartKey;
    const quantity = parseInt(input.value);
    
    if (cartKey && quantity >= 0) {
        // Implementation for updating cart item quantity
        console.log('Updating cart item:', cartKey, 'to quantity:', quantity);
    }
}

/**
 * Remove item from cart
 */
function removeCartItem(button) {
    const cartKey = button.dataset.cartKey;
    
    if (cartKey && confirm('Remove this item from cart?')) {
        // Implementation for removing cart item
        console.log('Removing cart item:', cartKey);
    }
}

/**
 * Initialize smooth scrolling - FIXED VERSION
 */
function initializeSmoothScrolling() {
    
    document.addEventListener('click', function(e) {
        // Only handle anchor links that have a valid href with hash
        if (e.target.tagName === 'A' || e.target.closest('a')) {
            const link = e.target.tagName === 'A' ? e.target : e.target.closest('a');
            const href = link.getAttribute('href');
            
            // Check if it's a valid anchor link (not just '#' or empty)
            if (href && href.startsWith('#') && href.length > 1) {
                const targetId = href.substring(1); // Remove the '#'
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
            // For links with just '#' or empty href, don't prevent default
            // but also don't try to scroll to anything
        }
    });
}

/**
 * Initialize lazy loading for images
 */
function initializeLazyLoading() {
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        img.classList.remove('lazy');
                        observer.unobserve(img);
                    }
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
}

/**
 * Show notification message
 */
function showNotification(message, type = 'success') {
    
    // Use TostiShop notifications if available
    if (window.tostishopNotifications) {
        if (type === 'success') {
            window.tostishopNotifications.success(message);
        } else if (type === 'error') {
            window.tostishopNotifications.error(message);
        } else {
            window.tostishopNotifications.info(message);
        }
        return;
    }
    
    // Fallback notification
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg text-white ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

/**
 * Utility functions
 */

// Debounce function
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            timeout = null;
            if (!immediate) func(...args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func(...args);
    };
}

// Throttle function
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Check if element is in viewport
function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

// Global functions for Alpine.js components
window.addToCart = addToCartAjax;
window.increaseQuantity = function() { /* defined above */ };
window.decreaseQuantity = function() { /* defined above */ };
window.showNotification = showNotification;
