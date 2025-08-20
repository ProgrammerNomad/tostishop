<?php
/**
 * TostiShop Theme Customizer
 * WordPress Customizer settings and controls
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme customizer settings
 */
function tostishop_customize_register($wp_customize) {
    // Hero section
    $wp_customize->add_section('tostishop_hero', array(
        'title' => __('Hero Section', 'tostishop'),
        'priority' => 30,
        'description' => __('Customize the hero section on your homepage.', 'tostishop'),
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
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_image', array(
        'label' => __('Hero Background Image', 'tostishop'),
        'section' => 'tostishop_hero',
        'settings' => 'hero_image',
    )));
    
    // Colors section
    $wp_customize->add_section('tostishop_colors', array(
        'title' => __('TostiShop Colors', 'tostishop'),
        'priority' => 40,
        'description' => __('Customize your brand colors.', 'tostishop'),
    ));
    
    // Primary color
    $wp_customize->add_setting('primary_color', array(
        'default' => '#14175b',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', array(
        'label' => __('Primary Color (Navy)', 'tostishop'),
        'section' => 'tostishop_colors',
        'settings' => 'primary_color',
    )));
    
    // Accent color
    $wp_customize->add_setting('accent_color', array(
        'default' => '#e42029',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color', array(
        'label' => __('Accent Color (Red)', 'tostishop'),
        'section' => 'tostishop_colors',
        'settings' => 'accent_color',
    )));
    
    // Typography section
    $wp_customize->add_section('tostishop_typography', array(
        'title' => __('Typography', 'tostishop'),
        'priority' => 50,
        'description' => __('Customize your typography settings.', 'tostishop'),
    ));
    
    // Body font
    $wp_customize->add_setting('body_font', array(
        'default' => 'Inter',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('body_font', array(
        'label' => __('Body Font', 'tostishop'),
        'section' => 'tostishop_typography',
        'type' => 'select',
        'choices' => array(
            'Inter' => 'Inter',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Poppins' => 'Poppins',
        ),
    ));
    
    // Footer section
    $wp_customize->add_section('tostishop_footer', array(
        'title' => __('Footer Settings', 'tostishop'),
        'priority' => 60,
        'description' => __('Customize your footer content.', 'tostishop'),
    ));
    
    $wp_customize->add_setting('footer_text', array(
        'default' => __('© 2024 TostiShop. All rights reserved.', 'tostishop'),
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('footer_text', array(
        'label' => __('Footer Copyright Text', 'tostishop'),
        'section' => 'tostishop_footer',
        'type' => 'textarea',
    ));
}
add_action('customize_register', 'tostishop_customize_register');

/**
 * Enhanced Pagination Function
 */
function tostishop_enhanced_pagination() {
    global $wp_query;
    
    $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
    $max_pages = $wp_query->max_num_pages;
    
    if ($max_pages <= 1) {
        return;
    }
    
    echo '<nav class="tostishop-pagination" role="navigation" aria-label="' . __('Pagination Navigation', 'tostishop') . '">';
    echo '<div class="pagination-wrapper flex flex-wrap justify-center items-center gap-2">';
    
    // Previous button
    if ($paged > 1) {
        echo '<a href="' . esc_url(get_pagenum_link($paged - 1)) . '" class="btn-secondary">Previous</a>';
        echo '<span class="sr-only">' . __('Previous', 'tostishop') . '</span>';
        echo '</a>';
    }
    
    // Page numbers
    $range = 2; // Number of pages to show on each side
    $start = max(1, $paged - $range);
    $end = min($max_pages, $paged + $range);
    
    // First page + dots
    if ($start > 1) {
        echo '<a href="' . esc_url(get_pagenum_link(1)) . '" class="btn-secondary">1</a>';
        if ($start > 2) {
            echo '<span class="pagination-dots">...</span>';
        }
    }
    
    // Page range
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $paged) {
            echo '<span class="btn-primary current">' . $i . '</span>';
        } else {
            echo '<a href="' . esc_url(get_pagenum_link($i)) . '" class="btn-secondary">' . $i . '</a>';
        }
    }
    
    // Last page + dots
    if ($end < $max_pages) {
        if ($end < $max_pages - 1) {
            echo '<span class="pagination-dots">...</span>';
        }
        echo '<a href="' . esc_url(get_pagenum_link($max_pages)) . '" class="btn-secondary">' . $max_pages . '</a>';
    }
    
    // Next button
    if ($paged < $max_pages) {
        echo '<a href="' . esc_url(get_pagenum_link($paged + 1)) . '" class="btn-secondary">Next</a>';
        echo '<span class="sr-only">' . __('Next', 'tostishop') . '</span>';
        echo '</a>';
    }
    
    echo '</div>';
    echo '</nav>';
}
