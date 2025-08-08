<?php
/**
 * Login/Register Form - TostiShop Modern UI with 2-Column Layout
 * Mobile-first design with comprehensive authentication options
 * Features: Mobile OTP, Google Login, Email Login/Register
 * Desktop: 2-column layout with branding section
 *
 * @version 3.3.0 - Fixed reCAPTCHA Placement
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<!-- Desktop 2-Column Layout Container -->
<div class="min-h-screen bg-gradient-to-br from-silver-50 via-gray-50 to-silver-100">
    <div class="container mx-auto px-4 py-8">
        
        <!-- 2-Column Grid (Desktop Only) -->
        <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center lg:min-h-screen">
            
            <!-- Left Column: Login Form (Mobile Full Width, Desktop Left) -->
            <div class="lg:order-1">
                <div class="max-w-md mx-auto lg:max-w-lg">
                    
                    <!-- Welcome Block (Top Banner Section) -->
                    <div class="text-center mb-8">
                        <div class="rounded-2xl  p-8 mb-6">
                            
                            <h1 class="text-3xl font-bold text-navy-900 mb-3">
                                Welcome to TostiShop
                            </h1>
                            <p class="text-gray-600 text-base leading-relaxed">
                                Login to track your orders, wishlist products, and enjoy faster checkout.
                            </p>
                        </div>
                    </div>

                    <!-- Main Authentication Card -->
                    <div id="firebase-auth-container" class="firebase-auth-container rounded-2xl overflow-hidden">
                        
                        <!-- Card Body -->
                        <div class="p-8" x-data="{ 
                            currentView: 'initial', 
                            isRegistering: false,
                            init() {
                                // Listen for Firebase events
                                document.addEventListener('switch-to-otp', (e) => {
                                    this.currentView = 'otp';
                                    // Update phone display
                                    this.$nextTick(() => {
                                        if (e.detail.phone) {
                                            document.getElementById('otp-phone-display').textContent = e.detail.phone;
                                        }
                                    });
                                });
                                
                                // Listen for phone registration event
                                document.addEventListener('switch-to-phone-register', (e) => {
                                    console.log('🎯 Alpine.js received switch-to-phone-register event:', e.detail);
                                    this.currentView = 'phone-register';
                                    console.log('📱 Alpine.js currentView set to:', this.currentView);
                                    // Update phone display
                                    this.$nextTick(() => {
                                        if (e.detail.phone) {
                                            console.log('📱 Updating verified phone display:', e.detail.phone);
                                            document.getElementById('verified-phone-display').textContent = e.detail.phone;
                                        }
                                    });
                                });
                                
                                // Listen for return to initial view
                                document.addEventListener('switch-to-initial', () => {
                                    this.currentView = 'initial';
                                });
                            }
                        }">
                            
                            <!-- Loading Overlay -->
                            <div id="loading-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                                <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                                    <svg class="animate-spin h-6 w-6 text-navy-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-navy-900 font-medium">Processing...</span>
                                </div>
                            </div>

                            <!-- Alert Messages -->
                            <div id="firebase-error" class="hidden mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p id="firebase-error-message" class="text-sm text-red-700 font-medium"></p>
                                </div>
                            </div>

                            <div id="firebase-success" class="hidden mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded-r-lg">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p id="firebase-success-message" class="text-sm text-green-700 font-medium"></p>
                                </div>
                            </div>

                            <!-- 🎯 INITIAL VIEW: Simple 3-Button Layout -->
                            <div x-show="currentView === 'initial'" x-transition class="space-y-6 px-1 py-1">
                                
                                <!-- 1. Mobile Number Input + Get OTP Button -->
                                <div class="space-y-4">
                                    <div>
                                        <label for="mobile-number" class="block text-sm font-medium text-gray-700 mb-2">
                                            Mobile Number
                                        </label>
                                        <div class="flex rounded-lg overflow-visible">
                                            <!-- Country Code Prefix -->
                                            <div class="flex items-center justify-center bg-gray-100 border border-gray-300 border-r-0 rounded-l-lg px-3 flex-shrink-0 min-w-[50px]">
                                                <span class="text-navy-900 text-sm font-medium whitespace-nowrap">+91</span>
                                            </div>
                                            <!-- Phone Input -->
                                            <input type="tel" 
                                                   id="mobile-number" 
                                                   name="mobile-number" 
                                                   class="w-full py-3 px-3 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500 text-base"
                                                   placeholder="XXXXXXXXXX"
                                                   maxlength="10"
                                                   pattern="[6-9][0-9]{9}"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    <!-- Invisible reCAPTCHA container -->
                                    <div id="recaptcha-container"></div>
                                    
                                    <!-- Get OTP Button - FIXED HOVER COLORS -->
                                    <button id="send-otp-btn"
                                            disabled
                                            class="w-full bg-gray-100 text-gray-600 border-2 border-gray-300 cursor-not-allowed py-3 px-4 rounded-lg font-semibold transition-all duration-200 flex items-center justify-center text-base shadow-sm hover:bg-gray-200 hover:border-gray-400 hover:shadow-md focus:ring-2 focus:ring-offset-2 focus:ring-gray-300">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M21 21H3a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v14a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Send OTP
                                    </button>
                                    
                                    <!-- Security Info (Optional) -->
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500">
                                            🛡️ Your number is protected by advanced security measures
                                        </p>
                                    </div>
                                </div>

                                <!-- Divider -->
                                <div class="relative my-6">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-300"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-4 bg-white text-gray-500 font-medium">OR</span>
                                    </div>
                                </div>

                                <!-- 2. Login with Google Button - AUTO-REGISTRATION ENABLED -->
                                <div class="space-y-2">
                                    <button id="google-login-btn" 
                                            class="w-full bg-white text-gray-700 border-2 border-gray-300 py-3 px-4 rounded-lg font-medium hover:bg-gray-50 hover:border-gray-400 hover:shadow-md focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center shadow-sm">
                                        <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                        </svg>
                                        Continue with Google
                                    </button>
                                    <!-- Helpful hint about Google's automatic account creation -->
                                    <p class="text-xs text-center text-gray-500">
                                        ✨ New users will be automatically registered
                                    </p>
                                </div>

                                <!-- Divider -->
                                <div class="relative my-6">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-300"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-4 bg-white text-gray-500 font-medium">OR</span>
                                    </div>
                                </div>

                                <!-- 3. Login with Email & Password Button - ENHANCED -->
                                <button @click="currentView = 'email'" 
                                        class="w-full bg-gray-100 text-gray-700 border-2 border-gray-300 py-3 px-4 rounded-lg font-semibold hover:bg-gray-200 hover:border-gray-400 hover:shadow-md focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center shadow-sm">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Login with Email & Password
                                </button>
                            </div>

                            <!-- 📱 OTP VERIFICATION VIEW -->
                            <div x-show="currentView === 'otp'" x-transition class="space-y-6 px-1 py-1">
                                
                                <!-- Back to Other Methods -->
                                <div class="text-center mb-6">
                                    <button @click="currentView = 'initial'" 
                                            class="text-navy-900 hover:text-navy-800 font-medium text-sm flex items-center mx-auto">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        Try other login method
                                    </button>
                                </div>

                                <!-- OTP Input Section -->
                                <div class="text-center">
                                    <h3 class="text-xl font-bold text-navy-900 mb-2">Enter OTP</h3>
                                    <p class="text-gray-600 mb-6">We've sent a 6-digit code to <span class="font-medium" id="otp-phone-display">+91 XXXXXXXXXX</span></p>
                                    
                                    <div>
                                        <input type="text" 
                                               id="otp-code" 
                                               name="otp-code" 
                                               class="w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500 text-center text-2xl tracking-widest font-mono bg-gray-50"
                                               placeholder="000000"
                                               maxlength="6"
                                               pattern="[0-9]{6}">
                                    </div>
                                </div>
                                
                                <!-- reCAPTCHA Container REMOVED FROM HERE -->
                                
                                <div class="space-y-3">
                                    <button id="verify-otp-btn" 
                                            class="w-full bg-accent text-white border-2 border-accent py-4 px-4 rounded-lg font-semibold hover:bg-red-600 hover:border-red-600 hover:shadow-md focus:ring-2 focus:ring-accent focus:ring-offset-2 transition-all duration-200 shadow-sm">
                                        Verify OTP
                                    </button>
                                    
                                    <button id="resend-otp-btn" 
                                            class="w-full text-gray-600 py-2 text-sm hover:text-navy-600 transition-colors font-medium">
                                        Resend OTP (30s)
                                    </button>
                                </div>
                                
                                <!-- OTP Messages -->
                                <div id="otp-success" class="hidden text-center text-green-600 text-sm font-medium">
                                    ✅ OTP sent successfully
                                </div>
                                <div id="otp-error" class="hidden text-center text-red-600 text-sm font-medium">
                                    ❌ Invalid number or wrong OTP
                                </div>
                            </div>

                            <!-- 📝 PHONE REGISTRATION VIEW - NEW USER INFORMATION COLLECTION -->
                            <div x-show="currentView === 'phone-register'" x-transition class="space-y-6 px-1 py-1">
                                
                                <!-- Success Icon & Message -->
                                <div class="text-center mb-6">
                                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-navy-900 mb-2">Phone Verified! 🎉</h3>
                                    <p class="text-gray-600 mb-2">Your phone number <span class="font-medium" id="verified-phone-display">+91 XXXXXXXXXX</span> has been verified.</p>
                                    <p class="text-gray-600">Please provide your details to complete registration:</p>
                                </div>

                                <!-- Registration Form for New Phone Users -->
                                <div class="space-y-4">
                                    <div>
                                        <label for="phone-register-name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Full Name *
                                        </label>
                                        <input type="text" 
                                               id="phone-register-name" 
                                               name="phone-register-name" 
                                               class="w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500"
                                               placeholder="Enter your full name"
                                               required>
                                    </div>
                                    
                                    <div>
                                        <label for="phone-register-email" class="block text-sm font-medium text-gray-700 mb-2">
                                            Email Address *
                                        </label>
                                        <input type="email" 
                                               id="phone-register-email" 
                                               name="phone-register-email" 
                                               class="w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500"
                                               placeholder="your@email.com"
                                               required>
                                        <p class="text-xs text-gray-500 mt-1">We'll use this for order updates and receipts</p>
                                    </div>
                                    
                                    <!-- Terms Acceptance -->
                                    <div class="flex items-start">
                                        <input id="phone-register-terms" 
                                               name="phone-register-terms" 
                                               type="checkbox" 
                                               class="h-4 w-4 text-navy-900 focus:ring-navy-500 border-gray-300 rounded mt-1"
                                               required>
                                        <label for="phone-register-terms" class="ml-3 block text-sm text-gray-700">
                                            I agree to TostiShop's <a href="#" class="text-navy-900 hover:text-navy-800 font-medium">Terms of Service</a> and <a href="#" class="text-navy-900 hover:text-navy-800 font-medium">Privacy Policy</a>
                                        </label>
                                    </div>
                                    
                                    <button id="complete-phone-registration-btn" 
                                            class="w-full bg-accent text-white border-2 border-accent py-4 px-4 rounded-lg font-semibold hover:bg-red-600 hover:border-red-600 hover:shadow-md focus:ring-2 focus:ring-accent focus:ring-offset-2 transition-all duration-200 shadow-sm">
                                        Create Account & Continue
                                    </button>
                                    
                                    <!-- Info Message -->
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-blue-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-sm text-blue-700">
                                                🔒 Your information is secure and will only be used for your TostiShop account.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 📧 EMAIL LOGIN/REGISTER VIEW -->
                            <div x-show="currentView === 'email'" x-transition class="space-y-6 px-1 py-1">
                                
                                <!-- Back to Other Methods -->
                                <div class="text-center mb-6">
                                    <button @click="currentView = 'initial'" 
                                            class="text-navy-900 hover:text-navy-800 font-medium text-sm flex items-center mx-auto">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        Try other login method
                                    </button>
                                </div>

                                <!-- Login/Register Toggle -->
                                <div class="flex bg-gray-50 rounded-lg p-1 mb-6">
                                    <button @click="isRegistering = false" 
                                            :class="!isRegistering ? 'bg-white text-navy-900 shadow-sm' : 'text-gray-600'" 
                                            class="flex-1 py-3 px-4 text-sm font-medium rounded-md transition-all duration-200">
                                        Sign In
                                    </button>
                                    <button @click="isRegistering = true" 
                                            :class="isRegistering ? 'bg-white text-navy-900 shadow-sm' : 'text-gray-600'" 
                                            class="flex-1 py-3 px-4 text-sm font-medium rounded-md transition-all duration-200">
                                        Register
                                    </button>
                                </div>

                                <!-- Login Form -->
                                <div x-show="!isRegistering" class="space-y-4">
                                    <div>
                                        <label for="email-login" class="block text-sm font-medium text-gray-700 mb-2">
                                            Email Address
                                        </label>
                                        <input type="email" 
                                               id="email-login" 
                                               name="email-login" 
                                               class="w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500"
                                               placeholder="your@email.com"
                                               required>
                                    </div>
                                    
                                    <div>
                                        <label for="password-login" class="block text-sm font-medium text-gray-700 mb-2">
                                            Password
                                        </label>
                                        <div class="relative" x-data="{ showPassword: false }">
                                            <input type="password" 
                                                   id="password-login" 
                                                   name="password-login" 
                                                   :type="showPassword ? 'text' : 'password'"
                                                   class="w-full py-3 px-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500"
                                                   placeholder="••••••••"
                                                   required>
                                            <button type="button" 
                                                    @click="showPassword = !showPassword"
                                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 focus:outline-none">
                                                <svg x-show="!showPassword" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <svg x-show="showPassword" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-1.563 3.029M8.12 9.12c-1.25 1.07-2.046 2.401-2.046 3.88 0 3.866 4.5 7 10.5 7 1.21 0 2.38-.16 3.5-.45"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <input id="remember-me" 
                                                   name="remember-me" 
                                                   type="checkbox" 
                                                   class="h-4 w-4 text-navy-900 focus:ring-navy-500 border-gray-300 rounded">
                                            <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                                                Remember me
                                            </label>
                                        </div>
                                        
                                        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="text-sm text-navy-900 hover:text-navy-800 font-medium">
                                            Forgot password?
                                        </a>
                                    </div>
                                    
                                    <button id="email-login-btn" 
                                            class="w-full bg-navy-900 text-white border-2 border-navy-900 py-3 px-4 rounded-lg font-semibold hover:bg-navy-800 hover:border-navy-800 hover:shadow-md focus:ring-2 focus:ring-navy-500 focus:ring-offset-2 transition-all duration-200 shadow-sm">
                                        Sign In
                                    </button>
                                    
                                    <!-- Login Error Messages -->
                                    <div id="login-error" class="hidden text-center text-red-600 text-sm font-medium">
                                        ❌ Wrong password or email doesn't exist
                                    </div>
                                </div>

                                <!-- Registration Form -->
                                <div x-show="isRegistering" class="space-y-4">
                                    <div>
                                        <label for="email-register" class="block text-sm font-medium text-gray-700 mb-2">
                                            Email Address
                                        </label>
                                        <input type="email" 
                                               id="email-register" 
                                               name="email-register" 
                                               class="w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500"
                                               placeholder="your@email.com"
                                               required>
                                    </div>
                                    
                                    <div>
                                        <label for="password-register" class="block text-sm font-medium text-gray-700 mb-2">
                                            Password
                                        </label>
                                        <div class="relative" x-data="{ showPassword: false }">
                                            <input type="password" 
                                                   id="password-register" 
                                                   name="password-register" 
                                                   :type="showPassword ? 'text' : 'password'"
                                                   class="w-full py-3 px-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500"
                                                   placeholder="••••••••"
                                                   required>
                                            <button type="button" 
                                                    @click="showPassword = !showPassword"
                                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 focus:outline-none">
                                                <svg x-show="!showPassword" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <svg x-show="showPassword" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Minimum 8 characters with at least one letter and number</p>
                                    </div>
                                    
                                    <button id="email-register-btn" 
                                            class="w-full bg-accent text-white border-2 border-accent py-3 px-4 rounded-lg font-semibold hover:bg-red-600 hover:border-red-600 hover:shadow-md focus:ring-2 focus:ring-accent focus:ring-offset-2 transition-all duration-200 shadow-sm">
                                        Register & Continue
                                    </button>
                                    
                                    <!-- Tips & Messages -->
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-blue-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-sm text-blue-700">
                                                💡 We'll create your account if it doesn't exist.
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Registration Error/Success Messages -->
                                    <div id="register-error" class="hidden text-center text-red-600 text-sm font-medium">
                                        ❌ Email already exists or registration failed
                                    </div>
                                    <div id="register-success" class="hidden text-center text-green-600 text-sm font-medium">
                                        ✅ Account created successfully! Redirecting...
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-8">
                        <p class="text-xs text-gray-500">
                            By signing in, you agree to our 
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
                            <span class="text-gray-700 text-sm">Trusted by 10,000+ users</span>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-100 rounded-full flex-shrink-0 flex items-center justify-center mr-3">
                                <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                            </div>
                            <span class="text-gray-700 text-sm">Secure Payments</span>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-100 rounded-full flex-shrink-0 flex items-center justify-center mr-3">
                                <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                            </div>
                            <span class="text-gray-700 text-sm">Fast Checkout</span>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-100 rounded-full flex-shrink-0 flex items-center justify-center mr-3">
                                <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                            </div>
                            <span class="text-gray-700 text-sm">Easy Returns</span>
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
                            <span class="text-xs font-medium">Safe Payments</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Registration Modal (Enhanced for TostiShop - Wider Desktop Layout) -->
<div id="user-registration-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md md:max-w-lg lg:max-w-xl w-full p-6 md:p-8">
        <!-- Header Section -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-gradient-to-r from-navy-900 to-accent rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-navy-900 mb-2">Complete Your Registration</h3>
            <p class="text-gray-600">We need a few more details to create your TostiShop account</p>
            <div id="auth-method-display" class="mt-3 text-sm text-accent font-medium bg-accent/10 px-3 py-1 rounded-full inline-block"></div>
        </div>
        
        <form id="complete-registration-form" class="space-y-5">
            <!-- Name Fields Row (Side by Side on Desktop) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- First Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" id="user-first-name" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all"
                           placeholder="Enter your first name" required>
                </div>
                
                <!-- Last Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                    <input type="text" id="user-last-name" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all"
                           placeholder="Enter your last name" required>
                </div>
            </div>
            
            <!-- Contact Information Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Email Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" id="user-email" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all"
                           placeholder="Enter your email address" required>
                    <p class="text-xs text-gray-500 mt-1">For order updates and account recovery</p>
                </div>
                
                <!-- Phone display (for phone auth) -->
                <div id="phone-auth-display" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <div class="relative">
                        <input type="text" id="user-phone-display" 
                               class="w-full px-4 py-3 pr-12 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" 
                               readonly>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <div class="flex items-center space-x-1 text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                </svg>
                                <span class="text-xs font-medium">Verified</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-green-600 mt-1">✅ Verified via SMS authentication</p>
                </div>
                
                <!-- Email takes full width when phone is hidden -->
                <div id="email-full-width" class="md:hidden">
                    <!-- This div will be shown when phone auth is not used -->
                </div>
            </div>
            
            <!-- Terms and Privacy Notice -->
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-sm text-gray-600">
                        <p>* Required fields. By creating an account, you agree to our 
                        <a href="#" class="text-accent hover:text-red-600 font-medium">Terms of Service</a> and 
                        <a href="#" class="text-accent hover:text-red-600 font-medium">Privacy Policy</a>.</p>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons - Enhanced Spacing for Desktop -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6">
                <button type="button" id="cancel-registration" 
                        class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium">
                    Cancel
                </button>
                <button type="submit" id="complete-registration-btn"
                        class="flex-1 px-6 py-3 bg-accent text-white rounded-lg hover:bg-red-600 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                    <span class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Create TostiShop Account</span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 🚨 EXISTING EMAIL CONFIRMATION MODAL -->
<div id="existing-email-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl max-w-md mx-4 w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="border-b border-gray-100 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-navy-900">Account Already Exists</h3>
                        <p class="text-sm text-gray-600">We found an existing account</p>
                    </div>
                </div>
                <button id="close-existing-email-modal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Content -->
        <div class="px-6 py-6">
            <!-- Account Info Display -->
            <div class="bg-blue-50 rounded-lg p-4 mb-6 border border-blue-200">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-blue-900" id="existing-email-message">
                            <!-- Dynamic message will be inserted here -->
                        </p>
                        <div class="mt-2 space-y-1">
                            <p class="text-xs text-blue-700">
                                <span class="font-medium">📧 Email:</span> 
                                <span id="existing-email-display" class="font-mono"></span>
                            </p>
                            <p class="text-xs text-blue-700">
                                <span class="font-medium">📱 Phone:</span> 
                                <span id="existing-phone-display" class="font-mono"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Options -->
            <div class="space-y-4">
                <div class="text-center">
                    <p class="text-gray-700 mb-4">🔐 To link your phone to this account, password verification is required.</p>
                </div>

                <!-- Primary Action: Sign In -->
                <button id="sign-in-existing-btn" 
                        class="w-full bg-accent text-white py-4 px-6 rounded-lg font-semibold hover:bg-red-600 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <span class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Link Phone to Account</span>
                    </span>
                </button>

                <!-- Secondary Action: Use Different Email -->
                <button id="use-different-email-btn" 
                        class="w-full border-2 border-gray-300 text-gray-700 py-3 px-6 rounded-lg font-medium hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                    <span class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        <span>Use Different Email Address</span>
                    </span>
                </button>
            </div>

            <!-- Help Text -->
            <div class="mt-6 pt-4 border-t border-gray-100">
                <div class="flex items-start space-x-2">
                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-xs text-gray-500">
                        💡 <strong>Tip:</strong> If you can't remember your password, you can reset it after signing in using the "Forgot Password" option.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🔗 ACCOUNT BINDING MODAL - SECURE PASSWORD VERIFICATION -->
<div id="account-binding-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Verify Account Ownership</h3>
                    <p class="text-sm text-gray-600">Link phone number to existing account</p>
                </div>
            </div>
            <button id="close-binding-modal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="px-6 py-6">
            <!-- Security Notice -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <h4 class="font-medium text-yellow-800 mb-1">Security Verification Required</h4>
                        <p class="text-sm text-yellow-700">
                            To link your phone number to this existing account, please verify your password.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Account Details -->
            <div class="bg-blue-50 rounded-lg p-4 mb-6 border border-blue-200">
                <div class="space-y-2">
                    <p class="text-sm">
                        <span class="font-medium text-blue-900">📧 Email:</span> 
                        <span id="binding-email-display" class="text-blue-800"></span>
                    </p>
                    <p class="text-sm">
                        <span class="font-medium text-blue-900">📱 Phone:</span> 
                        <span id="binding-phone-display" class="text-blue-800 font-mono"></span>
                    </p>
                </div>
            </div>

            <!-- Password Verification -->
            <div class="space-y-4">
                <div>
                    <label for="binding-password" class="block text-sm font-medium text-gray-700 mb-2">
                        Enter Your Account Password
                    </label>
                    <input type="password" 
                           id="binding-password" 
                           name="binding-password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all"
                           placeholder="Enter your password"
                           required>
                    <p class="text-xs text-gray-500 mt-1">This verifies that you own the email account</p>
                </div>
            </div>
        </div>

        <!-- Modal Actions -->
        <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 space-y-reverse sm:space-y-0">
            <button id="cancel-binding-btn" 
                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button id="verify-and-bind-btn" 
                    class="w-full sm:w-auto bg-accent text-white px-6 py-2 rounded-lg font-medium hover:bg-red-600 transition-colors">
                Verify & Link Account
            </button>
        </div>
    </div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>

<!-- Enhanced Modal Styling for Desktop -->
<style>
/* Modal backdrop blur and improved animations */
#user-registration-modal {
    backdrop-filter: blur(4px);
    animation: modalFadeIn 0.3s ease-out;
}

