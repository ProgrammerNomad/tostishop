<?php
/**
 * TostiShop Modern Shop Sidebar Filters
 * Amazon-style compact filters for shop, archive, and category pages
 * 
 * @package TostiShop
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display modern shop sidebar with filters
 */
function tostishop_shop_sidebar() {
    if (!is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    ?>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-4">
        <!-- Sidebar Header -->
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                </svg>
                <?php _e('Filters', 'tostishop'); ?>
            </h3>
            <?php if (tostishop_has_active_filters()) : ?>
                <button onclick="tostishopClearFilters()" class="mt-2 text-sm text-accent hover:text-red-700 font-medium">
                    <?php _e('Clear All Filters', 'tostishop'); ?>
                </button>
            <?php endif; ?>
        </div>

        <!-- Filter Sections -->
        <div class="divide-y divide-gray-200">
            
            <!-- Price Filter -->
            <?php 
            $price_ranges = tostishop_get_price_ranges_with_counts();
            if (!empty($price_ranges)) : 
            ?>
            <div class="p-4" x-data="{ open: true }">
                <button @click="open = !open" class="w-full flex items-center justify-between text-left">
                    <h4 class="font-medium text-gray-900"><?php _e('Price Range', 'tostishop'); ?></h4>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': !open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-screen"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 max-h-screen"
                     x-transition:leave-end="opacity-0 max-h-0"
                     class="mt-3 space-y-2 overflow-hidden">
                    <?php foreach ($price_ranges as $range) : ?>
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" 
                                   name="price_range[]" 
                                   value="<?php echo esc_attr($range['min'] . '-' . $range['max']); ?>"
                                   <?php echo tostishop_is_price_range_active($range['min'], $range['max']) ? 'checked' : ''; ?>
                                   onchange="tostishopFilterByPrice(this)"
                                   class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent focus:ring-2">
                            <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900 flex-1">
                                <?php echo $range['label']; ?>
                            </span>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                <?php echo $range['count']; ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Categories Filter -->
            <?php 
            $categories = tostishop_get_product_categories_with_counts();
            if (!empty($categories) && !is_product_category()) : 
            ?>
            <div class="p-4" x-data="{ open: true }">
                <button @click="open = !open" class="w-full flex items-center justify-between text-left">
                    <h4 class="font-medium text-gray-900"><?php _e('Categories', 'tostishop'); ?></h4>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': !open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-screen" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 max-h-screen" x-transition:leave-end="opacity-0 max-h-0" class="mt-3 space-y-2 overflow-hidden">
                    <?php foreach ($categories as $category) : ?>
                        <a href="<?php echo get_term_link($category); ?>" 
                           class="flex items-center justify-between py-1 text-sm text-gray-700 hover:text-accent transition-colors">
                            <span><?php echo esc_html($category->name); ?></span>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                <?php echo $category->count; ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Brands Filter -->
            <?php 
            $brands = tostishop_get_product_brands_with_counts();
            if (!empty($brands)) : 
            ?>
            <div class="p-4" x-data="{ open: true }">
                <button @click="open = !open" class="w-full flex items-center justify-between text-left">
                    <h4 class="font-medium text-gray-900"><?php _e('Brands', 'tostishop'); ?></h4>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': !open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-screen" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 max-h-screen" x-transition:leave-end="opacity-0 max-h-0" class="mt-3 space-y-2 overflow-hidden">
                    <?php foreach ($brands as $brand) : ?>
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" 
                                   name="brand[]" 
                                   value="<?php echo esc_attr($brand->slug); ?>"
                                   <?php echo tostishop_is_brand_active($brand->slug) ? 'checked' : ''; ?>
                                   onchange="tostishopFilterByBrand(this)"
                                   class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent focus:ring-2">
                            <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900 flex-1">
                                <?php echo esc_html($brand->name); ?>
                            </span>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                <?php echo $brand->count; ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Ratings Filter -->
            <div class="p-4" x-data="{ open: true }">
                <button @click="open = !open" class="w-full flex items-center justify-between text-left">
                    <h4 class="font-medium text-gray-900"><?php _e('Customer Rating', 'tostishop'); ?></h4>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': !open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-screen" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 max-h-screen" x-transition:leave-end="opacity-0 max-h-0" class="mt-3 space-y-2 overflow-hidden">
                    <?php for ($i = 4; $i >= 1; $i--) : ?>
                        <label class="flex items-center cursor-pointer group">
                            <input type="radio" 
                                   name="rating" 
                                   value="<?php echo $i; ?>"
                                   <?php echo (isset($_GET['rating']) && $_GET['rating'] == $i) ? 'checked' : ''; ?>
                                   onchange="tostishopFilterByRating(this)"
                                   class="w-4 h-4 text-accent border-gray-300 focus:ring-accent focus:ring-2">
                            <span class="ml-2 flex items-center">
                                <?php for ($j = 1; $j <= 5; $j++) : ?>
                                    <svg class="w-4 h-4 <?php echo ($j <= $i) ? 'text-yellow-400' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                <?php endfor; ?>
                                <span class="ml-1 text-sm text-gray-600"><?php _e('& Up', 'tostishop'); ?></span>
                            </span>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Availability Filter -->
            <div class="p-4" x-data="{ open: true }">
                <button @click="open = !open" class="w-full flex items-center justify-between text-left">
                    <h4 class="font-medium text-gray-900"><?php _e('Availability', 'tostishop'); ?></h4>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': !open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-screen" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 max-h-screen" x-transition:leave-end="opacity-0 max-h-0" class="mt-3 space-y-2 overflow-hidden">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" 
                               name="in_stock" 
                               value="1"
                               <?php echo isset($_GET['in_stock']) ? 'checked' : ''; ?>
                               onchange="tostishopFilterByStock(this)"
                               class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent focus:ring-2">
                        <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900">
                            <?php _e('In Stock Only', 'tostishop'); ?>
                        </span>
                    </label>
                    
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" 
                               name="on_sale" 
                               value="1"
                               <?php echo isset($_GET['on_sale']) ? 'checked' : ''; ?>
                               onchange="tostishopFilterBySale(this)"
                               class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent focus:ring-2">
                        <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900">
                            <?php _e('On Sale', 'tostishop'); ?>
                        </span>
                    </label>
                </div>
            </div>

        </div>
    </div>

    <!-- Filter JavaScript -->
    <script>
    function tostishopFilterByPrice(checkbox) {
        const url = new URL(window.location);
        const priceRanges = Array.from(document.querySelectorAll('input[name="price_range[]"]:checked')).map(cb => cb.value);
        
        if (priceRanges.length > 0) {
            url.searchParams.set('price_range', priceRanges.join(','));
        } else {
            url.searchParams.delete('price_range');
        }
        
        window.location.href = url.toString();
    }

    function tostishopFilterByBrand(checkbox) {
        const url = new URL(window.location);
        const brands = Array.from(document.querySelectorAll('input[name="brand[]"]:checked')).map(cb => cb.value);
        
        if (brands.length > 0) {
            url.searchParams.set('brand', brands.join(','));
        } else {
            url.searchParams.delete('brand');
        }
        
        window.location.href = url.toString();
    }

    function tostishopFilterByRating(radio) {
        const url = new URL(window.location);
        url.searchParams.set('rating', radio.value);
        window.location.href = url.toString();
    }

    function tostishopFilterByStock(checkbox) {
        const url = new URL(window.location);
        if (checkbox.checked) {
            url.searchParams.set('in_stock', '1');
        } else {
            url.searchParams.delete('in_stock');
        }
        window.location.href = url.toString();
    }

    function tostishopFilterBySale(checkbox) {
        const url = new URL(window.location);
        if (checkbox.checked) {
            url.searchParams.set('on_sale', '1');
        } else {
            url.searchParams.delete('on_sale');
        }
        window.location.href = url.toString();
    }

    function tostishopClearFilters() {
        const url = new URL(window.location);
        url.searchParams.delete('price_range');
        url.searchParams.delete('brand');
        url.searchParams.delete('rating');
        url.searchParams.delete('in_stock');
        url.searchParams.delete('on_sale');
        window.location.href = url.toString();
    }
    </script>
    
    <?php
}

/**
 * Test function to verify price ranges work correctly
 * Can be called from WordPress admin or debug mode
 */
function tostishop_test_price_ranges() {
    if (!function_exists('wc_get_products')) {
        return array('error' => 'WooCommerce not active');
    }
    
    $price_ranges = tostishop_get_price_ranges_with_counts();
    
    // Debug output
    $debug_info = array(
        'total_products' => count(wc_get_products(array('status' => 'publish', 'limit' => -1))),
        'price_ranges' => $price_ranges,
        'timestamp' => current_time('mysql')
    );
    
    return $debug_info;
}

/**
 * Get price ranges with product counts - only show ranges that have products
 */
function tostishop_get_price_ranges_with_counts() {
    global $wpdb;
    
    $price_ranges = array();
    
    // Define potential price ranges
    $ranges = array(
        array('min' => 0, 'max' => 500, 'label' => __('Under ₹500', 'tostishop')),
        array('min' => 500, 'max' => 1000, 'label' => __('₹500 - ₹1,000', 'tostishop')),
        array('min' => 1000, 'max' => 2000, 'label' => __('₹1,000 - ₹2,000', 'tostishop')),
        array('min' => 2000, 'max' => 3000, 'label' => __('₹2,000 - ₹3,000', 'tostishop')),
        array('min' => 3000, 'max' => 999999, 'label' => __('Over ₹3,000', 'tostishop')),
    );

    // Get current category if on category page
    $current_category = '';
    if (is_product_category()) {
        $current_category = get_queried_object_id();
    }

    // Build query for each range to check if products exist
    foreach ($ranges as $range) {
        $query = "
            SELECT COUNT(DISTINCT p.ID) as count
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = 'product'
            AND p.post_status = 'publish'
            AND pm.meta_key = '_price'
            AND CAST(pm.meta_value AS DECIMAL(10,2)) >= %f
        ";
        
        $params = array($range['min']);
        
        if ($range['max'] < 999999) {
            $query .= " AND CAST(pm.meta_value AS DECIMAL(10,2)) <= %f";
            $params[] = $range['max'];
        }

        // Add category filter if on category page
        if ($current_category) {
            $query .= " AND p.ID IN (
                SELECT object_id FROM {$wpdb->term_relationships} tr
                INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                WHERE tt.term_id = %d AND tt.taxonomy = 'product_cat'
            )";
            $params[] = $current_category;
        }

        $count = $wpdb->get_var($wpdb->prepare($query, $params));
        
        if ($count > 0) {
            $range['count'] = $count;
            $price_ranges[] = $range;
        }
    }

    return $price_ranges;
}

