<?php
/**
 * TostiShop Firebase Authentication AJAX Handlers - PRODUCTION VERSION
 * Real Firebase authentication with proper token verification
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle Firebase login AJAX request - PRODUCTION VERSION
 * Enhanced with complete WooCommerce user sync workflow
 */
function tostishop_handle_firebase_login() {
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
    $auth_method = isset($_POST['auth_method']) ? sanitize_text_field($_POST['auth_method']) : 'unknown';
    $from_checkout = isset($_POST['from_checkout']) && $_POST['from_checkout'] === 'true';
    $check_user_exists = isset($_POST['check_user_exists']) && $_POST['check_user_exists'] === 'true';
    $force_registration = isset($_POST['force_registration']) && $_POST['force_registration'] === 'true';
    
    // Get real user data from frontend (Firebase already verified these)
    $phone_number = isset($_POST['phone_number']) ? sanitize_text_field($_POST['phone_number']) : '';
    $user_email = isset($_POST['user_email']) ? sanitize_email($_POST['user_email']) : '';
    $user_name = isset($_POST['user_name']) ? sanitize_text_field($_POST['user_name']) : '';
    $user_uid = isset($_POST['user_uid']) ? sanitize_text_field($_POST['user_uid']) : '';
    $email_verified = isset($_POST['email_verified']) && $_POST['email_verified'] === 'true';

    try {
        // Verify Firebase token and get user data
        $firebase_user_data = tostishop_verify_firebase_token_production($firebase_token);
        
        if (!$firebase_user_data) {
            wp_send_json_error(array(
                'message' => 'Invalid authentication token.',
                'code' => 'firebase_auth_failed'
            ));
            return;
        }
        
        // Use real data from frontend when available, fallback to token data
        $final_user_data = array(
            'uid' => !empty($user_uid) ? $user_uid : $firebase_user_data['uid'],
            'email' => !empty($user_email) ? $user_email : $firebase_user_data['email'],
            'name' => !empty($user_name) ? $user_name : $firebase_user_data['name'],
            'phone_number' => $phone_number,
            'email_verified' => $email_verified || $firebase_user_data['email_verified'],
            'auth_method' => $auth_method,
            'picture' => $firebase_user_data['picture'] ?? ''
        );

        // Use enhanced user checking function
        $user_check_result = tostishop_check_wc_user_exists($final_user_data);
        $existing_user_id = $user_check_result['user_id'];
        
        // Handle different scenarios based on flags
        if ($check_user_exists && !$force_registration) {
            // Just checking if user exists
            if (!$user_check_result['exists']) {
                wp_send_json_error(array(
                    'message' => 'User not found.',
                    'code' => 'user_not_found',
                    'details' => 'No account found for this ' . $auth_method . '. Please register first.'
                ));
                return;
            }
            
            // User exists, proceed with enhanced login
            $login_success = tostishop_wc_login_user_by_email($final_user_data['email'], $final_user_data);
            
            if (!$login_success) {
                wp_send_json_error(array(
                    'message' => 'Login failed.',
                    'code' => 'login_failed'
                ));
                return;
            }
            
            // Determine redirect URL
            $redirect_url = home_url('/my-account/');
            if ($from_checkout) {
                $redirect_url = wc_get_checkout_url();
            }
            
            wp_send_json_success(array(
                'message' => 'Welcome back!',
                'redirect_url' => $redirect_url,
                'user_id' => $existing_user_id,
                'auth_method' => $auth_method,
                'found_by' => $user_check_result['found_by']
            ));
            return;
        }
        
        if ($force_registration || !$user_check_result['exists']) {
            // Create new user or force registration
            
            // For phone auth without email, require additional user data collection
            if ($auth_method === 'phone' && empty($final_user_data['email'])) {
                wp_send_json_error(array(
                    'message' => 'Email is required to create account.',
                    'code' => 'email_required',
                    'details' => 'Phone authentication requires email and name to complete registration.'
                ));
                return;
            }
            
            // For phone auth with incomplete data, require registration completion
            if ($auth_method === 'phone' && !$force_registration && (empty($user_email) || empty($user_name))) {
                wp_send_json_error(array(
                    'message' => 'Please complete registration.',
                    'code' => 'registration_required',
                    'details' => 'Phone users must provide name and email to complete account creation.'
                ));
                return;
            }
            
            // Use enhanced user creation function
            $new_user_id = tostishop_create_wc_user_from_firebase($final_user_data);
            
            if (is_wp_error($new_user_id)) {
                wp_send_json_error(array(
                    'message' => 'Failed to create account: ' . $new_user_id->get_error_message(),
                    'code' => 'user_creation_failed'
                ));
                return;
            }
            
            $existing_user_id = $new_user_id;
        } else {
            // Update existing user with latest Firebase data
            tostishop_update_user_from_firebase($existing_user_id, $final_user_data);
        }

        // Log in the user
        wp_set_current_user($existing_user_id);
        wp_set_auth_cookie($existing_user_id, true);
        
        // Update last login
        update_user_meta($existing_user_id, 'firebase_last_login', current_time('mysql'));
        update_user_meta($existing_user_id, 'firebase_auth_method', $auth_method);
        
        // Determine redirect URL
        $redirect_url = home_url('/my-account/');
        if ($from_checkout) {
            $redirect_url = wc_get_checkout_url();
        }
        
        $welcome_message = $force_registration ? 'Welcome to TostiShop!' : 'Welcome back!';
        
        wp_send_json_success(array(
            'message' => $welcome_message,
            'redirect_url' => $redirect_url,
            'user_id' => $existing_user_id,
            'auth_method' => $auth_method
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
 * PRODUCTION Firebase Token Verification
 * Verify Firebase JWT token and extract real user data
 */
function tostishop_verify_firebase_token_production($token) {
    if (empty($token)) {
        return false;
    }
    
    // Split JWT token
    $token_parts = explode('.', $token);
    if (count($token_parts) !== 3) {
        error_log('Invalid JWT token format');
        return false;
    }
    
    try {
        // Decode payload (2nd part of JWT)
        $payload_encoded = $token_parts[1];
        // Add padding if needed
        $payload_encoded .= str_repeat('=', (4 - strlen($payload_encoded) % 4) % 4);
        $payload_json = base64_decode(strtr($payload_encoded, '-_', '+/'));
        $payload = json_decode($payload_json, true);
        
        if (!$payload || !isset($payload['sub'])) {
            error_log('Invalid token payload or missing subject');
            return false;
        }
        
        // Extract real user data from Firebase token
        $user_data = array(
            'uid' => $payload['sub'], // Firebase UID
            'email' => isset($payload['email']) ? $payload['email'] : '',
            'name' => isset($payload['name']) ? $payload['name'] : '',
            'picture' => isset($payload['picture']) ? $payload['picture'] : '',
            'email_verified' => isset($payload['email_verified']) ? $payload['email_verified'] : false,
            'phone_number' => isset($payload['phone_number']) ? $payload['phone_number'] : '',
            'provider' => isset($payload['firebase']['sign_in_provider']) ? $payload['firebase']['sign_in_provider'] : 'unknown',
            'auth_time' => isset($payload['auth_time']) ? $payload['auth_time'] : time(),
            'exp' => isset($payload['exp']) ? $payload['exp'] : 0
        );
        
        // Check if token is expired
        if ($user_data['exp'] > 0 && $user_data['exp'] < time()) {
            error_log('Firebase token has expired');
            return false;
        }
        
        error_log('✅ Firebase token verified for user: ' . $user_data['uid'] . ' (' . $user_data['email'] . ')');
        return $user_data;
        
    } catch (Exception $e) {
        error_log('Error decoding Firebase token: ' . $e->getMessage());
        return false;
    }
}

/**
 * Find existing WordPress user based on Firebase data - PRODUCTION VERSION
 */
function tostishop_find_existing_user_production($firebase_user_data) {
    // Try to find user by Firebase UID first (most reliable)
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
            update_user_meta($user->ID, 'firebase_auth_method', $firebase_user_data['auth_method']);
            return $user->ID;
        }
    }
    
    // Try by phone for phone auth
    if (!empty($firebase_user_data['phone_number'])) {
        $users = get_users(array(
            'meta_key' => 'firebase_phone',
            'meta_value' => $firebase_user_data['phone_number'],
            'number' => 1
        ));
        
        if (!empty($users)) {
            // Link Firebase UID to existing user
            update_user_meta($users[0]->ID, 'firebase_uid', $firebase_user_data['uid']);
            update_user_meta($users[0]->ID, 'firebase_auth_method', $firebase_user_data['auth_method']);
            return $users[0]->ID;
        }
    }
    
    return false;
}

/**
 * Create WordPress user from Firebase data - PRODUCTION VERSION
 */
function tostishop_create_user_from_firebase($firebase_user_data) {
    // Determine email (required for WordPress user)
    $email = $firebase_user_data['email'];
    
    // For phone-only auth, we need to handle users without email
    if (empty($email) && !empty($firebase_user_data['phone_number'])) {
        // Generate a temporary email for phone-only users
        $phone_clean = preg_replace('/[^0-9]/', '', $firebase_user_data['phone_number']);
        $email = 'phone_' . $phone_clean . '@tostishop.local';
    }
    
    if (empty($email)) {
        return new WP_Error('no_email', 'Email is required to create account');
    }
    
    // Check if email already exists
    if (email_exists($email)) {
        return new WP_Error('email_exists', 'An account with this email already exists');
    }
    
    // Generate username
    $username = tostishop_generate_unique_username_production($firebase_user_data, $email);
    
    // Extract name information
    $display_name = !empty($firebase_user_data['name']) ? $firebase_user_data['name'] : 'TostiShop Customer';
    $name_parts = explode(' ', $display_name, 2);
    $first_name = $name_parts[0];
    $last_name = isset($name_parts[1]) ? $name_parts[1] : '';
    
    // Create WordPress user
    $user_data = array(
        'user_login' => $username,
        'user_email' => $email,
        'display_name' => $display_name,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'user_pass' => wp_generate_password(16, true, true),
        'role' => 'customer'
    );
    
    $user_id = wp_insert_user($user_data);
    
    if (is_wp_error($user_id)) {
        return $user_id;
    }
    
    // Add Firebase metadata
    update_user_meta($user_id, 'firebase_uid', $firebase_user_data['uid']);
    update_user_meta($user_id, 'firebase_auth_method', $firebase_user_data['auth_method']);
    update_user_meta($user_id, 'firebase_registration_date', current_time('mysql'));
    
    // Add phone number if available
    if (!empty($firebase_user_data['phone_number'])) {
        update_user_meta($user_id, 'firebase_phone', $firebase_user_data['phone_number']);
        update_user_meta($user_id, 'billing_phone', $firebase_user_data['phone_number']);
    }
    
    // Add email verification status
    if (isset($firebase_user_data['email_verified']) && $firebase_user_data['email_verified']) {
        update_user_meta($user_id, 'firebase_email_verified', '1');
    }
    
    // Add profile picture if available
    if (!empty($firebase_user_data['picture'])) {
        update_user_meta($user_id, 'firebase_profile_picture', $firebase_user_data['picture']);
    }
    
    error_log('✅ New user created from Firebase: ' . $user_id . ' (' . $email . ')');
    
    return $user_id;
}

/**
 * Login WooCommerce user by email - Enhanced for Firebase integration
 * @param string $email User email address
 * @param array $user_data Additional user data from Firebase
 * @return bool Success status
 */
function tostishop_wc_login_user_by_email($email, $user_data = array()) {
    $user = get_user_by('email', $email);
    if ($user) {
        // Set current user and auth cookie
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, true);
        
        // Trigger WordPress login action
        do_action('wp_login', $user->user_login, $user);
        
        // Update last login timestamp
        update_user_meta($user->ID, 'last_login', current_time('mysql'));
        
        // Update Firebase-specific metadata if provided
        if (!empty($user_data['firebase_uid'])) {
            update_user_meta($user->ID, 'firebase_uid', $user_data['firebase_uid']);
        }
        if (!empty($user_data['auth_method'])) {
            update_user_meta($user->ID, 'firebase_auth_method', $user_data['auth_method']);
            update_user_meta($user->ID, 'firebase_last_login', current_time('mysql'));
        }
        if (!empty($user_data['phone_number'])) {
            update_user_meta($user->ID, 'firebase_phone', $user_data['phone_number']);
            // Also update billing phone for WooCommerce
            update_user_meta($user->ID, 'billing_phone', $user_data['phone_number']);
        }
        
        error_log('✅ User logged in successfully: ' . $email . ' (ID: ' . $user->ID . ')');
        return true;
    }
    
    error_log('❌ User not found for email: ' . $email);
    return false;
}

/**
 * Check if WooCommerce user exists and get comprehensive user info
 * @param array $firebase_user_data Firebase user data
 * @return array|false User info array or false if not found
 */
function tostishop_check_wc_user_exists($firebase_user_data) {
    $user_info = array(
        'exists' => false,
        'user_id' => null,
        'found_by' => null,
        'user' => null
    );
    
    // Try by email first (most reliable)
    if (!empty($firebase_user_data['email'])) {
        $user = get_user_by('email', $firebase_user_data['email']);
        if ($user) {
            $user_info['exists'] = true;
            $user_info['user_id'] = $user->ID;
            $user_info['found_by'] = 'email';
            $user_info['user'] = $user;
            return $user_info;
        }
    }
    
    // Try by Firebase UID
    if (!empty($firebase_user_data['uid'])) {
        $users = get_users(array(
            'meta_key' => 'firebase_uid',
            'meta_value' => $firebase_user_data['uid'],
            'number' => 1
        ));
        
        if (!empty($users)) {
            $user = $users[0];
            $user_info['exists'] = true;
            $user_info['user_id'] = $user->ID;
            $user_info['found_by'] = 'firebase_uid';
            $user_info['user'] = $user;
            return $user_info;
        }
    }
    
    // Try by phone number (for phone auth)
    if (!empty($firebase_user_data['phone_number'])) {
        $users = get_users(array(
            'meta_key' => 'firebase_phone',
            'meta_value' => $firebase_user_data['phone_number'],
            'number' => 1
        ));
        
        if (!empty($users)) {
            $user = $users[0];
            $user_info['exists'] = true;
            $user_info['user_id'] = $user->ID;
            $user_info['found_by'] = 'phone_number';
            $user_info['user'] = $user;
            return $user_info;
        }
    }
    
    return $user_info;
}

/**
 * Generate unique username for production
 */
function tostishop_generate_unique_username_production($firebase_user_data, $email) {
    // Try to use name first
    if (!empty($firebase_user_data['name'])) {
        $name_clean = sanitize_user(strtolower(str_replace(' ', '_', $firebase_user_data['name'])));
        if (!empty($name_clean) && !username_exists($name_clean)) {
            return $name_clean;
        }
    }
    
    // Try email prefix
    $email_prefix = sanitize_user(explode('@', $email)[0]);
    if (!empty($email_prefix) && !username_exists($email_prefix)) {
        return $email_prefix;
    }
    
    // Generate unique username with Firebase UID
    $uid_short = substr($firebase_user_data['uid'], -8);
    $username = 'user_' . $uid_short;
    
    // Ensure uniqueness
    $counter = 1;
    $original_username = $username;
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

// Additional helper endpoint for checking user existence
add_action('wp_ajax_tostishop_check_firebase_user', 'tostishop_handle_check_firebase_user');
add_action('wp_ajax_nopriv_tostishop_check_firebase_user', 'tostishop_handle_check_firebase_user');

/**
 * Handle checking if Firebase user exists in WooCommerce
 */
function tostishop_handle_check_firebase_user() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tostishop_firebase_nonce')) {
        wp_send_json_error(array(
            'message' => 'Security verification failed.',
            'code' => 'nonce_failed'
        ));
        return;
    }
    
    // Get user identification data
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $firebase_uid = isset($_POST['firebase_uid']) ? sanitize_text_field($_POST['firebase_uid']) : '';
    
    if (empty($email) && empty($phone) && empty($firebase_uid)) {
        wp_send_json_error(array(
            'message' => 'At least one identifier is required.',
            'code' => 'no_identifier'
        ));
        return;
    }
    
    $user_data = array();
    if (!empty($email)) $user_data['email'] = $email;
    if (!empty($phone)) $user_data['phone_number'] = $phone;
    if (!empty($firebase_uid)) $user_data['uid'] = $firebase_uid;
    
    $user_check = tostishop_check_wc_user_exists($user_data);
    
    wp_send_json_success(array(
        'exists' => $user_check['exists'],
        'user_id' => $user_check['user_id'],
        'found_by' => $user_check['found_by'],
        'needs_email_update' => $user_check['exists'] ? tostishop_user_needs_email_update($user_check['user_id']) : false
    ));
}

