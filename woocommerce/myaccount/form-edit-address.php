<?php
/**
 * Edit address form - TostiShop Address Book Complete Implementation
 * 
 * This template provides complete address book functionality with:
 * - Add, edit, delete addresses
 * - Address tagging (Home, Office, etc.)
 * - Integration with checkout system
 * - Mobile-optimized interface
 * 
 * @package TostiShop
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

// Check if our saved addresses class exists
if (class_exists('TostiShop_Saved_Addresses')) {
    // Initialize saved addresses
    global $tostishop_saved_addresses;
    if (!$tostishop_saved_addresses) {
        $tostishop_saved_addresses = new TostiShop_Saved_Addresses();
    }
    
    // Get user addresses
    $addresses = $tostishop_saved_addresses->get_user_addresses();
    ?>
    
    
    <!-- Address Book Content -->
    <div class="address-book-wrapper" x-data="addressBookManager()">
        
        <style>
        /* Fix potential grid display issues */
        .address-book-grid {
            display: grid !important;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .address-book-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        
        @media (min-width: 1024px) {
            .address-book-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        
        .address-card {
            display: flex !important;
            flex-direction: column;
            justify-content: space-between;
            min-height: 280px;
        }
        
        .address-details {
            flex: 1;
        }
        
        .address-actions {
            margin-top: auto;
        }
        </style>
        
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2"><?php _e('Address Book', 'tostishop'); ?></h2>
            <p class="text-gray-600"><?php _e('Manage your saved addresses for faster checkout', 'tostishop'); ?></p>
        </div>            <!-- Add New Address Button -->
            <div class="mb-6">
                <button @click="showAddForm = true" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <?php _e('Add New Address', 'tostishop'); ?>
                </button>
            </div>
            
            <!-- Success/Error Messages -->
            <div x-show="message" x-transition class="mb-6">
                <div :class="messageType === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'" 
                     class="border rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg x-show="messageType === 'success'" class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                            <svg x-show="messageType === 'error'" class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium" x-text="message"></p>
                        </div>
                        <div class="ml-auto pl-3">
                            <button @click="message = ''" class="inline-flex rounded-md p-1.5 hover:bg-gray-100">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Addresses Display -->
            <?php if (!empty($addresses)) : ?>
                <div class="address-book-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <?php foreach ($addresses as $address) : 
                        // Convert object to array if needed
                        $addr = is_object($address) ? (array) $address : $address;
                    ?>
                        <div class="address-card bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all duration-200 flex flex-col <?php echo $addr['is_default'] ? 'ring-2 ring-red-500 border-red-300' : ''; ?>">
                            
                            <!-- Address Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <?php if ($addr['address_type'] === 'billing') : ?>
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
                                        <h3 class="font-semibold text-gray-900 text-lg"><?php echo esc_html($addr['address_name']); ?></h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $addr['address_type'] === 'billing' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'; ?>">
                                            <?php echo esc_html(ucfirst($addr['address_type'])); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Default Badge -->
                            <?php if ($addr['is_default']) : ?>
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
                            <div class="address-details space-y-2 text-sm text-gray-600 flex-grow">
                                <div class="font-medium text-gray-900">
                                    <?php echo esc_html($addr['first_name'] . ' ' . $addr['last_name']); ?>
                                </div>
                                <?php if (!empty($addr['company'])) : ?>
                                    <div><?php echo esc_html($addr['company']); ?></div>
                                <?php endif; ?>
                                <div><?php echo esc_html($addr['address_1']); ?></div>
                                <?php if (!empty($addr['address_2'])) : ?>
                                    <div><?php echo esc_html($addr['address_2']); ?></div>
                                <?php endif; ?>
                                <div>
                                    <?php echo esc_html($addr['city']); ?>, 
                                    <?php echo esc_html($addr['state']); ?> 
                                    <?php echo esc_html($addr['postcode']); ?>
                                </div>
                                <div><?php echo esc_html($addr['country']); ?></div>
                                <?php if (!empty($addr['phone'])) : ?>
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        <?php echo esc_html($addr['phone']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="address-actions mt-4 flex space-x-2">
                                <button @click="editAddress(<?php echo esc_attr(json_encode($addr)); ?>)" 
                                        class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                                    <?php _e('Edit', 'tostishop'); ?>
                                </button>
                                
                                <?php if (!$addr['is_default']) : ?>
                                    <button @click="setDefaultAddress(<?php echo esc_attr($addr['id']); ?>, '<?php echo esc_attr($addr['address_type']); ?>')" 
                                            class="px-3 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200 transition-colors">
                                        <?php _e('Set Default', 'tostishop'); ?>
                                    </button>
                                <?php endif; ?>
                                
                                <button @click="deleteAddress(<?php echo esc_attr($addr['id']); ?>)" 
                                        class="px-3 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 transition-colors">
                                    <?php _e('Delete', 'tostishop'); ?>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">
                        <?php _e('No saved addresses yet', 'tostishop'); ?>
                    </h3>
                    <p class="text-gray-600 mb-8 leading-relaxed">
                        <?php _e('Add your first address to make checkout faster and easier for future orders.', 'tostishop'); ?>
                    </p>
                    <button @click="showAddForm = true" 
                            class="inline-flex items-center px-6 py-3 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <?php _e('Add Your First Address', 'tostishop'); ?>
                    </button>
                </div>
            <?php endif; ?>
            
            <!-- Add/Edit Address Modal -->
            <div x-show="showAddForm || showEditForm" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                
                <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
                    
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900" x-text="editingAddress ? '<?php _e('Edit Address', 'tostishop'); ?>' : '<?php _e('Add New Address', 'tostishop'); ?>'"></h3>
                        <button @click="closeForm()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Address Form -->
                    <form @submit.prevent="saveAddress()" class="space-y-6">
                        
                        <!-- Address Type and Name Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('Address Type', 'tostishop'); ?>
                                </label>
                                <select x-model="formData.address_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    <option value="billing"><?php _e('Billing', 'tostishop'); ?></option>
                                    <option value="shipping"><?php _e('Shipping', 'tostishop'); ?></option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('Address Name/Tag', 'tostishop'); ?>
                                </label>
                                <select x-model="formData.address_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    <option value="Home"><?php _e('Home', 'tostishop'); ?></option>
                                    <option value="Office"><?php _e('Office', 'tostishop'); ?></option>
                                    <option value="Work"><?php _e('Work', 'tostishop'); ?></option>
                                    <option value="Parents"><?php _e('Parents', 'tostishop'); ?></option>
                                    <option value="Friend"><?php _e('Friend', 'tostishop'); ?></option>
                                    <option value="Other"><?php _e('Other', 'tostishop'); ?></option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Name Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('First Name', 'tostishop'); ?> *
                                </label>
                                <input type="text" x-model="formData.first_name" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('Last Name', 'tostishop'); ?> *
                                </label>
                                <input type="text" x-model="formData.last_name" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <!-- Company -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?php _e('Company', 'tostishop'); ?>
                            </label>
                            <input type="text" x-model="formData.company" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>
                        
                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?php _e('Street Address', 'tostishop'); ?> *
                            </label>
                            <input type="text" x-model="formData.address_1" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>
                        
                        <!-- Address 2 -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?php _e('Apartment, suite, etc.', 'tostishop'); ?>
                            </label>
                            <input type="text" x-model="formData.address_2" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>
                        
                        <!-- City, State, Postal Code Row -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('City', 'tostishop'); ?> *
                                </label>
                                <input type="text" x-model="formData.city" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('State', 'tostishop'); ?> *
                                </label>
                                <input type="text" x-model="formData.state" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('Postal Code', 'tostishop'); ?> *
                                </label>
                                <input type="text" x-model="formData.postcode" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <!-- Country and Phone Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('Country', 'tostishop'); ?> *
                                </label>
                                <select x-model="formData.country" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    <?php
                                    $countries = WC()->countries->get_countries();
                                    foreach ($countries as $code => $name) :
                                    ?>
                                        <option value="<?php echo esc_attr($code); ?>"><?php echo esc_html($name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php _e('Phone', 'tostishop'); ?>
                                </label>
                                <input type="tel" x-model="formData.phone" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <!-- Email (for billing addresses) -->
                        <div x-show="formData.address_type === 'billing'">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <?php _e('Email', 'tostishop'); ?>
                            </label>
                            <input type="email" x-model="formData.email" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>
                        
                        <!-- Default Address Checkbox -->
                        <div class="flex items-center">
                            <input type="checkbox" x-model="formData.is_default" 
                                   class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <label class="ml-2 block text-sm text-gray-700">
                                <?php _e('Set as default address', 'tostishop'); ?>
                            </label>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="flex space-x-4 pt-6">
                            <button type="submit" :disabled="loading" 
                                    class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!loading" x-text="editingAddress ? '<?php _e('Update Address', 'tostishop'); ?>' : '<?php _e('Save Address', 'tostishop'); ?>'"></span>
                                <span x-show="loading"><?php _e('Saving...', 'tostishop'); ?></span>
                            </button>
                            <button type="button" @click="closeForm()" 
                                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                <?php _e('Cancel', 'tostishop'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alpine.js Address Book Manager -->
    <script>
    function addressBookManager() {
        return {
            // State
            showAddForm: false,
            showEditForm: false,
            editingAddress: null,
            loading: false,
            message: '',
            messageType: 'success',
            
            // Form data
            formData: {
                id: null,
                address_type: 'billing',
                address_name: 'Home',
                first_name: '',
                last_name: '',
                company: '',
                address_1: '',
                address_2: '',
                city: '',
                state: '',
                postcode: '',
                country: '<?php echo esc_attr(WC()->countries->get_base_country()); ?>',
                phone: '',
                email: '',
                is_default: false
            },
            
            // Show success message
            showMessage(message, type = 'success') {
                this.message = message;
                this.messageType = type;
                setTimeout(() => {
                    this.message = '';
                }, 5000);
            },
            
            // Reset form data
            resetForm() {
                this.formData = {
                    id: null,
                    address_type: 'billing',
                    address_name: 'Home',
                    first_name: '',
                    last_name: '',
                    company: '',
                    address_1: '',
                    address_2: '',
                    city: '',
                    state: '',
                    postcode: '',
                    country: '<?php echo esc_attr(WC()->countries->get_base_country()); ?>',
                    phone: '',
                    email: '',
                    is_default: false
                };
            },
            
            // Close form
            closeForm() {
                this.showAddForm = false;
                this.showEditForm = false;
                this.editingAddress = null;
                this.resetForm();
            },
            
            // Edit address
            editAddress(address) {
                this.editingAddress = address;
                this.formData = { ...address };
                this.showEditForm = true;
                this.showAddForm = false;
            },
            
            // Save address (add or update)
            async saveAddress() {
                this.loading = true;
                
                try {
                    const formData = new FormData();
                    formData.append('action', 'tostishop_save_address');
                    formData.append('nonce', tostishop_addresses.nonce);
                    
                    // Add all form data
                    Object.keys(this.formData).forEach(key => {
                        formData.append(key, this.formData[key] || '');
                    });
                    
                    const response = await fetch(tostishop_addresses.ajax_url, {
                        method: 'POST',
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.showMessage(data.data.message || 'Address saved successfully!');
                        this.closeForm();
                        // Reload page to show updated addresses
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        this.showMessage(data.data.message || 'Error saving address', 'error');
                    }
                } catch (error) {
                    this.showMessage('Network error. Please try again.', 'error');
                } finally {
                    this.loading = false;
                }
            },
            
            // Set default address
            async setDefaultAddress(addressId, addressType) {
                try {
                    const formData = new FormData();
                    formData.append('action', 'tostishop_set_default_address');
                    formData.append('nonce', tostishop_addresses.nonce);
                    formData.append('address_id', addressId);
                    formData.append('address_type', addressType);
                    
                    const response = await fetch(tostishop_addresses.ajax_url, {
                        method: 'POST',
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.showMessage('Default address updated!');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        this.showMessage(data.data.message || 'Error updating default address', 'error');
                    }
                } catch (error) {
                    this.showMessage('Network error. Please try again.', 'error');
                }
            },
            
            // Delete address
            async deleteAddress(addressId) {
                if (!confirm('<?php _e('Are you sure you want to delete this address?', 'tostishop'); ?>')) {
                    return;
                }
                
                try {
                    const formData = new FormData();
                    formData.append('action', 'tostishop_delete_address');
                    formData.append('nonce', tostishop_addresses.nonce);
                    formData.append('address_id', addressId);
                    
                    const response = await fetch(tostishop_addresses.ajax_url, {
                        method: 'POST',
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.showMessage('Address deleted successfully!');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        this.showMessage(data.data.message || 'Error deleting address', 'error');
                    }
                } catch (error) {
                    this.showMessage('Network error. Please try again.', 'error');
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
        <div class="woocommerce-message woocommerce-message--info bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-blue-800"><?php _e('The address book feature is currently unavailable. Please contact support.', 'tostishop'); ?></p>
        </div>
    </div>
    
    <div class="woocommerce-MyAccount-content">
        <div class="text-center py-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4"><?php _e('Address Management Unavailable', 'tostishop'); ?></h3>
            <p class="text-gray-600 mb-6"><?php _e('We are currently experiencing technical difficulties with the address book feature.', 'tostishop'); ?></p>
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="inline-flex items-center px-6 py-3 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <?php _e('Go to Dashboard', 'tostishop'); ?>
            </a>
        </div>
    </div>
    <?php
}