/**
 * TostiShop Theme JavaScript - FIXED VERSION
 * Enhanced functionality for modern WooCommerce experience
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize grid/list view toggle
    const gridView = document.querySelector('[data-view="grid"]');
    const listView = document.querySelector('[data-view="list"]');
    const productGrid = document.querySelector('[data-product-grid]');
    
    if (gridView && listView && productGrid) {
        gridView.addEventListener('click', function() {
            productGrid.className = productGrid.className.replace(/grid-cols-\d+/, 'grid-cols-2 md:grid-cols-3 lg:grid-cols-4');
            this.classList.add('bg-primary', 'text-white');
            this.classList.remove('bg-gray-200', 'text-gray-700');
            listView.classList.remove('bg-primary', 'text-white');
            listView.classList.add('bg-gray-200', 'text-gray-700');
            localStorage.setItem('tostiShopGridView', 'grid');
        });
        
        listView.addEventListener('click', function() {
            productGrid.className = productGrid.className.replace(/grid-cols-\d+/, 'grid-cols-1');
            this.classList.add('bg-primary', 'text-white');
            this.classList.remove('bg-gray-200', 'text-gray-700');
            gridView.classList.remove('bg-primary', 'text-white');
            gridView.classList.add('bg-gray-200', 'text-gray-700');
            localStorage.setItem('tostiShopGridView', 'list');
        });
        
        // Load saved view preference
        const savedView = localStorage.getItem('tostiShopGridView');
        if (savedView === 'list') {
            listView.click();
        } else {
            gridView.click();
        }
    }
    
    // Enhanced mobile menu functionality
    const mobileMenuButton = document.querySelector('[data-mobile-menu-button]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    const mobileMenuClose = document.querySelector('[data-mobile-menu-close]');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    }
    
    if (mobileMenuClose && mobileMenu) {
        mobileMenuClose.addEventListener('click', function() {
            mobileMenu.classList.add('hidden');
            document.body.style.overflow = '';
        });
    }
    
    // AJAX add to cart enhancement for simple products
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-to-cart-btn') && !e.target.classList.contains('loading')) {
            e.preventDefault();
            
            const button = e.target;
            const productId = button.getAttribute('data-product-id');
            const quantity = button.getAttribute('data-quantity') || 1;
            
            // Add loading state
            button.classList.add('loading');
            const originalText = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin h-4 w-4 mx-auto" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            
            // Use WooCommerce AJAX add to cart
            const formData = new FormData();
            formData.append('action', 'woocommerce_add_to_cart');
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            
            fetch(tostishop_ajax.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Show success message
                button.classList.remove('loading');
                button.classList.add('added');
                button.innerHTML = 'Added to Cart';
                
                // Update cart count if possible
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    const currentCount = parseInt(cartCount.textContent) || 0;
                    cartCount.textContent = currentCount + parseInt(quantity);
                }
                
                // Show notification with actions
                const productName = button.closest('.product-item')?.querySelector('h3')?.textContent || 
                                  button.closest('[data-product-id]')?.querySelector('.product-title')?.textContent || 
                                  'Product';
                
                // Trigger custom event for notifications
                if (typeof jQuery !== 'undefined') {
                    jQuery(document).trigger('tostishop_product_added_to_cart', [productName]);
                    
                    // Also trigger WooCommerce event if available
                    jQuery(document.body).trigger('added_to_cart', [null, null, button]);
                }
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('added');
                }, 2000);
                
                // Trigger cart update event
                document.body.dispatchEvent(new Event('wc_fragment_refresh'));
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                button.classList.remove('loading');
                button.innerHTML = 'Error - Try Again';
                
                // Show error notification
                if (window.tostishopNotifications) {
                    window.tostishopNotifications.error('Error adding product to cart. Please try again.');
                }
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                }, 2000);
            });
        }
    });
    
    // Product quantity controls
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('quantity-plus')) {
            const input = e.target.previousElementSibling;
            if (input && input.type === 'number') {
                input.value = parseInt(input.value) + 1;
                input.dispatchEvent(new Event('change'));
            }
        }
        
        if (e.target.classList.contains('quantity-minus')) {
            const input = e.target.nextElementSibling;
            if (input && input.type === 'number' && parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                input.dispatchEvent(new Event('change'));
            }
        }
    });
    
});

// Global functions for compatibility
window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
};

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
};

window.updateMainImage = function(imageSrc) {
    const mainImage = document.querySelector('.product-main-image img');
    if (mainImage && imageSrc) {
        mainImage.src = imageSrc;
    }
};

// jQuery document ready (for WooCommerce compatibility)
if (typeof jQuery !== 'undefined') {
    jQuery(document).ready(function($) {
        
        // Update cart fragments on page load
        if (typeof wc_cart_fragments_params !== 'undefined') {
            $(document.body).trigger('wc_fragment_refresh');
        }
        
        // Listen for WooCommerce add to cart events
        $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
            const productName = $button.closest('.product-item')?.find('h3')?.text() || 
                              $button.closest('[data-product-id]')?.find('.product-title')?.text() || 
                              'Product';
            
            if (window.tostishopNotifications) {
                window.tostishopNotifications.cart(productName + ' added to cart!', [
                    {
                        text: 'View Cart',
                        action: 'view-cart',
                        class: 'bg-white bg-opacity-20 text-white hover:bg-opacity-30 transition-all duration-200'
                    },
                    {
                        text: 'Continue Shopping',
                        action: 'continue-shopping',
                        class: 'bg-transparent border border-white border-opacity-30 text-white hover:bg-white hover:bg-opacity-10 transition-all duration-200'
                    }
                ]);
            }
        });

        // Custom event for manual cart additions
        $(document).on('tostishop_product_added_to_cart', function(event, productName) {
            if (window.tostishopNotifications) {
                window.tostishopNotifications.cart(productName + ' added to cart!', [
                    {
                        text: 'View Cart',
                        action: 'view-cart',
                        class: 'bg-white bg-opacity-20 text-white hover:bg-opacity-30 transition-all duration-200'
                    },
                    {
                        text: 'Continue Shopping', 
                        action: 'continue-shopping',
                        class: 'bg-transparent border border-white border-opacity-30 text-white hover:bg-white hover:bg-opacity-10 transition-all duration-200'
                    }
                ]);
            }
        });
        
    });
}
