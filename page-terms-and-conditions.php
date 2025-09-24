<?php
/**
 * Template Name: Terms and Conditions
 * 
 * TostiShop Terms and Conditions Page Template
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Legal Information
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">Terms and Conditions</h1>
                <p class="text-lg md:text-xl text-silver-50 max-w-3xl mx-auto">
                    Please read these terms carefully before using TostiShop services
                </p>
                <div class="mt-8 text-sm text-silver-50">
                    Last updated: December 2024
                </div>
            </div>
        </div>
        <!-- Decorative wave -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#ffffff"/>
            </svg>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Table of Contents -->
            <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200 mb-12">
                <h2 class="text-2xl font-bold text-navy-900 mb-6">Table of Contents</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <a href="#acceptance" class="block text-accent hover:text-red-600 transition-colors">1. Acceptance of Terms</a>
                        <a href="#services" class="block text-accent hover:text-red-600 transition-colors">2. Description of Services</a>
                        <a href="#registration" class="block text-accent hover:text-red-600 transition-colors">3. User Registration</a>
                        <a href="#orders" class="block text-accent hover:text-red-600 transition-colors">4. Orders and Payments</a>
                        <a href="#shipping" class="block text-accent hover:text-red-600 transition-colors">5. Shipping and Delivery</a>
                        <a href="#returns" class="block text-accent hover:text-red-600 transition-colors">6. Returns and Refunds</a>
                    </div>
                    <div class="space-y-2">
                        <a href="#conduct" class="block text-accent hover:text-red-600 transition-colors">7. User Conduct</a>
                        <a href="#privacy" class="block text-accent hover:text-red-600 transition-colors">8. Privacy Policy</a>
                        <a href="#intellectual" class="block text-accent hover:text-red-600 transition-colors">9. Intellectual Property</a>
                        <a href="#liability" class="block text-accent hover:text-red-600 transition-colors">10. Limitation of Liability</a>
                        <a href="#termination" class="block text-accent hover:text-red-600 transition-colors">11. Termination</a>
                        <a href="#contact" class="block text-accent hover:text-red-600 transition-colors">12. Contact Information</a>
                    </div>
                </div>
            </div>

            <!-- Terms Content -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                
                <!-- Section 1 -->
                <div id="acceptance" class="border-b border-gray-200 p-8">
                    <h3 class="text-xl font-bold text-navy-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-accent text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                        Acceptance of Terms
                    </h3>
                    <div class="ml-11 space-y-4 text-gray-600">
                        <p>By accessing and using the TostiShop website (www.tostishop.com), you accept and agree to be bound by the terms and provision of this agreement.</p>
                        <p>If you do not agree to abide by the above, please do not use this service. These terms constitute a legally binding agreement between you and TostiShop.</p>
                        <p>We reserve the right to change these terms at any time without prior notice. Your continued use of the website following any changes indicates your acceptance of the new terms.</p>
                    </div>
                </div>

                <!-- Section 2 -->
                <div id="services" class="border-b border-gray-200 p-8">
                    <h3 class="text-xl font-bold text-navy-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-accent text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                        Description of Services
                    </h3>
                    <div class="ml-11 space-y-4 text-gray-600">
                        <p>TostiShop is an e-commerce platform that provides:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Online sale of beauty and personal care products</li>
                            <li>Product information, reviews, and recommendations</li>
                            <li>Order processing and customer support services</li>
                            <li>Delivery and logistics coordination</li>
                            <li>Return and refund processing</li>
                        </ul>
                        <p>All products sold are genuine and sourced from authorized distributors. We reserve the right to modify or discontinue services at our discretion.</p>
                    </div>
                </div>

                <!-- Section 3 -->
                <div id="registration" class="border-b border-gray-200 p-8">
                    <h3 class="text-xl font-bold text-navy-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-accent text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                        User Registration
                    </h3>
                    <div class="ml-11 space-y-4 text-gray-600">
                        <p>To access certain features and make purchases, you must register for an account. You agree to:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Provide accurate, current, and complete information</li>
                            <li>Maintain and update your information to keep it accurate</li>
                            <li>Protect your account credentials and notify us of unauthorized use</li>
                            <li>Take responsibility for all activities under your account</li>
                        </ul>
                        <p>We reserve the right to suspend or terminate accounts that violate these terms or provide false information.</p>
                    </div>
                </div>

                <!-- Section 4 -->
                <div id="orders" class="border-b border-gray-200 p-8">
                    <h3 class="text-xl font-bold text-navy-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-accent text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                        Orders and Payments
                    </h3>
                    <div class="ml-11 space-y-4 text-gray-600">
                        <p>Order Process:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>All orders are subject to availability and confirmation</li>
                            <li>We reserve the right to refuse or cancel orders at our discretion</li>
                            <li>Prices are subject to change without notice</li>
                            <li>Payment must be received before order processing</li>
                        </ul>
                        <p>Payment Methods: We accept UPI, credit cards, debit cards, net banking, and Cash on Delivery (COD) for eligible orders.</p>
                        <p>All transactions are processed through secure payment gateways with industry-standard encryption.</p>
                    </div>
                </div>

                <!-- Section 5 -->
                <div id="shipping" class="border-b border-gray-200 p-8">
                    <h3 class="text-xl font-bold text-navy-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-accent text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">5</span>
                        Shipping and Delivery
                    </h3>
                    <div class="ml-11 space-y-4 text-gray-600">
                        <p>Delivery Terms:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Delivery timelines are estimates and not guaranteed</li>
                            <li>We deliver across India through our logistics partners</li>
                            <li>Delivery charges may apply based on location and order value</li>
                            <li>Risk of loss transfers to you upon delivery</li>
                        </ul>
                        <p>We are not responsible for delays caused by factors beyond our control including weather, customs, or carrier delays.</p>
                    </div>
                </div>

                <!-- Section 6 -->
                <div id="returns" class="border-b border-gray-200 p-8">
                    <h3 class="text-xl font-bold text-navy-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-accent text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">6</span>
                        Returns and Refunds
                    </h3>
                    <div class="ml-11 space-y-4 text-gray-600">
                        <p>Return Policy:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Returns accepted within 30 days of delivery for eligible items</li>
                            <li>Items must be unused, unopened, and in original packaging</li>
                            <li>Some items like cosmetics may have different return policies</li>
                            <li>Return shipping costs may be deducted from refunds</li>
                        </ul>
                        <p>Refunds will be processed to the original payment method within 7-14 business days after we receive the returned item.</p>
                    </div>
                </div>

                <!-- Section 7 -->
                <div id="conduct" class="border-b border-gray-200 p-8">
                    <h3 class="text-xl font-bold text-navy-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-accent text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">7</span>
                        User Conduct
                    </h3>
                    <div class="ml-11 space-y-4 text-gray-600">
                        <p>You agree not to:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Use the website for any unlawful purpose</li>
                            <li>Attempt to gain unauthorized access to our systems</li>
                            <li>Interfere with the website's functionality or security</li>
                            <li>Post false, misleading, or inappropriate content</li>
                            <li>Violate any applicable laws or regulations</li>
                        </ul>
                        <p>Violation of these terms may result in account suspension or termination and legal action.</p>
                    </div>
                </div>

                <!-- Section 8 -->
                <div id="privacy" class="border-b border-gray-200 p-8">
                    <h3 class="text-xl font-bold text-navy-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-accent text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">8</span>
                        Privacy Policy
                    </h3>
                    <div class="ml-11 space-y-4 text-gray-600">
                        <p>Your privacy is important to us. Our Privacy Policy explains:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>How we collect, use, and protect your personal information</li>
                            <li>Cookie usage and tracking technologies</li>
                            <li>Data sharing practices with third parties</li>
                            <li>Your rights regarding your personal data</li>
                        </ul>
                        <p>By using our services, you consent to the collection and use of information as described in our Privacy Policy.</p>
                    </div>
                </div>

                <!-- Section 9 -->
                <div id="intellectual" class="border-b border-gray-200 p-8">
                    <h3 class="text-xl font-bold text-navy-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-accent text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">9</span>
                        Intellectual Property
                    </h3>
                    <div class="ml-11 space-y-4 text-gray-600">
                        <p>All content on TostiShop, including but not limited to:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Text, graphics, logos, images, and software</li>
                            <li>Product descriptions and reviews</li>
                            <li>Website design and functionality</li>
                            <li>Trademarks and brand names</li>
                        </ul>
                        <p>Are the property of TostiShop or our content suppliers and are protected by intellectual property laws. Unauthorized use is prohibited.</p>
                    </div>
                </div>

                <!-- Section 10 -->
                <div id="liability" class="border-b border-gray-200 p-8">
                    <h3 class="text-xl font-bold text-navy-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-accent text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">10</span>
                        Limitation of Liability
                    </h3>
                    <div class="ml-11 space-y-4 text-gray-600">
                        <p>TostiShop shall not be liable for:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Indirect, incidental, or consequential damages</li>
                            <li>Loss of profits, data, or business interruption</li>
                            <li>Damages exceeding the amount paid for the product</li>
                            <li>Issues arising from product use or misuse</li>
                        </ul>
                        <p>Our total liability is limited to the purchase price of the specific product in question.</p>
                    </div>
                </div>

                <!-- Section 11 -->
                <div id="termination" class="border-b border-gray-200 p-8">
                    <h3 class="text-xl font-bold text-navy-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-accent text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">11</span>
                        Termination
                    </h3>
                    <div class="ml-11 space-y-4 text-gray-600">
                        <p>This agreement is effective until terminated. We may terminate your access:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Immediately without notice for terms violation</li>
                            <li>For fraudulent or suspicious activity</li>
                            <li>For non-payment or chargebacks</li>
                            <li>At our discretion for business reasons</li>
                        </ul>
                        <p>Upon termination, your right to use the service ceases immediately, but these terms remain in effect.</p>
                    </div>
                </div>

                <!-- Section 12 -->
                <div id="contact" class="p-8">
                    <h3 class="text-xl font-bold text-navy-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-accent text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">12</span>
                        Contact Information
                    </h3>
                    <div class="ml-11 space-y-4 text-gray-600">
                        <p>For questions about these Terms and Conditions, please contact us:</p>
                        <div class="bg-gray-50 rounded-lg p-4 mt-4">
                            <p><strong>Email:</strong> contact@tostishop.com</p>
                            <p><strong>Phone:</strong> +91-79829 99145</p>
                            <p><strong>Address:</strong> L246, 2nd Floor, Sector-12, Pratap Vihar, Ghaziabad, UP 201009</p>
                            <p><strong>Business Hours:</strong> Monday to Saturday, 11 AM to 6 PM IST</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="mt-12 text-center">
                <div class="bg-navy-900 text-white rounded-xl p-8">
                    <div class="flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold">Important Notice</h3>
                    </div>
                    <p class="text-silver-50 leading-relaxed">
                        These terms are governed by Indian law. Any disputes will be subject to the jurisdiction of courts in Ghaziabad, Uttar Pradesh. By using TostiShop, you acknowledge that you have read, understood, and agree to these terms and conditions.
                    </p>
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
            "name": "Terms and Conditions",
            "item": "<?php echo esc_url(get_permalink()); ?>"
        }
    ]
}
</script>

<?php get_footer(); ?>