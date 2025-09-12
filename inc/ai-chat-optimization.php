<?php
/**
 * AI Chat Model Optimization
 * Enhanced website information for AI systems
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AI-Optimized Business Information
 */
function tostishop_ai_business_context() {
    $ai_context = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'TostiShop',
        'alternateName' => ['Tosti Shop', 'TostiShop.com'],
        'description' => 'Premium online beauty and personal care store specializing in cosmetics, jewelry, and personal care products with fast delivery across India.',
        'url' => home_url(),
        'logo' => get_template_directory_uri() . '/assets/images/logo.png',
        'foundingDate' => '2024',
        'slogan' => 'Your one-stop shop for beauty and personal care',
        
        // Contact Information
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => '+91-79829 99145',
            'contactType' => 'customer service',
            'areaServed' => 'IN',
            'availableLanguage' => ['Hindi', 'English'],
            'serviceUrl' => home_url('/contact-us/'),
            'hoursAvailable' => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                'opens' => '11:00',
                'closes' => '18:00',
                'validFrom' => '2024-01-01',
                'validThrough' => '2025-12-31'
            ]
        ],
        
        // Physical Address
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'L246, 2nd Floor, Sector-12, Pratap Vihar',
            'addressLocality' => 'Ghaziabad',
            'addressRegion' => 'Uttar Pradesh',
            'postalCode' => '201009',
            'addressCountry' => 'IN'
        ],
        
        // Email
        'email' => 'contact@tostishop.com',
        
        // Business Categories
        'knowsAbout' => [
            'Beauty Products',
            'Cosmetics and Makeup',
            'Fashion Jewelry',
            'Personal Care Items',
            'Skincare Products',
            'Hair Care',
            'Wellness Products',
            'Beauty Accessories',
            'Gift Items',
            'Online Shopping'
        ],
        
        // Service Areas
        'areaServed' => [
            '@type' => 'Country',
            'name' => 'India',
            'description' => 'Nationwide delivery across India with special focus on NCR region'
        ],
        
        // Social Media
        'sameAs' => [
            'https://www.facebook.com/tostishop',
            'https://www.instagram.com/tostishop',
            'https://www.pinterest.com/tostishop'
        ],
        
        // Business Features
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => 'Beauty & Personal Care Products',
            'itemListElement' => [
                [
                    '@type' => 'OfferCatalog',
                    'name' => 'Cosmetics & Makeup',
                    'description' => 'Wide range of makeup products including foundation, lipstick, eyeshadow, mascara, and more'
                ],
                [
                    '@type' => 'OfferCatalog',
                    'name' => 'Fashion Jewelry',
                    'description' => 'Trendy jewelry pieces including earrings, necklaces, bracelets, and rings'
                ],
                [
                    '@type' => 'OfferCatalog',
                    'name' => 'Personal Care',
                    'description' => 'Skincare, hair care, and wellness products for daily beauty routine'
                ]
            ]
        ],
        
        // Payment and Delivery
        'paymentAccepted' => [
            'UPI',
            'Credit Card',
            'Debit Card',
            'Net Banking',
            'Digital Wallets'
        ],
        'currenciesAccepted' => 'INR',
        'priceRange' => '₹₹',
        
        // AI-Specific Context
        'additionalProperty' => [
            [
                '@type' => 'PropertyValue',
                'name' => 'Business Type',
                'value' => 'E-commerce Beauty Store'
            ],
            [
                '@type' => 'PropertyValue',
                'name' => 'Target Audience',
                'value' => 'Beauty enthusiasts, fashion-conscious individuals, gift shoppers'
            ],
            [
                '@type' => 'PropertyValue',
                'name' => 'Delivery Area',
                'value' => 'Pan India with express delivery in NCR'
            ],
            [
                '@type' => 'PropertyValue',
                'name' => 'Specialization',
                'value' => 'Premium beauty products at affordable prices'
            ]
        ]
    ];

    echo '<script type="application/ld+json">' . json_encode($ai_context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>' . "\n";
}

