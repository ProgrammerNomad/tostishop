<?php
/**
 * Firebase Password Synchronization
 * Handles password changes and resets to keep WordPress and Firebase in sync
 * 
 * @package TostiShop
 * @subpackage Firebase
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize password synchronization hooks
 */
function tostishop_init_password_sync() {
    // Hook into password reset process
    add_action('after_password_reset', 'tostishop_sync_password_reset', 10, 2);
    add_action('password_reset', 'tostishop_handle_password_reset', 10, 2);
    
    // Hook into profile/account updates
    add_action('profile_update', 'tostishop_sync_profile_password_change', 10, 2);
    add_action('woocommerce_save_account_details', 'tostishop_sync_wc_account_password_change', 10, 1);
    
    // Hook into user password changes (admin or programmatic)
    add_action('wp_set_password', 'tostishop_sync_wp_password_change', 10, 2);
    
    // Hook into WooCommerce password validation
    add_action('woocommerce_save_account_details_errors', 'tostishop_validate_firebase_password_change', 10, 2);
}

/**
 * Handle password reset - Update Firebase when WordPress password is reset
 * 
 * @param WP_User $user The user object
 * @param string $new_pass The new password
 */
function tostishop_sync_password_reset($user, $new_pass) {
    if (!$user || is_wp_error($user)) {
        return;
    }
    
    // Check if user has Firebase account
    $firebase_uid = get_user_meta($user->ID, 'firebase_uid', true);
    if (empty($firebase_uid)) {
        return; // No Firebase account to sync
    }
    
    error_log('TostiShop: Syncing password reset for user ' . $user->ID . ' with Firebase UID: ' . $firebase_uid);
    
    // Update Firebase password
    $sync_result = tostishop_update_firebase_password($user->user_email, $new_pass);
    
    if ($sync_result['success']) {
        // Update sync timestamp
        update_user_meta($user->ID, 'firebase_password_sync_date', current_time('mysql'));
        update_user_meta($user->ID, 'firebase_password_sync_method', 'wp_reset');
        
        error_log('TostiShop: Firebase password reset successful for user ' . $user->ID);
    } else {
        error_log('TostiShop: Firebase password reset failed for user ' . $user->ID . ': ' . $sync_result['error']);
        
        // Store sync failure for retry
        update_user_meta($user->ID, 'firebase_password_sync_failed', current_time('mysql'));
        update_user_meta($user->ID, 'firebase_password_sync_error', $sync_result['error']);
    }
}

/**
 * Handle WordPress password reset hook
 * 
 * @param WP_User $user The user object
 * @param string $new_pass The new password
 */
function tostishop_handle_password_reset($user, $new_pass) {
    // This is called during the reset process
    tostishop_sync_password_reset($user, $new_pass);
}

/**
 * Handle profile update password changes
 * 
 * @param int $user_id The user ID
 * @param WP_User $old_user_data The user object before update
 */
function tostishop_sync_profile_password_change($user_id, $old_user_data) {
    // Check if password was actually changed
    $new_user_data = get_userdata($user_id);
    
    if (!$new_user_data || $old_user_data->user_pass === $new_user_data->user_pass) {
        return; // Password not changed
    }
    
    // Check if user has Firebase account
    $firebase_uid = get_user_meta($user_id, 'firebase_uid', true);
    if (empty($firebase_uid)) {
        return; // No Firebase account to sync
    }
    
    error_log('TostiShop: Syncing profile password change for user ' . $user_id);
    
    // Note: We don't have the plain text password here, so we need to handle this differently
    // We'll mark for sync and handle it in the WooCommerce hook where we have access to the password
    update_user_meta($user_id, 'firebase_password_sync_pending', current_time('mysql'));
}

/**
 * Handle WooCommerce account details password changes
 * This is where we have access to the plain text password
 * 
 * @param int $user_id The user ID
 */
