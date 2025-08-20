/**
 * TostiShop Algolia Search - Minimal Header Implementation
 */

// Check if Algolia libraries are loaded
function waitForAlgolia(callback, maxAttempts = 15) {
    let attempts = 0;
    
    function checkLibraries() {
        attempts++;
        
        if (typeof algoliasearch !== 'undefined' && typeof instantsearch !== 'undefined') {
            console.log('✅ Algolia libraries loaded');
            callback();
            return;
        }
        
        // Try fallback CDN on attempt 5
        if (attempts === 5 && typeof algoliasearch === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/algoliasearch@4/dist/algoliasearch.umd.js';
            script.onload = () => console.log('✅ Fallback CDN loaded');
            document.head.appendChild(script);
        }
        
        if (attempts >= maxAttempts) {
            console.error('❌ Algolia libraries failed to load');
            return;
        }
        
        setTimeout(checkLibraries, 200);
    }
    
    checkLibraries();
}

// Create search dropdown
function createSearchDropdown(searchBox) {
    const parent = searchBox.closest('div');
    if (!parent) return;
    
    const dropdown = document.createElement('div');
    dropdown.id = 'algolia-dropdown';
    dropdown.className = 'absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg z-50 mt-1 hidden';
    dropdown.innerHTML = `
        <div class="p-4">
            <div id="algolia-stats" class="text-sm text-gray-600 mb-3"></div>
            <div id="algolia-hits" class="max-h-80 overflow-y-auto"></div>
        </div>
    `;
    
    parent.style.position = 'relative';
    parent.appendChild(dropdown);
    
    // Show/hide dropdown
    searchBox.addEventListener('input', (e) => {
        const query = e.target.value.trim();
        dropdown.classList.toggle('hidden', query.length === 0);
    });
    
    // Hide when clicking outside
    document.addEventListener('click', (e) => {
        if (!parent.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
}

// Initialize Algolia
function initAlgolia() {
    if (typeof tostishopAlgolia === 'undefined' || !tostishopAlgolia.appId) {
        console.log('❌ Algolia config missing');
        return;
    }
    
    try {
        const searchClient = algoliasearch(tostishopAlgolia.appId, tostishopAlgolia.searchKey);
        const search = instantsearch({
            indexName: tostishopAlgolia.indexName,
            searchClient,
            searchFunction: (helper) => {
                if (helper.state.query === '') return;
                helper.search();
            }
        });
        
        // Add widgets
        search.addWidgets([
            instantsearch.widgets.searchBox({
                container: '#algolia-search-box',
                placeholder: 'Search products...',
                showReset: false,
                showSubmit: false
            }),
            instantsearch.widgets.hits({
                container: '#algolia-hits',
                templates: {
                    item: `
                        <div class="flex items-center p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100" onclick="window.location.href='{{url}}'">
                            {{#image}}
                            <img src="{{image}}" alt="{{title}}" class="w-12 h-12 object-cover rounded mr-3">
                            {{/image}}
                            {{^image}}
                            <div class="w-12 h-12 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            {{/image}}
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 truncate">{{#helpers.highlight}}{"attribute": "title"}{{/helpers.highlight}}</h4>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">{{price}}</span>
                                </p>
                            </div>
                        </div>
                    `,
                    empty: `
                        <div class="text-center py-8">
                            <p class="text-gray-500">No products found</p>
                        </div>
                    `
                }
            }),
            instantsearch.widgets.stats({
                container: '#algolia-stats',
                templates: {
                    text: '{{#hasNoResults}}No results{{/hasNoResults}}{{#hasOneResult}}1 result{{/hasOneResult}}{{#hasManyResults}}{{nbHits}} results{{/hasManyResults}}'
                }
            })
        ]);
        
        search.start();
        window.tostishopSearch = search;
        console.log('🎉 Algolia search ready');
        
    } catch (error) {
        console.error('❌ Algolia init error:', error);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    const searchBox = document.querySelector('#algolia-search-box');
    
    if (!searchBox) {
        console.log('ℹ️ No search box found');
        return;
    }
    
    // Create dropdown if it doesn't exist
    if (!document.querySelector('#algolia-hits')) {
        createSearchDropdown(searchBox);
    }
    
    // Wait for libraries and initialize
    waitForAlgolia(initAlgolia);
});
