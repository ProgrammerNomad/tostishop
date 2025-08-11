</main>

<?php if (!is_cart() && !is_checkout()) : ?>
<!-- Footer -->
<footer class="bg-navy-900 text-white mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Desktop Footer -->
        <div class="hidden md:grid md:grid-cols-3 md:gap-8">
            
            <!-- Footer Widget 1 -->
            <div>
                <div id="block-8" class="widget widget_block"><p class="text-sm">
  Tosti Shop is your one-stop shop for all things beauty and personal care!<br><br>
  We are an online and offline store offering a wide variety of jewelry, cosmetics, and personal care products to help you look and feel your best.
</p></div><div id="block-9" class="widget widget_block">
<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
</div><div id="nav_menu-4" class="widget widget_nav_menu"><h3 class="widget-title text-lg font-semibold mb-4">Get to Know Us</h3><div class="menu-get-to-know-us-container"><ul id="menu-get-to-know-us" class="menu"><li id="menu-item-186" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-186"><a href="https://www.tostishop.com/about-us/">About Us</a></li>
<li id="menu-item-183" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-183"><a href="https://www.tostishop.com/press-releases/">Press Releases</a></li>
<li id="menu-item-184" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-184"><a href="https://www.tostishop.com/careers/">Careers</a></li>
<li id="menu-item-185" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-185"><a href="https://www.tostishop.com/gift-a-smile/">Gift a Smile</a></li>
</ul></div></div>
            </div>
            
            <!-- Footer Widget 2 -->
            <div>
                <div id="nav_menu-5" class="widget widget_nav_menu"><h3 class="widget-title text-lg font-semibold mb-4">Let Us Help You</h3><div class="menu-let-us-help-you-container"><ul id="menu-let-us-help-you" class="menu"><li id="menu-item-637" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-637"><a href="https://www.tostishop.com/contact-us/">Contact US</a></li>
<li id="menu-item-202" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-202"><a href="https://www.tostishop.com/100-purchase-protection/">100% Purchase Protection</a></li>
<li id="menu-item-195" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-privacy-policy menu-item-195"><a rel="privacy-policy" href="https://www.tostishop.com/privacy-policy/">Privacy Policy</a></li>
<li id="menu-item-196" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-196"><a href="https://www.tostishop.com/terms-and-conditions/">Terms and Conditions</a></li>
<li id="menu-item-638" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-638"><a href="https://www.tostishop.com/refund-and-returns-policy/">Refund and Returns Policy</a></li>
<li id="menu-item-639" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-639"><a href="https://www.tostishop.com/shipping-policy/">Shipping Policy</a></li>
<li id="menu-item-640" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-640"><a href="https://www.tostishop.com/communication-preferences/">Communication preferences</a></li>
</ul></div></div>
            </div>
            
            <!-- Footer Widget 3 -->
            <div>
                <div id="nav_menu-6" class="widget widget_nav_menu"><h3 class="widget-title text-lg font-semibold mb-4">My Account</h3><div class="menu-my-account-container"><ul id="menu-my-account" class="menu"><li id="menu-item-187" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-187"><a href="https://www.tostishop.com/my-account/">My account</a></li>
<li id="menu-item-192" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-192"><a href="https://www.tostishop.com/my-account/edit-account/">Account details</a></li>
<li id="menu-item-188" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-188"><a href="https://www.tostishop.com/my-account/">Login</a></li>
<li id="menu-item-189" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-189"><a href="https://www.tostishop.com/my-account/">Register</a></li>
<li id="menu-item-194" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-194"><a href="https://www.tostishop.com/my-account/lost-password/">Lost password</a></li>
<li id="menu-item-190" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-190"><a href="https://www.tostishop.com/my-account/orders/">Orders</a></li>
<li id="menu-item-191" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-191"><a href="https://www.tostishop.com/my-account/edit-address/">Addresses</a></li>
</ul></div></div>
            </div>
        </div>
        
        <!-- Mobile Footer (Accordion Style) -->
        <div class="md:hidden" x-data="{ openSection: null }">
            
            <!-- Footer Section 1 -->
            <div class="border-b border-navy-800">
                <button @click="openSection = openSection === 1 ? null : 1" class="w-full flex items-center justify-between py-4 text-left">
                    <h3 class="text-lg font-semibold text-white">Quick Links</h3>
                    <svg class="w-5 h-5 text-silver-400 transform transition-transform duration-200" :class="{ 'rotate-180': openSection === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openSection === 1" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="pb-4" style="display: none;">
                    <ul class="space-y-2 text-sm text-silver-200">
                        <li><a href="https://www.tostishop.com/shop" class="block hover:text-white transition-colors duration-200">Shop</a></li>
                        <li><a href="https://www.tostishop.com/about" class="block hover:text-white transition-colors duration-200">About Us</a></li>
                        <li><a href="https://www.tostishop.com/contact" class="block hover:text-white transition-colors duration-200">Contact</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Footer Section 2 -->
            <div class="border-b border-navy-800">
                <button @click="openSection = openSection === 2 ? null : 2" class="w-full flex items-center justify-between py-4 text-left">
                    <h3 class="text-lg font-semibold text-white">Customer Service</h3>
                    <svg class="w-5 h-5 text-silver-400 transform transition-transform duration-200" :class="{ 'rotate-180': openSection === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openSection === 2" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="pb-4" style="display: none;">
                    <ul class="space-y-2 text-sm text-silver-200">
                        <li><a href="https://www.tostishop.com/my-account" class="block hover:text-white transition-colors duration-200">My Account</a></li>
                        <li><a href="https://www.tostishop.com/shipping-info" class="block hover:text-white transition-colors duration-200">Shipping Info</a></li>
                        <li><a href="https://www.tostishop.com/returns" class="block hover:text-white transition-colors duration-200">Returns</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Payment Icons & Copyright -->
        <div class="mt-8 pt-8 border-t border-navy-800">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                
                <!-- Payment Icons -->
                <div class="flex items-center space-x-4 mb-4 md:mb-0">
                    <span class="text-sm text-silver-300">We accept:</span>
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
                    <p>© 2025 TostiShop. All rights reserved.</p>
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
