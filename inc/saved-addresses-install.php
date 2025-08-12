<?php
/**
 * TostiShop Saved Addresses Installation Script
 * Handles database table creation and initial setup
 * 
 * @package TostiShop
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Install Saved Addresses Feature
 * This function is called when the theme is activated
 */
function tostishop_install_saved_addresses() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'tostishop_saved_addresses';
    
    // Check if table already exists
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            address_type varchar(10) NOT NULL DEFAULT 'billing',
            address_name varchar(100) NOT NULL,
            first_name varchar(100) NOT NULL,
            last_name varchar(100) NOT NULL,
            company varchar(100) DEFAULT '',
            address_1 varchar(255) NOT NULL,
            address_2 varchar(255) DEFAULT '',
            city varchar(100) NOT NULL,
            state varchar(100) NOT NULL,
            postcode varchar(20) NOT NULL,
            country varchar(5) NOT NULL,
            phone varchar(20) DEFAULT '',
            email varchar(100) DEFAULT '',
            is_default tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY address_type (address_type),
            KEY is_default (is_default),
            KEY user_type (user_id, address_type)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        // Add option to track installation
        add_option('tostishop_saved_addresses_version', '1.0.0');
        add_option('tostishop_saved_addresses_installed', time());
    }
    
    // Add rewrite endpoints
    add_rewrite_endpoint('address-book', EP_ROOT | EP_PAGES);
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

/**
 * Uninstall Saved Addresses Feature
 * This function is called when the theme is deactivated (optional)
 */
function tostishop_uninstall_saved_addresses() {
    // Note: We don't drop the table on theme deactivation to preserve user data
    // Only remove rewrite rules
    flush_rewrite_rules();
}

/**
 * Upgrade Saved Addresses Feature
 * Handle database updates for new versions
 */
function tostishop_upgrade_saved_addresses() {
    $current_version = get_option('tostishop_saved_addresses_version', '0.0.0');
    
    if (version_compare($current_version, '1.0.0', '<')) {
        // Run upgrade to 1.0.0
        tostishop_upgrade_to_1_0_0();
        update_option('tostishop_saved_addresses_version', '1.0.0');
    }
    
    // Future version upgrades can be added here
}

/**
 * Upgrade to version 1.0.0
 */
function tostishop_upgrade_to_1_0_0() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'tostishop_saved_addresses';
    
    // Check if any columns are missing and add them
    $columns = $wpdb->get_col("DESCRIBE $table_name");
    
    if (!in_array('updated_at', $columns)) {
        $wpdb->query("ALTER TABLE $table_name ADD COLUMN updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }
    
    if (!in_array('created_at', $columns)) {
        $wpdb->query("ALTER TABLE $table_name ADD COLUMN created_at datetime DEFAULT CURRENT_TIMESTAMP");
    }
    
    // Add indexes if they don't exist
    $indexes = $wpdb->get_results("SHOW INDEX FROM $table_name");
    $index_names = array_column($indexes, 'Key_name');
    
    if (!in_array('user_type', $index_names)) {
        $wpdb->query("ALTER TABLE $table_name ADD INDEX user_type (user_id, address_type)");
    }
}

/**
 * Import existing WooCommerce addresses
 * This function can be used to import existing customer addresses
 */
function tostishop_import_existing_addresses() {
    global $wpdb;
    
    // Get all users with billing addresses
    $users_with_billing = $wpdb->get_results("
        SELECT DISTINCT user_id 
        FROM {$wpdb->usermeta} 
        WHERE meta_key = 'billing_first_name' 
        AND meta_value != ''
    ");
    
    foreach ($users_with_billing as $user_data) {
        $user_id = $user_data->user_id;
        
        // Check if user already has saved addresses
        $existing_addresses = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}tostishop_saved_addresses WHERE user_id = %d",
            $user_id
        ));
        
        if ($existing_addresses > 0) {
            continue; // Skip if user already has saved addresses
        }
        
        // Get billing address
        $billing_data = array(
            'first_name' => get_user_meta($user_id, 'billing_first_name', true),
            'last_name' => get_user_meta($user_id, 'billing_last_name', true),
            'company' => get_user_meta($user_id, 'billing_company', true),
            'address_1' => get_user_meta($user_id, 'billing_address_1', true),
            'address_2' => get_user_meta($user_id, 'billing_address_2', true),
            'city' => get_user_meta($user_id, 'billing_city', true),
            'state' => get_user_meta($user_id, 'billing_state', true),
            'postcode' => get_user_meta($user_id, 'billing_postcode', true),
            'country' => get_user_meta($user_id, 'billing_country', true),
            'phone' => get_user_meta($user_id, 'billing_phone', true),
            'email' => get_user_meta($user_id, 'billing_email', true),
        );
        
        // Only import if we have required fields
        if (!empty($billing_data['first_name']) && !empty($billing_data['address_1'])) {
            $wpdb->insert(
                $wpdb->prefix . 'tostishop_saved_addresses',
                array_merge($billing_data, array(
                    'user_id' => $user_id,
                    'address_type' => 'billing',
                    'address_name' => 'Default Billing',
                    'is_default' => 1
                ))
            );
        }
        
        // Get shipping address if different
        $shipping_data = array(
            'first_name' => get_user_meta($user_id, 'shipping_first_name', true),
            'last_name' => get_user_meta($user_id, 'shipping_last_name', true),
            'company' => get_user_meta($user_id, 'shipping_company', true),
            'address_1' => get_user_meta($user_id, 'shipping_address_1', true),
            'address_2' => get_user_meta($user_id, 'shipping_address_2', true),
            'city' => get_user_meta($user_id, 'shipping_city', true),
            'state' => get_user_meta($user_id, 'shipping_state', true),
            'postcode' => get_user_meta($user_id, 'shipping_postcode', true),
            'country' => get_user_meta($user_id, 'shipping_country', true),
        );
        
        // Check if shipping is different from billing
        $is_different = false;
        foreach (['first_name', 'last_name', 'address_1', 'city', 'postcode', 'country'] as $field) {
            if (!empty($shipping_data[$field]) && $shipping_data[$field] !== $billing_data[$field]) {
                $is_different = true;
                break;
            }
        }
        
        if ($is_different && !empty($shipping_data['first_name']) && !empty($shipping_data['address_1'])) {
            $wpdb->insert(
                $wpdb->prefix . 'tostishop_saved_addresses',
                array_merge($shipping_data, array(
                    'user_id' => $user_id,
                    'address_type' => 'shipping',
                    'address_name' => 'Default Shipping',
                    'is_default' => 1,
                    'email' => '', // Shipping doesn't need email
                    'phone' => ''  // Shipping doesn't need phone
                ))
            );
        }
    }
}

/**
 * Get installation statistics
 */
function tostishop_get_saved_addresses_stats() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'tostishop_saved_addresses';
    
    $stats = array(
        'total_addresses' => 0,
        'total_users' => 0,
        'billing_addresses' => 0,
        'shipping_addresses' => 0,
        'default_addresses' => 0
    );
    
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $stats['total_addresses'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        $stats['total_users'] = $wpdb->get_var("SELECT COUNT(DISTINCT user_id) FROM $table_name");
        $stats['billing_addresses'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE address_type = 'billing'");
        $stats['shipping_addresses'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE address_type = 'shipping'");
        $stats['default_addresses'] = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE is_default = 1");
    }
    
    return $stats;
}