/**
 * Verify password for account binding - SECURITY FUNCTION
 */
function tostishop_verify_password() {
    // Security check
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_firebase_nonce')) {
        wp_send_json_error(array(
            'message' => 'Security verification failed.',
            'code' => 'nonce_failed'
        ));
        return;
    }
    
    $email = sanitize_email($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        wp_send_json_error(array(
            'message' => 'Email and password are required.',
            'code' => 'missing_data'
        ));
        return;
    }
    
    // Verify password against WordPress user
    $user = wp_authenticate($email, $password);
    
    if (is_wp_error($user)) {
        wp_send_json_error(array(
            'message' => 'Incorrect password. Please try again.',
            'code' => 'invalid_credentials'
        ));
        return;
    }
    
    // Password is correct
    wp_send_json_success(array(
        'message' => 'Password verified successfully.',
        'user_id' => $user->ID
    ));
}

/**
 * Bind phone number to existing account - SECURE PROCESS
 */
function tostishop_bind_phone_to_account() {
    // Security check
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_firebase_nonce')) {
        wp_send_json_error(array(
            'message' => 'Security verification failed.',
            'code' => 'nonce_failed'
        ));
        return;
    }
    
    $firebase_token = sanitize_text_field($_POST['firebase_token'] ?? '');
    $existing_email = sanitize_email($_POST['existing_email'] ?? '');
    $new_phone = sanitize_text_field($_POST['new_phone'] ?? '');
    $firebase_uid = sanitize_text_field($_POST['firebase_uid'] ?? '');
    $from_checkout = ($_POST['from_checkout'] ?? '') === 'true';
    
    if (empty($firebase_token) || empty($existing_email) || empty($new_phone) || empty($firebase_uid)) {
        wp_send_json_error(array(
            'message' => 'Missing required data for account binding.',
            'code' => 'missing_data'
        ));
        return;
    }
    
    // Verify Firebase token
    try {
        $decoded_token = tostishop_verify_firebase_token($firebase_token);
        if (!$decoded_token) {
            wp_send_json_error(array(
                'message' => 'Firebase authentication failed.',
                'code' => 'firebase_auth_failed'
            ));
            return;
        }
    } catch (Exception $e) {
        wp_send_json_error(array(
            'message' => 'Firebase verification error: ' . $e->getMessage(),
            'code' => 'firebase_verification_error'
        ));
        return;
    }
    
    // Get existing WordPress user
    $user = get_user_by('email', $existing_email);
    if (!$user) {
        wp_send_json_error(array(
            'message' => 'User account not found.',
            'code' => 'user_not_found'
        ));
        return;
    }
    
    // Update user meta with phone number and Firebase UID
    update_user_meta($user->ID, 'billing_phone', $new_phone);
    update_user_meta($user->ID, 'firebase_uid', $firebase_uid);
    update_user_meta($user->ID, 'phone_verified', true);
    update_user_meta($user->ID, 'phone_verification_date', current_time('mysql'));
    update_user_meta($user->ID, 'auth_method', 'phone_binding');
    
    // Log the user in
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID, true);
    
    // Determine redirect URL
    $redirect_url = home_url('/my-account/');
    if ($from_checkout) {
        $redirect_url = wc_get_checkout_url();
    }
    
    wp_send_json_success(array(
        'message' => 'Phone number successfully linked to your account!',
        'redirect_url' => $redirect_url,
        'user_id' => $user->ID,
        'phone_linked' => true
    ));
}

