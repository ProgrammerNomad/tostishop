<?php
// filepath: c:\xampp\htdocs\tostishop\wp-content\themes\tostishop\inc\tosti-admin-menu.php

/**
 * TostiShop Admin Menu
 * Creates a dedicated "Tosti Theme" admin menu with submenu items
 * 
 * @package TostiShop
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the main Tosti Theme admin menu and its submenus
 */
function tostishop_admin_menu() {
    // Add main menu
    add_menu_page(
        'TostiShop Theme',     // Page title
        'Tosti Theme',         // Menu title
        'manage_options',      // Capability
        'tostishop-theme',     // Menu slug
        'tostishop_dashboard_page', // Function to display the dashboard
        'dashicons-store',     // Icon (store icon fits ecommerce theme)
        59                     // Position (after Appearance)
    );
    
    // Add Dashboard submenu (same as parent)
    add_submenu_page(
        'tostishop-theme',     // Parent slug
        'Dashboard',           // Page title
        'Dashboard',           // Menu title
        'manage_options',      // Capability
        'tostishop-theme',     // Menu slug (same as parent)
        'tostishop_dashboard_page' // Function
    );
    
    // Add Firebase Auth submenu
    add_submenu_page(
        'tostishop-theme',     // Parent slug
        'Firebase Settings',   // Page title
        'Firebase Auth',       // Menu title
        'manage_options',      // Capability
        'tostishop-firebase',  // Menu slug
        'tostishop_firebase_settings_page' // Function
    );
    
    // Add Shiprocket submenu
    add_submenu_page(
        'tostishop-theme',     // Parent slug
        'Shiprocket Settings', // Page title
        'Shiprocket',          // Menu title
        'manage_options',      // Capability
        'tostishop-shiprocket', // Menu slug
        'tostishop_shiprocket_settings_page' // Function
    );
    
    // Remove the old Firebase menu from Appearance
    remove_submenu_page('themes.php', 'tostishop-firebase');
}
add_action('admin_menu', 'tostishop_admin_menu');

/**
 * TostiShop Dashboard Page
 */
