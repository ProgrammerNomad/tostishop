<?php
/**
 * Template Name: Privacy Policy
 * 
 * TostiShop Privacy Policy Template
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Your Privacy Matters
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">Privacy Policy</h1>
                <p class="text-lg md:text-xl text-silver-50 max-w-3xl mx-auto">
                    We are committed to protecting your personal information and being transparent about how we collect, use, and share your data.
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

    <!-- Quick Summary -->
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Privacy Highlights -->
            <div class="bg-silver-50 rounded-xl p-8 mb-12 border border-gray-200">
                <h2 class="text-2xl font-bold text-navy-900 mb-6">Privacy at a Glance</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-navy-900 mb-2">Secure Data</h3>
                        <p class="text-gray-600 text-sm">Bank-level encryption protects your information</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-navy-900 mb-2">Your Control</h3>
                        <p class="text-gray-600 text-sm">You decide what information to share</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-navy-900 mb-2">Transparent</h3>
                        <p class="text-gray-600 text-sm">Clear information about data usage</p>
                    </div>
                </div>
            </div>

            <!-- Last Updated -->
            <div class="bg-blue-50 rounded-lg p-4 mb-8 border border-blue-200">
                <p class="text-blue-800 text-sm">
                    <strong>Last Updated:</strong> December 2024 | 
                    <strong>Effective Date:</strong> December 1, 2024 |
                    <strong>Next Review:</strong> June 2025
                </p>
            </div>

            <!-- Table of Contents -->
            <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200 mb-12">
                <h2 class="text-xl font-bold text-navy-900 mb-6">Table of Contents</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <a href="#section-1" class="block text-accent hover:text-red-600 transition-colors">1. Information We Collect</a>
                        <a href="#section-2" class="block text-accent hover:text-red-600 transition-colors">2. How We Use Your Information</a>
                        <a href="#section-3" class="block text-accent hover:text-red-600 transition-colors">3. Information Sharing</a>
                        <a href="#section-4" class="block text-accent hover:text-red-600 transition-colors">4. Data Security</a>
                        <a href="#section-5" class="block text-accent hover:text-red-600 transition-colors">5. Cookies and Tracking</a>
                        <a href="#section-6" class="block text-accent hover:text-red-600 transition-colors">6. Third-Party Services</a>
                    </div>
                    <div class="space-y-2">
                        <a href="#section-7" class="block text-accent hover:text-red-600 transition-colors">7. Your Rights</a>
                        <a href="#section-8" class="block text-accent hover:text-red-600 transition-colors">8. Data Retention</a>
                        <a href="#section-9" class="block text-accent hover:text-red-600 transition-colors">9. Children's Privacy</a>
                        <a href="#section-10" class="block text-accent hover:text-red-600 transition-colors">10. International Users</a>
                        <a href="#section-11" class="block text-accent hover:text-red-600 transition-colors">11. Policy Changes</a>
                        <a href="#section-12" class="block text-accent hover:text-red-600 transition-colors">12. Contact Us</a>
                    </div>
                </div>
            </div>

            <!-- Privacy Policy Content -->
            
            <!-- Section 1: Information We Collect -->
            <section id="section-1" class="mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-6">1. Information We Collect</h2>
                
                <div class="space-y-8">
                    <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200">
                        <h3 class="text-xl font-semibold text-navy-900 mb-4">Personal Information You Provide</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-navy-900 mb-3">Account Information</h4>
                                <ul class="text-gray-600 text-sm space-y-1">
                                    <li>• Name and contact details</li>
                                    <li>• Email address and phone number</li>
                                    <li>• Account credentials and preferences</li>
                                    <li>• Profile information and settings</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold text-navy-900 mb-3">Transaction Information</h4>
                                <ul class="text-gray-600 text-sm space-y-1">
                                    <li>• Billing and shipping addresses</li>
                                    <li>• Payment method information</li>
                                    <li>• Order history and preferences</li>
                                    <li>• Customer service communications</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200">
                        <h3 class="text-xl font-semibold text-navy-900 mb-4">Information We Collect Automatically</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-navy-900 mb-3">Device & Usage Data</h4>
                                <ul class="text-gray-600 text-sm space-y-1">
                                    <li>• IP address and device identifiers</li>
                                    <li>• Browser type and operating system</li>
                                    <li>• Pages visited and time spent</li>
                                    <li>• Referral sources and exit pages</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold text-navy-900 mb-3">Location Data</h4>
                                <ul class="text-gray-600 text-sm space-y-1">
                                    <li>• General location (city/state)</li>
                                    <li>• Delivery address information</li>
                                    <li>• Time zone and regional settings</li>
                                    <li>• GPS data (with your permission)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 2: How We Use Your Information -->
            <section id="section-2" class="mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-6">2. How We Use Your Information</h2>
                
                <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold text-navy-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M8 11v6h8v-6M8 11H6a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2v-6a2 2 0 00-2-2h-2"></path>
                                </svg>
                                Essential Services
                            </h3>
                            <ul class="text-gray-600 text-sm space-y-2">
                                <li>• Process and fulfill your orders</li>
                                <li>• Manage your account and preferences</li>
                                <li>• Provide customer support services</li>
                                <li>• Send order confirmations and updates</li>
                                <li>• Process payments securely</li>
                                <li>• Prevent fraud and security issues</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-navy-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Improvements & Marketing
                            </h3>
                            <ul class="text-gray-600 text-sm space-y-2">
                                <li>• Improve our website and services</li>
                                <li>• Personalize your shopping experience</li>
                                <li>• Send promotional offers (with consent)</li>
                                <li>• Conduct market research and analytics</li>
                                <li>• Recommend products you might like</li>
                                <li>• Comply with legal requirements</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 3: Information Sharing -->
            <section id="section-3" class="mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-6">3. Information Sharing</h2>
                
                <div class="space-y-6">
                    <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200">
                        <h3 class="text-xl font-semibold text-navy-900 mb-4">We Share Information With:</h3>
                        
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-navy-900 mb-2">Service Partners</h4>
                                    <p class="text-gray-600 text-sm">Shipping companies, payment processors, and logistics partners to fulfill orders and process transactions.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-navy-900 mb-2">Legal Requirements</h4>
                                    <p class="text-gray-600 text-sm">Government authorities and law enforcement when required by law, court orders, or to protect rights and safety.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-navy-900 mb-2">Analytics Partners</h4>
                                    <p class="text-gray-600 text-sm">Analytics services to understand website usage and improve our services (data is anonymized).</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-50 rounded-xl p-6 border border-red-200">
                        <h4 class="font-semibold text-red-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            We Never Sell Your Personal Data
                        </h4>
                        <p class="text-red-700 text-sm">We do not sell, rent, or trade your personal information to third parties for their marketing purposes.</p>
                    </div>
                </div>
            </section>

            <!-- Section 4: Data Security -->
            <section id="section-4" class="mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-6">4. Data Security</h2>
                
                <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold text-navy-900 mb-4">Technical Safeguards</h3>
                            <ul class="text-gray-600 text-sm space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    SSL/TLS encryption for data transmission
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Secure servers with regular security updates
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Encrypted password storage
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Regular security audits and monitoring
                                </li>
                            </ul>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-navy-900 mb-4">Administrative Controls</h3>
                            <ul class="text-gray-600 text-sm space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Limited access to personal data
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Employee privacy training programs
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Incident response procedures
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Secure data storage and backup systems
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 5: Cookies and Tracking -->
            <section id="section-5" class="mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-6">5. Cookies and Tracking Technologies</h2>
                
                <div class="space-y-6">
                    <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200">
                        <h3 class="text-xl font-semibold text-navy-900 mb-6">Types of Cookies We Use</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-navy-900 mb-3 flex items-center">
                                    <span class="w-3 h-3 bg-green-600 rounded-full mr-2"></span>
                                    Essential Cookies
                                </h4>
                                <p class="text-gray-600 text-sm mb-3">Required for basic website functionality.</p>
                                <ul class="text-gray-500 text-xs space-y-1">
                                    <li>• Shopping cart functionality</li>
                                    <li>• User authentication</li>
                                    <li>• Security and fraud prevention</li>
                                </ul>
                            </div>

                            <div>
                                <h4 class="font-semibold text-navy-900 mb-3 flex items-center">
                                    <span class="w-3 h-3 bg-blue-600 rounded-full mr-2"></span>
                                    Analytics Cookies
                                </h4>
                                <p class="text-gray-600 text-sm mb-3">Help us understand website usage patterns.</p>
                                <ul class="text-gray-500 text-xs space-y-1">
                                    <li>• Page views and user behavior</li>
                                    <li>• Performance monitoring</li>
                                    <li>• Website optimization</li>
                                </ul>
                            </div>

                            <div>
                                <h4 class="font-semibold text-navy-900 mb-3 flex items-center">
                                    <span class="w-3 h-3 bg-purple-600 rounded-full mr-2"></span>
                                    Functional Cookies
                                </h4>
                                <p class="text-gray-600 text-sm mb-3">Remember your preferences and settings.</p>
                                <ul class="text-gray-500 text-xs space-y-1">
                                    <li>• Language and region settings</li>
                                    <li>• Display preferences</li>
                                    <li>• Recently viewed items</li>
                                </ul>
                            </div>

                            <div>
                                <h4 class="font-semibold text-navy-900 mb-3 flex items-center">
                                    <span class="w-3 h-3 bg-accent rounded-full mr-2"></span>
                                    Marketing Cookies
                                </h4>
                                <p class="text-gray-600 text-sm mb-3">Used to deliver relevant advertisements.</p>
                                <ul class="text-gray-500 text-xs space-y-1">
                                    <li>• Personalized product recommendations</li>
                                    <li>• Targeted advertising</li>
                                    <li>• Social media integration</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                        <h4 class="font-semibold text-blue-800 mb-3">Cookie Management</h4>
                        <p class="text-blue-700 text-sm mb-4">You can control cookies through your browser settings or our cookie preference center. Note that disabling certain cookies may affect website functionality.</p>
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">
                            Manage Cookie Preferences
                        </button>
                    </div>
                </div>
            </section>

            <!-- Section 7: Your Rights -->
            <section id="section-7" class="mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-6">7. Your Privacy Rights</h2>
                
                <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold text-navy-900 mb-4">Data Access & Control</h3>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="font-medium text-navy-900">Access Your Data</h4>
                                        <p class="text-gray-600 text-sm">Request a copy of personal information we hold about you</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="font-medium text-navy-900">Correct Information</h4>
                                        <p class="text-gray-600 text-sm">Update or correct inaccurate personal information</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <div>
                                        <h4 class="font-medium text-navy-900">Delete Your Data</h4>
                                        <p class="text-gray-600 text-sm">Request deletion of your personal information</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-navy-900 mb-4">Communication Preferences</h3>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 21l-5.586-5.586m0 0L10 13l2-2h2l2-2m0 0L18 7l-2 2-1-1-2 2m0 0L10 13l2-2h2l2-2"></path>
                                    </svg>
                                    <div>
                                        <h4 class="font-medium text-navy-900">Opt-out of Marketing</h4>
                                        <p class="text-gray-600 text-sm">Unsubscribe from promotional emails and messages</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="font-medium text-navy-900">Data Portability</h4>
                                        <p class="text-gray-600 text-sm">Export your data in a commonly used format</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="font-medium text-navy-900">File a Complaint</h4>
                                        <p class="text-gray-600 text-sm">Contact our privacy team or relevant authorities</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 12: Contact Us -->
            <section id="section-12" class="mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-6">12. Contact Us</h2>
                
                <div class="bg-gradient-to-r from-navy-900 to-navy-800 rounded-xl p-8 text-white">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-xl font-semibold mb-6">Privacy Questions or Concerns?</h3>
                            <p class="text-silver-50 mb-6">
                                Our privacy team is here to help you understand how we protect your data and assist with any privacy-related requests.
                            </p>
                            
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>privacy@tostishop.com</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-accent mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span>+91-79829 99145</span>
                                </div>
                                
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-accent mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <p>L246, 2nd Floor, Sector-12</p>
                                        <p>Pratap Vihar, Ghaziabad</p>
                                        <p>Uttar Pradesh 201009, India</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold mb-4">Quick Actions</h4>
                            <div class="space-y-3">
                                <a href="/contact-us" class="block w-full px-6 py-3 bg-accent text-white font-semibold rounded-lg hover:bg-red-600 transition-colors text-center">
                                    Contact Privacy Team
                                </a>
                                <button class="block w-full px-6 py-3 bg-white bg-opacity-10 text-white font-semibold rounded-lg hover:bg-opacity-20 transition-colors">
                                    Download Your Data
                                </button>
                                <button class="block w-full px-6 py-3 bg-white bg-opacity-10 text-white font-semibold rounded-lg hover:bg-opacity-20 transition-colors">
                                    Manage Privacy Settings
                                </button>
                            </div>
                            
                            <div class="mt-6 p-4 bg-white bg-opacity-10 rounded-lg">
                                <p class="text-sm text-silver-50">
                                    <strong>Response Time:</strong> We respond to privacy requests within 30 days as required by applicable law.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>

<!-- Smooth scrolling for anchor links -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for table of contents links
    const tocLinks = document.querySelectorAll('a[href^="#"]');
    tocLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
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
            "item": "<?php echo esc_url(home_url()); ?>"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "Privacy Policy",
            "item": "<?php echo esc_url(get_permalink()); ?>"
        }
    ]
}
</script>

<?php get_footer(); ?>