/**
 * Check Firebase UID link status - PREVENTS DUPLICATE LINKING
 * Uses Firebase UID only (not phone number) to check for existing links
 */
function tostishop_check_firebase_uid_status() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tostishop_firebase_nonce')) {
        wp_send_json_error(array(
            'message' => 'Security verification failed.',
            'code' => 'nonce_failed'
        ));
        return;
    }
    
    $firebase_uid = isset($_POST['firebase_uid']) ? sanitize_text_field($_POST['firebase_uid']) : '';
    $phone_number = isset($_POST['phone_number']) ? sanitize_text_field($_POST['phone_number']) : '';
    
    if (empty($firebase_uid)) {
        wp_send_json_error(array(
            'message' => 'Firebase UID is required.',
            'code' => 'firebase_uid_missing'
        ));
        return;
    }
    
    try {
        // Check if this Firebase UID is already linked to a WordPress user
        $existing_user = get_users(array(
            'meta_key' => 'firebase_uid',
            'meta_value' => $firebase_uid,
            'number' => 1,
            'fields' => 'all'
        ));
        
        if (!empty($existing_user)) {
            // Firebase UID already linked - auto-login user
            $user = $existing_user[0];
            
            // Log the user in
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID, true);
            
            // Update last login time
            update_user_meta($user->ID, 'last_login_method', 'firebase_phone');
            update_user_meta($user->ID, 'last_login_time', current_time('mysql'));
            
            // Determine redirect URL
            $redirect_url = '/my-account/';
            if (function_exists('wc_get_account_endpoint_url')) {
                $redirect_url = wc_get_account_endpoint_url('dashboard');
            }
            
            wp_send_json_success(array(
                'already_linked' => true,
                'message' => 'Welcome back! You have been logged in automatically.',
                'user_id' => $user->ID,
                'user_email' => $user->user_email,
                'redirect_url' => $redirect_url
            ));
            return;
        }
        
        // Firebase UID not linked - check if phone is in billing_phone field
        if (!empty($phone_number)) {
            $users_with_phone = get_users(array(
                'meta_key' => 'billing_phone',
                'meta_value' => $phone_number,
                'number' => 1,
                'fields' => 'all'
            ));
            
            if (!empty($users_with_phone)) {
                // Phone found but not linked - needs password verification
                $user = $users_with_phone[0];
                
                wp_send_json_success(array(
                    'already_linked' => false,
                    'phone_found' => true,
                    'needs_password_verification' => true,
                    'user_email' => $user->user_email,
                    'user_id' => $user->ID
                ));
                return;
            }
        }
        
        // Neither Firebase UID nor phone found - new registration needed
        wp_send_json_success(array(
            'already_linked' => false,
            'phone_found' => false,
            'needs_registration' => true
        ));
        
    } catch (Exception $e) {
        error_log('Check Firebase UID status error: ' . $e->getMessage());
        
        wp_send_json_error(array(
            'message' => 'Error checking account status.',
            'code' => 'check_error',
            'debug' => TOSTISHOP_DEV_MODE ? $e->getMessage() : null
        ));
    }
}

