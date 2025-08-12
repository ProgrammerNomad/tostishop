<?php
/**
 * Address Management Helper Functions
 * Safe testing and management functions for saved addresses
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get user's saved addresses count for display
 */
function tostishop_get_address_count($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    if (!$user_id) {
        return array('billing' => 0, 'shipping' => 0);
    }
    
    $saved_addresses = get_user_meta($user_id, '_saved_addresses', true);
    if (!is_array($saved_addresses)) {
        return array('billing' => 0, 'shipping' => 0);
    }
    
    $billing_count = count(array_filter($saved_addresses, function($addr) {
        return $addr['type'] === 'billing';
    }));
    
    $shipping_count = count(array_filter($saved_addresses, function($addr) {
        return $addr['type'] === 'shipping';
    }));
    
    return array('billing' => $billing_count, 'shipping' => $shipping_count);
}

/**
 * Display address management info in admin (safe for production)
 */
function tostishop_address_management_info() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Only show on specific admin pages
    $screen = get_current_screen();
    if (!$screen || !in_array($screen->id, array('users', 'profile', 'user-edit'))) {
        return;
    }
    
    ?>
    <div class="notice notice-info">
        <h3>📍 Amazon-Style Address Picker</h3>
        <p><strong>Status:</strong> ✅ Active and ready for use</p>
        <p><strong>Location:</strong> WooCommerce Checkout Page (for logged-in users)</p>
        <p><strong>How to test:</strong></p>
        <ol>
            <li>Log in as a customer</li>
            <li>Go to WooCommerce checkout</li>
            <li>Click "Add New Address" to create your first address</li>
            <li>Save the address and it will appear as a selectable card</li>
        </ol>
        <p><em>Addresses are stored safely in user meta as '_saved_addresses'</em></p>
    </div>
    <?php
}

/**
 * Clear saved addresses for current user only (safe admin function)
 */
function tostishop_clear_my_addresses() {
    if (!is_user_logged_in()) {
        return false;
    }
    
    $user_id = get_current_user_id();
    return delete_user_meta($user_id, '_saved_addresses');
}

/**
 * AJAX handler to clear only current user's addresses
 */
function tostishop_ajax_clear_my_addresses() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_clear_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
        return;
    }
    
    $result = tostishop_clear_my_addresses();
    
    if ($result) {
        wp_send_json_success('Your saved addresses have been cleared');
    } else {
        wp_send_json_error('No addresses to clear or operation failed');
    }
}

add_action('wp_ajax_tostishop_clear_my_addresses', 'tostishop_ajax_clear_my_addresses');

/**
 * Add user-specific address management to user profile
 */
function tostishop_user_address_management($user) {
    if (!current_user_can('edit_user', $user->ID)) {
        return;
    }
    
    $address_count = tostishop_get_address_count($user->ID);
    ?>
    <h3>Saved Addresses</h3>
    <table class="form-table">
        <tr>
            <th><label>Address Count</label></th>
            <td>
                <p>Billing Addresses: <strong><?php echo $address_count['billing']; ?></strong></p>
                <p>Shipping Addresses: <strong><?php echo $address_count['shipping']; ?></strong></p>
                <?php if ($address_count['billing'] > 0 || $address_count['shipping'] > 0): ?>
                    <p><em>User can manage addresses on the checkout page</em></p>
                <?php else: ?>
                    <p><em>User hasn't saved any addresses yet</em></p>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <?php
}

add_action('show_user_profile', 'tostishop_user_address_management');
add_action('edit_user_profile', 'tostishop_user_address_management');

// Only enable info display in development mode or for admins
if (defined('TOSTISHOP_DEV_MODE') && TOSTISHOP_DEV_MODE) {
    add_action('admin_notices', 'tostishop_address_management_info');
}
