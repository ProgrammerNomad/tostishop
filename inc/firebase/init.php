<?php
/**
 * Firebase Authentication Initialization
 * TostiShop Theme - Firebase Module Loader
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load Firebase modules
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/enqueue.php';
require_once __DIR__ . '/ajax-handlers.php';

/**
 * Initialize Firebase Authentication System
 */
function tostishop_firebase_init() {
    // Initialize Firebase only if API key is set
    $api_key = get_option('tostishop_firebase_api_key');
    if (!empty($api_key)) {
        // Firebase is configured, enable authentication
        add_action('wp_enqueue_scripts', 'tostishop_enqueue_firebase_scripts');
        
        // NO UI HOOKS - Firebase will only work with custom form-login.php
        // Removed: add_action('woocommerce_before_checkout_form', 'tostishop_add_firebase_to_checkout', 5);
        // Removed: add_action('woocommerce_before_customer_login_form', 'tostishop_add_firebase_to_account', 5);
    }
}
add_action('init', 'tostishop_firebase_init');

/**
 * Firebase Settings Page
 */
function tostishop_firebase_admin_page() {
    if (isset($_POST['submit'])) {
        // Save Firebase settings
        update_option('tostishop_firebase_api_key', sanitize_text_field($_POST['firebase_api_key']));
        update_option('tostishop_firebase_project_id', sanitize_text_field($_POST['firebase_project_id']));
        update_option('tostishop_firebase_auth_domain', sanitize_text_field($_POST['firebase_auth_domain']));
        update_option('tostishop_firebase_sender_id', sanitize_text_field($_POST['firebase_sender_id']));
        update_option('tostishop_firebase_app_id', sanitize_text_field($_POST['firebase_app_id']));
        update_option('tostishop_firebase_measurement_id', sanitize_text_field($_POST['firebase_measurement_id']));
        
        echo '<div class="notice notice-success"><p>Firebase settings saved successfully!</p></div>';
    }
    
    $api_key = get_option('tostishop_firebase_api_key', '');
    $project_id = get_option('tostishop_firebase_project_id', '');
    $auth_domain = get_option('tostishop_firebase_auth_domain', '');
    $sender_id = get_option('tostishop_firebase_sender_id', '');
    $app_id = get_option('tostishop_firebase_app_id', '');
    $measurement_id = get_option('tostishop_firebase_measurement_id', '');
    ?>
    
    <div class="wrap">
        <h1>🔥 Firebase Authentication Settings</h1>
        <p>Configure Firebase for TostiShop mobile OTP, Google, and email authentication.</p>
        
        <div class="card" style="max-width: 800px;">
            <h2>Firebase Project Configuration</h2>
            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th scope="row">API Key</th>
                        <td>
                            <input type="text" name="firebase_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text" required />
                            <p class="description">Your Firebase Web API Key</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Project ID</th>
                        <td>
                            <input type="text" name="firebase_project_id" value="<?php echo esc_attr($project_id); ?>" class="regular-text" required />
                            <p class="description">Your Firebase Project ID</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Auth Domain</th>
                        <td>
                            <input type="text" name="firebase_auth_domain" value="<?php echo esc_attr($auth_domain); ?>" class="regular-text" required />
                            <p class="description">Usually: your-project-id.firebaseapp.com</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Messaging Sender ID</th>
                        <td>
                            <input type="text" name="firebase_sender_id" value="<?php echo esc_attr($sender_id); ?>" class="regular-text" required />
                            <p class="description">Firebase Cloud Messaging Sender ID</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">App ID</th>
                        <td>
                            <input type="text" name="firebase_app_id" value="<?php echo esc_attr($app_id); ?>" class="regular-text" required />
                            <p class="description">Firebase App ID</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Measurement ID (Optional)</th>
                        <td>
                            <input type="text" name="firebase_measurement_id" value="<?php echo esc_attr($measurement_id); ?>" class="regular-text" />
                            <p class="description">Google Analytics Measurement ID (optional)</p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button('Save Firebase Settings'); ?>
            </form>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>🚀 Setup Instructions</h2>
            <ol>
                <li><strong>Create Firebase Project:</strong> Go to <a href="https://console.firebase.google.com/" target="_blank">Firebase Console</a></li>
                <li><strong>Enable Authentication:</strong> Go to Authentication > Sign-in method</li>
                <li><strong>Enable Sign-in Providers:</strong>
                    <ul>
                        <li>✅ Phone (for OTP login)</li>
                        <li>✅ Google (for social login)</li>
                        <li>✅ Email/Password (for traditional login)</li>
                    </ul>
                </li>
                <li><strong>Add Web App:</strong> Project Settings > Add app > Web</li>
                <li><strong>Copy Config:</strong> Copy the config values to the form above</li>
                <li><strong>Add Domain:</strong> Authentication > Settings > Authorized domains > Add <code><?php echo home_url(); ?></code></li>
            </ol>
        </div>
        
        <?php if (!empty($api_key)): ?>
        <div class="card" style="max-width: 800px; margin-top: 20px; background: #d4edda; border-color: #c3e6cb;">
            <h2 style="color: #155724;">✅ Firebase Status: Connected</h2>
            <p style="color: #155724;">Firebase authentication is properly configured and ready to use!</p>
        </div>
        <?php else: ?>
        <div class="card" style="max-width: 800px; margin-top: 20px; background: #f8d7da; border-color: #f5c6cb;">
            <h2 style="color: #721c24;">⚠️ Firebase Status: Not Configured</h2>
            <p style="color: #721c24;">Please fill in the Firebase configuration above to enable authentication.</p>
        </div>
        <?php endif; ?>
    </div>
    
    <?php
}

/**
 * Firebase authentication is now integrated with custom form-login.php only
 * No separate UI hooks needed - Firebase works with existing custom forms
 */