/**
 * Get product categories with counts
 */
function tostishop_get_product_categories_with_counts() {
    $categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'orderby' => 'count',
        'order' => 'DESC',
        'number' => 10,
    ));

    return is_wp_error($categories) ? array() : $categories;
}

/**
 * Get product brands with counts (assuming you have a brand taxonomy)
 */
function tostishop_get_product_brands_with_counts() {
    // Check if brand taxonomy exists
    if (!taxonomy_exists('product_brand')) {
        return array();
    }

    $brands = get_terms(array(
        'taxonomy' => 'product_brand',
        'hide_empty' => true,
        'orderby' => 'count',
        'order' => 'DESC',
        'number' => 10,
    ));

    return is_wp_error($brands) ? array() : $brands;
}

/**
 * Check if a price range is currently active
 */
function tostishop_is_price_range_active($min, $max) {
    if (!isset($_GET['price_range'])) {
        return false;
    }

    $active_ranges = explode(',', $_GET['price_range']);
    $current_range = $min . '-' . $max;
    
    return in_array($current_range, $active_ranges);
}

/**
 * Check if a brand is currently active
 */
function tostishop_is_brand_active($brand_slug) {
    if (!isset($_GET['brand'])) {
        return false;
    }

    $active_brands = explode(',', $_GET['brand']);
    return in_array($brand_slug, $active_brands);
}

