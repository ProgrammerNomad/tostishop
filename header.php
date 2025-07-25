<!DOCTYPE html>
<html <?php language_attributes(); ?> class="h-full">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#3b82f6">
    <?php wp_head(); ?>
</head>
<body <?php body_class('h-full bg-white'); ?> x-data="{ mobileMenuOpen: false, cartOpen: false }">

<!-- Mobile Menu Overlay -->
<div x-show="mobileMenuOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 lg:hidden">
    <div class="fixed inset-0 bg-black bg-opacity-25" @click="mobileMenuOpen = false"></div>
</div>

<!-- Mobile Menu -->
<div x-show="mobileMenuOpen"
     x-transition:enter="transition ease-in-out duration-300 transform"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in-out duration-300 transform"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="fixed top-0 left-0 z-50 w-64 h-full bg-white shadow-lg lg:hidden">
    
    <div class="flex items-center justify-between p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-900"><?php _e('Menu', 'tostishop'); ?></h2>
        <button @click="mobileMenuOpen = false" class="p-2 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    
    <nav class="p-4">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'mobile',
            'menu_class' => 'space-y-2',
            'container' => false,
            'fallback_cb' => false,
            'link_before' => '<span class="block px-3 py-2 text-base font-medium text-gray-900 hover:text-navy-600 hover:bg-gray-50 rounded-md transition-colors duration-200">',
            'link_after' => '</span>',
        ));
        ?>
    </nav>
</div>

<!-- Header -->
<header class="sticky top-0 z-30 bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <!-- Mobile menu button -->
            <button @click="mobileMenuOpen = true" class="p-2 text-gray-600 lg:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <!-- Logo -->
            <div class="flex-shrink-0">
                <?php if (has_custom_logo()) : ?>
                    <div class="custom-logo">
                        <?php 
                        $custom_logo_id = get_theme_mod('custom_logo');
                        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                        if ($logo) : ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center">
                                <img src="<?php echo esc_url($logo[0]); ?>" 
                                     alt="<?php echo esc_attr(get_bloginfo('name')); ?>" 
                                     class="w-32 md:w-48 xl:w-52 h-auto max-h-12 md:max-h-16"
                                     style="width: 200px; max-width: 200px;">
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center">
                        <span class="text-xl md:text-2xl font-bold text-primary">
                            <?php bloginfo('name'); ?>
                        </span>
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex lg:space-x-8">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'flex space-x-8',
                    'container' => false,
                    'fallback_cb' => false,
                    'link_before' => '<span class="text-sm font-medium text-gray-700 hover:text-navy-600 transition-colors duration-200">',
                    'link_after' => '</span>',
                ));
                ?>
            </nav>
            
            <!-- Search & Cart -->
            <div class="flex items-center space-x-4">
                <!-- Search -->
                <div class="hidden sm:block">
                    <?php if (function_exists('get_product_search_form')) : ?>
                        <div class="relative">
                            <input type="search" 
                                   placeholder="<?php _e('Search products...', 'tostishop'); ?>"
                                   class="w-64 pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Cart -->
                <?php if (function_exists('WC')) : ?>
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" 
                   class="relative p-2 text-gray-600 hover:text-navy-600 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <?php $cart_count = tostishop_cart_count(); ?>
                    <?php if ($cart_count > 0) : ?>
                    <span class="absolute -top-2 -right-2 bg-accent text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        <?php echo $cart_count; ?>
                    </span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<main id="main" class="min-h-screen">
