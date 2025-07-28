<?php
/**
 * TostiShop Firebase Authentication AJAX Handlers
 * Handle Firebase authentication and user registration for TostiShop
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle Firebase login AJAX request
 */
function tostishop_handle_firebase_login() {
    // Add debug logging
    error_log('🔥 TostiShop Firebase login attempt: ' . print_r($_POST, true));
    
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tostishop_firebase_nonce')) {
        wp_send_json_error(array(
            'message' => 'Security verification failed.',
            'code' => 'nonce_failed'
        ));
        return;
    }
    
    // Check if token exists
    if (!isset($_POST['firebase_token']) || empty($_POST['firebase_token'])) {
        wp_send_json_error(array(
            'message' => 'Authentication token is required.',
            'code' => 'token_missing'
        ));
        return;
    }
    
    // Get request data
    $firebase_token = sanitize_text_field($_POST['firebase_token']);
    $auth_method = isset($_POST['auth_method']) ? sanitize_text_field($_POST['auth_method']) : 'phone';
    $from_checkout = isset($_POST['from_checkout']) && $_POST['from_checkout'] === 'true';
    
    try {
        // For development, decode the actual Firebase user data from token
        // In production, this should use Firebase Admin SDK for full verification
        $firebase_user_data = tostishop_decode_firebase_token($firebase_token, $auth_method);
        
        if (!$firebase_user_data) {
            wp_send_json_error(array(
                'message' => 'Invalid authentication token.',
                'code' => 'firebase_auth_failed'
            ));
            return;
        }

        // Check if user exists in WordPress
        $existing_user_id = tostishop_find_existing_user($firebase_user_data, $auth_method);
        
        if (!$existing_user_id) {
            // 🆕 NEW USER - Request registration details
            error_log('🆕 New user detected, requesting registration details');
            
            wp_send_json_error(array(
                'message' => 'Please complete your registration.',
                'code' => 'user_not_registered',
                'firebase_data' => array(
                    'uid' => $firebase_user_data['uid'],
                    'email' => $firebase_user_data['email'] ?? '',
                    'name' => $firebase_user_data['name'] ?? '',
                    'phone' => $firebase_user_data['phone_number'] ?? '',
                    'auth_method' => $auth_method
                )
            ));
            return;
        }

        // ✅ EXISTING USER - Log them in
        wp_set_current_user($existing_user_id);
        wp_set_auth_cookie($existing_user_id, true);
        
        // Update last login
        update_user_meta($existing_user_id, 'firebase_last_login', current_time('mysql'));
        
        // Determine redirect URL
        $redirect_url = home_url('/my-account/');
        if ($from_checkout) {
            $redirect_url = wc_get_checkout_url();
        }
        
        error_log('✅ Existing user logged in successfully: ' . $existing_user_id);
        
        wp_send_json_success(array(
            'message' => 'Welcome back to TostiShop!',
            'redirect_url' => $redirect_url,
            'user_id' => $existing_user_id
        ));
        
    } catch (Exception $e) {
        error_log('❌ Firebase authentication error: ' . $e->getMessage());
        wp_send_json_error(array(
            'message' => 'Authentication failed. Please try again.',
            'code' => 'firebase_auth_failed'
        ));
    }
}

/**
 * Handle Firebase user registration AJAX request
 */
