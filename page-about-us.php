<?php
/**
 * Template Name: About Us
 * 
 * TostiShop About Us Page Template
 * Mobile-First with Pure Tailwind CSS following brand color scheme
 */

defined('ABSPATH') || exit;
get_header(); 
?>

<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-navy-900 via-navy-800 to-navy-900 text-white pt-16 pb-40 relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-full text-sm font-semibold mb-6">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    Our Story
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">About TostiShop</h1>
                <p class="text-lg md:text-xl text-silver-50 max-w-3xl mx-auto">
                    Empowering beauty enthusiasts across India with authentic products, exceptional service, and unmatched convenience
                </p>
            </div>
        </div>
        <!-- Decorative wave -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#ffffff"/>
            </svg>
        </div>
    </section>

    <!-- Company Overview -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="inline-block bg-accent text-white px-3 py-1 rounded-full text-sm font-semibold mb-6">
                        Since 2019
                    </div>
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-navy-900 mb-6">
                        Revolutionizing Beauty Shopping in India
                    </h2>
                    <div class="space-y-6 text-gray-600 text-lg">
                        <p>
                            TostiShop began with a simple mission: to make authentic beauty and personal care products accessible to everyone across India. What started as a small venture has grown into a trusted platform serving thousands of customers nationwide.
                        </p>
                        <p>
                            We believe that everyone deserves to look and feel their best. That's why we've curated an extensive collection of genuine beauty products, from everyday essentials to premium cosmetics, all at competitive prices with nationwide delivery.
                        </p>
                        <p>
                            Our commitment to quality, authenticity, and customer satisfaction has made us a preferred destination for beauty enthusiasts seeking reliable products and exceptional service.
                        </p>
                    </div>
                </div>
                <div class="relative">
                    <div class="aspect-square bg-gradient-to-br from-accent to-red-600 rounded-2xl flex items-center justify-center">
                        <div class="text-white text-center p-8">
                            <svg class="w-24 h-24 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <h3 class="text-2xl font-bold mb-3">50,000+</h3>
                            <p class="text-lg">Happy Customers Nationwide</p>
                        </div>
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-navy-900 rounded-full opacity-20"></div>
                    <div class="absolute -bottom-6 -left-6 w-16 h-16 bg-accent rounded-full opacity-30"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission, Vision, Values -->
    <section class="py-20 bg-silver-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-navy-900 mb-6">
                    Our Foundation
                </h2>
                <p class="text-gray-600 max-w-3xl mx-auto text-lg">
                    The principles that guide everything we do at TostiShop
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Mission -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200 text-center">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Our Mission</h3>
                    <p class="text-gray-600 leading-relaxed">
                        To democratize beauty by providing authentic, high-quality products at affordable prices with exceptional customer service across India.
                    </p>
                </div>

                <!-- Vision -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200 text-center">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Our Vision</h3>
                    <p class="text-gray-600 leading-relaxed">
                        To become India's most trusted beauty platform, where every individual can discover, explore, and express their unique beauty confidently.
                    </p>
                </div>

                <!-- Values -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200 text-center">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Our Values</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Authenticity, quality, customer-centricity, innovation, and inclusivity form the core of our brand identity and business practices.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Company Stats -->
    <section class="py-20 bg-navy-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-6">
                    TostiShop by Numbers
                </h2>
                <p class="text-silver-50 max-w-3xl mx-auto text-lg">
                    Our growth reflects the trust and love of our customers across India
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-accent mb-3">50K+</div>
                    <div class="text-silver-50 text-lg">Happy Customers</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-accent mb-3">2000+</div>
                    <div class="text-silver-50 text-lg">Products Available</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-accent mb-3">500+</div>
                    <div class="text-silver-50 text-lg">Cities Covered</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-bold text-accent mb-3">99%</div>
                    <div class="text-silver-50 text-lg">Customer Satisfaction</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-navy-900 mb-6">
                    Why Choose TostiShop?
                </h2>
                <p class="text-gray-600 max-w-3xl mx-auto text-lg">
                    We go beyond just selling products - we're your trusted beauty partner
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Authentic Products -->
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-accent to-red-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">100% Authentic Products</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Every product is sourced directly from authorized distributors and brands, ensuring you receive only genuine items with full warranty protection.
                    </p>
                </div>

                <!-- Competitive Pricing -->
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-accent to-red-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Best Prices Guaranteed</h3>
                    <p class="text-gray-600 leading-relaxed">
                        We offer competitive pricing with regular deals and discounts, making premium beauty products accessible to everyone without compromising on quality.
                    </p>
                </div>

                <!-- Fast Delivery -->
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-accent to-red-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Express Delivery</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Quick and reliable delivery across India with real-time tracking, secure packaging, and special express delivery options for urgent orders.
                    </p>
                </div>

                <!-- Customer Support -->
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-accent to-red-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 11-9.75 9.75A9.75 9.75 0 0112 2.25z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">24/7 Customer Care</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Our dedicated customer support team is always ready to assist you with product queries, order tracking, and any concerns you may have.
                    </p>
                </div>

                <!-- Easy Returns -->
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-accent to-red-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Hassle-free Returns</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Easy return and exchange policy within 30 days of purchase, with quick refund processing and transparent return procedures.
                    </p>
                </div>

                <!-- Secure Shopping -->
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-accent to-red-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Secure Payments</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Multiple secure payment options including UPI, cards, net banking, and COD with industry-standard encryption for complete peace of mind.
                    </p>
                </div>
            </div>
        </div>
    </section>


    <!-- Contact CTA -->
    <section class="py-16 bg-navy-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-6">
                Ready to Experience the TostiShop Difference?
            </h2>
            <p class="text-silver-50 mb-8 max-w-2xl mx-auto text-lg">
                Join thousands of satisfied customers who trust TostiShop for their beauty and personal care needs
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
                   class="inline-flex items-center px-8 py-4 bg-accent text-white font-semibold rounded-lg hover:bg-red-600 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Start Shopping
                </a>
                <a href="/contact-us" 
                   class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-navy-900 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Contact Us
                </a>
            </div>
        </div>
    </section>
</div>

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
            "item": "<?php echo esc_url(home_url()); ?>"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "About Us",
            "item": "<?php echo esc_url(get_permalink()); ?>"
        }
    ]
}
</script>

<?php get_footer(); ?>