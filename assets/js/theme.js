// TostiShop Theme JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Product grid view toggle
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const productGrid = document.getElementById('productGrid');
    
    if (gridView && listView && productGrid) {
        gridView.addEventListener('click', function(e) {
            e.preventDefault();
            productGrid.className = 'grid grid-cols-2 md:grid-cols-3 gap-6';
            productGrid.setAttribute('data-view', 'grid');
            
            // Update button states
            gridView.classList.add('bg-primary', 'text-white');
            gridView.classList.remove('bg-gray-100', 'text-gray-600');
            listView.classList.remove('bg-primary', 'text-white');
            listView.classList.add('bg-gray-100', 'text-gray-600');
            
            // Update product items for grid view
            const productItems = productGrid.querySelectorAll('.product-item');
            productItems.forEach(item => {
                item.className = 'product-item group';
            });
            
            localStorage.setItem('tostiShopGridView', 'grid');
        });
        
        listView.addEventListener('click', function(e) {
            e.preventDefault();
            productGrid.className = 'grid grid-cols-1 gap-4';
            productGrid.setAttribute('data-view', 'list');
            
            // Update button states
            listView.classList.add('bg-primary', 'text-white');
            listView.classList.remove('bg-gray-100', 'text-gray-600');
            gridView.classList.remove('bg-primary', 'text-white');
            gridView.classList.add('bg-gray-100', 'text-gray-600');
            
            // Update product items for list view
            const productItems = productGrid.querySelectorAll('.product-item');
            productItems.forEach(item => {
                item.className = 'product-item group flex bg-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-200';
            });
            
            localStorage.setItem('tostiShopGridView', 'list');
        });
        
        // Restore saved view preference
        const savedView = localStorage.getItem('tostiShopGridView');
        if (savedView === 'list') {
            listView.click();
        } else {
            // Default to grid view
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
        const $form = $(this);
        const $gallery = $('.product-gallery');
        const $mainImage = $gallery.find('.bg-gray-100');
        const $thumbnails = $gallery.find('[data-thumbnail]');
        
        // Store original images
        const originalMainImage = $mainImage.html();
        const originalThumbnails = $thumbnails.clone();
        
        $form.on('found_variation', function(event, variation) {
            // Handle variation found - update main product image
            if (variation.image && variation.image.src) {
                // Update main image
                $mainImage.html(`
                    <img src="${variation.image.src}" 
                         alt="${variation.image.alt || ''}"
                         class="w-full h-full object-cover">
                `);
                
                // Update gallery thumbnails if variation has gallery
                if (variation.image.gallery_thumbnail_src) {
                    // Create new thumbnail for variation
                    const $newThumbnail = $(`
                        <button onclick="updateMainImage('${variation.image.src}')" 
                                class="flex-none w-16 h-16 bg-gray-100 rounded border-2 border-blue-500 overflow-hidden">
                            <img src="${variation.image.gallery_thumbnail_src}" 
                                 alt="${variation.image.alt || ''}"
                                 class="w-full h-full object-cover">
                        </button>
                    `);
                    
                    // Reset all thumbnail borders and add to first
                    $thumbnails.removeClass('border-blue-500').addClass('border-gray-200');
                    $thumbnails.first().replaceWith($newThumbnail);
                }
            }
            
            // Update price display if needed
            if (variation.price_html) {
                $('.product-price, .single-product-price').html(variation.price_html);
            }
            
            // Update stock status
            if (variation.is_in_stock !== undefined) {
                const $stockStatus = $('.stock-status');
                if (variation.is_in_stock) {
                    $stockStatus.html(`
                        <div class="flex items-center text-green-600">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium">In Stock</span>
                        </div>
                    `);
                } else {
                    $stockStatus.html(`
                        <div class="flex items-center text-red-600">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium">Out of Stock</span>
                        </div>
                    `);
                }
            }
        });
        
        $form.on('reset_data', function() {
            // Handle variation reset - restore original images
            $mainImage.html(originalMainImage);
            
            // Restore original thumbnails
            $thumbnails.each(function(index) {
                if (originalThumbnails[index]) {
                    $(this).replaceWith(originalThumbnails.eq(index).clone());
                }
            });
            
            // Reset thumbnail states
            $('.product-gallery button').removeClass('border-blue-500').addClass('border-gray-200');
            $('.product-gallery button').first().removeClass('border-gray-200').addClass('border-blue-500');
        });
    });
    
    // Add global function for manual image updates
    window.updateMainImage = function(imageSrc) {
        const $mainImage = $('.product-gallery .bg-gray-100');
        $mainImage.html(`
            <img src="${imageSrc}" 
                 alt="Product Image"
                 class="w-full h-full object-cover">
        `);
        
        // Update thumbnail states
        $('.product-gallery button').removeClass('border-blue-500').addClass('border-gray-200');
        event.target.closest('button').classList.remove('border-gray-200');
        event.target.closest('button').classList.add('border-blue-500');
    };
    
    // Gallery navigation function
    window.showGalleryImage = function(imageIndex) {
        const $mainImageContainer = $('.product-gallery .bg-gray-100');
        const $thumbnails = $('.thumbnail-btn');
        
        // Hide all gallery images
        $('.gallery-image, .main-product-image').hide();
        
        // Reset all thumbnail borders
        $thumbnails.removeClass('border-blue-500').addClass('border-gray-200');
        
        if (imageIndex === 0) {
            // Show main product image
            $('.main-product-image').show();
            $('[data-thumbnail="main"]').removeClass('border-gray-200').addClass('border-blue-500');
        } else {
            // Show specific gallery image
            $(`.gallery-image[data-gallery-index="${imageIndex}"]`).show();
            $(`[data-thumbnail="${imageIndex}"]`).removeClass('border-gray-200').addClass('border-blue-500');
        }
    };
    
    // Enhanced cart updates
    $(document.body).on('updated_cart_totals updated_checkout', function() {
        // Reinitialize any cart-specific JavaScript
        console.log('Cart updated');
    });
    
});
