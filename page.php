<?php get_header(); ?>

<?php 
// Check if this is a WooCommerce account page - if so, use wider layout
$is_account_page = (function_exists('is_account_page') && is_account_page()) || 
                   (function_exists('is_wc_endpoint_url') && is_wc_endpoint_url()) ||
                   (strpos(get_the_content(), 'woocommerce') !== false);

$container_class = $is_account_page ? 'max-w-7xl xl:max-w-[95rem] 2xl:max-w-[110rem]' : 'max-w-4xl';
?>

<div class="<?php echo $container_class; ?> mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
        
        <article class="<?php echo $is_account_page ? '' : 'prose prose-lg'; ?> max-w-none">
            
            <!-- Page Header -->
            <?php if (!$is_account_page) : ?>
            <header class="mb-8 text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4"><?php the_title(); ?></h1>
                
                <?php if (has_excerpt()) : ?>
                    <div class="text-xl text-gray-600 leading-relaxed">
                        <?php the_excerpt(); ?>
                    </div>
                <?php endif; ?>
            </header>
            <?php endif; ?>
            
            <!-- Featured Image -->
            <?php if (has_post_thumbnail() && !$is_account_page) : ?>
                <div class="mb-8 rounded-lg overflow-hidden">
                    <?php the_post_thumbnail('large', array('class' => 'w-full h-auto')); ?>
                </div>
            <?php endif; ?>
            
            <!-- Page Content -->
            <div class="<?php echo $is_account_page ? '' : 'text-gray-700 leading-relaxed'; ?>">
                <?php 
                the_content();
                
                if (!$is_account_page) {
                    wp_link_pages(array(
                        'before' => '<div class="page-links flex items-center justify-center space-x-2 mt-8">',
                        'after' => '</div>',
                        'link_before' => '<span class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded transition-colors duration-200">',
                        'link_after' => '</span>',
                    ));
                }
                ?>
            </div>
            
        </article>
        
        <?php endwhile; ?>
        
    <?php else : ?>
    
        <!-- No Content Found -->
        <div class="text-center py-16">
            <h1 class="text-3xl font-bold text-gray-900 mb-4"><?php _e('Page Not Found', 'tostishop'); ?></h1>
            <p class="text-gray-600 mb-8"><?php _e('Sorry, the page you are looking for could not be found.', 'tostishop'); ?></p>
            <a href="<?php echo esc_url(home_url('/')); ?>" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                <?php _e('Back to Home', 'tostishop'); ?>
            </a>
        </div>
        
    <?php endif; ?>
    
</div>

<?php get_footer(); ?>
