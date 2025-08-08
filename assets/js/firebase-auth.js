/**
 * Firebase Authentication for TostiShop WooCommerce Login
 * Mobile-First Authentication with OTP, Google, and Email fallback
 */

class TostiShopFirebaseAuth {
    constructor() {
        this.firebaseConfig = window.tostiShopFirebaseConfig || {};
        this.currentUser = null;
        this.confirmationResult = null;
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
            this.setupReCaptcha();

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
        } catch (error) {
            console.error('reCAPTCHA setup error:', error);
            this.showError('Security verification unavailable. Please try email login.');
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
            // Reset reCAPTCHA
            this.recaptchaVerifier.clear();
            this.setupReCaptcha();
            
            // Wait a moment then resend
            setTimeout(() => {
                this.sendOTP();
            }, 1000);

        } catch (error) {
            console.error('Resend OTP error:', error);
            this.showError('Failed to resend OTP. Please try again.');
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
                from_checkout: window.location.pathname.includes('checkout')
            };
            
            console.log('🔥 Sending user data to WordPress:', userData);
            
            // Send to WordPress backend for user creation/login
            const response = await fetch(tostiShopAjax.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(userData)
            });

            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('Login successful! Redirecting...');
                
                // Redirect after successful login
                setTimeout(() => {
                    window.location.href = data.data.redirect_url || '/my-account/';
                }, 1500);
                
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

            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('Login successful! Redirecting...');
                
                // Redirect after successful login
                setTimeout(() => {
                    window.location.href = data.data.redirect_url || '/my-account/';
                }, 1500);
                
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
        document.getElementById('phone-input-section').style.display = 'none';
        document.getElementById('otp-verification-section').classList.remove('hidden');
        document.getElementById('otp-code').focus();
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
    if (document.getElementById('firebase-auth-container')) {
        window.tostiShopAuth = new TostiShopFirebaseAuth();
    }
});

