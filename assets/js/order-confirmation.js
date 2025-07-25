/**
 * Order Confirmation Page Enhancements
 * TostiShop Theme - Mobile-First Design
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Add success animation to the confirmation icon
    const successIcon = document.querySelector('.order-success-icon');
    if (successIcon) {
        // Add the animation class after a short delay for better effect
        setTimeout(() => {
            successIcon.classList.add('order-success-icon');
        }, 300);
    }

    // Add copy order number functionality
    const orderNumber = document.querySelector('[data-order-number]');
    if (orderNumber) {
        const copyButton = document.createElement('button');
        copyButton.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
        `;
        copyButton.className = 'ml-2 p-1 text-gray-400 hover:text-gray-600 transition-colors duration-200';
        copyButton.title = 'Copy order number';
        
        copyButton.addEventListener('click', async () => {
            try {
                const orderText = orderNumber.textContent.trim();
                await navigator.clipboard.writeText(orderText);
                
                // Show success feedback
                copyButton.innerHTML = `
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
                
                setTimeout(() => {
                    copyButton.innerHTML = `
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    `;
                }, 2000);
            } catch (err) {
                console.log('Could not copy order number');
            }
        });
        
        orderNumber.appendChild(copyButton);
    }

    // Add print functionality
    const addPrintButton = () => {
        const actionButtons = document.querySelector('.order-confirmation-actions');
        if (actionButtons) {
            const printButton = document.createElement('button');
            printButton.innerHTML = `
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Order
            `;
            printButton.className = 'w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-medium text-center flex items-center justify-center hover:bg-gray-200 transition-colors duration-200';
            
            printButton.addEventListener('click', () => {
                window.print();
            });
            
            actionButtons.appendChild(printButton);
        }
    };

    // Add social sharing functionality
    const addSocialShare = () => {
        const orderDetails = document.querySelector('.order-details-card');
        if (orderDetails && navigator.share) {
            const shareButton = document.createElement('button');
            shareButton.innerHTML = `
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                </svg>
                Share Order
            `;
            shareButton.className = 'text-sm text-gray-600 hover:text-gray-800 flex items-center transition-colors duration-200';
            
            shareButton.addEventListener('click', async () => {
                try {
                    await navigator.share({
                        title: 'Order Confirmation',
                        text: 'My order has been confirmed!',
                        url: window.location.href
                    });
                } catch (err) {
                    console.log('Could not share order');
                }
            });
            
            const shareContainer = document.createElement('div');
            shareContainer.className = 'mt-4 pt-4 border-t border-gray-200 text-center';
            shareContainer.appendChild(shareButton);
            orderDetails.appendChild(shareContainer);
        }
    };

    // Smooth scroll to order details when page loads
    const smoothScrollToOrder = () => {
        const orderDetails = document.querySelector('.order-details-card');
        if (orderDetails && window.location.hash === '') {
            setTimeout(() => {
                orderDetails.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }, 500);
        }
    };

    // Add loading states to action buttons
    const addLoadingStates = () => {
        const actionButtons = document.querySelectorAll('.order-action-btn');
        actionButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (this.href && !this.href.includes('#')) {
                    const originalText = this.innerHTML;
                    this.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    `;
                    this.disabled = true;
                }
            });
        });
    };

    // Add refresh functionality for real-time order status
    const addOrderRefresh = () => {
        const refreshButton = document.createElement('button');
        refreshButton.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
        `;
        refreshButton.className = 'absolute top-4 right-4 p-2 text-gray-400 hover:text-gray-600 transition-colors duration-200';
        refreshButton.title = 'Refresh order status';
        
        refreshButton.addEventListener('click', () => {
            location.reload();
        });
        
        const orderCard = document.querySelector('.order-details-card');
        if (orderCard) {
            orderCard.style.position = 'relative';
            orderCard.appendChild(refreshButton);
        }
    };

    // Initialize all enhancements
    addPrintButton();
    addSocialShare();
    smoothScrollToOrder();
    addLoadingStates();
    addOrderRefresh();

    // Add keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + P for print
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            window.print();
        }
        
        // R key for refresh
        if (e.key === 'r' && !e.ctrlKey && !e.metaKey && e.target.tagName !== 'INPUT') {
            location.reload();
        }
    });

    // Add visual feedback for user interactions
    document.querySelectorAll('button, a').forEach(element => {
        element.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.98)';
        }, { passive: true });
        
        element.addEventListener('touchend', function() {
            this.style.transform = 'scale(1)';
        }, { passive: true });
    });

    // Add accessibility enhancements
    const addA11yEnhancements = () => {
        // Announce order confirmation to screen readers
        const announcement = document.createElement('div');
        announcement.textContent = 'Order confirmation page loaded. Your order has been successfully placed.';
        announcement.className = 'sr-only';
        announcement.setAttribute('aria-live', 'polite');
        document.body.appendChild(announcement);

        // Add skip link for keyboard navigation
        const skipLink = document.createElement('a');
        skipLink.textContent = 'Skip to order details';
        skipLink.href = '#order-details';
        skipLink.className = 'sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-primary text-white px-4 py-2 rounded z-50';
        document.body.insertBefore(skipLink, document.body.firstChild);

        // Add order details ID for skip link
        const orderDetails = document.querySelector('.order-details-card');
        if (orderDetails) {
            orderDetails.id = 'order-details';
        }
    };

    addA11yEnhancements();
});

// Service Worker registration for offline functionality (optional)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then((registration) => {
                console.log('SW registered: ', registration);
            })
            .catch((registrationError) => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}
