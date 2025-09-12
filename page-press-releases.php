<?php
/**
 * Template Name: Press Releases
 * 
 * TostiShop Press Releases Page Template
 * Mobile-First with Pure Tailwind CSS following brand color scheme
 */

defined('ABSPATH') || exit;
get_header(); 
?>

<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-navy-900 via-navy-800 to-navy-900 text-white py-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-full text-sm font-semibold mb-6">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    Latest Updates
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">Press Releases</h1>
                <p class="text-lg md:text-xl text-silver-50 max-w-3xl mx-auto">
                    Stay updated with the latest news, announcements, and milestones from TostiShop
                </p>
            </div>
        </div>
        <!-- Decorative wave -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Featured Press Release -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-12 border border-gray-100">
                <div class="md:flex">
                    <div class="md:w-1/3">
                        <div class="h-64 md:h-full bg-gradient-to-br from-accent to-red-600 flex items-center justify-center">
                            <div class="text-white text-center p-6">
                                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                <h3 class="text-xl font-bold">Featured News</h3>
                            </div>
                        </div>
                    </div>
                    <div class="md:w-2/3 p-8">
                        <div class="mb-4">
                            <span class="inline-block bg-accent text-white px-3 py-1 rounded-full text-sm font-semibold">Featured</span>
                            <span class="ml-3 text-gray-500 text-sm">December 2024</span>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-4">
                            TostiShop Reaches 50,000+ Happy Customers Milestone
                        </h2>
                        <p class="text-gray-600 text-lg mb-6">
                            We're thrilled to announce that TostiShop has successfully served over 50,000 customers across India, establishing ourselves as a trusted destination for beauty and personal care products with nationwide reach.
                        </p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-navy-900 rounded-full flex items-center justify-center text-white font-semibold">
                                    T
                                </div>
                                <div class="ml-3">
                                    <p class="font-semibold text-navy-900">TostiShop Team</p>
                                    <p class="text-sm text-gray-500">Official Announcement</p>
                                </div>
                            </div>
                            <button class="text-accent font-semibold hover:text-red-600 transition-colors">
                                Read Full Story →
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Press Releases Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Press Release 1 -->
                <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                    <div class="aspect-video bg-gradient-to-br from-navy-900 to-navy-800 flex items-center justify-center">
                        <div class="text-white text-center">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <p class="text-sm font-medium">Product Launch</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <time>November 15, 2024</time>
                            <span class="mx-2">•</span>
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">Product</span>
                        </div>
                        <h3 class="text-xl font-semibold text-navy-900 mb-3 group-hover:text-accent transition-colors">
                            New Winter Collection 2024 Launch
                        </h3>
                        <p class="text-gray-600 mb-4">
                            Introducing our exclusive winter collection featuring premium beauty and skincare products designed for the cold season.
                        </p>
                        <button class="text-accent font-semibold hover:text-red-600 transition-colors">
                            Read More →
                        </button>
                    </div>
                </article>

                <!-- Press Release 2 -->
                <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                    <div class="aspect-video bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                        <div class="text-white text-center">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm font-medium">Expansion</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <time>October 28, 2024</time>
                            <span class="mx-2">•</span>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">Growth</span>
                        </div>
                        <h3 class="text-xl font-semibold text-navy-900 mb-3 group-hover:text-accent transition-colors">
                            Expanding Delivery Network to Remote Areas
                        </h3>
                        <p class="text-gray-600 mb-4">
                            TostiShop now delivers to 500+ additional pin codes, bringing beauty products to previously unreachable locations.
                        </p>
                        <button class="text-accent font-semibold hover:text-red-600 transition-colors">
                            Read More →
                        </button>
                    </div>
                </article>

                <!-- Press Release 3 -->
                <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                    <div class="aspect-video bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                        <div class="text-white text-center">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <p class="text-sm font-medium">Partnership</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <time>October 10, 2024</time>
                            <span class="mx-2">•</span>
                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs font-medium">Partnership</span>
                        </div>
                        <h3 class="text-xl font-semibold text-navy-900 mb-3 group-hover:text-accent transition-colors">
                            Strategic Partnership with Leading Beauty Brands
                        </h3>
                        <p class="text-gray-600 mb-4">
                            New exclusive partnerships bring premium international beauty brands to Indian customers at competitive prices.
                        </p>
                        <button class="text-accent font-semibold hover:text-red-600 transition-colors">
                            Read More →
                        </button>
                    </div>
                </article>

                <!-- Press Release 4 -->
                <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                    <div class="aspect-video bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center">
                        <div class="text-white text-center">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm font-medium">Achievement</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <time>September 22, 2024</time>
                            <span class="mx-2">•</span>
                            <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs font-medium">Award</span>
                        </div>
                        <h3 class="text-xl font-semibold text-navy-900 mb-3 group-hover:text-accent transition-colors">
                            Best Customer Service Award 2024
                        </h3>
                        <p class="text-gray-600 mb-4">
                            TostiShop receives recognition for outstanding customer service and satisfaction ratings in the e-commerce sector.
                        </p>
                        <button class="text-accent font-semibold hover:text-red-600 transition-colors">
                            Read More →
                        </button>
                    </div>
                </article>

                <!-- Press Release 5 -->
                <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                    <div class="aspect-video bg-gradient-to-br from-accent to-red-600 flex items-center justify-center">
                        <div class="text-white text-center">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <p class="text-sm font-medium">Innovation</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <time>August 15, 2024</time>
                            <span class="mx-2">•</span>
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-medium">Tech</span>
                        </div>
                        <h3 class="text-xl font-semibold text-navy-900 mb-3 group-hover:text-accent transition-colors">
                            AI-Powered Beauty Recommendations Launch
                        </h3>
                        <p class="text-gray-600 mb-4">
                            Revolutionary AI technology helps customers find perfect beauty products based on their skin type and preferences.
                        </p>
                        <button class="text-accent font-semibold hover:text-red-600 transition-colors">
                            Read More →
                        </button>
                    </div>
                </article>

                <!-- Press Release 6 -->
                <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                    <div class="aspect-video bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center">
                        <div class="text-white text-center">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <p class="text-sm font-medium">Social Impact</p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-3">
                            <time>July 30, 2024</time>
                            <span class="mx-2">•</span>
                            <span class="bg-teal-100 text-teal-800 px-2 py-1 rounded text-xs font-medium">CSR</span>
                        </div>
                        <h3 class="text-xl font-semibold text-navy-900 mb-3 group-hover:text-accent transition-colors">
                            TostiShop Sustainability Initiative
                        </h3>
                        <p class="text-gray-600 mb-4">
                            Launching eco-friendly packaging and carbon-neutral delivery options as part of our environmental commitment.
                        </p>
                        <button class="text-accent font-semibold hover:text-red-600 transition-colors">
                            Read More →
                        </button>
                    </div>
                </article>
            </div>

            <!-- Newsletter Signup -->
            <div class="mt-16 bg-navy-900 rounded-xl p-8 md:p-12 text-center text-white">
                <h3 class="text-2xl md:text-3xl font-bold mb-4">Stay Updated</h3>
                <p class="text-silver-50 mb-8 max-w-2xl mx-auto">
                    Subscribe to our press releases and be the first to know about TostiShop's latest news and announcements.
                </p>
                <div class="max-w-md mx-auto flex flex-col sm:flex-row gap-4">
                    <input 
                        type="email" 
                        placeholder="Enter your email"
                        class="flex-1 px-4 py-3 rounded-lg text-navy-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent"
                    >
                    <button class="px-6 py-3 bg-accent text-white font-semibold rounded-lg hover:bg-red-600 transition-colors whitespace-nowrap">
                        Subscribe
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Press Contact Section -->
    <section class="py-16 bg-silver-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-4">Media Contact</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    For press inquiries and media requests, please reach out to our communications team
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Press Contact -->
                <div class="bg-white rounded-lg p-6 text-center shadow-sm border border-gray-200">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-navy-900 mb-2">Email</h3>
                    <p class="text-gray-600">contact@tostishop.com</p>
                </div>

                <!-- Phone Contact -->
                <div class="bg-white rounded-lg p-6 text-center shadow-sm border border-gray-200">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-navy-900 mb-2">Phone</h3>
                    <p class="text-gray-600">+91-79829 99145</p>
                </div>

                <!-- Response Time -->
                <div class="bg-white rounded-lg p-6 text-center shadow-sm border border-gray-200">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-navy-900 mb-2">Response Time</h3>
                    <p class="text-gray-600">24-48 hours</p>
                </div>
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
            "name": "Press Releases",
            "item": "<?php echo esc_url(get_permalink()); ?>"
        }
    ]
}
</script>

<?php get_footer(); ?>