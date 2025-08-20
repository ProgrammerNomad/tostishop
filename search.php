<?php
/**
 * Algolia Search Results Page
 * Template for displaying Algolia-powered search results
 * 
 * @package TostiShop
 */

get_header(); ?>

<div class="algolia-search-container py-8">
    <!-- Search Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">
            <?php 
            if (isset($_GET['s']) && !empty($_GET['s'])) {
                printf(__('Search Results for: %s', 'tostishop'), '<span class="text-blue-600">' . esc_html($_GET['s']) . '</span>');
            } else {
                _e('Product Search', 'tostishop');
            }
            ?>
        </h1>
        
        <!-- Algolia Search Box -->
        <div id="algolia-search-box" class="max-w-2xl"></div>
    </div>
    
    <!-- Search Content -->
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Filters Sidebar -->
        <aside class="w-full lg:w-64 xl:w-72">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-6"><?php _e('Filters', 'tostishop'); ?></h2>
                
                <!-- Clear Filters -->
                <div id="algolia-clear-refinements"></div>
                
                <!-- Sort By -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-3"><?php _e('Sort By', 'tostishop'); ?></h3>
                    <div id="algolia-sort-by"></div>
                </div>
                
                <!-- Categories -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-3"><?php _e('Categories', 'tostishop'); ?></h3>
                    <div id="algolia-categories"></div>
                </div>
                
                <!-- Price Range -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-3"><?php _e('Price Range', 'tostishop'); ?></h3>
                    <div id="algolia-price-range"></div>
                </div>
                
                <!-- On Sale Toggle -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-3"><?php _e('Special Offers', 'tostishop'); ?></h3>
                    <div id="algolia-on-sale"></div>
                    <div id="algolia-in-stock"></div>
                </div>
            </div>
        </aside>
        
        <!-- Results Area -->
        <main class="flex-1">
            <!-- Stats -->
            <div id="algolia-stats" class="mb-4"></div>
            
            <!-- Results -->
            <div id="algolia-hits" class="mb-8"></div>
            
            <!-- Pagination -->
            <div id="algolia-pagination"></div>
        </main>
    </div>
</div>

<!-- Mobile Filters Toggle (shown only on mobile) -->
<div class="lg:hidden fixed bottom-4 right-4 z-50">
    <button id="mobile-filters-toggle" 
            class="bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition-colors duration-200"
            aria-label="<?php _e('Toggle Filters', 'tostishop'); ?>">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"></path>
        </svg>
    </button>
</div>

<!-- Mobile Filters Modal -->
<div id="mobile-filters-modal" class="lg:hidden fixed inset-0 z-40 hidden">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeMobileFilters()"></div>
    
    <!-- Modal Content -->
    <div class="fixed bottom-0 left-0 right-0 bg-white rounded-t-lg max-h-[80vh] overflow-y-auto">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900"><?php _e('Filters', 'tostishop'); ?></h2>
            <button onclick="closeMobileFilters()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="p-4">
            <!-- Mobile filters content (same as sidebar but for mobile) -->
            <div class="space-y-6">
                <div id="algolia-clear-refinements-mobile"></div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-900 mb-3"><?php _e('Sort By', 'tostishop'); ?></h3>
                    <div id="algolia-sort-by-mobile"></div>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-900 mb-3"><?php _e('Categories', 'tostishop'); ?></h3>
                    <div id="algolia-categories-mobile"></div>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-900 mb-3"><?php _e('Price Range', 'tostishop'); ?></h3>
                    <div id="algolia-price-range-mobile"></div>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-900 mb-3"><?php _e('Special Offers', 'tostishop'); ?></h3>
                    <div id="algolia-on-sale-mobile"></div>
                    <div id="algolia-in-stock-mobile"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mobile filters functionality
function openMobileFilters() {
    document.getElementById('mobile-filters-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeMobileFilters() {
    document.getElementById('mobile-filters-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

document.getElementById('mobile-filters-toggle').addEventListener('click', openMobileFilters);

// Auto-trigger search if there's a query parameter
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const searchQuery = urlParams.get('s');
    
    if (searchQuery && window.search) {
        window.search.helper.setQuery(searchQuery).search();
    }
});
</script>

<?php get_footer(); ?>