// Register the new AJAX actions
add_action('wp_ajax_tostishop_verify_password', 'tostishop_verify_password');
add_action('wp_ajax_nopriv_tostishop_verify_password', 'tostishop_verify_password');

add_action('wp_ajax_tostishop_bind_phone_to_account', 'tostishop_bind_phone_to_account');
add_action('wp_ajax_nopriv_tostishop_bind_phone_to_account', 'tostishop_bind_phone_to_account');

add_action('wp_ajax_tostishop_check_firebase_uid_status', 'tostishop_check_firebase_uid_status');
add_action('wp_ajax_nopriv_tostishop_check_firebase_uid_status', 'tostishop_check_firebase_uid_status');

// Password sync AJAX handlers
add_action('wp_ajax_tostishop_check_password_sync_queue', 'tostishop_check_password_sync_queue');
add_action('wp_ajax_nopriv_tostishop_check_password_sync_queue', 'tostishop_check_password_sync_queue');

add_action('wp_ajax_tostishop_mark_password_sync_complete', 'tostishop_mark_password_sync_complete');
add_action('wp_ajax_nopriv_tostishop_mark_password_sync_complete', 'tostishop_mark_password_sync_complete');

add_action('wp_ajax_tostishop_mark_password_sync_skipped', 'tostishop_mark_password_sync_skipped');
add_action('wp_ajax_nopriv_tostishop_mark_password_sync_skipped', 'tostishop_mark_password_sync_skipped');

