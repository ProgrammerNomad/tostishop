/**
 * TostiShop Algolia Search Integration
 * InstantSearch.js implementation for WooCommerce products
 */

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if Algolia config is available
    if (typeof tostishopAlgolia === 'undefined' || !tostishopAlgolia.appId) {
        console.log('Algolia configuration not found');
        return;
    }
    
    console.log('Initializing TostiShop Algolia Search...');
    
    // Initialize Algolia search client
    const searchClient = algoliasearch(tostishopAlgolia.appId, tostishopAlgolia.searchKey);
    
    // Initialize InstantSearch
    const search = instantsearch({
        indexName: tostishopAlgolia.indexName,
        searchClient,
        searchFunction: function(helper) {
            // Only search if there's a query or filters
            if (helper.state.query === '' && Object.keys(helper.state.facetsRefinements).length === 0) {
                return;
            }
            helper.search();
        }
    });
    
    // Add search widgets
    addSearchWidgets(search);
    
    // Initialize autocomplete if enabled
    if (tostishopAlgolia.autocomplete) {
        initializeAutocomplete();
    }
    
    // Start InstantSearch
    search.start();
    
    console.log('Algolia Search initialized successfully');
});

/**
 * Add search widgets to InstantSearch instance
 */
function addSearchWidgets(search) {
    // Search Box
    search.addWidgets([
        instantsearch.widgets.searchBox({
            container: '#algolia-search-box',
            placeholder: 'Search products...',
            showReset: true,
            showSubmit: true,
            cssClasses: {
                root: 'w-full',
                form: 'relative',
                input: 'w-full px-4 py-3 pr-12 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                submit: 'absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600',
                reset: 'absolute right-10 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600'
            }
        })
    ]);
    
    // Search Results
    search.addWidgets([
        instantsearch.widgets.hits({
            container: '#algolia-hits',
            templates: {
                empty: getEmptyTemplate(),
                item: getHitTemplate()
            },
            cssClasses: {
                root: 'w-full',
                list: 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6',
                item: 'bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200'
            }
        })
    ]);
    
    // Pagination
    search.addWidgets([
        instantsearch.widgets.pagination({
            container: '#algolia-pagination',
            cssClasses: {
                root: 'flex justify-center items-center space-x-2 mt-8',
                list: 'flex items-center space-x-1',
                item: 'px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50',
                selectedItem: 'px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-md',
                disabledItem: 'px-3 py-2 text-sm font-medium text-gray-300 bg-gray-100 border border-gray-200 rounded-md cursor-not-allowed'
            }
        })
    ]);
    
    // Stats
    search.addWidgets([
        instantsearch.widgets.stats({
            container: '#algolia-stats',
            templates: {
                text: '{{#hasNoResults}}No results{{/hasNoResults}}{{#hasOneResult}}1 result{{/hasOneResult}}{{#hasManyResults}}{{#helpers.formatNumber}}{{nbHits}}{{/helpers.formatNumber}} results{{/hasManyResults}} found in {{processingTimeMS}}ms'
            },
            cssClasses: {
                root: 'text-sm text-gray-600 mb-4'
            }
        })
    ]);
    
    // Refinement List - Categories
    search.addWidgets([
        instantsearch.widgets.refinementList({
            container: '#algolia-categories',
            attribute: 'categories',
            limit: 10,
            showMore: true,
            showMoreLimit: 20,
            cssClasses: {
                root: 'mb-6',
                list: 'space-y-2',
                item: 'flex items-center',
                checkbox: 'mr-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500',
                label: 'text-sm text-gray-700 cursor-pointer',
                count: 'ml-auto text-xs text-gray-500'
            }
        })
    ]);
    
    // Range Input - Price
    search.addWidgets([
        instantsearch.widgets.rangeInput({
            container: '#algolia-price-range',
            attribute: 'price',
            precision: 2,
            templates: {
                separatorText: 'to'
            },
            cssClasses: {
                root: 'mb-6',
                form: 'flex items-center space-x-2',
                input: 'w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-blue-500',
                separator: 'text-sm text-gray-500'
            }
        })
    ]);
    
    // Toggle Refinement - On Sale
    search.addWidgets([
        instantsearch.widgets.toggleRefinement({
            container: '#algolia-on-sale',
            attribute: 'on_sale',
            templates: {
                labelText: 'On Sale Only'
            },
            cssClasses: {
                root: 'mb-4',
                checkbox: 'mr-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500',
                label: 'text-sm text-gray-700 cursor-pointer'
            }
        })
    ]);
    
    // Toggle Refinement - In Stock
    search.addWidgets([
        instantsearch.widgets.toggleRefinement({
            container: '#algolia-in-stock',
            attribute: 'in_stock',
            templates: {
                labelText: 'In Stock Only'
            },
            cssClasses: {
                root: 'mb-4',
                checkbox: 'mr-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500',
                label: 'text-sm text-gray-700 cursor-pointer'
            }
        })
    ]);
    
    // Sort By
    search.addWidgets([
        instantsearch.widgets.sortBy({
            container: '#algolia-sort-by',
            items: [
                { label: 'Relevance', value: tostishopAlgolia.indexName },
                { label: 'Price: Low to High', value: tostishopAlgolia.indexName + '_price_asc' },
                { label: 'Price: High to Low', value: tostishopAlgolia.indexName + '_price_desc' },
                { label: 'Date: Newest First', value: tostishopAlgolia.indexName + '_date_desc' },
                { label: 'Rating: Highest First', value: tostishopAlgolia.indexName + '_rating_desc' }
            ],
            cssClasses: {
                root: 'mb-4',
                select: 'w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500'
            }
        })
    ]);
    
    // Clear Refinements
    search.addWidgets([
        instantsearch.widgets.clearRefinements({
            container: '#algolia-clear-refinements',
            templates: {
                resetLabel: 'Clear All Filters'
            },
            cssClasses: {
                root: 'mb-4',
                button: 'w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:ring-2 focus:ring-blue-500'
            }
        })
    ]);
}

