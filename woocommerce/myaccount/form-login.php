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
                    <div class="rounded-2xl overflow-hidden">
                        
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
                                    
                                    <!-- ✅ NO MORE VISIBLE reCAPTCHA - IT'S NOW INVISIBLE! -->
                                    
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

                                <!-- 2. Login with Google Button - ENHANCED WITH PROPER BORDER -->
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

<!-- User Registration Modal (for new Firebase users) -->
<div id="user-registration-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="text-center mb-6">
            <h3 class="text-xl font-bold text-navy-900 mb-2">Complete Your Registration</h3>
            <p class="text-gray-600">We need a few more details to create your account</p>
        </div>
        
        <form id="complete-registration-form" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                <input type="text" id="user-full-name" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                       placeholder="Enter your full name" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                <input type="email" id="user-email" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                       placeholder="Enter your email address" required>
            </div>
            
            <div class="text-xs text-gray-500">
                <p>* Required fields. This information will be used to create your TostiShop account.</p>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="button" id="cancel-registration" 
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" id="complete-registration-btn"
                        class="flex-1 px-4 py-2 bg-accent text-white rounded-md hover:bg-red-600">
                    Complete Registration
                </button>
            </div>
        </form>
    </div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>