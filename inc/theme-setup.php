<?php
/**
 * TostiShop Theme Setup and Configuration
 * Core theme functionality and setup
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup - Core functionality
 */
function tostishop_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    // Custom logo support with TostiShop branding
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array('site-title', 'site-description'),
        'unlink-homepage-logo' => false,
    ));
    
    // WooCommerce support
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'tostishop'),
        'mobile' => __('Mobile Menu', 'tostishop'),
        'footer' => __('Footer Menu', 'tostishop'),
    ));
    
    // Add custom image sizes
    add_image_size('tostishop-hero', 1200, 600, true);
    add_image_size('tostishop-product-thumb', 300, 300, true);
    add_image_size('tostishop-category-thumb', 150, 150, true);
    
    // Set custom logo on theme activation
    if (!get_theme_mod('custom_logo')) {
        $logo_path = get_template_directory() . '/assets/images/logo.png';
        if (file_exists($logo_path)) {
            $logo_attachment_id = tostishop_upload_logo();
            if ($logo_attachment_id) {
                set_theme_mod('custom_logo', $logo_attachment_id);
            }
        }
    }
}
add_action('after_setup_theme', 'tostishop_setup');

/**
 * Upload and set custom logo
 */
function tostishop_upload_logo() {
    $logo_url = get_template_directory_uri() . '/assets/images/logo.png';
    $logo_path = get_template_directory() . '/assets/images/logo.png';
    
    if (!file_exists($logo_path)) {
        return false;
    }
    
    // Check if logo already exists in media library
    $existing_logo = get_posts(array(
        'post_type' => 'attachment',
        'meta_query' => array(
            array(
                'key' => '_wp_attached_file',
                'value' => 'tostishop-logo.png',
                'compare' => 'LIKE'
            )
        ),
        'posts_per_page' => 1
    ));
    
    if (!empty($existing_logo)) {
        return $existing_logo[0]->ID;
    }
    
    // Upload logo to media library
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    
    $tmp = download_url($logo_url);
    if (is_wp_error($tmp)) {
        return false;
    }
    
    $file_array = array(
        'name' => 'tostishop-logo.png',
        'tmp_name' => $tmp
    );
    
    $attachment_id = media_handle_sideload($file_array, 0);
    
    if (is_wp_error($attachment_id)) {
        @unlink($tmp);
        return false;
    }
    
    @unlink($tmp);
    return $attachment_id;
}

/**
 * Register widget areas
 */
function tostishop_widgets_init() {
    register_sidebar(array(
        'name' => __('Footer Widget 1', 'tostishop'),
        'id' => 'footer-1',
        'description' => __('Add widgets here to appear in your footer.', 'tostishop'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title text-lg font-semibold mb-4">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer Widget 2', 'tostishop'),
        'id' => 'footer-2',
        'description' => __('Add widgets here to appear in your footer.', 'tostishop'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title text-lg font-semibold mb-4">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer Widget 3', 'tostishop'),
        'id' => 'footer-3',
        'description' => __('Add widgets here to appear in your footer.', 'tostishop'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title text-lg font-semibold mb-4">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'tostishop_widgets_init');

/**
 * Set default custom logo on theme activation
 */
function tostishop_set_default_logo() {
    // Check if custom logo is not already set
    if (!has_custom_logo()) {
        $logo_attachment_id = tostishop_upload_logo();
        if ($logo_attachment_id) {
            set_theme_mod('custom_logo', $logo_attachment_id);
        }
    }
}
add_action('after_switch_theme', 'tostishop_set_default_logo');
