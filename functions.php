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
