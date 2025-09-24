<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 1.6.4
 */

defined( 'ABSPATH' ) || exit;

get_header(); 

// Generate comprehensive structured data for SEO
global $product;

// Ensure we have the product object
if (!$product || !is_a($product, 'WC_Product')) {
    $product = wc_get_product(get_the_ID());
}

// Verify we have a valid product before proceeding
if ($product && is_a($product, 'WC_Product') && method_exists($product, 'get_name')) {
    $structured_data = array(
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $product->get_name(),
        'description' => wp_strip_all_tags($product->get_short_description() ? $product->get_short_description() : $product->get_description()),
        'sku' => $product->get_sku(),
        'gtin' => $product->get_sku(),
        'brand' => array(
            '@type' => 'Brand',
            'name' => get_bloginfo('name')
        ),
        'manufacturer' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url()
        ),
        'url' => get_permalink(),
        'image' => array()
    );

    // Add product images
    $image_ids = $product->get_gallery_image_ids();
    if ($product->get_image_id()) {
        array_unshift($image_ids, $product->get_image_id());
    }

    foreach ($image_ids as $image_id) {
        $image_url = wp_get_attachment_image_url($image_id, 'full');
        if ($image_url) {
            $structured_data['image'][] = $image_url;
        }
    }

    // Add pricing information
    if ($product->is_in_stock()) {
        $structured_data['offers'] = array(
            '@type' => 'Offer',
            'url' => get_permalink(),
            'priceCurrency' => get_woocommerce_currency(),
            'price' => $product->get_regular_price(),
            'priceValidUntil' => date('Y-m-d', strtotime('+1 year')),
            'availability' => 'https://schema.org/InStock',
            'itemCondition' => 'https://schema.org/NewCondition',
            'seller' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'url' => home_url()
            )
        );
        
        // Add sale price and discount information if on sale
        if ($product->is_on_sale() && $product->get_sale_price()) {
            $structured_data['offers']['price'] = $product->get_sale_price();
            
            // Add discount percentage for better SEO
            $discount_percentage = tostishop_get_discount_percentage($product);
            if ($discount_percentage) {
                $structured_data['offers']['priceSpecification'] = array(
                    '@type' => 'PriceSpecification',
                    'price' => $product->get_sale_price(),
                    'priceCurrency' => get_woocommerce_currency(),
                    'valueAddedTaxIncluded' => wc_prices_include_tax() ? 'true' : 'false'
                );
                
                // Add original price as additional property
                $structured_data['offers']['eligibleTransactionVolume'] = array(
                    '@type' => 'PriceSpecification',
                    'price' => $product->get_regular_price(),
                    'priceCurrency' => get_woocommerce_currency(),
                    'name' => 'Original Price'
                );
            }
        }
    } else {
        $structured_data['offers'] = array(
            '@type' => 'Offer',
            'url' => get_permalink(),
            'priceCurrency' => get_woocommerce_currency(),
            'price' => $product->get_regular_price(),
            'availability' => 'https://schema.org/OutOfStock',
            'itemCondition' => 'https://schema.org/NewCondition'
        );
    }

    // Add rating and review data
    if ($product->get_review_count() > 0) {
        $structured_data['aggregateRating'] = array(
            '@type' => 'AggregateRating',
            'ratingValue' => $product->get_average_rating(),
            'reviewCount' => $product->get_review_count(),
            'bestRating' => '5',
            'worstRating' => '1'
        );
    }

    // Add category information
    $categories = get_the_terms($product->get_id(), 'product_cat');
    if ($categories && !is_wp_error($categories)) {
        $category_names = array();
        foreach ($categories as $category) {
            $category_names[] = $category->name;
        }
        $structured_data['category'] = implode(', ', $category_names);
    }

    // Add dimensions and weight if available
    if ($product->has_dimensions()) {
        $structured_data['weight'] = array(
            '@type' => 'QuantitativeValue',
            'value' => $product->get_weight(),
            'unitCode' => get_option('woocommerce_weight_unit', 'kg')
        );
    }

    // Add product attributes
    $attributes = $product->get_attributes();
    if (!empty($attributes)) {
        $additional_property = array();
        foreach ($attributes as $attribute) {
            if ($attribute->get_visible()) {
                $additional_property[] = array(
                    '@type' => 'PropertyValue',
                    'name' => wc_attribute_label($attribute->get_name()),
                    'value' => $product->get_attribute($attribute->get_name())
                );
            }
        }
        if (!empty($additional_property)) {
            $structured_data['additionalProperty'] = $additional_property;
        }
    }

    // Output product structured data
    echo '<script type="application/ld+json">' . wp_json_encode($structured_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";

    // Add breadcrumb structured data
    $breadcrumb_data = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array(
            array(
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => home_url()
            ),
            array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Shop',
                'item' => get_permalink(wc_get_page_id('shop'))
            )
        )
    );

    // Add category breadcrumbs
    if ($categories && !is_wp_error($categories)) {
        $position = 3;
        foreach ($categories as $category) {
            $breadcrumb_data['itemListElement'][] = array(
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $category->name,
                'item' => get_term_link($category)
            );
            $position++;
        }
    }

    // Add current product
    $breadcrumb_data['itemListElement'][] = array(
        '@type' => 'ListItem',
        'position' => count($breadcrumb_data['itemListElement']) + 1,
        'name' => $product->get_name(),
        'item' => get_permalink()
    );

    echo '<script type="application/ld+json">' . wp_json_encode($breadcrumb_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";

    // Add Organization/Website structured data
    $organization_data = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'logo' => array(
            '@type' => 'ImageObject',
            'url' => get_theme_file_uri('/assets/images/logo.png')
        ),
        'sameAs' => array(
            // Add your social media profiles here
            'https://www.facebook.com/tostishop',
            'https://www.instagram.com/tostishop',
            'https://www.twitter.com/tostishop'
        )
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode($organization_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";

    // Add WebSite structured data for search functionality
    $website_data = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => array(
                '@type' => 'EntryPoint',
                'urlTemplate' => home_url('/?s={search_term_string}&post_type=product')
            ),
            'query-input' => 'required name=search_term_string'
        )
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode($website_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";

    // Add Open Graph and Twitter Card meta tags
    echo '<meta property="og:type" content="product">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($product->get_name()) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr(wp_strip_all_tags($product->get_short_description() ? $product->get_short_description() : $product->get_description())) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    
    if ($product->get_image_id()) {
        $og_image = wp_get_attachment_image_url($product->get_image_id(), 'large');
        if ($og_image) {
            echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
            echo '<meta property="og:image:width" content="1200">' . "\n";
            echo '<meta property="og:image:height" content="630">' . "\n";
        }
    }
    
    // Twitter Card
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($product->get_name()) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr(wp_strip_all_tags($product->get_short_description() ? $product->get_short_description() : $product->get_description())) . '">' . "\n";
    
    if ($product->get_image_id()) {
        $twitter_image = wp_get_attachment_image_url($product->get_image_id(), 'large');
        if ($twitter_image) {
            echo '<meta name="twitter:image" content="' . esc_url($twitter_image) . '">' . "\n";
        }
    }

    // Product-specific meta tags
    echo '<meta name="product:price:amount" content="' . esc_attr($product->get_price()) . '">' . "\n";
    echo '<meta name="product:price:currency" content="' . esc_attr(get_woocommerce_currency()) . '">' . "\n";
    echo '<meta name="product:availability" content="' . ($product->is_in_stock() ? 'in stock' : 'out of stock') . '">' . "\n";
    
    if ($product->get_sku()) {
        echo '<meta name="product:retailer_item_id" content="' . esc_attr($product->get_sku()) . '">' . "\n";
    }
}
?>

<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breadcrumbs -->
    <?php tostishop_breadcrumbs(); ?>
    
    <!-- WooCommerce Notices -->
    <div class="woocommerce-notices-wrapper mb-6">
        <?php wc_print_notices(); ?>
    </div>
    
    <?php while (have_posts()) : the_post(); ?>
        <?php global $product; ?>
        
        <!-- Mobile Layout (unchanged) -->
        <div class="lg:hidden">
            <!-- Product Images -->
            <div class="mb-8">
                <div class="product-gallery" 
                     x-data="{ currentImage: 0, images: [] }"
                     role="region" 
                     aria-label="<?php esc_attr_e('Product Images', 'tostishop'); ?>">
                    
                    <!-- Main Image -->
                    <div class="mb-4">
                        <div class="product-image-container aspect-square focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php 
                                $main_image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
                                $main_image_alt = $main_image_alt ?: sprintf(__('%s main product image', 'tostishop'), get_the_title());
                                ?>
                                <?php the_post_thumbnail('large', array(
                                    'class' => 'w-full h-full object-cover main-product-image',
                                    'id' => 'main-product-image',
                                    'alt' => $main_image_alt,
                                    'loading' => 'eager',
                                    'tabindex' => '0'
                                )); ?>
                            <?php endif; ?>
                            
                            <!-- Gallery Images (initially hidden) -->
                            <?php
                            $attachment_ids = $product->get_gallery_image_ids();
                            foreach ($attachment_ids as $index => $attachment_id) :
                                $image_url = wp_get_attachment_image_url($attachment_id, 'large');
                                $image_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
                                $image_alt = $image_alt ?: sprintf(__('%s product image %d', 'tostishop'), get_the_title(), $index + 2);
                            ?>
                                <img src="<?php echo esc_url($image_url); ?>" 
                                     alt="<?php echo esc_attr($image_alt); ?>"
                                     class="w-full h-full object-cover gallery-image"
                                     style="display: none;"
                                     data-gallery-index="<?php echo $index + 1; ?>"
                                     loading="lazy"
                                     tabindex="0">
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Thumbnail Navigation -->
                    <?php if ($attachment_ids) : ?>
                    <div class="flex space-x-2 overflow-x-auto pb-2" role="tablist" aria-label="<?php esc_attr_e('Product image thumbnails', 'tostishop'); ?>">
                        <!-- Main thumbnail -->
                        <button onclick="showGalleryImage(0)" 
                                class="flex-none w-16 h-16 product-thumbnail active thumbnail-btn focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                data-thumbnail="main"
                                role="tab"
                                aria-selected="true"
                                aria-controls="main-product-image"
                                aria-label="<?php esc_attr_e('View main product image', 'tostishop'); ?>"
                                tabindex="0">
                            <?php the_post_thumbnail('thumbnail', array(
                                'class' => 'w-full h-full object-cover',
                                'alt' => '',
                                'role' => 'presentation'
                            )); ?>
                        </button>
                        
                        <!-- Gallery thumbnails -->
                        <?php foreach ($attachment_ids as $index => $attachment_id) : ?>
                            <button onclick="showGalleryImage(<?php echo $index + 1; ?>)" 
                                    class="flex-none w-16 h-16 product-thumbnail thumbnail-btn focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    data-thumbnail="<?php echo $index + 1; ?>"
                                    role="tab"
                                    aria-selected="false"
                                    aria-controls="main-product-image"
                                    aria-label="<?php echo esc_attr(sprintf(__('View product image %d', 'tostishop'), $index + 2)); ?>"
                                    tabindex="-1">
                                <?php echo wp_get_attachment_image($attachment_id, 'thumbnail', false, array(
                                    'class' => 'w-full h-full object-cover',
                                    'alt' => '',
                                    'role' => 'presentation'
                                )); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Mobile Product Information -->
            <div class="mt-8 lg:hidden">
                <!-- Category -->
                <?php if ($categories): ?>
                    <nav aria-label="<?php esc_attr_e('Product category', 'tostishop'); ?>" class="mb-2">
                        <a href="<?php echo esc_url(get_term_link($categories[0])); ?>" 
                           class="text-sm text-blue-600 font-medium uppercase tracking-wide hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded">
                            <?php echo esc_html($categories[0]->name); ?>
                        </a>
                    </nav>
                <?php endif; ?>
                
                <!-- Title -->
                <header>
                    <h1 class="text-2xl font-bold text-gray-900 mb-4 leading-tight"><?php the_title(); ?></h1>
                </header>
                
                <!-- Rating -->
                <div class="flex items-center space-x-2 mb-4" role="group" aria-label="<?php echo $product->get_average_rating() > 0 ? esc_attr(sprintf(__('Product rating: %s out of 5 stars based on %d reviews', 'tostishop'), $product->get_average_rating(), $product->get_review_count())) : esc_attr(__('No reviews yet', 'tostishop')); ?>">
                    <div class="flex text-yellow-400" aria-hidden="true">
                        <?php
                        $rating = $product->get_average_rating();
                        for ($i = 1; $i <= 5; $i++) :
                            if ($rating > 0 && $i <= $rating) : ?>
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            <?php else : ?>
                                <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            <?php endif;
                        endfor; ?>
                    </div>
                    <span class="text-sm text-gray-600">
                        <?php if ($rating > 0) : ?>
                            <span class="sr-only"><?php echo esc_html(sprintf(__('%s out of 5 stars, ', 'tostishop'), $rating)); ?></span>
                            <?php echo $product->get_review_count(); ?> <?php echo _n('review', 'reviews', $product->get_review_count(), 'tostishop'); ?>
                        <?php else : ?>
                            <span class="sr-only"><?php _e('No reviews yet', 'tostishop'); ?></span>
                            <?php _e('No reviews yet', 'tostishop'); ?>
                        <?php endif; ?>
                    </span>
                </div>
                
                <!-- Price -->
                <div class="flex items-center space-x-3 mb-4">
                    <div class="text-2xl font-bold text-gray-900 product-price" role="text" aria-label="<?php echo esc_attr(sprintf(__('Price: %s', 'tostishop'), strip_tags($product->get_price_html()))); ?>">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    
                    <!-- Discount Percentage Badge -->
                    <?php if ($product->is_on_sale()) : ?>
                        <?php $discount_percentage = tostishop_get_discount_percentage($product); ?>
                        <?php if ($discount_percentage) : ?>
                            <span class="text-sm font-bold text-accent bg-red-50 px-3 py-1 rounded-full border border-red-200"
                                  title="<?php echo esc_attr(sprintf(__('You save %d%% on this product', 'tostishop'), $discount_percentage)); ?>"
                                  role="text" aria-label="<?php echo esc_attr(sprintf(__('%d percent off', 'tostishop'), $discount_percentage)); ?>">
                                <?php echo esc_html($discount_percentage); ?>% <?php _e('OFF', 'tostishop'); ?>
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Short Description -->
                <?php if ($product->get_short_description()) : ?>
                    <div class="text-gray-600 leading-relaxed mb-6">
                        <?php echo apply_filters('woocommerce_short_description', $product->get_short_description()); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Stock Status -->
                <div class="flex items-center space-x-2 stock-status mb-6" role="status" aria-live="polite">
                    <?php if ($product->is_in_stock()) : ?>
                        <div class="flex items-center text-green-600">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium"><?php _e('In Stock', 'tostishop'); ?></span>
                        </div>
                    <?php else : ?>
                        <div class="flex items-center text-red-600">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
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
                
                <!-- Add to Cart Form for Mobile -->
                <form class="cart space-y-4 mb-6" 
                      action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" 
                      method="post" 
                      enctype='multipart/form-data'
                      role="form"
                      aria-label="<?php esc_attr_e('Add product to cart', 'tostishop'); ?>">
                    
                    <!-- Variable Product Options -->
                    <?php do_action('woocommerce_before_add_to_cart_button'); ?>
                    
                    <!-- Quantity -->
                    <?php if (!$product->is_sold_individually()): ?>
                        <div class="quantity-selector">
                            <label for="quantity-mobile" class="block text-sm font-medium text-gray-700 mb-2">
                                <?php _e('Quantity:', 'tostishop'); ?>
                            </label>
                            <div class="flex items-center border border-gray-300 rounded-lg w-32">
                                <button type="button" 
                                        class="p-3 text-gray-600 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset"
                                        onclick="decreaseQuantity('quantity-mobile')"
                                        aria-label="<?php esc_attr_e('Decrease quantity', 'tostishop'); ?>"
                                        tabindex="0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <input type="number" 
                                       id="quantity-mobile"
                                       name="quantity" 
                                       value="<?php echo esc_attr(isset($_POST['quantity']) ? wc_stock_amount($_POST['quantity']) : $product->get_min_purchase_quantity()); ?>" 
                                       min="<?php echo esc_attr(apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product)); ?>" 
                                       max="<?php echo esc_attr(apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product)); ?>"
                                       step="1"
                                       class="w-full text-center border-0 focus:ring-0 focus:outline-none text-base py-2" 
                                       aria-label="<?php esc_attr_e('Product quantity', 'tostishop'); ?>"
                                       inputmode="numeric">
                                <button type="button" 
                                        class="p-3 text-gray-600 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset"
                                        onclick="increaseQuantity('quantity-mobile')"
                                        aria-label="<?php esc_attr_e('Increase quantity', 'tostishop'); ?>"
                                        tabindex="0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="quantity" value="1" />
                    <?php endif; ?>
                    
                    <!-- Mobile Buttons (Stacked) -->
                    <div class="space-y-3">
                        <?php if ($product->is_purchasable() && $product->is_in_stock()): ?>
                            <button type="submit" 
                                    name="add-to-cart" 
                                    value="<?php echo esc_attr($product->get_id()); ?>"
                                    class="btn-primary w-full py-4 px-6 text-lg"
                                    aria-describedby="add-to-cart-description">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 2.5M7 13l2.5 2.5m6 0L19 18H7"></path>
                                    </svg>
                                    <?php echo esc_html($product->single_add_to_cart_text()); ?>
                                </span>
                            </button>
                            <div id="add-to-cart-description" class="sr-only">
                                <?php echo esc_html(sprintf(__('Add %s to your shopping cart', 'tostishop'), get_the_title())); ?>
                            </div>
                        <?php else: ?>
                            <button type="button" 
                                    disabled
                                    class="btn-secondary w-full py-4 px-6 text-lg cursor-not-allowed" disabled
                                    aria-label="<?php echo $product->is_in_stock() ? esc_attr__('Product not available for purchase', 'tostishop') : esc_attr__('Product is out of stock', 'tostishop'); ?>">
                                <?php echo $product->is_in_stock() ? esc_html__('Not Available', 'tostishop') : esc_html__('Out of Stock', 'tostishop'); ?>
                            </button>
                        <?php endif; ?>
                        
                        <!-- Wishlist Button -->
                        <?php echo tostishop_get_wishlist_button($product->get_id(), 'w-full border-2 border-gray-300 text-gray-700 py-3 px-6 rounded-lg font-medium hover:border-gray-400 focus:border-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center'); ?>
                    </div>
                </form>
                
                <!-- Shiprocket Pincode Check (Mobile) -->
                <?php 
                if (function_exists('tostishop_shiprocket_pincode_check')) {
                    tostishop_shiprocket_pincode_check();
                }
                ?>
                
                <!-- Mobile Product Meta -->
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
                
                <!-- Mobile Shipping Information & Returns -->
                <div class="border-t pt-6 space-y-6">
                    <!-- Shipping Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4-8-4m16 0v10l-8 4-8-4V7"></path>
                            </svg>
                            <?php _e('Shipping Information', 'tostishop'); ?>
                        </h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span><?php _e('Free shipping on orders over ₹700', 'tostishop'); ?></span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span><?php _e('Standard delivery: 3-5 business days', 'tostishop'); ?></span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span><?php _e('Express delivery: 1-2 business days', 'tostishop'); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Returns & Exchanges -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <?php _e('Returns & Exchanges', 'tostishop'); ?>
                        </h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-start">
                                <span class="text-green-600 mr-2">•</span>
                                <span><?php _e('10-day return policy', 'tostishop'); ?></span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-green-600 mr-2">•</span>
                                <span><?php _e('Items must be in original condition', 'tostishop'); ?></span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-green-600 mr-2">•</span>
                                <span><?php _e('Free returns on defective items', 'tostishop'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Desktop Layout (60/40 split with image grid) -->
        <div class="hidden lg:flex lg:gap-8">
            <!-- Product Images Section (60% width) -->
            <section class="w-3/5" role="region" aria-label="<?php esc_attr_e('Product Images', 'tostishop'); ?>">
                <div class="product-gallery-grid">
                    <!-- All Images Grid (2 images per row like Myntra) -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Main Product Image -->
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="product-image-container aspect-square focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2">
                                <?php 
                                $main_image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
                                $main_image_alt = $main_image_alt ?: sprintf(__('%s main product image', 'tostishop'), get_the_title());
                                ?>
                                <?php the_post_thumbnail('large', array(
                                    'class' => 'w-full h-full object-cover hover:scale-105 transition-transform duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
                                    'alt' => $main_image_alt,
                                    'loading' => 'eager',
                                    'tabindex' => '0'
                                )); ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Gallery Images -->
                        <?php
                        $attachment_ids = $product->get_gallery_image_ids();
                        if ($attachment_ids) :
                            foreach ($attachment_ids as $index => $attachment_id) :
                                $image_url = wp_get_attachment_image_url($attachment_id, 'large');
                                $image_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
                                $image_alt = $image_alt ?: sprintf(__('%s product image %d', 'tostishop'), get_the_title(), $index + 2);
                        ?>
                            <div class="product-image-container aspect-square focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2">
                                <img src="<?php echo esc_url($image_url); ?>" 
                                     alt="<?php echo esc_attr($image_alt); ?>"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                     loading="lazy"
                                     tabindex="0">
                            </div>
                        <?php 
                            endforeach;
                        else :
                            // If no gallery images, show placeholder images to maintain grid
                            for ($i = 1; $i <= 3; $i++) :
                        ?>
                            <div class="product-image-container aspect-square flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        <?php endfor; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            
            <!-- Product Info Section (40% width) -->
            <section class="w-2/5 space-y-6" role="main" aria-label="<?php esc_attr_e('Product Information', 'tostishop'); ?>">
                
                <!-- Category -->
                <?php
                $categories = get_the_terms($product->get_id(), 'product_cat');
                if ($categories && !is_wp_error($categories)) :
                    $category = $categories[0];
                ?>
                    <nav aria-label="<?php esc_attr_e('Product category', 'tostishop'); ?>">
                        <a href="<?php echo esc_url(get_term_link($category)); ?>" 
                           class="text-sm text-blue-600 font-medium uppercase tracking-wide hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded">
                            <?php echo esc_html($category->name); ?>
                        </a>
                    </nav>
                <?php endif; ?>
                
                <!-- Title -->
                <header>
                    <h1 class="text-3xl font-bold text-gray-900 leading-tight"><?php the_title(); ?></h1>
                </header>
                
                <!-- Rating -->
                <div class="flex items-center space-x-2 mb-4">
                    <div class="flex text-yellow-400">
                        <?php
                        $rating = $product->get_average_rating();
                        for ($i = 1; $i <= 5; $i++) :
                            if ($rating > 0 && $i <= $rating) : ?>
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
                    <?php if ($rating > 0) : ?>
                        <span class="text-sm text-gray-600"><?php echo $product->get_review_count(); ?> <?php echo _n('review', 'reviews', $product->get_review_count(), 'tostishop'); ?></span>
                    <?php else : ?>
                        <span class="text-sm text-gray-400"><?php _e('No reviews yet', 'tostishop'); ?></span>
                    <?php endif; ?>
                </div>
                
                <!-- Price -->
                <div class="flex items-center space-x-3 mb-4">
                    <div class="text-3xl font-bold text-gray-900 product-price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    
                    <!-- Discount Percentage Badge -->
                    <?php if ($product->is_on_sale()) : ?>
                        <?php $discount_percentage = tostishop_get_discount_percentage($product); ?>
                        <?php if ($discount_percentage) : ?>
                            <span class="text-lg font-bold text-accent bg-red-50 px-3 py-2 rounded-full border border-red-200 shadow-sm"
                                  title="<?php echo esc_attr(sprintf(__('You save %d%% on this product', 'tostishop'), $discount_percentage)); ?>"
                                  role="text" aria-label="<?php echo esc_attr(sprintf(__('%d percent off', 'tostishop'), $discount_percentage)); ?>">
                                <?php echo esc_html($discount_percentage); ?>% <?php _e('OFF', 'tostishop'); ?>
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
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
                                <div class="variation-attribute" data-attribute="<?php echo esc_attr($attribute_name); ?>">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <?php echo esc_html($attribute_label); ?>
                                    </label>
                                    
                                    <?php 
                                    // Check if this is a color attribute and show swatches
                                    $is_color_attr = (strpos(strtolower($attribute_name), 'color') !== false || strpos(strtolower($attribute_name), 'colour') !== false);
                                    
                                    if ($is_color_attr && count($options) <= 8) : ?>
                                        <!-- Color Swatches -->
                                        <div class="flex flex-wrap gap-3">
                                            <?php foreach ($options as $option) : 
                                                $color_value = strtolower(trim($option));
                                                $bg_color = '';
                                                
                                                // Map common color names to hex values
                                                $color_map = array(
                                                    'red' => '#dc2626', 'blue' => '#2563eb', 'green' => '#16a34a', 
                                                    'yellow' => '#eab308', 'purple' => '#9333ea', 'pink' => '#ec4899',
                                                    'black' => '#000000', 'white' => '#ffffff', 'gray' => '#6b7280',
                                                    'grey' => '#6b7280', 'navy' => '#1e3a8a', 'orange' => '#ea580c',
                                                    'brown' => '#a16207', 'beige' => '#d6d3d1', 'cream' => '#fef7ed'
                                                );
                                                
                                                if (isset($color_map[$color_value])) {
                                                    $bg_color = $color_map[$color_value];
                                                } elseif (preg_match('/^#[a-f0-9]{6}$/i', $color_value)) {
                                                    $bg_color = $color_value;
                                                } else {
                                                    $bg_color = '#6b7280'; // Default gray
                                                }
                                                
                                                $border_style = ($bg_color === '#ffffff') ? 'border-2 border-gray-300' : 'border border-gray-200';
                                            ?>
                                                <label class="color-swatch cursor-pointer group">
                                                    <input type="radio" 
                                                           name="<?php echo esc_attr('attribute_' . sanitize_title($attribute_name)); ?>" 
                                                           value="<?php echo esc_attr($option); ?>"
                                                           class="sr-only variation-radio"
                                                           data-attribute="<?php echo esc_attr($attribute_name); ?>">
                                                    <span class="block w-8 h-8 rounded-full <?php echo $border_style; ?> transition-all duration-200 hover:scale-110"
                                                          style="background-color: <?php echo esc_attr($bg_color); ?>;"
                                                          title="<?php echo esc_attr($option); ?>"></span>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else : ?>
                                        <!-- Regular Dropdown -->
                                        <select name="<?php echo esc_attr('attribute_' . sanitize_title($attribute_name)); ?>" 
                                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent variation-select">
                                            <option value=""><?php printf(__('Choose %s', 'tostishop'), $attribute_label); ?></option>
                                            <?php foreach ($options as $option) : ?>
                                                <option value="<?php echo esc_attr($option); ?>"><?php echo esc_html($option); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                            
                            <!-- Hidden variation ID field -->
                            <input type="hidden" name="variation_id" class="variation_id" value="0" />
                            <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>" />
                            
                            <!-- Variation data container -->
                            <div class="woocommerce-variation-add-to-cart variations_button" style="display: none;">
                                <div class="woocommerce-variation-price"></div>
                                <div class="woocommerce-variation-availability"></div>
                            </div>
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
                    <div class="space-y-3 lg:space-y-0 lg:flex lg:gap-4">
                        <?php if ($product->is_in_stock()) : ?>
                            <button type="submit" 
                                    name="add-to-cart" 
                                    value="<?php echo esc_attr($product->get_id()); ?>"
                                    class="btn-primary w-full lg:flex-1 py-4 px-6 text-lg">
                                <?php echo esc_html($product->single_add_to_cart_text()); ?>
                            </button>
                        <?php else : ?>
                            <button type="button" disabled 
                                    class="btn-secondary w-full lg:flex-1 py-4 px-6 text-lg cursor-not-allowed" disabled>
                                <?php _e('Out of Stock', 'tostishop'); ?>
                            </button>
                        <?php endif; ?>
                        
                        <!-- Wishlist Button -->
                        <div class="w-full lg:flex-1">
                            <?php echo tostishop_get_wishlist_button($product->get_id(), 'w-full border-2 border-gray-300 text-gray-700 py-4 px-6 rounded-lg text-lg font-semibold hover:border-gray-400 transition-colors duration-200 flex items-center justify-center'); ?>
                        </div>
                    </div>
                </form>
                
                <!-- Shiprocket Pincode Check (Desktop) -->
                <?php 
                if (function_exists('tostishop_shiprocket_pincode_check')) {
                    tostishop_shiprocket_pincode_check();
                }
                ?>
                
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
                
                <!-- Shipping Information & Returns -->
                <div class="border-t pt-6 space-y-6">
                    <!-- Shipping Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4-8-4m16 0v10l-8 4-8-4V7"></path>
                            </svg>
                            <?php _e('Shipping Information', 'tostishop'); ?>
                        </h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span><?php _e('Free shipping on orders over ₹700', 'tostishop'); ?></span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span><?php _e('Standard delivery: 3-5 business days', 'tostishop'); ?></span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-blue-600 mr-2">•</span>
                                <span><?php _e('Express delivery: 1-2 business days', 'tostishop'); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Returns & Exchanges -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <?php _e('Returns & Exchanges', 'tostishop'); ?>
                        </h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-start">
                                <span class="text-green-600 mr-2">•</span>
                                <span><?php _e('10-day return policy', 'tostishop'); ?></span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-green-600 mr-2">•</span>
                                <span><?php _e('Items must be in original condition', 'tostishop'); ?></span>
                            </div>
                            <div class="flex items-start">
                                <span class="text-green-600 mr-2">•</span>
                                <span><?php _e('Free returns on defective items', 'tostishop'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
/**
 * Product Upsells
 * Hook: woocommerce_output_upsells
 */
woocommerce_upsell_display();
?>
        
        <!-- Product Tabs (Mobile-Optimized) -->
        <div class="mt-16 px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'description' }">
            
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
                            <p><?php _e('• Free shipping on orders over ₹700', 'tostishop'); ?></p>
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



<!-- Related Products Cards -->
<?php
// Get related products first
$related_ids = wc_get_related_products( $product->get_id(), 10 );
$related_products = array();

if ( ! empty( $related_ids ) ) {
    $related_products = wc_get_products( array(
        'include' => $related_ids,
        'limit'   => 10,
        'status'  => 'publish',
    ) );
}

// If we don't have enough related products, fill with recent products
if ( count( $related_products ) < 10 ) {
    $recent_products = wc_get_products( array(
        'limit'   => 10 - count( $related_products ),
        'status'  => 'publish',
        'exclude' => array( $product->get_id() ), // Exclude current product
        'orderby' => 'date',
        'order'   => 'DESC',
    ) );
    
    // Merge with related products
    $related_products = array_merge( $related_products, $recent_products );
}

if ( ! empty( $related_products ) ) :
?>
<section class="pt-16 pb-8 bg-gray-200">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-2">
                <?php _e( 'You may also like', 'tostishop' ); ?>
            </h2>
            <p class="text-gray-600">
                <?php _e( 'Similar products that other customers loved', 'tostishop' ); ?>
            </p>
        </div>

        <!-- Products Grid (6 items per row, full width) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <?php foreach ( $related_products as $related_product ) : ?>
                <div class="product-card bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200 h-full flex flex-col">
                    
                    <!-- Product Image (Fixed aspect ratio for consistency) -->
                    <div class="product-image bg-gray-100 aspect-square relative">
                        <a href="<?php echo esc_url( $related_product->get_permalink() ); ?>" class="block h-full w-full">
                            <?php if ( $related_product->get_image_id() ) : ?>
                                <?php echo $related_product->get_image( 'medium', array(
                                    'class' => 'w-full h-full object-cover object-center',
                                    'alt' => $related_product->get_name()
                                ) ); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" 
                                     alt="<?php echo esc_attr( $related_product->get_name() ); ?>" 
                                     class="w-full h-full object-cover object-center">
                            <?php endif; ?>
                        </a>
                        
                        <!-- Sale Badge -->
                        <?php if ( $related_product->is_on_sale() ) : ?>
                            <div class="absolute top-2 left-2 bg-accent text-white text-xs font-bold px-2 py-1 rounded z-10">
                                <?php _e( 'Sale', 'tostishop' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="p-3 flex flex-col flex-grow">
                        <!-- Category -->
                        <?php
                        $categories = get_the_terms( $related_product->get_id(), 'product_cat' );
                        if ( $categories && ! is_wp_error( $categories ) ) :
                            $category = $categories[0];
                        ?>
                            <p class="text-xs text-primary font-medium uppercase tracking-wide mb-1">
                                <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="hover:text-accent transition-colors">
                                    <?php echo esc_html( $category->name ); ?>
                                </a>
                            </p>
                        <?php else : ?>
                            <div class="h-3 mb-1"></div> <!-- Placeholder for consistent height -->
                        <?php endif; ?>
                    
                    <!-- Product Title -->
                    <h3 class="text-xs font-medium text-gray-900 mb-2 line-clamp-2 leading-tight flex-grow">
                        <a href="<?php echo esc_url( $related_product->get_permalink() ); ?>" class="hover:text-primary transition-colors duration-200">
                            <?php echo esc_html( $related_product->get_name() ); ?>
                        </a>
                    </h3>
                    
                    <!-- Rating -->
                    <div class="flex items-center space-x-1 mb-2">
                        <div class="flex text-yellow-400">
                            <?php
                            $rating = $related_product->get_average_rating();
                            for ( $i = 1; $i <= 5; $i++ ) :
                                if ( $rating > 0 && $i <= $rating ) : ?>
                                    <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                <?php else : ?>
                                    <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.927c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                <?php endif;
                            endfor; ?>
                        </div>
                        <?php if ($rating > 0) : ?>
                            <span class="text-xs text-gray-500">(<?php echo $related_product->get_review_count(); ?>)</span>
                        <?php else : ?>
                            <span class="text-xs text-gray-400"><?php _e('No reviews', 'tostishop'); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Price Section -->
                    <div class="mt-auto">
                        <div class="flex items-center justify-between mb-1">
                            <div class="text-xs font-bold text-gray-900 flex-1">
                                <?php echo $related_product->get_price_html(); ?>
                            </div>
                            
                            <!-- Sale Badge with Discount Percentage -->
                            <?php if ( $related_product->is_on_sale() ) : ?>
                                <?php $discount = tostishop_get_discount_percentage($related_product); ?>
                                <?php if ($discount) : ?>
                                    <span class="text-xs bg-accent text-white px-1 py-0.5 rounded-full font-medium"
                                          title="<?php echo esc_attr(sprintf(__('%d%% off', 'tostishop'), $discount)); ?>">
                                        -<?php echo esc_html($discount); ?>% OFF
                                    </span>
                                <?php else : ?>
                                    <span class="text-xs bg-accent text-white px-1 py-0.5 rounded-full font-medium">
                                        <?php _e( 'Sale', 'tostishop' ); ?>
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Stock Status -->
                        <?php if ( ! $related_product->is_in_stock() ) : ?>
                            <span class="text-xs text-red-600 font-medium px-2 py-1 bg-red-50 rounded-full block text-center">
                                <?php _e( 'Out of Stock', 'tostishop' ); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
                
<?php endif; ?>

<!-- Sticky Add to Cart (Mobile) -->
<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 lg:hidden z-20 shadow-lg" 
     role="complementary" 
     aria-label="<?php esc_attr_e('Quick add to cart', 'tostishop'); ?>">
    <div class="flex items-center space-x-4">
        <div class="flex-1">
            <div class="text-lg font-bold text-gray-900" 
                 role="text" 
                 aria-label="<?php echo esc_attr(sprintf(__('Price: %s', 'tostishop'), strip_tags($product->get_price_html()))); ?>">
                <?php echo $product->get_price_html(); ?>
            </div>
        </div>
        <button type="button" 
                onclick="handleFloatingAddToCart()" 
                id="floating-add-to-cart-btn"
                class="<?php echo $product->is_in_stock() ? 'btn-primary' : 'btn-secondary cursor-not-allowed'; ?> px-6 py-3"
                <?php echo (!$product->is_in_stock() ? 'disabled' : ''); ?>
                aria-label="<?php echo $product->is_in_stock() ? esc_attr(sprintf(__('Add %s to cart', 'tostishop'), get_the_title())) : esc_attr__('Product is out of stock', 'tostishop'); ?>">
            <span class="flex items-center">
                <?php if ($product->is_in_stock()) : ?>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 2.5M7 13l2.5 2.5m6 0L19 18H7"></path>
                    </svg>
                <?php endif; ?>
                <?php echo $product->is_in_stock() ? esc_html($product->single_add_to_cart_text()) : esc_html__('Out of Stock', 'tostishop'); ?>
            </span>
        </button>
    </div>
</div>

<script>
function showGalleryImage(index) {
    // Update thumbnail active states
    const thumbnails = document.querySelectorAll('.thumbnail-btn');
    thumbnails.forEach((thumb, i) => {
        if (i === index) {
            thumb.classList.add('active');
            thumb.setAttribute('aria-selected', 'true');
            thumb.setAttribute('tabindex', '0');
        } else {
            thumb.classList.remove('active');
            thumb.setAttribute('aria-selected', 'false');
            thumb.setAttribute('tabindex', '-1');
        }
    });
    
    // Update main image (if you have image switching functionality)
    // This would typically update the src of the main product image
    // You can extend this based on your gallery implementation
}
</script>

<?php get_footer(); ?>