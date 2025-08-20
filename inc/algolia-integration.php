<?php
/**
 * Algolia Search Integration for TostiShop
 * 
 * @package TostiShop
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load Composer autoloader
$composer_autoload = get_template_directory() . '/vendor/autoload.php';
if (file_exists($composer_autoload)) {
    require_once $composer_autoload;
}

use Algolia\AlgoliaSearch\SearchClient;

/**
 * Initialize Algolia Integration
 */
class TostiShop_Algolia_Integration {
    
    private static $instance = null;
    private $algolia_client = null;
    private $index = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'init'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // WooCommerce hooks for product indexing
        add_action('woocommerce_product_set_stock_status', array($this, 'update_product_index'), 10, 1);
        add_action('woocommerce_update_product', array($this, 'update_product_index'), 10, 1);
        add_action('woocommerce_delete_product', array($this, 'remove_product_from_index'), 10, 1);
    }
    
    /**
     * Initialize Algolia if credentials are available
     */
    public function init() {
        if ($this->is_algolia_enabled()) {
            $this->init_algolia_client();
        }
    }
    
    /**
     * Check if Algolia is properly configured
     */
    public function is_algolia_enabled() {
        $app_id = get_option('tostishop_algolia_app_id');
        $search_key = get_option('tostishop_algolia_search_key');
        $enabled = get_option('tostishop_algolia_enabled', false);
        
        return $enabled && !empty($app_id) && !empty($search_key);
    }
    
    /**
     * Initialize Algolia Client
     */
    private function init_algolia_client() {
        if (!class_exists('Algolia\AlgoliaSearch\SearchClient')) {
            return false;
        }
        
        try {
            $app_id = get_option('tostishop_algolia_app_id');
            $admin_key = get_option('tostishop_algolia_admin_key');
            
            if (empty($app_id) || empty($admin_key)) {
                return false;
            }
            
            $this->algolia_client = SearchClient::create($app_id, $admin_key);
            $index_name = get_option('tostishop_algolia_index_name', 'tostishop_products');
            $this->index = $this->algolia_client->initIndex($index_name);
            
            return true;
        } catch (Exception $e) {
            error_log('Algolia initialization error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Add admin menu page
     */
    public function add_admin_menu() {
        add_submenu_page(
            'themes.php',
            __('Algolia Search Settings', 'tostishop'),
            __('Algolia Search', 'tostishop'),
            'manage_options',
            'tostishop-algolia',
            array($this, 'admin_page_html')
        );
    }
    
    /**
     * Register settings
     */
    public function admin_init() {
        // Register settings
        register_setting('tostishop_algolia_settings', 'tostishop_algolia_enabled');
        register_setting('tostishop_algolia_settings', 'tostishop_algolia_app_id');
        register_setting('tostishop_algolia_settings', 'tostishop_algolia_search_key');
        register_setting('tostishop_algolia_settings', 'tostishop_algolia_admin_key');
        register_setting('tostishop_algolia_settings', 'tostishop_algolia_index_name');
        register_setting('tostishop_algolia_settings', 'tostishop_algolia_autocomplete');
        register_setting('tostishop_algolia_settings', 'tostishop_algolia_suggestions_count');
        register_setting('tostishop_algolia_settings', 'tostishop_algolia_results_per_page');
        register_setting('tostishop_algolia_settings', 'tostishop_algolia_auto_sync');
        
        // Add settings sections
        add_settings_section(
            'tostishop_algolia_credentials',
            __('Algolia API Credentials', 'tostishop'),
            array($this, 'credentials_section_callback'),
            'tostishop_algolia_settings'
        );
        
        add_settings_section(
            'tostishop_algolia_search_settings',
            __('Search Configuration', 'tostishop'),
            array($this, 'search_settings_section_callback'),
            'tostishop_algolia_settings'
        );
        
        add_settings_section(
            'tostishop_algolia_sync',
            __('Product Synchronization', 'tostishop'),
            array($this, 'sync_section_callback'),
            'tostishop_algolia_settings'
        );
        
        // Add settings fields
        $this->add_settings_fields();
    }
    
    /**
     * Add all settings fields
     */
    private function add_settings_fields() {
        // Enable/Disable
        add_settings_field(
            'tostishop_algolia_enabled',
            __('Enable Algolia Search', 'tostishop'),
            array($this, 'enabled_field_callback'),
            'tostishop_algolia_settings',
            'tostishop_algolia_credentials'
        );
        
        // App ID
        add_settings_field(
            'tostishop_algolia_app_id',
            __('Application ID', 'tostishop'),
            array($this, 'app_id_field_callback'),
            'tostishop_algolia_settings',
            'tostishop_algolia_credentials'
        );
        
        // Search Key
        add_settings_field(
            'tostishop_algolia_search_key',
            __('Search-Only API Key', 'tostishop'),
            array($this, 'search_key_field_callback'),
            'tostishop_algolia_settings',
            'tostishop_algolia_credentials'
        );
        
        // Admin Key
        add_settings_field(
            'tostishop_algolia_admin_key',
            __('Admin API Key', 'tostishop'),
            array($this, 'admin_key_field_callback'),
            'tostishop_algolia_settings',
            'tostishop_algolia_credentials'
        );
        
        // Index Name
        add_settings_field(
            'tostishop_algolia_index_name',
            __('Index Name', 'tostishop'),
            array($this, 'index_name_field_callback'),
            'tostishop_algolia_settings',
            'tostishop_algolia_credentials'
        );
        
        // Autocomplete
        add_settings_field(
            'tostishop_algolia_autocomplete',
            __('Enable Autocomplete', 'tostishop'),
            array($this, 'autocomplete_field_callback'),
            'tostishop_algolia_settings',
            'tostishop_algolia_search_settings'
        );
        
        // Suggestions Count
        add_settings_field(
            'tostishop_algolia_suggestions_count',
            __('Number of Suggestions', 'tostishop'),
            array($this, 'suggestions_count_field_callback'),
            'tostishop_algolia_settings',
            'tostishop_algolia_search_settings'
        );
        
        // Results per page
        add_settings_field(
            'tostishop_algolia_results_per_page',
            __('Results per Page', 'tostishop'),
            array($this, 'results_per_page_field_callback'),
            'tostishop_algolia_settings',
            'tostishop_algolia_search_settings'
        );
        
        // Auto sync
        add_settings_field(
            'tostishop_algolia_auto_sync',
            __('Auto-sync Products', 'tostishop'),
            array($this, 'auto_sync_field_callback'),
            'tostishop_algolia_settings',
            'tostishop_algolia_sync'
        );
    }
    
    /**
     * Section callbacks
     */
    public function credentials_section_callback() {
        echo '<p>' . __('Enter your Algolia API credentials. You can find these in your Algolia dashboard.', 'tostishop') . '</p>';
    }
    
    public function search_settings_section_callback() {
        echo '<p>' . __('Configure how search behaves on your website.', 'tostishop') . '</p>';
    }
    
    public function sync_section_callback() {
        echo '<p>' . __('Manage product synchronization with Algolia.', 'tostishop') . '</p>';
    }
    
    /**
     * Field callbacks
     */
    public function enabled_field_callback() {
        $enabled = get_option('tostishop_algolia_enabled', false);
        echo '<input type="checkbox" name="tostishop_algolia_enabled" value="1" ' . checked(1, $enabled, false) . ' />';
        echo '<p class="description">' . __('Enable Algolia search to replace the default WordPress search.', 'tostishop') . '</p>';
    }
    
    public function app_id_field_callback() {
        $app_id = get_option('tostishop_algolia_app_id', '');
        echo '<input type="text" name="tostishop_algolia_app_id" value="' . esc_attr($app_id) . '" class="regular-text" />';
        echo '<p class="description">' . __('Your Algolia Application ID.', 'tostishop') . '</p>';
    }
    
    public function search_key_field_callback() {
        $search_key = get_option('tostishop_algolia_search_key', '');
        echo '<input type="text" name="tostishop_algolia_search_key" value="' . esc_attr($search_key) . '" class="regular-text" />';
        echo '<p class="description">' . __('Your Algolia Search-Only API Key (safe for frontend use).', 'tostishop') . '</p>';
    }
    
    public function admin_key_field_callback() {
        $admin_key = get_option('tostishop_algolia_admin_key', '');
        echo '<input type="password" name="tostishop_algolia_admin_key" value="' . esc_attr($admin_key) . '" class="regular-text" />';
        echo '<p class="description">' . __('Your Algolia Admin API Key (used for indexing, keep secure).', 'tostishop') . '</p>';
    }
    
    public function index_name_field_callback() {
        $index_name = get_option('tostishop_algolia_index_name', 'tostishop_products');
        echo '<input type="text" name="tostishop_algolia_index_name" value="' . esc_attr($index_name) . '" class="regular-text" />';
        echo '<p class="description">' . __('The name of your Algolia index.', 'tostishop') . '</p>';
    }
    
    public function autocomplete_field_callback() {
        $autocomplete = get_option('tostishop_algolia_autocomplete', true);
        echo '<input type="checkbox" name="tostishop_algolia_autocomplete" value="1" ' . checked(1, $autocomplete, false) . ' />';
        echo '<p class="description">' . __('Show search suggestions as users type.', 'tostishop') . '</p>';
    }
    
    public function suggestions_count_field_callback() {
        $count = get_option('tostishop_algolia_suggestions_count', 5);
        echo '<input type="number" name="tostishop_algolia_suggestions_count" value="' . esc_attr($count) . '" min="1" max="20" />';
        echo '<p class="description">' . __('Number of autocomplete suggestions to show.', 'tostishop') . '</p>';
    }
    
    public function results_per_page_field_callback() {
        $results = get_option('tostishop_algolia_results_per_page', 12);
        echo '<input type="number" name="tostishop_algolia_results_per_page" value="' . esc_attr($results) . '" min="1" max="100" />';
        echo '<p class="description">' . __('Number of search results to show per page.', 'tostishop') . '</p>';
    }
    
    public function auto_sync_field_callback() {
        $auto_sync = get_option('tostishop_algolia_auto_sync', true);
        echo '<input type="checkbox" name="tostishop_algolia_auto_sync" value="1" ' . checked(1, $auto_sync, false) . ' />';
        echo '<p class="description">' . __('Automatically update Algolia index when products are modified.', 'tostishop') . '</p>';
    }
    
    /**
     * Admin page HTML
     */
    public function admin_page_html() {
        if (isset($_GET['settings-updated'])) {
            add_settings_error('tostishop_algolia_messages', 'tostishop_algolia_message', __('Settings Saved'), 'updated');
        }
        
        settings_errors('tostishop_algolia_messages');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="nav-tab-wrapper">
                <a href="#settings" class="nav-tab nav-tab-active"><?php _e('Settings', 'tostishop'); ?></a>
                <a href="#sync" class="nav-tab"><?php _e('Sync Products', 'tostishop'); ?></a>
                <a href="#analytics" class="nav-tab"><?php _e('Analytics', 'tostishop'); ?></a>
            </div>
            
            <div id="settings" class="tab-content">
                <form action="options.php" method="post">
                    <?php
                    settings_fields('tostishop_algolia_settings');
                    do_settings_sections('tostishop_algolia_settings');
                    submit_button(__('Save Settings', 'tostishop'));
                    ?>
                </form>
            </div>
            
            <div id="sync" class="tab-content" style="display: none;">
                <h2><?php _e('Product Synchronization', 'tostishop'); ?></h2>
                <p><?php _e('Sync your WooCommerce products with Algolia search index.', 'tostishop'); ?></p>
                
                <div class="sync-actions">
                    <button id="sync-all-products" class="button button-primary">
                        <?php _e('Sync All Products', 'tostishop'); ?>
                    </button>
                    <button id="clear-index" class="button button-secondary">
                        <?php _e('Clear Index', 'tostishop'); ?>
                    </button>
                    <button id="test-connection" class="button">
                        <?php _e('Test Connection', 'tostishop'); ?>
                    </button>
                </div>
                
                <div id="sync-progress" style="display: none;">
                    <h3><?php _e('Sync Progress', 'tostishop'); ?></h3>
                    <div id="progress-bar" style="width: 100%; background: #f0f0f0; height: 20px; border-radius: 10px;">
                        <div id="progress-fill" style="width: 0%; background: #0073aa; height: 100%; border-radius: 10px; transition: width 0.3s;"></div>
                    </div>
                    <p id="progress-text"><?php _e('Starting sync...', 'tostishop'); ?></p>
                </div>
            </div>
            
            <div id="analytics" class="tab-content" style="display: none;">
                <h2><?php _e('Search Analytics', 'tostishop'); ?></h2>
                <p><?php _e('Coming soon: Search analytics and insights.', 'tostishop'); ?></p>
            </div>
        </div>
        
        <style>
        .tab-content { margin-top: 20px; }
        .sync-actions { margin: 20px 0; }
        .sync-actions button { margin-right: 10px; }
        #sync-progress { margin-top: 20px; padding: 20px; background: #f9f9f9; border-radius: 5px; }
        </style>
        
        <script>
        // Add nonce for AJAX calls
        var tostishopAlgoliaNonce = '<?php echo wp_create_nonce('tostishop_algolia_nonce'); ?>';
        </script>
        
        <?php
        // Enqueue admin script
        wp_enqueue_script(
            'tostishop-algolia-admin',
            get_template_directory_uri() . '/assets/js/algolia-admin.js',
            array('jquery'),
            TOSTISHOP_VERSION,
            true
        );
        ?>
        <?php
    }
    
    /**
     * Enqueue frontend scripts
     */
    public function enqueue_scripts() {
        if (!$this->is_algolia_enabled()) {
            return;
        }
        
        // Algolia InstantSearch.js
        wp_enqueue_script(
            'algolia-instantsearch',
            'https://cdn.jsdelivr.net/npm/instantsearch.js@4.49.0/dist/instantsearch.production.min.js',
            array(),
            '4.49.0',
            true
        );
        
        // Algolia Autocomplete
        if (get_option('tostishop_algolia_autocomplete', true)) {
            wp_enqueue_script(
                'algolia-autocomplete',
                'https://cdn.jsdelivr.net/npm/@algolia/autocomplete-js@1.11.1/dist/umd/index.production.js',
                array(),
                '1.11.1',
                true
            );
        }
        
        // Theme's Algolia integration script
        wp_enqueue_script(
            'tostishop-algolia',
            get_template_directory_uri() . '/assets/js/algolia-search.js',
            array('algolia-instantsearch'),
            TOSTISHOP_VERSION,
            true
        );
        
        // Localize script with Algolia config
        wp_localize_script('tostishop-algolia', 'tostishopAlgolia', array(
            'appId' => get_option('tostishop_algolia_app_id'),
            'searchKey' => get_option('tostishop_algolia_search_key'),
            'indexName' => get_option('tostishop_algolia_index_name', 'tostishop_products'),
            'autocomplete' => get_option('tostishop_algolia_autocomplete', true),
            'suggestionsCount' => get_option('tostishop_algolia_suggestions_count', 5),
            'resultsPerPage' => get_option('tostishop_algolia_results_per_page', 12),
            'shopUrl' => wc_get_page_permalink('shop'),
            'cartUrl' => wc_get_cart_url(),
            'currencySymbol' => get_woocommerce_currency_symbol(),
            'nonce' => wp_create_nonce('tostishop_algolia_nonce')
        ));
        
        // Algolia CSS
        wp_enqueue_style(
            'tostishop-algolia',
            get_template_directory_uri() . '/assets/css/algolia-search.css',
            array(),
            TOSTISHOP_VERSION
        );
    }
    
    /**
     * Update product in Algolia index
     */
    public function update_product_index($product_id) {
        if (!$this->is_algolia_enabled() || !get_option('tostishop_algolia_auto_sync', true)) {
            return;
        }
        
        if (!$this->index) {
            return;
        }
        
        $product = wc_get_product($product_id);
        if (!$product) {
            return;
        }
        
        $product_data = $this->prepare_product_data($product);
        
        try {
            $this->index->saveObject($product_data);
        } catch (Exception $e) {
            error_log('Algolia product update error: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove product from Algolia index
     */
    public function remove_product_from_index($product_id) {
        if (!$this->is_algolia_enabled() || !get_option('tostishop_algolia_auto_sync', true)) {
            return;
        }
        
        if (!$this->index) {
            return;
        }
        
        try {
            $this->index->deleteObject($product_id);
        } catch (Exception $e) {
            error_log('Algolia product deletion error: ' . $e->getMessage());
        }
    }
    
    /**
     * Prepare product data for Algolia
     */
    public function prepare_product_data($product) {
        $product_id = $product->get_id();
        $categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'names'));
        $tags = wp_get_post_terms($product_id, 'product_tag', array('fields' => 'names'));
        
        $data = array(
            'objectID' => $product_id,
            'title' => $product->get_name(),
            'description' => wp_strip_all_tags($product->get_description()),
            'short_description' => wp_strip_all_tags($product->get_short_description()),
            'sku' => $product->get_sku(),
            'price' => (float) $product->get_price(),
            'regular_price' => (float) $product->get_regular_price(),
            'sale_price' => $product->is_on_sale() ? (float) $product->get_sale_price() : null,
            'on_sale' => $product->is_on_sale(),
            'stock_status' => $product->get_stock_status(),
            'in_stock' => $product->is_in_stock(),
            'stock_quantity' => $product->get_stock_quantity(),
            'categories' => $categories,
            'tags' => $tags,
            'image' => wp_get_attachment_image_url($product->get_image_id(), 'medium'),
            'gallery' => array_map(function($id) {
                return wp_get_attachment_image_url($id, 'medium');
            }, $product->get_gallery_image_ids()),
            'url' => get_permalink($product_id),
            'date_created' => $product->get_date_created()->getTimestamp(),
            'date_modified' => $product->get_date_modified()->getTimestamp(),
            'rating_average' => (float) $product->get_average_rating(),
            'rating_count' => (int) $product->get_rating_count(),
            'type' => $product->get_type(),
            'status' => $product->get_status(),
        );
        
        // Add product attributes
        $attributes = $product->get_attributes();
        foreach ($attributes as $attribute) {
            if ($attribute->get_variation()) {
                continue; // Skip variation attributes for now
            }
            
            $attribute_name = wc_attribute_label($attribute->get_name());
            $attribute_values = wc_get_product_terms($product_id, $attribute->get_name(), array('fields' => 'names'));
            $data['attributes'][$attribute_name] = $attribute_values;
        }
        
        // Add custom fields if any
        $custom_fields = get_post_meta($product_id);
        foreach ($custom_fields as $key => $value) {
            if (strpos($key, '_') !== 0 && !empty($value[0])) { // Skip private fields
                $data['custom_fields'][$key] = $value[0];
            }
        }
        
        return $data;
    }
    
    /**
     * Get Algolia index
     */
    public function get_index() {
        return $this->index;
    }
}

// Initialize the Algolia integration
TostiShop_Algolia_Integration::get_instance();