#user-registration-modal > div {
    animation: modalSlideIn 0.3s ease-out;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes modalSlideIn {
    from { 
        opacity: 0; 
        transform: translateY(-20px) scale(0.95); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

/* Prevent body scroll when modal is open */
body.modal-open {
    overflow: hidden;
}

/* Enhanced focus states for form fields */
#user-first-name:focus,
#user-last-name:focus,
#user-email:focus {
    box-shadow: 0 0 0 3px rgba(228, 32, 41, 0.1);
    border-color: #e42029;
}

/* Better button spacing on mobile */
@media (max-width: 640px) {
    #user-registration-modal .flex.flex-col.sm\\:flex-row {
        flex-direction: column;
    }
    
    #user-registration-modal .flex.flex-col.sm\\:flex-row > button {
        width: 100%;
        margin-bottom: 0.75rem;
    }
    
    #user-registration-modal .flex.flex-col.sm\\:flex-row > button:last-child {
        margin-bottom: 0;
    }
}

/* Better visual hierarchy */
#auth-method-display {
    background: linear-gradient(135deg, rgba(228, 32, 41, 0.1) 0%, rgba(20, 23, 91, 0.1) 100%);
}

/* Enhanced verified phone styling */
#phone-auth-display .text-green-600 {
    background: rgba(16, 185, 129, 0.1);
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
}

