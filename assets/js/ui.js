/**
 * TostiShop Theme JavaScript
 * Mobile-first interactivity with Alpine.js
 * Fixed syntax error - Version 1.0.1
 */

// Screen reader announcement helper - Global function
window.announceToScreenReader = function(message) {
    if (!message || typeof message !== 'string') {
        return;
    }
    
    const announcement = document.createElement('div');
    announcement.setAttribute('aria-live', 'polite');
    announcement.setAttribute('aria-atomic', 'true');
    announcement.setAttribute('class', 'sr-only');
    announcement.textContent = message;
    document.body.appendChild(announcement);
    
    // Remove after announcement
    setTimeout(() => {
        if (announcement && announcement.parentNode) {
            document.body.removeChild(announcement);
        }
    }, 1000);
};

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
 * Initialize WooCommerce functionality with accessibility
 */
function initializeWooCommerce() {
    
    // AJAX Add to Cart with better feedback
    initializeAjaxAddToCart();
    
    // Form validation
    initializeFormValidation();
    
    // Quantity controls
    initializeQuantityControls();
    
    // Mobile-specific enhancements
    initializeMobileEnhancements();
}

/**
 * Placeholder for addToCartAjax function
 */
function addToCartAjax() {
    // This function is disabled - redirect to single product page for add to cart
    return false;
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
    
    // Quantity increase/decrease buttons with accessibility
    window.increaseQuantity = function(inputId = 'quantity') {
        const quantityInput = document.getElementById(inputId);
        if (quantityInput) {
            const currentValue = parseInt(quantityInput.value) || 1;
            const maxValue = parseInt(quantityInput.getAttribute('max')) || 999;
            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
                // Announce change to screen readers
                announceToScreenReader(`Quantity increased to ${quantityInput.value}`);
                // Trigger change event
                quantityInput.dispatchEvent(new Event('change'));
            }
        }
    };
    
    window.decreaseQuantity = function(inputId = 'quantity') {
        const quantityInput = document.getElementById(inputId);
        if (quantityInput) {
            const currentValue = parseInt(quantityInput.value) || 1;
            const minValue = parseInt(quantityInput.getAttribute('min')) || 1;
            if (currentValue > minValue) {
                quantityInput.value = currentValue - 1;
                // Announce change to screen readers
                announceToScreenReader(`Quantity decreased to ${quantityInput.value}`);
                // Trigger change event
                quantityInput.dispatchEvent(new Event('change'));
            }
        }
    };
    
    // Gallery navigation with accessibility
    window.showGalleryImage = function(index) {
        const mainImage = document.getElementById('main-product-image');
        const galleryImages = document.querySelectorAll('.gallery-image');
        const thumbnails = document.querySelectorAll('.thumbnail-btn');
        
        if (!mainImage) return;
        
        // Hide all gallery images
        galleryImages.forEach(img => img.style.display = 'none');
        
        // Update thumbnail states and ARIA attributes
        thumbnails.forEach((thumb, i) => {
            thumb.classList.remove('border-blue-500');
            thumb.classList.add('border-gray-200');
            thumb.setAttribute('aria-selected', 'false');
            thumb.setAttribute('tabindex', '-1');
        });
        
        if (index === 0) {
            // Show main image
            mainImage.style.display = 'block';
            mainImage.focus();
            // Update main thumbnail
            const mainThumb = document.querySelector('[data-thumbnail="main"]');
            if (mainThumb) {
                mainThumb.classList.remove('border-gray-200');
                mainThumb.classList.add('border-blue-500');
                mainThumb.setAttribute('aria-selected', 'true');
                mainThumb.setAttribute('tabindex', '0');
            }
            announceToScreenReader('Viewing main product image');
        } else {
            // Show gallery image
            mainImage.style.display = 'none';
            const targetGalleryImage = document.querySelector(`[data-gallery-index="${index}"]`);
            if (targetGalleryImage) {
                targetGalleryImage.style.display = 'block';
                targetGalleryImage.focus();
                announceToScreenReader(`Viewing product image ${index + 1}`);
            }
            
            // Update corresponding thumbnail
            const targetThumb = document.querySelector(`[data-thumbnail="${index}"]`);
            if (targetThumb) {
                targetThumb.classList.remove('border-gray-200');
                targetThumb.classList.add('border-blue-500');
                targetThumb.setAttribute('aria-selected', 'true');
                targetThumb.setAttribute('tabindex', '0');
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
 * Initialize product gallery with accessibility
 */
function initializeProductGallery() {
    
    const gallery = document.querySelector('.product-gallery');
    const thumbnails = document.querySelectorAll('.thumbnail-btn');
    
    if (!gallery) return;
    
    // Keyboard navigation for thumbnails
    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('keydown', function(e) {
            let targetIndex = index;
            
            switch(e.key) {
                case 'ArrowRight':
                case 'ArrowDown':
                    e.preventDefault();
                    targetIndex = (index + 1) % thumbnails.length;
                    break;
                case 'ArrowLeft':
                case 'ArrowUp':
                    e.preventDefault();
                    targetIndex = (index - 1 + thumbnails.length) % thumbnails.length;
                    break;
                case 'Home':
                    e.preventDefault();
                    targetIndex = 0;
                    break;
                case 'End':
                    e.preventDefault();
                    targetIndex = thumbnails.length - 1;
                    break;
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    thumbnail.click();
                    return;
                default:
                    return;
            }
            
            // Focus and activate target thumbnail
            thumbnails[targetIndex].focus();
            thumbnails[targetIndex].click();
        });
    });
    
    // Touch swipe support for mobile
    let startX = 0;
    let startY = 0;
    let currentX = 0;
    let currentY = 0;
    let isDragging = false;
    let isHorizontalSwipe = false;
    
    gallery.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
        isDragging = true;
        isHorizontalSwipe = false;
    });
    
    gallery.addEventListener('touchmove', function(e) {
        if (!isDragging) return;
        
        currentX = e.touches[0].clientX;
        currentY = e.touches[0].clientY;
        
        const diffX = Math.abs(startX - currentX);
        const diffY = Math.abs(startY - currentY);
        
        // Determine if this is a horizontal swipe gesture
        if (diffX > diffY && diffX > 10) {
            isHorizontalSwipe = true;
            e.preventDefault(); // Only prevent default for horizontal swipes
        } else if (diffY > diffX) {
            // This is vertical scrolling, allow default behavior
            isHorizontalSwipe = false;
        }
    });
    
    gallery.addEventListener('touchend', function() {
        if (!isDragging || !isHorizontalSwipe) {
            isDragging = false;
            return;
        }
        
        const diffX = startX - currentX;
        const threshold = 50;
        
        if (Math.abs(diffX) > threshold) {
            const currentActive = document.querySelector('.thumbnail-btn[aria-selected="true"]');
            const currentIndex = Array.from(thumbnails).indexOf(currentActive);
            
            if (diffX > 0 && currentIndex < thumbnails.length - 1) {
                // Swipe left - next image
                thumbnails[currentIndex + 1].click();
            } else if (diffX < 0 && currentIndex > 0) {
                // Swipe right - previous image
                thumbnails[currentIndex - 1].click();
            }
        }
        
        isDragging = false;
        isHorizontalSwipe = false;
    });
    
    // Initialize first image as active
    if (thumbnails.length > 0) {
        const mainThumb = document.querySelector('[data-thumbnail="main"]');
        if (mainThumb) {
            mainThumb.setAttribute('aria-selected', 'true');
            mainThumb.setAttribute('tabindex', '0');
        }
    }
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