/**
 * Check if any filters are currently active
 */
function tostishop_has_active_filters() {
    return isset($_GET['price_range']) || isset($_GET['brand']) || isset($_GET['rating']) || isset($_GET['in_stock']) || isset($_GET['on_sale']);
}

/**
 * Apply filters to WooCommerce product query
 */
function tostishop_apply_product_filters($query) {
    if (!is_admin() && $query->is_main_query() && (is_shop() || is_product_category() || is_product_tag())) {
        
        $meta_query = $query->get('meta_query') ?: array();
        $tax_query = $query->get('tax_query') ?: array();

        // Price range filter
        if (isset($_GET['price_range'])) {
            $price_ranges = explode(',', $_GET['price_range']);
            $price_conditions = array();
            
            foreach ($price_ranges as $range) {
                list($min, $max) = explode('-', $range);
                
                $condition = array(
                    'key' => '_price',
                    'value' => array($min, $max == '999999' ? 999999 : $max),
                    'compare' => 'BETWEEN',
                    'type' => 'DECIMAL(10,2)'
                );
                
                $price_conditions[] = $condition;
            }
            
            if (count($price_conditions) > 1) {
                $price_conditions['relation'] = 'OR';
            }
            
            $meta_query[] = $price_conditions;
        }

        // Stock filter
        if (isset($_GET['in_stock'])) {
            $meta_query[] = array(
                'key' => '_stock_status',
                'value' => 'instock',
                'compare' => '='
            );
        }

        // Sale filter
        if (isset($_GET['on_sale'])) {
            $meta_query[] = array(
                'key' => '_sale_price',
                'value' => '',
                'compare' => '!='
            );
        }

        // Brand filter
        if (isset($_GET['brand']) && taxonomy_exists('product_brand')) {
            $brands = explode(',', $_GET['brand']);
            $tax_query[] = array(
                'taxonomy' => 'product_brand',
                'field' => 'slug',
                'terms' => $brands,
                'operator' => 'IN'
            );
        }

        // Rating filter
        if (isset($_GET['rating'])) {
            $rating = intval($_GET['rating']);
            $meta_query[] = array(
                'key' => '_wc_average_rating',
                'value' => $rating,
                'compare' => '>=',
                'type' => 'DECIMAL'
            );
        }

        $query->set('meta_query', $meta_query);
        $query->set('tax_query', $tax_query);
    }
}
add_action('pre_get_posts', 'tostishop_apply_product_filters');
