/**
 * TostiShop Firebase Authentication
 * Clean implementation with Firestore integration
 */

class TostiShopAuth {
    constructor() {
        this.firebaseConfig = window.tostiShopFirebaseConfig || {};
        this.auth = null;
        this.db = null;
        this.currentUser = null;
        this.confirmationResult = null;
        this.recaptchaVerifier = null;
        this.init();
    }

    async init() {
        try {
            if (typeof firebase === 'undefined') {
                console.error('Firebase SDK not loaded');
                return;
            }

            // Initialize Firebase
            if (!firebase.apps.length) {
                firebase.initializeApp(this.firebaseConfig);
            }

            this.auth = firebase.auth();
            this.db = firebase.firestore();
            
            this.setupEventListeners();
            this.setupRecaptcha();
            
            console.log('TostiShop Auth initialized');
        } catch (error) {
            console.error('Firebase initialization error:', error);
            this.showError('Authentication service unavailable');
        }
    }

    setupEventListeners() {
        // Phone authentication
        document.getElementById('send-otp-btn')?.addEventListener('click', () => this.sendOTP());
        document.getElementById('verify-otp-btn')?.addEventListener('click', () => this.verifyOTP());
        document.getElementById('resend-otp-btn')?.addEventListener('click', () => this.resendOTP());

        // Google authentication
        document.getElementById('google-login-btn')?.addEventListener('click', () => this.googleLogin());

        // Email authentication
        document.getElementById('email-login-btn')?.addEventListener('click', () => this.emailLogin());
        document.getElementById('email-register-btn')?.addEventListener('click', () => this.emailRegister());

        // Phone registration modal
        document.getElementById('complete-phone-registration-btn')?.addEventListener('click', () => this.completePhoneRegistration());
        document.getElementById('close-phone-modal-btn')?.addEventListener('click', () => this.closePhoneModal());

        // Mobile number validation
        const mobileInput = document.getElementById('mobile-number');
        if (mobileInput) {
            mobileInput.addEventListener('input', this.validateMobileNumber.bind(this));
        }
    }

    setupRecaptcha() {
        try {
            const container = document.getElementById('recaptcha-container');
            if (!container) return;

            if (this.recaptchaVerifier) {
                this.recaptchaVerifier.clear();
            }

            this.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                'size': 'normal',
                'callback': () => console.log('reCAPTCHA solved'),
                'expired-callback': () => this.showError('reCAPTCHA expired. Please try again.')
            });

