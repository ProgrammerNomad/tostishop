/**
 * Firebase Authentication for TostiShop Custom Login Form
 * Complete integration with existing form-login.php
 * Handles Mobile OTP, Google Login, and Email Authentication
 * 
 * @version 4.0.0 - Production Ready
 */

(function($) {
    'use strict';

    // Firebase configuration and variables
    let auth = null;
    let recaptchaVerifier = null;
    let confirmationResult = null;
    let currentPhoneNumber = '';

    // Initialize Firebase when DOM is ready
    $(document).ready(function() {
        initializeFirebase();
        bindEvents();
        setupOTPInputs();
    });

    /**
     * Initialize Firebase Authentication
     */
    function initializeFirebase() {
        try {
            // Check if Firebase config is available
            if (typeof tostiShopFirebaseConfig === 'undefined') {
                console.warn('Firebase configuration not found. Please configure Firebase in admin settings.');
                return;
            }

            // Initialize Firebase
            firebase.initializeApp(tostiShopFirebaseConfig);
            auth = firebase.auth();

            console.log('Firebase initialized successfully');

            // Set up invisible reCAPTCHA
            setupRecaptcha();

        } catch (error) {
            console.error('Firebase initialization error:', error);
            showError('Firebase initialization failed. Please contact support.');
        }
    }

    /**
     * Setup invisible reCAPTCHA for phone authentication
     */
    function setupRecaptcha() {
        try {
            recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                size: 'invisible',
                callback: function(response) {
                    console.log('reCAPTCHA verified');
                },
                'expired-callback': function() {
                    console.log('reCAPTCHA expired');
                    showError('Security verification expired. Please try again.');
                }
            });
        } catch (error) {
            console.error('reCAPTCHA setup error:', error);
        }
    }

    /**
     * Bind all event listeners
     */
    function bindEvents() {
        // Mobile OTP Events
        $('#send-otp-btn').on('click', function(e) {
            e.preventDefault();
            sendOTP();
        });
        $('#verify-otp-btn').on('click', function(e) {
            e.preventDefault();
            verifyOTP();
        });
        $('#resend-otp-btn').on('click', function(e) {
            e.preventDefault();
            resendOTP();
        });

        // Google Login Event  
        $('#google-login-btn').on('click', function(e) {
            e.preventDefault();
            loginWithGoogle();
        });

        // Email Authentication Events
        $('#email-login-btn').on('click', function(e) {
            e.preventDefault();
            handleEmailAuth();
        });
        
        $('#email-register-btn').on('click', function(e) {
            e.preventDefault();
            handleEmailAuth();
        });

        // OTP Navigation
        $(document).on('click', '[x-show="currentView === \'otp\'"] button[\\@click="currentView = \'initial\'"]', function() {
            // Reset OTP form when going back
            resetOTPForm();
        });
    }

    /**
     * Setup OTP input field behavior
     */
    function setupOTPInputs() {
        // Setup single OTP input field (6-digit)
        $('#otp-code').on('input', function() {
            const value = $(this).val();
            // Only allow numbers
            $(this).val(value.replace(/[^0-9]/g, ''));
        });

        // Allow only numeric input
        $('#otp-code').on('keypress', function(e) {
            // Allow only numbers, backspace, delete, tab, escape, enter
            if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true) ||
                // Allow numbers 0-9
                (e.keyCode >= 48 && e.keyCode <= 57) ||
                (e.keyCode >= 96 && e.keyCode <= 105)) {
                return;
            }
            e.preventDefault();
        });
    }

    /**
     * Send OTP to mobile number
     */
    function sendOTP() {
        const phoneInput = $('#mobile-number');
        const phoneNumber = phoneInput.val().trim();

        // Validate phone number
        if (!phoneNumber || phoneNumber.length !== 10 || !/^[6-9][0-9]{9}$/.test(phoneNumber)) {
            showError('Please enter a valid 10-digit mobile number starting with 6, 7, 8, or 9.');
            phoneInput.focus();
            return;
        }

        // Format phone number with country code
        currentPhoneNumber = '+91' + phoneNumber;

        showLoading('Sending OTP...');

        // Clear any previous reCAPTCHA
        if (recaptchaVerifier) {
            recaptchaVerifier.clear();
            setupRecaptcha();
        }

        // Send SMS verification
        auth.signInWithPhoneNumber(currentPhoneNumber, recaptchaVerifier)
            .then(function(result) {
                confirmationResult = result;
                
                hideLoading();
                showSuccess('OTP sent successfully to ' + currentPhoneNumber);
                
                // Update display phone number
                $('#otp-phone-display').text(currentPhoneNumber);
                
                // Switch to OTP view by dispatching event
                const switchEvent = new CustomEvent('switch-to-otp', {
                    detail: { phone: currentPhoneNumber }
                });
                document.dispatchEvent(switchEvent);
                
                // Focus OTP input
                setTimeout(() => {
                    $('#otp-code').focus();
                }, 100);

            })
            .catch(function(error) {
                hideLoading();
                console.error('SMS sending error:', error);
                
                let errorMessage = 'Failed to send OTP. ';
                if (error.code === 'auth/too-many-requests') {
                    errorMessage = 'Too many attempts. Please try again later.';
                } else if (error.code === 'auth/invalid-phone-number') {
                    errorMessage = 'Invalid phone number format.';
                } else {
                    errorMessage += 'Please try again.';
                }
                
                showError(errorMessage);
            });
    }

    /**
     * Verify OTP code
     */
    function verifyOTP() {
        // Get OTP from single input field (as per your form design)
        const otpCode = $('#otp-code').val().trim();
        
        if (!otpCode || otpCode.length !== 6 || !/^[0-9]{6}$/.test(otpCode)) {
            showError('Please enter the complete 6-digit OTP.');
            $('#otp-code').focus();
            return;
        }

        if (!confirmationResult) {
            showError('No OTP verification in progress. Please request a new OTP.');
            return;
        }

        showLoading('Verifying OTP...');

        // Verify the SMS code
        confirmationResult.confirm(otpCode)
            .then(function(result) {
                const user = result.user;
                console.log('OTP verification successful:', user);
                
                // Login successful, send to WordPress
                loginToWordPress(user, 'phone');
            })
            .catch(function(error) {
                hideLoading();
                console.error('OTP verification error:', error);
                
                let errorMessage = 'Invalid OTP. ';
                if (error.code === 'auth/invalid-verification-code') {
                    errorMessage = 'Invalid OTP code. Please check and try again.';
                } else if (error.code === 'auth/code-expired') {
                    errorMessage = 'OTP code has expired. Please request a new one.';
                }
                
                showError(errorMessage);
                
                // Clear OTP input
                $('#otp-code').val('').focus();
            });
    }

    /**
     * Resend OTP
     */
    function resendOTP() {
        if (!currentPhoneNumber) {
            showError('Please enter your phone number first.');
            return;
        }

        // Disable resend button temporarily
        const resendBtn = $('#resend-otp-btn');
        resendBtn.prop('disabled', true).text('Sending...');

        // Reset and send new OTP
        confirmationResult = null;
        if (recaptchaVerifier) {
            recaptchaVerifier.clear();
            setupRecaptcha();
        }

        auth.signInWithPhoneNumber(currentPhoneNumber, recaptchaVerifier)
            .then(function(result) {
                confirmationResult = result;
                showSuccess('New OTP sent to ' + currentPhoneNumber);
                
                // Reset OTP input
                $('#otp-code').val('').focus();
                
                // Re-enable button with countdown
                startResendCountdown(resendBtn);
            })
            .catch(function(error) {
                console.error('Resend OTP error:', error);
                showError('Failed to resend OTP. Please try again.');
                resendBtn.prop('disabled', false).text('Resend OTP');
            });
    }

    /**
     * Start countdown for resend button
     */
    function startResendCountdown(button) {
        let countdown = 30;
        const originalText = 'Resend OTP';
        
        const interval = setInterval(() => {
            button.text(`Resend OTP (${countdown}s)`);
            countdown--;
            
            if (countdown < 0) {
                clearInterval(interval);
                button.prop('disabled', false).text(originalText);
            }
        }, 1000);
    }

    /**
     * Login with Google
     */
    function loginWithGoogle() {
        if (!auth) {
            showError('Firebase not initialized. Please refresh the page.');
            return;
        }

        showLoading('Connecting to Google...');

        const provider = new firebase.auth.GoogleAuthProvider();
        provider.addScope('email');
        provider.addScope('profile');

        auth.signInWithPopup(provider)
            .then(function(result) {
                const user = result.user;
                console.log('Google login successful:', user);
                
                // Login successful, send to WordPress
                loginToWordPress(user, 'google');
            })
            .catch(function(error) {
                hideLoading();
                console.error('Google login error:', error);
                
                let errorMessage = 'Google login failed. ';
                if (error.code === 'auth/popup-closed-by-user') {
                    errorMessage = 'Login cancelled. Please try again.';
                } else if (error.code === 'auth/popup-blocked') {
                    errorMessage = 'Pop-up blocked by browser. Please allow pop-ups and try again.';
                } else {
                    errorMessage += 'Please try again.';
                }
                
                showError(errorMessage);
            });
    }

    /**
     * Handle Email Authentication (Login/Register)
     */
    function handleEmailAuth() {
        // Get current registration state from Alpine.js
        const alpineElement = document.querySelector('[x-data]');
        const isRegistering = alpineElement && alpineElement._x_dataStack && 
                            alpineElement._x_dataStack[0].isRegistering;
        
        if (isRegistering) {
            // Register mode
            const email = $('#email-register').val().trim();
            const password = $('#password-register').val();
            
            // Validate inputs
            if (!email || !password) {
                showError('Please fill in all required fields.');
                return;
            }

            if (!isValidEmail(email)) {
                showError('Please enter a valid email address.');
                $('#email-register').focus();
                return;
            }

            if (password.length < 6) {
                showError('Password must be at least 6 characters long.');
                $('#password-register').focus();
                return;
            }
            
            registerWithEmail(email, password);
        } else {
            // Login mode
            const email = $('#email-login').val().trim();
            const password = $('#password-login').val();
            
            // Validate inputs
            if (!email || !password) {
                showError('Please fill in all required fields.');
                return;
            }

            if (!isValidEmail(email)) {
                showError('Please enter a valid email address.');
                $('#email-login').focus();
                return;
            }
            
            loginWithEmail(email, password);
        }
    }

    /**
     * Login with Email and Password
     */
    function loginWithEmail(email, password) {
        showLoading('Logging in...');

        auth.signInWithEmailAndPassword(email, password)
            .then(function(result) {
                const user = result.user;
                console.log('Email login successful:', user);
                
                // Login successful, send to WordPress
                loginToWordPress(user, 'email');
            })
            .catch(function(error) {
                hideLoading();
                console.error('Email login error:', error);
                
                let errorMessage = 'Login failed. ';
                if (error.code === 'auth/user-not-found') {
                    errorMessage = 'No account found with this email. Please register first.';
                } else if (error.code === 'auth/wrong-password') {
                    errorMessage = 'Incorrect password. Please try again.';
                } else if (error.code === 'auth/invalid-email') {
                    errorMessage = 'Invalid email address format.';
                } else if (error.code === 'auth/too-many-requests') {
                    errorMessage = 'Too many failed attempts. Please try again later.';
                } else {
                    errorMessage += 'Please check your credentials and try again.';
                }
                
                showError(errorMessage);
            });
    }

    /**
     * Register with Email and Password
     */
    function registerWithEmail(email, password) {
        showLoading('Creating account...');

        auth.createUserWithEmailAndPassword(email, password)
            .then(function(result) {
                const user = result.user;
                console.log('Email registration successful:', user);
                
                // Registration successful, send to WordPress
                loginToWordPress(user, 'email');
            })
            .catch(function(error) {
                hideLoading();
                console.error('Email registration error:', error);
                
                let errorMessage = 'Registration failed. ';
                if (error.code === 'auth/email-already-in-use') {
                    errorMessage = 'An account with this email already exists. Please login instead.';
                } else if (error.code === 'auth/invalid-email') {
                    errorMessage = 'Invalid email address format.';
                } else if (error.code === 'auth/weak-password') {
                    errorMessage = 'Password is too weak. Please choose a stronger password.';
                } else {
                    errorMessage += 'Please try again.';
                }
                
                showError(errorMessage);
            });
    }

    /**
     * Login to WordPress with Firebase user
     */
    function loginToWordPress(firebaseUser, authMethod) {
        if (!firebaseUser) {
            hideLoading();
            showError('Authentication failed. Please try again.');
            return;
        }

        // Get Firebase ID token
        firebaseUser.getIdToken()
            .then(function(idToken) {
                
                // Prepare user data for WordPress
                const userData = {
                    action: 'tostishop_firebase_login',
                    firebase_token: idToken,
                    nonce: tostiShopAjax.nonce,
                    auth_method: authMethod,
                    from_checkout: window.location.href.includes('checkout') ? 'true' : 'false'
                };

                // Send to WordPress
                $.ajax({
                    url: tostiShopAjax.ajaxurl,
                    type: 'POST',
                    data: userData,
                    timeout: 30000,
                    success: function(response) {
                        hideLoading();
                        
                        if (response.success) {
                            showSuccess(response.data.message || 'Login successful! Redirecting...');
                            
                            // Redirect after short delay
                            setTimeout(function() {
                                window.location.href = response.data.redirect_url || tostiShopAjax.redirectUrl;
                            }, 1500);
                            
                        } else {
                            showError(response.data.message || 'Login failed. Please try again.');
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        console.error('WordPress login error:', xhr.responseText, status, error);
                        showError('Connection error. Please check your internet connection and try again.');
                    }
                });
            })
            .catch(function(error) {
                hideLoading();
                console.error('Token retrieval error:', error);
                showError('Authentication token error. Please try again.');
            });
    }

    /**
     * Utility Functions
     */
    function showLoading(message = 'Processing...') {
        $('#loading-overlay').removeClass('hidden');
        $('#loading-overlay span').text(message);
    }

    function hideLoading() {
        $('#loading-overlay').addClass('hidden');
    }

    function showError(message) {
        hideSuccess();
        $('#firebase-error-message').text(message);
        $('#firebase-error').removeClass('hidden');
        
        // Auto-hide after 8 seconds
        setTimeout(function() {
            hideError();
        }, 8000);
    }

    function showSuccess(message) {
        hideError();
        $('#firebase-success-message').text(message);
        $('#firebase-success').removeClass('hidden');
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            hideSuccess();
        }, 5000);
    }

    function hideError() {
        $('#firebase-error').addClass('hidden');
    }

    function hideSuccess() {
        $('#firebase-success').addClass('hidden');
    }

    function resetOTPForm() {
        $('#otp-code').val('');
        confirmationResult = null;
        currentPhoneNumber = '';
        hideError();
        hideSuccess();
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Global error handler for unhandled Firebase errors
    window.addEventListener('unhandledrejection', function(event) {
        if (event.reason && event.reason.code && event.reason.code.startsWith('auth/')) {
            console.error('Unhandled Firebase auth error:', event.reason);
            showError('Authentication error occurred. Please try again.');
            event.preventDefault();
        }
    });

})(jQuery);
