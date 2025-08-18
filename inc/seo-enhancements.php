<?php
/**
 * TostiShop SEO Enhancements
 * Advanced SEO features for search engines and AI chat models
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enhanced SEO Meta Tags
 */
function tostishop_enhanced_seo_meta() {
    global $post, $product;
    
    // Core meta tags
    echo '<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">' . "\n";
    echo '<meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">' . "\n";
    
    // Language and region
    echo '<meta name="language" content="' . get_locale() . '">' . "\n";
    echo '<meta name="geo.region" content="IN">' . "\n";
    echo '<meta name="geo.country" content="India">' . "\n";
    
    // Business information
    echo '<meta name="business:contact_data:street_address" content="L246, 2nd Floor, Sector-12, Pratap Vihar">' . "\n";
    echo '<meta name="business:contact_data:locality" content="Ghaziabad">' . "\n";
    echo '<meta name="business:contact_data:region" content="Uttar Pradesh">' . "\n";
    echo '<meta name="business:contact_data:postal_code" content="201009">' . "\n";
    echo '<meta name="business:contact_data:country_name" content="India">' . "\n";
    echo '<meta name="business:contact_data:email" content="contact@tostishop.com">' . "\n";
    echo '<meta name="business:contact_data:phone_number" content="+91-79829 99145">' . "\n";
    echo '<meta name="business:contact_data:website" content="' . home_url() . '">' . "\n";
    
    // Author and publisher information
    echo '<meta name="author" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    echo '<meta name="publisher" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    
    // Page-specific meta tags
    if (is_front_page()) {
        echo '<meta name="description" content="' . esc_attr(get_bloginfo('description') . ' - Shop quality products online with fast delivery across India.') . '">' . "\n";
        echo '<meta name="keywords" content="online shopping, ecommerce, quality products, fast delivery, india">' . "\n";
    } elseif (is_product()) {
        // Get the current product object properly
        global $woocommerce, $product;
        if (!$product) {
            $product = wc_get_product(get_the_ID());
        }
        
        if ($product && is_object($product)) {
            $description = wp_strip_all_tags($product->get_short_description() ?: $product->get_description());
            $description = wp_trim_words($description, 30, '...');
            echo '<meta name="description" content="' . esc_attr($description) . ' Buy online at ' . get_bloginfo('name') . ' with fast delivery.">' . "\n";
            
            // Product-specific keywords
            $categories = get_the_terms($product->get_id(), 'product_cat');
            $keywords = [];
            if ($categories && !is_wp_error($categories)) {
                foreach ($categories as $category) {
                    $keywords[] = $category->name;
                }
            }
            $keywords[] = $product->get_name();
            echo '<meta name="keywords" content="' . esc_attr(implode(', ', $keywords)) . '">' . "\n";
        }
    } elseif (is_product_category()) {
        $term = get_queried_object();
        echo '<meta name="description" content="Shop ' . esc_attr($term->name) . ' products online at ' . get_bloginfo('name') . '. Quality products with fast delivery across India.">' . "\n";
    }
    
    // Canonical URL
    echo '<link rel="canonical" href="' . esc_url(get_permalink()) . '">' . "\n";
    
    // Mobile optimization
    echo '<meta name="format-detection" content="telephone=yes">' . "\n";
    echo '<meta name="mobile-web-app-capable" content="yes">' . "\n";
    echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
    echo '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">' . "\n";
}
add_action('wp_head', 'tostishop_enhanced_seo_meta', 1);

/**
 * JSON-LD Structured Data for Business Information
 */
