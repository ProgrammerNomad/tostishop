<?php
/**
 * My Account Wishlist Page
 *
 * @package TostiShop
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

$wishlist_items = isset($wishlist_items) ? $wishlist_items : tostishop_get_user_wishlist();
?>

<div class="wishlist-page">
    <div class="wishlist-header mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2"><?php _e('My Wishlist', 'tostishop'); ?></h2>
        <p class="text-gray-600">
            <?php 
            $count = count($wishlist_items);
            if ($count > 0) {
                printf(
                    _n(
                        'You have %d item in your wishlist',
                        'You have %d items in your wishlist',
                        $count,
                        'tostishop'
                    ),
                    $count
                );
            } else {
                _e('Your wishlist is empty', 'tostishop');
            }
            ?>
        </p>
    </div>

    <?php if (!empty($wishlist_items)) : ?>
        <div class="wishlist-items-container">
            <div class="grid gap-6">
                <?php foreach ($wishlist_items as $item) : 
                    $product = $item['product'];
                    $date_added = $item['date_added'];
                ?>
                    <div class="wishlist-item bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                        <div class="flex flex-col lg:flex-row gap-6">
                            
                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                <div class="w-full lg:w-32 h-48 lg:h-32 bg-gray-100 rounded-lg overflow-hidden">
                                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="block w-full h-full">
                                        <?php
                                        $image_id = $product->get_image_id();
                                        if ($image_id) {
                                            echo wp_get_attachment_image(
                                                $image_id,
                                                'woocommerce_thumbnail',
                                                false,
                                                array(
                                                    'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300',
                                                    'alt' => esc_attr($product->get_name())
                                                )
                                            );
                                        } else {
                                            echo '<div class="w-full h-full bg-gray-200 flex items-center justify-center">';
                                            echo '<span class="text-gray-400 text-sm">' . __('No Image', 'tostishop') . '</span>';
                                            echo '</div>';
                                        }
                                        ?>
                                    </a>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="flex-grow">
                                <div class="flex flex-col lg:flex-row justify-between h-full">
                                    <div class="flex-grow mb-4 lg:mb-0">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                            <a href="<?php echo esc_url($product->get_permalink()); ?>" class="hover:text-accent transition-colors">
                                                <?php echo esc_html($product->get_name()); ?>
                                            </a>
                                        </h3>
                                        
                                        <div class="flex flex-wrap items-center gap-4 mb-3">
                                            <!-- Price -->
                                            <div class="price text-lg font-bold">
                                                <?php echo $product->get_price_html(); ?>
                                            </div>
                                            
                                            <!-- Stock Status -->
                                            <div class="stock-status">
                                                <?php if ($product->is_in_stock()) : ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <?php _e('In Stock', 'tostishop'); ?>
                                                    </span>
                                                <?php else : ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <?php _e('Out of Stock', 'tostishop'); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <!-- Short Description -->
                                        <?php if ($product->get_short_description()) : ?>
                                            <div class="text-sm text-gray-600 mb-3">
                                                <?php echo wp_trim_words($product->get_short_description(), 20); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Date Added -->
                                        <div class="text-xs text-gray-500">
                                            <?php 
                                            printf(
                                                __('Added on %s', 'tostishop'), 
                                                date_i18n(get_option('date_format'), strtotime($date_added))
                                            ); 
                                            ?>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex flex-col lg:items-end gap-3">
                                        <!-- Add to Cart Button -->
                                        <?php if ($product->is_purchasable() && $product->is_in_stock()) : ?>
                                            <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
                                                <?php if ($product->is_type('simple')) : ?>
                                                    <button type="submit" 
                                                            name="add-to-cart" 
                                                            value="<?php echo esc_attr($product->get_id()); ?>" 
                                                            class="inline-flex items-center px-4 py-2 bg-accent text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13h10m-6 6a1 1 0 11-2 0 1 1 0 012 0zm6 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                                        </svg>
                                                        <?php _e('Add to Cart', 'tostishop'); ?>
                                                    </button>
                                                <?php else : ?>
                                                    <a href="<?php echo esc_url($product->get_permalink()); ?>" 
                                                       class="inline-flex items-center px-4 py-2 bg-accent text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                                                        <?php _e('Select Options', 'tostishop'); ?>
                                                    </a>
                                                <?php endif; ?>
                                            </form>
                                        <?php elseif (!$product->is_in_stock()) : ?>
                                            <button disabled class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 font-medium rounded-lg cursor-not-allowed">
                                                <?php _e('Out of Stock', 'tostishop'); ?>
                                            </button>
                                        <?php endif; ?>

                                        <!-- Remove from Wishlist Button -->
                                        <button class="wishlist-btn in-wishlist inline-flex items-center px-3 py-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" 
                                                data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                                                title="<?php esc_attr_e('Remove from Wishlist', 'tostishop'); ?>">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                            <?php _e('Remove', 'tostishop'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Wishlist Actions -->
        <div class="wishlist-actions mt-8 pt-6 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row gap-4 justify-between">
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <?php _e('Continue Shopping', 'tostishop'); ?>
                </a>
                
                <button id="add-all-to-cart" 
                        class="inline-flex items-center px-6 py-3 bg-accent text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13h10m-6 6a1 1 0 11-2 0 1 1 0 012 0zm6 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                    </svg>
                    <?php _e('Add All to Cart', 'tostishop'); ?>
                </button>
            </div>
        </div>

    <?php else : ?>
        <!-- Empty Wishlist -->
        <div class="wishlist-items-container">
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 mb-6 text-gray-300">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2"><?php _e('Your wishlist is empty', 'tostishop'); ?></h3>
                <p class="text-gray-600 mb-6"><?php _e('Start adding products you love to your wishlist!', 'tostishop'); ?></p>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
                   class="inline-flex items-center px-6 py-3 bg-accent text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                    <?php _e('Continue Shopping', 'tostishop'); ?>
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Add All to Cart Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addAllBtn = document.getElementById('add-all-to-cart');
    if (addAllBtn) {
        addAllBtn.addEventListener('click', function() {
            const cartForms = document.querySelectorAll('.wishlist-item form.cart');
            
            if (cartForms.length === 0) return;
            
            // Disable button and show loading
            addAllBtn.disabled = true;
            addAllBtn.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Adding...';
            
            // Submit all forms
            let formsSubmitted = 0;
            cartForms.forEach(form => {
                setTimeout(() => {
                    form.submit();
                    formsSubmitted++;
                    
                    if (formsSubmitted === cartForms.length) {
                        // Redirect to cart after a short delay
                        setTimeout(() => {
                            window.location.href = '<?php echo esc_js(wc_get_cart_url()); ?>';
                        }, 1000);
                    }
                }, formsSubmitted * 200); // Stagger submissions
            });
        });
    }
});
</script>
