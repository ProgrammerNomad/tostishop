<?php
/**
 * TostiShop Helper Functions
 * Utility functions and helper methods
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get Featured Products for Homepage
 */
function tostishop_get_featured_products($limit = 8) {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => '_featured',
                'value' => 'yes'
            )
        ),
        'post_status' => 'publish'
    );
    
    $featured_products = new WP_Query($args);
    
    // If no featured products, get latest products
    if (!$featured_products->have_posts()) {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $limit,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish'
        );
        
        $featured_products = new WP_Query($args);
    }
    
    return $featured_products;
}

/**
 * Get Products on Sale for Homepage
 */
function tostishop_get_sale_products($limit = 8) {
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => '_sale_price',
                'value' => '',
                'compare' => '!='
            )
        ),
        'post_status' => 'publish'
    );
    
    return new WP_Query($args);
}

/**
 * Get product categories with products count
 */
function tostishop_get_product_categories($args = array()) {
    $defaults = array(
        'taxonomy' => 'product_cat',
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => true,
        'parent' => 0, // Only top-level categories
    );
    
    $args = wp_parse_args($args, $defaults);
    
    return get_terms($args);
}

/**
 * Format price with currency
 */
function tostishop_format_price($price) {
    if (function_exists('wc_price')) {
        return wc_price($price);
    }
    
    return '₹' . number_format($price, 2);
}

/**
 * Get product rating HTML
 */
function tostishop_get_product_rating_html($product) {
    if (!$product || !function_exists('wc_get_rating_html')) {
        return '';
    }
    
    $rating_count = $product->get_rating_count();
    $review_count = $product->get_review_count();
    $average = $product->get_average_rating();
    
    if ($rating_count > 0) {
        return wc_get_rating_html($average, $rating_count);
    }
    
    return '<div class="star-rating"><span class="text-gray-300 text-sm">No reviews yet</span></div>';
}

/**
 * Check if user is on mobile device
 */
function tostishop_is_mobile() {
    return wp_is_mobile();
}

/**
 * Get social media links
 */
function tostishop_get_social_links() {
    return array(
        'facebook' => get_theme_mod('facebook_url', ''),
        'instagram' => get_theme_mod('instagram_url', ''),
        'twitter' => get_theme_mod('twitter_url', ''),
        'youtube' => get_theme_mod('youtube_url', ''),
        'linkedin' => get_theme_mod('linkedin_url', ''),
    );
}

/**
 * Generate breadcrumb structure
 */
function tostishop_get_breadcrumbs() {
    $breadcrumbs = array();
    
    // Home
    $breadcrumbs[] = array(
        'title' => __('Home', 'tostishop'),
        'url' => home_url('/')
    );
    
    if (is_shop()) {
        $breadcrumbs[] = array(
            'title' => __('Shop', 'tostishop'),
            'url' => ''
        );
    } elseif (is_product_category()) {
        $breadcrumbs[] = array(
            'title' => __('Shop', 'tostishop'),
            'url' => wc_get_page_permalink('shop')
        );
        $breadcrumbs[] = array(
            'title' => single_cat_title('', false),
            'url' => ''
        );
    } elseif (is_product()) {
        $breadcrumbs[] = array(
            'title' => __('Shop', 'tostishop'),
            'url' => wc_get_page_permalink('shop')
        );
        $breadcrumbs[] = array(
            'title' => get_the_title(),
            'url' => ''
        );
    }
    
    return $breadcrumbs;
}

/**
 * Get theme color scheme
 */
function tostishop_get_color_scheme() {
    return array(
        'primary' => get_theme_mod('primary_color', '#14175b'),
        'accent' => get_theme_mod('accent_color', '#e42029'),
        'silver' => '#ecebee'
    );
}

/**
 * Format date for display
 */
function tostishop_format_date($date, $format = 'F j, Y') {
    if (empty($date)) {
        return '';
    }
    
    if (is_string($date)) {
        $date = strtotime($date);
    }
    
    return date_i18n($format, $date);
}

/**
 * Truncate text with proper word break
 */
function tostishop_truncate_text($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . $suffix;
}

/**
 * Get page loading time
 */
function tostishop_get_page_load_time() {
    return timer_stop();
}

/**
 * Check if WooCommerce is active
 */
function tostishop_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Get cart total
 */
function tostishop_get_cart_total() {
    if (!tostishop_is_woocommerce_active()) {
        return '';
    }
    
    return WC()->cart->get_cart_total();
}

/**
 * Check if current page is related to WooCommerce
 */
function tostishop_is_woocommerce_page() {
    if (!tostishop_is_woocommerce_active()) {
        return false;
    }
    
    return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
}

/**
 * Get theme version
 */
function tostishop_get_theme_version() {
    $theme = wp_get_theme();
    return $theme->get('Version');
}

/**
 * Auto-setup theme logo on activation
 */
function tostishop_setup_logo() {
    $logo_path = get_template_directory() . '/assets/images/logo.png';
    
    if (file_exists($logo_path) && !get_theme_mod('custom_logo')) {
        $logo_attachment_id = tostishop_upload_logo();
        if ($logo_attachment_id) {
            set_theme_mod('custom_logo', $logo_attachment_id);
        }
    }
}

/**
 * Calculate discount percentage for products on sale
 */
function tostishop_get_discount_percentage($product) {
    if (!$product || !$product->is_on_sale()) {
        return false;
    }
    
    $regular_price = (float) $product->get_regular_price();
    $sale_price = (float) $product->get_sale_price();
    
    if ($regular_price <= 0 || $sale_price <= 0) {
        return false;
    }
    
    $discount = (($regular_price - $sale_price) / $regular_price) * 100;
    return round($discount);
}

/**
 * Get formatted discount percentage display
 */
function tostishop_get_discount_display($product, $show_text = true) {
    $discount = tostishop_get_discount_percentage($product);
    
    if (!$discount) {
        return '';
    }
    
    $text = $show_text ? __(' Off', 'tostishop') : '';
    return sprintf('-%d%%%s', $discount, $text);
}

/**
 * Debug function for development
 */
function tostishop_debug($data, $label = '') {
    if (!defined('WP_DEBUG') || !WP_DEBUG) {
        return;
    }
    
    $output = $label ? $label . ': ' : '';
    $output .= print_r($data, true);
    
    error_log($output);
}