/**
 * Show WooCommerce-style notice in the notices wrapper
 */
function showWooCommerceNotice(message, type = 'success') {
    const noticesWrapper = document.querySelector('.woocommerce-notices-wrapper');
    if (!noticesWrapper) {
        // Fallback to regular notification if no notices wrapper
        showNotification(message, type);
        return;
    }
    
    // Clear existing notices
    noticesWrapper.innerHTML = '';
    
    // Create notice element
    const notice = document.createElement('div');
    notice.className = `woocommerce-message ${type === 'error' ? 'woocommerce-error' : 'woocommerce-message'}`;
    notice.setAttribute('role', 'alert');
    
    // Add appropriate styling
    if (type === 'error') {
        notice.className += ' bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4';
    } else {
        notice.className += ' bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4';
    }
    
    notice.innerHTML = message;
    
    // Add to notices wrapper
    noticesWrapper.appendChild(notice);
    
    // Scroll to notices
    noticesWrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    
    // Auto-remove success notices after 5 seconds
    if (type === 'success') {
        setTimeout(() => {
            if (notice.parentNode) {
                notice.style.opacity = '0';
                setTimeout(() => {
                    if (notice.parentNode) {
                        notice.parentNode.removeChild(notice);
                    }
                }, 300);
            }
        }, 5000);
    }
}

