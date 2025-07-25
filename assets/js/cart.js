/**
 * Enhanced Cart Functionality
 * Handles quantity updates and item removal via AJAX
 */

(function($) {
    'use strict';

    // Cart functionality object
    const TostiShopCart = {
        init: function() {
            this.addQuantityButtons();
            this.bindEvents();
        },

        bindEvents: function() {
            // Handle remove buttons
            $(document).on('click', '.cart-remove-btn', this.handleRemoveItem);
            
            // Handle quantity changes
            $(document).on('change', '.cart .qty', this.handleQuantityChange);
            
            // Handle quantity button clicks
            $(document).on('click', '.quantity .plus, .quantity .minus', this.handleQuantityButtons);
        },

        addQuantityButtons: function() {
            $('.woocommerce .quantity .qty').each(function() {
                const $input = $(this);
                const $wrapper = $input.closest('.quantity');
                
                if ($wrapper.find('.plus, .minus').length > 0) {
                    return; // Already processed
                }
                
                // Create minus button
                const $minusBtn = $('<button type="button" class="minus">−</button>');
                $minusBtn.on('click', function(e) {
                    e.preventDefault();
                    const currentValue = parseInt($input.val()) || 0;
                    const minValue = parseInt($input.attr('min')) || 0;
                    if (currentValue > minValue) {
                        $input.val(currentValue - 1).trigger('change');
                    }
                });
                
                // Create plus button
                const $plusBtn = $('<button type="button" class="plus">+</button>');
                $plusBtn.on('click', function(e) {
                    e.preventDefault();
                    const currentValue = parseInt($input.val()) || 0;
                    const maxValue = parseInt($input.attr('max')) || 9999;
                    if (currentValue < maxValue) {
                        $input.val(currentValue + 1).trigger('change');
                    }
                });
                
                // Insert buttons
                $wrapper.prepend($minusBtn);
                $wrapper.append($plusBtn);
            });
        },

        handleQuantityButtons: function(e) {
            e.preventDefault();
            const $button = $(this);
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
            const $input = $(this);
            const cartItemKey = TostiShopCart.extractCartItemKey($input.attr('name'));
            
            if (!cartItemKey) return;
            
            const quantity = parseInt($input.val()) || 0;
            const $cartItem = $input.closest('.cart-item, [class*="cart_item"]');
            
            TostiShopCart.updateCartItemQuantity(cartItemKey, quantity, $cartItem);
        },

        handleRemoveItem: function(e) {
            e.preventDefault();
            const $button = $(this);
            const cartItemKey = $button.data('cart-item-key');
            const $cartItem = $button.closest('.cart-item, [class*="cart_item"]');
            
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }
            
            TostiShopCart.removeCartItem(cartItemKey, $cartItem);
        },

        updateCartItemQuantity: function(cartItemKey, quantity, $cartItem) {
            if (!cartItemKey) return;
            
            // Show loading state
            $cartItem.css({'opacity': '0.6', 'pointer-events': 'none'});
            
            const data = {
                action: 'tostishop_update_cart_item',
                cart_item_key: cartItemKey,
                quantity: quantity,
                nonce: tostishop_ajax.nonce
            };
            
            $.post(tostishop_ajax.ajax_url, data)
                .done(function(response) {
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
                        TostiShopCart.showNotification('Error updating cart. Please try again.', 'error');
                    }
                })
                .fail(function() {
                    TostiShopCart.showNotification('Error updating cart. Please try again.', 'error');
                })
                .always(function() {
                    // Remove loading state
                    $cartItem.css({'opacity': '1', 'pointer-events': 'auto'});
                });
        },

        removeCartItem: function(cartItemKey, $cartItem) {
            if (!cartItemKey) return;
            
            // Show loading state
            $cartItem.css({'opacity': '0.6', 'pointer-events': 'none'});
            
            const data = {
                action: 'tostishop_remove_cart_item',
                cart_item_key: cartItemKey,
                nonce: tostishop_ajax.nonce
            };
            
            $.post(tostishop_ajax.ajax_url, data)
                .done(function(response) {
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
                        TostiShopCart.showNotification('Error removing item. Please try again.', 'error');
                    }
                })
                .fail(function() {
                    TostiShopCart.showNotification('Error removing item. Please try again.', 'error');
                })
                .always(function() {
                    // Remove loading state
                    $cartItem.css({'opacity': '1', 'pointer-events': 'auto'});
                });
        },

        extractCartItemKey: function(inputName) {
            const match = inputName.match(/cart\[([^\]]+)\]/);
            return match ? match[1] : null;
        },

        updateCartCount: function(count) {
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
        if ($('body').hasClass('woocommerce-cart')) {
            TostiShopCart.init();
        }
    });

    // Make it globally available
    window.TostiShopCart = TostiShopCart;

})(jQuery);