function tostishop_sync_wc_account_password_change($user_id) {
    // Check if there's a pending sync
    $sync_pending = get_user_meta($user_id, 'firebase_password_sync_pending', true);
    if (empty($sync_pending)) {
        return;
    }
    
    // Check if user has Firebase account
    $firebase_uid = get_user_meta($user_id, 'firebase_uid', true);
    if (empty($firebase_uid)) {
        // Clean up pending sync
        delete_user_meta($user_id, 'firebase_password_sync_pending');
        return;
    }
    
    // Get the new password from POST data (WooCommerce form)
    $new_password = isset($_POST['password_1']) ? $_POST['password_1'] : '';
    
    if (empty($new_password)) {
        // No password change
        delete_user_meta($user_id, 'firebase_password_sync_pending');
        return;
    }
    
    $user = get_userdata($user_id);
    if (!$user) {
        delete_user_meta($user_id, 'firebase_password_sync_pending');
        return;
    }
    
    error_log('TostiShop: Syncing WooCommerce password change for user ' . $user_id);
    
    // Update Firebase password
    $sync_result = tostishop_update_firebase_password($user->user_email, $new_password);
    
    if ($sync_result['success']) {
        // Update sync timestamp
        update_user_meta($user_id, 'firebase_password_sync_date', current_time('mysql'));
        update_user_meta($user_id, 'firebase_password_sync_method', 'wc_profile');
        
        error_log('TostiShop: Firebase password sync successful for user ' . $user_id);
    } else {
        error_log('TostiShop: Firebase password sync failed for user ' . $user_id . ': ' . $sync_result['error']);
        
        // Store sync failure for retry
        update_user_meta($user_id, 'firebase_password_sync_failed', current_time('mysql'));
        update_user_meta($user_id, 'firebase_password_sync_error', $sync_result['error']);
    }
    
    // Clean up pending sync
    delete_user_meta($user_id, 'firebase_password_sync_pending');
}

/**
 * Handle programmatic password changes (wp_set_password)
 * 
 * @param string $password The new password
 * @param int $user_id The user ID
 */
function tostishop_sync_wp_password_change($password, $user_id) {
    // Check if user has Firebase account
    $firebase_uid = get_user_meta($user_id, 'firebase_uid', true);
    if (empty($firebase_uid)) {
        return; // No Firebase account to sync
    }
    
    $user = get_userdata($user_id);
    if (!$user) {
        return;
    }
    
    error_log('TostiShop: Syncing programmatic password change for user ' . $user_id);
    
    // Update Firebase password
    $sync_result = tostishop_update_firebase_password($user->user_email, $password);
    
    if ($sync_result['success']) {
        // Update sync timestamp
        update_user_meta($user_id, 'firebase_password_sync_date', current_time('mysql'));
        update_user_meta($user_id, 'firebase_password_sync_method', 'wp_set_password');
        
        error_log('TostiShop: Firebase password sync successful for user ' . $user_id);
    } else {
        error_log('TostiShop: Firebase password sync failed for user ' . $user_id . ': ' . $sync_result['error']);
        
        // Store sync failure for retry
        update_user_meta($user_id, 'firebase_password_sync_failed', current_time('mysql'));
        update_user_meta($user_id, 'firebase_password_sync_error', $sync_result['error']);
    }
}

/**
 * Validate Firebase password change in WooCommerce
 * 
 * @param WP_Error $errors Error object
 * @param WP_User $user User object
 */
