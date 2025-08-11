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
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
        
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            
            <!-- Brand & Description -->
            <div class="lg:col-span-1">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-white mb-4">
                        TostiShop
                    </h3>
                    <p class="text-silver-200 text-sm leading-relaxed mb-6">
                        Your one-stop shop for all things beauty and personal care! We offer a wide variety of jewelry, cosmetics, and personal care products to help you look and feel your best.
                    </p>
                    <!-- Social Media Icons -->
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-navy-800 hover:bg-accent rounded-lg flex items-center justify-center transition-colors duration-300 group">
                            <svg class="w-5 h-5 text-silver-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-navy-800 hover:bg-accent rounded-lg flex items-center justify-center transition-colors duration-300 group">
                            <svg class="w-5 h-5 text-silver-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-navy-800 hover:bg-accent rounded-lg flex items-center justify-center transition-colors duration-300 group">
                            <svg class="w-5 h-5 text-silver-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.219-.359-.219c0-1.495.869-2.611 1.955-2.611.922 0 1.368.692 1.368 1.518 0 .927-.592 2.311-.898 3.595-.255 1.078.54 1.956 1.602 1.956 1.924 0 3.405-2.03 3.405-4.963 0-2.588-1.867-4.402-4.538-4.402-3.092 0-4.915 2.309-4.915 4.703 0 .934.358 1.935.808 2.484a.363.363 0 0 1 .084.353c-.091.378-.293 1.19-.332 1.355-.053.218-.173.264-.4.159-1.492-.694-2.424-2.875-2.424-4.627 0-3.769 2.737-7.229 7.892-7.229 4.144 0 7.365 2.953 7.365 6.899 0 4.117-2.595 7.431-6.199 7.431-1.211 0-2.357-.629-2.746-1.378 0 0-.599 2.282-.744 2.84-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.001 12.017.001z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-navy-800 hover:bg-accent rounded-lg flex items-center justify-center transition-colors duration-300 group">
                            <svg class="w-5 h-5 text-silver-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Get to Know Us -->
            <div>
                <h3 class="text-lg font-bold text-white mb-6 flex items-center">
                    <div class="w-8 h-8 bg-accent/20 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    Get to Know Us
                </h3>
                <ul class="space-y-3">
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
            <div>
                <h3 class="text-lg font-bold text-white mb-6 flex items-center">
                    <div class="w-8 h-8 bg-accent/20 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    Customer Service
                </h3>
                <ul class="space-y-3">
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
            <div>
                <h3 class="text-lg font-bold text-white mb-6 flex items-center">
                    <div class="w-8 h-8 bg-accent/20 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    My Account
                </h3>
                <ul class="space-y-3">
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
        <div class="border-t border-navy-700 pt-8 mt-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-6 lg:space-y-0">
                
                <!-- Payment Methods -->
                <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <span class="text-sm font-medium text-silver-200">Secure Payments:</span>
                    <div class="flex items-center space-x-3">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-3 py-1 rounded-md text-xs font-bold shadow-lg">
                            VISA
                        </div>
                        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white px-3 py-1 rounded-md text-xs font-bold shadow-lg">
                            MC
                        </div>
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-1 rounded-md text-xs font-bold shadow-lg">
                            PayPal
                        </div>
                        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-3 py-1 rounded-md text-xs font-bold shadow-lg">
                            UPI
                        </div>
                    </div>
                </div>
                
                <!-- Legal Links -->
                <div class="flex flex-wrap items-center space-x-6 text-sm">
                    <a href="<?php echo home_url('/privacy-policy/'); ?>" class="text-silver-300 hover:text-accent transition-colors duration-300">Privacy</a>
                    <a href="<?php echo home_url('/terms-and-conditions/'); ?>" class="text-silver-300 hover:text-accent transition-colors duration-300">Terms</a>
                    <a href="<?php echo home_url('/contact/'); ?>" class="text-silver-300 hover:text-accent transition-colors duration-300">Contact</a>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="text-center mt-8 pt-6 border-t border-navy-800">
                <p class="text-silver-400 text-sm">
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
