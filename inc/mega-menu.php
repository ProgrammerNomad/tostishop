<?php
/**
 * Mega Menu Component
 * Displays categories and subcategories in a mega menu format
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate mega menu HTML
 */
function tostishop_mega_menu() {
    // Get all product categories with products
    $categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => 0, // Only top-level categories
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ));

    if (empty($categories) || is_wp_error($categories)) {
        return;
    }

    ob_start();
    ?>
    
    <!-- Desktop Mega Menu -->
    <nav class="hidden lg:block relative" x-data="{ activeMenu: null }" @mouseleave="activeMenu = null">
        <div class="flex space-x-8">
            
            <?php foreach ($categories as $category) : 
                $subcategories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                    'parent' => $category->term_id,
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ));
                
                $has_subcategories = !empty($subcategories) && !is_wp_error($subcategories);
                $category_url = get_term_link($category);
                ?>
                
                <div class="relative">
                    <?php if ($has_subcategories) : ?>
                        <button @mouseenter="activeMenu = '<?php echo esc_attr($category->slug); ?>'" 
                                class="flex items-center text-sm font-medium text-gray-700 hover:text-navy-600 transition-colors duration-200 py-2 group">
                            <?php echo esc_html($category->name); ?>
                            <svg class="w-3 h-3 ml-1 transition-transform duration-200 group-hover:rotate-180" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    <?php else : ?>
                        <a href="<?php echo esc_url($category_url); ?>" 
                           class="flex items-center text-sm font-medium text-gray-700 hover:text-navy-600 transition-colors duration-200 py-2">
                            <?php echo esc_html($category->name); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($has_subcategories) : ?>
                        <!-- Mega Menu Dropdown -->
                        <div x-show="activeMenu === '<?php echo esc_attr($category->slug); ?>'" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute left-0 top-full mt-1 w-80 bg-white rounded-xl shadow-xl border border-gray-200 z-50 overflow-hidden">
                            
                            <!-- Category Header -->
                            <div class="bg-gradient-to-r from-navy-900 to-blue-600 px-6 py-4">
                                <h3 class="text-white font-semibold text-lg">
                                    <?php echo esc_html($category->name); ?>
                                </h3>
                                <p class="text-blue-100 text-sm mt-1">
                                    <?php printf(_n('%d product', '%d products', $category->count, 'tostishop'), $category->count); ?>
                                </p>
                            </div>
                            
                            <!-- Subcategories Grid -->
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- View All Category -->
                                    <a href="<?php echo esc_url($category_url); ?>" 
                                       class="group block p-3 rounded-lg hover:bg-gray-50 transition-all duration-200 border border-transparent hover:border-gray-200">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-navy-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-navy-200 transition-colors">
                                                <svg class="w-4 h-4 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900 group-hover:text-navy-600">
                                                    <?php _e('View All', 'tostishop'); ?>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <?php printf(__('%d items', 'tostishop'), $category->count); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    
                                    <?php foreach ($subcategories as $subcategory) : 
                                        $subcat_url = get_term_link($subcategory);
                                        $thumbnail_id = get_term_meta($subcategory->term_id, 'thumbnail_id', true);
                                        $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
                                        ?>
                                        
                                        <a href="<?php echo esc_url($subcat_url); ?>" 
                                           class="group block p-3 rounded-lg hover:bg-gray-50 transition-all duration-200 border border-transparent hover:border-gray-200">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gray-100 rounded-lg overflow-hidden mr-3 flex-shrink-0">
                                                    <?php if ($image_url) : ?>
                                                        <img src="<?php echo esc_url($image_url); ?>" 
                                                             alt="<?php echo esc_attr($subcategory->name); ?>"
                                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-200">
                                                    <?php else : ?>
                                                        <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                            </svg>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="text-sm font-medium text-gray-900 group-hover:text-navy-600 transition-colors truncate">
                                                        <?php echo esc_html($subcategory->name); ?>
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        <?php printf(_n('%d item', '%d items', $subcategory->count, 'tostishop'), $subcategory->count); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Featured Products (Optional) -->
                                <?php 
                                $featured_products = wc_get_featured_product_ids();
                                if (!empty($featured_products)) :
                                    $category_products = get_posts(array(
                                        'post_type' => 'product',
                                        'posts_per_page' => 2,
                                        'post__in' => $featured_products,
                                        'tax_query' => array(
                                            array(
                                                'taxonomy' => 'product_cat',
                                                'field' => 'term_id',
                                                'terms' => $category->term_id,
                                            ),
                                        ),
                                    ));
                                    
                                    if (!empty($category_products)) :
                                ?>
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">
                                        <?php _e('Featured Products', 'tostishop'); ?>
                                    </h4>
                                    <div class="space-y-3">
                                        <?php foreach ($category_products as $product_post) : 
                                            $product = wc_get_product($product_post->ID);
                                            if (!$product) continue;
                                            ?>
                                            <a href="<?php echo esc_url($product->get_permalink()); ?>" 
                                               class="flex items-center group">
                                                <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden mr-3">
                                                    <?php echo $product->get_image('thumbnail'); ?>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-medium text-gray-900 group-hover:text-navy-600 transition-colors truncate">
                                                        <?php echo esc_html($product->get_name()); ?>
                                                    </div>
                                                    <div class="text-sm text-accent font-semibold">
                                                        <?php echo $product->get_price_html(); ?>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
            <?php endforeach; ?>
        </div>
    </nav>
    
    <?php
    return ob_get_clean();
}

