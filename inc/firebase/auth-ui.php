<?php
/**
 * Firebase Authentication UI
 * TostiShop Theme - Firebase Login Interface
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render Firebase authentication UI
 */
function tostishop_render_firebase_login_ui() {
    // Don't show if user is already logged in
    if (is_user_logged_in()) {
        return;
    }
    
    // Don't show if Firebase is not configured
    if (!tostishop_is_firebase_configured()) {
        return;
    }
    ?>
    
    <div id="tostishop-firebase-auth" class="firebase-auth-container bg-white border border-gray-200 rounded-lg p-6 shadow-sm mb-6">
        <div class="firebase-auth-header mb-4">
            <h3 class="text-lg font-semibold text-navy-900 mb-2 flex items-center">
                <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                <?php _e('Quick Login', 'tostishop'); ?>
            </h3>
            <p class="text-sm text-gray-600">
                <?php _e('Login instantly with your phone number or Google account', 'tostishop'); ?>
            </p>
        </div>
        
        <!-- Firebase Auth Tabs -->
        <div class="firebase-auth-tabs" x-data="{ activeTab: 'phone' }">
            
            <!-- Tab Navigation -->
            <div class="flex border-b border-gray-200 mb-4">
                <button @click="activeTab = 'phone'" :class="activeTab === 'phone' ? 'border-accent text-accent' : 'border-transparent text-gray-500 hover:text-gray-700'" class="flex-1 py-2 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-200">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <?php _e('Phone', 'tostishop'); ?>
                </button>
                <button @click="activeTab = 'google'" :class="activeTab === 'google' ? 'border-accent text-accent' : 'border-transparent text-gray-500 hover:text-gray-700'" class="flex-1 py-2 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-200">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"></path>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"></path>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"></path>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"></path>
                    </svg>
                    <?php _e('Google', 'tostishop'); ?>
                </button>
                <button @click="activeTab = 'email'" :class="activeTab === 'email' ? 'border-accent text-accent' : 'border-transparent text-gray-500 hover:text-gray-700'" class="flex-1 py-2 px-1 text-center border-b-2 font-medium text-sm transition-colors duration-200">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <?php _e('Email', 'tostishop'); ?>
                </button>
            </div>
            
            <!-- Phone Login Tab -->
            <div x-show="activeTab === 'phone'" class="firebase-phone-login">
                <form id="firebase-phone-form" class="space-y-4">
                    <div>
                        <label for="phone-number" class="block text-sm font-medium text-gray-700 mb-1">
                            <?php _e('Phone Number', 'tostishop'); ?>
                        </label>
                        <div class="flex">
                            <select id="country-code" class="rounded-l-lg border border-gray-300 px-3 py-2 text-sm bg-gray-50 text-gray-700">
                                <option value="+91">🇮🇳 +91</option>
                                <option value="+1">🇺🇸 +1</option>
                                <option value="+44">🇬🇧 +44</option>
                                <option value="+61">🇦🇺 +61</option>
                            </select>
                            <input type="tel" id="phone-number" placeholder="9876543210" class="flex-1 rounded-r-lg border border-l-0 border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent" required>
                        </div>
                    </div>
                    
                    <div id="recaptcha-container" class="recaptcha-container"></div>
                    
                    <button type="submit" id="send-otp-btn" class="w-full bg-accent text-white px-4 py-2 rounded-lg font-medium text-sm hover:bg-red-600 transition-colors duration-200 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <?php _e('Send OTP', 'tostishop'); ?>
                    </button>
                </form>
                
                <!-- OTP Verification (Hidden initially) -->
                <form id="firebase-otp-form" class="space-y-4 mt-4" style="display: none;">
                    <div>
                        <label for="otp-code" class="block text-sm font-medium text-gray-700 mb-1">
                            <?php _e('Enter OTP', 'tostishop'); ?>
                        </label>
                        <input type="text" id="otp-code" placeholder="123456" maxlength="6" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-center text-lg font-mono tracking-widest focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent" required>
                    </div>
                    
                    <button type="submit" id="verify-otp-btn" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg font-medium text-sm hover:bg-green-700 transition-colors duration-200">
                        <?php _e('Verify & Login', 'tostishop'); ?>
                    </button>
                    
                    <button type="button" id="resend-otp-btn" class="w-full text-accent text-sm hover:underline">
                        <?php _e('Resend OTP', 'tostishop'); ?>
                    </button>
                </form>
            </div>
            
            <!-- Google Login Tab -->
            <div x-show="activeTab === 'google'" class="firebase-google-login">
                <button id="google-login-btn" class="w-full bg-white border border-gray-300 text-gray-700 px-4 py-3 rounded-lg font-medium text-sm hover:bg-gray-50 transition-colors duration-200 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"></path>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"></path>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"></path>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"></path>
                    </svg>
                    <?php _e('Continue with Google', 'tostishop'); ?>
                </button>
            </div>
            
            <!-- Email Login Tab -->
            <div x-show="activeTab === 'email'" class="firebase-email-login">
                <form id="firebase-email-form" class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            <?php _e('Email Address', 'tostishop'); ?>
                        </label>
                        <input type="email" id="email" placeholder="your@email.com" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent" required>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            <?php _e('Password', 'tostishop'); ?>
                        </label>
                        <input type="password" id="password" placeholder="Your password" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent" required>
                    </div>
                    
                    <button type="submit" id="email-login-btn" class="w-full bg-navy-900 text-white px-4 py-2 rounded-lg font-medium text-sm hover:bg-navy-800 transition-colors duration-200">
                        <?php _e('Login with Email', 'tostishop'); ?>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Loading State -->
        <div id="firebase-loading" class="hidden text-center py-4">
            <div class="inline-flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-accent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm text-gray-600"><?php _e('Authenticating...', 'tostishop'); ?></span>
            </div>
        </div>
        
        <!-- Error Messages -->
        <div id="firebase-error" class="hidden mt-4 bg-red-50 border border-red-200 rounded-lg p-3">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <p class="text-sm text-red-800" id="firebase-error-message"></p>
            </div>
        </div>
        
        <!-- Success Messages -->
        <div id="firebase-success" class="hidden mt-4 bg-green-50 border border-green-200 rounded-lg p-3">
            <div class="flex">
                <svg class="w-5 h-5 text-green-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-green-800" id="firebase-success-message"></p>
            </div>
        </div>
    </div>
    
    <?php
}

/**
 * Add Firebase login to checkout page
 */
function tostishop_add_firebase_to_checkout() {
    if (!is_user_logged_in() && tostishop_is_firebase_configured()) {
        echo '<div class="firebase-checkout-login mb-6">';
        echo '<h3 class="text-lg font-semibold text-navy-900 mb-4">' . __('Quick Login for Faster Checkout', 'tostishop') . '</h3>';
        tostishop_render_firebase_login_ui();
        echo '</div>';
    }
}

/**
 * Add Firebase login to My Account page
 */
function tostishop_add_firebase_to_account() {
    if (!is_user_logged_in() && tostishop_is_firebase_configured()) {
        echo '<div class="firebase-account-login mb-6">';
        tostishop_render_firebase_login_ui();
        echo '</div>';
    }
}
