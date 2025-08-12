<?php
/**
 * TostiShop Saved Addresses Admin Page
 * Admin interface for managing saved addresses feature
 * 
 * @package TostiShop
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add admin menu for saved addresses
 */
function tostishop_saved_addresses_admin_menu() {
    add_submenu_page(
        'woocommerce',
        __('Saved Addresses', 'tostishop'),
        __('Saved Addresses', 'tostishop'),
        'manage_woocommerce',
        'tostishop-saved-addresses',
        'tostishop_saved_addresses_admin_page'
    );
}
add_action('admin_menu', 'tostishop_saved_addresses_admin_menu');

/**
 * Admin page content
 */
function tostishop_saved_addresses_admin_page() {
    // Handle actions
    if (isset($_POST['action'])) {
        $action = sanitize_text_field($_POST['action']);
        
        switch ($action) {
            case 'install_database':
                if (wp_verify_nonce($_POST['nonce'], 'tostishop_admin_action')) {
                    tostishop_install_saved_addresses();
                    echo '<div class="notice notice-success"><p>' . __('Database tables created successfully!', 'tostishop') . '</p></div>';
                }
                break;
                
            case 'import_addresses':
                if (wp_verify_nonce($_POST['nonce'], 'tostishop_admin_action')) {
                    tostishop_import_existing_addresses();
                    echo '<div class="notice notice-success"><p>' . __('Existing addresses imported successfully!', 'tostishop') . '</p></div>';
                }
                break;
        }
    }
    
    $stats = tostishop_get_saved_addresses_stats();
    ?>
    
    <div class="wrap">
        <h1><?php _e('Saved Addresses Management', 'tostishop'); ?></h1>
        
        <!-- Statistics -->
        <div class="card">
            <h2><?php _e('Statistics', 'tostishop'); ?></h2>
            <table class="widefat">
                <tbody>
                    <tr>
                        <td><strong><?php _e('Total Addresses:', 'tostishop'); ?></strong></td>
                        <td><?php echo esc_html($stats['total_addresses']); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Users with Addresses:', 'tostishop'); ?></strong></td>
                        <td><?php echo esc_html($stats['total_users']); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Billing Addresses:', 'tostishop'); ?></strong></td>
                        <td><?php echo esc_html($stats['billing_addresses']); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Shipping Addresses:', 'tostishop'); ?></strong></td>
                        <td><?php echo esc_html($stats['shipping_addresses']); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('Default Addresses:', 'tostishop'); ?></strong></td>
                        <td><?php echo esc_html($stats['default_addresses']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Installation Status -->
        <div class="card">
            <h2><?php _e('Installation Status', 'tostishop'); ?></h2>
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . 'tostishop_saved_addresses';
            $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
            $version = get_option('tostishop_saved_addresses_version', 'Not installed');
            $installed_date = get_option('tostishop_saved_addresses_installed');
            ?>
            
            <p>
                <strong><?php _e('Database Table:', 'tostishop'); ?></strong>
                <?php if ($table_exists) : ?>
                    <span style="color: green;"><?php _e('✓ Installed', 'tostishop'); ?></span>
                <?php else : ?>
                    <span style="color: red;"><?php _e('✗ Not Found', 'tostishop'); ?></span>
                <?php endif; ?>
            </p>
            
            <p>
                <strong><?php _e('Version:', 'tostishop'); ?></strong>
                <?php echo esc_html($version); ?>
            </p>
            
            <?php if ($installed_date) : ?>
                <p>
                    <strong><?php _e('Installed:', 'tostishop'); ?></strong>
                    <?php echo esc_html(date('Y-m-d H:i:s', $installed_date)); ?>
                </p>
            <?php endif; ?>
            
            <?php if (!$table_exists) : ?>
                <form method="post" style="margin-top: 20px;">
                    <?php wp_nonce_field('tostishop_admin_action', 'nonce'); ?>
                    <input type="hidden" name="action" value="install_database">
                    <button type="submit" class="button button-primary">
                        <?php _e('Install Database Tables', 'tostishop'); ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>
        
        <!-- Migration Tools -->
        <div class="card">
            <h2><?php _e('Migration Tools', 'tostishop'); ?></h2>
            <p><?php _e('Import existing customer addresses from WooCommerce user meta data.', 'tostishop'); ?></p>
            
            <form method="post" style="margin-top: 20px;">
                <?php wp_nonce_field('tostishop_admin_action', 'nonce'); ?>
                <input type="hidden" name="action" value="import_addresses">
                <button type="submit" class="button button-secondary" 
                        onclick="return confirm('<?php _e('This will import existing customer addresses. Are you sure?', 'tostishop'); ?>')">
                    <?php _e('Import Existing Addresses', 'tostishop'); ?>
                </button>
            </form>
            
            <p class="description">
                <?php _e('Note: This will only import addresses for users who don\'t already have saved addresses.', 'tostishop'); ?>
            </p>
        </div>
        
        <!-- Recent Addresses -->
        <?php if ($stats['total_addresses'] > 0) : ?>
            <div class="card">
                <h2><?php _e('Recent Addresses', 'tostishop'); ?></h2>
                <?php
                $recent_addresses = $wpdb->get_results(
                    "SELECT sa.*, u.user_login, u.user_email 
                     FROM {$wpdb->prefix}tostishop_saved_addresses sa 
                     LEFT JOIN {$wpdb->users} u ON sa.user_id = u.ID 
                     ORDER BY sa.created_at DESC 
                     LIMIT 10"
                );
                ?>
                
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('User', 'tostishop'); ?></th>
                            <th><?php _e('Address Name', 'tostishop'); ?></th>
                            <th><?php _e('Type', 'tostishop'); ?></th>
                            <th><?php _e('Address', 'tostishop'); ?></th>
                            <th><?php _e('Default', 'tostishop'); ?></th>
                            <th><?php _e('Created', 'tostishop'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_addresses as $address) : ?>
                            <tr>
                                <td>
                                    <strong><?php echo esc_html($address->user_login); ?></strong><br>
                                    <small><?php echo esc_html($address->user_email); ?></small>
                                </td>
                                <td><?php echo esc_html($address->address_name); ?></td>
                                <td>
                                    <span class="<?php echo $address->address_type === 'billing' ? 'dashicons-money' : 'dashicons-products'; ?>"></span>
                                    <?php echo esc_html(ucfirst($address->address_type)); ?>
                                </td>
                                <td>
                                    <?php echo esc_html($address->first_name . ' ' . $address->last_name); ?><br>
                                    <small><?php echo esc_html($address->address_1 . ', ' . $address->city); ?></small>
                                </td>
                                <td>
                                    <?php if ($address->is_default) : ?>
                                        <span style="color: green;">✓</span>
                                    <?php else : ?>
                                        <span style="color: #ccc;">○</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo esc_html(date('Y-m-d H:i', strtotime($address->created_at))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <!-- Documentation -->
        <div class="card">
            <h2><?php _e('Documentation', 'tostishop'); ?></h2>
            <h3><?php _e('How it works:', 'tostishop'); ?></h3>
            <ul>
                <li><?php _e('Customers can save multiple billing and shipping addresses', 'tostishop'); ?></li>
                <li><?php _e('Addresses are accessible from My Account > Address Book', 'tostishop'); ?></li>
                <li><?php _e('During checkout, customers can select from saved addresses', 'tostishop'); ?></li>
                <li><?php _e('Each customer can set default addresses for faster checkout', 'tostishop'); ?></li>
            </ul>
            
            <h3><?php _e('Features:', 'tostishop'); ?></h3>
            <ul>
                <li><?php _e('✓ Multiple addresses per customer', 'tostishop'); ?></li>
                <li><?php _e('✓ Default address selection', 'tostishop'); ?></li>
                <li><?php _e('✓ Mobile-optimized interface', 'tostishop'); ?></li>
                <li><?php _e('✓ AJAX-powered address management', 'tostishop'); ?></li>
                <li><?php _e('✓ Integration with WooCommerce checkout', 'tostishop'); ?></li>
                <li><?php _e('✓ Address validation and sanitization', 'tostishop'); ?></li>
            </ul>
        </div>
    </div>
    
    <style>
    .card {
        background: #fff;
        border: 1px solid #ccd0d4;
        border-radius: 4px;
        margin: 20px 0;
        padding: 20px;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
    }
    .card h2 {
        margin-top: 0;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    </style>
    <?php
}

/**
 * Add admin styles
 */
function tostishop_saved_addresses_admin_styles($hook) {
    if ($hook !== 'woocommerce_page_tostishop-saved-addresses') {
        return;
    }
    
    wp_enqueue_style('dashicons');
}
add_action('admin_enqueue_scripts', 'tostishop_saved_addresses_admin_styles');
