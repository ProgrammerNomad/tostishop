/**
 * Firebase Authentication for TostiShop Custom Login Form
 * Production-ready version with comprehensive error handling
 * 
 * @version 5.0.0 - Production Ready
 */

(function($) {
    'use strict';

    // Firebase configuration and variables
    let auth = null;
    let recaptchaVerifier = null;
    let confirmationResult = null;
    let currentPhoneNumber = '';
    let recaptchaSolved = false;

    // Test phone numbers (for development only)
    const TEST_PHONE_NUMBERS = window.tostiShopDevMode ? {
        '+919999999999': '123456',
        '+919876543210': '654321',
        '+919450987150': '111111'
    } : {};

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
                showError('Authentication service not configured. Please contact support.');
                return;
            }

            // Check if Firebase SDK is loaded
            if (typeof firebase === 'undefined') {
                console.error('Firebase SDK not loaded');
                showError('Authentication service unavailable. Please refresh the page.');
                return;
            }

            // Initialize Firebase
            if (!firebase.apps.length) {
                firebase.initializeApp(tostiShopFirebaseConfig);
            }
            auth = firebase.auth();

            console.log('Firebase initialized successfully');

            // Set up reCAPTCHA after Firebase is initialized
            setupRecaptcha();

        } catch (error) {
            console.error('Firebase initialization error:', error);
            showError('Authentication service initialization failed. Please refresh the page.');
        }
    }

    /**
     * Setup reCAPTCHA for phone authentication
     */
    function setupRecaptcha() {
        try {
            // Clear any existing reCAPTCHA
            if (recaptchaVerifier) {
                recaptchaVerifier.clear();
                recaptchaVerifier = null;
            }

            // Reset solved status
            recaptchaSolved = false;
            console.log('reCAPTCHA setup: recaptchaSolved reset to false');

            // Clear the container
            const recaptchaContainer = document.getElementById('recaptcha-container');
            if (recaptchaContainer) {
                recaptchaContainer.innerHTML = '';
                console.log('reCAPTCHA container cleared');
            } else {
                console.error('reCAPTCHA container not found');
                return;
            }

            // Create new reCAPTCHA verifier
            recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                'size': 'normal',
                'callback': function(response) {
                    console.log('✅ reCAPTCHA SOLVED!');
                    recaptchaSolved = true;
                    
                    // Show success message
                    showSuccess('Security verification completed!');
                    
                    // Validate button state
                    validateSendOTPButton();
                },
                'expired-callback': function() {
                    console.log('❌ reCAPTCHA EXPIRED');
                    recaptchaSolved = false;
                    showError('Security verification expired. Please solve the reCAPTCHA again.');
                    $('#send-otp-btn').prop('disabled', true);
                },
                'error-callback': function(error) {
                    console.error('❌ reCAPTCHA ERROR:', error);
                    recaptchaSolved = false;
                    showError('Security verification failed. Please try again.');
                    $('#send-otp-btn').prop('disabled', true);
                },
                'theme': 'light'
            });

            // Render the reCAPTCHA
            recaptchaVerifier.render().then(function(widgetId) {
                console.log('✅ reCAPTCHA rendered successfully. Widget ID:', widgetId);
                window.recaptchaWidgetId = widgetId;
                
                // Add visual styling
                if (recaptchaContainer) {
                    recaptchaContainer.style.border = '2px solid #10b981';
                    recaptchaContainer.style.borderRadius = '8px';
                    recaptchaContainer.style.padding = '4px';
                }
                
                // Update instruction text
                const instructionText = document.getElementById('recaptcha-instruction');
                if (instructionText) {
                    instructionText.textContent = '👆 Please complete the security verification above';
                    instructionText.style.color = '#f59e0b';
                    instructionText.style.fontWeight = 'bold';
                }
                
            }).catch(function(error) {
                console.error('❌ reCAPTCHA render error:', error);
                showError('Security verification failed to load. Please refresh the page.');
            });

        } catch (error) {
            console.error('❌ reCAPTCHA setup error:', error);
            showError('Security verification setup failed. Please refresh the page and try again.');
        }
    }

    /**
     * Validate and enable/disable Send OTP button
     */
    function validateSendOTPButton() {
        const phoneValue = $('#mobile-number').val().replace(/[^0-9]/g, '');
        const isValidPhone = phoneValue.length === 10 && /^[6-9][0-9]{9}$/.test(phoneValue);
        
        console.log('🔍 Validating Send OTP button:', {
            phoneValue: phoneValue,
            phoneLength: phoneValue.length,
            isValidPhone: isValidPhone,
            recaptchaSolved: recaptchaSolved
        });
        
        // Update button state
        const sendButton = $('#send-otp-btn');
        if (isValidPhone && recaptchaSolved) {
            sendButton.prop('disabled', false);
            sendButton.removeClass('bg-gray-400 cursor-not-allowed')
                     .addClass('bg-accent hover:bg-red-600 cursor-pointer');
            sendButton.text('Send OTP');
            console.log('✅ Send OTP button ENABLED');
        } else {
            sendButton.prop('disabled', true);
            sendButton.removeClass('bg-accent hover:bg-red-600 cursor-pointer')
                     .addClass('bg-gray-400 cursor-not-allowed');
            
            // Update button text based on what's missing
            if (!isValidPhone && !recaptchaSolved) {
                sendButton.text('Enter Phone & Complete reCAPTCHA');
            } else if (!isValidPhone) {
                sendButton.text('Enter Valid Phone Number');
            } else if (!recaptchaSolved) {
                sendButton.text('Complete Security Verification');
            }
            
            console.log('❌ Send OTP button DISABLED');
        }
    }

    /**
     * Bind all event listeners
     */
    function bindEvents() {
        // Mobile OTP Events
        $('#send-otp-btn').on('click', function(e) {
            e.preventDefault();
            console.log('🔥 Send OTP button clicked');
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

        // Mobile number input validation
        $('#mobile-number').on('input', function() {
            const value = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(value);
            
            console.log('📱 Phone number input changed:', value);
            
            // Visual feedback
            const phoneInput = $(this);
            if (value.length === 10 && /^[6-9][0-9]{9}$/.test(value)) {
                phoneInput.removeClass('border-red-300').addClass('border-green-300');
            } else if (value.length > 0) {
                phoneInput.removeClass('border-green-300').addClass('border-red-300');
            } else {
                phoneInput.removeClass('border-red-300 border-green-300');
            }
            
            validateSendOTPButton();
        });

        $('#mobile-number').on('keyup', function() {
            validateSendOTPButton();
        });
    }

    /**
     * Setup OTP input field behavior
     */
    function setupOTPInputs() {
        $('#otp-code').on('input', function() {
            const value = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(value);
            
            // Visual feedback
            const otpInput = $(this);
            if (value.length === 6) {
                otpInput.removeClass('border-red-300').addClass('border-green-300');
                setTimeout(() => {
                    verifyOTP();
                }, 500);
            } else if (value.length > 0) {
                otpInput.removeClass('border-green-300 border-red-300');
            }
        });
    }

    /**
     * Send OTP to mobile number - PRODUCTION VERSION
     */
    function sendOTP() {
        console.log('🚀 sendOTP function called');
        
        const phoneInput = $('#mobile-number');
        const phoneNumber = phoneInput.val().trim();

        console.log('📱 Phone number from input:', phoneNumber);

        // Validation
        if (!phoneNumber || phoneNumber.length !== 10 || !/^[6-9][0-9]{9}$/.test(phoneNumber)) {
            showError('Please enter a valid 10-digit mobile number starting with 6, 7, 8, or 9.');
            phoneInput.focus();
            return;
        }

        if (!recaptchaSolved || !recaptchaVerifier) {
            showError('Please complete the security verification (reCAPTCHA) first.');
            return;
        }

        // Format phone number
        currentPhoneNumber = '+91' + phoneNumber;
        console.log('🌍 Formatted phone number:', currentPhoneNumber);

        // Check for test numbers (development only)
        if (window.tostiShopDevMode && TEST_PHONE_NUMBERS[currentPhoneNumber]) {
            console.log('🧪 Using test phone number');
            simulateTestOTP(currentPhoneNumber);
            return;
        }

        showLoading('Sending OTP...');
        $('#send-otp-btn').prop('disabled', true);

        console.log('🔥 About to call Firebase signInWithPhoneNumber');

        // Send SMS verification
        auth.signInWithPhoneNumber(currentPhoneNumber, recaptchaVerifier)
            .then(function(result) {
                console.log('✅ Firebase signInWithPhoneNumber success');
                confirmationResult = result;
                
                hideLoading();
                showSuccess('OTP sent successfully to ' + currentPhoneNumber);
                
                // Update display
                $('#otp-phone-display').text(currentPhoneNumber);
                
                // Switch to OTP view
                const switchEvent = new CustomEvent('switch-to-otp', {
                    detail: { phone: currentPhoneNumber }
                });
                document.dispatchEvent(switchEvent);
                
                // Focus OTP input
                setTimeout(() => {
                    $('#otp-code').focus();
                }, 100);

                // Start countdown
                startResendCountdown($('#resend-otp-btn'));

            })
            .catch(function(error) {
                console.error('❌ Firebase signInWithPhoneNumber error:', error);
                hideLoading();
                $('#send-otp-btn').prop('disabled', false);
                
                let errorMessage = 'Failed to send OTP. ';
                if (error.code === 'auth/too-many-requests') {
                    errorMessage = '🚫 Too many requests. Please try again in 15-30 minutes, or use Google/Email login.';
                } else if (error.code === 'auth/invalid-phone-number') {
                    errorMessage = 'Invalid phone number format.';
                } else if (error.code === 'auth/captcha-check-failed') {
                    errorMessage = 'Security verification failed. Please try again.';
                    setupRecaptcha();
                } else {
                    errorMessage += 'Please try again or use alternative login method.';
                }
                
                showError(errorMessage);
            });
    }

    /**
     * Simulate test OTP (development only)
     */
    function simulateTestOTP(phoneNumber) {
        if (!window.tostiShopDevMode) return;
        
        console.log('🧪 Simulating test OTP for:', phoneNumber);
        
        showLoading('Sending test OTP...');
        
        setTimeout(() => {
            hideLoading();
            showSuccess('Test OTP sent to ' + phoneNumber + '. Use: ' + TEST_PHONE_NUMBERS[phoneNumber]);
            
            $('#otp-phone-display').text(phoneNumber);
            
            // Create fake confirmation result
            confirmationResult = {
                confirm: function(code) {
                    return new Promise((resolve, reject) => {
                        if (code === TEST_PHONE_NUMBERS[phoneNumber]) {
                            const fakeUser = {
                                uid: 'test-user-' + Date.now(),
                                phoneNumber: phoneNumber,
                                getIdToken: () => Promise.resolve('test-token-' + Date.now())
                            };
                            resolve({ user: fakeUser });
                        } else {
                            reject({ code: 'auth/invalid-verification-code' });
                        }
                    });
                }
            };
            
            // Switch to OTP view
            const switchEvent = new CustomEvent('switch-to-otp', {
                detail: { phone: phoneNumber }
            });
            document.dispatchEvent(switchEvent);
            
            setTimeout(() => {
                $('#otp-code').focus();
            }, 100);

            startResendCountdown($('#resend-otp-btn'));
            
        }, 1500);
    }

    /**
     * Verify OTP code
     */
    function verifyOTP() {
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

        confirmationResult.confirm(otpCode)
            .then(function(result) {
                const user = result.user;
                console.log('✅ OTP verification successful');
                
                loginToWordPress(user, 'phone');
            })
            .catch(function(error) {
                hideLoading();
                console.error('❌ OTP verification error:', error);
                
                let errorMessage = 'Invalid OTP. ';
                if (error.code === 'auth/invalid-verification-code') {
                    if (window.tostiShopDevMode && TEST_PHONE_NUMBERS[currentPhoneNumber]) {
                        errorMessage = `Invalid test OTP. Use: ${TEST_PHONE_NUMBERS[currentPhoneNumber]}`;
                    } else {
                        errorMessage = 'Invalid OTP code. Please check and try again.';
                    }
                } else if (error.code === 'auth/code-expired') {
                    errorMessage = 'OTP code has expired. Please request a new one.';
                }
                
                showError(errorMessage);
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

        // Check for test numbers
        if (window.tostiShopDevMode && TEST_PHONE_NUMBERS[currentPhoneNumber]) {
            simulateTestOTP(currentPhoneNumber);
            return;
        }

        const resendBtn = $('#resend-otp-btn');
        resendBtn.prop('disabled', true).text('Sending...');

        confirmationResult = null;
        setupRecaptcha();
        
        setTimeout(() => {
            if (recaptchaVerifier) {
                auth.signInWithPhoneNumber(currentPhoneNumber, recaptchaVerifier)
                    .then(function(result) {
                        confirmationResult = result;
                        showSuccess('New OTP sent to ' + currentPhoneNumber);
                        $('#otp-code').val('').focus();
                        startResendCountdown(resendBtn);
                    })
                    .catch(function(error) {
                        console.error('Resend OTP error:', error);
                        showError('Failed to resend OTP. Please try Google/Email login.');
                        resendBtn.prop('disabled', false).text('Resend OTP');
                    });
            }
        }, 1000);
    }

    /**
     * Start countdown for resend button
     */
    function startResendCountdown(button) {
        let countdown = 30;
        button.prop('disabled', true);
        
        const interval = setInterval(() => {
            button.text(`Resend OTP (${countdown}s)`);
            countdown--;
            
            if (countdown < 0) {
                clearInterval(interval);
                button.prop('disabled', false).text('Resend OTP');
            }
        }, 1000);
    }

    /**
     * Login with Google
     */
    function loginWithGoogle() {
        if (!auth) {
            showError('Authentication service not available. Please refresh the page.');
            return;
        }

        showLoading('Connecting to Google...');

        const provider = new firebase.auth.GoogleAuthProvider();
        provider.addScope('email');
        provider.addScope('profile');

        auth.signInWithPopup(provider)
            .then(function(result) {
                const user = result.user;
                console.log('✅ Google login successful');
                
                loginToWordPress(user, 'google');
            })
            .catch(function(error) {
                hideLoading();
                console.error('❌ Google login error:', error);
                
                let errorMessage = 'Google login failed. ';
                if (error.code === 'auth/popup-closed-by-user') {
                    errorMessage = 'Login cancelled. Please try again.';
                } else if (error.code === 'auth/popup-blocked') {
                    errorMessage = 'Pop-up blocked. Please allow pop-ups and try again.';
                } else {
                    errorMessage += 'Please try email login instead.';
                }
                
                showError(errorMessage);
            });
    }

    /**
     * Handle Email Authentication
     */
    function handleEmailAuth() {
        const alpineElement = document.querySelector('[x-data]');
        const isRegistering = alpineElement && alpineElement._x_dataStack && 
                            alpineElement._x_dataStack[0].isRegistering;
        
        if (isRegistering) {
            const email = $('#email-register').val().trim();
            const password = $('#password-register').val();
            
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
            const email = $('#email-login').val().trim();
            const password = $('#password-login').val();
            
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
     * Login with Email
     */
    function loginWithEmail(email, password) {
        showLoading('Logging in...');

        auth.signInWithEmailAndPassword(email, password)
            .then(function(result) {
                const user = result.user;
                console.log('✅ Email login successful');
                
                loginToWordPress(user, 'email');
            })
            .catch(function(error) {
                hideLoading();
                console.error('❌ Email login error:', error);
                
                let errorMessage = 'Login failed. ';
                if (error.code === 'auth/user-not-found') {
                    errorMessage = 'No account found with this email. Please register first.';
                } else if (error.code === 'auth/wrong-password') {
                    errorMessage = 'Incorrect password. Please try again.';
                } else if (error.code === 'auth/invalid-email') {
                    errorMessage = 'Invalid email address format.';
                } else {
                    errorMessage += 'Please check your credentials and try again.';
                }
                
                showError(errorMessage);
            });
    }

    /**
     * Register with Email
     */
    function registerWithEmail(email, password) {
        showLoading('Creating account...');

        auth.createUserWithEmailAndPassword(email, password)
            .then(function(result) {
                const user = result.user;
                console.log('✅ Email registration successful');
                
                loginToWordPress(user, 'email');
            })
            .catch(function(error) {
                hideLoading();
                console.error('❌ Email registration error:', error);
                
                let errorMessage = 'Registration failed. ';
                if (error.code === 'auth/email-already-in-use') {
                    errorMessage = 'An account with this email already exists. Please login instead.';
                } else if (error.code === 'auth/invalid-email') {
                    errorMessage = 'Invalid email address format.';
                } else if (error.code === 'auth/weak-password') {
                    errorMessage = 'Password is too weak. Please choose a stronger password.';
                }
                
                showError(errorMessage);
            });
    }

    /**
     * Login to WordPress - ENHANCED VERSION
     */
    function loginToWordPress(firebaseUser, authMethod) {
        if (!firebaseUser) {
            hideLoading();
            showError('Authentication failed. Please try again.');
            return;
        }

        console.log('🔄 Logging into WordPress...');

        firebaseUser.getIdToken()
            .then(function(idToken) {
                
                const userData = {
                    action: 'tostishop_firebase_login',
                    firebase_token: idToken,
                    nonce: tostiShopAjax.nonce,
                    auth_method: authMethod,
                    from_checkout: window.location.href.includes('checkout') ? 'true' : 'false'
                };

                console.log('📤 Sending login request to WordPress');

                $.ajax({
                    url: tostiShopAjax.ajaxurl,
                    type: 'POST',
                    data: userData,
                    timeout: 30000,
                    success: function(response) {
                        hideLoading();
                        
                        console.log('📥 WordPress response:', response);
                        
                        if (response.success) {
                            showSuccess(response.data.message || 'Login successful! Redirecting...');
                            
                            setTimeout(function() {
                                window.location.href = response.data.redirect_url || tostiShopAjax.redirectUrl;
                            }, 1500);
                            
                        } else {
                            showError(response.data.message || 'Login failed. Please try again.');
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        console.error('❌ WordPress login error:', xhr.responseText, status, error);
                        showError('Connection error. Please check your internet connection and try again.');
                    }
                });
            })
            .catch(function(error) {
                hideLoading();
                console.error('❌ Token retrieval error:', error);
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
        
        const hideDelay = message.includes('Too many') ? 10000 : 8000;
        setTimeout(function() {
            hideError();
        }, hideDelay);
    }

    function showSuccess(message) {
        hideError();
        $('#firebase-success-message').text(message);
        $('#firebase-success').removeClass('hidden');
        
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

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Global error handler
    window.addEventListener('unhandledrejection', function(event) {
        if (event.reason && event.reason.code && event.reason.code.startsWith('auth/')) {
            console.error('Unhandled Firebase auth error:', event.reason);
            showError('Authentication error occurred. Please try again.');
            event.preventDefault();
        }
    });

})(jQuery);