/**
 * Mobile menu for categories
 */
function tostishop_mobile_categories_menu() {
    $categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => 0,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ));

    if (empty($categories) || is_wp_error($categories)) {
        return;
    }

    ob_start();
    ?>
    
    <div class="space-y-1" x-data="{ openCategory: null }">
        
        <?php foreach ($categories as $category) : 
            $subcategories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'parent' => $category->term_id,
                'orderby' => 'menu_order',
                'order' => 'ASC'
            ));
            
            $has_subcategories = !empty($subcategories) && !is_wp_error($subcategories);
            $category_url = get_term_link($category);
            ?>
            
            <div class="space-y-1">
                <?php if ($has_subcategories) : ?>
                    <!-- Category with subcategories -->
                    <button @click="openCategory = openCategory === '<?php echo esc_attr($category->slug); ?>' ? null : '<?php echo esc_attr($category->slug); ?>'" 
                            class="w-full flex items-center justify-between px-3 py-2 text-base font-medium text-gray-900 hover:text-navy-600 hover:bg-gray-50 rounded-md transition-colors duration-200">
                        <span><?php echo esc_html($category->name); ?></span>
                        <svg class="w-4 h-4 transition-transform duration-200" 
                             :class="{ 'rotate-90': openCategory === '<?php echo esc_attr($category->slug); ?>' }" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    
                    <!-- Subcategories -->
                    <div x-show="openCategory === '<?php echo esc_attr($category->slug); ?>'" 
                         x-collapse 
                         class="ml-4 space-y-1">
                        <!-- View All -->
                        <a href="<?php echo esc_url($category_url); ?>" 
                           class="block px-3 py-2 text-sm text-gray-700 hover:text-navy-600 hover:bg-gray-50 rounded-md transition-colors duration-200">
                            <?php _e('View All', 'tostishop'); ?> <?php echo esc_html($category->name); ?>
                        </a>
                        
                        <?php foreach ($subcategories as $subcategory) : 
                            $subcat_url = get_term_link($subcategory);
                            ?>
                            <a href="<?php echo esc_url($subcat_url); ?>" 
                               class="block px-3 py-2 text-sm text-gray-700 hover:text-navy-600 hover:bg-gray-50 rounded-md transition-colors duration-200">
                                <?php echo esc_html($subcategory->name); ?>
                                <span class="text-xs text-gray-500">
                                    (<?php echo $subcategory->count; ?>)
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    
                <?php else : ?>
                    <!-- Direct category link -->
                    <a href="<?php echo esc_url($category_url); ?>" 
                       class="block px-3 py-2 text-base font-medium text-gray-900 hover:text-navy-600 hover:bg-gray-50 rounded-md transition-colors duration-200">
                        <?php echo esc_html($category->name); ?>
                    </a>
                <?php endif; ?>
            </div>
            
        <?php endforeach; ?>
    </div>
    
    <?php
    return ob_get_clean();
}