function tostishop_business_structured_data() {
    $business_data = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => get_bloginfo('name'),
        'description' => 'Online beauty and personal care store offering jewelry, cosmetics, and personal care products with fast delivery across India.',
        'url' => home_url(),
        'logo' => get_template_directory_uri() . '/assets/images/logo.png',
        'image' => get_template_directory_uri() . '/assets/images/logo-big.png',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'L246, 2nd Floor, Sector-12, Pratap Vihar',
            'addressLocality' => 'Ghaziabad',
            'addressRegion' => 'Uttar Pradesh',
            'postalCode' => '201009',
            'addressCountry' => 'IN'
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => '+91-79829 99145',
            'contactType' => 'customer service',
            'availableLanguage' => ['Hindi', 'English'],
            'hoursAvailable' => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => [
                    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
                ],
                'opens' => '11:00',
                'closes' => '18:00'
            ]
        ],
        'email' => 'contact@tostishop.com',
        'priceRange' => '₹₹',
        'paymentAccepted' => ['UPI', 'Credit Card', 'Debit Card', 'Net Banking'],
        'currenciesAccepted' => 'INR',
        'areaServed' => [
            '@type' => 'Country',
            'name' => 'India'
        ],
        'sameAs' => [
            'https://www.facebook.com/tostishop',
            'https://www.instagram.com/tostishop',
            'https://www.pinterest.com/tostishop'
        ],
        'foundingDate' => '2024',
        'slogan' => 'Your one-stop shop for beauty and personal care',
        'knowsAbout' => [
            'Beauty Products',
            'Cosmetics',
            'Jewelry',
            'Personal Care',
            'Skincare',
            'Makeup',
            'Hair Care'
        ]
    ];

    if (function_exists('wc_get_base_location')) {
        $business_data['@type'] = 'Store';
        $business_data['hasOfferCatalog'] = [
            '@type' => 'OfferCatalog',
            'name' => 'Beauty & Personal Care Products',
            'itemListElement' => [
                [
                    '@type' => 'OfferCatalog',
                    'name' => 'Cosmetics',
                    'description' => 'Wide range of makeup and beauty products'
                ],
                [
                    '@type' => 'OfferCatalog', 
                    'name' => 'Jewelry',
                    'description' => 'Fashion jewelry and accessories'
                ],
                [
                    '@type' => 'OfferCatalog',
                    'name' => 'Personal Care',
                    'description' => 'Skincare, hair care and wellness products'
                ]
            ]
        ];
    }

    echo '<script type="application/ld+json">' . json_encode($business_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'tostishop_business_structured_data', 2);

/**
 * Enhanced Open Graph and Twitter Cards
 */
function tostishop_enhanced_social_meta() {
    global $post, $product;
    
    // Open Graph
    echo '<meta property="og:locale" content="' . esc_attr(str_replace('-', '_', get_locale())) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    
    if (is_front_page()) {
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name') . ' - ' . get_bloginfo('description')) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url(home_url()) . '">' . "\n";
        
        // Use hero image or logo
        $hero_image = get_theme_mod('hero_image');
        $og_image = $hero_image ?: get_theme_file_uri('/assets/images/logo-big.png');
        echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
        
    } elseif (is_product()) {
        // Get the current product object properly
        global $woocommerce, $product;
        if (!$product) {
            $product = wc_get_product(get_the_ID());
        }
        
        if ($product && is_object($product)) {
            echo '<meta property="og:type" content="product">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr($product->get_name()) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
            
            // Product image
            if ($product->get_image_id()) {
                $image_url = wp_get_attachment_image_url($product->get_image_id(), 'large');
                echo '<meta property="og:image" content="' . esc_url($image_url) . '">' . "\n";
            }
            
            // Product-specific OG tags
            echo '<meta property="product:brand" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
            echo '<meta property="product:availability" content="' . ($product->is_in_stock() ? 'in stock' : 'out of stock') . '">' . "\n";
            echo '<meta property="product:condition" content="new">' . "\n";
            echo '<meta property="product:price:amount" content="' . esc_attr($product->get_price()) . '">' . "\n";
            echo '<meta property="product:price:currency" content="' . esc_attr(get_woocommerce_currency()) . '">' . "\n";
        }
    }
    
    // Twitter Cards
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:site" content="@tostishop">' . "\n";
    echo '<meta name="twitter:creator" content="@tostishop">' . "\n";
    
    // Additional social platforms
    echo '<meta property="fb:app_id" content="YOUR_FACEBOOK_APP_ID">' . "\n";
    echo '<meta property="fb:pages" content="YOUR_FACEBOOK_PAGE_ID">' . "\n";
}
add_action('wp_head', 'tostishop_enhanced_social_meta', 2);

