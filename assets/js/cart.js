/**
 * Enhanced Cart Functionality
 * Handles quantity updates and item removal via AJAX
 */

(function($) {
    'use strict';

    // Cart functionality object
    const TostiShopCart = {
        init: function() {
            console.log('TostiShopCart: Initializing cart functionality');
            
            // Basic initialization
            TostiShopCart.bindEvents();
            TostiShopCart.addQuantityButtons();
            TostiShopCart.updateShippingDisplay();
            
            console.log('TostiShopCart: Cart initialization complete');
        },

        bindEvents: function() {
            console.log('TostiShopCart: Binding events');
            
            // Handle remove buttons with direct event binding
            $(document).on('click', '.cart-remove-btn', this.handleRemoveItem.bind(this));
            
            // Handle quantity changes - broader selector to catch all quantity inputs
            $(document).on('change', '.qty, input[name*="cart["][name*="][qty]"], .woocommerce-cart-form input[type="number"]', this.handleQuantityChange.bind(this));
            
            // Handle quantity button clicks
            $(document).on('click', '.quantity-controls .plus, .quantity-controls .minus', this.handleQuantityButtons.bind(this));
            
            // Handle shipping method changes
            $(document).on('change', 'input[name^="shipping_method"]', this.handleShippingMethodChange.bind(this));
            
            // Handle cart form submission
            $(document).on('submit', '.woocommerce-cart-form', this.handleCartUpdate.bind(this));
        },

        addModernModal: function() {
            if ($('#tostishop-modal').length === 0) {
                const modalHTML = `
                    <div id="tostishop-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
                        <div class="bg-white rounded-xl p-6 m-4 max-w-md w-full shadow-2xl transform transition-all">
                            <div class="text-center">
                                <div class="mb-4">
                                    <svg class="w-12 h-12 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </div>
                                <h3 id="modal-title" class="text-lg font-semibold text-gray-900 mb-2"></h3>
                                <p id="modal-message" class="text-gray-600 mb-6"></p>
                                <div class="flex space-x-3">
                                    <button id="modal-cancel" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                                    <button id="modal-confirm" class="flex-1 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('body').append(modalHTML);
                
                // Bind modal events
                $('#modal-cancel, #tostishop-modal').on('click', function(e) {
                    if (e.target === this) {
                        TostiShopCart.hideModal();
                    }
                });
            }
        },

        showConfirmationModal: function(title, message, confirmCallback) {
            $('#modal-title').text(title);
            $('#modal-message').text(message);
            $('#tostishop-modal').removeClass('hidden').addClass('flex');
            
            // Remove previous event listeners and add new one
            $('#modal-confirm').off('click').on('click', function() {
                TostiShopCart.hideModal();
                confirmCallback();
            });
        },

        hideModal: function() {
            $('#tostishop-modal').addClass('hidden').removeClass('flex');
        },

        addQuantityButtons: function() {
            console.log('TostiShopCart: Adding quantity buttons');
            
            // Look for all quantity inputs in the cart and add visual feedback
            $('.qty, input[name*="cart["][name*="][qty]"]').each(function() {
                const $input = $(this);
                const $wrapper = $input.closest('.quantity-controls');
                
                if ($wrapper.length > 0) {
                    const $plusBtn = $wrapper.find('.plus');
                    const $minusBtn = $wrapper.find('.minus');
                    
                    // Update button states based on current values
                    TostiShopCart.updateButtonStates($input, $plusBtn, $minusBtn);
                    
                    // Listen for value changes to update button states
                    $input.on('input change', function() {
                        TostiShopCart.updateButtonStates($(this), $plusBtn, $minusBtn);
                    });
                }
            });
        },

        updateButtonStates: function($input, $plusBtn, $minusBtn) {
            const currentValue = parseInt($input.val()) || 0;
            const maxValue = parseInt($input.attr('max')) || 9999;
            const minValue = parseInt($input.attr('min')) || 0;
            const isReadonly = $input.prop('readonly');
            
            // Update plus button state
            if (isReadonly || currentValue >= maxValue) {
                $plusBtn.addClass('opacity-50 cursor-not-allowed').removeClass('hover:bg-gray-100');
                $plusBtn.attr('title', 'Maximum quantity reached');
            } else {
                $plusBtn.removeClass('opacity-50 cursor-not-allowed').addClass('hover:bg-gray-100');
                $plusBtn.attr('title', 'Increase quantity');
            }
            
            // Update minus button state
            if (currentValue <= minValue) {
                $minusBtn.addClass('opacity-50 cursor-not-allowed').removeClass('hover:bg-gray-100');
                $minusBtn.attr('title', 'Minimum quantity reached');
            } else {
                $minusBtn.removeClass('opacity-50 cursor-not-allowed').addClass('hover:bg-gray-100');
                $minusBtn.attr('title', 'Decrease quantity');
            }
        },

        handleQuantityButtons: function(e) {
            e.preventDefault();
            console.log('TostiShopCart: Quantity button clicked');
            
            const $button = $(e.currentTarget);
            const $input = $button.siblings('.qty').length > 0 ? $button.siblings('.qty') : $button.parent().find('.qty');
            
            if ($input.length === 0) {
                console.log('TostiShopCart: No quantity input found');
                return;
            }
            
            const currentValue = parseInt($input.val()) || 0;
            const productName = $input.data('product-name') || 'this product';

            if ($button.hasClass('plus')) {
                const maxValue = parseInt($input.attr('max')) || 9999;
                const stockQty = $input.data('stock-qty');
                
                if (currentValue < maxValue) {
                    $input.val(currentValue + 1).trigger('change');
                } else {
                    // Show stock limit message
                    let message = `Cannot add more ${productName} to cart.`;
                    if (stockQty && stockQty !== 'unlimited') {
                        message = `Only ${maxValue} of "${productName}" available in stock.`;
                    } else {
                        message = `Maximum quantity limit (${maxValue}) reached for "${productName}".`;
                    }
                    TostiShopCart.showNotification(message, 'warning');
                }
            } else if ($button.hasClass('minus')) {
                const minValue = parseInt($input.attr('min')) || 0;
                if (currentValue > minValue) {
                    $input.val(currentValue - 1).trigger('change');
                }
            }
        },        handleQuantityChange: function(e) {
            console.log('TostiShopCart: Quantity changed');
            
            const $input = $(e.target);
            
            // Validate quantity limits before processing
            const quantity = parseInt($input.val()) || 0;
            const maxValue = parseInt($input.attr('max')) || 9999;
            const minValue = parseInt($input.attr('min')) || 0;
            const productName = $input.data('product-name') || 'this product';
            
            // Check limits and provide feedback
            if (quantity > maxValue) {
                $input.val(maxValue);
                const stockQty = $input.data('stock-qty');
                let message = `Maximum quantity limit (${maxValue}) reached for "${productName}".`;
                if (stockQty && stockQty !== 'unlimited') {
                    message = `Only ${maxValue} of "${productName}" available in stock.`;
                }
                TostiShopCart.showNotification(message, 'warning');
                return;
            }
            
            if (quantity < minValue) {
                $input.val(minValue);
                return;
            }
            
            // Try to get cart item key from data attribute first, then from name attribute
            let cartItemKey = $input.data('cart-item-key') || $input.attr('data-cart-item-key');
            if (!cartItemKey) {
                cartItemKey = this.extractCartItemKey($input.attr('name'));
            }
            
            if (!cartItemKey) {
                console.log('TostiShopCart: No cart item key found');
                return;
            }
            
            const $cartItem = $input.closest('.cart-item, [class*="cart_item"]');
            
            console.log('TostiShopCart: Updating quantity for key:', cartItemKey, 'to:', quantity);
            
            this.updateCartItemQuantity(cartItemKey, quantity, $cartItem);
        },

        handleCartUpdate: function(e) {
            // Let the default form submission happen for non-AJAX updates
            console.log('TostiShopCart: Form submitted');
        },

        handleShippingMethodChange: function(e) {
            console.log('TostiShopCart: Shipping method changed');
            
            const $input = $(e.target);
            const shippingMethod = $input.val();
            
            // Show loading state on shipping methods container
            const $container = $input.closest('.woocommerce-shipping-totals, .cart-totals');
            $container.css({'opacity': '0.6', 'pointer-events': 'none'});
            
            // Trigger WooCommerce's shipping calculation
            $('body').trigger('update_checkout');
            
            // Also update cart totals for cart page
            const data = {
                action: 'tostishop_update_shipping_method',
                shipping_method: shippingMethod,
                nonce: tostishop_ajax.nonce
            };
            
            $.post(tostishop_ajax.ajax_url, data)
                .done(function(response) {
                    console.log('TostiShopCart: Shipping method updated:', response);
                    
                    if (response.success) {
                        // Update cart totals
                        TostiShopCart.updateCartTotals(response.data);
                        TostiShopCart.showNotification('Shipping method updated!', 'success');
                    } else {
                        TostiShopCart.showNotification('Refreshing page to update shipping...', 'info');
                        // Fallback: reload page
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                })
                .fail(function(xhr, status, error) {
                    console.log('TostiShopCart: Shipping update failed:', xhr, status, error);
                    TostiShopCart.showNotification('Refreshing page to update shipping...', 'info');
                    // Fallback: reload page
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                })
                .always(function() {
                    // Remove loading state
                    $container.css({'opacity': '1', 'pointer-events': 'auto'});
                });
        },

        updateShippingDisplay: function() {
            console.log('TostiShopCart: Updating shipping display');
            
            // Find shipping method containers and ensure they're properly styled
            const $shippingMethods = $('.woocommerce-shipping-methods, .shipping-methods');
            
            if ($shippingMethods.length > 0) {
                $shippingMethods.find('input[type="radio"]').each(function() {
                    const $input = $(this);
                    const $label = $input.closest('label');
                    
                    // Apply standardized styling if not already applied
                    if (!$input.hasClass('radio-standard')) {
                        $input.addClass('radio-standard');
                    }
                    
                    // Ensure proper label styling
                    if ($label.length > 0 && !$label.hasClass('radio-option')) {
                        $label.addClass('radio-option');
                    }
                });
                
                console.log('TostiShopCart: Shipping display updated');
            } else {
                console.log('TostiShopCart: No shipping methods found');
            }
        },

        handleRemoveItem: function(e) {
            e.preventDefault();
            console.log('TostiShopCart: Remove button clicked');
            
            const $button = $(e.target).closest('.cart-remove-btn');
            const cartItemKey = $button.data('cart_item_key') || $button.attr('data-cart_item_key');
            const $cartItem = $button.closest('.cart-item, [class*="cart_item"]');
            
            console.log('TostiShopCart: Removing item with key:', cartItemKey);
            
            if (!cartItemKey) {
                console.log('TostiShopCart: No cart item key found for removal');
                return;
            }
            
            // Show modern confirmation modal
            this.showConfirmationModal(
                'Remove Item',
                'Are you sure you want to remove this item from your cart?',
                () => {
                    this.removeCartItem(cartItemKey, $cartItem);
                }
            );
        },

        updateCartItemQuantity: function(cartItemKey, quantity, $cartItem) {
            if (!cartItemKey) {
                console.log('TostiShopCart: No cart item key provided for update');
                return;
            }
            
            console.log('TostiShopCart: Making AJAX request to update quantity');
            
            // Show loading state
            $cartItem.css({'opacity': '0.6', 'pointer-events': 'none'});
            
            const data = {
                action: 'tostishop_update_cart_item',
                cart_item_key: cartItemKey,
                quantity: quantity,
                nonce: tostishop_ajax.nonce
            };
            
            console.log('TostiShopCart: AJAX data:', data);
            
            $.post(tostishop_ajax.ajax_url, data)
                .done(function(response) {
                    console.log('TostiShopCart: AJAX response:', response);
                    
                    if (response.success) {
                        // Update cart count
                        TostiShopCart.updateCartCount(response.data.cart_count);
                        
                        // If quantity is 0, remove the item
                        if (quantity === 0) {
                            $cartItem.fadeOut(300, function() {
                                $(this).remove();
                                if (response.data.cart_count === 0) {
                                    location.reload();
                                }
                            });
                        } else {
                            // Update cart totals
                            TostiShopCart.updateCartTotals(response.data);
                            TostiShopCart.showNotification('Cart updated successfully!', 'success');
                        }
                    } else {
                        console.log('TostiShopCart: AJAX request failed:', response);
                        TostiShopCart.showNotification('Updating cart using page refresh...', 'info');
                        // Fallback: Submit the form normally
                        setTimeout(() => {
                            $('.woocommerce-cart-form').submit();
                        }, 1000);
                    }
                })
                .fail(function(xhr, status, error) {
                    console.log('TostiShopCart: AJAX request failed:', xhr, status, error);
                    TostiShopCart.showNotification('Updating cart using page refresh...', 'info');
                    // Fallback: Submit the form normally
                    setTimeout(() => {
                        $('.woocommerce-cart-form').submit();
                    }, 1000);
                })
                .always(function() {
                    // Remove loading state
                    $cartItem.css({'opacity': '1', 'pointer-events': 'auto'});
                });
        },

        removeCartItem: function(cartItemKey, $cartItem) {
            if (!cartItemKey) {
                console.log('TostiShopCart: No cart item key provided for removal');
                return;
            }
            
            console.log('TostiShopCart: Making AJAX request to remove item');
            
            // Show loading state
            $cartItem.css({'opacity': '0.6', 'pointer-events': 'none'});
            
            const data = {
                action: 'tostishop_remove_cart_item',
                cart_item_key: cartItemKey,
                nonce: tostishop_ajax.nonce
            };
            
            console.log('TostiShopCart: AJAX data:', data);
            
            $.post(tostishop_ajax.ajax_url, data)
                .done(function(response) {
                    console.log('TostiShopCart: AJAX response:', response);
                    
                    if (response.success) {
                        // Update cart count
                        TostiShopCart.updateCartCount(response.data.cart_count);
                        
                        // Remove item with animation
                        $cartItem.css('transform', 'translateX(-100%)');
                        setTimeout(function() {
                            $cartItem.remove();
                            
                            // Reload if cart is empty
                            if (response.data.cart_count === 0) {
                                location.reload();
                            } else {
                                // Update cart totals
                                TostiShopCart.updateCartTotals(response.data);
                            }
                        }, 300);
                        
                        TostiShopCart.showNotification('Item removed from cart!', 'success');
                    } else {
                        console.log('TostiShopCart: AJAX request failed:', response);
                        TostiShopCart.showNotification('Error removing item. Please try again.', 'error');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.log('TostiShopCart: AJAX request failed:', xhr, status, error);
                    TostiShopCart.showNotification('Error removing item. Please try again.', 'error');
                })
                .always(function() {
                    // Remove loading state
                    $cartItem.css({'opacity': '1', 'pointer-events': 'auto'});
                });
        },

        extractCartItemKey: function(inputName) {
            if (!inputName) return null;
            
            const match = inputName.match(/cart\[([^\]]+)\]/);
            const key = match ? match[1] : null;
            console.log('TostiShopCart: Extracted cart key from', inputName, ':', key);
            return key;
        },

        updateCartCount: function(count) {
            console.log('TostiShopCart: Updating cart count to:', count);
            
            $('[data-cart-count], .cart-count').each(function() {
                const $el = $(this);
                $el.text(count);
                if (count > 0) {
                    $el.removeClass('hidden');
                } else {
                    $el.addClass('hidden');
                }
            });
        },

        updateCartTotals: function(data) {
            console.log('TostiShopCart: Updating cart totals:', data);
            
            // Update subtotal
            if (data.cart_subtotal) {
                $('[data-cart-subtotal]').html(data.cart_subtotal);
            }
            
            // Update total
            if (data.cart_total) {
                $('[data-cart-total]').html(data.cart_total);
            }
        },

        showNotification: function(message, type) {
            console.log('TostiShopCart: Showing notification:', message, type);
            
            // Try to use existing notification system
            if (window.tostishopNotifications) {
                if (type === 'success') {
                    window.tostishopNotifications.success(message);
                } else {
                    window.tostishopNotifications.error(message);
                }
                return;
            }
            
            // Fallback notification
            const $notification = $('<div>')
                .addClass('fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300')
                .addClass(type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white')
                .text(message);
            
            $('body').append($notification);
            
            // Auto remove after 3 seconds
            setTimeout(function() {
                $notification.css({
                    'opacity': '0',
                    'transform': 'translateX(100%)'
                });
                setTimeout(function() {
                    $notification.remove();
                }, 300);
            }, 3000);
        },

        showConfirmationModal: function(title, message, onConfirm) {
            // Remove any existing modals
            $('.tostishop-modal').remove();
            
            const modalHTML = `
                <div class="tostishop-modal fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity modal-overlay" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="modal-content inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">${title}</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">${message}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                <button type="button" class="modal-confirm w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                                    Confirm
                                </button>
                                <button type="button" class="modal-cancel mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('body').append(modalHTML);
            
            const $modal = $('.tostishop-modal');
            
            // Show modal with animation
            setTimeout(() => {
                $modal.find('.modal-overlay').addClass('opacity-100');
                $modal.find('.modal-content').addClass('opacity-100 translate-y-0 sm:scale-100');
            }, 10);
            
            // Handle confirm
            $modal.find('.modal-confirm').on('click', function() {
                TostiShopCart.hideModal();
                if (onConfirm) onConfirm();
            });
            
            // Handle cancel and overlay click
            $modal.find('.modal-cancel, .modal-overlay').on('click', function() {
                TostiShopCart.hideModal();
            });
            
            // Handle escape key
            $(document).on('keydown.modal', function(e) {
                if (e.keyCode === 27) {
                    TostiShopCart.hideModal();
                }
            });
        },

        hideModal: function() {
            const $modal = $('.tostishop-modal');
            if ($modal.length) {
                $modal.find('.modal-overlay').removeClass('opacity-100');
                $modal.find('.modal-content').removeClass('opacity-100 translate-y-0 sm:scale-100');
                setTimeout(() => {
                    $modal.remove();
                }, 300);
            }
            $(document).off('keydown.modal');
        },

        updateCartTotals: function(data) {
            if (data && data.fragments) {
                // Update WooCommerce fragments
                $.each(data.fragments, function(key, value) {
                    $(key).replaceWith(value);
                });
            }
            
            // Update cart count in header if available
            if (data.cart_count !== undefined) {
                $('.cart-count, [data-cart-count]').text(data.cart_count);
                
                // Hide cart count if empty
                if (data.cart_count === 0) {
                    $('.cart-count, [data-cart-count]').addClass('hidden');
                } else {
                    $('.cart-count, [data-cart-count]').removeClass('hidden');
                }
            }
            
            // Update cart total displays
            if (data.cart_total !== undefined) {
                $('.cart-total, [data-cart-total]').html(data.cart_total);
            }
            
            // Update cart subtotal displays  
            if (data.cart_subtotal !== undefined) {
                $('.cart-subtotal, [data-cart-subtotal]').html(data.cart_subtotal);
            }
            
            // Trigger custom event for other scripts
            $(document.body).trigger('tostishop_cart_updated', [data]);
        },

        updateCartCount: function(count) {
            $('.cart-count, [data-cart-count]').text(count);
            
            if (count === 0) {
                $('.cart-count, [data-cart-count]').addClass('hidden');
            } else {
                $('.cart-count, [data-cart-count]').removeClass('hidden');
            }
        },

        extractCartItemKey: function(inputName) {
            if (!inputName) return null;
            
            const match = inputName.match(/cart\[([^\]]+)\]/);
            const key = match ? match[1] : null;
            
            console.log('TostiShopCart: Extracted cart item key:', key, 'from name:', inputName);
            
            return key;
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        console.log('TostiShopCart: Document ready');
        
        if ($('body').hasClass('woocommerce-cart')) {
            console.log('TostiShopCart: Cart page detected, initializing');
            TostiShopCart.init();
        } else {
            console.log('TostiShopCart: Not a cart page');
        }
    });

    // Make it globally available
    window.TostiShopCart = TostiShopCart;

})(jQuery);