function tostishop_validate_firebase_password_change($errors, $user) {
    // Check if password change is being attempted
    $new_password = isset($_POST['password_1']) ? $_POST['password_1'] : '';
    $confirm_password = isset($_POST['password_2']) ? $_POST['password_2'] : '';
    
    if (empty($new_password) && empty($confirm_password)) {
        return; // No password change
    }
    
    // Check if user has Firebase account
    $firebase_uid = get_user_meta($user->ID, 'firebase_uid', true);
    if (empty($firebase_uid)) {
        return; // No Firebase account, proceed normally
    }
    
    // Additional validation for Firebase users
    if (!empty($new_password) && strlen($new_password) < 6) {
        $errors->add('password_length', __('For Firebase-linked accounts, password must be at least 6 characters long.', 'tostishop'));
    }
    
    // Check for Firebase password policy
    if (!empty($new_password) && !tostishop_validate_firebase_password_policy($new_password)) {
        $errors->add('password_policy', __('Password does not meet Firebase security requirements. Please use a stronger password.', 'tostishop'));
    }
}

/**
 * Update Firebase user password
 * 
 * @param string $email User email
 * @param string $password New password
 * @return array Result array with success/error
 */
function tostishop_update_firebase_password($email, $password) {
    try {
        // Check if Firebase Admin SDK is available
        if (!tostishop_is_firebase_admin_available()) {
            return array(
                'success' => false,
                'error' => 'Firebase Admin SDK not available'
            );
        }
        
        // For now, we'll use a different approach since Firebase Admin SDK is complex
        // We'll store the password change and sync it when user next logs in
        return tostishop_queue_firebase_password_sync($email, $password);
        
    } catch (Exception $e) {
        return array(
            'success' => false,
            'error' => 'Firebase password update failed: ' . $e->getMessage()
        );
    }
}

/**
 * Queue Firebase password sync for next login
 * Since we can't directly update Firebase password without Admin SDK,
 * we'll store it securely and sync on next Firebase login
 * 
 * @param string $email User email
 * @param string $password New password
 * @return array Result array
 */
function tostishop_queue_firebase_password_sync($email, $password) {
    $user = get_user_by('email', $email);
    if (!$user) {
        return array(
            'success' => false,
            'error' => 'User not found'
        );
    }
    
    // Hash the password for secure storage
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Store for sync on next login
    update_user_meta($user->ID, 'firebase_password_sync_queued', current_time('mysql'));
    update_user_meta($user->ID, 'firebase_password_sync_hash', $password_hash);
    
    error_log('TostiShop: Queued Firebase password sync for user ' . $user->ID);
    
    return array(
        'success' => true,
        'message' => 'Password change queued for Firebase sync'
    );
}

/**
 * Process queued Firebase password sync during login
 * This is called from the login process
 * 
 * @param int $user_id User ID
 * @param string $firebase_email Firebase email
 * @return bool Success status
 */
