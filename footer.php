</main>

<!-- Footer -->
<footer class="bg-navy-900 text-white mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Desktop Footer -->
        <div class="hidden md:grid md:grid-cols-3 md:gap-8">
            
            <!-- Footer Widget 1 -->
            <div>
                <?php if (is_active_sidebar('footer-1')) : ?>
                    <?php dynamic_sidebar('footer-1'); ?>
                <?php else : ?>
                    <h3 class="text-lg font-semibold text-white mb-4"><?php _e('Quick Links', 'tostishop'); ?></h3>
                    <ul class="space-y-2 text-sm text-silver-200">
                        <li><a href="<?php echo esc_url(home_url('/shop')); ?>" class="hover:text-white transition-colors duration-200"><?php _e('Shop', 'tostishop'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/about')); ?>" class="hover:text-white transition-colors duration-200"><?php _e('About Us', 'tostishop'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/contact')); ?>" class="hover:text-white transition-colors duration-200"><?php _e('Contact', 'tostishop'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>" class="hover:text-white transition-colors duration-200"><?php _e('Privacy Policy', 'tostishop'); ?></a></li>
                    </ul>
                <?php endif; ?>
            </div>
            
            <!-- Footer Widget 2 -->
            <div>
                <?php if (is_active_sidebar('footer-2')) : ?>
                    <?php dynamic_sidebar('footer-2'); ?>
                <?php else : ?>
                    <h3 class="text-lg font-semibold text-white mb-4"><?php _e('Customer Service', 'tostishop'); ?></h3>
                    <ul class="space-y-2 text-sm text-silver-200">
                        <li><a href="<?php echo esc_url(home_url('/my-account')); ?>" class="hover:text-white transition-colors duration-200"><?php _e('My Account', 'tostishop'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/shipping-info')); ?>" class="hover:text-white transition-colors duration-200"><?php _e('Shipping Info', 'tostishop'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/returns')); ?>" class="hover:text-white transition-colors duration-200"><?php _e('Returns', 'tostishop'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/faq')); ?>" class="hover:text-white transition-colors duration-200"><?php _e('FAQ', 'tostishop'); ?></a></li>
                    </ul>
                <?php endif; ?>
            </div>
            
            <!-- Footer Widget 3 -->
            <div>
                <?php if (is_active_sidebar('footer-3')) : ?>
                    <?php dynamic_sidebar('footer-3'); ?>
                <?php else : ?>
                    <h3 class="text-lg font-semibold text-white mb-4"><?php _e('Contact Info', 'tostishop'); ?></h3>
                    <div class="space-y-2 text-sm text-silver-200">
                        <p><?php _e('123 Store Street', 'tostishop'); ?></p>
                        <p><?php _e('City, State 12345', 'tostishop'); ?></p>
                        <p><?php _e('Phone: (555) 123-4567', 'tostishop'); ?></p>
                        <p><?php _e('Email: info@tostishop.com', 'tostishop'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Mobile Footer (Accordion Style) -->
        <div class="md:hidden" x-data="{ openSection: null }">
            
            <!-- Footer Section 1 -->
            <div class="border-b border-navy-800">
                <button @click="openSection = openSection === 1 ? null : 1" 
                        class="w-full flex items-center justify-between py-4 text-left">
                    <h3 class="text-lg font-semibold text-white"><?php _e('Quick Links', 'tostishop'); ?></h3>
                    <svg class="w-5 h-5 text-silver-400 transform transition-transform duration-200"
                         :class="{ 'rotate-180': openSection === 1 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openSection === 1" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="pb-4">
                    <ul class="space-y-2 text-sm text-silver-200">
                        <li><a href="<?php echo esc_url(home_url('/shop')); ?>" class="block hover:text-white transition-colors duration-200"><?php _e('Shop', 'tostishop'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/about')); ?>" class="block hover:text-white transition-colors duration-200"><?php _e('About Us', 'tostishop'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/contact')); ?>" class="block hover:text-white transition-colors duration-200"><?php _e('Contact', 'tostishop'); ?></a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Footer Section 2 -->
            <div class="border-b border-navy-800">
                <button @click="openSection = openSection === 2 ? null : 2" 
                        class="w-full flex items-center justify-between py-4 text-left">
                    <h3 class="text-lg font-semibold text-white"><?php _e('Customer Service', 'tostishop'); ?></h3>
                    <svg class="w-5 h-5 text-silver-400 transform transition-transform duration-200"
                         :class="{ 'rotate-180': openSection === 2 }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openSection === 2" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="pb-4">
                    <ul class="space-y-2 text-sm text-silver-200">
                        <li><a href="<?php echo esc_url(home_url('/my-account')); ?>" class="block hover:text-white transition-colors duration-200"><?php _e('My Account', 'tostishop'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/shipping-info')); ?>" class="block hover:text-white transition-colors duration-200"><?php _e('Shipping Info', 'tostishop'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/returns')); ?>" class="block hover:text-white transition-colors duration-200"><?php _e('Returns', 'tostishop'); ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Payment Icons & Copyright -->
        <div class="mt-8 pt-8 border-t border-navy-800">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                
                <!-- Payment Icons -->
                <div class="flex items-center space-x-4 mb-4 md:mb-0">
                    <span class="text-sm text-silver-300"><?php _e('We accept:', 'tostishop'); ?></span>
                    <div class="flex items-center space-x-2">
                        <!-- Payment method icons -->
                        <div class="w-8 h-5 bg-navy-800 border border-navy-700 rounded flex items-center justify-center">
                            <span class="text-xs text-silver-400">VISA</span>
                        </div>
                        <div class="w-8 h-5 bg-navy-800 border border-navy-700 rounded flex items-center justify-center">
                            <span class="text-xs text-silver-400">MC</span>
                        </div>
                        <div class="w-8 h-5 bg-navy-800 border border-navy-700 rounded flex items-center justify-center">
                            <span class="text-xs text-silver-400">PP</span>
                        </div>
                        <div class="w-8 h-5 bg-navy-800 border border-navy-700 rounded flex items-center justify-center">
                            <span class="text-xs text-silver-400">AE</span>
                        </div>
                    </div>
                </div>
                
                <!-- Copyright -->
                <div class="text-sm text-silver-300">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'tostishop'); ?></p>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
