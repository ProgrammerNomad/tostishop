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
    window.increaseQuantity = function() {
        const quantityInput = document.getElementById('quantity');
        if (quantityInput) {
            const currentValue = parseInt(quantityInput.value) || 1;
            const maxValue = parseInt(quantityInput.getAttribute('max')) || 999;
            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
            }
        }
    };
    
    window.decreaseQuantity = function() {
        const quantityInput = document.getElementById('quantity');
        if (quantityInput) {
            const currentValue = parseInt(quantityInput.value) || 1;
            const minValue = parseInt(quantityInput.getAttribute('min')) || 1;
            if (currentValue > minValue) {
                quantityInput.value = currentValue - 1;
            }
        }
    };
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
    
    // Image zoom on hover (desktop only)
    const productImages = document.querySelectorAll('.product-image');
    
    productImages.forEach(image => {
        if (window.innerWidth > 1024) {
            image.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1)';
            });
            
            image.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        }
    });
    
    // Touch/swipe support for mobile gallery
    initializeTouchGallery();
}

/**
 * Initialize touch gallery for mobile
 */
function initializeTouchGallery() {
    
    const gallery = document.querySelector('.product-gallery');
    if (!gallery || window.innerWidth > 1024) return;
    
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
    
    gallery.addEventListener('touchend', function() {
        if (!isDragging) return;
        
        const diffX = startX - currentX;
        const threshold = 50;
        
        if (Math.abs(diffX) > threshold) {
            if (diffX > 0) {
                // Swipe left - next image
                nextGalleryImage();
            } else {
                // Swipe right - previous image
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
    // Implementation for next image
    console.log('Next image');
}

/**
 * Navigate to previous gallery image
 */
function previousGalleryImage() {
    // Implementation for previous image
    console.log('Previous image');
}

/**
 * Initialize product filters
 */
function initializeProductFilters() {
    
    // Price range slider
    const priceSlider = document.querySelector('[data-price-slider]');
    if (priceSlider) {
        // Implementation for price range functionality
        console.log('Price slider initialized');
    }
    
    // Filter checkboxes
    const filterCheckboxes = document.querySelectorAll('[data-filter-checkbox]');
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            applyFilters();
        });
    });
}

/**
 * Apply selected filters
 */
function applyFilters() {
    // Collect all selected filters
    const filters = {};
    
    // Get price range
    const minPrice = document.querySelector('[data-min-price]')?.value;
    const maxPrice = document.querySelector('[data-max-price]')?.value;
    
    if (minPrice) filters.min_price = minPrice;
    if (maxPrice) filters.max_price = maxPrice;
    
    // Get selected categories
    const selectedCategories = [];
    document.querySelectorAll('[data-category-filter]:checked').forEach(checkbox => {
        selectedCategories.push(checkbox.value);
    });
    
    if (selectedCategories.length > 0) {
        filters.categories = selectedCategories;
    }
    
    // Apply filters via URL or AJAX
    applyFiltersToProducts(filters);
}

/**
 * Apply filters to product grid
 */
function applyFiltersToProducts(filters) {
    // Implementation for filtering products
    console.log('Applying filters:', filters);
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
 * Initialize smooth scrolling
 */
function initializeSmoothScrolling() {
    
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
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
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    observer.unobserve(img);
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
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after delay
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
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
