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