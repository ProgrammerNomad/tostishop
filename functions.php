<?php
/**
 * TostiShop Theme Functions
 * Mobile-first WooCommerce theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
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
}
add_action('after_setup_theme', 'tostishop_setup');

/**
 * Enqueue scripts and styles
 */
function tostishop_scripts() {
    // Main stylesheet (compiled Tailwind CSS)
    wp_enqueue_style('tostishop-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Alpine.js for interactivity
    wp_enqueue_script('alpinejs', 'https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js', array(), '3.0.0', true);
    wp_script_add_data('alpinejs', 'defer', true);
    
    // Custom JS
    wp_enqueue_script('tostishop-ui', get_template_directory_uri() . '/assets/js/ui.js', array(), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('tostishop-ui', 'tostishop_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('tostishop_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'tostishop_scripts');

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
 * Customize WooCommerce
 */
// Remove default WooCommerce styles
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Modify WooCommerce loop
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

// Add custom WooCommerce actions
add_action('woocommerce_before_shop_loop_item_title', 'tostishop_product_link_open', 10);
add_action('woocommerce_shop_loop_item_title', 'tostishop_product_title', 10);
add_action('woocommerce_after_shop_loop_item', 'tostishop_product_link_close', 5);

function tostishop_product_link_open() {
    global $product;
    echo '<a href="' . get_permalink($product->get_id()) . '" class="group block">';
}

function tostishop_product_link_close() {
    echo '</a>';
}

function tostishop_product_title() {
    echo '<h3 class="text-sm font-medium text-gray-900 group-hover:text-navy-600 transition-colors duration-200">' . get_the_title() . '</h3>';
}

/**
 * Custom functions
 */

// Get cart count for header
function tostishop_cart_count() {
    if (function_exists('WC')) {
        return WC()->cart->get_cart_contents_count();
    }
    return 0;
}

// Get cart total for header
function tostishop_cart_total() {
    if (function_exists('WC')) {
        return WC()->cart->get_cart_total();
    }
    return '';
}

// Custom breadcrumbs
function tostishop_breadcrumbs() {
    if (function_exists('woocommerce_breadcrumb')) {
        woocommerce_breadcrumb(array(
            'delimiter' => ' / ',
            'wrap_before' => '<nav class="breadcrumbs text-sm text-gray-600 mb-4">',
            'wrap_after' => '</nav>',
            'before' => '',
            'after' => '',
            'home' => __('Home', 'tostishop'),
        ));
    }
}

/**
 * AJAX handlers
 */

// Add to cart via AJAX
add_action('wp_ajax_tostishop_add_to_cart', 'tostishop_ajax_add_to_cart');
add_action('wp_ajax_nopriv_tostishop_add_to_cart', 'tostishop_ajax_add_to_cart');

function tostishop_ajax_add_to_cart() {
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_nonce')) {
        wp_die('Security check failed');
    }
    
    $product_id = absint($_POST['product_id']);
    $quantity = absint($_POST['quantity']);
    
    $result = WC()->cart->add_to_cart($product_id, $quantity);
    
    if ($result) {
        wp_send_json_success(array(
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_total' => WC()->cart->get_cart_total(),
        ));
    } else {
        wp_send_json_error();
    }
}

/**
 * Set default custom logo on theme activation
 */
function tostishop_set_default_logo() {
    // Check if custom logo is not already set
    if (!has_custom_logo()) {
        $logo_path = get_template_directory() . '/assets/images/logo.png';
        $logo_url = get_template_directory_uri() . '/assets/images/logo.png';
        
        // Check if logo file exists
        if (file_exists($logo_path)) {
            // Get the upload directory
            $upload_dir = wp_upload_dir();
            
            // Check if attachment already exists
            $attachment_id = attachment_url_to_postid($logo_url);
            
            if (!$attachment_id) {
                // Create attachment
                $filename = basename($logo_path);
                $file_type = wp_check_filetype($filename, null);
                
                $attachment = array(
                    'guid' => $logo_url,
                    'post_mime_type' => $file_type['type'],
                    'post_title' => 'TostiShop Logo',
                    'post_content' => '',
                    'post_status' => 'inherit'
                );
                
                $attachment_id = wp_insert_attachment($attachment, $logo_path);
                
                if (!is_wp_error($attachment_id)) {
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    $attachment_data = wp_generate_attachment_metadata($attachment_id, $logo_path);
                    wp_update_attachment_metadata($attachment_id, $attachment_data);
                }
            }
            
            // Set as custom logo
            if ($attachment_id) {
                set_theme_mod('custom_logo', $attachment_id);
            }
        }
    }
}

// Run on theme activation
add_action('after_switch_theme', 'tostishop_set_default_logo');

/**
 * Theme customizer
 */
function tostishop_customize_register($wp_customize) {
    // Hero section
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
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('hero_subtitle', array(
        'label' => __('Hero Subtitle', 'tostishop'),
        'section' => 'tostishop_hero',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('hero_image', array(
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_image', array(
        'label' => __('Hero Background Image', 'tostishop'),
        'section' => 'tostishop_hero',
    )));
}
add_action('customize_register', 'tostishop_customize_register');