/**
 * AI-Optimized FAQ Schema for Voice Search
 */
function tostishop_ai_faq_schema() {
    $faqs = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name' => 'What products does TostiShop sell?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'TostiShop specializes in beauty and personal care products including cosmetics, makeup, fashion jewelry, skincare products, hair care items, and personal care accessories.'
                ]
            ],
            [
                '@type' => 'Question',
                'name' => 'Where is TostiShop located?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'TostiShop is located at L246, 2nd Floor, Sector-12, Pratap Vihar, Ghaziabad, Uttar Pradesh 201009, India.'
                ]
            ],
            [
                '@type' => 'Question',
                'name' => 'How can I contact TostiShop?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'You can contact TostiShop via WhatsApp at +91-79829 99145 (11 AM to 6 PM) or email at contact@tostishop.com'
                ]
            ],
            [
                '@type' => 'Question',
                'name' => 'What are TostiShop business hours?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'TostiShop customer service is available Monday to Saturday from 11 AM to 6 PM Indian Standard Time.'
                ]
            ],
            [
                '@type' => 'Question',
                'name' => 'Does TostiShop deliver across India?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'Yes, TostiShop provides nationwide delivery across India with express delivery options available in the NCR region.'
                ]
            ],
            [
                '@type' => 'Question',
                'name' => 'What payment methods does TostiShop accept?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'TostiShop accepts UPI, credit cards, debit cards, net banking, Cash on Delivery (COD), and various digital wallets for secure payments.'
                ]
            ],
            [
                '@type' => 'Question',
                'name' => 'What is TostiShop return policy?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'TostiShop offers hassle-free returns within 7-14 days of delivery for eligible products.'
                ]
            ],
            [
                '@type' => 'Question',
                'name' => 'How can I track my TostiShop order?',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => 'You can track your order using the tracking link sent to your email or by logging into your TostiShop account dashboard.'
                ]
            ]
        ]
    ];

    echo '<script type="application/ld+json">' . json_encode($faqs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}

/**
 * Website Navigation Schema for AI Understanding
 */