function tostishop_handle_firebase_register() {
    error_log('📝 TostiShop Firebase registration attempt: ' . print_r($_POST, true));
    
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tostishop_firebase_nonce')) {
        wp_send_json_error(array(
            'message' => 'Security verification failed.',
            'code' => 'nonce_failed'
        ));
        return;
    }

    // Get and validate input data
    $firebase_token = sanitize_text_field($_POST['firebase_token'] ?? '');
    $auth_method = sanitize_text_field($_POST['auth_method'] ?? 'phone');
    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name = sanitize_text_field($_POST['last_name'] ?? '');
    $user_email = sanitize_email($_POST['user_email'] ?? '');
    $user_phone = sanitize_text_field($_POST['user_phone'] ?? '');
    $from_checkout = isset($_POST['from_checkout']) && $_POST['from_checkout'] === 'true';
    
    // Validation
    if (empty($first_name) || strlen($first_name) < 2) {
        wp_send_json_error(array(
            'message' => 'Please enter your first name (at least 2 characters).',
            'code' => 'invalid_first_name'
        ));
        return;
    }
    
    if (empty($last_name) || strlen($last_name) < 2) {
        wp_send_json_error(array(
            'message' => 'Please enter your last name (at least 2 characters).',
            'code' => 'invalid_last_name'
        ));
        return;
    }
    
    if (empty($user_email) || !is_email($user_email)) {
        wp_send_json_error(array(
            'message' => 'Please enter a valid email address.',
            'code' => 'invalid_email'
        ));
        return;
    }
    
    try {
        // Verify Firebase token first
                // Verify Firebase token again
        $firebase_user_data = tostishop_decode_firebase_token($firebase_token, $auth_method);
        
        if (!$firebase_user_data) {
            wp_send_json_error(array(
                'message' => 'Invalid authentication token.',
                'code' => 'firebase_auth_failed'
            ));
            return;
        }
    
        // ✅ SMART EXISTING USER HANDLING
        // Check if this Firebase user already exists first
        $existing_user_id = tostishop_find_existing_user($firebase_user_data, $auth_method);
        if ($existing_user_id) {
            error_log('🔄 Existing Firebase user found during registration, logging them in: ' . $existing_user_id);
            
            wp_set_current_user($existing_user_id);
            wp_set_auth_cookie($existing_user_id, true);
            
            $redirect_url = home_url('/my-account/');
            if ($from_checkout) {
                $redirect_url = wc_get_checkout_url();
            }
            
            wp_send_json_success(array(
                'message' => 'Welcome back to TostiShop!',
                'redirect_url' => $redirect_url,
                'user_id' => $existing_user_id,
                'auth_method' => $auth_method
            ));
            return;
        }
        
        // Check if email already exists (but doesn't have Firebase UID)
        $existing_email_user = get_user_by('email', $user_email);
        if ($existing_email_user) {
            error_log('🔍 Found user with same email: ' . $existing_email_user->ID);
            
            // Check if this user already has Firebase UID
            $existing_firebase_uid = get_user_meta($existing_email_user->ID, 'firebase_uid', true);
            
            if (empty($existing_firebase_uid)) {
                // Email exists but no Firebase UID - link the accounts
                error_log('� Linking existing email user to Firebase UID');
                update_user_meta($existing_email_user->ID, 'firebase_uid', $firebase_user_data['uid']);
                update_user_meta($existing_email_user->ID, 'firebase_auth_method', $auth_method);
                
                wp_set_current_user($existing_email_user->ID);
                wp_set_auth_cookie($existing_email_user->ID, true);
                
                $redirect_url = home_url('/my-account/');
                if ($from_checkout) {
                    $redirect_url = wc_get_checkout_url();
                }
                
                wp_send_json_success(array(
                    'message' => 'Account linked successfully! Welcome back to TostiShop!',
                    'redirect_url' => $redirect_url,
                    'user_id' => $existing_email_user->ID,
                    'auth_method' => $auth_method
                ));
                return;
            } else {
                // This case should have been caught by the Firebase UID check above
                // But if it somehow gets here, also handle it gracefully
                error_log('🔄 User with email and Firebase UID exists, logging them in');
                
                wp_set_current_user($existing_email_user->ID);
                wp_set_auth_cookie($existing_email_user->ID, true);
                
                $redirect_url = home_url('/my-account/');
                if ($from_checkout) {
                    $redirect_url = wc_get_checkout_url();
                }
                
                wp_send_json_success(array(
                    'message' => 'Welcome back to TostiShop!',
                    'redirect_url' => $redirect_url,
                    'user_id' => $existing_email_user->ID,
                    'auth_method' => $auth_method
                ));
                return;
            }
        }

        // Create WordPress user
        $display_name = trim($first_name . ' ' . $last_name);
        $username = tostishop_generate_unique_username($first_name, $last_name, $user_email);
        
        $user_data = array(
            'user_login' => $username,
            'user_email' => $user_email,
            'display_name' => $display_name,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'user_pass' => wp_generate_password(12, false),
            'role' => 'customer'
        );

        $user_id = wp_insert_user($user_data);

        if (is_wp_error($user_id)) {
            error_log('❌ User creation failed: ' . $user_id->get_error_message());
            wp_send_json_error(array(
                'message' => 'Failed to create account: ' . $user_id->get_error_message(),
                'code' => 'user_creation_failed'
            ));
            return;
        }

        // Store Firebase and TostiShop user metadata
        update_user_meta($user_id, 'firebase_uid', $firebase_user_data['uid']);
        update_user_meta($user_id, 'firebase_auth_method', $auth_method);
        update_user_meta($user_id, 'firebase_registration_date', current_time('mysql'));
        
        // Store phone number for phone auth
        if (!empty($user_phone)) {
            update_user_meta($user_id, 'firebase_phone', $user_phone);
            update_user_meta($user_id, 'billing_phone', $user_phone);
        }

        // Log the new user in
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id, true);

        // Determine redirect URL
        $redirect_url = home_url('/my-account/');
        if ($from_checkout) {
            $redirect_url = wc_get_checkout_url();
        }

        // Send welcome email (optional)
        tostishop_send_welcome_email($user_id, $display_name, $user_email);

        error_log('✅ New user registered successfully: ' . $user_id);

        wp_send_json_success(array(
            'message' => 'Welcome to TostiShop! Account created successfully.',
            'redirect_url' => $redirect_url,
            'user_id' => $user_id,
            'auth_method' => $auth_method
        ));

    } catch (Exception $e) {
        error_log('❌ Registration error: ' . $e->getMessage());
        wp_send_json_error(array(
            'message' => 'Registration failed. Please try again.',
            'code' => 'registration_failed'
        ));
    }
}

