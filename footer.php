<?php
/**
 * The template for displaying the footer
 * 
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package TostiShop
 */
?>
</main>

<?php if (!is_cart() && !is_checkout()) : ?>
<!-- Footer -->
<footer class="bg-navy-900 text-white mt-16 relative overflow-hidden">
    <!-- Beauty & Jewelry Themed Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-navy-900 via-navy-800 to-navy-900"></div>
    
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 pointer-events-none">
        <!-- Jewelry Gems & Sparkles -->
        <div class="absolute top-8 left-12 w-4 h-4 bg-accent/30 rounded-full animate-pulse"></div>
        <div class="absolute top-20 right-16 w-2 h-2 bg-silver-300/40 rounded-full animate-ping"></div>
        <div class="absolute bottom-32 left-20 w-3 h-3 bg-accent/20 rounded-full"></div>
        <div class="absolute bottom-16 right-24 w-5 h-5 bg-silver-300/30 rounded-full animate-pulse delay-300"></div>
        
        <!-- Lipstick & Makeup Elements -->
        <div class="absolute top-16 right-1/3 opacity-10">
            <svg class="w-8 h-8 text-accent rotate-12" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L8 6v12a4 4 0 0 0 8 0V6l-4-4z"/>
                <circle cx="12" cy="4" r="1"/>
            </svg>
        </div>
        
        <!-- Ring/Circle Elements -->
        <div class="absolute bottom-24 left-1/4 opacity-15">
            <svg class="w-10 h-10 text-silver-300 rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="8" stroke-width="1"/>
                <circle cx="12" cy="12" r="3" fill="currentColor"/>
            </svg>
        </div>
        
        <!-- Perfume Bottle -->
        <div class="absolute top-24 left-1/2 opacity-10">
            <svg class="w-6 h-6 text-accent -rotate-12" fill="currentColor" viewBox="0 0 24 24">
                <path d="M9 3V1h6v2h4v4h-1l-1 12H7L6 7H5V3h4zm2 0h2v1h-2V3z"/>
            </svg>
        </div>
        
        <!-- Subtle Pattern Overlay -->
        <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle at 20% 20%, rgba(228, 32, 41, 0.1) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(236, 235, 238, 0.1) 0%, transparent 50%);"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-12 relative z-10">
        
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8 mb-6 md:mb-12" x-data="{ 
            openSections: { 
                brand: true, 
                about: false, 
                service: false, 
                account: false 
            } 
        }">
            
            <!-- Brand & Description -->
            <div class="lg:col-span-1">
                <div class="mb-4 md:mb-6">
                    <h3 class="text-xl md:text-2xl font-bold text-white mb-2 md:mb-4">
                        TostiShop
                    </h3>
                    <p class="text-silver-200 text-sm leading-relaxed mb-4 md:mb-6">
                        Your one-stop shop for all things beauty and personal care! We offer a wide variety of jewelry, cosmetics, and personal care products to help you look and feel your best.
                    </p>
                    <!-- Social Media Icons -->
                    <div class="flex space-x-3 md:space-x-4">
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/tostishop" target="_blank" rel="noopener noreferrer" class="w-8 h-8 md:w-10 md:h-10 bg-navy-800 hover:bg-accent rounded-lg flex items-center justify-center transition-colors duration-300 group" title="Follow us on Facebook @tostishop">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-silver-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <!-- Instagram -->
                        <a href="https://www.instagram.com/tostishop" target="_blank" rel="noopener noreferrer" class="w-8 h-8 md:w-10 md:h-10 bg-navy-800 hover:bg-accent rounded-lg flex items-center justify-center transition-colors duration-300 group" title="Follow us on Instagram @tostishop">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-silver-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <!-- Pinterest -->
                        <a href="https://www.pinterest.com/tostishop" target="_blank" rel="noopener noreferrer" class="w-8 h-8 md:w-10 md:h-10 bg-navy-800 hover:bg-accent rounded-lg flex items-center justify-center transition-colors duration-300 group" title="Follow us on Pinterest @tostishop">
                            <svg class="w-4 h-4 md:w-5 md:h-5 text-silver-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.888-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Get to Know Us -->
            <div class="md:block">
                <button @click="openSections.about = !openSections.about" class="w-full flex items-center justify-between text-left md:cursor-default md:pointer-events-none">
                    <h3 class="text-base md:text-lg font-bold text-white mb-3 md:mb-6 flex items-center">
                        <div class="w-6 h-6 md:w-8 md:h-8 bg-accent/20 rounded-lg flex items-center justify-center mr-2 md:mr-3">
                            <svg class="w-3 h-3 md:w-4 md:h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        Get to Know Us
                    </h3>
                    <svg class="w-4 h-4 text-silver-300 transform transition-transform duration-200 md:hidden" :class="openSections.about ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <ul class="space-y-2 md:space-y-3 transition-all duration-300 md:block" x-show="openSections.about || window.innerWidth >= 768" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0" style="overflow: hidden;">
                    <li><a href="<?php echo home_url('/about-us/'); ?>" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        About Us
                    </a></li>
                    <li><a href="<?php echo home_url('/careers/'); ?>" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Careers
                    </a></li>
                    <li><a href="<?php echo home_url('/contact/'); ?>" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Contact Us
                    </a></li>
                </ul>
            </div>
            
            <!-- Customer Service -->
            <div class="md:block">
                <button @click="openSections.service = !openSections.service" class="w-full flex items-center justify-between text-left md:cursor-default md:pointer-events-none">
                    <h3 class="text-base md:text-lg font-bold text-white mb-3 md:mb-6 flex items-center">
                        <div class="w-6 h-6 md:w-8 md:h-8 bg-accent/20 rounded-lg flex items-center justify-center mr-2 md:mr-3">
                            <svg class="w-3 h-3 md:w-4 md:h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        Customer Service
                    </h3>
                    <svg class="w-4 h-4 text-silver-300 transform transition-transform duration-200 md:hidden" :class="openSections.service ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <ul class="space-y-2 md:space-y-3 transition-all duration-300 md:block" x-show="openSections.service || window.innerWidth >= 768" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0" style="overflow: hidden;">
                    <li><a href="<?php echo home_url('/help/'); ?>" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Help Center
                    </a></li>
                    <li><a href="<?php echo home_url('/returns/'); ?>" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Returns & Refunds
                    </a></li>
                    <li><a href="<?php echo home_url('/shipping/'); ?>" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Shipping Info
                    </a></li>
                    <li><a href="<?php echo home_url('/privacy-policy/'); ?>" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Privacy Policy
                    </a></li>
                </ul>
            </div>
            
            <!-- My Account -->
            <div class="md:block">
                <button @click="openSections.account = !openSections.account" class="w-full flex items-center justify-between text-left md:cursor-default md:pointer-events-none">
                    <h3 class="text-base md:text-lg font-bold text-white mb-3 md:mb-6 flex items-center">
                        <div class="w-6 h-6 md:w-8 md:h-8 bg-accent/20 rounded-lg flex items-center justify-center mr-2 md:mr-3">
                            <svg class="w-3 h-3 md:w-4 md:h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        My Account
                    </h3>
                    <svg class="w-4 h-4 text-silver-300 transform transition-transform duration-200 md:hidden" :class="openSections.account ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <ul class="space-y-2 md:space-y-3 transition-all duration-300 md:block" x-show="openSections.account || window.innerWidth >= 768" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0" style="overflow: hidden;">
                    <?php if (!is_user_logged_in()) : ?>
                    <li><a href="<?php echo wc_get_page_permalink('myaccount'); ?>" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Login
                    </a></li>
                    <li><a href="<?php echo wc_get_page_permalink('myaccount'); ?>" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Register
                    </a></li>
                    <li><a href="<?php echo wc_lostpassword_url(); ?>" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Lost Password
                    </a></li>
                    <?php else : ?>
                    <li><a href="<?php echo wc_get_page_permalink('myaccount'); ?>" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Dashboard
                    </a></li>
                    <li><a href="<?php echo wc_get_endpoint_url('orders', '', wc_get_page_permalink('myaccount')); ?>" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Orders
                    </a></li>
                    <li><a href="<?php echo wc_get_endpoint_url('edit-account', '', wc_get_page_permalink('myaccount')); ?>" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Account Details
                    </a></li>
                    <li><a href="<?php echo wp_logout_url(home_url()); ?>" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Logout
                    </a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        
        <!-- Bottom Section -->
        <div class="border-t border-navy-700 pt-4 md:pt-8 mt-4 md:mt-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 md:space-y-6 lg:space-y-0">
                
                <!-- Payment Methods -->
                <div class="flex flex-col sm:flex-row sm:items-center space-y-3 md:space-y-4 sm:space-y-0 sm:space-x-6">
                    <span class="text-xs md:text-sm font-medium text-silver-200">Secure Payments via Razorpay:</span>
                    <div class="flex items-center space-x-2 md:space-x-3">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-2 md:px-3 py-1 rounded-md text-xs font-bold shadow-lg">
                            VISA
                        </div>
                        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white px-2 md:px-3 py-1 rounded-md text-xs font-bold shadow-lg">
                            MASTERCARD
                        </div>
                        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-2 md:px-3 py-1 rounded-md text-xs font-bold shadow-lg">
                            UPI
                        </div>
                        <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-2 md:px-3 py-1 rounded-md text-xs font-bold shadow-lg">
                            NET BANKING
                        </div>
                        <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white px-2 md:px-3 py-1 rounded-md text-xs font-bold shadow-lg">
                            RUPAY
                        </div>
                    </div>
                </div>
                
                <!-- Legal Links -->
                <div class="flex flex-wrap items-center space-x-4 md:space-x-6 text-xs md:text-sm">
                    <a href="<?php echo home_url('/privacy-policy/'); ?>" class="text-silver-300 hover:text-accent transition-colors duration-300">Privacy</a>
                    <a href="<?php echo home_url('/terms-and-conditions/'); ?>" class="text-silver-300 hover:text-accent transition-colors duration-300">Terms</a>
                    <a href="<?php echo home_url('/contact/'); ?>" class="text-silver-300 hover:text-accent transition-colors duration-300">Contact</a>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="text-center mt-4 md:mt-8 pt-4 md:pt-6 border-t border-navy-800">
                <p class="text-silver-400 text-xs md:text-sm">
                    © <?php echo date('Y'); ?> <span class="font-semibold text-white"><?php bloginfo('name'); ?></span>. All rights reserved. 
                    <span class="text-accent">Made with ❤️ for beauty enthusiasts</span>
                </p>
            </div>
        </div>
    </div>
</footer>
<?php else : ?>
<!-- Minimal Footer for Cart/Checkout -->
<footer class="bg-white border-t border-gray-200 mt-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="text-center">
            <p class="text-sm text-gray-600"><?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'tostishop'); ?></p>
        </div>
    </div>
</footer>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