function tostishop_sitemap_schema() {
    $sitemap = [
        '@context' => 'https://schema.org',
        '@type' => 'SiteNavigationElement',
        'name' => 'TostiShop Navigation',
        'url' => home_url(),
        'hasPart' => [
            [
                '@type' => 'SiteNavigationElement',
                'name' => 'Home',
                'description' => 'TostiShop homepage with featured products and offers',
                'url' => home_url()
            ],
            [
                '@type' => 'SiteNavigationElement',
                'name' => 'Shop',
                'description' => 'Browse all beauty and personal care products',
                'url' => function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/')
            ],
            [
                '@type' => 'SiteNavigationElement',
                'name' => 'Cosmetics',
                'description' => 'Makeup and cosmetic products',
                'url' => home_url('/product-category/cosmetics/')
            ],
            [
                '@type' => 'SiteNavigationElement',
                'name' => 'Jewelry',
                'description' => 'Fashion jewelry and accessories',
                'url' => home_url('/product-category/jewelry/')
            ],
            [
                '@type' => 'SiteNavigationElement',
                'name' => 'Personal Care',
                'description' => 'Skincare and personal care products',
                'url' => home_url('/product-category/personal-care/')
            ],
            [
                '@type' => 'SiteNavigationElement',
                'name' => 'Contact',
                'description' => 'Get in touch with TostiShop customer service',
                'url' => home_url('/contact-us/')
            ]
        ]
    ];

    echo '<script type="application/ld+json">' . json_encode($sitemap, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}

/**
 * AI Chat Assistant Information
 */
function tostishop_ai_assistant_meta() {
    echo '<meta name="ai:business_name" content="TostiShop">' . "\n";
    echo '<meta name="ai:business_type" content="E-commerce Beauty Store">' . "\n";
    echo '<meta name="ai:contact_phone" content="+91-79829 99145">' . "\n";
    echo '<meta name="ai:contact_email" content="contact@tostishop.com">' . "\n";
    echo '<meta name="ai:address" content="L246, 2nd Floor, Sector-12, Pratap Vihar, Ghaziabad UP 201009">' . "\n";
    echo '<meta name="ai:business_hours" content="Monday-Saturday 11:00-18:00 IST">' . "\n";
    echo '<meta name="ai:service_area" content="India">' . "\n";
    echo '<meta name="ai:products" content="Cosmetics, Jewelry, Personal Care, Beauty Products">' . "\n";
    echo '<meta name="ai:payment_methods" content="UPI, Credit Card, Debit Card, Net Banking">' . "\n";
    echo '<meta name="ai:delivery" content="Nationwide India delivery">' . "\n";
    echo '<meta name="ai:languages" content="Hindi, English">' . "\n";
    echo '<meta name="ai:founded" content="2024">' . "\n";
}

// Hook the functions
add_action('wp_head', 'tostishop_ai_business_context', 3);
add_action('wp_head', 'tostishop_ai_faq_schema', 4);
add_action('wp_head', 'tostishop_sitemap_schema', 5);
add_action('wp_head', 'tostishop_ai_assistant_meta', 6);

/**
 * AI-Readable Microdata for Chat Systems
 */
function tostishop_microdata_output() {
    if (is_front_page()) {
        echo '<div itemscope itemtype="https://schema.org/LocalBusiness" style="display:none;">';
        echo '<span itemprop="name">TostiShop</span>';
        echo '<span itemprop="description">Premium online beauty and personal care store in India</span>';
        echo '<div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">';
        echo '<span itemprop="streetAddress">L246, 2nd Floor, Sector-12, Pratap Vihar</span>';
        echo '<span itemprop="addressLocality">Ghaziabad</span>';
        echo '<span itemprop="addressRegion">Uttar Pradesh</span>';
        echo '<span itemprop="postalCode">201009</span>';
        echo '<span itemprop="addressCountry">India</span>';
        echo '</div>';
        echo '<span itemprop="telephone">+91-79829 99145</span>';
        echo '<span itemprop="email">contact@tostishop.com</span>';
        echo '<div itemprop="openingHours" content="Mo-Sa 11:00-18:00">Monday-Saturday 11 AM to 6 PM</div>';
        echo '</div>';
    }
}
add_action('wp_footer', 'tostishop_microdata_output');

/**
 * Enhanced AI Search Context
 */
function tostishop_ai_search_context() {
    // Add search-specific context for AI systems
    $search_context = [
        'business' => [
            'name' => 'TostiShop',
            'type' => 'Beauty E-commerce Store',
            'location' => 'Ghaziabad, Uttar Pradesh, India',
            'established' => '2024',
            'specialization' => 'Beauty, Cosmetics, Jewelry, Personal Care'
        ],
        'contact' => [
            'address' => 'L246, 2nd Floor, Sector-12, Pratap Vihar, Ghaziabad UP 201009',
            'phone' => '+91-79829 99145',
            'email' => 'contact@tostishop.com',
            'hours' => '11 AM to 6 PM (Monday to Saturday)',
            'website' => home_url()
        ],
        'services' => [
            'products' => ['Cosmetics', 'Makeup', 'Jewelry', 'Personal Care', 'Skincare', 'Hair Care'],
            'delivery' => 'Nationwide India',
            'payment' => ['UPI', 'Cards', 'Net Banking', 'Digital Wallets'],
            'languages' => ['Hindi', 'English']
        ]
    ];
    
    echo '<script type="text/plain" id="ai-context-data">' . json_encode($search_context) . '</script>' . "\n";
}
add_action('wp_head', 'tostishop_ai_search_context', 7);