/* Loading state for registration button */
#complete-registration-btn:disabled {
    cursor: not-allowed;
    opacity: 0.7;
    transform: none;
}

#complete-registration-btn:disabled:hover {
    background-color: #e42029;
    transform: none;
}

/* 🚨 Existing Email Modal Styling */
#existing-email-modal {
    backdrop-filter: blur(8px);
    z-index: 9999;
    animation: modalFadeIn 0.3s ease-out;
}

#existing-email-modal .bg-white {
    animation: modalSlideIn 0.3s ease-out;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
}

/* Enhanced focus states for modal buttons */
#existing-email-modal button:focus {
    outline: 2px solid #e42029;
    outline-offset: 2px;
    box-shadow: 0 0 0 4px rgba(228, 32, 41, 0.1);
}

/* Smooth hover transitions for modal buttons */
#sign-in-existing-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(228, 32, 41, 0.3);
}

#use-different-email-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Account info styling */
#existing-email-modal .bg-blue-50 {
    background: linear-gradient(135deg, rgb(239 246 255) 0%, rgb(219 234 254) 100%);
}

/* Better mobile responsiveness for existing email modal */
@media (max-width: 640px) {
    #existing-email-modal .max-w-md {
        max-width: calc(100vw - 2rem);
        margin: 1rem;
    }
}