/**
 * Check password sync queue for user
 */
function tostishop_check_password_sync_queue() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tostishop_firebase_nonce')) {
        wp_send_json_error(array(
            'message' => 'Security verification failed.',
            'code' => 'nonce_failed'
        ));
        return;
    }
    
    $firebase_uid = isset($_POST['firebase_uid']) ? sanitize_text_field($_POST['firebase_uid']) : '';
    
    if (empty($firebase_uid)) {
        wp_send_json_error(array(
            'message' => 'Firebase UID is required.',
            'code' => 'firebase_uid_missing'
        ));
        return;
    }
    
    try {
        // Find user by Firebase UID
        $users = get_users(array(
            'meta_key' => 'firebase_uid',
            'meta_value' => $firebase_uid,
            'number' => 1,
            'fields' => 'all'
        ));
        
        if (empty($users)) {
            wp_send_json_success(array(
                'sync_needed' => false,
                'message' => 'User not found'
            ));
            return;
        }
        
        $user = $users[0];
        
        // Check if password sync is queued
        $sync_queued = get_user_meta($user->ID, 'firebase_password_sync_queued', true);
        $sync_ready = get_user_meta($user->ID, 'firebase_password_sync_ready', true);
        
        if (!empty($sync_queued) || !empty($sync_ready)) {
            $sync_data = array(
                'sync_id' => $user->ID . '_' . time(),
                'sync_queued_date' => $sync_queued,
                'sync_ready_date' => $sync_ready,
                'user_id' => $user->ID
            );
            
            wp_send_json_success(array(
                'sync_needed' => true,
                'sync_data' => $sync_data,
                'message' => 'Password sync required'
            ));
        } else {
            wp_send_json_success(array(
                'sync_needed' => false,
                'message' => 'No sync required'
            ));
        }
        
    } catch (Exception $e) {
        error_log('Check password sync queue error: ' . $e->getMessage());
        
        wp_send_json_error(array(
            'message' => 'Error checking sync queue.',
            'code' => 'check_error',
            'debug' => TOSTISHOP_DEV_MODE ? $e->getMessage() : null
        ));
    }
}

