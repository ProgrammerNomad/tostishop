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

<div <?php wc_product_class('product-item group bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200', $product); ?>>
    <!-- Product Image (Clickable) -->
    <div class="product-image relative bg-gray-100 rounded-t-lg overflow-hidden mb-4 aspect-square">
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
            <?php 
            $discount_percentage = tostishop_get_discount_percentage($product);
            if ($discount_percentage) : ?>
                <div class="absolute top-2 left-2 bg-accent text-white text-xs font-bold px-2 py-1 rounded" 
                     title="<?php echo esc_attr(sprintf(__('%d%% discount', 'tostishop'), $discount_percentage)); ?>">
                    -<?php echo esc_html($discount_percentage); ?>%
                </div>
            <?php else : ?>
                <div class="absolute top-2 left-2 bg-accent text-white text-xs font-bold px-2 py-1 rounded">
                    <?php _e('Sale', 'tostishop'); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <!-- Product Info -->
    <div class="product-info px-4 pb-4 space-y-3">
        <!-- Category Name -->
        <?php
        $categories = get_the_terms($product->get_id(), 'product_cat');
        if ($categories && !is_wp_error($categories)) :
            $category = $categories[0];
        ?>
            <p class="text-xs text-primary font-medium uppercase tracking-wide">
                <a href="<?php echo esc_url(get_term_link($category)); ?>" class="hover:text-navy-900 transition-colors duration-200"><?php echo esc_html($category->name); ?></a>
            </p>
        <?php endif; ?>
        
        <!-- Product Title - Clickable -->
        <h3 class="product-title text-sm font-medium text-gray-900 leading-tight">
            <a href="<?php echo esc_url(get_permalink()); ?>" class="line-clamp-2 hover:text-navy-900 transition-colors duration-200"><?php the_title(); ?></a>
        </h3>
        
        <!-- Rating Area - Always Show (even if empty) -->
        <div class="rating-area min-h-[20px]">
            <div class="flex items-center">
                <div class="flex text-yellow-400">
                    <?php
                    $rating = $product->get_average_rating();
                    for ($i = 1; $i <= 5; $i++) :
                        if ($rating > 0 && $i <= $rating) : ?>
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        <?php else : ?>
                            <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        <?php endif;
                    endfor; ?>
                </div>
                <?php if ($rating > 0) : ?>
                    <span class="text-xs text-gray-500 ml-1">(<?php echo $product->get_review_count(); ?>)</span>
                <?php else : ?>
                    <span class="text-xs text-gray-400 ml-1"><?php _e('No reviews', 'tostishop'); ?></span>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Price and Stock -->
        <div class="flex items-center justify-between pt-2">
            <div class="flex items-center space-x-2">
                <div class="text-base font-bold text-navy-900">
                    <?php echo $product->get_price_html(); ?>
                </div>
                
                <!-- Discount Percentage Badge -->
                <?php if ($product->is_on_sale()) : ?>
                    <?php $discount_percentage = tostishop_get_discount_percentage($product); ?>
                    <?php if ($discount_percentage) : ?>
                        <span class="text-xs font-bold text-accent bg-red-50 px-2 py-1 rounded-full border border-red-200 shadow-sm hover:bg-red-100 hover:border-red-300 transition-all duration-200"
                              title="<?php echo esc_attr(sprintf(__('Save %d%% on this product', 'tostishop'), $discount_percentage)); ?>">
                            -<?php echo esc_html($discount_percentage); ?>%
                        </span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <!-- Stock Status Badge - Only show if out of stock -->
            <?php if (!$product->is_in_stock()) : ?>
                <span class="text-xs text-red-600 font-medium px-2 py-1 bg-red-50 rounded-full border border-red-200">
                    <?php _e('Out of Stock', 'tostishop'); ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>
