<?php
/**
 * Lost password form - TostiShop Modern UI
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.2.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );
?>

<!-- Desktop 2-Column Layout Container -->
<div class="min-h-screen bg-gradient-to-br from-silver-50 via-gray-50 to-silver-100">
    <div class="container mx-auto px-4 py-8">
        
        <!-- 2-Column Grid (Desktop Only) -->
        <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center lg:min-h-screen">
            
            <!-- Left Column: Password Reset Form (Mobile Full Width, Desktop Left) -->
            <div>
                <div class="max-w-md mx-auto lg:max-w-lg">
                    
                    <!-- Welcome Block (Top Banner Section) -->
                    <div class="text-center mb-8">
                        <div class="rounded-2xl p-8 mb-2">
                            <h1 class="text-3xl font-bold text-navy-900 mb-3">
                                Reset Your Password
                            </h1>
                            <p class="text-gray-600 text-base leading-relaxed">
                                Enter your email address below. We'll send you a link to reset your password.
                            </p>
                        </div>
                    </div>

                    <!-- Main Reset Password Card -->
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm ">
                        
                        <!-- Card Body -->
                        <div class="p-8">
                            
                            <?php wc_print_notices(); ?>

                            <form method="post" class="woocommerce-ResetPassword lost_reset_password space-y-6 px-1">
                                <div>
                                    <label for="user_login" class="block text-sm font-medium text-gray-700 mb-2">
                                        <?php esc_html_e( 'Email address', 'woocommerce' ); ?>
                                    </label>
                                    <input type="text" 
                                           name="user_login" 
                                           id="user_login" 
                                           class="w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500" 
                                           placeholder="your@email.com" 
                                           autocomplete="username"
                                           required
                                           aria-required="true" />
                                </div>

                                <div class="clear"></div>

                                <?php do_action( 'woocommerce_lostpassword_form' ); ?>

                                <button type="submit" 
                                        class="w-full bg-navy-900 text-white py-3 px-4 rounded-lg font-semibold hover:bg-navy-800 focus:ring-2 focus:ring-navy-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <?php esc_html_e( 'Reset password', 'woocommerce' ); ?>
                                </button>

                                <input type="hidden" name="wc_reset_password" value="true" />
                                <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>
                            </form>
                            
                            <!-- Back to Login Link -->
                            <div class="text-center mt-6">
                                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" 
                                   class="text-navy-900 hover:text-navy-800 font-medium text-sm flex items-center mx-auto justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    <?php esc_html_e( 'Return to login', 'woocommerce' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Info Message -->
                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-blue-700">
                                Check your spam folder if you don't receive the password reset email within a few minutes.
                            </p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-8">
                        <p class="text-xs text-gray-500">
                            By continuing, you agree to our 
                            <a href="#" class="text-navy-900 hover:underline">Terms of Service</a> and 
                            <a href="#" class="text-navy-900 hover:underline">Privacy Policy</a>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Branding Section (Hidden on Mobile, Visible on Desktop) -->
            <div class="hidden lg:block lg:order-2">
                <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-6">
                    
                    <!-- Brand Image with Gradient Background -->
                    <div class="mb-6 rounded-xl overflow-hidden bg-gradient-to-r from-navy-900 to-accent h-48 flex items-center justify-center relative">
                        <?php if (file_exists(get_template_directory() . '/assets/images/store-banner.jpg')): ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/store-banner.jpg" alt="TostiShop Store" class="object-cover w-full h-full opacity-80">
                        <?php endif; ?>
                        <!-- Text Overlay -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-white text-center px-4 bg-navy-900 bg-opacity-40 py-3 rounded-lg">
                                <h3 class="text-xl font-bold mb-1">TostiShop</h3>
                                <p class="text-sm text-white text-opacity-90">Beauty & Personal Care</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tagline -->
                    <h2 class="text-xl font-bold text-navy-900 text-center mb-8">
                        India's Favorite Beauty & Personal Care Store
                    </h2>
                    
                    <!-- Features List (Clean & Minimal) -->
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-100 rounded-full flex-shrink-0 flex items-center justify-center mr-3">
                                <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                            </div>
                            <span class="text-gray-700 text-sm">Secure Account Recovery</span>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-100 rounded-full flex-shrink-0 flex items-center justify-center mr-3">
                                <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                            </div>
                            <span class="text-gray-700 text-sm">Instant Password Reset</span>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-100 rounded-full flex-shrink-0 flex items-center justify-center mr-3">
                                <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                            </div>
                            <span class="text-gray-700 text-sm">24/7 Customer Support</span>
                        </div>
                    </div>
                    
                    <!-- Statistics (Minimal) -->
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div class="text-center py-3">
                            <div class="text-xl font-bold text-navy-900">1K+</div>
                            <div class="text-xs text-gray-500">Happy Customers</div>
                        </div>
                        <div class="text-center py-3">
                            <div class="text-xl font-bold text-navy-900">5K+</div>
                            <div class="text-xs text-gray-500">Products Sold</div>
                        </div>
                    </div>
                    
                    <!-- Trust Badges (Text Only) -->
                    <div class="flex items-center justify-center space-x-6 mt-4 pt-4 border-t border-gray-200">
                        <div class="text-center text-gray-500">
                            <span class="text-xs font-medium">SSL Secured</span>
                        </div>
                        <div class="text-center text-gray-500">
                            <span class="text-xs font-medium">Safe & Private</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php do_action( 'woocommerce_after_lost_password_form' ); ?>