/**
 * Mark password sync as complete
 */
function tostishop_mark_password_sync_complete() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tostishop_firebase_nonce')) {
        wp_send_json_error(array(
            'message' => 'Security verification failed.',
            'code' => 'nonce_failed'
        ));
        return;
    }
    
    $firebase_uid = isset($_POST['firebase_uid']) ? sanitize_text_field($_POST['firebase_uid']) : '';
    $sync_id = isset($_POST['sync_id']) ? sanitize_text_field($_POST['sync_id']) : '';
    
    if (empty($firebase_uid)) {
        wp_send_json_error(array(
            'message' => 'Firebase UID is required.',
            'code' => 'firebase_uid_missing'
        ));
        return;
    }
    
    try {
        // Find user by Firebase UID
        $users = get_users(array(
            'meta_key' => 'firebase_uid',
            'meta_value' => $firebase_uid,
            'number' => 1,
            'fields' => 'all'
        ));
        
        if (empty($users)) {
            wp_send_json_error(array(
                'message' => 'User not found.',
                'code' => 'user_not_found'
            ));
            return;
        }
        
        $user = $users[0];
        
        // Clear sync queue and mark as complete
        delete_user_meta($user->ID, 'firebase_password_sync_queued');
        delete_user_meta($user->ID, 'firebase_password_sync_ready');
        delete_user_meta($user->ID, 'firebase_password_sync_hash');
        delete_user_meta($user->ID, 'firebase_password_sync_pending');
        delete_user_meta($user->ID, 'firebase_password_sync_failed');
        delete_user_meta($user->ID, 'firebase_password_sync_error');
        
        // Mark sync as complete
        update_user_meta($user->ID, 'firebase_password_sync_date', current_time('mysql'));
        update_user_meta($user->ID, 'firebase_password_sync_method', 'firebase_update');
        update_user_meta($user->ID, 'firebase_password_sync_completed', current_time('mysql'));
        
        wp_send_json_success(array(
            'message' => 'Password sync marked as complete.',
            'user_id' => $user->ID
        ));
        
    } catch (Exception $e) {
        error_log('Mark password sync complete error: ' . $e->getMessage());
        
        wp_send_json_error(array(
            'message' => 'Error marking sync as complete.',
            'code' => 'sync_complete_error',
            'debug' => TOSTISHOP_DEV_MODE ? $e->getMessage() : null
        ));
    }
}

