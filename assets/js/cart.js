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
            this.addQuantityButtons();
            this.bindEvents();
        },

        bindEvents: function() {
            console.log('TostiShopCart: Binding events');
            
            // Handle remove buttons with direct event binding
            $(document).on('click', '.cart-remove-btn', this.handleRemoveItem.bind(this));
            
            // Handle quantity changes - broader selector to catch all quantity inputs
            $(document).on('change', '.qty, input[name*="cart["][name*="][qty]"]', this.handleQuantityChange.bind(this));
            
            // Handle quantity button clicks
            $(document).on('click', '.quantity .plus, .quantity .minus', this.handleQuantityButtons.bind(this));
            
            // Handle cart form submission
            $(document).on('submit', '.woocommerce-cart-form', this.handleCartUpdate.bind(this));
        },

        addQuantityButtons: function() {
            console.log('TostiShopCart: Adding quantity buttons');
            
            // Look for all quantity inputs in the cart
            $('.qty, input[name*="cart["][name*="][qty]"]').each(function() {
                const $input = $(this);
                const $wrapper = $input.closest('.quantity, .flex');
                
                // Skip if already has buttons or if not in cart form
                if ($wrapper.find('.plus, .minus').length > 0 || !$input.closest('.woocommerce-cart-form').length) {
                    return;
                }
                
                // Ensure wrapper has proper styling
                if (!$wrapper.hasClass('quantity')) {
                    $wrapper.addClass('quantity').css({
                        'display': 'flex',
                        'align-items': 'center',
                        'border': '1px solid #d1d5db',
                        'border-radius': '0.5rem',
                        'overflow': 'hidden'
                    });
                }
                
                // Create minus button
                const $minusBtn = $('<button type="button" class="minus">−</button>');
                
                // Create plus button
                const $plusBtn = $('<button type="button" class="plus">+</button>');
                
                // Insert buttons
                $wrapper.prepend($minusBtn);
                $wrapper.append($plusBtn);
                
                console.log('TostiShopCart: Added buttons for quantity input');
            });
        },

        handleQuantityButtons: function(e) {
            e.preventDefault();
            console.log('TostiShopCart: Quantity button clicked');
            
            const $button = $(e.target);
            const $input = $button.siblings('.qty');
            const currentValue = parseInt($input.val()) || 0;
            
            if ($button.hasClass('plus')) {
                const maxValue = parseInt($input.attr('max')) || 9999;
                if (currentValue < maxValue) {
                    $input.val(currentValue + 1).trigger('change');
                }
            } else if ($button.hasClass('minus')) {
                const minValue = parseInt($input.attr('min')) || 0;
                if (currentValue > minValue) {
                    $input.val(currentValue - 1).trigger('change');
                }
            }
        },

        handleQuantityChange: function(e) {
            console.log('TostiShopCart: Quantity changed');
            
            const $input = $(e.target);
            const cartItemKey = this.extractCartItemKey($input.attr('name'));
            
            if (!cartItemKey) {
                console.log('TostiShopCart: No cart item key found');
                return;
            }
            
            const quantity = parseInt($input.val()) || 0;
            const $cartItem = $input.closest('.cart-item, [class*="cart_item"]');
            
            console.log('TostiShopCart: Updating quantity for key:', cartItemKey, 'to:', quantity);
            
            this.updateCartItemQuantity(cartItemKey, quantity, $cartItem);
        },

        handleCartUpdate: function(e) {
            // Let the default form submission happen for non-AJAX updates
            console.log('TostiShopCart: Form submitted');
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
            
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }
            
            this.removeCartItem(cartItemKey, $cartItem);
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
                        TostiShopCart.showNotification('Error updating cart. Please try again.', 'error');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.log('TostiShopCart: AJAX request failed:', xhr, status, error);
                    TostiShopCart.showNotification('Error updating cart. Please try again.', 'error');
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
