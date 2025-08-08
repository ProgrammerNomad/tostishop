/**
 * Firebase Authentication for TostiShop WooCommerce Login
 * Mobile-First Authentication with OTP, Google, and Email fallback
 * Enhanced with Phone Registration Flow for New Users
 */

class TostiShopFirebaseAuth {
    constructor() {
        this.firebaseConfig = window.tostiShopFirebaseConfig || {};
        this.currentUser = null;
        this.confirmationResult = null;
        this.pendingPhoneUser = null; // Store pending phone user for registration
        this.init();
    }

    async init() {
        try {
            // Wait for Firebase to load
            if (typeof firebase === 'undefined') {
                console.error('Firebase SDK not loaded');
                return;
            }

            // Initialize Firebase
            if (!firebase.apps.length) {
                firebase.initializeApp(this.firebaseConfig);
            }

            this.auth = firebase.auth();
            this.setupAuthStateListener();
            this.setupEventListeners();
            
            // Setup reCAPTCHA only if container exists and not already setup
            const recaptchaContainer = document.getElementById('recaptcha-container');
            if (recaptchaContainer && !this.recaptchaVerifier) {
                this.setupReCaptcha();
            }

            console.log('TostiShop Firebase Auth initialized');
        } catch (error) {
            console.error('Firebase initialization error:', error);
            this.showError('Authentication service unavailable. Please try email login.');
        }
    }

    setupAuthStateListener() {
        this.auth.onAuthStateChanged((user) => {
            this.currentUser = user;
            if (user) {
                console.log('User signed in:', user.uid);
                this.handleSuccessfulLogin(user);
            } else {
                console.log('User signed out');
            }
        });
    }

    setupEventListeners() {
        // Mobile OTP Events
        document.getElementById('send-otp-btn')?.addEventListener('click', () => this.sendOTP());
        document.getElementById('verify-otp-btn')?.addEventListener('click', () => this.verifyOTP());
        document.getElementById('resend-otp-btn')?.addEventListener('click', () => this.resendOTP());

        // Google Login Event
        document.getElementById('google-login-btn')?.addEventListener('click', () => this.signInWithGoogle());

        // Phone Registration Events
        document.getElementById('complete-phone-registration-btn')?.addEventListener('click', (e) => {
            e.preventDefault();
            this.completePhoneRegistration();
        });

        // Mobile number formatting
        const mobileInput = document.getElementById('mobile-number');
        if (mobileInput) {
            mobileInput.addEventListener('input', this.formatMobileNumber.bind(this));
            mobileInput.addEventListener('keypress', this.onlyNumbers.bind(this));
        }

        // OTP input formatting
        const otpInput = document.getElementById('otp-code');
        if (otpInput) {
            otpInput.addEventListener('input', this.formatOTPInput.bind(this));
            otpInput.addEventListener('keypress', this.onlyNumbers.bind(this));
        }
    }

    setupReCaptcha() {
        try {
            // Clear existing reCAPTCHA container
            const recaptchaContainer = document.getElementById('recaptcha-container');
            if (recaptchaContainer) {
                recaptchaContainer.innerHTML = '';
            }

            // Destroy existing verifier if it exists
            if (this.recaptchaVerifier) {
                try {
                    this.recaptchaVerifier.clear();
                } catch (e) {
                    // Ignore clear errors
                }
                this.recaptchaVerifier = null;
            }

            this.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                'size': 'normal',
                'callback': (response) => {
                    console.log('reCAPTCHA solved');
                },
                'expired-callback': () => {
                    console.log('reCAPTCHA expired');
                    this.showError('Security verification expired. Please try again.');
                },
                'theme': 'light'
            });

