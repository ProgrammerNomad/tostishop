<?php
/**
 * TostiShop Custom Homepage Template
 * 
 * Mobile-First WooCommerce Homepage with all features
 * Template Name: TostiShop Homepage
 * 
 * @package TostiShop
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header(); 

// Get global product for structured data
global $woocommerce;
?>

<!-- TostiShop Homepage - Mobile-First Design -->
<div class="tostishop-homepage">

    <!-- 1. Hero Section -->
    <section class="hero-section relative bg-gradient-to-br from-navy-900 via-navy-800 to-navy-900 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-16 md:py-24 lg:py-32 text-center">
                <!-- Hero Content -->
                <div class="max-w-4xl mx-auto">
                    <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        <span class="block text-white">Welcome to</span>
                        <span class="block text-accent text-4xl md:text-6xl lg:text-7xl">TostiShop</span>
                    </h1>
                    
                    <p class="text-lg md:text-xl lg:text-2xl mb-8 text-silver-50 max-w-2xl mx-auto">
                        Your one-stop destination for fashion, beauty, and lifestyle products
                    </p>
                    
                    <!-- Hero Features Strip -->
                    <div class="flex flex-wrap justify-center items-center gap-4 md:gap-8 mb-8 text-sm md:text-base">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span>Free Shipping</span>
                        </div>
                        <div class="hidden sm:block w-px h-4 bg-silver-50"></div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Trusted by 10K+</span>
                        </div>
                        <div class="hidden sm:block w-px h-4 bg-silver-50"></div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span>COD Available</span>
                        </div>
                    </div>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" 
                           class="inline-flex items-center justify-center px-8 py-4 bg-accent text-white font-semibold rounded-lg hover:bg-red-600 transition-colors duration-200 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Shop Now
                        </a>
                        <a href="#categories" 
                           class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-navy-900 transition-all duration-200">
                            Browse Categories
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Decorative Elements -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
            </svg>
        </div>
    </section>

    <!-- 2. Main Categories Section -->
    <section id="categories" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-navy-900 mb-6">
                    Shop by Category
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    Discover our wide range of products across different categories
                </p>
            </div>
            
            <!-- Single Row Centered Categories -->
            <div class="flex justify-center">
                <div class="flex flex-wrap justify-center gap-6 lg:gap-8 max-w-6xl">
                    <?php
                    $categories = get_terms( array(
                        'taxonomy' => 'product_cat',
                        'hide_empty' => true,
                        'number' => 6,
                        'parent' => 0,
                        'orderby' => 'count',
                        'order' => 'DESC'
                    ) );
                    
                    if ( $categories && ! is_wp_error( $categories ) ) :
                        foreach ( $categories as $category ) :
                            $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                            $image_url = $thumbnail_id ? wp_get_attachment_url( $thumbnail_id ) : wc_placeholder_img_src();
                    ?>
                        <div class="category-card group w-32 sm:w-40 md:w-44 lg:w-48" data-category-id="<?php echo esc_attr( $category->term_id ); ?>">
                            <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" 
                               class="block bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border border-gray-100 group-hover:border-accent">
                                
                                <!-- Category Image -->
                                <div class="aspect-square bg-gray-100 overflow-hidden">
                                    <img src="<?php echo esc_url( $image_url ); ?>" 
                                         alt="<?php echo esc_attr( $category->name ); ?>"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                                
                                <!-- Category Info -->
                                <div class="p-3 lg:p-4 text-center">
                                    <h3 class="font-semibold text-navy-900 group-hover:text-accent transition-colors duration-200 mb-1 text-sm lg:text-base">
                                        <?php echo esc_html( $category->name ); ?>
                                    </h3>
                                    <p class="text-xs lg:text-sm text-gray-500">
                                        <?php echo esc_html( $category->count ); ?> products
                                    </p>
                                </div>
                            </a>
                        </div>
                    <?php 
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Featured Products -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-navy-900 mb-6">
                    Featured Products
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    Hand-picked products that our customers love the most
                </p>
            </div>
            
            <!-- Single Row Centered Featured Products -->
            <div class="flex justify-center">
                <div class="flex flex-wrap justify-center gap-6 lg:gap-8 max-w-6xl">
                    <?php
                    global $woocommerce_loop;
                    
                    // Get featured products first
                    $featured_products = wc_get_products( array(
                        'status' => 'publish',
                        'visibility' => 'catalog',
                        'featured' => true,
                        'limit' => 6,
                    ) );
                    
                    // If no featured products, get recent products
                    if ( empty( $featured_products ) ) {
                        $featured_products = wc_get_products( array(
                            'status' => 'publish',
                            'visibility' => 'catalog',
                            'limit' => 6,
                            'orderby' => 'date',
                            'order' => 'DESC',
                        ) );
                    }
                    
                    // Display products
                    if ( ! empty( $featured_products ) ) :
                        foreach ( $featured_products as $featured_product ) :
                            // Setup WooCommerce product context
                            $post_object = get_post( $featured_product->get_id() );
                            setup_postdata( $GLOBALS['post'] =& $post_object );
                            
                            // Set global product
                            global $product;
                            $product = $featured_product;
                    ?>
                        <div class="product-wrapper w-40 sm:w-44 md:w-48 lg:w-52">
                            <div class="product-item">
                                <!-- Product Image -->
                                <div class="product-image">
                                    <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
                                        <?php echo $product->get_image( array( 200, 200 ) ); ?>
                                    </a>
                                </div>
                                
                                <!-- Product Info -->
                                <div class="product-info">
                                    <h3 class="product-title">
                                        <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
                                            <?php echo esc_html( $product->get_name() ); ?>
                                        </a>
                                    </h3>
                                    
                                    <!-- Product Rating -->
                                    <?php if ( $product->get_average_rating() ) : ?>
                                        <div class="star-rating">
                                            <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Product Price -->
                                    <div class="product-price">
                                        <?php echo $product->get_price_html(); ?>
                                    </div>
                                    
                                    <!-- Add to Cart Button -->
                                    <?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
                                        <button class="add-to-cart-btn" 
                                                data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
                                                data-quantity="1">
                                            Add to Cart
                                        </button>
                                    <?php else : ?>
                                        <button class="add-to-cart-btn" disabled>
                                            Out of Stock
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </div>
            
            <!-- View All Button -->
            <div class="text-center mt-16">
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" 
                   class="view-all-btn">
                    View All Products
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- 4. Today's Deals -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-accent/10 text-accent rounded-full text-sm font-medium mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Limited Time Offers
                </div>
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-navy-900 mb-6">
                    Today's Deals
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    Don't miss out on these amazing deals - available for a limited time only!
                </p>
            </div>
            
            <!-- Today's Deals Swiper Slider -->
            <div class="deals-slider-container relative">
                <!-- Swiper -->
                <div class="swiper deals-swiper">
                    <div class="swiper-wrapper">
                        <?php
                        global $woocommerce_loop;
                        
                        $sale_products = wc_get_products( array(
                            'status' => 'publish',
                            'visibility' => 'catalog',
                            'on_sale' => true,
                            'limit' => 12, // Increased limit for slider
                            'orderby' => 'date',
                            'order' => 'DESC',
                        ) );
                        
                        if ( ! empty( $sale_products ) ) :
                            $woocommerce_loop['columns'] = 6;
                            $woocommerce_loop['is_homepage'] = true;
                            
                            foreach ( $sale_products as $sale_product ) :
                                // Calculate discount percentage
                                $regular_price = (float) $sale_product->get_regular_price();
                                $sale_price = (float) $sale_product->get_sale_price();
                                $discount_percentage = 0;
                                
                                if ( $regular_price > 0 && $sale_price > 0 ) {
                                    $discount_percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                                }
                                
                                // Setup WooCommerce product context
                                $post_object = get_post( $sale_product->get_id() );
                                setup_postdata( $GLOBALS['post'] =& $post_object );
                                
                                // Set global product
                                global $product;
                                $product = $sale_product;
                        ?>
                            <div class="swiper-slide">
                                <div class="product-wrapper">
                                    <div class="deal-card relative">
                                        <!-- Custom Product Display for Deals -->
                                        <div class="product-item">
                                            <!-- Discount Badge (% OFF only) -->
                                            <?php if ( $discount_percentage > 0 ) : ?>
                                                <div class="discount-badge">
                                                    <?php echo esc_html( $discount_percentage ); ?>% OFF
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Product Image -->
                                            <div class="product-image">
                                                <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
                                                    <?php echo $product->get_image( array( 200, 200 ) ); ?>
                                                </a>
                                            </div>
                                            
                                            <!-- Product Info -->
                                            <div class="product-info">
                                                <h3 class="product-title">
                                                    <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
                                                        <span class="line-clamp-2"><?php echo esc_html( $product->get_name() ); ?></span>
                                                    </a>
                                                </h3>
                                                
                                                <!-- Product Rating -->
                                                <?php if ( $product->get_average_rating() ) : ?>
                                                    <div class="star-rating">
                                                        <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Product Price -->
                                                <div class="product-price">
                                                    <?php echo $product->get_price_html(); ?>
                                                </div>
                                                
                                                <!-- Add to Cart Button -->
                                                <?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
                                                    <button class="add-to-cart-btn" 
                                                            data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
                                                            data-quantity="1">
                                                        Add to Cart
                                                    </button>
                                                <?php else : ?>
                                                    <button class="add-to-cart-btn" disabled>
                                                        Out of Stock
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endforeach;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                    
                    <!-- Navigation buttons -->
                    <div class="swiper-button-next deals-next"></div>
                    <div class="swiper-button-prev deals-prev"></div>
                    
                    <!-- Pagination dots -->
                    <div class="swiper-pagination deals-pagination"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Why Shop With Us -->
    <section class="py-20 bg-navy-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-6">
                    Why Shop With Us?
                </h2>
                <p class="text-silver-50 max-w-2xl mx-auto text-lg">
                    We're committed to providing you with the best shopping experience
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-16">
                <!-- Fast Delivery -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Fast Delivery</h3>
                    <p class="text-silver-50 text-lg">Quick and reliable delivery across India with real-time tracking</p>
                </div>
                
                <!-- Secure Payments -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Secure Payments</h3>
                    <p class="text-silver-50 text-lg">Multiple payment options with bank-level security and COD available</p>
                </div>
                
                <!-- Easy Returns -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Easy Returns</h3>
                    <p class="text-silver-50 text-lg">Hassle-free returns and exchanges within 30 days of purchase</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Customer Testimonials -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-navy-900 mb-6">
                    What Our Customers Say
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    Real reviews from real customers who love shopping with us
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                // Get latest product reviews
                $reviews = get_comments( array(
                    'post_type' => 'product',
                    'status' => 'approve',
                    'number' => 3,
                    'meta_query' => array(
                        array(
                            'key' => 'rating',
                            'value' => 4,
                            'compare' => '>='
                        )
                    )
                ) );
                
                if ( $reviews ) :
                    foreach ( $reviews as $review ) :
                        $rating = get_comment_meta( $review->comment_ID, 'rating', true );
                        $product = wc_get_product( $review->comment_post_ID );
                ?>
                    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                        <!-- Rating Stars -->
                        <div class="flex items-center mb-4">
                            <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                <svg class="w-5 h-5 <?php echo $i <= $rating ? 'text-yellow-400' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            <?php endfor; ?>
                        </div>
                        
                        <!-- Review Content -->
                        <p class="text-gray-700 mb-4 italic">
                            "<?php echo esc_html( wp_trim_words( $review->comment_content, 20 ) ); ?>"
                        </p>
                        
                        <!-- Customer Info -->
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-navy-900 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                <?php echo esc_html( strtoupper( substr( $review->comment_author, 0, 1 ) ) ); ?>
                            </div>
                            <div class="ml-3">
                                <p class="font-semibold text-gray-900"><?php echo esc_html( $review->comment_author ); ?></p>
                                <?php if ( $product ) : ?>
                                    <p class="text-sm text-gray-500">Purchased: <?php echo esc_html( $product->get_name() ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php 
                    endforeach;
                else :
                    // Fallback testimonials if no reviews
                    $fallback_testimonials = array(
                        array(
                            'name' => 'Priya S.',
                            'rating' => 5,
                            'content' => 'Amazing quality products and super fast delivery. Highly recommended!',
                            'product' => 'Fashion Collection'
                        ),
                        array(
                            'name' => 'Rahul M.',
                            'rating' => 5,
                            'content' => 'Great shopping experience. Easy returns and excellent customer service.',
                            'product' => 'Electronics'
                        ),
                        array(
                            'name' => 'Sneha K.',
                            'rating' => 4,
                            'content' => 'Love the variety of products. COD option is very convenient.',
                            'product' => 'Beauty Products'
                        )
                    );
                    
                    foreach ( $fallback_testimonials as $testimonial ) :
                ?>
                    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
                        <!-- Rating Stars -->
                        <div class="flex items-center mb-4">
                            <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                <svg class="w-5 h-5 <?php echo $i <= $testimonial['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            <?php endfor; ?>
                        </div>
                        
                        <!-- Review Content -->
                        <p class="text-gray-700 mb-4 italic">
                            "<?php echo esc_html( $testimonial['content'] ); ?>"
                        </p>
                        
                        <!-- Customer Info -->
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-navy-900 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                <?php echo esc_html( strtoupper( substr( $testimonial['name'], 0, 1 ) ) ); ?>
                            </div>
                            <div class="ml-3">
                                <p class="font-semibold text-gray-900"><?php echo esc_html( $testimonial['name'] ); ?></p>
                                <p class="text-sm text-gray-500">Purchased: <?php echo esc_html( $testimonial['product'] ); ?></p>
                            </div>
                        </div>
                    </div>
                <?php 
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- 7. Blog Highlights -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-navy-900 mb-4">
                    Latest from Our Blog
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Stay updated with the latest trends, tips, and news from TostiShop
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php
                $blog_posts = get_posts( array(
                    'post_type' => 'post',
                    'posts_per_page' => 2,
                    'post_status' => 'publish',
                    'meta_query' => array(
                        array(
                            'key' => '_thumbnail_id',
                            'compare' => 'EXISTS'
                        )
                    )
                ) );
                
                if ( $blog_posts ) :
                    foreach ( $blog_posts as $post ) :
                        setup_postdata( $post );
                ?>
                    <article class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="aspect-video overflow-hidden">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover hover:scale-105 transition-transform duration-300' ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-6">
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <time datetime="<?php echo get_the_date( 'c' ); ?>">
                                    <?php echo get_the_date(); ?>
                                </time>
                                <span class="mx-2">•</span>
                                <span><?php echo esc_html( get_the_category_list( ', ' ) ); ?></span>
                            </div>
                            
                            <h3 class="text-xl font-semibold text-navy-900 mb-3 hover:text-accent transition-colors duration-200">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            
                            <p class="text-gray-600 mb-4">
                                <?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?>
                            </p>
                            
                            <a href="<?php the_permalink(); ?>" 
                               class="inline-flex items-center text-accent font-semibold hover:text-red-600 transition-colors duration-200">
                                Read More
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                <?php 
                    endforeach;
                    wp_reset_postdata();
                else :
                    // Fallback content if no blog posts
                ?>
                    <div class="md:col-span-2 text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Blog Coming Soon</h3>
                        <p class="text-gray-600">We're working on bringing you amazing content. Stay tuned!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>


    <!-- 8. Footer CTA / Service Strip -->
    <section class="py-8 bg-silver-50 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <!-- Free COD -->
                <div class="flex items-center justify-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-accent rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <div class="font-semibold text-navy-900">Free COD</div>
                            <div class="text-sm text-gray-600">Cash on Delivery Available</div>
                        </div>
                    </div>
                </div>
                
                <!-- PAN India Shipping -->
                <div class="flex items-center justify-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-accent rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <div class="font-semibold text-navy-900">PAN India Shipping</div>
                            <div class="text-sm text-gray-600">We Deliver Everywhere</div>
                        </div>
                    </div>
                </div>
                
                <!-- 24/7 Support -->
                <div class="flex items-center justify-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-accent rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 11-9.75 9.75A9.75 9.75 0 0112 2.25z"></path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <div class="font-semibold text-navy-900">24/7 Support</div>
                            <div class="text-sm text-gray-600">Always Here to Help</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<!-- SEO Structured Data (JSON-LD) -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "<?php echo esc_js( get_bloginfo( 'name' ) ); ?>",
    "url": "<?php echo esc_url( home_url() ); ?>",
    "description": "<?php echo esc_js( get_bloginfo( 'description' ) ); ?>",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "<?php echo esc_url( home_url() ); ?>/?s={search_term_string}",
        "query-input": "required name=search_term_string"
    },
    "publisher": {
        "@type": "Organization",
        "name": "<?php echo esc_js( get_bloginfo( 'name' ) ); ?>",
        "logo": {
            "@type": "ImageObject",
            "url": "<?php echo esc_url( get_theme_file_uri( '/assets/images/logo.png' ) ); ?>"
        },
        "sameAs": [
            "https://www.facebook.com/tostishop",
            "https://www.instagram.com/tostishop", 
            "https://www.twitter.com/tostishop"
        ]
    }
}
</script>

<!-- Breadcrumbs Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "<?php echo esc_url( home_url() ); ?>"
        }
    ]
}
</script>

<?php get_footer(); ?>