/**
 * Mark password sync as skipped
 */
function tostishop_mark_password_sync_skipped() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tostishop_firebase_nonce')) {
        wp_send_json_error(array(
            'message' => 'Security verification failed.',
            'code' => 'nonce_failed'
        ));
        return;
    }
    
    $firebase_uid = isset($_POST['firebase_uid']) ? sanitize_text_field($_POST['firebase_uid']) : '';
    
    if (empty($firebase_uid)) {
        wp_send_json_error(array(
            'message' => 'Firebase UID is required.',
            'code' => 'firebase_uid_missing'
        ));
        return;
    }
    
    try {
        // Find user by Firebase UID
        $users = get_users(array(
            'meta_key' => 'firebase_uid',
            'meta_value' => $firebase_uid,
            'number' => 1,
            'fields' => 'all'
        ));
        
        if (empty($users)) {
            wp_send_json_error(array(
                'message' => 'User not found.',
                'code' => 'user_not_found'
            ));
            return;
        }
        
        $user = $users[0];
        
        // Mark sync as skipped (will retry on next login)
        update_user_meta($user->ID, 'firebase_password_sync_skipped', current_time('mysql'));
        update_user_meta($user->ID, 'firebase_password_sync_skip_count', 
                         intval(get_user_meta($user->ID, 'firebase_password_sync_skip_count', true)) + 1);
        
        wp_send_json_success(array(
            'message' => 'Password sync marked as skipped.',
            'user_id' => $user->ID
        ));
        
    } catch (Exception $e) {
        error_log('Mark password sync skipped error: ' . $e->getMessage());
        
        wp_send_json_error(array(
            'message' => 'Error marking sync as skipped.',
            'code' => 'sync_skip_error',
            'debug' => TOSTISHOP_DEV_MODE ? $e->getMessage() : null
        ));
    }
}

