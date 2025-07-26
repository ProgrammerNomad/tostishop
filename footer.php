</main>

<?php if (!is_cart() && !is_checkout()) : ?>
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
                    <div class="space-y-3 text-sm text-silver-200">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <a href="mailto:contact@tostishop.com" class="hover:text-white transition-colors duration-200">contact@tostishop.com</a>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.595z"/>
                            </svg>
                            <a href="https://wa.me/917982999145" target="_blank" rel="noopener" class="hover:text-white transition-colors duration-200">+91 79829 99145</a>
                        </div>
                        <p class="text-xs text-silver-300 mt-2"><?php _e('WhatsApp chat only (no calls)', 'tostishop'); ?></p>
                        <div class="flex items-center space-x-2 mt-3">
                            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-xs"><?php _e('Support: 9 AM - 6 PM IST', 'tostishop'); ?></span>
                        </div>
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