/**
 * Enhanced Structured Data for AI Chat Models
 */
function tostishop_ai_chat_structured_data() {
    // Website/Organization schema
    $organization_schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'logo' => array(
            '@type' => 'ImageObject',
            'url' => get_theme_file_uri('/assets/images/logo.png'),
            'width' => 200,
            'height' => 60
        ),
        'description' => get_bloginfo('description'),
        'address' => array(
            '@type' => 'PostalAddress',
            'addressCountry' => 'IN',
            'addressRegion' => 'Your State',
            'addressLocality' => 'Your City',
            'postalCode' => 'Your PIN',
            'streetAddress' => 'Your Store Address'
        ),
        'contactPoint' => array(
            '@type' => 'ContactPoint',
            'telephone' => '+91-79829-99145',
            'contactType' => 'customer service',
            'availableLanguage' => ['English', 'Hindi'],
            'areaServed' => 'IN'
        ),
        'sameAs' => array(
            'https://www.facebook.com/tostishop',
            'https://www.instagram.com/tostishop',
            'https://www.twitter.com/tostishop'
        )
    );
    
    // E-commerce specific schema
    if (function_exists('WC')) {
        $organization_schema['@type'] = ['Organization', 'OnlineStore'];
        $organization_schema['currenciesAccepted'] = 'INR';
        $organization_schema['paymentAccepted'] = ['Cash on Delivery', 'Credit Card', 'Debit Card', 'UPI'];
        $organization_schema['priceRange'] = '₹100-₹10000';
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($organization_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    
    // Website search schema
    $website_schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => array(
                '@type' => 'EntryPoint',
                'urlTemplate' => home_url() . '/?s={search_term_string}&post_type=product'
            ),
            'query-input' => 'required name=search_term_string'
        )
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode($website_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'tostishop_ai_chat_structured_data', 3);

/**
 * FAQ Schema for AI Understanding
 */
function tostishop_faq_schema() {
    if (is_front_page()) {
        $faq_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array(
                array(
                    '@type' => 'Question',
                    'name' => 'What payment methods do you accept?',
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => 'We accept Cash on Delivery (COD), Credit Cards, Debit Cards, UPI, and Net Banking.'
                    )
                ),
                array(
                    '@type' => 'Question',
                    'name' => 'Do you deliver across India?',
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => 'Yes, we deliver across India with fast and reliable shipping.'
                    )
                ),
                array(
                    '@type' => 'Question',
                    'name' => 'What is your return policy?',
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => 'We offer hassle-free returns within 7-14 days of delivery.'
                    )
                ),
                array(
                    '@type' => 'Question',
                    'name' => 'How can I track my order?',
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => 'You can track your order using the tracking link sent to your email or by logging into your account.'
                    )
                )
            )
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}
add_action('wp_head', 'tostishop_faq_schema', 4);

/**
 * AI-Friendly Site Map Generator
 */
