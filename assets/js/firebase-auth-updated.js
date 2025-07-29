/**
 * Firebase Authentication for TostiShop Custom Login Form
 * Production-ready with enhanced error handling and fallbacks
 * 
 * @version 5.4.1 - FIXED Google Loop + Email Auth + Lost Password UI
 * 
 * FIXES IN THIS VERSION:
 * ✅ Fixed Google login infinite loop issue
 * ✅ Fixed email registration calling login instead of showing modal
 * ✅ Separated email login and registration into distinct functions
 * ✅ Proper loop prevention for both login and registration flows
 * ✅ Email login & registration fully functional with Firebase
 * ✅ Better error handling and user feedback
 * ✅ Automatic Google account creation (no modal)
 * ✅ Email registration shows modal for name collection
 * ✅ Enhanced lost password page with success states
 */

(function($) {
    'use strict';

    // Firebase configuration and variables
    let auth = null;
    let recaptchaVerifier = null;
    let confirmationResult = null;
    let currentPhoneNumber = '';
    let recaptchaSolved = false;
    let retryAttempts = 0;
    const MAX_RETRY_ATTEMPTS = 2;

    // Google login loop prevention
    let googleLoginAttempts = 0;
    const MAX_GOOGLE_LOGIN_ATTEMPTS = 1;

    // Google registration loop prevention
    let googleRegistrationAttempts = 0;
    const MAX_GOOGLE_REGISTRATION_ATTEMPTS = 1;

    // Test phone numbers (DISABLED IN PRODUCTION)
    const TEST_PHONE_NUMBERS = {}; // Empty object for production

    // Initialize Firebase when DOM is ready
    $(document).ready(function() {
        console.log('🚀 TostiShop Firebase Auth v5.4.1 - FIXED Google Loop + Email Auth + Lost Password UI');
        initializeFirebase();
        bindEvents();
        setupOTPInputs();
    });

    /**
     * Initialize Firebase Authentication - ENHANCED ERROR HANDLING
     */
    function initializeFirebase() {
        try {
            if (typeof tostiShopFirebaseConfig === 'undefined') {
                console.warn('Firebase configuration not found.');
                showError('Authentication service not configured. Please use Google or Email login.');
                return;
            }

            if (typeof firebase === 'undefined') {
                console.error('Firebase SDK not loaded');
                showError('Authentication service unavailable. Please refresh the page.');
                return;
            }

            // Validate Firebase config
            const requiredFields = ['apiKey', 'authDomain', 'projectId'];
            const missingFields = requiredFields.filter(field => !tostiShopFirebaseConfig[field]);
            
            if (missingFields.length > 0) {
                console.error('Missing Firebase config fields:', missingFields);
                showError('Firebase configuration incomplete. Please use Google or Email login.');
                return;
            }

            if (!firebase.apps.length) {
                firebase.initializeApp(tostiShopFirebaseConfig);
            }
            auth = firebase.auth();

            console.log('Firebase initialized successfully');

            // Set up invisible reCAPTCHA with better error handling
            setupInvisibleRecaptchaWithFallback();

        } catch (error) {
            console.error('Firebase initialization error:', error);
            showError('Authentication service initialization failed. Please use Google or Email login.');
            
            // Disable phone OTP button
            $('#send-otp-btn').prop('disabled', true)
                             .removeClass('bg-accent')
                             .addClass('bg-gray-400')
                             .text('Phone OTP Unavailable - Use Email/Google');
        }
    }

    /**
     * Setup Invisible reCAPTCHA with Fallback - FIXED VERSION
     */
    function setupInvisibleRecaptchaWithFallback() {
        try {
            // Clear any existing reCAPTCHA
            if (recaptchaVerifier) {
                try {
                    recaptchaVerifier.clear();
                } catch (e) {
                    console.warn('Failed to clear existing reCAPTCHA:', e);
                }
                recaptchaVerifier = null;
            }

            // Check if the button exists
            const sendButton = document.getElementById('send-otp-btn');
            if (!sendButton) {
                console.error('Send OTP button not found');
                return;
            }

            // Create invisible reCAPTCHA verifier with enhanced error handling
            recaptchaVerifier = new firebase.auth.RecaptchaVerifier('send-otp-btn', {
                'size': 'invisible',
                'callback': function(response) {
                    console.log('✅ Invisible reCAPTCHA solved automatically');
                    recaptchaSolved = true;
                    retryAttempts = 0; // Reset retry attempts on success
                    sendOTPWithVerifiedRecaptcha();
                },
                'expired-callback': function() {
                    console.log('❌ reCAPTCHA EXPIRED');
                    recaptchaSolved = false;
                    showError('Security verification expired. Please try again.');
                    resetSendButton();
                },
                'error-callback': function(error) {
                    console.error('❌ reCAPTCHA ERROR:', error);
                    recaptchaSolved = false;
                    handleRecaptchaError(error);
                }
            });

            console.log('✅ Invisible reCAPTCHA setup complete');
            updateSendButtonState();
            
        } catch (error) {
            console.error('❌ Invisible reCAPTCHA setup error:', error);
            handleRecaptchaSetupError(error);
        }
    }

    /**
     * Handle reCAPTCHA setup errors
     */
    function handleRecaptchaSetupError(error) {
        console.error('reCAPTCHA setup failed:', error);
        
        // Disable phone OTP and suggest alternatives
        $('#send-otp-btn').prop('disabled', true)
                         .removeClass('bg-accent hover:bg-red-600')
                         .addClass('bg-gray-400 cursor-not-allowed')
                         .text('Phone OTP Unavailable');
        
        showError('Phone verification unavailable. Please use Google Login or Email Login instead.');
        
        // Add helpful message
        setTimeout(() => {
            showSuccess('💡 Try Google Login (recommended) or Email Login below!');
        }, 3000);
    }

    /**
     * Handle reCAPTCHA runtime errors
     */
    function handleRecaptchaError(error) {
        if (retryAttempts < MAX_RETRY_ATTEMPTS) {
            retryAttempts++;
            console.log(`🔄 Retrying reCAPTCHA setup (attempt ${retryAttempts}/${MAX_RETRY_ATTEMPTS})`);
            
            setTimeout(() => {
                setupInvisibleRecaptchaWithFallback();
            }, 2000);
            
            showError('Security verification failed. Retrying...');
        } else {
            showError('Security verification repeatedly failed. Please use Google or Email login.');
            $('#send-otp-btn').prop('disabled', true)
                             .text('Use Alternative Login Methods');
        }
    }

    /**
     * Update Send OTP Button State - ENHANCED WITH BETTER MESSAGING
     */
    function updateSendButtonState() {
        const phoneValue = $('#mobile-number').val().replace(/[^0-9]/g, '');
        const isValidPhone = phoneValue.length === 10 && /^[6-9][0-9]{9}$/.test(phoneValue);
        
        const sendButton = $('#send-otp-btn');
        
        // Check if reCAPTCHA is available
        if (!recaptchaVerifier) {
            sendButton.prop('disabled', true)
                     .removeClass('bg-accent text-white hover:bg-red-600')
                     .addClass('bg-gray-400 text-gray-700 cursor-not-allowed')
                     .text('Phone OTP Unavailable - Use Email/Google');
            return;
        }
        
        if (isValidPhone) {
            // ✅ VALID PHONE - Enable button with TostiShop accent colors
            sendButton.prop('disabled', false);
            sendButton.removeClass('bg-gray-100 text-gray-600 border-gray-300 cursor-not-allowed hover:bg-gray-200 hover:border-gray-400')
                     .addClass('bg-accent text-white border-accent hover:bg-red-600 hover:border-red-600 cursor-pointer shadow-sm hover:shadow-md');
            sendButton.text('Send OTP');
            console.log('✅ Send OTP button ENABLED');
        } else if (phoneValue.length === 0) {
            // 🎯 NO INPUT YET - Neutral state
            sendButton.prop('disabled', true);
            sendButton.removeClass('bg-accent text-white border-accent hover:bg-red-600 hover:border-red-600 cursor-pointer shadow-sm hover:shadow-md')
                     .addClass('bg-gray-100 text-gray-600 border-gray-300 cursor-not-allowed hover:bg-gray-200 hover:border-gray-400');
            sendButton.text('Send OTP');
            console.log('⚪ Send OTP button NEUTRAL (no input)');
        } else {
            // ❌ INVALID INPUT - Show validation message
            sendButton.prop('disabled', true);
            sendButton.removeClass('bg-accent text-white border-accent hover:bg-red-600 hover:border-red-600 cursor-pointer shadow-sm hover:shadow-md')
                     .addClass('bg-gray-100 text-gray-600 border-gray-300 cursor-not-allowed hover:bg-gray-200 hover:border-gray-400');
            sendButton.text('Enter Valid Phone Number');
            console.log('❌ Send OTP button DISABLED (invalid input)');
        }
    }

    /**
     * Reset send button to default state
     */
    function resetSendButton() {
        $('#send-otp-btn').prop('disabled', false)
                         .removeClass('bg-gray-400')
                         .addClass('bg-accent hover:bg-red-600')
                         .text('Send OTP');
        updateSendButtonState();
    }

    /**
     * Bind all event listeners - ENHANCED ERROR HANDLING
     */
    function bindEvents() {
        // Mobile OTP Events - Enhanced with better error handling
        $('#send-otp-btn').on('click', function(e) {
            e.preventDefault();
            console.log('🔥 Send OTP button clicked');
            
            const phoneValue = $('#mobile-number').val().replace(/[^0-9]/g, '');
            const isValidPhone = phoneValue.length === 10 && /^[6-9][0-9]{9}$/.test(phoneValue);
            
            if (!isValidPhone) {
                showError('Please enter a valid 10-digit mobile number starting with 6, 7, 8, or 9.');
                $('#mobile-number').focus();
                return;
            }
            
            // Format phone number
            currentPhoneNumber = '+91' + phoneValue;
            
            // Check for test numbers (development only)
            if (window.tostiShopDevMode && TEST_PHONE_NUMBERS[currentPhoneNumber]) {
                console.log('🧪 Using test phone number');
                simulateTestOTP(currentPhoneNumber);
                return;
            }
            
            // Check if reCAPTCHA is ready
            if (!recaptchaVerifier) {
                showError('Security verification not ready. Please use Google or Email login.');
                return;
            }
            
            // Trigger invisible reCAPTCHA
            showLoading('Verifying security...');
            $(this).prop('disabled', true);
            
            try {
                recaptchaVerifier.verify();
            } catch (error) {
                hideLoading();
                $(this).prop('disabled', false);
                console.error('reCAPTCHA verification error:', error);
                
                if (error.code === 'auth/internal-error-encountered' || error.message.includes('500')) {
                    showError('🚫 Firebase service temporarily unavailable. Please try Google Login or Email Login instead.');
                } else {
                    showError('Security verification failed. Please try again or use alternative login methods.');
                }
            }
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
            googleLoginAttempts = 0; // Reset attempts on new login click
            loginWithGoogle();
        });

        // Email Authentication Events
        $('#email-login-btn').on('click', function(e) {
            e.preventDefault();
            console.log('📧 Email login button clicked');
            handleEmailLogin();
        });
        
        $('#email-register-btn').on('click', function(e) {
            e.preventDefault();
            console.log('📝 Email register button clicked');
            console.log('🔍 Debug: Button ID that was clicked:', $(this).attr('id'));
            console.log('🔍 Debug: Calling handleEmailRegistration function...');
            handleEmailRegistration();
        });

        // Real-time email validation
        $('#email-login, #email-register').on('blur', function() {
            const email = $(this).val().trim();
            if (email && !isValidEmail(email)) {
                $(this).addClass('border-red-500 focus:border-red-500 focus:ring-red-500');
                showError('Please enter a valid email address.');
            } else {
                $(this).removeClass('border-red-500 focus:border-red-500 focus:ring-red-500');
                hideError();
            }
        });

        // Password strength indicator (basic)
        $('#password-register').on('input', function() {
            const password = $(this).val();
            const strengthIndicator = $(this).siblings('.password-strength');
            
            if (password.length === 0) {
                strengthIndicator.remove();
                return;
            }
            
            if (strengthIndicator.length === 0) {
                $(this).after('<div class="password-strength text-xs mt-1"></div>');
            }
            
            const indicator = $(this).siblings('.password-strength');
            
            if (password.length < 6) {
                indicator.html('<span class="text-red-600">⚠️ Too short (minimum 6 characters)</span>');
                $(this).addClass('border-red-300');
            } else if (password.length < 8) {
                indicator.html('<span class="text-yellow-600">⚡ Fair strength</span>');
                $(this).removeClass('border-red-300').addClass('border-yellow-300');
            } else {
                indicator.html('<span class="text-green-600">✅ Good strength</span>');
                $(this).removeClass('border-red-300 border-yellow-300').addClass('border-green-300');
            }
        });

        // User Registration Modal Events
        $('#complete-registration-form').on('submit', function(e) {
            e.preventDefault();
            completeUserRegistration();
        });

        $('#complete-registration-btn').on('click', function(e) {
            e.preventDefault();
            completeUserRegistration();
        });

        $('#cancel-registration').on('click', function(e) {
            e.preventDefault();
            closeRegistrationModal();
            showError('Registration cancelled. Please try again when ready.');
        });

        // Close modal on backdrop click
        $('#user-registration-modal').on('click', function(e) {
            if (e.target === this) {
                closeRegistrationModal();
                showError('Registration cancelled.');
            }
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
            
            updateSendButtonState();
        });

        $('#mobile-number').on('keyup', function() {
            updateSendButtonState();
        });
        
        // Enter key handling for email forms
        $('#email-login, #password-login').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                $('#email-login-btn').click();
            }
        });
        
        $('#email-register, #password-register').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                $('#email-register-btn').click();
            }
        });
        
        // OTP input enter key handling
        $('#otp-code').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                $('#verify-otp-btn').click();
            }
        });
    }

    /**
     * Send OTP with verified reCAPTCHA - ENHANCED ERROR HANDLING
     */
    function sendOTPWithVerifiedRecaptcha() {
        console.log('🚀 Sending OTP with verified reCAPTCHA');
        
        showLoading('Sending OTP...');

        // Send SMS verification with enhanced error handling
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
                resetSendButton();
                
                let errorMessage = 'Failed to send OTP. ';
                let showAlternatives = false;
                
                if (error.code === 'auth/internal-error-encountered' || error.message.includes('500')) {
                    errorMessage = '🚫 Firebase service temporarily unavailable. Please try again in a few minutes.';
                    showAlternatives = true;
                } else if (error.code === 'auth/too-many-requests') {
                    errorMessage = '🚫 Too many requests. Please wait 15-30 minutes or use alternative login methods.';
                    showAlternatives = true;
                } else if (error.code === 'auth/invalid-phone-number') {
                    errorMessage = 'Invalid phone number format. Please check and try again.';
                } else if (error.code === 'auth/captcha-check-failed') {
                    errorMessage = 'Security verification failed. Please try again.';
                    // Reset reCAPTCHA
                    setTimeout(() => {
                        setupInvisibleRecaptchaWithFallback();
                    }, 1000);
                } else {
                    errorMessage += 'Please try again or use alternative login methods.';
                    showAlternatives = true;
                }
                
                showError(errorMessage);
                
                // Show helpful alternatives
                if (showAlternatives) {
                    setTimeout(() => {
                        showSuccess('💡 Try Google Login (fast & reliable) or Email Login below!');
                    }, 4000);
                }
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
     * Resend OTP - ENHANCED ERROR HANDLING
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
        
        // Reset and setup reCAPTCHA
        setupInvisibleRecaptchaWithFallback();
        
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
                        
                        if (error.code === 'auth/internal-error-encountered') {
                            showError('Service temporarily unavailable. Please try Google/Email login.');
                        } else {
                            showError('Failed to resend OTP. Please try Google/Email login.');
                        }
                        
                        resendBtn.prop('disabled', false).text('Resend OTP');
                    });
            } else {
                showError('Security verification not ready. Please try Google/Email login.');
                resendBtn.prop('disabled', false).text('Resend OTP');
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
     * Login with Google - ENHANCED WITH AUTO-REGISTRATION & LOOP PREVENTION
     */
    function loginWithGoogle() {
        if (!auth) {
            showError('Authentication service not available. Please refresh the page.');
            return;
        }

        // Reset attempts when starting fresh
        googleLoginAttempts = 0;
        googleRegistrationAttempts = 0;

        showLoading('Connecting to Google...');

        const provider = new firebase.auth.GoogleAuthProvider();
        provider.addScope('email');
        provider.addScope('profile');

        auth.signInWithPopup(provider)
            .then(function(result) {
                const user = result.user;
                console.log('✅ Google login successful');
                console.log('📋 Google User Data:', {
                    uid: user.uid,
                    email: user.email,
                    displayName: user.displayName,
                    photoURL: user.photoURL
                });
                
                // For Google auth, we handle differently than phone auth
                loginToWordPressWithGoogle(user);
            })
            .catch(function(error) {
                hideLoading();
                googleLoginAttempts = 0; // Reset on error
                googleRegistrationAttempts = 0; // Reset on error
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
     * Handle Email Login - ENHANCED WITH PROPER VALIDATION
     */
    function handleEmailLogin() {
        console.log('� Email login initiated');
        
        const email = $('#email-login').val().trim();
        const password = $('#password-login').val();
        
        console.log('� Email login attempt for:', email);
        
        // Validation
        if (!email || !password) {
            showError('Please fill in all required fields.');
            if (!email) $('#email-login').focus();
            else $('#password-login').focus();
            return;
        }

        if (!isValidEmail(email)) {
            showError('Please enter a valid email address.');
            $('#email-login').focus();
            return;
        }
        
        loginWithEmail(email, password);
    }

    /**
     * Handle Email Registration - ENHANCED WITH PROPER VALIDATION
     */
    function handleEmailRegistration() {
        console.log('📝 Email registration initiated');
        
        const email = $('#email-register').val().trim();
        const password = $('#password-register').val();
        
        console.log('� Email registration attempt for:', email);
        
        // Validation
        if (!email || !password) {
            showError('Please fill in all required fields.');
            if (!email) $('#email-register').focus();
            else $('#password-register').focus();
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
    }

    /**
     * Login with Email - ENHANCED WITH BETTER ERROR HANDLING
     */
    function loginWithEmail(email, password) {
        console.log('🔥 loginWithEmail called with:', { email: email });
        showLoading('Signing in...');

        auth.signInWithEmailAndPassword(email, password)
            .then(function(result) {
                const user = result.user;
                console.log('✅ Email login successful for:', user.email);
                console.log('📋 User Data:', {
                    uid: user.uid,
                    email: user.email,
                    emailVerified: user.emailVerified
                });
                
                // For email auth, we use the standard login flow
                console.log('🔄 Calling loginToWordPress for existing email user...');
                loginToWordPress(user, 'email');
            })
            .catch(function(error) {
                hideLoading();
                console.error('❌ Email login error:', error);
                
                let errorMessage = 'Login failed. ';
                switch (error.code) {
                    case 'auth/user-not-found':
                        errorMessage = 'No account found with this email address. Please register first or check your email.';
                        break;
                    case 'auth/wrong-password':
                        errorMessage = 'Incorrect password. Please try again or reset your password.';
                        break;
                    case 'auth/invalid-email':
                        errorMessage = 'Invalid email address format. Please check and try again.';
                        break;
                    case 'auth/too-many-requests':
                        errorMessage = 'Too many failed attempts. Please try again later or reset your password.';
                        break;
                    case 'auth/user-disabled':
                        errorMessage = 'This account has been disabled. Please contact support.';
                        break;
                    case 'auth/network-request-failed':
                        errorMessage = 'Network error. Please check your internet connection and try again.';
                        break;
                    default:
                        errorMessage += 'Please check your credentials and try again.';
                }
                
                showError(errorMessage);
                
                // Focus back to password field for wrong password
                if (error.code === 'auth/wrong-password') {
                    $('#password-login').val('').focus();
                }
            });
    }

    /**
     * Register with Email - ENHANCED WITH BETTER ERROR HANDLING
     */
    function registerWithEmail(email, password) {
        console.log('🔥 registerWithEmail called with:', { email: email, password: '***' });
        showLoading('Creating your account...');

        auth.createUserWithEmailAndPassword(email, password)
            .then(function(result) {
                const user = result.user;
                console.log('✅ Email registration successful for:', user.email);
                console.log('📋 New User Data:', {
                    uid: user.uid,
                    email: user.email,
                    emailVerified: user.emailVerified
                });
                
                // For email registration, we need to show registration modal 
                // to collect additional user info (name) for WordPress account
                console.log('👤 New email user created, showing registration modal...');
                hideLoading(); // Hide loading before showing modal
                showUserRegistrationModal(user, 'email');
            })
            .catch(function(error) {
                hideLoading();
                console.error('❌ Email registration error:', error);
                
                let errorMessage = 'Registration failed. ';
                switch (error.code) {
                    case 'auth/email-already-in-use':
                        errorMessage = 'An account with this email already exists. Please sign in instead or use a different email.';
                        break;
                    case 'auth/invalid-email':
                        errorMessage = 'Invalid email address format. Please check and try again.';
                        break;
                    case 'auth/weak-password':
                        errorMessage = 'Password is too weak. Please choose a stronger password (at least 6 characters).';
                        break;
                    case 'auth/operation-not-allowed':
                        errorMessage = 'Email registration is currently disabled. Please contact support.';
                        break;
                    case 'auth/network-request-failed':
                        errorMessage = 'Network error. Please check your internet connection and try again.';
                        break;
                    default:
                        errorMessage += 'Please try again or contact support if the problem persists.';
                }
                
                showError(errorMessage);
                
                // If email already exists, suggest switching to login
                if (error.code === 'auth/email-already-in-use') {
                    setTimeout(() => {
                        showSuccess('💡 Try switching to "Sign In" tab to login with this email!');
                    }, 2000);
                }
            });
    }

    /**
     * Login to WordPress - ENHANCED WITH REGISTRATION FLOW
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

                // Pass actual user data for email authentication
                if (authMethod === 'email') {
                    userData.email_uid = firebaseUser.uid || '';
                    userData.email_address = firebaseUser.email || '';
                    userData.email_verified = firebaseUser.emailVerified || false;
                }

                console.log('📤 Sending login request to WordPress with nonce:', userData.nonce);

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
                            
                        } else if (response.data.code === 'user_not_registered') {
                            // 🆕 NEW USER - Show registration form
                            console.log('👤 New user detected, showing registration form');
                            showUserRegistrationModal(firebaseUser, authMethod);
                            
                        } else {
                            const errorCode = response.data.code || '';
                            let errorMessage = response.data.message || 'Login failed. Please try again.';
                            
                            if (errorCode === 'nonce_failed') {
                                errorMessage = 'Security verification failed. Please refresh the page and try again.';
                            } else if (errorCode === 'firebase_auth_failed') {
                                errorMessage = 'Authentication token verification failed. Please try again or use a different login method.';
                            }
                            
                            showError(errorMessage);
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
     * Login to WordPress with Google - ENHANCED WITH AUTO-REGISTRATION & LOOP PREVENTION
     * For Google auth, we try login first. If user doesn't exist, we auto-register them.
     */
    function loginToWordPressWithGoogle(firebaseUser) {
        if (!firebaseUser) {
            hideLoading();
            showError('Google authentication failed. Please try again.');
            return;
        }

        // Prevent infinite loops
        if (googleLoginAttempts >= MAX_GOOGLE_LOGIN_ATTEMPTS) {
            hideLoading();
            console.error('❌ Maximum Google login attempts reached');
            showError('Google login failed after multiple attempts. Please refresh the page and try again.');
            return;
        }

        googleLoginAttempts++;
        console.log('🔄 Logging into WordPress with Google... (Attempt: ' + googleLoginAttempts + ')');

        firebaseUser.getIdToken()
            .then(function(idToken) {
                
                const userData = {
                    action: 'tostishop_firebase_login',
                    firebase_token: idToken,
                    nonce: tostiShopAjax.nonce,
                    auth_method: 'google',
                    // Pass actual Google user data to backend
                    google_email: firebaseUser.email || '',
                    google_name: firebaseUser.displayName || '',
                    google_uid: firebaseUser.uid || '',
                    from_checkout: window.location.href.includes('checkout') ? 'true' : 'false'
                };

                console.log('📤 Sending Google login request to WordPress with user data:', {
                    email: firebaseUser.email,
                    name: firebaseUser.displayName,
                    uid: firebaseUser.uid
                });

                $.ajax({
                    url: tostiShopAjax.ajaxurl,
                    type: 'POST',
                    data: userData,
                    timeout: 30000,
                    success: function(response) {
                        console.log('📥 WordPress Google login response:', response);
                        
                        if (response.success) {
                            // ✅ EXISTING USER - Login successful
                            hideLoading();
                            googleLoginAttempts = 0; // Reset attempts on success
                            showSuccess(response.data.message || 'Welcome back! Redirecting...');
                            
                            setTimeout(function() {
                                window.location.href = response.data.redirect_url || tostiShopAjax.redirectUrl;
                            }, 1500);
                            
                        } else if (response.data.code === 'user_not_registered') {
                            // 🆕 NEW GOOGLE USER - Auto-register them (no modal)
                            console.log('👤 New Google user detected, auto-registering...');
                            autoRegisterGoogleUser(firebaseUser);
                            
                        } else {
                            hideLoading();
                            googleLoginAttempts = 0; // Reset attempts on final error
                            const errorCode = response.data.code || '';
                            let errorMessage = response.data.message || 'Google login failed. Please try again.';
                            
                            if (errorCode === 'nonce_failed') {
                                errorMessage = 'Security verification failed. Please refresh the page and try again.';
                            } else if (errorCode === 'firebase_auth_failed') {
                                errorMessage = 'Google authentication verification failed. Please try again.';
                            }
                            
                            showError(errorMessage);
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        googleLoginAttempts = 0; // Reset attempts on connection error
                        console.error('❌ WordPress Google login error:', xhr.responseText, status, error);
                        showError('Connection error. Please check your internet connection and try again.');
                    }
                });
            })
            .catch(function(error) {
                hideLoading();
                googleLoginAttempts = 0; // Reset attempts on token error
                console.error('❌ Google token retrieval error:', error);
                showError('Authentication token error. Please try again.');
            });
    }

    /**
     * Auto-register Google user - NO MODAL NEEDED, NO LOOPS
     */
    function autoRegisterGoogleUser(firebaseUser) {
        if (!firebaseUser) {
            hideLoading();
            showError('Google authentication failed. Please try again.');
            return;
        }

        // Prevent infinite loops for Google registration too
        if (googleRegistrationAttempts >= MAX_GOOGLE_REGISTRATION_ATTEMPTS) {
            hideLoading();
            googleLoginAttempts = 0; // Reset login attempts as well
            console.error('❌ Maximum Google registration attempts reached');
            showError('Google account creation failed after multiple attempts. Please refresh the page and try again, or use email registration.');
            return;
        }

        googleRegistrationAttempts++;
        console.log('🔄 Auto-registering Google user... (Attempt: ' + googleRegistrationAttempts + ')');
        
        showLoading('Creating your account...');
        
        // Extract name from Google data
        const displayName = firebaseUser.displayName || '';
        const nameParts = displayName.split(' ');
        const firstName = nameParts[0] || 'User';
        const lastName = nameParts.slice(1).join(' ') || 'Google';
        
        firebaseUser.getIdToken()
            .then(function(idToken) {
                
                const registrationData = {
                    action: 'tostishop_firebase_register',
                    firebase_token: idToken,
                    nonce: tostiShopAjax.nonce,
                    auth_method: 'google',
                    first_name: firstName,
                    last_name: lastName,
                    user_email: firebaseUser.email || '',
                    // Pass actual Google user data to backend
                    google_email: firebaseUser.email || '',
                    google_name: firebaseUser.displayName || '',
                    google_uid: firebaseUser.uid || '',
                    user_phone: '', // Google doesn't provide phone
                    from_checkout: window.location.href.includes('checkout') ? 'true' : 'false'
                };
                
                console.log('📤 Sending Google auto-registration request', {
                    action: registrationData.action,
                    auth_method: registrationData.auth_method,
                    email: firebaseUser.email,
                    name: firebaseUser.displayName,
                    uid: firebaseUser.uid,
                    attempt: googleRegistrationAttempts
                });
                
                $.ajax({
                    url: tostiShopAjax.ajaxurl,
                    type: 'POST',
                    data: registrationData,
                    timeout: 30000,
                    success: function(response) {
                        hideLoading();
                        googleLoginAttempts = 0; // Reset login attempts on completion
                        googleRegistrationAttempts = 0; // Reset registration attempts on completion
                        
                        console.log('📥 Google registration response:', response);
                        
                        if (response.success) {
                            showSuccess(response.data.message || '🎉 Welcome to TostiShop! Account created successfully.');
                            
                            setTimeout(function() {
                                window.location.href = response.data.redirect_url || tostiShopAjax.redirectUrl || '/my-account/';
                            }, 2000);
                            
                        } else {
                            const errorCode = response.data.code || '';
                            let errorMessage = response.data.message || 'Account creation failed. Please try again.';
                            
                            if (errorCode === 'email_exists') {
                                // Email exists - try to login instead
                                errorMessage = 'An account with this email already exists. Attempting to log you in...';
                                showError(errorMessage);
                                
                                // Try to login instead (but don't create a loop)
                                setTimeout(() => {
                                    showLoading('Logging you in...');
                                    // Reset attempts and try login once more
                                    googleLoginAttempts = 0;
                                    loginToWordPressWithGoogle(firebaseUser);
                                }, 2000);
                                return;
                            }
                            
                            console.error('❌ Google registration failed:', errorMessage);
                            showError(errorMessage);
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        googleLoginAttempts = 0; // Reset login attempts on error
                        googleRegistrationAttempts = 0; // Reset registration attempts on error
                        console.error('❌ Google registration error:', xhr.responseText, status, error);
                        showError('Account creation failed. Please try again or contact support.');
                    }
                });
            })
            .catch(function(error) {
                hideLoading();
                googleLoginAttempts = 0; // Reset login attempts on token error
                googleRegistrationAttempts = 0; // Reset registration attempts on token error
                console.error('❌ Google registration token error:', error);
                showError('Authentication error during registration. Please try again.');
            });
    }

    /**
     * Show User Registration Modal - ENHANCED FOR DESKTOP LAYOUT
     */
    function showUserRegistrationModal(firebaseUser, authMethod) {
        console.log('🔄 Showing registration modal for new user');
        
        // Pre-fill available data
        const userEmail = firebaseUser.email || '';
        const userName = firebaseUser.displayName || '';
        const userPhone = firebaseUser.phoneNumber || currentPhoneNumber || '';
        
        // Split name into first and last if available
        if (userName) {
            const nameParts = userName.split(' ');
            $('#user-first-name').val(nameParts[0] || '');
            $('#user-last-name').val(nameParts.slice(1).join(' ') || '');
        }
        
        $('#user-email').val(userEmail);
        
        // Show authentication method with TostiShop colors
        let authMethodText = '';
        if (authMethod === 'phone') {
            authMethodText = `📱 Authenticated via Phone: ${userPhone}`;
            $('#user-phone-display').val(userPhone);
            $('#phone-auth-display').removeClass('hidden');
            
            // Adjust email field width when phone is shown
            $('#user-email').parent().removeClass('md:col-span-2').addClass('md:col-span-1');
        } else if (authMethod === 'google') {
            authMethodText = `🔍 Authenticated via Google`;
            $('#phone-auth-display').addClass('hidden');
            
            // Email takes full width when no phone
            $('#user-email').parent().removeClass('md:col-span-1').addClass('md:col-span-2');
        } else if (authMethod === 'email') {
            authMethodText = `📧 Authenticated via Email`;
            $('#phone-auth-display').addClass('hidden');
            
            // Email takes full width when no phone
            $('#user-email').parent().removeClass('md:col-span-1').addClass('md:col-span-2');
        }
        
        $('#auth-method-display').text(authMethodText);
        
        // Show modal with enhanced desktop styling
        $('#user-registration-modal').removeClass('hidden');
        
        // Add backdrop blur effect
        $('body').addClass('modal-open');
        
        // Focus strategy based on what's missing
        setTimeout(() => {
            if (!$('#user-first-name').val()) {
                $('#user-first-name').focus();
            } else if (!$('#user-last-name').val()) {
                $('#user-last-name').focus();
            } else if (!userEmail && authMethod === 'phone') {
                $('#user-email').focus();
            } else {
                $('#user-first-name').focus();
            }
        }, 300);
        
        // Store Firebase user data for later use
        window.pendingFirebaseUser = firebaseUser;
        window.pendingAuthMethod = authMethod;
        window.pendingPhoneNumber = userPhone;
    }

    /**
     * Complete User Registration - NEW FUNCTION
     */
    function completeUserRegistration() {
        const firstName = $('#user-first-name').val().trim();
        const lastName = $('#user-last-name').val().trim();
        const email = $('#user-email').val().trim();
        
        // Validation
        if (!firstName || firstName.length < 2) {
            showError('Please enter your first name (at least 2 characters).');
            $('#user-first-name').focus();
            return;
        }
        
        if (!lastName || lastName.length < 2) {
            showError('Please enter your last name (at least 2 characters).');
            $('#user-last-name').focus();
            return;
        }
        
        if (!email || !isValidEmail(email)) {
            showError('Please enter a valid email address.');
            $('#user-email').focus();
            return;
        }
        
        const firebaseUser = window.pendingFirebaseUser;
        const authMethod = window.pendingAuthMethod;
        const phoneNumber = window.pendingPhoneNumber || '';
        
        if (!firebaseUser) {
            showError('Session expired. Please try logging in again.');
            $('#user-registration-modal').addClass('hidden');
            return;
        }
        
        showLoading('Creating your TostiShop account...');
        
        // Get Firebase token and create WordPress user
        firebaseUser.getIdToken()
            .then(function(idToken) {
                
                const registrationData = {
                    action: 'tostishop_firebase_register',
                    firebase_token: idToken,
                    nonce: tostiShopAjax.nonce,
                    auth_method: authMethod,
                    first_name: firstName,
                    last_name: lastName,
                    user_email: email,
                    user_phone: phoneNumber,
                    from_checkout: window.location.href.includes('checkout') ? 'true' : 'false'
                };

                console.log('📤 Sending registration request to WordPress', {
                    firstName: firstName,
                    lastName: lastName,
                    email: email,
                    phone: phoneNumber,
                    method: authMethod
                });

                $.ajax({
                    url: tostiShopAjax.ajaxurl,
                    type: 'POST',
                    data: registrationData,
                    timeout: 30000,
                    success: function(response) {
                        hideLoading();
                        
                        console.log('📥 Registration response:', response);
                        
                        if (response.success) {
                            $('#user-registration-modal').addClass('hidden');
                            showSuccess('🎉 Welcome to TostiShop! Account created successfully. Redirecting...');
                            
                            // Clean up
                            window.pendingFirebaseUser = null;
                            window.pendingAuthMethod = null;
                            window.pendingPhoneNumber = null;
                            
                            setTimeout(function() {
                                window.location.href = response.data.redirect_url || tostiShopAjax.redirectUrl || '/my-account/';
                            }, 2000);
                            
                        } else {
                            const errorMessage = response.data ? response.data.message : 'Registration failed. Please try again.';
                            showError(errorMessage);
                        }
                    },
                    error: function(xhr, status, error) {
                        hideLoading();
                        console.error('❌ Registration error:', xhr.responseText, status, error);
                        showError('Registration failed. Please check your connection and try again.');
                    }
                });
            })
            .catch(function(error) {
                hideLoading();
                console.error('❌ Token retrieval error:', error);
                showError('Authentication error. Please try again.');
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
        
        const hideDelay = message.includes('unavailable') || message.includes('500') ? 12000 : 8000;
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

    /**
     * Close registration modal and clean up
     */
    function closeRegistrationModal() {
        $('#user-registration-modal').addClass('hidden');
        $('body').removeClass('modal-open');
        
        // Clean up stored data
        window.pendingFirebaseUser = null;
        window.pendingAuthMethod = null;
        window.pendingPhoneNumber = null;
        
        // Reset form
        resetRegistrationForm();
    }

    /**
     * Reset registration form to default state
     */
    function resetRegistrationForm() {
        // Clear form fields (but preserve pre-filled data)
        // $('#user-first-name, #user-last-name, #user-email').val('');
        
        // Re-enable form elements
        $('#complete-registration-btn').prop('disabled', false).html(`
            <span class="flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Create TostiShop Account</span>
            </span>
        `);
        $('#cancel-registration').prop('disabled', false);
        
        // Remove error styling
        $('#user-first-name, #user-last-name, #user-email').removeClass('border-red-300');
    }

    // Global error handler
    window.addEventListener('unhandledrejection', function(event) {
        if (event.reason && event.reason.code && event.reason.code.startsWith('auth/')) {
            console.error('Unhandled Firebase auth error:', event.reason);
            
            // Handle specific Firebase errors
            if (event.reason.code === 'auth/internal-error-encountered') {
                showError('🚫 Firebase service temporarily unavailable. Please use Google or Email login.');
            } else {
                showError('Authentication error occurred. Please try alternative login methods.');
            }
            
            event.preventDefault();
        }
    });

})(jQuery);