            this.recaptchaVerifier.render();
        } catch (error) {
            console.error('reCAPTCHA setup error:', error);
        }
    }

    // ============================================
    // 1. PHONE NUMBER AUTHENTICATION
    // ============================================

    async sendOTP() {
        try {
            const mobileNumber = document.getElementById('mobile-number').value.trim();
            
            if (!this.isValidMobile(mobileNumber)) {
                this.showError('Please enter a valid 10-digit mobile number');
                return;
            }

            const fullPhoneNumber = '+91' + mobileNumber;
            this.showLoading();

            this.confirmationResult = await this.auth.signInWithPhoneNumber(
                fullPhoneNumber, 
                this.recaptchaVerifier
            );

            this.switchView('otp');
            this.showSuccess('OTP sent to your mobile number');
            this.hideLoading();

        } catch (error) {
            console.error('Send OTP error:', error);
            this.hideLoading();
            this.showError('Failed to send OTP. Please try again.');
        }
    }

    async verifyOTP() {
        try {
            const otpCode = document.getElementById('otp-code').value.trim();
            
            if (otpCode.length !== 6) {
                this.showError('Please enter a valid 6-digit OTP');
                return;
            }

            this.showLoading();

            const result = await this.confirmationResult.confirm(otpCode);
            const user = result.user;

            // Enhanced: Check if user exists in Firestore AND WooCommerce
            const userDoc = await this.db.collection('users').doc(user.uid).get();
            
            if (userDoc.exists) {
                // User exists in Firestore - check WooCommerce user sync
                const firestoreData = userDoc.data();
                await this.syncFirebaseWithWooCommerce(user, firestoreData, 'phone');
            } else {
                // New user - show registration modal to collect name + email
                this.showPhoneRegistrationModal(user);
            }

        } catch (error) {
            console.error('OTP verification error:', error);
            this.hideLoading();
            this.showError('Invalid OTP. Please try again.');
        }
    }

    async resendOTP() {
        try {
            this.showLoading();
            
            // Reset reCAPTCHA
            if (this.recaptchaVerifier) {
                this.recaptchaVerifier.clear();
                this.setupRecaptcha();
            }

            await this.sendOTP();
        } catch (error) {
            console.error('Resend OTP error:', error);
            this.hideLoading();
            this.showError('Failed to resend OTP');
        }
    }

    showPhoneRegistrationModal(user) {
        this.hideLoading();
        this.currentUser = user;
        
        // Update phone display in modal
        const phoneDisplay = document.getElementById('verified-phone-display');
        if (phoneDisplay) {
            phoneDisplay.textContent = user.phoneNumber;
        }
        
        // Show modal
        document.getElementById('phone-registration-modal').classList.remove('hidden');
    }

    async completePhoneRegistration() {
        try {
            const name = document.getElementById('phone-register-name').value.trim();
            const email = document.getElementById('phone-register-email').value.trim();
            
            if (!name) {
                this.showError('Please enter your full name');
                return;
            }
            
            if (!this.isValidEmail(email)) {
                this.showError('Please enter a valid email address');
                return;
            }

            this.showLoading();

            // Update Firebase user profile
            await this.currentUser.updateProfile({
                displayName: name
            });

            // Save user data to Firestore
            const userData = {
                uid: this.currentUser.uid,
                name: name,
                email: email,
                phone: this.currentUser.phoneNumber,
                createdAt: firebase.firestore.FieldValue.serverTimestamp(),
                authMethod: 'phone',
                emailVerified: false
            };

            await this.db.collection('users').doc(this.currentUser.uid).set(userData);

            // Sync with WooCommerce (force registration)
            await this.syncFirebaseWithWooCommerce(this.currentUser, userData, 'phone', true);

        } catch (error) {
            console.error('Phone registration error:', error);
            this.hideLoading();
            this.showError('Registration failed. Please try again.');
        }
    }

    closePhoneModal() {
        document.getElementById('phone-registration-modal')?.classList.add('hidden');
        // Clear form
        document.getElementById('phone-register-name').value = '';
        document.getElementById('phone-register-email').value = '';
    }

    // ============================================
    // 2. GOOGLE AUTHENTICATION
    // ============================================

    async googleLogin() {
        try {
            this.showLoading();
            
            const provider = new firebase.auth.GoogleAuthProvider();
            provider.addScope('email');
            provider.addScope('profile');

            const result = await this.auth.signInWithPopup(provider);
            const user = result.user;

            // Check if user exists in Firestore and sync with WooCommerce
            const userDoc = await this.db.collection('users').doc(user.uid).get();
            
            if (userDoc.exists) {
                // User exists in Firestore - sync with WooCommerce
                await this.syncFirebaseWithWooCommerce(user, userDoc.data(), 'google');
            } else {
                // New Google user - create Firestore profile and sync
                const userData = {
                    uid: user.uid,
                    name: user.displayName,
                    email: user.email,
                    phone: user.phoneNumber || '',
                    createdAt: firebase.firestore.FieldValue.serverTimestamp(),
                    authMethod: 'google',
                    emailVerified: user.emailVerified,
                    photoURL: user.photoURL || ''
                };
                
                await this.db.collection('users').doc(user.uid).set(userData);
                await this.syncFirebaseWithWooCommerce(user, userData, 'google', true);
            }

        } catch (error) {
            console.error('Google login error:', error);
            this.hideLoading();
            
            if (error.code === 'auth/popup-closed-by-user') {
                this.showError('Login cancelled');
            } else {
                this.showError('Google login failed. Please try again.');
            }
        }
    }

    // ============================================
    // 3. EMAIL/PASSWORD AUTHENTICATION
    // ============================================

    async emailLogin() {
        try {
            const email = document.getElementById('email-login').value.trim();
            const password = document.getElementById('password-login').value.trim();
            
            if (!email || !password) {
                this.showError('Please enter both email and password');
                return;
            }

            this.showLoading();

            const result = await this.auth.signInWithEmailAndPassword(email, password);
            const user = result.user;

            // Get user data from Firestore and sync with WooCommerce
            const userDoc = await this.db.collection('users').doc(user.uid).get();
            
            if (userDoc.exists) {
                // User exists in Firestore - sync with WooCommerce
                await this.syncFirebaseWithWooCommerce(user, userDoc.data(), 'email');
            } else {
                // Fallback: create basic user data if missing
                const userData = {
                    uid: user.uid,
                    name: user.displayName || 'User',
                    email: user.email,
                    phone: '',
                    authMethod: 'email',
                    createdAt: firebase.firestore.FieldValue.serverTimestamp(),
                    emailVerified: user.emailVerified
                };
                
                await this.db.collection('users').doc(user.uid).set(userData);
                await this.syncFirebaseWithWooCommerce(user, userData, 'email', true);
            }

        } catch (error) {
            console.error('Email login error:', error);
            this.hideLoading();
            
            if (error.code === 'auth/user-not-found') {
                this.showError('Account not found, please register');
            } else if (error.code === 'auth/wrong-password') {
                this.showError('Invalid email or password');
            } else {
                this.showError('Login failed. Please try again.');
            }
        }
    }

    async emailRegister() {
        try {
            const name = document.getElementById('name-register').value.trim();
            const email = document.getElementById('email-register').value.trim();
            const password = document.getElementById('password-register').value.trim();
            
            if (!name) {
                this.showError('Please enter your full name');
                return;
            }
            
            if (!this.isValidEmail(email)) {
                this.showError('Please enter a valid email address');
                return;
            }
            
            if (password.length < 6) {
                this.showError('Password must be at least 6 characters');
                return;
            }

            this.showLoading();

            const result = await this.auth.createUserWithEmailAndPassword(email, password);
            const user = result.user;

            // Update profile
            await user.updateProfile({
                displayName: name
            });

            // Save to Firestore and sync with WooCommerce
            const userData = {
                uid: user.uid,
                name: name,
                email: email,
                phone: '',
                authMethod: 'email',
                createdAt: firebase.firestore.FieldValue.serverTimestamp(),
                emailVerified: user.emailVerified
            };

            await this.db.collection('users').doc(user.uid).set(userData);
            await this.syncFirebaseWithWooCommerce(user, userData, 'email', true);

        } catch (error) {
            console.error('Email registration error:', error);
            this.hideLoading();
            
            if (error.code === 'auth/email-already-in-use') {
                this.showError('Email already in use');
            } else if (error.code === 'auth/weak-password') {
                this.showError('Password is too weak');
            } else {
                this.showError('Registration failed. Please try again.');
            }
        }
    }

    // ============================================
    // COMMON METHODS
    // ============================================
    // FIREBASE + WOOCOMMERCE SYNC SYSTEM
    // ============================================

    /**
     * Enhanced Firebase + WooCommerce User Sync
     * Handles all authentication scenarios with proper user flow
     */
    async syncFirebaseWithWooCommerce(firebaseUser, userData, authMethod, forceRegistration = false) {
        try {
            // Get Firebase ID token
            const idToken = await firebaseUser.getIdToken();
            
            // Prepare user data for backend
            const syncData = {
                action: 'tostishop_firebase_login',
                firebase_token: idToken,
                auth_method: authMethod,
                user_uid: firebaseUser.uid,
                user_email: userData.email || firebaseUser.email || '',
                user_name: userData.name || firebaseUser.displayName || '',
                phone_number: userData.phone || firebaseUser.phoneNumber || '',
                email_verified: firebaseUser.emailVerified,
                force_registration: forceRegistration,
                check_user_exists: !forceRegistration,
                from_checkout: window.location.href.includes('checkout'),
                nonce: tostiShopAjax.nonce
            };

            // Send to WordPress backend
            const response = await fetch(tostiShopAjax.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(syncData)
            });

            const result = await response.json();
            
            if (result.success) {
                this.hideLoading();
                this.showSuccess(result.data.message || 'Login successful! Redirecting...');
                
                // Log successful sync
                console.log('✅ Firebase + WooCommerce sync successful:', {
                    method: authMethod,
                    userId: result.data.user_id,
                    foundBy: result.data.found_by
                });
                
                // Redirect after short delay
                setTimeout(() => {
                    window.location.href = result.data.redirect_url || '/my-account/';
                }, 1500);
                
            } else {
                this.hideLoading();
                
                // Handle specific error cases
                if (result.data.code === 'user_not_found' && authMethod === 'phone') {
                    // For phone auth, show registration modal
                    this.showPhoneRegistrationModal(firebaseUser);
                    return;
                } else if (result.data.code === 'email_required') {
                    this.showError('Email address is required to complete registration');
                    return;
                } else {
                    throw new Error(result.data.message || 'Authentication failed');
                }
            }

        } catch (error) {
            console.error('❌ Firebase + WooCommerce sync error:', error);
            this.hideLoading();
            this.showError(error.message || 'Authentication failed. Please try again.');
        }
    }

    // ============================================
    // LEGACY LOGIN METHOD (Kept for compatibility)
    // ============================================

    async loginUser(firebaseUser, userData) {
        try {
            // Get Firebase ID token
            const idToken = await firebaseUser.getIdToken();
            
            // Send to WordPress backend
            const response = await fetch(tostiShopAjax.ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'tostishop_firebase_login',
                    firebase_token: idToken,
                    user_data: JSON.stringify(userData),
                    nonce: tostiShopAjax.nonce
                })
            });

            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('Login successful! Redirecting...');
                
                setTimeout(() => {
                    window.location.href = data.data.redirect_url || '/my-account/';
                }, 1500);
            } else {
                throw new Error(data.data.message || 'Login failed');
            }

        } catch (error) {
            console.error('WordPress login error:', error);
            this.hideLoading();
            this.showError('Login failed. Please try again.');
        }
    }

    // ============================================
    // UTILITY METHODS
    // ============================================

    validateMobileNumber() {
        const input = document.getElementById('mobile-number');
        const button = document.getElementById('send-otp-btn');
        const value = input.value.replace(/\D/g, '');
        
        // Limit to 10 digits
        if (value.length > 10) {
            input.value = value.slice(0, 10);
            return;
        }
        
        input.value = value;
        
        // Enable/disable button
        if (this.isValidMobile(value)) {
            button.disabled = false;
            button.classList.remove('bg-gray-300', 'cursor-not-allowed');
            button.classList.add('bg-accent', 'hover:bg-red-600', 'cursor-pointer');
        } else {
            button.disabled = true;
            button.classList.remove('bg-accent', 'hover:bg-red-600', 'cursor-pointer');
            button.classList.add('bg-gray-300', 'cursor-not-allowed');
        }
    }

    isValidMobile(number) {
        return /^[6-9][0-9]{9}$/.test(number);
    }

    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    switchView(view) {
        // Hide all views
        document.querySelectorAll('[x-show]').forEach(el => el.style.display = 'none');
        
        // Show target view
        const targetView = document.querySelector(`[x-show="currentView === '${view}'"]`);
        if (targetView) {
            targetView.style.display = 'block';
        }
        
        // Update phone display for OTP view
        if (view === 'otp') {
            const phoneDisplay = document.getElementById('otp-phone-display');
            const mobileNumber = document.getElementById('mobile-number').value;
            if (phoneDisplay && mobileNumber) {
                phoneDisplay.textContent = '+91 ' + mobileNumber;
            }
        }
    }

    showLoading() {
        document.getElementById('loading-overlay')?.classList.remove('hidden');
    }

    hideLoading() {
        document.getElementById('loading-overlay')?.classList.add('hidden');
    }

    showError(message) {
        const errorDiv = document.getElementById('firebase-error');
        const messageDiv = document.getElementById('firebase-error-message');
        
        if (errorDiv && messageDiv) {
            messageDiv.textContent = message;
            errorDiv.classList.remove('hidden');
            
            // Hide success message
            document.getElementById('firebase-success')?.classList.add('hidden');
            
            // Auto-hide after 8 seconds
            setTimeout(() => {
                errorDiv.classList.add('hidden');
            }, 8000);
        }
    }

    showSuccess(message) {
        const successDiv = document.getElementById('firebase-success');
        const messageDiv = document.getElementById('firebase-success-message');
        
        if (successDiv && messageDiv) {
            messageDiv.textContent = message;
            successDiv.classList.remove('hidden');
            
            // Hide error message
            document.getElementById('firebase-error')?.classList.add('hidden');
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                successDiv.classList.add('hidden');
            }, 5000);
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.firebase-auth-container') || document.querySelector('[x-data]')) {
        window.tostiShopAuth = new TostiShopAuth();
    }
});

// Export for use in other scripts
window.TostiShopAuth = TostiShopAuth;