/**
 * Get hit template for product display
 */
function getHitTemplate() {
    return `
    <div class="product-item h-full flex flex-col">
        <div class="aspect-square bg-gray-100 rounded-lg mb-4 relative overflow-hidden group">
            {{#image}}
            <img src="{{image}}" alt="{{title}}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
            {{/image}}
            {{^image}}
            <div class="w-full h-full flex items-center justify-center text-gray-400">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            {{/image}}
            
            {{#on_sale}}
            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                Sale
            </div>
            {{/on_sale}}
            
            {{^in_stock}}
            <div class="absolute top-2 right-2 bg-gray-500 text-white text-xs font-bold px-2 py-1 rounded">
                Out of Stock
            </div>
            {{/in_stock}}
        </div>
        
        <div class="flex-1 flex flex-col">
            <h3 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2">
                <a href="{{url}}" class="hover:text-blue-600">
                    {{#helpers.highlight}}{"attribute": "title"}{{/helpers.highlight}}
                </a>
            </h3>
            
            {{#short_description}}
            <p class="text-xs text-gray-600 mb-3 line-clamp-2">
                {{#helpers.highlight}}{"attribute": "short_description"}{{/helpers.highlight}}
            </p>
            {{/short_description}}
            
            <div class="mt-auto">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-lg font-bold text-gray-900">
                        {{#sale_price}}
                        <span class="text-red-600">${{sale_price}}</span>
                        <span class="text-sm text-gray-500 line-through ml-1">${{regular_price}}</span>
                        {{/sale_price}}
                        {{^sale_price}}
                        ${{price}}
                        {{/sale_price}}
                    </div>
                    
                    {{#rating_average}}
                    <div class="flex items-center">
                        <div class="flex text-yellow-400">
                            {{#rating_stars}}
                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                            {{/rating_stars}}
                        </div>
                        <span class="text-xs text-gray-500 ml-1">({{rating_count}})</span>
                    </div>
                    {{/rating_average}}
                </div>
                
                <button class="add-to-cart-btn w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors duration-200 text-sm font-medium"
                        data-product-id="{{objectID}}"
                        {{^in_stock}}disabled class="bg-gray-400 cursor-not-allowed"{{/in_stock}}>
                    {{#in_stock}}Add to Cart{{/in_stock}}
                    {{^in_stock}}Out of Stock{{/in_stock}}
                </button>
            </div>
        </div>
    </div>`;
}

