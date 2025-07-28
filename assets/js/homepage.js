/**
 * TostiShop Homepage JavaScript
 * 
 * Handles all homepage interactions including product sliders,
 * newsletter signup, Firebase authentication, and animations
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize all homepage features
    initScrollAnimations();
    initProductSliders();
    initTodaysDealsSwiper(); // Add Swiper initialization
    initNewsletterForm();
    initFirebaseLogin();
    initCategoryFilters();
    initLazyLoading();
    
    /**
     * Scroll-triggered animations
     */
    function initScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, observerOptions);
        
        // Observe all fade-in elements
        document.querySelectorAll('.fade-in-up').forEach(el => {
            observer.observe(el);
        });
    }
    
    /**
     * Product slider functionality
     */
    function initProductSliders() {
        const sliders = document.querySelectorAll('.product-slider');
        
        sliders.forEach(slider => {
            const container = slider.querySelector('.product-grid');
            const prevBtn = slider.querySelector('.slider-prev');
            const nextBtn = slider.querySelector('.slider-next');
            
            if (!container || !prevBtn || !nextBtn) return;
            
            let currentScroll = 0;
            const scrollAmount = 280; // Width of one product card + gap
            
            prevBtn.addEventListener('click', () => {
                currentScroll = Math.max(0, currentScroll - scrollAmount);
                container.scrollTo({
                    left: currentScroll,
                    behavior: 'smooth'
                });
                updateSliderButtons();
            });
            
            nextBtn.addEventListener('click', () => {
                const maxScroll = container.scrollWidth - container.clientWidth;
                currentScroll = Math.min(maxScroll, currentScroll + scrollAmount);
                container.scrollTo({
                    left: currentScroll,
                    behavior: 'smooth'
                });
                updateSliderButtons();
            });
            
            function updateSliderButtons() {
                const maxScroll = container.scrollWidth - container.clientWidth;
                prevBtn.disabled = currentScroll <= 0;
                nextBtn.disabled = currentScroll >= maxScroll;
                
                prevBtn.classList.toggle('opacity-50', currentScroll <= 0);
                nextBtn.classList.toggle('opacity-50', currentScroll >= maxScroll);
            }
            
            // Update on container scroll (for touch/swipe)
            container.addEventListener('scroll', () => {
                currentScroll = container.scrollLeft;
                updateSliderButtons();
            });
            
            // Initialize button states
            updateSliderButtons();
        });
    }
    
    /**
     * Today's Deals Swiper Initialization - Amazon Style
     */
    function initTodaysDealsSwiper() {
        // Check if Swiper is loaded and element exists
        if (typeof Swiper !== 'undefined' && document.querySelector('.deals-swiper')) {
            const dealsSwiper = new Swiper('.deals-swiper', {
                // Basic settings for Amazon-style slider
                slidesPerView: 2.3, // Mobile: 2 full + partial 3rd
                spaceBetween: 15,
                loop: true,
                centeredSlides: false,
                freeMode: false,
                
                // Responsive breakpoints - Amazon style with partial products
                breakpoints: {
                    // Mobile (>= 480px)
                    480: {
                        slidesPerView: 2.4, // 2 full + larger partial 3rd
                        spaceBetween: 15,
                    },
                    // Tablet (>= 640px) 
                    640: {
                        slidesPerView: 3.3, // 3 full + partial 4th
                        spaceBetween: 20,
                    },
                    // Desktop small (>= 768px)
                    768: {
                        slidesPerView: 3.5, // 3 full + larger partial 4th
                        spaceBetween: 20,
                    },
                    // Desktop medium (>= 1024px)
                    1024: {
                        slidesPerView: 4.3, // 4 full + partial 5th (as requested)
                        spaceBetween: 25,
                    },
                    // Desktop large (>= 1280px)
                    1280: {
                        slidesPerView: 4.4, // 4 full + larger partial 5th
                        spaceBetween: 25,
                    },
                    // Extra large (>= 1536px)
                    1536: {
                        slidesPerView: 4.5, // 4 full + half of 5th
                        spaceBetween: 30,
                    }
                },
                
                // Navigation arrows
                navigation: {
                    nextEl: '.deals-next',
                    prevEl: '.deals-prev',
                },
                
                // Pagination dots
                pagination: {
                    el: '.deals-pagination',
                    clickable: true,
                    renderBullet: function (index, className) {
                        return '<span class="' + className + '"></span>';
                    },
                },
                
                // Autoplay settings
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                    reverseDirection: false,
                },
                
                // Effects and performance
                effect: 'slide',
                speed: 600,
                
                // Additional Amazon-style settings
                grabCursor: true,
                watchSlidesProgress: true,
                watchSlidesVisibility: true,
                preventInteractionOnTransition: false,
                touchRatio: 1,
                touchAngle: 45,
                simulateTouch: true,
                allowTouchMove: true,
                
                // Smooth scrolling behavior
                freeMode: {
                    enabled: false,
                    sticky: true,
                },
                
                // Callbacks
                on: {
                    init: function () {
                        console.log('Today\'s Deals Amazon-Style Swiper initialized');
                        // Add loaded class for CSS animations
                        document.querySelector('.deals-swiper').classList.add('swiper-loaded');
                    },
                    slideChange: function () {
                        // Optional: Track slide changes for analytics
                        if (typeof gtag !== 'undefined') {
                            gtag('event', 'deals_slider_interaction', {
                                'event_category': 'engagement',
                                'slide_index': this.activeIndex
                            });
                        }
                    },
                    touchStart: function() {
                        // Pause autoplay on touch
                        this.autoplay.stop();
                    },
                    touchEnd: function() {
                        // Resume autoplay after touch
                        setTimeout(() => {
                            this.autoplay.start();
                        }, 3000);
                    }
                }
            });
            
            // Enhanced hover controls for Amazon-style experience
            const swiperContainer = document.querySelector('.deals-swiper');
            if (swiperContainer) {
                let hoverTimeout;
                
                swiperContainer.addEventListener('mouseenter', () => {
                    dealsSwiper.autoplay.stop();
                    clearTimeout(hoverTimeout);
                });
                
                swiperContainer.addEventListener('mouseleave', () => {
                    hoverTimeout = setTimeout(() => {
                        dealsSwiper.autoplay.start();
                    }, 1000);
                });
                
                // Add smooth scroll behavior on navigation click
                const navButtons = document.querySelectorAll('.deals-next, .deals-prev');
                navButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        // Add click feedback
                        button.style.transform = 'translateY(-50%) scale(0.95)';
                        setTimeout(() => {
                            button.style.transform = 'translateY(-50%) scale(1.1)';
                        }, 100);
                        setTimeout(() => {
                            button.style.transform = 'translateY(-50%)';
                        }, 200);
                    });
                });
            }
            
            // Keyboard navigation support
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    dealsSwiper.slidePrev();
                } else if (e.key === 'ArrowRight') {
                    dealsSwiper.slideNext();
                }
            });
            
        } else {
            console.warn('Swiper not loaded or .deals-swiper element not found');
        }
    }

    /**
     * Newsletter form handling
     */
    function initNewsletterForm() {
        const form = document.getElementById('newsletter-form');
        const emailInput = document.getElementById('newsletter-email');
        const submitBtn = form?.querySelector('button[type="submit"]');
        
        if (!form || !emailInput || !submitBtn) return;
        
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = emailInput.value.trim();
            
            if (!isValidEmail(email)) {
                showNotification('Please enter a valid email address', 'error');
                return;
            }
            
            // Show loading state
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Subscribing...';
            
            try {
                const response = await fetch(tostishop_ajax.ajax_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'newsletter_signup',
                        email: email,
                        nonce: tostishop_ajax.nonce
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Thank you for subscribing!', 'success');
                    emailInput.value = '';
                } else {
                    showNotification(result.data || 'Subscription failed. Please try again.', 'error');
                }
            } catch (error) {
                console.error('Newsletter signup error:', error);
                showNotification('Network error. Please try again.', 'error');
            } finally {
                // Restore button state
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    }
    
    /**
     * Firebase OTP login initialization
     */
    function initFirebaseLogin() {
        const firebaseBtn = document.getElementById('firebase-login');
        
        if (!firebaseBtn) return;
        
        firebaseBtn.addEventListener('click', function() {
            // Check if Firebase is initialized
            if (typeof firebase === 'undefined') {
                showNotification('Mobile login service is not available', 'error');
                return;
            }
            
            // For now, show a modal or redirect to login page
            // This would integrate with your Firebase setup
            showNotification('Mobile login feature coming soon!', 'info');
        });
    }
    
    /**
     * Category filter functionality
     */
    function initCategoryFilters() {
        const categoryCards = document.querySelectorAll('.category-card');
        
        categoryCards.forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                
                const categoryId = this.dataset.categoryId;
                const categoryName = this.querySelector('h3')?.textContent;
                
                if (categoryId) {
                    // Add loading state
                    this.classList.add('loading-skeleton');
                    
                    // Navigate to category page
                    window.location.href = this.href;
                }
            });
        });
    }
    
    /**
     * Lazy loading for product images
     */
    function initLazyLoading() {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    const src = img.dataset.src;
                    
                    if (src) {
                        img.src = src;
                        img.classList.remove('loading-skeleton');
                        img.classList.add('loaded');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    /**
     * Utility Functions
     */
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${getNotificationClasses(type)}`;
        notification.innerHTML = `
            <div class="flex items-center">
                <span class="mr-2">${getNotificationIcon(type)}</span>
                <span>${message}</span>
                <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    }
    
    function getNotificationClasses(type) {
        const classes = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-white',
            info: 'bg-blue-500 text-white'
        };
        return classes[type] || classes.info;
    }
    
    function getNotificationIcon(type) {
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };
        return icons[type] || icons.info;
    }
    
    /**
     * Performance optimizations
     */
    
    // Debounce function for scroll events
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Throttle function for high-frequency events
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    }
    
    /**
     * Mobile-specific enhancements
     */
    if (window.innerWidth <= 768) {
        // Touch-friendly product cards
        document.querySelectorAll('.product-item').forEach(item => {
            item.addEventListener('touchstart', function() {
                this.classList.add('touched');
            });
            
            item.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.classList.remove('touched');
                }, 150);
            });
        });
        
        // Swipe gestures for product sliders
        let startX = 0;
        let currentX = 0;
        
        document.querySelectorAll('.product-grid').forEach(grid => {
            grid.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
            });
            
            grid.addEventListener('touchmove', (e) => {
                currentX = e.touches[0].clientX;
            });
            
            grid.addEventListener('touchend', () => {
                const diff = startX - currentX;
                const threshold = 50;
                
                if (Math.abs(diff) > threshold) {
                    const slider = grid.closest('.product-slider');
                    const button = diff > 0 ? 
                        slider.querySelector('.slider-next') : 
                        slider.querySelector('.slider-prev');
                    
                    if (button && !button.disabled) {
                        button.click();
                    }
                }
            });
        });
    }
    
    /**
     * Accessibility enhancements
     */
    
    // Keyboard navigation for sliders
    document.querySelectorAll('.product-slider').forEach(slider => {
        slider.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                const prevBtn = this.querySelector('.slider-prev');
                if (prevBtn && !prevBtn.disabled) prevBtn.click();
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                const nextBtn = this.querySelector('.slider-next');
                if (nextBtn && !nextBtn.disabled) nextBtn.click();
            }
        });
    });
    
    // Focus management for modal-like interactions
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Close any open notifications
            document.querySelectorAll('.fixed.top-4.right-4').forEach(el => {
                el.remove();
            });
        }
    });
    
    /**
     * Analytics tracking (Google Analytics/GTM integration)
     */
    function trackEvent(eventName, eventData = {}) {
        // Google Analytics 4
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, eventData);
        }
        
        // Google Tag Manager
        if (typeof dataLayer !== 'undefined') {
            dataLayer.push({
                event: eventName,
                ...eventData
            });
        }
        
        // Facebook Pixel
        if (typeof fbq !== 'undefined') {
            fbq('track', eventName, eventData);
        }
    }
    
    // Track product interactions
    document.querySelectorAll('.product-item a').forEach(link => {
        link.addEventListener('click', function() {
            const productName = this.querySelector('h3')?.textContent;
            const productPrice = this.querySelector('.price')?.textContent;
            
            trackEvent('product_click', {
                product_name: productName,
                product_price: productPrice,
                source: 'homepage'
            });
        });
    });
    
    // Track category clicks
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', function() {
            const categoryName = this.querySelector('h3')?.textContent;
            
            trackEvent('category_click', {
                category_name: categoryName,
                source: 'homepage'
            });
        });
    });
    
});

/**
 * Global functions that might be called from inline scripts
 */
window.TostiShopHomepage = {
    trackEvent: function(eventName, eventData) {
        // Same tracking function as above, made globally available
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, eventData);
        }
        if (typeof dataLayer !== 'undefined') {
            dataLayer.push({
                event: eventName,
                ...eventData
            });
        }
        if (typeof fbq !== 'undefined') {
            fbq('track', eventName, eventData);
        }
    }
};
