<?php
/**
 * Template Name: 100% Purchase Protection
 * 
 * TostiShop Purchase Protection Page Template
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
                <div class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-full text-sm font-semibold mb-6">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Guaranteed Security
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">
                    <span class="text-green-400">100%</span> Purchase Protection
                </h1>
                <p class="text-lg md:text-xl text-silver-50 max-w-3xl mx-auto">
                    Shop with complete confidence - Your money is 100% protected with our comprehensive buyer protection program
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

    <!-- Protection Promise -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-navy-900 mb-6">
                    Our Protection Promise
                </h2>
                <p class="text-gray-600 max-w-3xl mx-auto text-lg">
                    Every purchase on TostiShop is backed by our comprehensive protection program
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Authentic Products -->
                <div class="bg-white rounded-xl p-8 shadow-lg border-2 border-green-200 text-center hover:shadow-xl transition-shadow">
                    <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Authentic Products Only</h3>
                    <p class="text-gray-600 leading-relaxed">
                        100% genuine products sourced directly from authorized brands and distributors. We guarantee authenticity or your money back.
                    </p>
                </div>

                <!-- Secure Payments -->
                <div class="bg-white rounded-xl p-8 shadow-lg border-2 border-blue-200 text-center hover:shadow-xl transition-shadow">
                    <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Secure Transactions</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Bank-level encryption and SSL security protect your payment information. Your financial data is never stored or compromised.
                    </p>
                </div>

                <!-- Money Back -->
                <div class="bg-white rounded-xl p-8 shadow-lg border-2 border-accent text-center hover:shadow-xl transition-shadow">
                    <div class="w-20 h-20 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Money Back Guarantee</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Not satisfied? Get a full refund within 30 days. No questions asked, no complicated procedures - just simple returns.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 bg-silver-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-navy-900 mb-6">
                    How Purchase Protection Works
                </h2>
                <p class="text-gray-600 max-w-3xl mx-auto text-lg">
                    Simple, transparent protection that covers every aspect of your shopping experience
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="relative mb-8">
                        <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto text-2xl font-bold text-white">
                            1
                        </div>
                        <div class="absolute top-8 left-8 hidden lg:block w-full h-0.5 bg-gray-300"></div>
                    </div>
                    <h3 class="text-lg font-semibold text-navy-900 mb-3">Shop Safely</h3>
                    <p class="text-gray-600">Browse and purchase from our 100% authentic product catalog with complete peace of mind.</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="relative mb-8">
                        <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto text-2xl font-bold text-white">
                            2
                        </div>
                        <div class="absolute top-8 left-8 hidden lg:block w-full h-0.5 bg-gray-300"></div>
                    </div>
                    <h3 class="text-lg font-semibold text-navy-900 mb-3">Secure Payment</h3>
                    <p class="text-gray-600">Your payment is processed through encrypted channels and held securely until delivery confirmation.</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="relative mb-8">
                        <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto text-2xl font-bold text-white">
                            3
                        </div>
                        <div class="absolute top-8 left-8 hidden lg:block w-full h-0.5 bg-gray-300"></div>
                    </div>
                    <h3 class="text-lg font-semibold text-navy-900 mb-3">Protected Delivery</h3>
                    <p class="text-gray-600">Receive your authentic products with tracking protection and damage coverage during transit.</p>
                </div>

                <!-- Step 4 -->
                <div class="text-center">
                    <div class="mb-8">
                        <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto text-2xl font-bold text-white">
                            4
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-navy-900 mb-3">Guaranteed Satisfaction</h3>
                    <p class="text-gray-600">Enjoy your purchase knowing you're covered by our 30-day money-back guarantee.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Protection Coverage -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-navy-900 mb-6">
                    What's Covered
                </h2>
                <p class="text-gray-600 max-w-3xl mx-auto text-lg">
                    Comprehensive protection covering every possible concern
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="space-y-6">
                        <!-- Coverage Item 1 -->
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-4 mt-1">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-navy-900 mb-2">Product Not as Described</h4>
                                <p class="text-gray-600">Full refund if the product doesn't match the description or images on our website.</p>
                            </div>
                        </div>

                        <!-- Coverage Item 2 -->
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-4 mt-1">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-navy-900 mb-2">Counterfeit Products</h4>
                                <p class="text-gray-600">Immediate refund and replacement if you receive a fake or counterfeit product.</p>
                            </div>
                        </div>

                        <!-- Coverage Item 3 -->
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-4 mt-1">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-navy-900 mb-2">Damaged in Transit</h4>
                                <p class="text-gray-600">Full compensation for products damaged during shipping with photographic evidence.</p>
                            </div>
                        </div>

                        <!-- Coverage Item 4 -->
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-4 mt-1">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-navy-900 mb-2">Non-Delivery</h4>
                                <p class="text-gray-600">Complete refund if your order doesn't arrive within the promised timeframe.</p>
                            </div>
                        </div>

                        <!-- Coverage Item 5 -->
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center mr-4 mt-1">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-navy-900 mb-2">Unauthorized Charges</h4>
                                <p class="text-gray-600">Protection against unauthorized transactions and fraudulent charges on your account.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-12 text-center text-white">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">100% Protected</h3>
                        <p class="text-lg mb-6">Every transaction is backed by our comprehensive buyer protection program</p>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4">
                            <p class="font-semibold">₹50,00,000+</p>
                            <p class="text-sm">Total Protection Coverage</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Easy Claims Process -->
    <section class="py-20 bg-navy-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-6">
                    Easy Claims Process
                </h2>
                <p class="text-silver-50 max-w-3xl mx-auto text-lg">
                    Filing a protection claim is simple and hassle-free
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Report the Issue</h3>
                    <p class="text-silver-50 leading-relaxed">
                        Contact our support team within 30 days with your order details and issue description.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Quick Review</h3>
                    <p class="text-silver-50 leading-relaxed">
                        Our team reviews your claim within 24 hours and may request additional documentation.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Fast Resolution</h3>
                    <p class="text-silver-50 leading-relaxed">
                        Approved claims are processed immediately with refund or replacement sent within 3-5 business days.
                    </p>
                </div>
            </div>

            <!-- Contact for Claims -->
            <div class="mt-16 text-center">
                <div class="bg-accent rounded-xl p-8 max-w-2xl mx-auto">
                    <h3 class="text-2xl font-bold mb-4">Need to File a Claim?</h3>
                    <p class="mb-6">Our protection team is ready to help you 24/7</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="tel:+917982999145" class="inline-flex items-center px-6 py-3 bg-white text-accent font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            Call Now
                        </a>
                        <a href="mailto:contact@tostishop.com" class="inline-flex items-center px-6 py-3 bg-white text-accent font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email Us
                        </a>
                    </div>
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
            "name": "100% Purchase Protection",
            "item": "<?php echo esc_url(get_permalink()); ?>"
        }
    ]
}
</script>

<?php get_footer(); ?>