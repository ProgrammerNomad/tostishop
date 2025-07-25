/**
 * TostiShop Theme JavaScript
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
                
                // Show notification with actions
                const productName = button.closest('.product-item')?.querySelector('h3')?.textContent || 
                                  button.closest('[data-product-id]')?.querySelector('.product-title')?.textContent || 
                                  'Product';
                
                // Trigger custom event for notifications
                $(document).trigger('tostishop_product_added_to_cart', [productName]);
                
                // Also trigger WooCommerce event if available
                $(document.body).trigger('added_to_cart', [null, null, button]);
                
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
    
    // Enhanced modal functionality for product quickview
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
    
    // Global image update function for product variations
    window.updateMainImage = function(imageSrc) {
        const mainImage = document.querySelector('.product-main-image img');
        if (mainImage && imageSrc) {
            mainImage.src = imageSrc;
        }
    };
    
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
    
    // Enhanced search functionality - FIXED VERSION
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
        
        // Store original images for reset functionality
        const originalMainImage = $mainImage.html();
        const originalThumbnails = $thumbnails.map(function() { return $(this).clone(true); }).get();
        
        // Enhanced color swatch functionality with variation integration
        $form.on('woocommerce_variation_has_changed', function() {
            const chosenAttributes = $form.find('select').serializeArray();
            
            // Update color swatches based on available variations
            $('.color-swatch').each(function() {
                const $swatch = $(this);
                const attributeName = $swatch.data('attribute');
                const attributeValue = $swatch.data('value');
                
                // Check if this combination is available
                const isAvailable = checkVariationAvailability(chosenAttributes, attributeName, attributeValue);
                
                if (isAvailable) {
                    $swatch.removeClass('opacity-50 cursor-not-allowed');
                    $swatch.addClass('cursor-pointer');
                } else {
                    $swatch.addClass('opacity-50 cursor-not-allowed');
                    $swatch.removeClass('cursor-pointer');
                }
            });
        });
        
        // Enhanced variation selection handling
        $form.on('found_variation', function(event, variation) {
            // Update main product image if variation has image
            if (variation.image && variation.image.src) {
                const $newMainImage = $(`
                    <img src="${variation.image.src}" 
                         alt="${variation.image.alt || 'Product variation'}"
                         class="w-full h-full object-cover">
                `);
                $mainImage.html($newMainImage);
                
                // Update gallery thumbnails if available
                if (variation.image.gallery_thumbnail_src) {
                    const $firstThumbnail = $thumbnails.first();
                    
                    $firstThumbnail.fadeOut(200, function() {
                        $(this).html(`
                            <img src="${variation.image.gallery_thumbnail_src}" 
                                 alt="${variation.image.alt || 'Product variation'}"
                                 class="w-full h-full object-cover">
                        `);
                        
                        // Update click handler for thumbnail
                        $(this).attr('onclick', `updateMainImage('${variation.image.src}')`);
                        $(this).fadeIn(100);
                    });
                }
                
                // Reset all thumbnail borders and highlight the first one
                $thumbnails.removeClass('border-blue-500').addClass('border-gray-200');
                $firstThumbnail.removeClass('border-gray-200').addClass('border-blue-500');
            }
            
            // Update price display
            if (variation.price_html) {
                $('.product-price, .single-product-price, .woocommerce-Price-amount').html(variation.price_html);
            }
            
            // Update stock status with better styling
            if (variation.is_in_stock !== undefined) {
                const $stockStatus = $('.stock-status');
                if (variation.is_in_stock) {
                    $stockStatus.html(`
                        <div class="flex items-center text-green-600">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium">In Stock</span>
                        </div>
                    `);
                } else {
                    $stockStatus.html(`
                        <div class="flex items-center text-red-600">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium">Out of Stock</span>
                        </div>
                    `);
                }
            }

            // Update availability text
            if (variation.availability_html) {
                $('.woocommerce-variation-availability').html(variation.availability_html);
            }
        });
        
        // Reset to original images when no variation is selected
        $form.on('reset_data', function() {
            $mainImage.html(originalMainImage);
            
            $thumbnails.each(function(index) {
                if (originalThumbnails[index]) {
                    $(this).replaceWith(originalThumbnails[index]);
                }
            });
            
            // Reset thumbnail highlighting
            $thumbnails.removeClass('border-blue-500').addClass('border-gray-200');
            $thumbnails.first().removeClass('border-gray-200').addClass('border-blue-500');
        });
    });
    
    // Enhanced product gallery with keyboard navigation
    let currentImageIndex = 0;
    const $galleryImages = $('.product-gallery [data-thumbnail]');
    
    // Keyboard navigation for product gallery
    $(document).on('keydown', function(e) {
        if ($('.product-gallery').length > 0) {
            if (e.key === 'ArrowRight') {
                e.preventDefault();
                nextImage();
            } else if (e.key === 'ArrowLeft') {
                e.preventDefault();
                previousImage();
            }
        }
    });
    
    // Gallery navigation functions
    window.nextImage = function() {
        if ($galleryImages.length > 0) {
            currentImageIndex = (currentImageIndex + 1) % $galleryImages.length;
            $galleryImages.eq(currentImageIndex).click();
        }
    };
    
    window.previousImage = function() {
        if ($galleryImages.length > 0) {
            currentImageIndex = currentImageIndex === 0 ? $galleryImages.length - 1 : currentImageIndex - 1;
            $galleryImages.eq(currentImageIndex).click();
        }
    };
    
    // Update current image index when thumbnail is clicked
    $galleryImages.on('click', function() {
        currentImageIndex = $galleryImages.index(this);
        
        // Update thumbnail highlighting
        $galleryImages.removeClass('border-blue-500').addClass('border-gray-200');
        $(this).removeClass('border-gray-200').addClass('border-blue-500');
        
        // Update data-thumbnail attribute for proper tracking
        const imageIndex = $(this).data('thumbnail');
        if (imageIndex !== undefined) {
            $(`[data-thumbnail="${imageIndex}"]`).removeClass('border-gray-200').addClass('border-blue-500');
        }
    };
    
    // Enhanced cart updates and WooCommerce integration
    $(document.body).on('updated_cart_totals updated_checkout', function() {
        console.log('Cart updated');
    });

    // Listen for WooCommerce add to cart events
    $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
        const productName = $button.closest('.product-item')?.find('h3')?.text() || 
                          $button.closest('[data-product-id]')?.find('.product-title')?.text() || 
                          'Product';
        
        if (window.tostishopNotifications) {
            window.tostishopNotifications.cart(`${productName} added to cart!`, [
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
            window.tostishopNotifications.cart(`${productName} added to cart!`, [
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

// Helper function to check variation availability
function checkVariationAvailability(chosenAttributes, attributeName, attributeValue) {
    // This would typically check against the available_variations data
    // For now, we'll return true as a placeholder
    return true;
}