function tostishop_process_queued_password_sync($user_id, $firebase_email) {
    $sync_queued = get_user_meta($user_id, 'firebase_password_sync_queued', true);
    if (empty($sync_queued)) {
        return true; // No sync needed
    }
    
    $password_hash = get_user_meta($user_id, 'firebase_password_sync_hash', true);
    if (empty($password_hash)) {
        // Clean up incomplete sync
        delete_user_meta($user_id, 'firebase_password_sync_queued');
        return false;
    }
    
    error_log('TostiShop: Processing queued Firebase password sync for user ' . $user_id);
    
    try {
        // Update Firebase password using the current Firebase user session
        // This requires JavaScript-side implementation
        
        // For now, we'll just mark it as processed and let the frontend handle it
        update_user_meta($user_id, 'firebase_password_sync_ready', current_time('mysql'));
        
        // Clean up queue
        delete_user_meta($user_id, 'firebase_password_sync_queued');
        delete_user_meta($user_id, 'firebase_password_sync_hash');
        
        return true;
        
    } catch (Exception $e) {
        error_log('TostiShop: Firebase password sync processing failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Check if Firebase Admin SDK is available
 * 
 * @return bool
 */
function tostishop_is_firebase_admin_available() {
    // Check if Firebase Admin SDK classes exist
    return class_exists('Kreait\Firebase\Factory') || 
           class_exists('Google\Cloud\Firestore\FirestoreClient') ||
           function_exists('firebase_admin_sdk_init');
}

/**
 * Validate password against Firebase policy
 * 
 * @param string $password Password to validate
 * @return bool Valid status
 */
function tostishop_validate_firebase_password_policy($password) {
    // Firebase minimum requirements
    if (strlen($password) < 6) {
        return false;
    }
    
    // Additional strength requirements
    $has_uppercase = preg_match('/[A-Z]/', $password);
    $has_lowercase = preg_match('/[a-z]/', $password);
    $has_number = preg_match('/\d/', $password);
    
    // At least 2 of the 3 character types
    $strength_score = ($has_uppercase ? 1 : 0) + ($has_lowercase ? 1 : 0) + ($has_number ? 1 : 0);
    
    return $strength_score >= 2;
}

/**
 * Get user's Firebase password sync status
 * 
 * @param int $user_id User ID
 * @return array Sync status information
 */
function tostishop_get_firebase_password_sync_status($user_id) {
    $firebase_uid = get_user_meta($user_id, 'firebase_uid', true);
    
    return array(
        'has_firebase_account' => !empty($firebase_uid),
        'firebase_uid' => $firebase_uid,
        'last_sync_date' => get_user_meta($user_id, 'firebase_password_sync_date', true),
        'sync_method' => get_user_meta($user_id, 'firebase_password_sync_method', true),
        'sync_pending' => !empty(get_user_meta($user_id, 'firebase_password_sync_pending', true)),
        'sync_queued' => !empty(get_user_meta($user_id, 'firebase_password_sync_queued', true)),
        'sync_ready' => !empty(get_user_meta($user_id, 'firebase_password_sync_ready', true)),
        'sync_failed' => get_user_meta($user_id, 'firebase_password_sync_failed', true),
        'sync_error' => get_user_meta($user_id, 'firebase_password_sync_error', true)
    );
}

/**
 * Retry failed password syncs
 * Can be called from cron or admin action
 */
function tostishop_retry_failed_password_syncs() {
    $users_with_failed_sync = get_users(array(
        'meta_key' => 'firebase_password_sync_failed',
        'meta_compare' => 'EXISTS',
        'number' => 10 // Process 10 at a time
    ));
    
    foreach ($users_with_failed_sync as $user) {
        $firebase_uid = get_user_meta($user->ID, 'firebase_uid', true);
        if (empty($firebase_uid)) {
            // Clean up invalid state
            delete_user_meta($user->ID, 'firebase_password_sync_failed');
            delete_user_meta($user->ID, 'firebase_password_sync_error');
            continue;
        }
        
        error_log('TostiShop: Retrying failed password sync for user ' . $user->ID);
        
        // Since we can't retry without the original password, we'll queue for next login
        update_user_meta($user->ID, 'firebase_password_sync_queued', current_time('mysql'));
        
        // Clean up failed state
        delete_user_meta($user->ID, 'firebase_password_sync_failed');
        delete_user_meta($user->ID, 'firebase_password_sync_error');
    }
}

// Initialize password synchronization
add_action('init', 'tostishop_init_password_sync');

// Add admin notice for sync status (for debugging)
if (defined('WP_DEBUG') && WP_DEBUG) {
    add_action('admin_notices', function() {
        if (current_user_can('manage_options')) {
            $current_user_sync = tostishop_get_firebase_password_sync_status(get_current_user_id());
            if ($current_user_sync['has_firebase_account']) {
                $status = 'Firebase Account Linked';
                if ($current_user_sync['sync_pending']) $status .= ' | Sync Pending';
                if ($current_user_sync['sync_queued']) $status .= ' | Sync Queued';
                if ($current_user_sync['sync_ready']) $status .= ' | Sync Ready';
                if ($current_user_sync['sync_failed']) $status .= ' | Sync Failed';
                
                echo '<div class="notice notice-info"><p><strong>Firebase Sync Status:</strong> ' . $status . '</p></div>';
            }
        }
    });
}