/**
 * Initialize AJAX Add to Cart with better feedback
 */
function initializeAjaxAddToCart() {
    const forms = document.querySelectorAll('form.cart');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Check if AJAX is available and working
            if (!tostishop_ajax || !tostishop_ajax.ajax_url || !tostishop_ajax.nonce) {
                // Let WooCommerce handle it normally if AJAX isn't properly set up
                return;
            }
            
            const submitBtn = form.querySelector('button[type="submit"]');
            if (!submitBtn) return;
            
            // Get product ID and quantity for validation
            const productId = form.querySelector('input[name="add-to-cart"]')?.value || 
                             form.querySelector('button[name="add-to-cart"]')?.value ||
                             form.querySelector('[data-product-id]')?.dataset.productId;
            const quantity = form.querySelector('input[name="quantity"]')?.value || 1;
            
            // Basic validation - if no product ID, let WooCommerce handle it
            if (!productId) {
                return;
            }
            
            // Only prevent default if we're going to handle it via AJAX
            e.preventDefault();
            
            // Show loading state
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="inline-flex items-center"><svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Adding...</span>';
            
            // Get form data
            const formData = new FormData(form);
            formData.append('action', 'tostishop_add_to_cart');
            formData.append('nonce', tostishop_ajax.nonce);
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            
            // Debug log form data
            console.log('Form data being sent:', {
                action: 'tostishop_add_to_cart',
                product_id: productId,
                quantity: quantity,
                nonce: tostishop_ajax.nonce
            });
            
            // Submit via AJAX
            fetch(tostishop_ajax?.ajax_url || '/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Add to cart response:', data); // Debug log
                if (data.success) {
                    announceToScreenReader('Product added to cart successfully');
                    showWooCommerceNotice(data.data.message || 'Product added to cart!', 'success');
                    // Update cart count if element exists
                    if (data.data && data.data.cart_count !== undefined) {
                        updateCartCount(data.data.cart_count);
                    }
                } else {
                    console.error('Add to cart failed:', data); // Debug log
                    announceToScreenReader('Failed to add product to cart');
                    showWooCommerceNotice(data.data || 'Failed to add to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Add to cart error:', error);
                announceToScreenReader('An error occurred while adding to cart');
                showWooCommerceNotice('An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                // Restore button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    });
}

/**
 * Initialize form validation
 */
function initializeFormValidation() {
    const quantityInputs = document.querySelectorAll('input[type="number"][name="quantity"]');
    
    quantityInputs.forEach(input => {
        input.addEventListener('invalid', function(e) {
            e.preventDefault();
            const message = getValidationMessage(this);
            showNotification(message, 'error');
            announceToScreenReader(message);
        });
        
        input.addEventListener('input', function() {
            this.setCustomValidity('');
            validateQuantityInput(this);
        });
    });
}

/**
 * Get appropriate validation message
 */
function getValidationMessage(input) {
    if (input.validity.valueMissing) {
        return 'Please enter a quantity';
    } else if (input.validity.rangeUnderflow) {
        return `Minimum quantity is ${input.min}`;
    } else if (input.validity.rangeOverflow) {
        return `Maximum quantity is ${input.max}`;
    } else if (input.validity.stepMismatch) {
        return 'Please enter a valid quantity';
    }
    return 'Please enter a valid quantity';
}

/**
 * Validate quantity input
 */
function validateQuantityInput(input) {
    const value = parseInt(input.value);
    const min = parseInt(input.min) || 1;
    const max = parseInt(input.max) || 999;
    
    if (value < min) {
        input.setCustomValidity(`Minimum quantity is ${min}`);
    } else if (value > max) {
        input.setCustomValidity(`Maximum quantity is ${max}`);
    } else {
        input.setCustomValidity('');
    }
}

/**
 * Initialize quantity controls with accessibility
 */
function initializeQuantityControls() {
    const quantityInputs = document.querySelectorAll('input[type="number"][name="quantity"]');
    
    quantityInputs.forEach(input => {
        // Add ARIA attributes
        input.setAttribute('role', 'spinbutton');
        input.setAttribute('aria-label', 'Product quantity');
        
        // Keyboard support
        input.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowUp') {
                e.preventDefault();
                const id = this.id || 'quantity';
                increaseQuantity(id);
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                const id = this.id || 'quantity';
                decreaseQuantity(id);
            }
        });
    });
}