// Export for use in other scripts
window.TostiShopFirebaseAuth = TostiShopFirebaseAuth;
            
        } catch (error) {
            console.error('Firebase initialization error:', error);
            this.showError('Failed to initialize authentication system');
        }
    }
    
    /**
     * Set up reCAPTCHA verifier
     */
    setupRecaptcha() {
        try {
            this.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                'size': 'normal',
                'callback': (response) => {
                    console.log('reCAPTCHA solved');
                },
                'expired-callback': () => {
                    console.log('reCAPTCHA expired');
                    this.showError('reCAPTCHA expired. Please try again.');
                }
            });
            
            this.recaptchaVerifier.render();
        } catch (error) {
            console.error('reCAPTCHA setup error:', error);
        }
    }
    
    /**
     * Bind event listeners
     */
    bindEvents() {
        // Phone authentication
        const phoneForm = document.getElementById('firebase-phone-form');
        if (phoneForm) {
            phoneForm.addEventListener('submit', (e) => this.handlePhoneSubmit(e));
        }
        
        const otpForm = document.getElementById('firebase-otp-form');
        if (otpForm) {
            otpForm.addEventListener('submit', (e) => this.handleOtpSubmit(e));
        }
        
        const resendBtn = document.getElementById('resend-otp-btn');
        if (resendBtn) {
            resendBtn.addEventListener('click', () => this.resendOtp());
        }
        
        // Google authentication
        const googleBtn = document.getElementById('google-login-btn');
        if (googleBtn) {
            googleBtn.addEventListener('click', () => this.handleGoogleLogin());
        }
        
        // Email authentication
        const emailForm = document.getElementById('firebase-email-form');
        if (emailForm) {
            emailForm.addEventListener('submit', (e) => this.handleEmailSubmit(e));
        }
        
        // OTP input auto-formatting
        const otpInput = document.getElementById('otp-code');
        if (otpInput) {
            otpInput.addEventListener('input', (e) => this.formatOtpInput(e));
        }
    }
    
    /**
     * Handle phone number submission
     */
    async handlePhoneSubmit(e) {
        e.preventDefault();
        
        const countryCode = document.getElementById('country-code').value;
        const phoneNumber = document.getElementById('phone-number').value;
        
        if (!phoneNumber || phoneNumber.length < 10) {
            this.showError('Please enter a valid phone number');
            return;
        }
        
        this.phoneNumber = countryCode + phoneNumber;
        this.showLoading(true);
        
        try {
            this.confirmationResult = await this.auth.signInWithPhoneNumber(
                this.phoneNumber, 
                this.recaptchaVerifier
            );
            
            // Show OTP form
            document.getElementById('firebase-phone-form').style.display = 'none';
            document.getElementById('firebase-otp-form').style.display = 'block';
            
            this.showSuccess('OTP sent to ' + this.phoneNumber);
            
        } catch (error) {
            console.error('Phone auth error:', error);
            this.showError(this.getErrorMessage(error));
            
            // Reset reCAPTCHA on error
            if (this.recaptchaVerifier) {
                this.recaptchaVerifier.clear();
                this.setupRecaptcha();
            }
        } finally {
            this.showLoading(false);
        }
    }
    
    /**
     * Handle OTP verification
     */
    async handleOtpSubmit(e) {
        e.preventDefault();
        
        const otpCode = document.getElementById('otp-code').value;
        
        if (!otpCode || otpCode.length !== 6) {
            this.showError('Please enter the 6-digit OTP');
            return;
        }
        
        this.showLoading(true);
        
        try {
            const result = await this.confirmationResult.confirm(otpCode);
            console.log('Phone auth successful:', result.user);
            
            // User will be handled by onAuthStateChanged
            
        } catch (error) {
            console.error('OTP verification error:', error);
            this.showError('Invalid OTP. Please try again.');
            
            // Clear OTP input
            document.getElementById('otp-code').value = '';
        } finally {
            this.showLoading(false);
        }
    }
    
    /**
     * Resend OTP
     */
    async resendOtp() {
        if (!this.phoneNumber) {
            this.showError('Phone number not found. Please start over.');
            return;
        }
        
        this.showLoading(true);
        
        try {
            // Reset reCAPTCHA
            if (this.recaptchaVerifier) {
                this.recaptchaVerifier.clear();
                this.setupRecaptcha();
            }
            
            this.confirmationResult = await this.auth.signInWithPhoneNumber(
                this.phoneNumber, 
                this.recaptchaVerifier
            );
            
            this.showSuccess('OTP resent to ' + this.phoneNumber);
            
        } catch (error) {
            console.error('Resend OTP error:', error);
            this.showError(this.getErrorMessage(error));
        } finally {
            this.showLoading(false);
        }
    }
    
    /**
     * Handle Google login
     */
    async handleGoogleLogin() {
        this.showLoading(true);
        
        try {
            const provider = new firebase.auth.GoogleAuthProvider();
            provider.addScope('email');
            provider.addScope('profile');
            
            const result = await this.auth.signInWithPopup(provider);
            console.log('Google auth successful:', result.user);
            
            // User will be handled by onAuthStateChanged
            
        } catch (error) {
            console.error('Google auth error:', error);
            
            if (error.code === 'auth/popup-closed-by-user') {
                this.showError('Login cancelled');
            } else {
                this.showError(this.getErrorMessage(error));
            }
        } finally {
            this.showLoading(false);
        }
    }
    
    /**
     * Handle email login
     */
    async handleEmailSubmit(e) {
        e.preventDefault();
        
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        if (!email || !password) {
            this.showError('Please enter both email and password');
            return;
        }
        
        this.showLoading(true);
        
        try {
            const result = await this.auth.signInWithEmailAndPassword(email, password);
            console.log('Email auth successful:', result.user);
            
            // User will be handled by onAuthStateChanged
            
        } catch (error) {
            console.error('Email auth error:', error);
            this.showError(this.getErrorMessage(error));
        } finally {
            this.showLoading(false);
        }
    }
    
    /**
     * Handle authentication state changes
     */
    async handleAuthStateChange(user) {
        if (user) {
            console.log('User signed in:', user);
            this.currentUser = user;
            
            try {
                // Get Firebase ID token
                const idToken = await user.getIdToken();
                
                // Send to WordPress for session creation
                await this.createWordPressSession(idToken, user);
                
            } catch (error) {
                console.error('Session creation error:', error);
                this.showError('Failed to create session. Please try again.');
            }
        } else {
            console.log('User signed out');
            this.currentUser = null;
        }
    }
    
    /**
     * Create WordPress session
     */
    async createWordPressSession(idToken, user) {
        const formData = new FormData();
        formData.append('action', 'tostishop_firebase_login');
        formData.append('id_token', idToken);
        formData.append('nonce', tostishopFirebase.nonce);
        formData.append('user_data', JSON.stringify({
            uid: user.uid,
            email: user.email,
            displayName: user.displayName,
            phoneNumber: user.phoneNumber,
            photoURL: user.photoURL
        }));
        
        try {
            const response = await fetch(tostishopFirebase.ajaxUrl, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('Login successful! Redirecting...');
                
                // Redirect after successful login
                setTimeout(() => {
                    if (data.data.redirect_url) {
                        window.location.href = data.data.redirect_url;
                    } else {
                        window.location.reload();
                    }
                }, 1500);
                
            } else {
                throw new Error(data.data.message || 'Login failed');
            }
            
        } catch (error) {
            console.error('WordPress session error:', error);
            this.showError('Failed to complete login. Please try again.');
        }
    }
    
    /**
     * Format OTP input
     */
    formatOtpInput(e) {
        let value = e.target.value.replace(/[^0-9]/g, '');
        if (value.length > 6) {
            value = value.slice(0, 6);
        }
        e.target.value = value;
    }
    
    /**
     * Show loading state
     */
    showLoading(show) {
        const loadingEl = document.getElementById('firebase-loading');
        const authContainer = document.querySelector('.firebase-auth-container');
        
        if (show) {
            if (loadingEl) loadingEl.classList.remove('hidden');
            if (authContainer) authContainer.classList.add('firebase-loading');
        } else {
            if (loadingEl) loadingEl.classList.add('hidden');
            if (authContainer) authContainer.classList.remove('firebase-loading');
        }
    }
    
    /**
     * Show error message
     */
    showError(message) {
        const errorEl = document.getElementById('firebase-error');
        const errorMessageEl = document.getElementById('firebase-error-message');
        const successEl = document.getElementById('firebase-success');
        
        if (errorEl && errorMessageEl) {
            errorMessageEl.textContent = message;
            errorEl.classList.remove('hidden');
            errorEl.classList.add('firebase-error-shake');
            
            // Remove shake animation after it completes
            setTimeout(() => {
                errorEl.classList.remove('firebase-error-shake');
            }, 500);
        }
        
        // Hide success message
        if (successEl) {
            successEl.classList.add('hidden');
        }
        
        console.error('Firebase Auth Error:', message);
    }
    
    /**
     * Show success message
     */
    showSuccess(message) {
        const successEl = document.getElementById('firebase-success');
        const successMessageEl = document.getElementById('firebase-success-message');
        const errorEl = document.getElementById('firebase-error');
        
        if (successEl && successMessageEl) {
            successMessageEl.textContent = message;
            successEl.classList.remove('hidden');
        }
        
        // Hide error message
        if (errorEl) {
            errorEl.classList.add('hidden');
        }
        
        console.log('Firebase Auth Success:', message);
    }
    
    /**
     * Get user-friendly error message
     */
    getErrorMessage(error) {
        switch (error.code) {
            case 'auth/invalid-phone-number':
                return 'Invalid phone number format';
            case 'auth/too-many-requests':
                return 'Too many attempts. Please try again later';
            case 'auth/user-not-found':
                return 'No account found with this email';
            case 'auth/wrong-password':
                return 'Incorrect password';
            case 'auth/weak-password':
                return 'Password is too weak';
            case 'auth/email-already-in-use':
                return 'Email is already registered';
            case 'auth/invalid-email':
                return 'Invalid email address';
            case 'auth/network-request-failed':
                return 'Network error. Please check your connection';
            default:
                return error.message || 'Authentication failed. Please try again';
        }
    }
    
    /**
     * Sign out user
     */
    async signOut() {
        try {
            await this.auth.signOut();
            console.log('User signed out successfully');
        } catch (error) {
            console.error('Sign out error:', error);
        }
    }
}

// Initialize Firebase Auth when script loads
window.TostiShopFirebaseAuth = new TostiShopFirebaseAuth();
