<?php get_header(); ?>

<!-- Hero Section -->
<?php if (is_front_page()) : ?>
<section class="relative bg-gradient-to-br from-navy-900 via-navy-800 to-navy-900 text-white">
    <div class="absolute inset-0 bg-black bg-opacity-20"></div>
    <?php 
    $hero_image = get_theme_mod('hero_image');
    if ($hero_image) : ?>
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url(<?php echo esc_url($hero_image); ?>);"></div>
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    <?php endif; ?>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                <?php echo esc_html(get_theme_mod('hero_title', __('Welcome to TostiShop', 'tostishop'))); ?>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-silver-200">
                <?php echo esc_html(get_theme_mod('hero_subtitle', __('Discover amazing products at great prices', 'tostishop'))); ?>
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo esc_url(home_url('/shop')); ?>" 
                   class="bg-accent hover:bg-red-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-200 text-center">
                    <?php _e('Shop Now', 'tostishop'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/about')); ?>" 
                   class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-navy-900 transition-colors duration-200 text-center">
                    <?php _e('Learn More', 'tostishop'); ?>
                </a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <?php if (is_front_page() && function_exists('WC')) : ?>
    
    <!-- Category Slider -->
    <section class="mb-16">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8 text-center">
            <?php _e('Shop by Category', 'tostishop'); ?>
        </h2>
        
        <div class="relative">
            <div class="flex gap-6 overflow-x-auto pb-4 scrollbar-hide" id="categorySlider">
                <?php
                $categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                    'number' => 8,
                ));
                
                if ($categories && !is_wp_error($categories)) :
                    foreach ($categories as $category) :
                        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                        $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : wc_placeholder_img_src();
                ?>
                <a href="<?php echo esc_url(get_term_link($category)); ?>" 
                   class="flex-none w-32 md:w-40 text-center group">
                    <div class="bg-gray-100 rounded-xl mb-3 overflow-hidden aspect-square">
                        <img src="<?php echo esc_url($image_url); ?>" 
                             alt="<?php echo esc_attr($category->name); ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors duration-200">
                        <?php echo esc_html($category->name); ?>
                    </h3>
                </a>
                <?php 
                    endforeach;
                endif; 
                ?>
            </div>
        </div>
    </section>
    
    <!-- Featured Products -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                <?php _e('Featured Products', 'tostishop'); ?>
            </h2>
            <a href="<?php echo esc_url(home_url('/shop')); ?>" 
               class="text-blue-600 hover:text-blue-700 font-medium">
                <?php _e('View All', 'tostishop'); ?> →
            </a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php
            $featured_products = wc_get_featured_product_ids();
            if ($featured_products) :
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 8,
                    'post__in' => $featured_products,
                    'orderby' => 'post__in'
                );
                $products = new WP_Query($args);
                
                if ($products->have_posts()) :
                    while ($products->have_posts()) : $products->the_post();
                        global $product;
            ?>
            <div class="group">
                <div class="bg-gray-100 rounded-lg overflow-hidden mb-4 aspect-square">
                    <a href="<?php echo esc_url(get_permalink()); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('tostishop-product-thumb', array('class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300')); ?>
                        <?php else : ?>
                            <img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover">
                        <?php endif; ?>
                    </a>
                </div>
                
                <div class="space-y-2">
                    <h3 class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors duration-200">
                        <a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a>
                    </h3>
                    
                    <!-- Optional: Add rating stars if needed -->
                    
                    <div class="text-lg font-bold text-gray-900">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                </div>
            </div>
            <?php 
                    endwhile;
                    wp_reset_postdata();
                endif;
            endif;
            ?>
        </div>
    </section>
    
    <?php else : ?>
    
    <!-- Regular Page Content -->
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
        <article class="prose prose-lg max-w-none">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6"><?php the_title(); ?></h1>
            <div class="text-gray-700 leading-relaxed">
                <?php the_content(); ?>
            </div>
        </article>
        <?php endwhile; ?>
    <?php else : ?>
        <div class="text-center py-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-4"><?php _e('Nothing Found', 'tostishop'); ?></h2>
            <p class="text-gray-600 mb-8"><?php _e('Sorry, no content was found.', 'tostishop'); ?></p>
            <a href="<?php echo esc_url(home_url('/')); ?>" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                <?php _e('Back to Home', 'tostishop'); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <?php endif; ?>
</div>

<?php get_footer(); ?>
