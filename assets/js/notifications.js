/**
 * TostiShop Notification System
 * Displays user-friendly notifications for cart actions, form submissions, etc.
 */

class TostiShopNotifications {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        this.createContainer();
        this.bindEvents();
    }

    createContainer() {
        // Create notification container if it doesn't exist
        if (!document.getElementById('tostishop-notifications')) {
            this.container = document.createElement('div');
            this.container.id = 'tostishop-notifications';
            this.container.className = 'fixed top-4 right-4 z-[9999] space-y-2 max-w-sm';
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('tostishop-notifications');
        }
    }

    show(message, type = 'success', duration = 4000, actions = null) {
        const notification = this.createNotification(message, type, actions);
        this.container.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full', 'opacity-0');
            notification.classList.add('translate-x-0', 'opacity-100');
        }, 50);

        // Auto-remove after duration (unless it has actions)
        if (!actions && duration > 0) {
            setTimeout(() => {
                this.hide(notification);
            }, duration);
        }

        return notification;
    }

    createNotification(message, type, actions) {
        const notification = document.createElement('div');
        notification.className = `
            transform translate-x-full opacity-0 transition-all duration-300 ease-out
            p-4 rounded-lg shadow-lg border-l-4 max-w-sm
            ${this.getTypeClasses(type)}
        `;

        let actionsHtml = '';
        if (actions) {
            actionsHtml = `
                <div class="mt-3 flex space-x-2">
                    ${actions.map(action => `
                        <button 
                            class="px-3 py-1 text-xs font-medium rounded ${action.class || 'bg-white bg-opacity-20 text-white hover:bg-opacity-30'}"
                            onclick="${action.onClick || ''}"
                            data-notification-action="${action.action || ''}"
                        >
                            ${action.text}
                        </button>
                    `).join('')}
                </div>
            `;
        }

        notification.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    ${this.getIcon(type)}
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium">${message}</p>
                    ${actionsHtml}
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button 
                        class="inline-flex text-opacity-70 hover:text-opacity-100 transition-opacity duration-200"
                        onclick="tostishopNotifications.hide(this.closest('.transform'))"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;

        return notification;
    }

    getTypeClasses(type) {
        const classes = {
            success: 'bg-green-500 border-green-600 text-white',
            error: 'bg-red-500 border-red-600 text-white',
            warning: 'bg-yellow-500 border-yellow-600 text-white',
            info: 'bg-blue-500 border-blue-600 text-white',
            cart: 'bg-primary border-navy-600 text-white'
        };
        return classes[type] || classes.info;
    }

    getIcon(type) {
        const icons = {
            success: `
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            `,
            error: `
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            `,
            warning: `
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            `,
            info: `
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            `,
            cart: `
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                </svg>
            `
        };
        return icons[type] || icons.info;
    }

    hide(notification) {
        if (!notification) return;
        
        notification.classList.remove('translate-x-0', 'opacity-100');
        notification.classList.add('translate-x-full', 'opacity-0');

        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }

    // Convenience methods
    success(message, duration = 4000, actions = null) {
        return this.show(message, 'success', duration, actions);
    }

    error(message, duration = 6000, actions = null) {
        return this.show(message, 'error', duration, actions);
    }

    warning(message, duration = 5000, actions = null) {
        return this.show(message, 'warning', duration, actions);
    }

    info(message, duration = 4000, actions = null) {
        return this.show(message, 'info', duration, actions);
    }

    cart(message, actions = null) {
        return this.show(message, 'cart', 5000, actions);
    }

    bindEvents() {
        // Listen for cart events
        document.addEventListener('click', (e) => {
            // Handle notification actions
            if (e.target.hasAttribute('data-notification-action')) {
                const action = e.target.getAttribute('data-notification-action');
                const notification = e.target.closest('.transform');
                
                switch (action) {
                    case 'view-cart':
                        window.location.href = wc_cart_fragments_params?.cart_url || '/cart';
                        break;
                    case 'continue-shopping':
                        this.hide(notification);
                        break;
                    case 'checkout':
                        window.location.href = wc_cart_fragments_params?.checkout_url || '/checkout';
                        break;
                }
            }
        });

        // Listen for WooCommerce events
        document.addEventListener('added_to_cart', (e) => {
            const productName = e.detail?.product_name || 'Product';
            this.cart(`${productName} added to cart!`, [
                {
                    text: 'View Cart',
                    action: 'view-cart',
                    class: 'bg-white bg-opacity-20 text-white hover:bg-opacity-30'
                },
                {
                    text: 'Continue Shopping',
                    action: 'continue-shopping',
                    class: 'bg-transparent border border-white border-opacity-30 text-white hover:bg-white hover:bg-opacity-10'
                }
            ]);
        });

        // Listen for form submissions
        document.addEventListener('wc_fragments_refreshed', () => {
            // Update cart count in notifications if needed
        });
    }
}

// Initialize notifications
window.tostishopNotifications = new TostiShopNotifications();

// Global convenience function
window.showNotification = function(message, type = 'success', duration = 4000, actions = null) {
    return window.tostishopNotifications.show(message, type, duration, actions);
};
