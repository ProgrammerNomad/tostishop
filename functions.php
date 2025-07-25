<?php
/**
 * TostiShop Theme Functions - CLEANED UP VERSION
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function tostishop_setup() {
    // Add theme support for various features
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('title-tag');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('custom-logo');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'tostishop'),
        'mobile' => __('Mobile Menu', 'tostishop'),
        'footer' => __('Footer Menu', 'tostishop'),
    ));
}
add_action('after_setup_theme', 'tostishop_setup');

/**
 * Enqueue scripts and styles - CLEANED UP
 */
function tostishop_scripts() {
    // Main Tailwind CSS (compiled)
    wp_enqueue_style('tostishop-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // ONLY enqueue minimal checkout CSS on checkout pages
    if (is_checkout()) {
        wp_enqueue_style(
            'tostishop-checkout-minimal',
            get_template_directory_uri() . '/assets/css/checkout-minimal.css',
            array('tostishop-style'),
            '1.0.0'
        );
    }
    
    // Alpine.js for interactivity
    wp_enqueue_script('alpinejs', 'https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js', array(), '3.0.0', true);
    wp_script_add_data('alpinejs', 'defer', true);
    
    // Theme JS
    wp_enqueue_script('tostishop-theme', get_template_directory_uri() . '/assets/js/theme.js', array('jquery'), '1.0.0', true);
    
    // AJAX parameters
    wp_localize_script('tostishop-theme', 'tostishop_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('tostishop_nonce'),
        'wc_ajax_url' => WC_AJAX::get_endpoint('%%endpoint%%'),
    ));
    
    // Checkout-specific scripts
    if (is_checkout()) {
        wp_enqueue_script(
            'tostishop-checkout-minimal',
            get_template_directory_uri() . '/assets/js/checkout-minimal.js',
            array('jquery', 'wc-checkout'),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'tostishop_scripts');

/**
 * Remove the bloated custom.css
 */
function tostishop_remove_bloated_css() {
    wp_dequeue_style('tostishop-custom');
    wp_deregister_style('tostishop-custom');
}
add_action('wp_enqueue_scripts', 'tostishop_remove_bloated_css', 999);

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
 * WooCommerce theme support
 */
function tostishop_woocommerce_setup() {
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 300,
        'single_image_width' => 600,
        'product_grid' => array(
            'default_rows' => 4,
            'min_rows' => 2,
            'max_rows' => 8,
            'default_columns' => 3,
            'min_columns' => 2,
            'max_columns' => 5,
        ),
    ));
}
add_action('after_setup_theme', 'tostishop_woocommerce_setup');

/**
 * Customize WooCommerce
 */
function tostishop_woocommerce_customization() {
    // Remove default WooCommerce styles
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    
    // Modify cart fragments
    add_filter('woocommerce_add_to_cart_fragments', 'tostishop_cart_count_fragments');
}
add_action('init', 'tostishop_woocommerce_customization');

/**
 * Cart count fragments for AJAX
 */
function tostishop_cart_count_fragments($fragments) {
    $fragments['[data-cart-count]'] = '<span data-cart-count class="cart-count bg-accent text-white text-xs rounded-full px-2 py-1 min-w-[1.25rem] h-5 flex items-center justify-center">' . WC()->cart->get_cart_contents_count() . '</span>';
    return $fragments;
}

/**
 * Debug checkout issues (only if WP_DEBUG is enabled)
 */
function tostishop_debug_checkout() {
    if (is_checkout() && defined('WP_DEBUG') && WP_DEBUG) {
        echo '<script>console.log("TostiShop Checkout Debug: Page loaded successfully");</script>';
    }
}
add_action('wp_footer', 'tostishop_debug_checkout');

/**
 * Theme customizer
 */
function tostishop_customize_register($wp_customize) {
    // Hero Section
    $wp_customize->add_section('tostishop_hero', array(
        'title' => __('Hero Section', 'tostishop'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('hero_title', array(
        'default' => __('Welcome to TostiShop', 'tostishop'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hero_title', array(
        'label' => __('Hero Title', 'tostishop'),
        'section' => 'tostishop_hero',
        'type' => 'text',
    ));

    $wp_customize->add_setting('hero_subtitle', array(
        'default' => __('Discover amazing products at great prices', 'tostishop'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hero_subtitle', array(
        'label' => __('Hero Subtitle', 'tostishop'),
        'section' => 'tostishop_hero',
        'type' => 'text',
    ));
}
add_action('customize_register', 'tostishop_customize_register');

// Content width
if (!isset($content_width)) {
    $content_width = 800;
}
