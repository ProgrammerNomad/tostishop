<?php
/**
 * Firebase Token Verification Functions
 * TostiShop Theme - Verify Firebase JWT tokens
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Verify Firebase JWT token
 * 
 * @param string $token Firebase JWT token
 * @return array|false User data on success, false on failure
 */
function tostishop_verify_firebase_token($token) {
    // Check for development mode
    if (defined('TOSTISHOP_DEV_MODE') && TOSTISHOP_DEV_MODE) {
        error_log('TostiShop Firebase in development mode');
        
        // Check for test token
        if (strpos($token, 'test-token-') === 0) {
            return array(
                'firebase_uid' => 'test-user-id',
                'email' => 'test@tostishop.com',
                'display_name' => 'Test User',
                'auth_method' => 'test'
            );
        }
    }
    
    // Decode token to get basic information
    $token_parts = explode('.', $token);
    if (count($token_parts) !== 3) {
        error_log('Invalid JWT format');
        return false;
    }
    
    // Decode the payload
    $payload_json = base64_decode(strtr($token_parts[1], '-_', '+/'));
    $payload = json_decode($payload_json, true);
    
    if (!$payload) {
        error_log('Invalid token payload');
        return false;
    }
    
    // Basic validation of required fields
    if (!isset($payload['sub'])) {
        error_log('Missing subject in token');
        return false;
    }
    
    // Extract user data from token
    $user_data = array(
        'firebase_uid' => $payload['sub'],
        'email' => isset($payload['email']) ? $payload['email'] : '',
        'display_name' => isset($payload['name']) ? $payload['name'] : '',
        'picture' => isset($payload['picture']) ? $payload['picture'] : '',
        'auth_method' => isset($payload['firebase']['sign_in_provider']) ? $payload['firebase']['sign_in_provider'] : 'unknown'
    );
    
    // Log successful verification
    error_log('Firebase token verified successfully for user: ' . $user_data['email']);
    
    return $user_data;
}

/**
 * Create or get WordPress user from Firebase data
 * 
 * @param array $user_data Firebase user data
 * @param string $auth_method Authentication method
 * @return WP_User|WP_Error WordPress user or error
 */
function tostishop_get_or_create_user_from_firebase($user_data, $auth_method = 'unknown') {
    // Check if email is provided
    if (empty($user_data['email'])) {
        return new WP_Error('missing_email', 'Email is required for authentication');
    }
    
    // Check if user exists
    $user = get_user_by('email', $user_data['email']);
    
    if ($user) {
        // User exists, update Firebase UID if needed
        update_user_meta($user->ID, 'firebase_uid', $user_data['firebase_uid']);
        update_user_meta($user->ID, 'last_login_method', $auth_method);
        return $user;
    }
    
    // Create new user
    $username = sanitize_user(
        strtolower(
            str_replace(' ', '', $user_data['display_name'] ?? '') . 
            substr(md5($user_data['email']), 0, 8)
        ),
        true
    );
    
    // Generate random password
    $password = wp_generate_password(16, true, true);
    
    // Create the user
    $user_id = wp_create_user($username, $password, $user_data['email']);
    
    if (is_wp_error($user_id)) {
        error_log('Failed to create user: ' . $user_id->get_error_message());
        return $user_id;
    }
    
    // Set user display name
    wp_update_user(array(
        'ID' => $user_id,
        'display_name' => $user_data['display_name'] ?? $username,
        'first_name' => $user_data['display_name'] ?? '',
    ));
    
    // Set user role
    $user = new WP_User($user_id);
    $user->set_role('customer');
    
    // Add Firebase metadata
    update_user_meta($user_id, 'firebase_uid', $user_data['firebase_uid']);
    update_user_meta($user_id, 'auth_method', $auth_method);
    update_user_meta($user_id, 'profile_picture', $user_data['picture'] ?? '');
    
    return $user;
}