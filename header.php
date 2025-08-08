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

<?php if (!is_cart() && !is_checkout()) : ?>
<!-- Header -->
<header class="sticky top-0 z-30 bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
<?php else : ?>
<!-- Minimal Header for Cart/Checkout -->
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-center h-16">
<?php endif; ?>
            
            <?php if (!is_cart() && !is_checkout()) : ?>
            <!-- Mobile menu button -->
            <button @click="mobileMenuOpen = true" class="p-2 text-gray-600 lg:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <?php endif; ?>
            
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
                                     class="h-auto w-auto max-w-[200px] max-h-12 md:max-h-16">
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
            
            <?php if (!is_cart() && !is_checkout()) : ?>
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
            <?php endif; ?>
            
            <?php if (!is_checkout()) : ?>
            <!-- Search & Cart -->
            <div class="flex items-center space-x-4">
                <!-- Search -->
                <?php if (!is_cart()) : ?>
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
                <?php endif; ?>
                
                <!-- User Account Menu -->
                <?php if (!is_cart()) : ?>
                <div class="relative" x-data="{ userMenuOpen: false }">
                    <?php if (is_user_logged_in()) : ?>
                        <!-- Logged In User - Responsive Design -->
                        <button @click="userMenuOpen = !userMenuOpen" 
                                class="flex items-center text-left hover:bg-gray-50 rounded-lg p-2 transition-colors duration-200">
                            <!-- Mobile: Icon Only -->
                            <div class="md:hidden">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <!-- Desktop: Amazon Style Text -->
                            <div class="hidden md:flex flex-col items-start">
                                <div class="text-xs text-gray-600">
                                    <?php printf(__('Hello, %s', 'tostishop'), wp_get_current_user()->display_name); ?>
                                </div>
                                <div class="text-sm font-medium text-gray-900 flex items-center">
                                    <?php _e('Account & Lists', 'tostishop'); ?>
                                    <svg class="w-3 h-3 ml-1 transition-transform duration-200" :class="{ 'rotate-180': userMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="userMenuOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.away="userMenuOpen = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                            
                            <!-- User Info -->
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">
                                    <?php echo esc_html(wp_get_current_user()->display_name); ?>
                                </p>
                                <p class="text-sm text-gray-500">
                                    <?php echo esc_html(wp_get_current_user()->user_email); ?>
                                </p>
                            </div>
                            
                            <!-- Menu Items -->
                            <div class="py-1">
                                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-navy-600 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <?php _e('My Account', 'tostishop'); ?>
                                </a>
                                
                                <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-navy-600 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <?php _e('My Orders', 'tostishop'); ?>
                                </a>
                                
                                <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-address')); ?>" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-navy-600 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <?php _e('Addresses', 'tostishop'); ?>
                                </a>
                                
                                <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-navy-600 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <?php _e('Account Details', 'tostishop'); ?>
                                </a>
                                
                                <div class="border-t border-gray-100 my-1"></div>
                                
                                <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" 
                                   class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <?php _e('Logout', 'tostishop'); ?>
                                </a>
                            </div>
                        </div>
                        
                    <?php else : ?>
                        <!-- Not Logged In - Responsive Design -->
                        <button @click="userMenuOpen = !userMenuOpen" 
                                class="flex items-center text-left hover:bg-gray-50 rounded-lg p-2 transition-colors duration-200">
                            <!-- Mobile: Icon Only -->
                            <div class="md:hidden">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <!-- Desktop: Amazon Style Text -->
                            <div class="hidden md:flex flex-col items-start">
                                <div class="text-xs text-gray-600">
                                    <?php _e('Hello, Sign in', 'tostishop'); ?>
                                </div>
                                <div class="text-sm font-medium text-gray-900 flex items-center">
                                    <?php _e('Account & Lists', 'tostishop'); ?>
                                    <svg class="w-3 h-3 ml-1 transition-transform duration-200" :class="{ 'rotate-180': userMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </button>
                        
                        <!-- Not Logged In Dropdown Menu -->
                        <div x-show="userMenuOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.away="userMenuOpen = false"
                             class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 py-4 z-50">
                            
                            <!-- Login Section -->
                            <div class="px-4 pb-4 border-b border-gray-100">
                                <div class="text-center">
                                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" 
                                       class="block w-full bg-accent text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors duration-200 mb-3">
                                        <?php _e('Sign In', 'tostishop'); ?>
                                    </a>
                                    <p class="text-xs text-gray-600">
                                        <?php _e('New customer?', 'tostishop'); ?>
                                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" 
                                           class="text-accent hover:text-red-600 font-medium">
                                            <?php _e('Start here.', 'tostishop'); ?>
                                        </a>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Quick Links -->
                            <div class="py-2">
                                <h4 class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    <?php _e('Your Account', 'tostishop'); ?>
                                </h4>
                                
                                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-navy-600 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <?php _e('Your Account', 'tostishop'); ?>
                                </a>
                                
                                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-navy-600 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <?php _e('Your Orders', 'tostishop'); ?>
                                </a>
                                
                                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-navy-600 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <?php _e('Your Wishlist', 'tostishop'); ?>
                                </a>
                                
                                <a href="<?php echo esc_url(home_url('/contact')); ?>" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-navy-600 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <?php _e('Customer Service', 'tostishop'); ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Cart -->
                <?php if (function_exists('WC') && !is_cart() && !is_checkout()) : ?>
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" 
                   class="relative p-2 text-gray-600 hover:text-navy-600 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <?php $cart_count = tostishop_cart_count(); ?>
                    <span class="absolute -top-2 -right-2 bg-accent text-white text-xs rounded-full h-5 w-5 flex items-center justify-center <?php echo $cart_count > 0 ? '' : 'hidden'; ?>" 
                          data-cart-count>
                        <?php echo $cart_count; ?>
                    </span>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<main id="main" class="min-h-screen">