/**
 * Helper Functions
 */

/**
 * Simulate Firebase user data (replace with actual Firebase Admin SDK in production)
 */
function tostishop_decode_firebase_token($token, $auth_method) {
    // Instead of simulation, decode the actual Firebase JWT token
    // The token contains real user data from Firebase authentication
    
    error_log('🔍 Decoding Firebase token for auth method: ' . $auth_method);
    
    try {
        // Firebase JWT tokens have 3 parts separated by dots
        $token_parts = explode('.', $token);
        
        if (count($token_parts) !== 3) {
            error_log('❌ Invalid JWT token format');
            return false;
        }
        
        // Decode the payload (middle part) - this contains user data
        $payload_base64 = $token_parts[1];
        
        // Add padding if needed for base64 decode
        $payload_base64 = str_pad($payload_base64, strlen($payload_base64) % 4, '=', STR_PAD_RIGHT);
        
        // Convert Firebase base64url to standard base64
        $payload_base64 = str_replace(['-', '_'], ['+', '/'], $payload_base64);
        
        $payload_json = base64_decode($payload_base64);
        $payload = json_decode($payload_json, true);
        
        if (!$payload) {
            error_log('❌ Failed to decode token payload');
            return false;
        }
        
        error_log('✅ Decoded Firebase token payload: ' . json_encode($payload));
        
        // Extract real user data from the token
        $user_data = array(
            'uid' => $payload['sub'] ?? 'unknown_uid', // Firebase UID
            'email' => $payload['email'] ?? '',
            'name' => $payload['name'] ?? '',
            'phone_number' => $payload['phone_number'] ?? '',
            'email_verified' => $payload['email_verified'] ?? false,
            'picture' => $payload['picture'] ?? ''
        );
        
        // Log the extracted data
        error_log('📤 Extracted user data: ' . json_encode($user_data));
        
        return $user_data;
        
    } catch (Exception $e) {
        error_log('❌ Error decoding Firebase token: ' . $e->getMessage());
        
        // Fallback for development/testing
        if (defined('TOSTISHOP_DEV_MODE') && TOSTISHOP_DEV_MODE) {
            error_log('� Using development fallback data');
            
            $user_data = array(
                'uid' => 'dev_user_' . time(),
                'email' => '',
                'name' => '',
                'phone_number' => ''
            );
            
            if ($auth_method === 'phone') {
                $user_data['phone_number'] = '+919450987150';
            } elseif ($auth_method === 'google') {
                $user_data['email'] = 'devgoogleuser@gmail.com';
                $user_data['name'] = 'Dev Google User';
                $user_data['email_verified'] = true;
            } elseif ($auth_method === 'email') {
                $user_data['email'] = 'devemailuser@example.com';
            }
            
            return $user_data;
        }
        
        return false;
    }
}

