<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility
if (empty($product) || !$product->is_visible()) {
    return;
}
?>

<div class="product-item group" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
    
    <!-- Product Image -->
    <div class="product-image relative bg-gray-100 rounded-lg overflow-hidden mb-4 aspect-square">
        <a href="<?php echo esc_url(get_permalink()); ?>" class="block h-full">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('tostishop-product-thumb', array(
                    'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300',
                    'alt' => get_the_title()
                )); ?>
            <?php else : ?>
                <img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" 
                     alt="<?php echo esc_attr(get_the_title()); ?>" 
                     class="w-full h-full object-cover">
            <?php endif; ?>
        </a>
        
        <!-- Sale Badge -->
        <?php if ($product->is_on_sale()) : ?>
            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                <?php _e('Sale', 'tostishop'); ?>
            </div>
        <?php endif; ?>
        
        <!-- Wishlist Button (if plugin exists) -->
        <button class="absolute top-2 right-2 p-2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full transition-all duration-200 opacity-0 group-hover:opacity-100">
            <svg class="w-4 h-4 text-gray-600 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
        </button>
    </div>
    
    <!-- Product Info -->
    <div class="product-info space-y-2 flex flex-col h-full">
        
        <!-- Category -->
        <?php
        $categories = get_the_terms($product->get_id(), 'product_cat');
        if ($categories && !is_wp_error($categories)) :
            $category = $categories[0];
        ?>
            <p class="text-xs text-gray-500 uppercase tracking-wide">
                <?php echo esc_html($category->name); ?>
            </p>
        <?php endif; ?>
        
        <!-- Product Title - Fixed height for 2 lines -->
        <h3 class="product-title text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors duration-200 h-10 overflow-hidden leading-5">
            <a href="<?php echo esc_url(get_permalink()); ?>" class="line-clamp-2"><?php the_title(); ?></a>
        </h3>
        
        <!-- Rating -->
        <?php if ($product->get_average_rating()) : ?>
            <div class="flex items-center space-x-1">
                <div class="flex text-yellow-400">
                    <?php
                    $rating = $product->get_average_rating();
                    $full_stars = floor($rating);
                    $half_star = $rating - $full_stars >= 0.5;
                    
                    for ($i = 1; $i <= 5; $i++) :
                        if ($i <= $full_stars) : ?>
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        <?php elseif ($half_star && $i == $full_stars + 1) : ?>
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                <defs>
                                    <linearGradient id="half">
                                        <stop offset="50%" stop-color="currentColor"/>
                                        <stop offset="50%" stop-color="#e5e7eb"/>
                                    </linearGradient>
                                </defs>
                                <path fill="url(#half)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        <?php else : ?>
                            <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        <?php endif;
                    endfor; ?>
                </div>
                <span class="text-xs text-gray-500">(<?php echo $product->get_review_count(); ?>)</span>
            </div>
        <?php endif; ?>
        
        <!-- Price and Stock -->
        <div class="flex items-center justify-between">
            <div class="text-lg font-bold text-gray-900">
                <?php echo $product->get_price_html(); ?>
            </div>
            
            <!-- Stock Status -->
            <?php if (!$product->is_in_stock()) : ?>
                <span class="text-xs text-red-600 font-medium">
                    <?php _e('Out of Stock', 'tostishop'); ?>
                </span>
            <?php endif; ?>
        </div>
        
        <!-- Add to Cart Button - Always at bottom -->
        <div class="mt-auto">
            <?php if ($product->is_in_stock()) : ?>
                <?php if ($product->is_type('simple')) : ?>
                    <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors duration-200 add-to-cart-btn"
                            data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                            data-quantity="1">
                        <?php _e('Add to Cart', 'tostishop'); ?>
                    </button>
                <?php else : ?>
                    <a href="<?php echo esc_url(get_permalink()); ?>" 
                       class="block w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md text-sm font-medium text-center hover:bg-gray-200 transition-colors duration-200">
                        <?php _e('Select Options', 'tostishop'); ?>
                    </a>
                <?php endif; ?>
            <?php else : ?>
                <button class="w-full bg-gray-300 text-gray-500 py-2 px-4 rounded-md text-sm font-medium cursor-not-allowed" disabled>
                    <?php _e('Out of Stock', 'tostishop'); ?>
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>