            return this.recaptchaVerifier.render();
        } catch (error) {
            console.error('reCAPTCHA setup error:', error);
            
            // If it's already rendered error, try to clear and retry once
            if (error.message && error.message.includes('already been rendered')) {
                console.log('Attempting to recover from reCAPTCHA already rendered error...');
                const recaptchaContainer = document.getElementById('recaptcha-container');
                if (recaptchaContainer) {
                    recaptchaContainer.innerHTML = '';
                    // Wait and try again
                    setTimeout(() => {
                        try {
                            this.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                                'size': 'normal',
                                'callback': (response) => {
                                    console.log('reCAPTCHA solved');
                                },
                                'expired-callback': () => {
                                    console.log('reCAPTCHA expired');
                                    this.showError('Security verification expired. Please try again.');
                                },
                                'theme': 'light'
                            });
                            this.recaptchaVerifier.render();
                        } catch (retryError) {
                            console.error('reCAPTCHA retry failed:', retryError);
                            this.showError('Please refresh the page and try again.');
                        }
                    }, 500);
                }
            } else {
                this.showError('Security verification unavailable. Please try email login.');
            }
        }
    }

    async sendOTP() {
        try {
            const countryCode = document.getElementById('country-code').value;
            const mobileNumber = document.getElementById('mobile-number').value;
            
            if (!this.validateMobileNumber(mobileNumber)) {
                this.showError('Please enter a valid 10-digit mobile number.');
                return;
            }

            const fullPhoneNumber = countryCode + mobileNumber;
            
            this.showLoading();
            
            // Ensure reCAPTCHA is ready
            if (!this.recaptchaVerifier) {
                this.setupReCaptcha();
                // Wait for reCAPTCHA to be ready
                await new Promise(resolve => setTimeout(resolve, 1000));
            }
            
            this.confirmationResult = await this.auth.signInWithPhoneNumber(
                fullPhoneNumber, 
                this.recaptchaVerifier
            );

            console.log('OTP sent successfully');
            this.showOTPSection();
            this.showSuccess('OTP sent to your mobile number. Please check your SMS.');
            this.hideLoading();

        } catch (error) {
            console.error('OTP send error:', error);
            this.hideLoading();
            
            let errorMessage = 'Failed to send OTP. Please try again.';
            
            if (error.code === 'auth/invalid-phone-number') {
                errorMessage = 'Invalid phone number format.';
            } else if (error.code === 'auth/too-many-requests') {
                errorMessage = 'Too many requests. Please try again later.';
            } else if (error.code === 'auth/captcha-check-failed') {
                errorMessage = 'Security verification failed. Please refresh and try again.';
            } else if (error.message && error.message.includes('reCAPTCHA')) {
                errorMessage = 'Please refresh the page and try again.';
                // Reset reCAPTCHA for next attempt
                this.recaptchaVerifier = null;
            }
            
            this.showError(errorMessage);
        }
    }

    async verifyOTP() {
        try {
            const otpCode = document.getElementById('otp-code').value;
            
            if (!this.validateOTP(otpCode)) {
                this.showError('Please enter a valid 6-digit OTP.');
                return;
            }

            if (!this.confirmationResult) {
                this.showError('Please request OTP first.');
                return;
            }

            this.showLoading();

            const result = await this.confirmationResult.confirm(otpCode);
            console.log('OTP verified successfully:', result.user.uid);
            
            // User is automatically signed in, onAuthStateChanged will handle the rest

        } catch (error) {
            console.error('OTP verification error:', error);
            this.hideLoading();
            
            let errorMessage = 'Invalid OTP. Please try again.';
            
            if (error.code === 'auth/invalid-verification-code') {
                errorMessage = 'The OTP you entered is incorrect.';
            } else if (error.code === 'auth/code-expired') {
                errorMessage = 'OTP has expired. Please request a new one.';
            }
            
            this.showError(errorMessage);
        }
    }

    async resendOTP() {
        try {
            this.showLoading();
            
            // Properly clear and reset reCAPTCHA
            if (this.recaptchaVerifier) {
                try {
                    this.recaptchaVerifier.clear();
                } catch (clearError) {
                    console.log('reCAPTCHA clear error (expected):', clearError.message);
                }
                this.recaptchaVerifier = null;
            }
            
            // Clear the container and setup fresh reCAPTCHA
            const recaptchaContainer = document.getElementById('recaptcha-container');
            if (recaptchaContainer) {
                recaptchaContainer.innerHTML = '';
            }
            
            // Wait a moment for DOM to be ready
            await new Promise(resolve => setTimeout(resolve, 500));
            
            // Setup fresh reCAPTCHA
            this.setupReCaptcha();
            
            // Wait another moment for reCAPTCHA to be ready
            await new Promise(resolve => setTimeout(resolve, 1000));
            
            // Send new OTP
            await this.sendOTP();
            
            this.hideLoading();

        } catch (error) {
            console.error('Resend OTP error:', error);
            this.hideLoading();
            this.showError('Failed to resend OTP. Please refresh the page and try again.');
        }
    }

    async signInWithGoogle() {
        try {
            this.showLoading();
            
            const provider = new firebase.auth.GoogleAuthProvider();
            provider.addScope('email');
            provider.addScope('profile');

            const result = await this.auth.signInWithPopup(provider);
            console.log('Google sign-in successful:', result.user.uid);
            
            // User is automatically signed in, onAuthStateChanged will handle the rest

        } catch (error) {
            console.error('Google sign-in error:', error);
            this.hideLoading();
            
            let errorMessage = 'Google sign-in failed. Please try again.';
            
            if (error.code === 'auth/popup-closed-by-user') {
                errorMessage = 'Sign-in cancelled.';
            } else if (error.code === 'auth/popup-blocked') {
                errorMessage = 'Pop-up blocked. Please allow pop-ups and try again.';
            }
            
            this.showError(errorMessage);
        }
    }

    async handleSuccessfulLogin(user) {
        try {
            this.showLoading();
            
            // Get ID token for WordPress authentication
            const idToken = await user.getIdToken();
            
            // Extract user information from Firebase user object
            const userData = {
                action: 'tostishop_firebase_login',
                firebase_token: idToken,
                nonce: tostiShopAjax.nonce,
                
                // Real user data from Firebase
                user_uid: user.uid,
                user_email: user.email || '',
                user_name: user.displayName || '',
                phone_number: user.phoneNumber || '',
                email_verified: user.emailVerified || false,
                
                // Provider information
                auth_method: this.getAuthMethod(user),
                
                // Additional context
                from_checkout: window.location.pathname.includes('checkout'),
                check_user_exists: true // Add flag to check if user exists first
            };
            
            console.log('🔥 Checking user existence in WordPress:', userData);
            
            // Send to WordPress backend to check if user exists
            const response = await fetch(tostiShopAjax.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(userData)
            });

            const data = await response.json();
            
            if (data.success) {
                // User exists, proceed with login
                this.showSuccess('Login successful! Redirecting...');
                
                // Redirect after successful login
                setTimeout(() => {
                    window.location.href = data.data.redirect_url || '/my-account/';
                }, 1500);
                
            } else if (data.data && data.data.code === 'user_not_found' && this.getAuthMethod(user) === 'phone') {
                // New phone user - show registration form
                this.hideLoading();
                this.showPhoneRegistrationForm(user);
                
            } else if (data.data && data.data.code === 'user_not_found' && this.getAuthMethod(user) === 'google') {
                // New Google user - auto-register since Google provides name and email
                await this.completeGoogleRegistration(user);
                
            } else {
                throw new Error(data.data.message || 'Login failed');
            }

        } catch (error) {
            console.error('WordPress login error:', error);
            this.hideLoading();
            this.showError('Login failed. Please try again or use email login.');
            
            // Sign out from Firebase since WordPress login failed
            this.auth.signOut();
        }
    }

    // New method to show phone registration form
    showPhoneRegistrationForm(user) {
        // Store user data for later use
        this.pendingPhoneUser = user;
        
        // Update the phone display in the form
        const phoneDisplay = document.getElementById('verified-phone-display');
        if (phoneDisplay && user.phoneNumber) {
            phoneDisplay.textContent = user.phoneNumber;
        }
        
        // Switch to registration view using Alpine.js
        const authContainer = document.querySelector('[x-data]');
        if (authContainer && authContainer._x_dataStack && authContainer._x_dataStack[0]) {
            authContainer._x_dataStack[0].currentView = 'phone-register';
        }
        
        console.log('📱 Showing phone registration form for new user');
    }

    // New method to handle Google auto-registration
    async completeGoogleRegistration(user) {
        try {
            this.showLoading();
            
            // Get ID token for WordPress authentication
            const idToken = await user.getIdToken();
            
            // Send complete registration data to WordPress
            const userData = {
                action: 'tostishop_firebase_login',
                firebase_token: idToken,
                nonce: tostiShopAjax.nonce,
                
                // Real user data from Firebase
                user_uid: user.uid,
                user_email: user.email || '',
                user_name: user.displayName || '',
                phone_number: user.phoneNumber || '',
                email_verified: user.emailVerified || false,
                
                // Provider information
                auth_method: this.getAuthMethod(user),
                
                // Additional context
                from_checkout: window.location.pathname.includes('checkout'),
                force_registration: true // Force account creation
            };
            
            console.log('🔥 Auto-registering Google user:', userData);
            
            const response = await fetch(tostiShopAjax.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(userData)
            });

            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('Welcome to TostiShop! Account created successfully. Redirecting...');
                
                // Redirect after successful registration
                setTimeout(() => {
                    window.location.href = data.data.redirect_url || '/my-account/';
                }, 1500);
                
            } else {
                throw new Error(data.data.message || 'Registration failed');
            }

        } catch (error) {
            console.error('Google auto-registration error:', error);
            this.hideLoading();
            this.showError('Registration failed. Please try again or use email registration.');
            
            // Sign out from Firebase since WordPress registration failed
            this.auth.signOut();
        }
    }

    // New method to complete phone registration
    async completePhoneRegistration() {
        try {
            // Get form data
            const name = document.getElementById('phone-register-name').value.trim();
            const email = document.getElementById('phone-register-email').value.trim();
            const termsAccepted = document.getElementById('phone-register-terms').checked;
            
            // Validate form
            if (!name) {
                this.showError('Please enter your full name.');
                return;
            }
            
            if (!email || !this.validateEmail(email)) {
                this.showError('Please enter a valid email address.');
                return;
            }
            
            if (!termsAccepted) {
                this.showError('Please accept the Terms of Service and Privacy Policy.');
                return;
            }
            
            if (!this.pendingPhoneUser) {
                this.showError('Session expired. Please start over.');
                return;
            }
            
            this.showLoading();
            
            // Get ID token for WordPress authentication
            const idToken = await this.pendingPhoneUser.getIdToken();
            
            // Send complete registration data to WordPress
            const userData = {
                action: 'tostishop_firebase_login',
                firebase_token: idToken,
                nonce: tostiShopAjax.nonce,
                
                // Real user data from Firebase
                user_uid: this.pendingPhoneUser.uid,
                user_email: email, // Use form email
                user_name: name, // Use form name
                phone_number: this.pendingPhoneUser.phoneNumber || '',
                email_verified: false, // Phone users need to verify email separately
                
                // Provider information
                auth_method: 'phone',
                
                // Additional context
                from_checkout: window.location.pathname.includes('checkout'),
                force_registration: true // Force account creation
            };
            
            console.log('🔥 Completing phone registration:', userData);
            
            const response = await fetch(tostiShopAjax.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(userData)
            });

            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('Welcome to TostiShop! Account created successfully. Redirecting...');
                
                // Clear pending user data
                this.pendingPhoneUser = null;
                
                // Redirect after successful registration
                setTimeout(() => {
                    window.location.href = data.data.redirect_url || '/my-account/';
                }, 1500);
                
            } else {
                throw new Error(data.data.message || 'Registration failed');
            }

        } catch (error) {
            console.error('Phone registration completion error:', error);
            this.hideLoading();
            this.showError('Registration failed. Please try again.');
        }
    }

    // Helper method to validate email
    validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Helper method to determine authentication method
    getAuthMethod(user) {
        if (user.phoneNumber) {
            return 'phone';
        }
        
        if (user.providerData && user.providerData.length > 0) {
            const provider = user.providerData[0];
            if (provider.providerId === 'google.com') {
                return 'google';
            } else if (provider.providerId === 'password') {
                return 'email';
            }
        }
        
        return 'email'; // default
    }

    // Utility Methods
    validateMobileNumber(number) {
        return /^[0-9]{10}$/.test(number);
    }

    validateOTP(otp) {
        return /^[0-9]{6}$/.test(otp);
    }

    formatMobileNumber(event) {
        let value = event.target.value.replace(/\D/g, '');
        if (value.length > 10) {
            value = value.slice(0, 10);
        }
        event.target.value = value;
    }

    formatOTPInput(event) {
        let value = event.target.value.replace(/\D/g, '');
        if (value.length > 6) {
            value = value.slice(0, 6);
        }
        event.target.value = value;
    }

    onlyNumbers(event) {
        const char = String.fromCharCode(event.which);
        if (!/[0-9]/.test(char)) {
            event.preventDefault();
        }
    }

    showOTPSection() {
        // Switch to OTP view using Alpine.js
        const authContainer = document.querySelector('[x-data]');
        if (authContainer && authContainer._x_dataStack && authContainer._x_dataStack[0]) {
            authContainer._x_dataStack[0].currentView = 'otp';
        }
        
        // Update phone display
        const phoneDisplay = document.getElementById('otp-phone-display');
        const countryCode = document.getElementById('country-code').value;
        const mobileNumber = document.getElementById('mobile-number').value;
        if (phoneDisplay) {
            phoneDisplay.textContent = countryCode + ' ' + mobileNumber;
        }
        
        // Focus OTP input
        setTimeout(() => {
            document.getElementById('otp-code')?.focus();
        }, 100);
    }

    showLoading() {
        document.getElementById('loading-overlay').classList.remove('hidden');
    }

    hideLoading() {
        document.getElementById('loading-overlay').classList.add('hidden');
    }

    showError(message) {
        const errorDiv = document.getElementById('firebase-error');
        const messageDiv = document.getElementById('firebase-error-message');
        
        messageDiv.textContent = message;
        errorDiv.classList.remove('hidden');
        
        // Hide success message if shown
        document.getElementById('firebase-success').classList.add('hidden');
        
        // Auto-hide after 10 seconds
        setTimeout(() => {
            errorDiv.classList.add('hidden');
        }, 10000);
    }

    showSuccess(message) {
        const successDiv = document.getElementById('firebase-success');
        const messageDiv = document.getElementById('firebase-success-message');
        
        messageDiv.textContent = message;
        successDiv.classList.remove('hidden');
        
        // Hide error message if shown
        document.getElementById('firebase-error').classList.add('hidden');
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            successDiv.classList.add('hidden');
        }, 5000);
    }

    // Public method to sign out
    signOut() {
        return this.auth.signOut();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize on login pages
    if (document.getElementById('firebase-auth-container') || document.querySelector('[x-data]')) {
        window.tostiShopAuth = new TostiShopFirebaseAuth();
    }
});

// Export for use in other scripts
window.TostiShopFirebaseAuth = TostiShopFirebaseAuth;
