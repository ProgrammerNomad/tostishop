<?php
/**
 * Template Name: Gift a Smile
 * 
 * TostiShop Gift Card Template
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                    Perfect Gift Solution
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">Gift a Smile</h1>
                <p class="text-lg md:text-xl text-silver-50 max-w-3xl mx-auto">
                    Give the gift of choice with TostiShop gift cards. Perfect for any occasion, redeemable on thousands of products.
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

    <!-- Gift Card Benefits -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-6">Why Choose TostiShop Gift Cards?</h2>
                <p class="text-gray-600 max-w-3xl mx-auto text-lg">
                    The perfect way to share the joy of shopping with your loved ones
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Instant Delivery -->
                <div class="bg-white rounded-xl p-8 text-center shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Instant Delivery</h3>
                    <p class="text-gray-600">Digital gift cards delivered instantly via email or SMS. Perfect for last-minute gifts.</p>
                </div>

                <!-- Never Expires -->
                <div class="bg-white rounded-xl p-8 text-center shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Never Expires</h3>
                    <p class="text-gray-600">No expiry date! Use the gift card whenever convenient, for any product on TostiShop.</p>
                </div>

                <!-- Any Amount -->
                <div class="bg-white rounded-xl p-8 text-center shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Any Amount</h3>
                    <p class="text-gray-600">Choose any amount from ₹100 to ₹50,000. Perfect for any budget and occasion.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Gift Card Purchase -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                
                <!-- Gift Card Form -->
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-8">Purchase Gift Card</h2>
                    
                    <form id="giftCardForm" class="space-y-6">
                        <!-- Gift Card Design -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-4">Choose Design</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="relative cursor-pointer">
                                    <input type="radio" name="design" value="birthday" id="birthday" class="sr-only">
                                    <label for="birthday" class="block bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg p-6 text-white text-center border-2 border-transparent hover:border-accent transition-colors">
                                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                        </svg>
                                        <span class="font-semibold">Birthday</span>
                                    </label>
                                </div>
                                <div class="relative cursor-pointer">
                                    <input type="radio" name="design" value="general" id="general" class="sr-only" checked>
                                    <label for="general" class="block bg-gradient-to-br from-navy-900 to-navy-700 rounded-lg p-6 text-white text-center border-2 border-accent transition-colors">
                                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                        </svg>
                                        <span class="font-semibold">General</span>
                                    </label>
                                </div>
                                <div class="relative cursor-pointer">
                                    <input type="radio" name="design" value="wedding" id="wedding" class="sr-only">
                                    <label for="wedding" class="block bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg p-6 text-white text-center border-2 border-transparent hover:border-accent transition-colors">
                                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span class="font-semibold">Wedding</span>
                                    </label>
                                </div>
                                <div class="relative cursor-pointer">
                                    <input type="radio" name="design" value="festival" id="festival" class="sr-only">
                                    <label for="festival" class="block bg-gradient-to-br from-green-600 to-teal-600 rounded-lg p-6 text-white text-center border-2 border-transparent hover:border-accent transition-colors">
                                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                        <span class="font-semibold">Festival</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Amount Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-4">Select Amount</label>
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <button type="button" class="amount-btn px-4 py-3 border border-gray-300 rounded-lg text-center font-semibold hover:border-accent hover:text-accent transition-colors" data-amount="500">₹500</button>
                                <button type="button" class="amount-btn px-4 py-3 border border-gray-300 rounded-lg text-center font-semibold hover:border-accent hover:text-accent transition-colors" data-amount="1000">₹1,000</button>
                                <button type="button" class="amount-btn px-4 py-3 border border-gray-300 rounded-lg text-center font-semibold hover:border-accent hover:text-accent transition-colors" data-amount="2000">₹2,000</button>
                                <button type="button" class="amount-btn px-4 py-3 border border-gray-300 rounded-lg text-center font-semibold hover:border-accent hover:text-accent transition-colors" data-amount="5000">₹5,000</button>
                                <button type="button" class="amount-btn px-4 py-3 border border-gray-300 rounded-lg text-center font-semibold hover:border-accent hover:text-accent transition-colors" data-amount="10000">₹10,000</button>
                                <button type="button" class="amount-btn px-4 py-3 border border-gray-300 rounded-lg text-center font-semibold hover:border-accent hover:text-accent transition-colors" data-amount="custom">Custom</button>
                            </div>
                            <div id="customAmountContainer" class="hidden">
                                <input type="number" id="customAmount" name="customAmount" placeholder="Enter amount (₹100 - ₹50,000)" min="100" max="50000" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent">
                            </div>
                            <input type="hidden" id="selectedAmount" name="amount" value="">
                        </div>

                        <!-- Recipient Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="recipientName" class="block text-sm font-medium text-gray-700 mb-2">Recipient Name *</label>
                                <input type="text" id="recipientName" name="recipientName" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent">
                            </div>
                            <div>
                                <label for="recipientEmail" class="block text-sm font-medium text-gray-700 mb-2">Recipient Email *</label>
                                <input type="email" id="recipientEmail" name="recipientEmail" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent">
                            </div>
                        </div>

                        <!-- Personal Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Personal Message (Optional)</label>
                            <textarea id="message" name="message" rows="4" maxlength="250"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent"
                                      placeholder="Write a personal message for the recipient..."></textarea>
                            <p class="text-sm text-gray-500 mt-1">Maximum 250 characters</p>
                        </div>

                        <!-- Sender Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="senderName" class="block text-sm font-medium text-gray-700 mb-2">Your Name *</label>
                                <input type="text" id="senderName" name="senderName" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent">
                            </div>
                            <div>
                                <label for="senderEmail" class="block text-sm font-medium text-gray-700 mb-2">Your Email *</label>
                                <input type="email" id="senderEmail" name="senderEmail" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent">
                            </div>
                        </div>

                        <!-- Delivery Options -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-4">Delivery Options</label>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input type="radio" name="delivery" value="instant" id="instant" class="mr-3" checked>
                                    <label for="instant" class="text-gray-700">
                                        <span class="font-semibold">Instant Delivery</span> - Send immediately after purchase
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="delivery" value="scheduled" id="scheduled" class="mr-3">
                                    <label for="scheduled" class="text-gray-700">
                                        <span class="font-semibold">Schedule Delivery</span> - Choose specific date and time
                                    </label>
                                </div>
                            </div>
                            <div id="scheduledContainer" class="hidden mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="deliveryDate" class="block text-sm font-medium text-gray-700 mb-2">Delivery Date</label>
                                    <input type="date" id="deliveryDate" name="deliveryDate"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent">
                                </div>
                                <div>
                                    <label for="deliveryTime" class="block text-sm font-medium text-gray-700 mb-2">Delivery Time</label>
                                    <select id="deliveryTime" name="deliveryTime"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-transparent">
                                        <option value="09:00">09:00 AM</option>
                                        <option value="12:00">12:00 PM</option>
                                        <option value="15:00">03:00 PM</option>
                                        <option value="18:00">06:00 PM</option>
                                        <option value="21:00">09:00 PM</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Purchase Button -->
                        <div class="pt-6">
                            <button type="submit" id="purchaseBtn" disabled
                                    class="w-full px-6 py-4 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed transition-colors">
                                Select Amount to Continue
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Gift Card Preview -->
                <div class="lg:sticky lg:top-8">
                    <h3 class="text-xl font-semibold text-navy-900 mb-6">Gift Card Preview</h3>
                    
                    <div id="giftCardPreview" class="bg-gradient-to-br from-navy-900 to-navy-700 rounded-xl p-8 text-white shadow-lg border-4 border-accent aspect-[3/2]">
                        <div class="h-full flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm opacity-80">TostiShop</span>
                                    <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                    </svg>
                                </div>
                                <h4 class="text-2xl font-bold mb-2">Gift Card</h4>
                                <p class="text-silver-50 text-sm">For: <span id="previewRecipient">Recipient Name</span></p>
                            </div>
                            <div>
                                <div class="text-3xl font-bold text-accent mb-2" id="previewAmount">₹0</div>
                                <p class="text-xs opacity-60">Valid on all TostiShop products • Never expires</p>
                            </div>
                        </div>
                    </div>

                    <!-- Gift Card Features -->
                    <div class="mt-8 space-y-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-navy-900">Instant Email Delivery</h4>
                                <p class="text-gray-600 text-sm">Delivered within minutes of purchase</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-navy-900">No Expiry Date</h4>
                                <p class="text-gray-600 text-sm">Use anytime, no rush required</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-navy-900">Easy to Redeem</h4>
                                <p class="text-gray-600 text-sm">Simple code entry at checkout</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 bg-silver-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-6">How Gift Cards Work</h2>
                <p class="text-gray-600 max-w-3xl mx-auto text-lg">
                    Simple, secure, and convenient way to gift
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Purchase -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl font-bold text-white">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Purchase</h3>
                    <p class="text-gray-600">Choose amount, add personal message, and complete secure payment. Gift card is generated instantly.</p>
                </div>

                <!-- Deliver -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl font-bold text-white">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Deliver</h3>
                    <p class="text-gray-600">Digital gift card is sent via email with your personal message. Option to schedule delivery.</p>
                </div>

                <!-- Redeem -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-accent rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl font-bold text-white">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-navy-900 mb-4">Redeem</h3>
                    <p class="text-gray-600">Recipient enters gift card code at checkout. Balance can be used across multiple purchases.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-6">Frequently Asked Questions</h2>
            </div>

            <div class="space-y-6">
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <h4 class="text-lg font-semibold text-navy-900 mb-3">How do I check my gift card balance?</h4>
                    <p class="text-gray-600">You can check your gift card balance by logging into your TostiShop account and navigating to "Gift Cards" section, or by entering your gift card code on our balance check page.</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <h4 class="text-lg font-semibold text-navy-900 mb-3">Can I use multiple gift cards for one purchase?</h4>
                    <p class="text-gray-600">Yes, you can use multiple gift cards in a single transaction. Simply enter each gift card code during checkout, and the amounts will be combined.</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <h4 class="text-lg font-semibold text-navy-900 mb-3">What if my purchase is less than the gift card amount?</h4>
                    <p class="text-gray-600">The remaining balance will stay on your gift card for future purchases. Gift card balances never expire, so you can use them anytime.</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <h4 class="text-lg font-semibold text-navy-900 mb-3">Can I return items purchased with a gift card?</h4>
                    <p class="text-gray-600">Yes, returns are processed normally. Refunds for gift card purchases are credited back to the original gift card or as a new gift card.</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <h4 class="text-lg font-semibold text-navy-900 mb-3">Is there a limit on gift card purchases?</h4>
                    <p class="text-gray-600">You can purchase gift cards from ₹100 to ₹50,000 per card. There's no limit on the number of gift cards you can purchase.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-navy-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-6">Ready to Gift a Smile?</h2>
            <p class="text-silver-50 mb-8 max-w-2xl mx-auto text-lg">
                Make someone's day special with a TostiShop gift card. Perfect for any occasion, delivered instantly.
            </p>
            <div class="space-y-4 md:space-y-0 md:flex md:space-x-4 justify-center">
                <a href="#giftCardForm" class="inline-block px-8 py-3 bg-accent text-white font-semibold rounded-lg hover:bg-red-600 transition-colors">
                    Purchase Gift Card
                </a>
                <a href="/contact-us" class="inline-block px-8 py-3 bg-white bg-opacity-10 text-white font-semibold rounded-lg hover:bg-opacity-20 transition-colors">
                    Need Help?
                </a>
            </div>
        </div>
    </section>
</div>

<!-- Gift Card Form JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountButtons = document.querySelectorAll('.amount-btn');
    const customAmountContainer = document.getElementById('customAmountContainer');
    const customAmountInput = document.getElementById('customAmount');
    const selectedAmountInput = document.getElementById('selectedAmount');
    const purchaseBtn = document.getElementById('purchaseBtn');
    const previewAmount = document.getElementById('previewAmount');
    const previewRecipient = document.getElementById('previewRecipient');
    const recipientNameInput = document.getElementById('recipientName');
    const deliveryOptions = document.querySelectorAll('input[name="delivery"]');
    const scheduledContainer = document.getElementById('scheduledContainer');
    const form = document.getElementById('giftCardForm');
    
    // Amount selection
    amountButtons.forEach(button => {
        button.addEventListener('click', function() {
            amountButtons.forEach(btn => btn.classList.remove('bg-accent', 'text-white', 'border-accent'));
            this.classList.add('bg-accent', 'text-white', 'border-accent');
            
            const amount = this.dataset.amount;
            
            if (amount === 'custom') {
                customAmountContainer.classList.remove('hidden');
                customAmountInput.focus();
                selectedAmountInput.value = '';
                previewAmount.textContent = '₹0';
                updatePurchaseButton();
            } else {
                customAmountContainer.classList.add('hidden');
                selectedAmountInput.value = amount;
                previewAmount.textContent = '₹' + parseInt(amount).toLocaleString('en-IN');
                updatePurchaseButton();
            }
        });
    });
    
    // Custom amount input
    customAmountInput.addEventListener('input', function() {
        const value = parseInt(this.value);
        if (value >= 100 && value <= 50000) {
            selectedAmountInput.value = value;
            previewAmount.textContent = '₹' + value.toLocaleString('en-IN');
        } else {
            selectedAmountInput.value = '';
            previewAmount.textContent = '₹0';
        }
        updatePurchaseButton();
    });
    
    // Recipient name update
    recipientNameInput.addEventListener('input', function() {
        previewRecipient.textContent = this.value || 'Recipient Name';
    });
    
    // Delivery option toggle
    deliveryOptions.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'scheduled') {
                scheduledContainer.classList.remove('hidden');
            } else {
                scheduledContainer.classList.add('hidden');
            }
        });
    });
    
    // Update purchase button state
    function updatePurchaseButton() {
        if (selectedAmountInput.value) {
            purchaseBtn.disabled = false;
            purchaseBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            purchaseBtn.classList.add('bg-accent', 'hover:bg-red-600');
            purchaseBtn.textContent = 'Purchase Gift Card - ' + previewAmount.textContent;
        } else {
            purchaseBtn.disabled = true;
            purchaseBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            purchaseBtn.classList.remove('bg-accent', 'hover:bg-red-600');
            purchaseBtn.textContent = 'Select Amount to Continue';
        }
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Here you would typically process the gift card purchase
        // For now, we'll show a success message
        
        const button = purchaseBtn;
        const originalText = button.innerHTML;
        
        // Show loading state
        button.innerHTML = '<span class="flex items-center justify-center"><svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Processing...</span>';
        button.disabled = true;
        
        // Simulate processing
        setTimeout(() => {
            alert('Gift card purchased successfully! The recipient will receive the gift card via email.');
            form.reset();
            previewAmount.textContent = '₹0';
            previewRecipient.textContent = 'Recipient Name';
            button.innerHTML = originalText;
            button.disabled = false;
            amountButtons.forEach(btn => btn.classList.remove('bg-accent', 'text-white', 'border-accent'));
            updatePurchaseButton();
        }, 3000);
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
            "name": "Gift a Smile",
            "item": "<?php echo esc_url(get_permalink()); ?>"
        }
    ]
}
</script>

<?php get_footer(); ?>