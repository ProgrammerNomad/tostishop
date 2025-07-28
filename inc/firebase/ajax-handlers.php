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
        // For development, simulate Firebase user data
        // In production, replace this with actual Firebase token verification
        $firebase_user_data = tostishop_simulate_firebase_user($firebase_token, $auth_method);
        
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
    
    // Check if email already exists
    if (email_exists($user_email)) {
        // Check if this is the same Firebase user trying to register again
        $existing_user = get_user_by('email', $user_email);
        $existing_firebase_uid = get_user_meta($existing_user->ID, 'firebase_uid', true);
        
        if ($existing_firebase_uid === $firebase_user_data['uid']) {
            // Same Firebase user - just log them in
            wp_set_current_user($existing_user->ID);
            wp_set_auth_cookie($existing_user->ID, true);
            
            $redirect_url = home_url('/my-account/');
            if ($from_checkout) {
                $redirect_url = wc_get_checkout_url();
            }
            
            error_log('✅ Firebase user re-linked to existing account: ' . $existing_user->ID);
            
            wp_send_json_success(array(
                'message' => 'Welcome back to TostiShop!',
                'redirect_url' => $redirect_url,
                'user_id' => $existing_user->ID,
                'auth_method' => $auth_method
            ));
            return;
            
        } else {
            // Different Firebase user with same email
            wp_send_json_error(array(
                'message' => 'An account with this email already exists. Please use a different email or try logging in.',
                'code' => 'email_exists'
            ));
            return;
        }
    }

    try {
        // Verify Firebase token again
        $firebase_user_data = tostishop_simulate_firebase_user($firebase_token, $auth_method);
        
        if (!$firebase_user_data) {
            wp_send_json_error(array(
                'message' => 'Invalid authentication token.',
                'code' => 'firebase_auth_failed'
            ));
            return;
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
function tostishop_simulate_firebase_user($token, $auth_method) {
    // In development mode, simulate Firebase user based on auth method
    // For production, this should use Firebase Admin SDK to verify tokens
    
    $user_data = array(
        'uid' => '',
        'email' => '',
        'name' => '',
        'phone_number' => ''
    );
    
    if ($auth_method === 'phone') {
        // For phone auth, use consistent test data
        $user_data['uid'] = 'firebase_phone_test_user';
        $user_data['phone_number'] = '+919450987150';
        
    } elseif ($auth_method === 'google') {
        // For Google auth, create a hash from the token to ensure same user gets same data
        // This way, the same Firebase user will always get the same simulated data
        $user_hash = substr(md5($token), 0, 8);
        
        $user_data['uid'] = 'firebase_google_' . $user_hash;
        $user_data['email'] = 'googleuser' . $user_hash . '@gmail.com';
        $user_data['name'] = 'Google User ' . $user_hash;
        $user_data['email_verified'] = true;
        
        error_log('🔍 Simulating Google user (HASH-BASED): ' . $user_data['name'] . ' (' . $user_data['email'] . ')');
        
    } elseif ($auth_method === 'email') {
        // For email auth, use hash-based consistent data
        $user_hash = substr(md5($token), 0, 8);
        $user_data['uid'] = 'firebase_email_' . $user_hash;
        $user_data['email'] = 'emailuser' . $user_hash . '@example.com';
    }
    
    return $user_data;
}

/**
 * Find existing WordPress user based on Firebase data
 */
function tostishop_find_existing_user($firebase_user_data, $auth_method) {
    // Try to find user by Firebase UID first
    $users = get_users(array(
        'meta_key' => 'firebase_uid',
        'meta_value' => $firebase_user_data['uid'],
        'number' => 1
    ));
    
    if (!empty($users)) {
        return $users[0]->ID;
    }
    
    // Try by email if available
    if (!empty($firebase_user_data['email'])) {
        $user = get_user_by('email', $firebase_user_data['email']);
        if ($user) {
            // Link Firebase UID to existing user
            update_user_meta($user->ID, 'firebase_uid', $firebase_user_data['uid']);
            return $user->ID;
        }
    }
    
    // Try by phone for phone auth
    if ($auth_method === 'phone' && !empty($firebase_user_data['phone_number'])) {
        $users = get_users(array(
            'meta_key' => 'firebase_phone',
            'meta_value' => $firebase_user_data['phone_number'],
            'number' => 1
        ));
        
        if (!empty($users)) {
            update_user_meta($users[0]->ID, 'firebase_uid', $firebase_user_data['uid']);
            return $users[0]->ID;
        }
    }
    
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