/**
 * Find existing WordPress user based on Firebase data
 */
function tostishop_find_existing_user($firebase_user_data, $auth_method) {
    error_log('🔍 Looking for existing user with Firebase UID: ' . $firebase_user_data['uid']);
    error_log('🔍 Email: ' . ($firebase_user_data['email'] ?? 'none'));
    error_log('🔍 Auth method: ' . $auth_method);
    
    // Try to find user by Firebase UID first (most reliable)
    $users = get_users(array(
        'meta_key' => 'firebase_uid',
        'meta_value' => $firebase_user_data['uid'],
        'number' => 1
    ));
    
    error_log('🔍 Firebase UID search found: ' . count($users) . ' users');
    
    if (!empty($users)) {
        error_log('✅ Found user by Firebase UID: ' . $users[0]->ID);
        return $users[0]->ID;
    }
    
    // Try by email if available (for linking existing accounts)
    if (!empty($firebase_user_data['email'])) {
        $user = get_user_by('email', $firebase_user_data['email']);
        if ($user) {
            error_log('✅ Found user by email, linking Firebase UID: ' . $user->ID);
            // Link Firebase UID to existing user for future logins
            update_user_meta($user->ID, 'firebase_uid', $firebase_user_data['uid']);
            update_user_meta($user->ID, 'firebase_auth_method', $auth_method);
            return $user->ID;
        } else {
            error_log('❌ No user found with email: ' . $firebase_user_data['email']);
        }
    }
    
    // Try by phone for phone auth (for linking existing accounts)
    if ($auth_method === 'phone' && !empty($firebase_user_data['phone_number'])) {
        $users = get_users(array(
            'meta_key' => 'firebase_phone',
            'meta_value' => $firebase_user_data['phone_number'],
            'number' => 1
        ));
        
        if (!empty($users)) {
            error_log('✅ Found user by phone, linking Firebase UID: ' . $users[0]->ID);
            update_user_meta($users[0]->ID, 'firebase_uid', $firebase_user_data['uid']);
            update_user_meta($users[0]->ID, 'firebase_auth_method', $auth_method);
            return $users[0]->ID;
        }
    }
    
    error_log('❌ No existing user found for Firebase UID: ' . $firebase_user_data['uid']);
    return false;
}

/**
 * Generate unique username
 */
function tostishop_generate_unique_username($first_name, $last_name, $email) {
    // Try first name + last name
    $username = sanitize_user(strtolower($first_name . '_' . $last_name));
    
    if (empty($username) || username_exists($username)) {
        // Try email prefix
        $username = sanitize_user(explode('@', $email)[0]);
    }
    
    if (empty($username) || username_exists($username)) {
        // Generate random username
        $username = 'tostishop_user_' . wp_rand(1000, 9999);
    }
    
    // Ensure it's still unique
    $original_username = $username;
    $counter = 1;
    while (username_exists($username)) {
        $username = $original_username . '_' . $counter;
        $counter++;
    }
    
    return $username;
}

/**
 * Send welcome email to new users
 */
function tostishop_send_welcome_email($user_id, $name, $email) {
    $subject = 'Welcome to TostiShop! 🎉';
    $message = "Hi {$name},\n\n";
    $message .= "Welcome to TostiShop! Your account has been created successfully.\n\n";
    $message .= "Thank you for choosing us for your beauty and personal care needs.\n\n";
    $message .= "You can now:\n";
    $message .= "• Browse our premium products\n";
    $message .= "• Track your orders\n";
    $message .= "• Manage your account\n\n";
    $message .= "Happy shopping!\n\n";
    $message .= "Best regards,\n";
    $message .= "The TostiShop Team\n";
    $message .= home_url();
    
    wp_mail($email, $subject, $message);
}

// Register AJAX handlers
add_action('wp_ajax_nopriv_tostishop_firebase_login', 'tostishop_handle_firebase_login');
add_action('wp_ajax_tostishop_firebase_login', 'tostishop_handle_firebase_login');
add_action('wp_ajax_nopriv_tostishop_firebase_register', 'tostishop_handle_firebase_register');
add_action('wp_ajax_tostishop_firebase_register', 'tostishop_handle_firebase_register');