/**
 * Initialize mobile-specific enhancements
 */
function initializeMobileEnhancements() {
    // Sticky add to cart visibility
    initializeStickyAddToCart();
    
    // Touch-friendly interactions
    initializeTouchEnhancements();
    
    // Mobile keyboard optimizations
    initializeMobileKeyboard();
}

/**
 * Initialize sticky add to cart behavior
 */
function initializeStickyAddToCart() {
    const stickyCart = document.querySelector('.fixed.bottom-0[role="complementary"]');
    const mainAddToCart = document.querySelector('form.cart');
    
    if (!stickyCart || !mainAddToCart) return;
    
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    stickyCart.style.transform = 'translateY(100%)';
                    stickyCart.setAttribute('aria-hidden', 'true');
                } else {
                    stickyCart.style.transform = 'translateY(0)';
                    stickyCart.setAttribute('aria-hidden', 'false');
                }
            });
        },
        { threshold: 0.1 }
    );
    
    observer.observe(mainAddToCart);
}

/**
 * Initialize touch enhancements
 */
function initializeTouchEnhancements() {
    // Add touch feedback to buttons
    const buttons = document.querySelectorAll('button, .btn, [role="button"]');
    
    buttons.forEach(button => {
        button.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.98)';
        });
        
        button.addEventListener('touchend', function() {
            this.style.transform = 'scale(1)';
        });
        
        button.addEventListener('touchcancel', function() {
            this.style.transform = 'scale(1)';
        });
    });
}

/**
 * Initialize mobile keyboard optimizations
 */
function initializeMobileKeyboard() {
    const numberInputs = document.querySelectorAll('input[type="number"]');
    
    numberInputs.forEach(input => {
        // Set inputmode for better mobile keyboards
        input.setAttribute('inputmode', 'numeric');
        input.setAttribute('pattern', '[0-9]*');
    });
}

/**
 * Update cart count in header via AJAX
 */
function refreshCartCount() {
    const cartCount = document.querySelector('.cart-count, [data-cart-count]');
    if (!cartCount) return;
    
    fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_cart_count'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.data);
        }
    })
    .catch(error => {
        console.error('Cart count update failed:', error);
    });
}
