<?php
/**
 * The Template for displaying all single products
 */

get_header(); ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breadcrumbs -->
    <?php tostishop_breadcrumbs(); ?>
    
    <?php while (have_posts()) : the_post(); ?>
        <?php global $product; ?>
        
        <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-start">
            
            <!-- Product Images -->
            <div class="mb-8 lg:mb-0">
                <div class="product-gallery" x-data="{ currentImage: 0, images: [] }">
                    
                    <!-- Main Image -->
                    <div class="mb-4">
                        <div class="bg-gray-100 rounded-lg overflow-hidden aspect-square">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large', array(
                                    'class' => 'w-full h-full object-cover main-product-image',
                                    'id' => 'main-product-image'
                                )); ?>
                            <?php endif; ?>
                            
                            <!-- Gallery Images (initially hidden) -->
                            <?php
                            $attachment_ids = $product->get_gallery_image_ids();
                            foreach ($attachment_ids as $index => $attachment_id) :
                                $image_url = wp_get_attachment_image_url($attachment_id, 'large');
                            ?>
                                <img src="<?php echo esc_url($image_url); ?>" 
                                     alt="<?php echo esc_attr(get_post_meta($attachment_id, '_wp_attachment_image_alt', true)); ?>"
                                     class="w-full h-full object-cover gallery-image"
                                     style="display: none;"
                                     data-gallery-index="<?php echo $index + 1; ?>">
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Thumbnail Navigation -->
                    <?php if ($attachment_ids) : ?>
                    <div class="flex space-x-2 overflow-x-auto">
                        <!-- Main thumbnail -->
                        <button onclick="showGalleryImage(0)" 
                                class="flex-none w-16 h-16 bg-gray-100 rounded border-2 border-blue-500 overflow-hidden thumbnail-btn"
                                data-thumbnail="main">
                            <?php the_post_thumbnail('thumbnail', array('class' => 'w-full h-full object-cover')); ?>
                        </button>
                        
                        <!-- Gallery thumbnails -->
                        <?php foreach ($attachment_ids as $index => $attachment_id) : ?>
                            <button onclick="showGalleryImage(<?php echo $index + 1; ?>)" 
                                    class="flex-none w-16 h-16 bg-gray-100 rounded border-2 border-gray-200 overflow-hidden thumbnail-btn"
                                    data-thumbnail="<?php echo $index + 1; ?>">
                                <?php echo wp_get_attachment_image($attachment_id, 'thumbnail', false, array('class' => 'w-full h-full object-cover')); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="space-y-6">
                
                <!-- Category -->
                <?php
                $categories = get_the_terms($product->get_id(), 'product_cat');
                if ($categories && !is_wp_error($categories)) :
                    $category = $categories[0];
                ?>
                    <p class="text-sm text-blue-600 font-medium uppercase tracking-wide">
                        <a href="<?php echo esc_url(get_term_link($category)); ?>"><?php echo esc_html($category->name); ?></a>
                    </p>
                <?php endif; ?>
                
                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-900"><?php the_title(); ?></h1>
                
                <!-- Rating -->
                <?php if ($product->get_average_rating()) : ?>
                    <div class="flex items-center space-x-2">
                        <div class="flex text-yellow-400">
                            <?php
                            $rating = $product->get_average_rating();
                            for ($i = 1; $i <= 5; $i++) :
                                if ($i <= $rating) : ?>
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                <?php else : ?>
                                    <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                <?php endif;
                            endfor; ?>
                        </div>
                        <span class="text-sm text-gray-600"><?php echo $product->get_review_count(); ?> <?php _e('reviews', 'tostishop'); ?></span>
                    </div>
                <?php endif; ?>
                
                <!-- Price -->
                <div class="text-3xl font-bold text-gray-900 product-price">
                    <?php echo $product->get_price_html(); ?>
                </div>
                
                <!-- Short Description -->
                <?php if ($product->get_short_description()) : ?>
                    <div class="text-gray-600 leading-relaxed">
                        <?php echo apply_filters('woocommerce_short_description', $product->get_short_description()); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Stock Status -->
                <div class="flex items-center space-x-2 stock-status">
                    <?php if ($product->is_in_stock()) : ?>
                        <div class="flex items-center text-green-600">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium"><?php _e('In Stock', 'tostishop'); ?></span>
                        </div>
                    <?php else : ?>
                        <div class="flex items-center text-red-600">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium"><?php _e('Out of Stock', 'tostishop'); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Delivery ETA -->
                    <div class="text-sm text-gray-500">
                        <?php _e('Estimated delivery: 3-5 business days', 'tostishop'); ?>
                    </div>
                </div>
                
                <!-- Add to Cart Form -->
                <form class="cart space-y-4" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
                    
                    <!-- Variable Product Options -->
                    <?php if ($product->is_type('variable')) : ?>
                        <div class="space-y-4">
                            <?php
                            $attributes = $product->get_variation_attributes();
                            foreach ($attributes as $attribute_name => $options) :
                                $attribute_label = wc_attribute_label($attribute_name);
                            ?>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <?php echo esc_html($attribute_label); ?>
                                    </label>
                                    <select name="<?php echo esc_attr('attribute_' . sanitize_title($attribute_name)); ?>" 
                                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value=""><?php printf(__('Choose %s', 'tostishop'), $attribute_label); ?></option>
                                        <?php foreach ($options as $option) : ?>
                                            <option value="<?php echo esc_attr($option); ?>"><?php echo esc_html($option); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Quantity -->
                    <?php if ($product->is_sold_individually()) : ?>
                        <input type="hidden" name="quantity" value="1" />
                    <?php else : ?>
                        <div class="flex items-center space-x-4">
                            <label class="text-sm font-medium text-gray-700"><?php _e('Quantity:', 'tostishop'); ?></label>
                            <div class="flex items-center border border-gray-300 rounded-lg">
                                <button type="button" class="p-2 text-gray-600 hover:text-gray-800" onclick="decreaseQuantity()">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <input type="number" 
                                       name="quantity" 
                                       value="1" 
                                       min="1" 
                                       max="<?php echo esc_attr($product->get_stock_quantity() ?: 999); ?>"
                                       class="w-16 text-center border-0 focus:ring-0" 
                                       id="quantity">
                                <button type="button" class="p-2 text-gray-600 hover:text-gray-800" onclick="increaseQuantity()">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Add to Cart Button -->
                    <div class="space-y-3">
                        <?php if ($product->is_in_stock()) : ?>
                            <button type="submit" 
                                    name="add-to-cart" 
                                    value="<?php echo esc_attr($product->get_id()); ?>"
                                    class="w-full bg-blue-600 text-white py-4 px-6 rounded-lg text-lg font-semibold hover:bg-blue-700 transition-colors duration-200">
                                <?php echo esc_html($product->single_add_to_cart_text()); ?>
                            </button>
                        <?php else : ?>
                            <button type="button" disabled 
                                    class="w-full bg-gray-300 text-gray-500 py-4 px-6 rounded-lg text-lg font-semibold cursor-not-allowed">
                                <?php _e('Out of Stock', 'tostishop'); ?>
                            </button>
                        <?php endif; ?>
                        
                        <!-- Wishlist Button -->
                        <button type="button" 
                                class="w-full border-2 border-gray-300 text-gray-700 py-3 px-6 rounded-lg font-medium hover:border-gray-400 transition-colors duration-200">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <?php _e('Add to Wishlist', 'tostishop'); ?>
                        </button>
                    </div>
                </form>
                
                <!-- Product Meta -->
                <div class="border-t pt-6 space-y-3">
                    <div class="flex items-center text-sm text-gray-600">
                        <span class="font-medium mr-2"><?php _e('SKU:', 'tostishop'); ?></span>
                        <span><?php echo $product->get_sku() ?: __('N/A', 'tostishop'); ?></span>
                    </div>
                    
                    <?php if ($categories) : ?>
                    <div class="flex items-center text-sm text-gray-600">
                        <span class="font-medium mr-2"><?php _e('Categories:', 'tostishop'); ?></span>
                        <div class="flex flex-wrap gap-1">
                            <?php foreach ($categories as $category) : ?>
                                <a href="<?php echo esc_url(get_term_link($category)); ?>" 
                                   class="text-blue-600 hover:text-blue-700"><?php echo esc_html($category->name); ?></a>
                                <?php if ($category !== end($categories)) echo ', '; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php
                    $tags = get_the_terms($product->get_id(), 'product_tag');
                    if ($tags && !is_wp_error($tags)) :
                    ?>
                    <div class="flex items-center text-sm text-gray-600">
                        <span class="font-medium mr-2"><?php _e('Tags:', 'tostishop'); ?></span>
                        <div class="flex flex-wrap gap-1">
                            <?php foreach ($tags as $tag) : ?>
                                <a href="<?php echo esc_url(get_term_link($tag)); ?>" 
                                   class="text-blue-600 hover:text-blue-700"><?php echo esc_html($tag->name); ?></a>
                                <?php if ($tag !== end($tags)) echo ', '; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Product Tabs (Mobile-Optimized) -->
        <div class="mt-16" x-data="{ activeTab: 'description' }">
            
            <!-- Tab Navigation -->
            <div class="flex border-b border-gray-200 overflow-x-auto">
                <button @click="activeTab = 'description'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'description', 'border-transparent text-gray-500': activeTab !== 'description' }"
                        class="flex-none px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap">
                    <?php _e('Description', 'tostishop'); ?>
                </button>
                
                <?php if ($product->get_review_count() > 0) : ?>
                <button @click="activeTab = 'reviews'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'reviews', 'border-transparent text-gray-500': activeTab !== 'reviews' }"
                        class="flex-none px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap">
                    <?php printf(__('Reviews (%d)', 'tostishop'), $product->get_review_count()); ?>
                </button>
                <?php endif; ?>
                
                <button @click="activeTab = 'shipping'" 
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'shipping', 'border-transparent text-gray-500': activeTab !== 'shipping' }"
                        class="flex-none px-6 py-3 border-b-2 font-medium text-sm whitespace-nowrap">
                    <?php _e('Shipping & Returns', 'tostishop'); ?>
                </button>
            </div>
            
            <!-- Tab Content -->
            <div class="py-8">
                
                <!-- Description Tab -->
                <div x-show="activeTab === 'description'" class="prose max-w-none">
                    <?php the_content(); ?>
                </div>
                
                <!-- Reviews Tab -->
                <?php if ($product->get_review_count() > 0) : ?>
                <div x-show="activeTab === 'reviews'">
                    <?php comments_template(); ?>
                </div>
                <?php endif; ?>
                
                <!-- Shipping Tab -->
                <div x-show="activeTab === 'shipping'" class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4"><?php _e('Shipping Information', 'tostishop'); ?></h3>
                        <div class="space-y-3 text-gray-600">
                            <p><?php _e('• Free shipping on orders over $50', 'tostishop'); ?></p>
                            <p><?php _e('• Standard delivery: 3-5 business days', 'tostishop'); ?></p>
                            <p><?php _e('• Express delivery: 1-2 business days', 'tostishop'); ?></p>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4"><?php _e('Returns & Exchanges', 'tostishop'); ?></h3>
                        <div class="space-y-3 text-gray-600">
                            <p><?php _e('• 30-day return policy', 'tostishop'); ?></p>
                            <p><?php _e('• Items must be in original condition', 'tostishop'); ?></p>
                            <p><?php _e('• Free returns on defective items', 'tostishop'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    <?php endwhile; ?>
</div>

<!-- Sticky Add to Cart (Mobile) -->
<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 lg:hidden z-20">
    <div class="flex items-center space-x-4">
        <div class="flex-1">
            <div class="text-lg font-bold text-gray-900"><?php echo $product->get_price_html(); ?></div>
        </div>
        <button class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200">
            <?php _e('Add to Cart', 'tostishop'); ?>
        </button>
    </div>
</div>

<?php get_footer(); ?>
