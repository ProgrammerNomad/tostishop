<?php
/**
 * Template Name: Shipping Policy
 * 
 * TostiShop Shipping Policy Template
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Fast & Reliable Delivery
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">Shipping Policy</h1>
                <p class="text-lg md:text-xl text-silver-50 max-w-3xl mx-auto">
                    We deliver happiness to your doorstep. Learn about our shipping options, delivery times, and coverage areas.
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

    <!-- Shipping Overview -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-16">
                <div class="bg-white rounded-xl p-6 text-center shadow-md border border-gray-200">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-navy-900 mb-1">Same Day</h3>
                    <p class="text-gray-600 text-sm">Delhi NCR orders before 2 PM</p>
                </div>

                <div class="bg-white rounded-xl p-6 text-center shadow-md border border-gray-200">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-navy-900 mb-1">2-3 Days</h3>
                    <p class="text-gray-600 text-sm">Major cities across India</p>
                </div>

                <div class="bg-white rounded-xl p-6 text-center shadow-md border border-gray-200">
                    <div class="w-16 h-16 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-navy-900 mb-1">Pan India</h3>
                    <p class="text-gray-600 text-sm">We deliver everywhere</p>
                </div>

                <div class="bg-white rounded-xl p-6 text-center shadow-md border border-gray-200">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-navy-900 mb-1">Insured</h3>
                    <p class="text-gray-600 text-sm">100% secure delivery</p>
                </div>
            </div>

            <div class="max-w-4xl mx-auto">
                <!-- Free Shipping Banner -->
                <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-8 mb-12 border border-green-200">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-navy-900 mb-4">Free Shipping on Orders Above ₹700</h2>
                        <p class="text-gray-700 mb-6">Enjoy complimentary shipping across India for orders totaling ₹700 or more. No minimum quantity required!</p>
                        <div class="inline-flex items-center bg-white px-6 py-3 rounded-full text-green-600 font-semibold border border-green-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Valid on all products
                        </div>
                    </div>
                </div>

                <!-- Delivery Options -->
                <div class="mb-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-8">Delivery Options</h2>
                    
                    <div class="space-y-6">
                        <!-- Standard Delivery -->
                        <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200">
                            <div class="flex items-start">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mr-6">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-xl font-semibold text-navy-900">Standard Delivery</h3>
                                        <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">Most Popular</span>
                                    </div>
                                    <p class="text-gray-600 mb-4">Regular delivery service with reliable tracking and customer support.</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <h4 class="font-semibold text-navy-900 mb-2">Delivery Time</h4>
                                            <ul class="text-gray-600 text-sm space-y-1">
                                                <li>• Major cities: 2-3 business days</li>
                                                <li>• Other locations: 3-5 business days</li>
                                                <li>• Remote areas: 5-7 business days</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-navy-900 mb-2">Shipping Cost</h4>
                                            <ul class="text-gray-600 text-sm space-y-1">
                                                <li>• Orders above ₹700: FREE</li>
                                                <li>• Orders below ₹700: ₹49</li>
                                                <li>• Remote areas: ₹99</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Express Delivery -->
                        <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200">
                            <div class="flex items-start">
                                <div class="w-16 h-16 bg-accent/10 rounded-full flex items-center justify-center mr-6">
                                    <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-xl font-semibold text-navy-900">Express Delivery</h3>
                                        <span class="px-4 py-2 bg-accent text-white rounded-full text-sm font-semibold">Fastest</span>
                                    </div>
                                    <p class="text-gray-600 mb-4">Priority delivery service for urgent orders with same-day and next-day options.</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <h4 class="font-semibold text-navy-900 mb-2">Delivery Time</h4>
                                            <ul class="text-gray-600 text-sm space-y-1">
                                                <li>• Delhi NCR: Same day (order by 2 PM)</li>
                                                <li>• Mumbai, Bangalore: Next day</li>
                                                <li>• Other metros: 1-2 business days</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-navy-900 mb-2">Shipping Cost</h4>
                                            <ul class="text-gray-600 text-sm space-y-1">
                                                <li>• Same day: ₹199</li>
                                                <li>• Next day: ₹149</li>
                                                <li>• Express (1-2 days): ₹99</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Scheduled Delivery -->
                        <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200">
                            <div class="flex items-start">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mr-6">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-xl font-semibold text-navy-900">Scheduled Delivery</h3>
                                        <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold">Convenient</span>
                                    </div>
                                    <p class="text-gray-600 mb-4">Choose your preferred delivery date and time slot for maximum convenience.</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <h4 class="font-semibold text-navy-900 mb-2">Available Slots</h4>
                                            <ul class="text-gray-600 text-sm space-y-1">
                                                <li>• Morning: 9 AM - 1 PM</li>
                                                <li>• Afternoon: 1 PM - 6 PM</li>
                                                <li>• Evening: 6 PM - 9 PM</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-navy-900 mb-2">Additional Cost</h4>
                                            <ul class="text-gray-600 text-sm space-y-1">
                                                <li>• Standard + ₹49</li>
                                                <li>• Available in select cities</li>
                                                <li>• 48-hour advance booking</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Zones -->
                <div class="mb-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-8">Delivery Zones</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Zone 1 -->
                        <div class="bg-green-50 rounded-xl p-6 border border-green-200">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-white font-bold">1</span>
                                </div>
                                <h3 class="text-lg font-semibold text-navy-900">Metro Cities</h3>
                            </div>
                            <p class="text-gray-700 mb-4">Major metropolitan areas with fastest delivery</p>
                            <ul class="text-sm text-gray-600 space-y-1 mb-4">
                                <li>• Delhi NCR</li>
                                <li>• Mumbai</li>
                                <li>• Bangalore</li>
                                <li>• Chennai</li>
                                <li>• Kolkata</li>
                                <li>• Hyderabad</li>
                                <li>• Pune</li>
                            </ul>
                            <div class="flex items-center text-green-600 font-semibold">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                1-3 days
                            </div>
                        </div>

                        <!-- Zone 2 -->
                        <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-white font-bold">2</span>
                                </div>
                                <h3 class="text-lg font-semibold text-navy-900">Tier 2 Cities</h3>
                            </div>
                            <p class="text-gray-700 mb-4">Major cities and state capitals</p>
                            <ul class="text-sm text-gray-600 space-y-1 mb-4">
                                <li>• Ahmedabad</li>
                                <li>• Surat</li>
                                <li>• Jaipur</li>
                                <li>• Lucknow</li>
                                <li>• Kanpur</li>
                                <li>• Nagpur</li>
                                <li>• Indore</li>
                            </ul>
                            <div class="flex items-center text-blue-600 font-semibold">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                3-5 days
                            </div>
                        </div>

                        <!-- Zone 3 -->
                        <div class="bg-yellow-50 rounded-xl p-6 border border-yellow-200">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-yellow-600 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-white font-bold">3</span>
                                </div>
                                <h3 class="text-lg font-semibold text-navy-900">Other Areas</h3>
                            </div>
                            <p class="text-gray-700 mb-4">Towns, rural areas, and remote locations</p>
                            <ul class="text-sm text-gray-600 space-y-1 mb-4">
                                <li>• Small towns</li>
                                <li>• Rural areas</li>
                                <li>• Hill stations</li>
                                <li>• Remote locations</li>
                                <li>• Island territories</li>
                                <li>• Border areas</li>
                            </ul>
                            <div class="flex items-center text-yellow-600 font-semibold">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                5-7 days
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Processing -->
                <div class="mb-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-8">Order Processing</h2>
                    
                    <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-navy-900 mb-2">Order Confirmed</h4>
                                <p class="text-gray-600 text-sm">Payment verified and order placed in our system</p>
                            </div>

                            <div class="text-center">
                                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-navy-900 mb-2">Processing</h4>
                                <p class="text-gray-600 text-sm">Items picked, packed, and prepared for shipment</p>
                            </div>

                            <div class="text-center">
                                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-navy-900 mb-2">Shipped</h4>
                                <p class="text-gray-600 text-sm">Package handed over to delivery partner with tracking</p>
                            </div>

                            <div class="text-center">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-navy-900 mb-2">Delivered</h4>
                                <p class="text-gray-600 text-sm">Package successfully delivered to your address</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 bg-silver-50 rounded-xl p-6 border border-gray-200">
                        <h4 class="font-semibold text-navy-900 mb-4">Processing Times</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h5 class="font-medium text-navy-900 mb-2">Regular Items</h5>
                                <ul class="text-gray-600 text-sm space-y-1">
                                    <li>• In-stock items: 24-48 hours</li>
                                    <li>• Made-to-order: 3-5 business days</li>
                                </ul>
                            </div>
                            <div>
                                <h5 class="font-medium text-navy-900 mb-2">Special Items</h5>
                                <ul class="text-gray-600 text-sm space-y-1">
                                    <li>• Bulk orders: 5-7 business days</li>
                                    <li>• Custom products: 7-10 business days</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tracking Information -->
                <div class="mb-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-8">Order Tracking</h2>
                    
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-8 border border-blue-200">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                            <div>
                                <h3 class="text-xl font-semibold text-navy-900 mb-4">Track Your Order</h3>
                                <p class="text-gray-700 mb-6">Stay updated with real-time tracking information from dispatch to delivery.</p>
                                
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">SMS and email notifications</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">Real-time location updates</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">Delivery partner contact details</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-lg p-6 border border-gray-200">
                                <h4 class="font-semibold text-navy-900 mb-4">Track Your Package</h4>
                                <div class="space-y-4">
                                    <input type="text" placeholder="Enter Order ID or Tracking Number" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent">
                                    <button class="w-full px-6 py-3 bg-accent text-white font-semibold rounded-lg hover:bg-red-600 transition-colors">
                                        Track Order
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-3">You can also track your order from your account dashboard.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="bg-yellow-50 rounded-xl p-8 border border-yellow-200">
                    <h3 class="text-xl font-semibold text-navy-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Important Shipping Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-navy-900 mb-3">Before You Order</h4>
                            <ul class="text-gray-700 text-sm space-y-2">
                                <li>• Verify your delivery address carefully</li>
                                <li>• Provide accurate contact information</li>
                                <li>• Check product availability in your area</li>
                                <li>• Consider delivery time for urgent orders</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold text-navy-900 mb-3">During Delivery</h4>
                            <ul class="text-gray-700 text-sm space-y-2">
                                <li>• Be available at the delivery address</li>
                                <li>• Keep your phone accessible for calls</li>
                                <li>• Check package condition before accepting</li>
                                <li>• Report any damage immediately</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16 bg-navy-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-6">Need Shipping Support?</h2>
            <p class="text-silver-50 mb-8 max-w-2xl mx-auto text-lg">
                Have questions about shipping or tracking? Our customer service team is here to help.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <div class="w-12 h-12 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-2">Call Us</h3>
                    <p class="text-silver-50">+91-79829 99145</p>
                </div>
                
                <div>
                    <div class="w-12 h-12 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-2">Email Us</h3>
                    <p class="text-silver-50">contact@tostishop.com</p>
                </div>
                
                <div>
                    <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-2">WhatsApp</h3>
                    <p class="text-silver-50">+91-79829 99145</p>
                </div>
            </div>
            
            <div class="space-y-4 md:space-y-0 md:flex md:space-x-4 justify-center">
                <a href="/contact-us" class="inline-block px-8 py-3 bg-accent text-white font-semibold rounded-lg hover:bg-red-600 transition-colors">
                    Contact Support
                </a>
                <a href="/" class="inline-block px-8 py-3 bg-white bg-opacity-10 text-white font-semibold rounded-lg hover:bg-opacity-20 transition-colors">
                    Continue Shopping
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
            "name": "Shipping Policy",
            "item": "<?php echo esc_url(get_permalink()); ?>"
        }
    ]
}
</script>

<?php get_footer(); ?>