/* 🔗 Account Binding Modal Styling - SECURE PASSWORD VERIFICATION */
#account-binding-modal {
    backdrop-filter: blur(8px);
    z-index: 9999;
    animation: modalFadeIn 0.3s ease-out;
}

#account-binding-modal .bg-white {
    animation: modalSlideIn 0.3s ease-out;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
}

/* Enhanced focus states for binding modal */
#account-binding-modal button:focus {
    outline: 2px solid #e42029;
    outline-offset: 2px;
    box-shadow: 0 0 0 4px rgba(228, 32, 41, 0.1);
}

#account-binding-modal input:focus {
    ring-color: #e42029;
    border-color: #e42029;
}

/* Smooth hover transitions for binding modal buttons */
#verify-and-bind-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(228, 32, 41, 0.3);
}

#cancel-binding-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Security notice styling */
#account-binding-modal .bg-yellow-50 {
    background: linear-gradient(135deg, rgb(254 252 232) 0%, rgb(254 240 138) 100%);
}

/* Account info styling for binding modal */
#account-binding-modal .bg-blue-50 {
    background: linear-gradient(135deg, rgb(239 246 255) 0%, rgb(219 234 254) 100%);
}

/* Better mobile responsiveness for binding modal */
@media (max-width: 640px) {
    #account-binding-modal .max-w-md {
        max-width: calc(100vw - 2rem);
        margin: 1rem;
    }
    
    #account-binding-modal .sm\\:flex-row {
        flex-direction: column-reverse;
    }
    
    #account-binding-modal .sm\\:space-x-3 {
        space-x: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enable/disable Send OTP button based on mobile number input
    const mobileInput = document.getElementById('mobile-number');
    const sendOtpBtn = document.getElementById('send-otp-btn');
    
    if (mobileInput && sendOtpBtn) {
        function validateAndUpdateButton() {
            const value = mobileInput.value.trim();
            const isValid = /^[6-9][0-9]{9}$/.test(value);
            
            if (isValid) {
                sendOtpBtn.disabled = false;
                sendOtpBtn.classList.remove('bg-gray-100', 'text-gray-600', 'border-gray-300', 'cursor-not-allowed');
                sendOtpBtn.classList.add('bg-accent', 'text-white', 'border-accent', 'cursor-pointer', 'hover:bg-red-600', 'hover:border-red-600');
            } else {
                sendOtpBtn.disabled = true;
                sendOtpBtn.classList.remove('bg-accent', 'text-white', 'border-accent', 'cursor-pointer', 'hover:bg-red-600', 'hover:border-red-600');
                sendOtpBtn.classList.add('bg-gray-100', 'text-gray-600', 'border-gray-300', 'cursor-not-allowed');
            }
        }
        
        // Check on input and paste events
        mobileInput.addEventListener('input', validateAndUpdateButton);
        mobileInput.addEventListener('paste', function() {
            setTimeout(validateAndUpdateButton, 10);
        });
        
        // Initial check
        validateAndUpdateButton();
    }
    
    // Add country code input element
    const countryCodeInput = document.createElement('input');
    countryCodeInput.type = 'hidden';
    countryCodeInput.id = 'country-code';
    countryCodeInput.value = '+91';
    document.body.appendChild(countryCodeInput);
});
</script>