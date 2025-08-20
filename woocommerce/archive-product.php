<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header(); ?>

<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ mobileFiltersOpen: false }">
    
    <!-- Breadcrumbs -->
    <?php tostishop_breadcrumbs(); ?>
    
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                <?php woocommerce_page_title(); ?>
            </h1>
            <?php if (term_description()) : ?>
                <p class="text-gray-600 mt-2"><?php echo term_description(); ?></p>
            <?php endif; ?>
        </div>
        
        <!-- View Toggle (Desktop) -->
        <div class="hidden md:flex items-center space-x-4">
            <button id="gridView" class="view-toggle-btn p-2 btn-primary rounded-md transition-all duration-200">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
            </button>
            <button id="listView" class="view-toggle-btn p-2 btn-secondary rounded-md transition-all duration-200">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>
    
    <div class="lg:grid lg:grid-cols-6 lg:gap-8">
        
        <!-- Modern Filters Sidebar (Desktop) -->
        <aside class="hidden lg:block">
            <?php tostishop_shop_sidebar(); ?>
        </aside>
        
        <!-- Mobile Filter Button -->
        <div class="lg:hidden mb-6">
            <button @click="mobileFiltersOpen = true" 
                    class="w-full bg-gray-100 text-gray-700 px-4 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors duration-200">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <?php _e('Filters', 'tostishop'); ?>
            </button>
        </div>
        
        <!-- Main Content -->
        <div class="lg:col-span-5">
            
            <!-- Toolbar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                    <?php if (woocommerce_result_count()) : ?>
                        <span class="text-sm text-gray-600"><?php woocommerce_result_count(); ?></span>
                    <?php endif; ?>
                </div>
                
                <?php if (woocommerce_catalog_ordering()) : ?>
                    <div class="woocommerce-ordering">
                        <?php woocommerce_catalog_ordering(); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Products Grid -->
            <?php if (woocommerce_product_loop()) : ?>
                
                <div id="productGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4 md:gap-6">
                    <?php
                    if (wc_get_loop_prop('is_shortcode')) {
                        woocommerce_product_loop_start();
                    }
                    
                    if (woocommerce_product_loop()) {
                        while (have_posts()) {
                            the_post();
                            wc_get_template_part('content', 'product');
                        }
                    }
                    
                    if (wc_get_loop_prop('is_shortcode')) {
                        woocommerce_product_loop_end();
                    }
                    ?>
                </div>
                
                <!-- Pagination -->
                <div class="mt-16 border-t border-gray-200 pt-8">
                    <div class="flex flex-col items-center">
                        <div class="pagination-wrapper">
                            <?php
                            global $wp_query;
                            $total_pages = $wp_query->max_num_pages;
                            $current_page = max(1, get_query_var('paged'));
                            
                            if ($total_pages > 1) {
                                echo '<nav class="flex items-center justify-center space-x-2" aria-label="Pagination">';
                                
                                // Previous button
                                if ($current_page > 1) {
                                    echo '<a href="' . esc_url(get_pagenum_link($current_page - 1)) . '" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Previous</a>';
                                }
                                
                                // Page numbers
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    if ($i == $current_page) {
                                        echo '<span class="px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-md">' . $i . '</span>';
                                    } else {
                                        echo '<a href="' . esc_url(get_pagenum_link($i)) . '" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">' . $i . '</a>';
                                    }
                                }
                                
                                // Next button
                                if ($current_page < $total_pages) {
                                    echo '<a href="' . esc_url(get_pagenum_link($current_page + 1)) . '" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Next</a>';
                                }
                                
                                echo '</nav>';
                            }
                            ?>
                        </div>
                        <?php if ($total_pages > 1) : ?>
                        <p class="text-sm text-gray-600 mt-4">
                            <?php 
                            printf(
                                __('Page %1$s of %2$s', 'tostishop'),
                                $current_page,
                                $total_pages
                            );
                            ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
                
            <?php else : ?>
                
                <!-- No Products Found -->
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900 mb-2"><?php _e('No products found', 'tostishop'); ?></h3>
                    <p class="text-gray-600 mb-6"><?php _e('Try adjusting your search or filter to find what you\'re looking for.', 'tostishop'); ?></p>
                    <a href="<?php echo esc_url(home_url('/shop')); ?>" 
                       class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                        <?php _e('View All Products', 'tostishop'); ?>
                    </a>
                </div>
                
            <?php endif; ?>
        </div>
    </div>

    <!-- Mobile Filters Modal -->
    <div x-show="mobileFiltersOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="transform translate-x-full"
         x-transition:enter-end="transform translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="transform translate-x-0"
         x-transition:leave-end="transform translate-x-full"
         class="fixed inset-0 z-50 lg:hidden"
         style="display: none;">
        
        <!-- Modal Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="mobileFiltersOpen = false"></div>
        
        <!-- Modal Content -->
        <div class="fixed right-0 top-0 h-full w-80 bg-white shadow-xl overflow-y-auto">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900"><?php _e('Filters', 'tostishop'); ?></h2>
                <button @click="mobileFiltersOpen = false" class="p-2 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Filter Content -->
            <div class="p-4">
                <?php tostishop_shop_sidebar(); ?>
            </div>
        </div>
    </div>

</div>

<?php get_footer(); ?>
