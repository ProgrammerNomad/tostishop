/**
 * Algolia Debug Script
 * Quick test to verify Algolia libraries are loading correctly
 */

console.log('=== Algolia Debug Test ===');

// Test 1: Check if configuration is available
if (typeof tostishopAlgolia === 'undefined') {
    console.error('❌ tostishopAlgolia configuration not found');
} else {
    console.log('✅ tostishopAlgolia configuration found:', tostishopAlgolia);
}

// Test 2: Check if algoliasearch is available
if (typeof algoliasearch === 'undefined') {
    console.error('❌ algoliasearch function not found - Search Client library not loaded');
} else {
    console.log('✅ algoliasearch function available');
}

// Test 3: Check if instantsearch is available
if (typeof instantsearch === 'undefined') {
    console.error('❌ instantsearch function not found - InstantSearch library not loaded');
} else {
    console.log('✅ instantsearch function available');
}

// Test 4: Check if autocomplete is available (optional)
if (typeof autocomplete === 'undefined') {
    console.warn('⚠️ autocomplete function not found - Autocomplete library not loaded (optional)');
} else {
    console.log('✅ autocomplete function available');
}

// Test 5: Try to create search client if everything is available
if (typeof tostishopAlgolia !== 'undefined' && typeof algoliasearch !== 'undefined' && tostishopAlgolia.appId) {
    try {
        const testClient = algoliasearch(tostishopAlgolia.appId, tostishopAlgolia.searchKey);
        console.log('✅ Search client created successfully');
        
        // Test search index
        const index = testClient.initIndex(tostishopAlgolia.indexName);
        console.log('✅ Search index initialized:', tostishopAlgolia.indexName);
        
    } catch (error) {
        console.error('❌ Error creating search client:', error);
    }
} else {
    console.error('❌ Cannot test search client - missing requirements');
}

console.log('=== End Algolia Debug Test ===');
