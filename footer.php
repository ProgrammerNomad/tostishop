</main>

<?php if (!is_cart() && !is_checkout()) : ?>
<!-- Footer -->
<footer class="bg-gradient-to-br from-navy-900 via-navy-800 to-navy-900 text-white mt-16 relative overflow-hidden">
    <!-- Enhanced Background Decorative Elements -->
    <div class="absolute inset-0">
        <!-- Geometric patterns -->
        <div class="absolute top-0 left-0 w-96 h-96 opacity-5">
            <svg viewBox="0 0 100 100" class="w-full h-full">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)" class="text-accent"/>
            </svg>
        </div>
        
        <!-- Subtle gradient orbs -->
        <div class="absolute top-20 right-20 w-72 h-72 bg-gradient-to-br from-accent/10 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-20 w-96 h-96 bg-gradient-to-tr from-silver-50/5 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-r from-accent/5 to-silver-50/5 rounded-full blur-2xl"></div>
        
        <!-- Diagonal lines pattern -->
        <div class="absolute inset-0 opacity-3">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="diagonals" width="20" height="20" patternUnits="userSpaceOnUse">
                        <path d="M0,20 L20,0" stroke="currentColor" stroke-width="0.5" class="text-accent"/>
                        <path d="M10,20 L20,10" stroke="currentColor" stroke-width="0.3" class="text-silver-200"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#diagonals)"/>
            </svg>
        </div>
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
                    <li><a href="https://www.tostishop.com/about-us/" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        About Us
                    </a></li>
                    <li><a href="https://www.tostishop.com/press-releases/" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Press Releases
                    </a></li>
                    <li><a href="https://www.tostishop.com/careers/" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Careers
                    </a></li>
                    <li><a href="https://www.tostishop.com/gift-a-smile/" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Gift a Smile
                    </a></li>
                </ul>
            </div>
            
            <!-- Let Us Help You -->
            <div>
                <h3 class="text-lg font-bold text-white mb-6 flex items-center">
                    <div class="w-8 h-8 bg-accent/20 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    Customer Support
                </h3>
                <ul class="space-y-3">
                    <li><a href="https://www.tostishop.com/contact-us/" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Contact Us
                    </a></li>
                    <li><a href="https://www.tostishop.com/100-purchase-protection/" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        100% Purchase Protection
                    </a></li>
                    <li><a href="https://www.tostishop.com/shipping-policy/" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Shipping Policy
                    </a></li>
                    <li><a href="https://www.tostishop.com/refund-and-returns-policy/" class="text-silver-200 hover:text-white hover:text-accent transition-colors duration-300 flex items-center group">
                        <span class="w-2 h-2 bg-accent rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        Returns & Refunds
                    </a></li>
                </ul>
            </div>
            
            <!-- My Account (Logical Organization) -->
                        <!-- My Account (Logical Organization) -->
            <div>
                <h3 class="text-lg font-bold text-white mb-6 flex items-center">
                    <div class="w-8 h-8 bg-accent/20 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    My Account
                </h3>
                <div class="space-y-4">
                    <?php if (!is_user_logged_in()) : ?>
                    <!-- Pre-Login Section (Guest Users) -->
                    <div class="bg-navy-800/50 rounded-lg p-4 border border-navy-700">
                        <h4 class="text-sm font-semibold text-accent mb-3">Access Your Account</h4>
                        <ul class="space-y-2">
                            <li><a href="https://www.tostishop.com/my-account/" class="text-silver-200 hover:text-white text-sm transition-colors duration-300 flex items-center group">
                                <svg class="w-3 h-3 text-accent mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Login
                            </a></li>
                            <li><a href="https://www.tostishop.com/my-account/" class="text-silver-200 hover:text-white text-sm transition-colors duration-300 flex items-center group">
                                <svg class="w-3 h-3 text-accent mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                Register
                            </a></li>
                            <li><a href="https://www.tostishop.com/my-account/lost-password/" class="text-silver-200 hover:text-white text-sm transition-colors duration-300 flex items-center group">
                                <svg class="w-3 h-3 text-accent mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                                </svg>
                                Lost Password
                            </a></li>
                        </ul>
                    </div>
                    <?php else : ?>
                    <!-- Post-Login Section (Logged-in Users) -->
                    <div class="bg-navy-800/50 rounded-lg p-4 border border-accent/30">
                        <h4 class="text-sm font-semibold text-accent mb-3">Welcome back!</h4>
                        <ul class="space-y-2">
                            <li><a href="https://www.tostishop.com/my-account/" class="text-silver-200 hover:text-white text-sm transition-colors duration-300 flex items-center group">
                                <svg class="w-3 h-3 text-accent mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                Dashboard
                            </a></li>
                            <li><a href="https://www.tostishop.com/my-account/edit-account/" class="text-silver-200 hover:text-white text-sm transition-colors duration-300 flex items-center group">
                                <svg class="w-3 h-3 text-accent mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Account Details
                            </a></li>
                            <li><a href="https://www.tostishop.com/my-account/orders/" class="text-silver-200 hover:text-white text-sm transition-colors duration-300 flex items-center group">
                                <svg class="w-3 h-3 text-accent mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Orders
                            </a></li>
                            <li><a href="https://www.tostishop.com/my-account/edit-address/" class="text-silver-200 hover:text-white text-sm transition-colors duration-300 flex items-center group">
                                <svg class="w-3 h-3 text-accent mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Addresses
                            </a></li>
                            <li><a href="<?php echo wp_logout_url(home_url()); ?>" class="text-silver-300 hover:text-accent text-sm transition-colors duration-300 flex items-center group">
                                <svg class="w-3 h-3 text-silver-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </a></li>
                        </ul>
                    </div>
                    <?php endif; ?>
        </div>
        
        <!-- Mobile Footer (Enhanced Accordion Style) -->
        <div class="md:hidden lg:hidden" x-data="{ openSection: null }">
            
            <!-- Company Section -->
            <div class="border-b border-navy-700 bg-navy-800/30 rounded-t-lg mb-1">
                <button @click="openSection = openSection === 1 ? null : 1" class="w-full flex items-center justify-between p-4 text-left">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <div class="w-6 h-6 bg-accent rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        Company
                    </h3>
                    <svg class="w-5 h-5 text-silver-400 transform transition-transform duration-300" :class="{ 'rotate-180': openSection === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openSection === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-4 pb-4" style="display: none;">
                    <ul class="space-y-3">
                        <li><a href="https://www.tostishop.com/about-us/" class="block text-silver-200 hover:text-accent transition-colors duration-300 flex items-center">
                            <span class="w-2 h-2 bg-accent rounded-full mr-3"></span>
                            About Us
                        </a></li>
                        <li><a href="https://www.tostishop.com/careers/" class="block text-silver-200 hover:text-accent transition-colors duration-300 flex items-center">
                            <span class="w-2 h-2 bg-accent rounded-full mr-3"></span>
                            Careers
                        </a></li>
                        <li><a href="https://www.tostishop.com/press-releases/" class="block text-silver-200 hover:text-accent transition-colors duration-300 flex items-center">
                            <span class="w-2 h-2 bg-accent rounded-full mr-3"></span>
                            Press Releases
                        </a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Support Section -->
            <div class="border-b border-navy-700 bg-navy-800/30 mb-1">
                <button @click="openSection = openSection === 2 ? null : 2" class="w-full flex items-center justify-between p-4 text-left">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <div class="w-6 h-6 bg-accent rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        Support
                    </h3>
                    <svg class="w-5 h-5 text-silver-400 transform transition-transform duration-300" :class="{ 'rotate-180': openSection === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openSection === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="px-4 pb-4" style="display: none;">
                    <ul class="space-y-3">
                        <li><a href="https://www.tostishop.com/contact-us/" class="block text-silver-200 hover:text-accent transition-colors duration-300 flex items-center">
                            <span class="w-2 h-2 bg-accent rounded-full mr-3"></span>
                            Contact Us
                        </a></li>
                        <li><a href="https://www.tostishop.com/shipping-policy/" class="block text-silver-200 hover:text-accent transition-colors duration-300 flex items-center">
                            <span class="w-2 h-2 bg-accent rounded-full mr-3"></span>
                            Shipping Info
                        </a></li>
                        <li><a href="https://www.tostishop.com/refund-and-returns-policy/" class="block text-silver-200 hover:text-accent transition-colors duration-300 flex items-center">
                            <span class="w-2 h-2 bg-accent rounded-full mr-3"></span>
                            Returns
                        </a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Account Section -->
            <div class="bg-navy-800/30 rounded-b-lg">
                <button @click="openSection = openSection === 3 ? null : 3" class="w-full flex items-center justify-between p-4 text-left">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <div class="w-6 h-6 bg-accent rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        My Account
                    </h3>
                    <svg class="w-5 h-5 text-silver-400 transform transition-transform duration-300" :class="{ 'rotate-180': openSection === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openSection === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="px-4 pb-4" style="display: none;">
                    <div class="space-y-3">
                        <div class="bg-navy-700/50 rounded-lg p-3">
                            <h4 class="text-sm font-semibold text-accent mb-2">Access</h4>
                            <ul class="space-y-2">
                                <li><a href="https://www.tostishop.com/my-account/" class="block text-silver-200 hover:text-white text-sm">Login</a></li>
                                <li><a href="https://www.tostishop.com/my-account/" class="block text-silver-200 hover:text-white text-sm">Register</a></li>
                            </ul>
                        </div>
                        <div class="bg-navy-700/30 rounded-lg p-3">
                            <h4 class="text-sm font-semibold text-silver-300 mb-2">Manage</h4>
                            <ul class="space-y-2">
                                <li><a href="https://www.tostishop.com/my-account/orders/" class="block text-silver-300 hover:text-white text-sm">Orders</a></li>
                                <li><a href="https://www.tostishop.com/my-account/edit-account/" class="block text-silver-300 hover:text-white text-sm">Account Details</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
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
                    <a href="https://www.tostishop.com/privacy-policy/" class="text-silver-300 hover:text-accent transition-colors duration-300">Privacy</a>
                    <a href="https://www.tostishop.com/terms-and-conditions/" class="text-silver-300 hover:text-accent transition-colors duration-300">Terms</a>
                    <a href="https://www.tostishop.com/communication-preferences/" class="text-silver-300 hover:text-accent transition-colors duration-300">Preferences</a>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="text-center mt-8 pt-6 border-t border-navy-800">
                <p class="text-silver-400 text-sm">
                    © 2025 <span class="font-semibold text-white">TostiShop</span>. All rights reserved. 
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
