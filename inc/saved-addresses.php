<?php
/**
 * TostiShop Saved Addresses Feature
 * Allow customers to save multiple shipping and billing addresses
 * 
 * @package TostiShop
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include installation script
require_once get_template_directory() . '/inc/saved-addresses-install.php';

// Include admin page if in admin
if (is_admin()) {
    require_once get_template_directory() . '/inc/saved-addresses-admin.php';
}

/**
 * Initialize Saved Addresses Feature
 */
class TostiShop_Saved_Addresses {
    
    public function __construct() {
        $this->init_hooks();
        // Ensure database table exists
        $this->maybe_create_table();
    }
    
    /**
     * Check if table exists and create if needed
     */
    private function maybe_create_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'tostishop_saved_addresses';
        
        // Check if table exists
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
        
        if (!$table_exists) {
            // Create table using install script
            if (class_exists('TostiShop_Saved_Addresses_Install')) {
                TostiShop_Saved_Addresses_Install::create_tables();
            }
        }
    }
    
    /**
     * Initialize hooks and filters
     */
    private function init_hooks() {
        // Override default address management
        // Add menu item for address book
        add_filter('woocommerce_account_menu_items', array($this, 'add_address_book_menu_item'), 40);
        
        // Hook into edit-address endpoint to show our content
        add_action('woocommerce_account_edit-address_endpoint', array($this, 'display_address_book_instead_of_edit'));
        
        // IMPORTANT: Don't override the entire endpoint, just the content
        // add_action('woocommerce_account_edit-address_endpoint', array($this, 'address_book_content'), 5);
        
        // Disable default WooCommerce address forms
        // add_action('woocommerce_account_edit-address_endpoint', array($this, 'disable_default_address_forms'), 1);
        
        // AJAX handlers
        add_action('wp_ajax_tostishop_save_address', array($this, 'ajax_save_address'));
        add_action('wp_ajax_tostishop_delete_address', array($this, 'ajax_delete_address'));
        add_action('wp_ajax_tostishop_set_default_address', array($this, 'ajax_set_default_address'));
        add_action('wp_ajax_tostishop_get_saved_address', array($this, 'ajax_get_saved_address'));
        add_action('wp_ajax_tostishop_get_addresses', array($this, 'ajax_get_addresses'));
        
        // Checkout modifications
        add_action('woocommerce_checkout_before_customer_details', array($this, 'add_saved_addresses_to_checkout'));
        
        // Enqueue scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Install database table on init if needed
        add_action('init', array($this, 'maybe_install_database'), 5);
    }
    
    /**
     * Disable default WooCommerce address forms when our address book is active
     */
    public function disable_default_address_forms() {
        // Remove default WooCommerce address handling
        remove_action('woocommerce_account_edit-address_endpoint', 'woocommerce_account_edit_address');
        
        // Prevent default address form from showing
        // Re-enable template override with proper structure
        add_filter('woocommerce_get_template', array($this, 'override_address_templates'), 10, 5);
    }
    
    /**
     * Override WooCommerce address templates to prevent default forms
     */
    public function override_address_templates($template, $template_name, $args, $template_path, $default_path) {
        // Debug: Log template requests for troubleshooting
        if (defined('WP_DEBUG') && WP_DEBUG && strpos($template_name, 'address') !== false) {
            error_log('TostiShop Address Override: ' . $template_name);
        }
        
        // If this is the default address edit template, redirect to our address book
        if ($template_name === 'myaccount/form-edit-address.php') {
            return get_template_directory() . '/woocommerce/myaccount/form-edit-address.php';
        }
        
        // If this is addresses template, use our address book
        if ($template_name === 'myaccount/addresses.php') {
            return get_template_directory() . '/woocommerce/myaccount/address-book.php';
        }
        
        return $template;
    }
    
    /**
     * Add address book menu item to My Account
     */
    public function add_address_book_menu_item($items) {
        // Replace the default edit-address with our address book
        if (isset($items['edit-address'])) {
            $items['edit-address'] = __('Address Book', 'tostishop');
        } else {
            // If edit-address doesn't exist, add our address book after dashboard
            $new_items = array();
            foreach ($items as $key => $item) {
                $new_items[$key] = $item;
                if ($key === 'dashboard') {
                    $new_items['address-book'] = __('Address Book', 'tostishop');
                }
            }
            return $new_items;
        }
        
        return $items;
    }
    
    /**
     * Display address book instead of standard edit address
     */
    public function display_address_book_instead_of_edit() {
        // Remove the default edit address handler to prevent duplication
        remove_action('woocommerce_account_edit-address_endpoint', 'woocommerce_account_edit_address');
        
        // Note: Template is handled by override_address_templates() method
        // Do not manually include the template here as it causes duplication
    }
    
    /**
     * Add custom endpoint for address book
     */
    public function add_address_book_endpoint() {
        add_rewrite_endpoint('address-book', EP_ROOT | EP_PAGES);
        flush_rewrite_rules();
    }
    
    /**
     * Redirect edit-address to address book
     */
    public function redirect_edit_address_to_address_book() {
        if (is_wc_endpoint_url('edit-address')) {
            wp_redirect(wc_get_account_endpoint_url('address-book'));
            exit;
        }
    }
    
    /**
     * Maybe install database table if it doesn't exist
     */
    public function maybe_install_database() {
        if (!get_option('tostishop_saved_addresses_installed')) {
            tostishop_install_saved_addresses();
        }
    }
    
    /**
     * Create database table for saved addresses
     * @deprecated Use tostishop_install_saved_addresses() instead
     */
    public function create_addresses_table() {
        tostishop_install_saved_addresses();
    }
    
    /**
     * Get saved addresses for a user
     */
    public function get_user_addresses($user_id = null, $address_type = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        if (!$user_id) {
            return array();
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'tostishop_saved_addresses';
        
        $where = $wpdb->prepare("WHERE user_id = %d", $user_id);
        
        if ($address_type) {
            $where .= $wpdb->prepare(" AND address_type = %s", $address_type);
        }
        
        $results = $wpdb->get_results(
            "SELECT * FROM $table_name $where ORDER BY is_default DESC, created_at DESC"
        );
        
        return $results ? $results : array();
    }
    
    /**
     * Get default address for a user
     */
    public function get_default_address($user_id = null, $address_type = 'billing') {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        if (!$user_id) {
            return null;
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'tostishop_saved_addresses';
        
        $result = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE user_id = %d AND address_type = %s AND is_default = 1 LIMIT 1",
            $user_id,
            $address_type
        ));
        
        return $result;
    }
    
    /**
     * Save a new address
     */
    public function save_address($address_data, $user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        if (!$user_id) {
            return false;
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'tostishop_saved_addresses';
        
        // If this is set as default, unset other defaults
        if (!empty($address_data['is_default'])) {
            $wpdb->update(
                $table_name,
                array('is_default' => 0),
                array(
                    'user_id' => $user_id,
                    'address_type' => $address_data['address_type']
                )
            );
        }
        
        $result = $wpdb->insert(
            $table_name,
            array_merge($address_data, array('user_id' => $user_id))
        );
        
        return $result !== false ? $wpdb->insert_id : false;
    }
    
    /**
     * Update an existing address
     */
    public function update_address($address_id, $address_data, $user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        if (!$user_id) {
            return false;
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'tostishop_saved_addresses';
        
        // If this is set as default, unset other defaults
        if (!empty($address_data['is_default'])) {
            $wpdb->update(
                $table_name,
                array('is_default' => 0),
                array(
                    'user_id' => $user_id,
                    'address_type' => $address_data['address_type']
                )
            );
        }
        
        $result = $wpdb->update(
            $table_name,
            $address_data,
            array(
                'id' => $address_id,
                'user_id' => $user_id
            )
        );
        
        return $result !== false;
    }
    
    /**
     * Delete an address
     */
    public function delete_address($address_id, $user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        if (!$user_id) {
            return false;
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'tostishop_saved_addresses';
        
        $result = $wpdb->delete(
            $table_name,
            array(
                'id' => $address_id,
                'user_id' => $user_id
            )
        );
        
        return $result !== false;
    }
    
    /**
     * AJAX handler for saving address
     */
    public function ajax_save_address() {
        check_ajax_referer('tostishop_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('You must be logged in to save addresses.', 'tostishop')));
        }
        
        $address_data = array(
            'address_type' => sanitize_text_field($_POST['address_type']),
            'address_name' => sanitize_text_field($_POST['address_name']),
            'first_name' => sanitize_text_field($_POST['first_name']),
            'last_name' => sanitize_text_field($_POST['last_name']),
            'company' => sanitize_text_field($_POST['company']),
            'address_1' => sanitize_text_field($_POST['address_1']),
            'address_2' => sanitize_text_field($_POST['address_2']),
            'city' => sanitize_text_field($_POST['city']),
            'state' => sanitize_text_field($_POST['state']),
            'postcode' => sanitize_text_field($_POST['postcode']),
            'country' => sanitize_text_field($_POST['country']),
            'phone' => sanitize_text_field($_POST['phone']),
            'email' => sanitize_email($_POST['email']),
            'is_default' => !empty($_POST['is_default']) ? 1 : 0
        );
        
        // Validate required fields
        $required_fields = array('address_name', 'first_name', 'last_name', 'address_1', 'city', 'postcode', 'country');
        foreach ($required_fields as $field) {
            if (empty($address_data[$field])) {
                wp_send_json_error(array('message' => sprintf(__('%s is required.', 'tostishop'), ucfirst(str_replace('_', ' ', $field)))));
            }
        }
        
        $address_id = !empty($_POST['address_id']) ? intval($_POST['address_id']) : 0;
        
        if ($address_id) {
            // Update existing address
            $result = $this->update_address($address_id, $address_data);
        } else {
            // Save new address
            $result = $this->save_address($address_data);
        }
        
        if ($result) {
            wp_send_json_success(array(
                'message' => __('Address saved successfully.', 'tostishop'),
                'address_id' => $address_id ? $address_id : $result
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to save address.', 'tostishop')));
        }
    }
    
    /**
     * AJAX handler for deleting address
     */
    public function ajax_delete_address() {
        check_ajax_referer('tostishop_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('You must be logged in to delete addresses.', 'tostishop')));
        }
        
        $address_id = intval($_POST['address_id']);
        
        if (!$address_id) {
            wp_send_json_error(array('message' => __('Invalid address ID.', 'tostishop')));
        }
        
        $result = $this->delete_address($address_id);
        
        if ($result) {
            wp_send_json_success(array('message' => __('Address deleted successfully.', 'tostishop')));
        } else {
            wp_send_json_error(array('message' => __('Failed to delete address.', 'tostishop')));
        }
    }
    
    /**
     * AJAX handler for setting default address
     */
    public function ajax_set_default_address() {
        check_ajax_referer('tostishop_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('You must be logged in to set default addresses.', 'tostishop')));
        }
        
        $address_id = intval($_POST['address_id']);
        $address_type = sanitize_text_field($_POST['address_type']);
        
        if (!$address_id) {
            wp_send_json_error(array('message' => __('Invalid address ID.', 'tostishop')));
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'tostishop_saved_addresses';
        $user_id = get_current_user_id();
        
        // First, unset all defaults for this address type
        $wpdb->update(
            $table_name,
            array('is_default' => 0),
            array(
                'user_id' => $user_id,
                'address_type' => $address_type
            )
        );
        
        // Then set the new default
        $result = $wpdb->update(
            $table_name,
            array('is_default' => 1),
            array(
                'id' => $address_id,
                'user_id' => $user_id
            )
        );
        
        if ($result !== false) {
            wp_send_json_success(array('message' => __('Default address updated successfully.', 'tostishop')));
        } else {
            wp_send_json_error(array('message' => __('Failed to update default address.', 'tostishop')));
        }
    }
    
    /**
     * AJAX handler for getting a saved address
     */
    public function ajax_get_saved_address() {
        check_ajax_referer('tostishop_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('You must be logged in to access addresses.', 'tostishop')));
        }
        
        $address_id = intval($_POST['address_id']);
        
        if (!$address_id) {
            wp_send_json_error(array('message' => __('Invalid address ID.', 'tostishop')));
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'tostishop_saved_addresses';
        $user_id = get_current_user_id();
        
        $address = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d AND user_id = %d",
            $address_id,
            $user_id
        ));
        
        if ($address) {
            wp_send_json_success($address);
        } else {
            wp_send_json_error(array('message' => __('Address not found.', 'tostishop')));
        }
    }
    
    /**
     * AJAX handler to get all user addresses
     */
    public function ajax_get_addresses() {
        check_ajax_referer('tostishop_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('You must be logged in to access addresses.', 'tostishop')));
        }
        
        $addresses = $this->get_user_addresses();
        wp_send_json_success($addresses);
    }
    
    /**
     * Add saved addresses to checkout page
     */
    public function add_saved_addresses_to_checkout() {
        if (!is_user_logged_in()) {
            return;
        }
        
        $billing_addresses = $this->get_user_addresses(null, 'billing');
        $shipping_addresses = $this->get_user_addresses(null, 'shipping');
        
        if (!empty($billing_addresses) || !empty($shipping_addresses)) {
            include get_template_directory() . '/woocommerce/checkout/saved-addresses.php';
        }
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Script is already enqueued in assets-enqueue.php
        // Just localize the data for AJAX
        if (is_account_page() || is_checkout()) {
            wp_localize_script('tostishop-address-book', 'tostishop_addresses', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('tostishop_nonce'),
                'i18n' => array(
                    'confirm_delete' => __('Are you sure you want to delete this address?', 'tostishop'),
                    'loading' => __('Loading...', 'tostishop'),
                    'save' => __('Save Address', 'tostishop'),
                    'saving' => __('Saving...', 'tostishop'),
                    'error' => __('An error occurred. Please try again.', 'tostishop')
                )
            ));
        }
    }
}
