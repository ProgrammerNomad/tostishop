<?php
/**
 * Firebase Authentication AJAX Handlers
 * TostiShop Theme - WordPress Integration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX handler for Firebase login
 */
function tostishop_handle_firebase_login() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_firebase_nonce')) {
        wp_send_json_error(array(
            'message' => 'Security check failed'
        ));
    }
    
    $firebase_token = sanitize_text_field($_POST['firebase_token']);
    
    if (empty($firebase_token)) {
        wp_send_json_error(array(
            'message' => 'Invalid Firebase token provided'
        ));
    }
    
    try {
        // For production, you should verify the token with Firebase Admin SDK
        // For now, we'll decode the token and extract user info
        
        // Split the JWT token to get payload
        $token_parts = explode('.', $firebase_token);
        if (count($token_parts) !== 3) {
            throw new Exception('Invalid token format');
        }
        
        // Decode the payload (base64url decode)
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $token_parts[1])), true);
        
        if (!$payload) {
            throw new Exception('Invalid token payload');
        }
        
        $firebase_uid = sanitize_text_field($payload['user_id'] ?? $payload['sub']);
        $email = sanitize_email($payload['email'] ?? '');
        $display_name = sanitize_text_field($payload['name'] ?? '');
        $phone_number = sanitize_text_field($payload['phone_number'] ?? '');
        
        // Check if user exists by email or Firebase UID
        $existing_user = null;
        
        if ($email) {
            $existing_user = get_user_by('email', $email);
        }
        
        if (!$existing_user) {
            // Check by Firebase UID in user meta
            $users = get_users(array(
                'meta_key' => 'firebase_uid',
                'meta_value' => $firebase_uid,
                'number' => 1
            ));
            
            if (!empty($users)) {
                $existing_user = $users[0];
            }
        }
        
        if ($existing_user) {
            // User exists, update Firebase UID if needed
            update_user_meta($existing_user->ID, 'firebase_uid', $firebase_uid);
            
            // Update display name if provided and current is empty
            if ($display_name && empty($existing_user->display_name)) {
                wp_update_user(array(
                    'ID' => $existing_user->ID,
                    'display_name' => $display_name
                ));
            }
            
            // Update phone number if provided
            if ($phone_number) {
                update_user_meta($existing_user->ID, 'billing_phone', $phone_number);
            }
            
            $user_id = $existing_user->ID;
            
        } else {
            // Create new user
            $username = $email ? sanitize_user(explode('@', $email)[0]) : 'user_' . $firebase_uid;
            
            // Ensure username is unique
            $original_username = $username;
            $counter = 1;
            while (username_exists($username)) {
                $username = $original_username . '_' . $counter;
                $counter++;
            }
            
            $user_data_array = array(
                'user_login' => $username,
                'user_email' => $email,
                'display_name' => $display_name ?: $username,
                'user_pass' => wp_generate_password(12, false),
                'role' => 'customer'
            );
            
            $user_id = wp_insert_user($user_data_array);
            
            if (is_wp_error($user_id)) {
                wp_send_json_error(array(
                    'message' => 'Failed to create user account'
                ));
            }
            
            // Store Firebase UID
            update_user_meta($user_id, 'firebase_uid', $firebase_uid);
            
            // Store phone number if provided
            if ($phone_number) {
                update_user_meta($user_id, 'billing_phone', $phone_number);
            }
            
            // Store Firebase login method
            if ($phone_number) {
                update_user_meta($user_id, 'firebase_login_method', 'phone');
            } elseif (strpos($email, '@gmail.com') !== false) {
                update_user_meta($user_id, 'firebase_login_method', 'google');
            } else {
                update_user_meta($user_id, 'firebase_login_method', 'email');
            }
        }
        
        // Log the user in
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id, true);
        
        // Store Firebase ID token for future use
        update_user_meta($user_id, 'firebase_id_token', $firebase_token);
        update_user_meta($user_id, 'firebase_last_login', current_time('mysql'));
        
        // Determine redirect URL
        $redirect_url = home_url('/my-account/');
        
        // If came from checkout, redirect to checkout
        if (isset($_POST['from_checkout']) && $_POST['from_checkout'] === 'true') {
            $redirect_url = wc_get_checkout_url();
        }
        
        // If there's a cart, redirect to cart
        if (WC()->cart && !WC()->cart->is_empty()) {
            $redirect_url = wc_get_cart_url();
        }
        
        wp_send_json_success(array(
            'message' => 'Login successful',
            'user_id' => $user_id,
            'redirect_url' => $redirect_url
        ));
        
    } catch (Exception $e) {
        wp_send_json_error(array(
            'message' => 'Authentication failed: ' . $e->getMessage()
        ));
    }
}

