<?php
/**
 * Firebase + WooCommerce User Management Functions
 * Enhanced user lifecycle management for TostiShop theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create WooCommerce user from Firebase data with enhanced metadata
 * @param array $firebase_user_data Firebase user information
 * @return int|WP_Error User ID on success, WP_Error on failure
 */
function tostishop_create_wc_user_from_firebase($firebase_user_data) {
    // Validate required data
    if (empty($firebase_user_data['email']) && empty($firebase_user_data['phone_number'])) {
        return new WP_Error('no_contact_info', 'Either email or phone number is required');
    }
    
    // Determine email (required for WordPress user)
    $email = $firebase_user_data['email'];
    
    // For phone-only auth, generate a temporary email
    if (empty($email) && !empty($firebase_user_data['phone_number'])) {
        $phone_clean = preg_replace('/[^0-9]/', '', $firebase_user_data['phone_number']);
        $email = 'phone_' . $phone_clean . '@tostishop.local';
        
        // Mark as temporary email for later update
        $firebase_user_data['temporary_email'] = true;
    }
    
    if (empty($email)) {
        return new WP_Error('no_email', 'Email is required to create account');
    }
    
    // Check if email already exists
    if (email_exists($email)) {
        return new WP_Error('email_exists', 'An account with this email already exists');
    }
    
    // Generate unique username
    $username = tostishop_generate_unique_username($firebase_user_data, $email);
    
    // Extract name information
    $display_name = !empty($firebase_user_data['name']) ? $firebase_user_data['name'] : 'TostiShop Customer';
    $name_parts = explode(' ', $display_name, 2);
    $first_name = $name_parts[0];
    $last_name = isset($name_parts[1]) ? $name_parts[1] : '';
    
    // Generate strong password (user logs in via Firebase)
    $password = wp_generate_password(20, true, true);
    
    // Create WordPress user
    $user_data = array(
        'user_login' => $username,
        'user_email' => $email,
        'display_name' => $display_name,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'user_pass' => $password,
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
    update_user_meta($user_id, 'account_source', 'firebase');
    
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
    
    // Mark temporary email if applicable
    if (!empty($firebase_user_data['temporary_email'])) {
        update_user_meta($user_id, 'firebase_temporary_email', '1');
    }
    
    // Set default WooCommerce billing information
    if (!empty($first_name)) {
        update_user_meta($user_id, 'billing_first_name', $first_name);
    }
    if (!empty($last_name)) {
        update_user_meta($user_id, 'billing_last_name', $last_name);
    }
    if (!empty($firebase_user_data['email']) && !$firebase_user_data['temporary_email']) {
        update_user_meta($user_id, 'billing_email', $firebase_user_data['email']);
    }
    
    // Log successful creation
    error_log('✅ New WooCommerce user created from Firebase: ' . $user_id . ' (' . $email . ') via ' . $firebase_user_data['auth_method']);
    
    // Trigger action hook for extensions
    do_action('tostishop_user_created_from_firebase', $user_id, $firebase_user_data);
    
    return $user_id;
}

/**
 * Enhanced username generation with better fallbacks
 */
function tostishop_generate_unique_username($firebase_user_data, $email) {
    // Try name-based username first
    if (!empty($firebase_user_data['name'])) {
        $name_clean = sanitize_user(strtolower(str_replace(' ', '_', $firebase_user_data['name'])));
        if (!empty($name_clean) && !username_exists($name_clean)) {
            return $name_clean;
        }
        
        // Try with numbers if name exists
        $counter = 1;
        while (username_exists($name_clean . '_' . $counter)) {
            $counter++;
            if ($counter > 100) break; // Prevent infinite loop
        }
        if ($counter <= 100) {
            return $name_clean . '_' . $counter;
        }
    }
    
    // Try email prefix
    $email_prefix = sanitize_user(explode('@', $email)[0]);
    if (!empty($email_prefix) && !username_exists($email_prefix)) {
        return $email_prefix;
    }
    
    // Try email prefix with numbers
    $counter = 1;
    while (username_exists($email_prefix . '_' . $counter)) {
        $counter++;
        if ($counter > 100) break;
    }
    if ($counter <= 100) {
        return $email_prefix . '_' . $counter;
    }
    
    // Fallback: use Firebase UID (guaranteed unique)
    if (!empty($firebase_user_data['uid'])) {
        $uid_short = substr($firebase_user_data['uid'], -8);
        return 'user_' . $uid_short;
    }
    
    // Final fallback: timestamp + random
    return 'user_' . time() . '_' . wp_rand(100, 999);
}

/**
 * Get WooCommerce user by Firebase UID
 */
function tostishop_get_user_by_firebase_uid($firebase_uid) {
    if (empty($firebase_uid)) {
        return false;
    }
    
    $users = get_users(array(
        'meta_key' => 'firebase_uid',
        'meta_value' => $firebase_uid,
        'number' => 1
    ));
    
    return !empty($users) ? $users[0] : false;
}

/**
 * Get WooCommerce user by phone number
 */
function tostishop_get_user_by_phone($phone_number) {
    if (empty($phone_number)) {
        return false;
    }
    
    $users = get_users(array(
        'meta_key' => 'firebase_phone',
        'meta_value' => $phone_number,
        'number' => 1
    ));
    
    return !empty($users) ? $users[0] : false;
}

/**
 * Update user profile from Firebase data
 */
function tostishop_update_user_from_firebase($user_id, $firebase_user_data) {
    if (empty($user_id) || empty($firebase_user_data)) {
        return false;
    }
    
    $updated = false;
    
    // Update name if provided and different
    if (!empty($firebase_user_data['name'])) {
        $current_display_name = get_user_meta($user_id, 'display_name', true);
        if ($current_display_name !== $firebase_user_data['name']) {
            wp_update_user(array(
                'ID' => $user_id,
                'display_name' => $firebase_user_data['name']
            ));
            $updated = true;
        }
    }
    
    // Update phone if provided
    if (!empty($firebase_user_data['phone_number'])) {
        update_user_meta($user_id, 'firebase_phone', $firebase_user_data['phone_number']);
        update_user_meta($user_id, 'billing_phone', $firebase_user_data['phone_number']);
        $updated = true;
    }
    
    // Update email verification status
    if (isset($firebase_user_data['email_verified'])) {
        update_user_meta($user_id, 'firebase_email_verified', $firebase_user_data['email_verified'] ? '1' : '0');
        $updated = true;
    }
    
    // Update profile picture
    if (!empty($firebase_user_data['picture'])) {
        update_user_meta($user_id, 'firebase_profile_picture', $firebase_user_data['picture']);
        $updated = true;
    }
    
    // Update last login timestamp
    update_user_meta($user_id, 'firebase_last_login', current_time('mysql'));
    update_user_meta($user_id, 'firebase_auth_method', $firebase_user_data['auth_method']);
    
    return $updated;
}

/**
 * Check if user needs email update (for phone-only users)
 */
function tostishop_user_needs_email_update($user_id) {
    return get_user_meta($user_id, 'firebase_temporary_email', true) === '1';
}

/**
 * Update user email from temporary to real email
 */
function tostishop_update_user_email($user_id, $new_email) {
    if (empty($user_id) || empty($new_email) || !is_email($new_email)) {
        return false;
    }
    
    // Check if email is already in use
    if (email_exists($new_email)) {
        return new WP_Error('email_exists', 'Email already in use');
    }
    
    // Update WordPress user email
    $result = wp_update_user(array(
        'ID' => $user_id,
        'user_email' => $new_email
    ));
    
    if (is_wp_error($result)) {
        return $result;
    }
    
    // Remove temporary email flag
    delete_user_meta($user_id, 'firebase_temporary_email');
    
    // Update billing email
    update_user_meta($user_id, 'billing_email', $new_email);
    
    error_log('✅ Updated user email from temporary to real: ' . $user_id . ' -> ' . $new_email);
    
    return true;
}

/**
 * Get user's Firebase authentication statistics
 */
function tostishop_get_user_firebase_stats($user_id) {
    return array(
        'firebase_uid' => get_user_meta($user_id, 'firebase_uid', true),
        'auth_method' => get_user_meta($user_id, 'firebase_auth_method', true),
        'registration_date' => get_user_meta($user_id, 'firebase_registration_date', true),
        'last_login' => get_user_meta($user_id, 'firebase_last_login', true),
        'email_verified' => get_user_meta($user_id, 'firebase_email_verified', true) === '1',
        'phone' => get_user_meta($user_id, 'firebase_phone', true),
        'profile_picture' => get_user_meta($user_id, 'firebase_profile_picture', true),
        'has_temporary_email' => get_user_meta($user_id, 'firebase_temporary_email', true) === '1'
    );
}
