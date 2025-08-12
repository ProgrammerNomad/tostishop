<?php
/**
 * TostiShop Custom Address Book - Edit Address Endpoint Override
 * 
 * This template handles the edit-address endpoint and shows our
 * custom address book system within the proper my-account layout.
 * 
 * @package TostiShop
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

// Debug information
error_log('TostiShop Debug: Checking for TostiShop_Saved_Addresses class');
error_log('TostiShop Debug: Class exists: ' . (class_exists('TostiShop_Saved_Addresses') ? 'YES' : 'NO'));

// Check if our saved addresses class exists and is active
if (class_exists('TostiShop_Saved_Addresses')) {
    error_log('TostiShop Debug: Class found, initializing...');
    
    // Use our custom address book system
    global $tostishop_saved_addresses;
    if (!$tostishop_saved_addresses) {
        error_log('TostiShop Debug: Creating new instance');
        $tostishop_saved_addresses = new TostiShop_Saved_Addresses();
    }
    
    error_log('TostiShop Debug: Getting user addresses...');
    // Get user addresses for our system
    $addresses = $tostishop_saved_addresses->get_user_addresses();
    error_log('TostiShop Debug: Found ' . count($addresses) . ' addresses');
    
    // Include only the content part of our custom address book template
    // This will be wrapped by the my-account layout (sidebar, header, footer)
    ?>
    <div class="address-book-container" x-data="addressBook()">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">
                    <?php _e('My Address Book', 'tostishop'); ?>
                </h2>
                <p class="text-gray-600 text-sm">
                    <?php _e('Manage your saved addresses for faster checkout', 'tostishop'); ?>
                </p>
            </div>
            <button @click="showAddForm = true" 
                    class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <?php _e('Add New Address', 'tostishop'); ?>
            </button>
        </div>

        <!-- Saved Addresses Grid -->
        <?php if (!empty($addresses)) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                <?php foreach ($addresses as $address) : ?>
                    <div class="address-card bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all duration-200 <?php echo $address['is_default'] ? 'ring-2 ring-red-500 border-red-300' : ''; ?>">
                        
                        <!-- Address Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <?php if ($address['address_type'] === 'billing') : ?>
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                        </div>
                                    <?php else : ?>
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-lg"><?php echo esc_html($address['address_name']); ?></h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $address['address_type'] === 'billing' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'; ?>">
                                        <?php echo esc_html(ucfirst($address['address_type'])); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Actions Dropdown -->
                            <div class="relative" x-data="{ dropdownOpen: false }">
                                <button @click="dropdownOpen = !dropdownOpen" class="p-2 text-gray-400 hover:text-gray-600 transition-colors rounded-lg hover:bg-gray-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                    </svg>
                                </button>
                                
                                <div x-show="dropdownOpen" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     @click.away="dropdownOpen = false"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-10">
                                    
                                    <button @click="editAddress(<?php echo esc_attr(json_encode($address)); ?>)" 
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors flex items-center">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <?php _e('Edit', 'tostishop'); ?>
                                    </button>
                                    
                                    <?php if (!$address['is_default']) : ?>
                                        <button @click="setDefault(<?php echo esc_attr($address['id']); ?>, '<?php echo esc_attr($address['address_type']); ?>')" 
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors flex items-center">
                                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <?php _e('Set as Default', 'tostishop'); ?>
                                        </button>
                                    <?php endif; ?>
                                    
                                    <div class="border-t border-gray-100 my-1"></div>
                                    
                                    <button @click="deleteAddress(<?php echo esc_attr($address['id']); ?>)" 
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center">
                                        <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <?php _e('Delete', 'tostishop'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Default Badge -->
                        <?php if ($address['is_default']) : ?>
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <?php _e('Default Address', 'tostishop'); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Address Details -->
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="font-medium text-gray-900">
                                <?php echo esc_html($address['first_name'] . ' ' . $address['last_name']); ?>
                            </div>
                            <?php if (!empty($address['company'])) : ?>
                                <div><?php echo esc_html($address['company']); ?></div>
                            <?php endif; ?>
                            <div><?php echo esc_html($address['address_1']); ?></div>
                            <?php if (!empty($address['address_2'])) : ?>
                                <div><?php echo esc_html($address['address_2']); ?></div>
                            <?php endif; ?>
                            <div>
                                <?php echo esc_html($address['city'] . ', ' . $address['state'] . ' ' . $address['postcode']); ?>
                            </div>
                            <div><?php echo esc_html($address['country']); ?></div>
                            <?php if (!empty($address['phone'])) : ?>
                                <div class="flex items-center mt-3 pt-3 border-t border-gray-100">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <?php echo esc_html($address['phone']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <!-- Empty State -->
            <div class="text-center py-16 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                <div class="max-w-sm mx-auto">
                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">
                        <?php _e('No saved addresses', 'tostishop'); ?>
                    </h3>
                    <p class="text-gray-600 mb-8 leading-relaxed">
                        <?php _e('Save your frequently used addresses for faster checkout. Add your home, work, or any other address to speed up future orders.', 'tostishop'); ?>
                    </p>
                    <button @click="showAddForm = true" 
                            class="inline-flex items-center px-6 py-3 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm transition-all duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <?php _e('Add Your First Address', 'tostishop'); ?>
                    </button>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Add/Edit Address Modal -->
        <div x-show="showAddForm" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
             style="display: none;"
             @click.self="closeForm()">
            
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto transform transition-all duration-300"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.stop>
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900" x-text="editingAddress ? '<?php _e('Edit Address', 'tostishop'); ?>' : '<?php _e('Add New Address', 'tostishop'); ?>'"></h3>
                    <button @click="closeForm()" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 rounded-full p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6">
                    <!-- Address Form -->
                    <form @submit.prevent="saveAddress()" id="address-form" class="space-y-6">
                        
                        <!-- Address Type and Name -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('Address Type', 'tostishop'); ?> <span class="text-red-500">*</span>
                                </label>
                                <select x-model="form.address_type" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200">
                                    <option value="billing"><?php _e('Billing', 'tostishop'); ?></option>
                                    <option value="shipping"><?php _e('Shipping', 'tostishop'); ?></option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('Address Name', 'tostishop'); ?> <span class="text-red-500">*</span>
                                </label>
                                <input type="text" x-model="form.address_name" required 
                                       placeholder="<?php _e('e.g., Home, Work, Office', 'tostishop'); ?>"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200">
                            </div>
                        </div>
                        
                        <!-- Name Fields -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('First Name', 'tostishop'); ?> <span class="text-red-500">*</span>
                                </label>
                                <input type="text" x-model="form.first_name" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('Last Name', 'tostishop'); ?> <span class="text-red-500">*</span>
                                </label>
                                <input type="text" x-model="form.last_name" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200">
                            </div>
                        </div>
                        
                        <!-- Company -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?php _e('Company', 'tostishop'); ?>
                            </label>
                            <input type="text" x-model="form.company" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200">
                        </div>
                        
                        <!-- Address Lines -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?php _e('Address Line 1', 'tostishop'); ?> <span class="text-red-500">*</span>
                            </label>
                            <input type="text" x-model="form.address_1" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?php _e('Address Line 2', 'tostishop'); ?>
                            </label>
                            <input type="text" x-model="form.address_2" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200">
                        </div>
                        
                        <!-- City, State, Postal Code -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('City', 'tostishop'); ?> <span class="text-red-500">*</span>
                                </label>
                                <input type="text" x-model="form.city" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('State', 'tostishop'); ?> <span class="text-red-500">*</span>
                                </label>
                                <input type="text" x-model="form.state" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('Postal Code', 'tostishop'); ?> <span class="text-red-500">*</span>
                                </label>
                                <input type="text" x-model="form.postcode" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200">
                            </div>
                        </div>
                        
                        <!-- Country -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?php _e('Country', 'tostishop'); ?> <span class="text-red-500">*</span>
                            </label>
                            <select x-model="form.country" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200">
                                <option value=""><?php _e('Select Country', 'tostishop'); ?></option>
                                <?php
                                $countries = WC()->countries->get_countries();
                                foreach ($countries as $code => $name) {
                                    echo '<option value="' . esc_attr($code) . '">' . esc_html($name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <!-- Contact Info -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('Phone', 'tostishop'); ?>
                                </label>
                                <input type="tel" x-model="form.phone" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('Email', 'tostishop'); ?>
                                </label>
                                <input type="email" x-model="form.email" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200">
                            </div>
                        </div>
                        
                        <!-- Default Address -->
                        <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg">
                            <input type="checkbox" x-model="form.is_default" id="is_default"
                                   class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500 focus:ring-2">
                            <label for="is_default" class="text-sm font-medium text-gray-700 cursor-pointer">
                                <?php _e('Set as default address', 'tostishop'); ?>
                            </label>
                        </div>
                    </form>
                </div>
                
                <!-- Modal Footer -->
                <div class="flex flex-col sm:flex-row gap-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                    <button type="submit" form="address-form"
                            :disabled="loading"
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105">
                        <svg x-show="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-show="!loading" x-text="editingAddress ? '<?php _e('Update Address', 'tostishop'); ?>' : '<?php _e('Save Address', 'tostishop'); ?>'"></span>
                        <span x-show="loading" class="ml-2"><?php _e('Saving...', 'tostishop'); ?></span>
                    </button>
                    <button type="button" @click="closeForm()" 
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 font-medium border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        <?php _e('Cancel', 'tostishop'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function addressBook() {
        return {
            showAddForm: false,
            editingAddress: null,
            loading: false,
            form: {
                address_type: 'billing',
                address_name: '',
                first_name: '',
                last_name: '',
                company: '',
                address_1: '',
                address_2: '',
                city: '',
                state: '',
                postcode: '',
                country: '',
                phone: '',
                email: '',
                is_default: false
            },
            
            editAddress(address) {
                this.editingAddress = address;
                this.form = { ...address };
                this.form.is_default = Boolean(address.is_default);
                this.showAddForm = true;
            },
            
            closeForm() {
                this.showAddForm = false;
                this.editingAddress = null;
                this.resetForm();
            },
            
            resetForm() {
                this.form = {
                    address_type: 'billing',
                    address_name: '',
                    first_name: '',
                    last_name: '',
                    company: '',
                    address_1: '',
                    address_2: '',
                    city: '',
                    state: '',
                    postcode: '',
                    country: '',
                    phone: '',
                    email: '',
                    is_default: false
                };
            },
            
            async saveAddress() {
                this.loading = true;
                
                const formData = new FormData();
                formData.append('action', 'tostishop_save_address');
                formData.append('nonce', '<?php echo wp_create_nonce('tostishop_save_address'); ?>');
                
                if (this.editingAddress) {
                    formData.append('address_id', this.editingAddress.id);
                }
                
                Object.keys(this.form).forEach(key => {
                    formData.append(key, this.form[key]);
                });
                
                try {
                    const response = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        location.reload();
                    } else {
                        alert(result.data.message || '<?php _e('Error saving address', 'tostishop'); ?>');
                    }
                } catch (error) {
                    alert('<?php _e('Network error occurred', 'tostishop'); ?>');
                }
                
                this.loading = false;
            },
            
            async deleteAddress(addressId) {
                if (!confirm('<?php _e('Are you sure you want to delete this address?', 'tostishop'); ?>')) {
                    return;
                }
                
                const formData = new FormData();
                formData.append('action', 'tostishop_delete_address');
                formData.append('nonce', '<?php echo wp_create_nonce('tostishop_delete_address'); ?>');
                formData.append('address_id', addressId);
                
                try {
                    const response = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        location.reload();
                    } else {
                        alert(result.data.message || '<?php _e('Error deleting address', 'tostishop'); ?>');
                    }
                } catch (error) {
                    alert('<?php _e('Network error occurred', 'tostishop'); ?>');
                }
            },
            
            async setDefault(addressId, addressType) {
                const formData = new FormData();
                formData.append('action', 'tostishop_set_default_address');
                formData.append('nonce', '<?php echo wp_create_nonce('tostishop_set_default_address'); ?>');
                formData.append('address_id', addressId);
                formData.append('address_type', addressType);
                
                try {
                    const response = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        location.reload();
                    } else {
                        alert(result.data.message || '<?php _e('Error setting default address', 'tostishop'); ?>');
                    }
                } catch (error) {
                    alert('<?php _e('Network error occurred', 'tostishop'); ?>');
                }
            }
        }
    }
    </script>
    <?php
    
} else {
    // Fallback: show a message that the address book is not available
    ?>
    <div class="woocommerce-notices-wrapper">
        <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-blue-800"><?php _e('The address book feature is currently unavailable. Please contact support.', 'tostishop'); ?></p>
        </div>
    </div>
    
    <div class="woocommerce-MyAccount-content">
        <div class="text-center py-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4"><?php _e('Address Management Unavailable', 'tostishop'); ?></h3>
            <p class="text-gray-600 mb-6"><?php _e('We are currently experiencing technical difficulties with the address book feature.', 'tostishop'); ?></p>
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="inline-flex items-center px-6 py-3 bg-primary text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <?php _e('Go to Dashboard', 'tostishop'); ?>
            </a>
        </div>
    </div>
    <?php
}