// Hook for both logged-in and non-logged-in users
add_action('wp_ajax_tostishop_firebase_login', 'tostishop_handle_firebase_login');
add_action('wp_ajax_nopriv_tostishop_firebase_login', 'tostishop_handle_firebase_login');

/**
 * AJAX handler for Firebase logout
 */
function tostishop_handle_firebase_logout() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_firebase_nonce')) {
        wp_send_json_error(array(
            'message' => 'Security check failed'
        ));
    }
    
    // Log out the user
    wp_logout();
    
    wp_send_json_success(array(
        'message' => 'Logout successful',
        'redirect_url' => home_url()
    ));
}

add_action('wp_ajax_tostishop_firebase_logout', 'tostishop_handle_firebase_logout');

/**
 * Add Firebase user data to WooCommerce customer
 */
function tostishop_firebase_update_customer_data($user_id) {
    if (!$user_id) {
        return;
    }
    
    $firebase_uid = get_user_meta($user_id, 'firebase_uid', true);
    $phone_number = get_user_meta($user_id, 'billing_phone', true);
    
    if ($phone_number && function_exists('WC')) {
        // Update WooCommerce customer data
        $customer = new WC_Customer($user_id);
        
        if ($customer) {
            $customer->set_billing_phone($phone_number);
            $customer->save();
        }
    }
}

add_action('user_register', 'tostishop_firebase_update_customer_data');
add_action('profile_update', 'tostishop_firebase_update_customer_data');

/**
 * Add Firebase login method to user profile
 */
function tostishop_firebase_user_profile_fields($user) {
    $firebase_uid = get_user_meta($user->ID, 'firebase_uid', true);
    $login_method = get_user_meta($user->ID, 'firebase_login_method', true);
    $last_login = get_user_meta($user->ID, 'firebase_last_login', true);
    
    if ($firebase_uid) {
        ?>
        <h3><?php _e('Firebase Authentication', 'tostishop'); ?></h3>
        <table class="form-table">
            <tr>
                <th><label><?php _e('Firebase UID', 'tostishop'); ?></label></th>
                <td><code><?php echo esc_html($firebase_uid); ?></code></td>
            </tr>
            <?php if ($login_method) : ?>
            <tr>
                <th><label><?php _e('Login Method', 'tostishop'); ?></label></th>
                <td>
                    <?php 
                    $methods = array(
                        'phone' => __('Phone Number', 'tostishop'),
                        'google' => __('Google Account', 'tostishop'),
                        'email' => __('Email & Password', 'tostishop')
                    );
                    echo isset($methods[$login_method]) ? $methods[$login_method] : $login_method;
                    ?>
                </td>
            </tr>
            <?php endif; ?>
            <?php if ($last_login) : ?>
            <tr>
                <th><label><?php _e('Last Firebase Login', 'tostishop'); ?></label></th>
                <td><?php echo esc_html(date('Y-m-d H:i:s', strtotime($last_login))); ?></td>
            </tr>
            <?php endif; ?>
        </table>
        <?php
    }
}

add_action('show_user_profile', 'tostishop_firebase_user_profile_fields');
add_action('edit_user_profile', 'tostishop_firebase_user_profile_fields');