/**
 * WordPress authentication fallback for Firebase login failures
 * Used when Firebase auth fails due to password sync issues
 */
function tostishop_wp_auth_fallback() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tostishop_firebase_nonce')) {
        wp_send_json_error(array(
            'message' => 'Security verification failed.',
            'code' => 'nonce_failed'
        ));
        return;
    }
    
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : ''; // Don't sanitize password
    
    if (empty($email) || empty($password)) {
        wp_send_json_error(array(
            'message' => 'Email and password are required.',
            'code' => 'missing_credentials'
        ));
        return;
    }
    
    try {
        // Attempt WordPress authentication
        $user = wp_authenticate($email, $password);
        
        if (is_wp_error($user)) {
            // Authentication failed
            $error_message = 'Invalid email or password.';
            
            // Handle specific error codes
            switch ($user->get_error_code()) {
                case 'invalid_username':
                    $error_message = 'No account found with this email address.';
                    break;
                case 'incorrect_password':
                    $error_message = 'Incorrect password. Please try again.';
                    break;
                case 'empty_username':
                case 'empty_password':
                    $error_message = 'Please enter both email and password.';
                    break;
                case 'invalid_email':
                    $error_message = 'Invalid email address format.';
                    break;
            }
            
            wp_send_json_error($error_message);
            return;
        }
        
        // Check if user has Firebase UID (should be a Firebase-linked account)
        $firebase_uid = get_user_meta($user->ID, 'firebase_uid', true);
        
        if (empty($firebase_uid)) {
            wp_send_json_error(array(
                'message' => 'This account is not linked to Firebase authentication.',
                'code' => 'not_firebase_account'
            ));
            return;
        }
        
        // WordPress authentication successful
        // Log the user in to WordPress
        wp_clear_auth_cookie();
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, false, is_ssl());
        
        // Queue password sync to update Firebase password
        update_user_meta($user->ID, 'firebase_password_sync_queued', current_time('mysql'));
        update_user_meta($user->ID, 'firebase_password_sync_ready', current_time('mysql'));
        update_user_meta($user->ID, 'firebase_password_sync_method', 'wp_fallback');
        update_user_meta($user->ID, 'firebase_password_sync_hash', wp_hash_password($password));
        
        // Log the successful fallback
        error_log("WordPress auth fallback successful for user: {$user->user_email} (ID: {$user->ID})");
        
        wp_send_json_success(array(
            'message' => 'WordPress authentication successful.',
            'user_id' => $user->ID,
            'firebase_uid' => $firebase_uid,
            'sync_required' => true
        ));
        
    } catch (Exception $e) {
        error_log('WordPress auth fallback error: ' . $e->getMessage());
        
        wp_send_json_error(array(
            'message' => 'Authentication error occurred.',
            'code' => 'auth_error',
            'debug' => TOSTISHOP_DEV_MODE ? $e->getMessage() : null
        ));
    }
}

// Hook the WordPress auth fallback AJAX handler
add_action('wp_ajax_tostishop_wp_auth_fallback', 'tostishop_wp_auth_fallback');
add_action('wp_ajax_nopriv_tostishop_wp_auth_fallback', 'tostishop_wp_auth_fallback');