/**
 * Get empty results template
 */
function getEmptyTemplate() {
    return `
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
    </div>`;
}

/**
 * Initialize autocomplete functionality
 */
function initializeAutocomplete() {
    const searchInput = document.querySelector('#algolia-search-box input');
    if (!searchInput) return;
    
    const searchClient = algoliasearch(tostishopAlgolia.appId, tostishopAlgolia.searchKey);
    
    autocomplete({
        container: searchInput.parentElement,
        placeholder: 'Search products...',
        getSources() {
            return [
                {
                    sourceId: 'products',
                    getItems({ query }) {
                        return searchClient.search([
                            {
                                indexName: tostishopAlgolia.indexName,
                                query,
                                params: {
                                    hitsPerPage: tostishopAlgolia.suggestionsCount
                                }
                            }
                        ]).then(({ results }) => {
                            return results[0].hits;
                        });
                    },
                    templates: {
                        item({ item, components }) {
                            return `
                            <div class="flex items-center p-2 hover:bg-gray-50">
                                ${item.image ? `<img src="${item.image}" alt="${item.title}" class="w-10 h-10 object-cover rounded mr-3">` : ''}
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 truncate">
                                        ${components.Highlight({ hit: item, attribute: 'title' })}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        $${item.price}
                                    </div>
                                </div>
                            </div>`;
                        }
                    },
                    getItemUrl({ item }) {
                        return item.url;
                    }
                }
            ];
        }
    });
}

/**
 * Add to cart functionality
 */
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('add-to-cart-btn')) {
        e.preventDefault();
        
        const button = e.target;
        const productId = button.dataset.productId;
        
        if (!productId) return;
        
        // Show loading state
        const originalText = button.textContent;
        button.textContent = 'Adding...';
        button.disabled = true;
        
        // Add to cart via AJAX (reuse existing cart functionality)
        const formData = new FormData();
        formData.append('action', 'tostishop_add_to_cart');
        formData.append('product_id', productId);
        formData.append('quantity', 1);
        formData.append('nonce', tostishopAlgolia.nonce);
        
        fetch(ajaxurl || '/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count if element exists
                const cartCount = document.querySelector('.cart-count');
                if (cartCount && data.data.cart_count) {
                    cartCount.textContent = data.data.cart_count;
                }
                
                // Show success message
                button.textContent = 'Added!';
                button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                button.classList.add('bg-green-600');
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-blue-600', 'hover:bg-blue-700');
                }, 2000);
            } else {
                throw new Error(data.data || 'Failed to add to cart');
            }
        })
        .catch(error => {
            console.error('Add to cart error:', error);
            button.textContent = 'Error';
            button.classList.add('bg-red-600');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.disabled = false;
                button.classList.remove('bg-red-600');
                button.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }, 2000);
        });
    }
});

/**
 * Helper function to generate star rating
 */
function generateStars(rating) {
    const stars = [];
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 !== 0;
    
    for (let i = 0; i < fullStars; i++) {
        stars.push({ type: 'full' });
    }
    
    if (hasHalfStar) {
        stars.push({ type: 'half' });
    }
    
    while (stars.length < 5) {
        stars.push({ type: 'empty' });
    }
    
    return stars;
}

// Make rating stars available in templates
if (typeof Handlebars !== 'undefined') {
    Handlebars.registerHelper('rating_stars', function() {
        return generateStars(this.rating_average);
    });
}
