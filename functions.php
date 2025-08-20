<?php
/**
 * TostiShop Theme Functions
 * Mobile-first WooCommerce theme with Firebase Authentication
 * 
 * @package TostiShop
 * @version 1.0.0
 * @author TostiShop Team
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define theme version constant
if (!defined('TOSTISHOP_VERSION')) {
    define('TOSTISHOP_VERSION', wp_get_theme()->get('Version') ?: '1.0.0');
}

// Development mode flag for testing
if (!defined('TOSTISHOP_DEV_MODE')) {
    define('TOSTISHOP_DEV_MODE', WP_DEBUG || (isset($_GET['dev']) && $_GET['dev'] === '1'));
}

/**
 * TostiShop Theme Core Modules
 * Organized modular structure for better code management
 */

// Core theme setup and configuration
require_once get_template_directory() . '/inc/theme-setup.php';

// Assets and scripts enqueue
require_once get_template_directory() . '/inc/assets-enqueue.php';

// WooCommerce customizations and enhancements
require_once get_template_directory() . '/inc/woocommerce-customizations.php';

// AJAX handlers for cart and other functionality
require_once get_template_directory() . '/inc/ajax-handlers.php';

// Theme customizer settings
require_once get_template_directory() . '/inc/theme-customizer.php';

// Helper functions and utilities
require_once get_template_directory() . '/inc/helper-functions.php';

// Mega menu functionality
require_once get_template_directory() . '/inc/mega-menu.php';

// Wishlist functionality
require_once get_template_directory() . '/inc/wishlist.php';
tostishop_init_wishlist();

// Enqueue wishlist assets and localization
add_action('wp_enqueue_scripts', 'tostishop_enqueue_wishlist_assets');

// Firebase authentication integration (if exists)
$firebase_init_file = get_template_directory() . '/inc/firebase/init.php';
if (file_exists($firebase_init_file)) {
    require_once $firebase_init_file;
}

// Theme options and admin customizations (if exists)
$theme_options_file = get_template_directory() . '/inc/theme-options.php';
if (file_exists($theme_options_file)) {
    require_once $theme_options_file;
}

$admin_menu_file = get_template_directory() . '/inc/tosti-admin-menu.php';
if (file_exists($admin_menu_file)) {
    require_once $admin_menu_file;
}

// Shiprocket integration for shipping and pincode checking
function tostishop_load_shiprocket_integration() {
    $shiprocket_file = get_template_directory() . '/inc/shiprocket-integration.php';
    if (file_exists($shiprocket_file)) {
        require_once $shiprocket_file;
    }
}
add_action('after_setup_theme', 'tostishop_load_shiprocket_integration');

// SEO enhancements for search engines and AI chat models
require_once get_template_directory() . '/inc/seo-enhancements.php';

// AI Chat Model optimization for modern search and voice assistants
require_once get_template_directory() . '/inc/ai-chat-optimization.php';
