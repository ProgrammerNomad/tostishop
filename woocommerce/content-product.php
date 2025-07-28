<?php
/**
 * The template for displaying product content within loops
 * Following TostiShop coding guidelines with unified badge system
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility
if (empty($product) || !$product->is_visible()) {
    return;
}

// Determine if we should show % OFF badges (centralized logic)
$show_percentage_badge = (
    is_shop() || 
    is_product_category() || 
    is_product_tag() ||
    is_woocommerce() ||  // Add this for broader WooCommerce coverage
    ( isset( $woocommerce_loop['is_homepage'] ) && $woocommerce_loop['is_homepage'] )
);

// Calculate discount percentage once
$discount_percentage = 0;
if ( $product->is_on_sale() ) {
    $regular_price = (float) $product->get_regular_price();
    $sale_price = (float) $product->get_sale_price();
    
    if ( $regular_price > 0 && $sale_price > 0 ) {
        $discount_percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
    }
}
?>

<div <?php 
    wc_product_class(
        'product-item group bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200', 
        $product
    ); 
    ?>
    <?php if ( $discount_percentage > 0 ) : ?>
        data-discount="<?php echo esc_attr( $discount_percentage ); ?>"
    <?php endif; ?>
>
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
        
        <!-- Enhanced Sale Badge Logic -->
        <?php if ( $product->is_on_sale() ) : ?>
            <?php if ( $show_percentage_badge && $discount_percentage > 0 ) : ?>
                <!-- % OFF Badge for Shop/Category/Homepage -->
                <div class="discount-badge absolute top-2 left-2 z-20 bg-gradient-to-br from-accent to-red-700 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg border-2 border-white/30 backdrop-blur-sm uppercase tracking-wide">
                    <?php echo esc_html( $discount_percentage ); ?>% OFF
                </div>
            <?php elseif ( !$show_percentage_badge ) : ?>
                <!-- Default "Sale" badge for single product pages -->
                <div class="absolute top-2 left-2 z-10 bg-accent text-white text-xs font-bold px-2 py-1 rounded shadow-md">
                    <?php _e( 'Sale', 'tostishop' ); ?>
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
                <a href="<?php echo esc_url(get_term_link($category)); ?>" 
                   class="hover:text-navy-900 transition-colors duration-200">
                    <?php echo esc_html($category->name); ?>
                </a>
            </p>
        <?php endif; ?>
        
        <!-- Product Title - Clickable -->
        <h3 class="product-title text-sm font-medium text-gray-900 leading-tight">
            <a href="<?php echo esc_url(get_permalink()); ?>" 
               class="line-clamp-2 hover:text-navy-900 transition-colors duration-200">
                <?php the_title(); ?>
            </a>
        </h3>
        
        <!-- Rating Area - Always Show (even if empty) -->
        <div class="rating-area min-h-[20px]">
            <?php if ($product->get_average_rating()) : ?>
                <div class="flex items-center">
                    <div class="flex text-yellow-400">
                        <?php
                        $rating = $product->get_average_rating();
                        for ($i = 1; $i <= 5; $i++) :
                            if ($i <= $rating) : ?>
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
                    <span class="text-xs text-gray-500 ml-1">(<?php echo $product->get_review_count(); ?>)</span>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Price and Stock -->
        <div class="flex items-center justify-between pt-2">
            <div class="text-base font-bold text-navy-900">
                <?php echo $product->get_price_html(); ?>
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
