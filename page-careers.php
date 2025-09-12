<?php
/**
 * Template Name: Careers
 * 
 * TostiShop Careers Page Template
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 002 2h2a2 2 0 002-2V4h-2a2 2 0 00-2-2z"></path>
                    </svg>
                    Join Our Team
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">Build Your Career at TostiShop</h1>
                <p class="text-lg md:text-xl text-silver-50 max-w-3xl mx-auto mb-8">
                    Join India's fastest-growing beauty e-commerce platform and help millions discover their perfect look
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#open-positions" class="inline-flex items-center px-8 py-4 bg-accent text-white font-semibold rounded-lg hover:bg-red-600 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        View Open Positions
                    </a>
                    <a href="#culture" class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-white text-white font-semibold rounded-lg hover:bg-white hover:text-navy-900 transition-all">
                        Learn About Our Culture
                    </a>
                </div>
            </div>
        </div>
        <!-- Decorative wave -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    <!-- Company Stats -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl md:text-4xl font-bold text-navy-900 mb-2">50K+</div>
                    <div class="text-gray-600">Happy Customers</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-bold text-navy-900 mb-2">100+</div>
                    <div class="text-gray-600">Team Members</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-bold text-navy-900 mb-2">500+</div>
                    <div class="text-gray-600">Cities Covered</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-bold text-navy-900 mb-2">5+</div>
                    <div class="text-gray-600">Years Experience</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Work With Us -->
    <section id="culture" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-navy-900 mb-6">
                    Why Work at TostiShop?
                </h2>
                <p class="text-gray-600 max-w-3xl mx-auto text-lg">
                    We believe in creating an environment where talent thrives, innovation happens, and careers flourish
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Growth Opportunities -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200 text-center hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Growth Opportunities</h3>
                    <p class="text-gray-600">Fast-track your career with clear progression paths, skill development programs, and leadership opportunities in a rapidly expanding company.</p>
                </div>

                <!-- Flexible Work -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200 text-center hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Flexible Work Culture</h3>
                    <p class="text-gray-600">Enjoy work-life balance with flexible hours, remote work options, and a supportive environment that values your well-being.</p>
                </div>

                <!-- Innovation Focus -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200 text-center hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Innovation & Technology</h3>
                    <p class="text-gray-600">Work with cutting-edge technology, AI-powered solutions, and be part of revolutionizing the beauty e-commerce industry.</p>
                </div>

                <!-- Team Culture -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200 text-center hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Collaborative Team</h3>
                    <p class="text-gray-600">Join a diverse, inclusive team that values collaboration, creativity, and celebrates each other's success and achievements.</p>
                </div>

                <!-- Benefits Package -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200 text-center hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Competitive Benefits</h3>
                    <p class="text-gray-600">Attractive salary packages, health insurance, performance bonuses, and additional perks that recognize your contribution.</p>
                </div>

                <!-- Learning & Development -->
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200 text-center hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Learning & Development</h3>
                    <p class="text-gray-600">Continuous learning opportunities, training programs, conferences, and mentorship to keep you at the forefront of your field.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Open Positions -->
    <section id="open-positions" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-navy-900 mb-6">
                    Current Openings
                </h2>
                <p class="text-gray-600 max-w-3xl mx-auto text-lg">
                    Find your next opportunity and join our mission to make beauty accessible to everyone across India
                </p>
            </div>

            <div class="space-y-6">
                <!-- Job 1 -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow group">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-3">
                                <h3 class="text-xl font-semibold text-navy-900 group-hover:text-accent transition-colors">
                                    Senior Frontend Developer
                                </h3>
                                <span class="ml-4 bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                    Full-time
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Ghaziabad / Remote
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 002 2h2a2 2 0 002-2V4h-2a2 2 0 00-2-2z"></path>
                                    </svg>
                                    3-5 years experience
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    ₹8-15 LPA
                                </span>
                            </div>
                            <p class="text-gray-600 mb-4">
                                Build responsive, high-performance web applications using React, Next.js, and modern frontend technologies. Join our tech team to create amazing user experiences.
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">React</span>
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">Next.js</span>
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">TypeScript</span>
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">Tailwind CSS</span>
                            </div>
                        </div>
                        <div class="mt-4 lg:mt-0 lg:ml-6">
                            <button class="w-full lg:w-auto px-6 py-3 bg-accent text-white font-semibold rounded-lg hover:bg-red-600 transition-colors">
                                Apply Now
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Job 2 -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow group">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-3">
                                <h3 class="text-xl font-semibold text-navy-900 group-hover:text-accent transition-colors">
                                    Digital Marketing Manager
                                </h3>
                                <span class="ml-4 bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                    Full-time
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Ghaziabad
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 002 2h2a2 2 0 002-2V4h-2a2 2 0 00-2-2z"></path>
                                    </svg>
                                    2-4 years experience
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    ₹6-10 LPA
                                </span>
                            </div>
                            <p class="text-gray-600 mb-4">
                                Drive customer acquisition and engagement through innovative digital marketing strategies. Lead campaigns across social media, email, and performance marketing channels.
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">Google Ads</span>
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">Facebook Ads</span>
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">SEO/SEM</span>
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">Analytics</span>
                            </div>
                        </div>
                        <div class="mt-4 lg:mt-0 lg:ml-6">
                            <button class="w-full lg:w-auto px-6 py-3 bg-accent text-white font-semibold rounded-lg hover:bg-red-600 transition-colors">
                                Apply Now
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Job 3 -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow group">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-3">
                                <h3 class="text-xl font-semibold text-navy-900 group-hover:text-accent transition-colors">
                                    Customer Success Executive
                                </h3>
                                <span class="ml-4 bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                                    Full-time
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Ghaziabad
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 002 2h2a2 2 0 002-2V4h-2a2 2 0 00-2-2z"></path>
                                    </svg>
                                    1-3 years experience
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    ₹3-6 LPA
                                </span>
                            </div>
                            <p class="text-gray-600 mb-4">
                                Ensure exceptional customer experience by resolving queries, managing relationships, and driving customer satisfaction across all touchpoints.
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">Customer Support</span>
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">CRM Tools</span>
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">Communication</span>
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">Problem Solving</span>
                            </div>
                        </div>
                        <div class="mt-4 lg:mt-0 lg:ml-6">
                            <button class="w-full lg:w-auto px-6 py-3 bg-accent text-white font-semibold rounded-lg hover:bg-red-600 transition-colors">
                                Apply Now
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Job 4 -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow group">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-3">
                                <h3 class="text-xl font-semibold text-navy-900 group-hover:text-accent transition-colors">
                                    Warehouse Operations Manager
                                </h3>
                                <span class="ml-4 bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                                    Full-time
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Ghaziabad
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 002 2h2a2 2 0 002-2V4h-2a2 2 0 00-2-2z"></path>
                                    </svg>
                                    2-5 years experience
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    ₹5-8 LPA
                                </span>
                            </div>
                            <p class="text-gray-600 mb-4">
                                Oversee warehouse operations, inventory management, and logistics coordination to ensure efficient order fulfillment and delivery processes.
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">Inventory Management</span>
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">Logistics</span>
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">Team Management</span>
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">Operations</span>
                            </div>
                        </div>
                        <div class="mt-4 lg:mt-0 lg:ml-6">
                            <button class="w-full lg:w-auto px-6 py-3 bg-accent text-white font-semibold rounded-lg hover:bg-red-600 transition-colors">
                                Apply Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View All Jobs -->
            <div class="text-center mt-12">
                <p class="text-gray-600 mb-6">Don't see a perfect match? We're always looking for talented individuals!</p>
                <a href="mailto:careers@tostishop.com" class="inline-flex items-center px-8 py-4 bg-navy-900 text-white font-semibold rounded-lg hover:bg-navy-800 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Send Your Resume
                </a>
            </div>
        </div>
    </section>

    <!-- Application Process -->
    <section class="py-20 bg-navy-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-6">
                    Our Hiring Process
                </h2>
                <p class="text-silver-50 max-w-3xl mx-auto text-lg">
                    We've designed a simple, transparent process to help you showcase your talents
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6 text-xl font-bold">
                        1
                    </div>
                    <h3 class="text-lg font-semibold mb-3">Apply Online</h3>
                    <p class="text-silver-50">Submit your application through our careers page or email your resume directly to our HR team.</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6 text-xl font-bold">
                        2
                    </div>
                    <h3 class="text-lg font-semibold mb-3">Initial Screening</h3>
                    <p class="text-silver-50">Our HR team reviews your application and conducts a preliminary phone or video screening.</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6 text-xl font-bold">
                        3
                    </div>
                    <h3 class="text-lg font-semibold mb-3">Technical/Role Interview</h3>
                    <p class="text-silver-50">Meet with the hiring manager and team members for role-specific discussions and assessments.</p>
                </div>

                <!-- Step 4 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-6 text-xl font-bold">
                        4
                    </div>
                    <h3 class="text-lg font-semibold mb-3">Final Decision</h3>
                    <p class="text-silver-50">We make our decision and extend an offer to successful candidates with detailed onboarding information.</p>
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
            "name": "Careers",
            "item": "<?php echo esc_url(get_permalink()); ?>"
        }
    ]
}
</script>

<?php get_footer(); ?>