function tostishop_generate_ai_sitemap() {
    // This creates a simple HTML sitemap for AI crawlers
    if (isset($_GET['ai-sitemap'])) {
        header('Content-Type: text/html; charset=utf-8');
        
        echo '<!DOCTYPE html><html><head>';
        echo '<title>Site Map - ' . get_bloginfo('name') . '</title>';
        echo '<meta name="robots" content="index, follow">';
        echo '</head><body>';
        echo '<h1>Site Map - ' . get_bloginfo('name') . '</h1>';
        
        // Homepage
        echo '<h2>Main Pages</h2><ul>';
        echo '<li><a href="' . home_url() . '">Homepage</a></li>';
        
        if (function_exists('WC')) {
            echo '<li><a href="' . wc_get_page_permalink('shop') . '">Shop</a></li>';
            echo '<li><a href="' . wc_get_page_permalink('cart') . '">Cart</a></li>';
            echo '<li><a href="' . wc_get_page_permalink('checkout') . '">Checkout</a></li>';
            echo '<li><a href="' . wc_get_page_permalink('myaccount') . '">My Account</a></li>';
        }
        echo '</ul>';
        
        // Product categories
        if (function_exists('WC')) {
            $categories = get_terms('product_cat', array('hide_empty' => true));
            if ($categories) {
                echo '<h2>Product Categories</h2><ul>';
                foreach ($categories as $category) {
                    echo '<li><a href="' . get_term_link($category) . '">' . $category->name . '</a></li>';
                }
                echo '</ul>';
            }
        }
        
        // Recent products
        if (function_exists('WC')) {
            $products = wc_get_products(array('limit' => 20, 'status' => 'publish'));
            if ($products) {
                echo '<h2>Featured Products</h2><ul>';
                foreach ($products as $product) {
                    echo '<li><a href="' . get_permalink($product->get_id()) . '">' . $product->get_name() . '</a></li>';
                }
                echo '</ul>';
            }
        }
        
        echo '</body></html>';
        exit;
    }
}
add_action('init', 'tostishop_generate_ai_sitemap');

/**
 * Enhanced robots.txt for AI crawlers
 */
function tostishop_enhanced_robots_txt($output, $public) {
    if ($public) {
        $output .= "User-agent: ChatGPT-User\n";
        $output .= "Allow: /\n\n";
        
        $output .= "User-agent: Claude-Web\n";
        $output .= "Allow: /\n\n";
        
        $output .= "User-agent: Bard\n";
        $output .= "Allow: /\n\n";
        
        $output .= "User-agent: *\n";
        $output .= "Allow: /\n";
        $output .= "Disallow: /wp-admin/\n";
        $output .= "Disallow: /wp-includes/\n";
        $output .= "Disallow: /wp-content/uploads/woocommerce_uploads/\n";
        $output .= "Disallow: /cart/\n";
        $output .= "Disallow: /checkout/\n";
        $output .= "Disallow: /my-account/\n\n";
        
        $output .= "Sitemap: " . home_url() . "/wp-sitemap.xml\n";
        $output .= "Sitemap: " . home_url() . "/?ai-sitemap=1\n";
    }
    
    return $output;
}
add_filter('robots_txt', 'tostishop_enhanced_robots_txt', 10, 2);

/**
 * Performance optimizations for SEO
 */
function tostishop_seo_performance_optimizations() {
    // Preload critical resources
    echo '<link rel="preload" href="' . get_theme_file_uri('/assets/css/main.css') . '" as="style">' . "\n";
    echo '<link rel="preload" href="' . get_theme_file_uri('/assets/js/alpine.min.js') . '" as="script">' . "\n";
    
    // DNS prefetch for external resources
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
    
    // Preconnect to critical domains
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action('wp_head', 'tostishop_seo_performance_optimizations', 0);

/**
 * AI Chat Helper - Structured Data for Chat Understanding
 */
function tostishop_ai_chat_helper() {
    // Add invisible content that helps AI understand the site better
    if (is_front_page()) {
        echo '<div style="display: none;" data-ai-context="store-info">';
        echo 'TostiShop is an online store based in India offering quality products with fast delivery. ';
        echo 'We accept COD, cards, and UPI payments. We deliver across India. ';
        echo 'Customer support available via WhatsApp at +91 79829 99145. ';
        echo 'We offer hassle-free returns and exchanges. ';
        echo 'Our specialties include quality products at competitive prices.';
        echo '</div>';
    }
}
add_action('wp_footer', 'tostishop_ai_chat_helper');
