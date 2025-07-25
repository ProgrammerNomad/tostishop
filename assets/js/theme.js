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
        
        // Store original images for reset functionality
        const originalMainImage = $mainImage.html();
        const originalThumbnails = $thumbnails.map(function() { return $(this).clone(true); }).get();
        
        // Enhanced color swatch functionality with variation integration
        $('.color-swatch input[type="radio"]').on('change', function() {
            // Remove active state from all color swatches
            $('.color-swatch span').removeClass('border-primary').addClass('border-gray-200');
            $('.color-swatch span').css({
                'transform': 'scale(1)',
                'box-shadow': '',
                'border-width': '1px'
            });
            
            // Add active state to selected swatch
            if (this.checked) {
                const $span = $(this).siblings('span');
                $span.removeClass('border-gray-200').addClass('border-primary');
                $span.css({
                    'border-width': '3px',
                    'box-shadow': '0 0 0 2px white, 0 0 0 4px #14175b',
                    'transform': 'scale(1.1)'
                });
            }
            
            // Trigger variation form to check for matches
            $form.trigger('check_variations');
        });

        // Handle variation found event - update images and UI
        $form.on('found_variation', function(event, variation) {
            console.log('Variation found:', variation);
            
            // Update main product image with smooth transition
            if (variation.image && variation.image.src) {
                $mainImage.fadeOut(200, function() {
                    $(this).html(`
                        <img src="${variation.image.src}" 
                             alt="${variation.image.alt || 'Product variation'}"
                             class="w-full h-full object-cover main-product-image"
                             style="display: block;">
                    `);
                    $(this).fadeIn(200);
                });
                
                // Update first thumbnail to show variation image
                const $firstThumbnail = $thumbnails.first();
                if ($firstThumbnail.length) {
                    const thumbnailSrc = variation.image.gallery_thumbnail_src || variation.image.thumb_src || variation.image.src;
                    $firstThumbnail.fadeOut(100, function() {
                        $(this).html(`
                            <img src="${thumbnailSrc}" 
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

        // Handle variation reset - restore original images
        $form.on('reset_data', function() {
            console.log('Variation data reset');
            
            // Restore main image with transition
            $mainImage.fadeOut(200, function() {
                $(this).html(originalMainImage);
                $(this).fadeIn(200);
            });
            
            // Restore original thumbnails
            $thumbnails.each(function(index) {
                if (originalThumbnails[index]) {
                    const $original = $(originalThumbnails[index]).clone(true);
                    $(this).replaceWith($original);
                }
            });
            
            // Reset color swatches
            $('.color-swatch span').removeClass('border-primary').addClass('border-gray-200');
            $('.color-swatch span').css({
                'border-width': '1px',
                'box-shadow': '',
                'transform': 'scale(1)'
            });
            $('.color-swatch input[type="radio"]').prop('checked', false);
            
            // Reset thumbnail states after a brief delay
            setTimeout(function() {
                $('.product-gallery button').removeClass('border-blue-500').addClass('border-gray-200');
                $('.product-gallery button').first().removeClass('border-gray-200').addClass('border-blue-500');
            }, 250);
        });

        // Handle variation selection change for dropdowns
        $form.on('change', '.variation-select', function() {
            // Trigger the variation form to find matching variation
            $form.trigger('check_variations');
        });
    });
    
    // Add global function for manual image updates
    window.updateMainImage = function(imageSrc) {
        const $mainImage = $('.product-gallery .bg-gray-100');
        const $currentButton = $(event.target).closest('button');
        
        // Update main image with transition
        $mainImage.fadeOut(200, function() {
            $(this).html(`
                <img src="${imageSrc}" 
                     alt="Product Image"
                     class="w-full h-full object-cover"
                     style="display: block;">
            `);
            $(this).fadeIn(200);
        });
        
        // Update thumbnail states
        $('.product-gallery button').removeClass('border-blue-500').addClass('border-gray-200');
        $currentButton.removeClass('border-gray-200').addClass('border-blue-500');
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
