// TostiShop Theme JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Product grid view toggle
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const productGrid = document.querySelector('.products');
    
    if (gridView && listView && productGrid) {
        gridView.addEventListener('click', function() {
            productGrid.className = 'products grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6';
            gridView.classList.add('text-blue-600');
            gridView.classList.remove('text-gray-600');
            listView.classList.add('text-gray-600');
            listView.classList.remove('text-blue-600');
            localStorage.setItem('tostiShopGridView', 'grid');
        });
        
        listView.addEventListener('click', function() {
            productGrid.className = 'products grid grid-cols-1 gap-6';
            listView.classList.add('text-blue-600');
            listView.classList.remove('text-gray-600');
            gridView.classList.add('text-gray-600');
            gridView.classList.remove('text-blue-600');
            localStorage.setItem('tostiShopGridView', 'list');
        });
        
        // Restore saved view preference
        const savedView = localStorage.getItem('tostiShopGridView');
        if (savedView === 'list') {
            listView.click();
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
    
    // AJAX add to cart enhancement
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add_to_cart_button') && !e.target.classList.contains('product_type_variable')) {
            e.preventDefault();
            
            const button = e.target;
            const productId = button.getAttribute('data-product_id');
            const quantity = button.getAttribute('data-quantity') || 1;
            
            // Add loading state
            button.classList.add('loading');
            button.innerHTML = '<svg class="animate-spin h-4 w-4 mx-auto" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            
            // AJAX request
            fetch(wc_add_to_cart_params.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'woocommerce_add_to_cart',
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                // Update cart count
                const cartCount = document.querySelector('.cart-count');
                if (cartCount && data.fragments && data.fragments['.cart-count']) {
                    cartCount.outerHTML = data.fragments['.cart-count'];
                }
                
                // Show success message
                button.classList.remove('loading');
                button.innerHTML = 'Added to Cart';
                button.classList.add('added');
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    button.innerHTML = 'Add to Cart';
                    button.classList.remove('added');
                }, 2000);
                
                // Trigger cart update event
                document.body.dispatchEvent(new Event('wc_fragment_refresh'));
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                button.classList.remove('loading');
                button.innerHTML = 'Error - Try Again';
                setTimeout(() => {
                    button.innerHTML = 'Add to Cart';
                }, 2000);
            });
        }
    });
    
    // Product quantity controls
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('quantity-plus')) {
            const input = e.target.previousElementSibling;
            const currentValue = parseInt(input.value) || 1;
            const maxValue = parseInt(input.getAttribute('max')) || 999;
            if (currentValue < maxValue) {
                input.value = currentValue + 1;
                input.dispatchEvent(new Event('change'));
            }
        }
        
        if (e.target.classList.contains('quantity-minus')) {
            const input = e.target.nextElementSibling;
            const currentValue = parseInt(input.value) || 1;
            const minValue = parseInt(input.getAttribute('min')) || 1;
            if (currentValue > minValue) {
                input.value = currentValue - 1;
                input.dispatchEvent(new Event('change'));
            }
        }
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Image lazy loading fallback for older browsers
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
    
    // Enhanced search functionality
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        const searchInput = searchForm.querySelector('input[type="search"]');
        let searchTimeout;
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();
                
                if (query.length > 2) {
                    searchTimeout = setTimeout(() => {
                        // Could add live search suggestions here
                        console.log('Searching for:', query);
                    }, 300);
                }
            });
        }
    }
    
});

// jQuery document ready (for WooCommerce compatibility)
jQuery(document).ready(function($) {
    
    // Update cart fragments on page load
    if (typeof wc_cart_fragments_params !== 'undefined') {
        $(document.body).trigger('wc_fragment_refresh');
    }
    
    // WooCommerce variation form enhancements
    $('.variations_form').each(function() {
        $(this).on('found_variation', function(event, variation) {
            // Handle variation found
            console.log('Variation found:', variation);
        });
        
        $(this).on('reset_data', function() {
            // Handle variation reset
            console.log('Variation reset');
        });
    });
    
    // Enhanced cart updates
    $(document.body).on('updated_cart_totals updated_checkout', function() {
        // Reinitialize any cart-specific JavaScript
        console.log('Cart updated');
    });
    
});