function tostishop_dashboard_page() {
    ?>
    <div class="wrap">
        <div class="tostishop-admin-header">
            <h1>TostiShop Theme Dashboard</h1>
            <p class="about-description">Welcome to TostiShop Theme - India's favorite beauty & personal care theme.</p>
        </div>
        
        <div class="tostishop-admin-cards">
            <div class="tostishop-card">
                <h2><span class="dashicons dashicons-admin-appearance"></span> Theme Settings</h2>
                <p>Manage your theme settings, colors, and layout options.</p>
                <div class="tostishop-card-actions">
                    <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary">Customize Theme</a>
                    <a href="<?php echo admin_url('admin.php?page=tostishop-firebase'); ?>" class="button">Firebase Auth</a>
                    <a href="<?php echo admin_url('admin.php?page=tostishop-shiprocket'); ?>" class="button">Shiprocket</a>
                </div>
            </div>
            
            <div class="tostishop-card">
                <h2><span class="dashicons dashicons-cart"></span> Shop Setup</h2>
                <p>Configure your WooCommerce store settings and product displays.</p>
                <div class="tostishop-card-actions">
                    <a href="<?php echo admin_url('admin.php?page=wc-admin'); ?>" class="button button-primary">WooCommerce Dashboard</a>
                    <a href="<?php echo admin_url('admin.php?page=wc-settings'); ?>" class="button">Store Settings</a>
                </div>
            </div>
            
            <div class="tostishop-card">
                <h2><span class="dashicons dashicons-info"></span> Theme Information</h2>
                <p>TostiShop Version: <?php echo wp_get_theme()->get('Version'); ?></p>
                <p>WooCommerce Version: <?php echo class_exists('WooCommerce') ? WC()->version : 'Not Active'; ?></p>
                <p>Firebase Auth: <?php echo get_option('tostishop_firebase_api_key') ? '<span class="tostishop-status active">Configured</span>' : '<span class="tostishop-status inactive">Not Configured</span>'; ?></p>
                <p>Shiprocket: <?php echo get_option('tostishop_shiprocket_token') ? '<span class="tostishop-status active">Connected</span>' : '<span class="tostishop-status inactive">Not Connected</span>'; ?></p>
            </div>
        </div>
        
        <div class="tostishop-admin-footer">
            <h3>Need help with TostiShop?</h3>
            <div class="tostishop-help-links">
                <a href="https://tostishop.com/docs" target="_blank">
                    <span class="dashicons dashicons-book"></span> Documentation
                </a>
                <a href="mailto:support@tostishop.com">
                    <span class="dashicons dashicons-email"></span> Email Support
                </a>
                <a href="https://wa.me/917982999145" target="_blank">
                    <span class="dashicons dashicons-whatsapp"></span> WhatsApp (Chat only)
                </a>
            </div>
        </div>
    </div>
    
    <style>
        .tostishop-admin-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .tostishop-admin-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .tostishop-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .tostishop-card h2 {
            display: flex;
            align-items: center;
            margin-top: 0;
            color: #14175b;
        }
        
        .tostishop-card h2 .dashicons {
            margin-right: 8px;
            color: #14175b;
        }
        
        .tostishop-card-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .tostishop-status {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .tostishop-status.active {
            background: #d1fae5;
            color: #065f46;
        }
        
        .tostishop-status.inactive {
            background: #fee2e2;
            color: #b91c1c;
        }
        
        .tostishop-admin-footer {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .tostishop-help-links {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .tostishop-help-links a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #14175b;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 4px;
            background: #f8fafc;
            transition: all 0.2s ease;
        }
        
        .tostishop-help-links a:hover {
            background: #f1f5f9;
            color: #e42029;
        }
        
        .tostishop-help-links .dashicons {
            margin-right: 6px;
        }
    </style>
    <?php
}

/**
 * Firebase Settings Page
 * This can remain the same as your current implementation
 * Just ensure it uses the new menu system
 */
function tostishop_firebase_settings_page() {
    // This function should be moved from your existing firebase/init.php
    // Include your existing Firebase settings page content here
    if (function_exists('tostishop_firebase_admin_page')) {
        tostishop_firebase_admin_page();
    } else {
        // Fallback if the function doesn't exist yet
        ?>
        <div class="wrap">
            <h1>Firebase Authentication Settings</h1>
            <p>Configure your Firebase settings for TostiShop.</p>
            
            <form method="post" action="">
                <?php
                // Get current Firebase settings
                $api_key = get_option('tostishop_firebase_api_key', '');
                $project_id = get_option('tostishop_firebase_project_id', '');
                $auth_domain = get_option('tostishop_firebase_auth_domain', '');
                $storage_bucket = get_option('tostishop_firebase_storage_bucket', '');
                $messaging_sender_id = get_option('tostishop_firebase_messaging_sender_id', '');
                $app_id = get_option('tostishop_firebase_app_id', '');
                ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">API Key</th>
                        <td>
                            <input type="text" name="firebase_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text" required />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Project ID</th>
                        <td>
                            <input type="text" name="firebase_project_id" value="<?php echo esc_attr($project_id); ?>" class="regular-text" required />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Auth Domain</th>
                        <td>
                            <input type="text" name="firebase_auth_domain" value="<?php echo esc_attr($auth_domain); ?>" class="regular-text" required />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Storage Bucket</th>
                        <td>
                            <input type="text" name="firebase_storage_bucket" value="<?php echo esc_attr($storage_bucket); ?>" class="regular-text" required />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Messaging Sender ID</th>
                        <td>
                            <input type="text" name="firebase_messaging_sender_id" value="<?php echo esc_attr($messaging_sender_id); ?>" class="regular-text" required />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">App ID</th>
                        <td>
                            <input type="text" name="firebase_app_id" value="<?php echo esc_attr($app_id); ?>" class="regular-text" required />
                        </td>
                    </tr>
                </table>
                
                <?php submit_button('Save Firebase Settings'); ?>
            </form>
        </div>
        <?php
    }
}

/**
 * Shiprocket Settings Page
 */
function tostishop_shiprocket_settings_page() {
    // Handle form submission
    if (isset($_POST['save_shiprocket_settings'])) {
        // Verify nonce
        if (!wp_verify_nonce($_POST['shiprocket_nonce'], 'save_shiprocket_settings')) {
            wp_die('Security check failed');
        }
        
        $email = sanitize_email($_POST['shiprocket_email']);
        $password = sanitize_text_field($_POST['shiprocket_password']);
        $show_pincode_check = isset($_POST['show_pincode_check']) ? 'yes' : 'no';
        $show_top_courier = isset($_POST['show_top_courier']) ? 'yes' : 'no';
        
        // Generate token by calling Shiprocket API
        $token = '';
        $token_error = '';
        
        if ($email && $password) {
            $response = wp_remote_post('https://apiv2.shiprocket.in/v1/external/auth/login', array(
                'headers' => array('Content-Type' => 'application/json'),
                'body'    => wp_json_encode(array(
                    'email'    => $email,
                    'password' => $password,
                )),
            ));
            
            if (!is_wp_error($response)) {
                $body = json_decode(wp_remote_retrieve_body($response));
                $code = wp_remote_retrieve_response_code($response);
                
                if ($code == 200 && isset($body->token)) {
                    $token = $body->token;
                } else {
                    $token_error = 'Invalid Shiprocket credentials or API error.';
                }
            } else {
                $token_error = 'Failed to connect to Shiprocket API: ' . $response->get_error_message();
            }
        }
        
        // Save settings
        update_option('tostishop_shiprocket_email', $email);
        update_option('tostishop_shiprocket_password', $password);
        update_option('tostishop_shiprocket_token', $token);
        update_option('tostishop_shiprocket_show_pincode_check', $show_pincode_check);
        update_option('tostishop_shiprocket_show_top_courier', $show_top_courier);
        
        if ($token_error) {
            echo '<div class="notice notice-error"><p>' . esc_html($token_error) . '</p></div>';
        } else {
            echo '<div class="notice notice-success"><p>Shiprocket settings saved successfully!</p></div>';
        }
    }
    
    // Get current settings
    $email = get_option('tostishop_shiprocket_email', '');
    $password = get_option('tostishop_shiprocket_password', '');
    $token = get_option('tostishop_shiprocket_token', '');
    $show_pincode_check = get_option('tostishop_shiprocket_show_pincode_check', 'no');
    $show_top_courier = get_option('tostishop_shiprocket_show_top_courier', 'yes');
    ?>
    
    <div class="wrap">
        <div class="tostishop-admin-header">
            <h1>Shiprocket Integration</h1>
            <p class="about-description">Configure Shiprocket shipping integration for your TostiShop store.</p>
        </div>
        
        <div class="tostishop-admin-cards">
            <div class="tostishop-card" style="max-width: 600px;">
                <h2><span class="dashicons dashicons-admin-network"></span> Shiprocket API Settings</h2>
                
                <form method="post" action="">
                    <?php wp_nonce_field('save_shiprocket_settings', 'shiprocket_nonce'); ?>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="shiprocket_email">Shiprocket Email</label></th>
                            <td>
                                <input type="email" id="shiprocket_email" name="shiprocket_email" 
                                       value="<?php echo esc_attr($email); ?>" class="regular-text" required />
                                <p class="description">Enter your Shiprocket account email.</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><label for="shiprocket_password">Shiprocket Password</label></th>
                            <td>
                                <input type="password" id="shiprocket_password" name="shiprocket_password" 
                                       value="<?php echo esc_attr($password); ?>" class="regular-text" required />
                                <p class="description">Enter your Shiprocket account password.</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><label for="shiprocket_token">Generated Token</label></th>
                            <td>
                                <textarea id="shiprocket_token" rows="3" class="large-text" readonly><?php echo esc_textarea($token); ?></textarea>
                                <p class="description">This token is automatically generated when you save valid credentials.</p>
                            </td>
                        </tr>
                    </table>
                    
                    <h3>Feature Settings</h3>
                    <table class="form-table">
                        <tr>
                            <th scope="row">Pincode Check</th>
                            <td>
                                <label>
                                    <input type="checkbox" name="show_pincode_check" value="yes" 
                                           <?php checked($show_pincode_check, 'yes'); ?> />
                                    Enable pincode serviceability check on product pages
                                </label>
                                <p class="description">Show a pincode checker on single product pages.</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Courier Options</th>
                            <td>
                                <label>
                                    <input type="checkbox" name="show_top_courier" value="yes" 
                                           <?php checked($show_top_courier, 'yes'); ?> />
                                    Show only top rated 5 courier providers
                                </label>
                                <p class="description">Limit shipping options to the best performing couriers.</p>
                            </td>
                        </tr>
                    </table>
                    
                    <?php submit_button('Save Shiprocket Settings', 'primary', 'save_shiprocket_settings'); ?>
                </form>
            </div>
            
            <div class="tostishop-card">
                <h2><span class="dashicons dashicons-info"></span> Connection Status</h2>
                <p><strong>API Connection:</strong> 
                    <?php echo $token ? '<span class="tostishop-status active">Connected</span>' : '<span class="tostishop-status inactive">Not Connected</span>'; ?>
                </p>
                <p><strong>Pincode Check:</strong> 
                    <?php echo $show_pincode_check === 'yes' ? '<span class="tostishop-status active">Enabled</span>' : '<span class="tostishop-status inactive">Disabled</span>'; ?>
                </p>
                
                <?php if ($token): ?>
                <div style="margin-top: 15px;">
                    <h4>Quick Actions</h4>
                    <a href="<?php echo admin_url('admin.php?page=wc-settings&tab=shipping'); ?>" class="button">
                        Configure Shipping Zones
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="tostishop-admin-footer">
            <h3>Shiprocket Integration Guide</h3>
            <div class="tostishop-help-links">
                <a href="https://shiprocket.in/" target="_blank">
                    <span class="dashicons dashicons-external"></span> Shiprocket Dashboard
                </a>
                <a href="https://apidocs.shiprocket.in/" target="_blank">
                    <span class="dashicons dashicons-book"></span> API Documentation
                </a>
            </div>
        </div>
    </div>
    <?php
}