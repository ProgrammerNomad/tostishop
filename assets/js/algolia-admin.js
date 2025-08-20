/**
 * TostiShop Algolia Admin JavaScript
 * Handles admin functionality for Algolia settings page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize admin functionality
    initializeTabSwitching();
    initializeSyncFunctionality();
    initializeTestConnection();
    initializeClearIndex();
});

/**
 * Tab switching functionality
 */
function initializeTabSwitching() {
    const tabs = document.querySelectorAll('.nav-tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('nav-tab-active'));
            
            // Add active class to clicked tab
            this.classList.add('nav-tab-active');
            
            // Hide all tab contents
            tabContents.forEach(content => content.style.display = 'none');
            
            // Show target tab content
            const targetId = this.getAttribute('href').substring(1);
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.style.display = 'block';
            }
        });
    });
}

/**
 * Product sync functionality
 */
function initializeSyncFunctionality() {
    const syncButton = document.getElementById('sync-all-products');
    if (!syncButton) return;
    
    syncButton.addEventListener('click', function() {
        if (!confirm('This will sync all products to Algolia. Continue?')) {
            return;
        }
        
        syncAllProducts();
    });
}

/**
 * Sync all products to Algolia
 */
function syncAllProducts() {
    const progressContainer = document.getElementById('sync-progress');
    const progressBar = document.getElementById('progress-fill');
    const progressText = document.getElementById('progress-text');
    const syncButton = document.getElementById('sync-all-products');
    
    // Show progress container
    progressContainer.style.display = 'block';
    
    // Disable sync button
    syncButton.disabled = true;
    syncButton.textContent = 'Syncing...';
    
    // Reset progress
    progressBar.style.width = '0%';
    progressText.textContent = 'Starting sync...';
    
    // Start batch sync
    syncBatch(0);
    
    function syncBatch(batchNumber) {
        const formData = new FormData();
        formData.append('action', 'tostishop_algolia_sync_all');
        formData.append('batch', batchNumber);
        formData.append('nonce', getAlgoliaNonce());
        
        fetch(ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update progress
                const progress = data.data.progress || 0;
                progressBar.style.width = progress + '%';
                progressText.textContent = data.data.message;
                
                // Continue with next batch if not complete
                if (!data.data.complete && data.data.next_batch !== null) {
                    setTimeout(() => {
                        syncBatch(data.data.next_batch);
                    }, 500); // Small delay to prevent overwhelming
                } else {
                    // Sync complete
                    progressText.textContent = 'Sync completed successfully!';
                    syncButton.disabled = false;
                    syncButton.textContent = 'Sync All Products';
                    
                    // Hide progress after 3 seconds
                    setTimeout(() => {
                        progressContainer.style.display = 'none';
                    }, 3000);
                }
            } else {
                throw new Error(data.data || 'Sync failed');
            }
        })
        .catch(error => {
            console.error('Sync error:', error);
            progressText.textContent = 'Sync failed: ' + error.message;
            progressBar.style.backgroundColor = '#dc3545';
            syncButton.disabled = false;
            syncButton.textContent = 'Sync All Products';
        });
    }
}

/**
 * Test Algolia connection
 */
function initializeTestConnection() {
    const testButton = document.getElementById('test-connection');
    if (!testButton) return;
    
    testButton.addEventListener('click', function() {
        testAlgoliaConnection();
    });
}

/**
 * Test Algolia connection
 */
function testAlgoliaConnection() {
    const testButton = document.getElementById('test-connection');
    const originalText = testButton.textContent;
    
    testButton.disabled = true;
    testButton.textContent = 'Testing...';
    
    const formData = new FormData();
    formData.append('action', 'tostishop_algolia_test_connection');
    formData.append('nonce', getAlgoliaNonce());
    
    fetch(ajaxurl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Connection successful!\n\nIndex: ${data.data.index_name}\nRecords: ${data.data.total_records}\nResponse time: ${data.data.processing_time}`);
        } else {
            throw new Error(data.data || 'Connection test failed');
        }
    })
    .catch(error => {
        console.error('Connection test error:', error);
        alert('Connection failed: ' + error.message);
    })
    .finally(() => {
        testButton.disabled = false;
        testButton.textContent = originalText;
    });
}

/**
 * Clear Algolia index
 */
function initializeClearIndex() {
    const clearButton = document.getElementById('clear-index');
    if (!clearButton) return;
    
    clearButton.addEventListener('click', function() {
        if (!confirm('This will permanently delete all records from the Algolia index. Are you sure?')) {
            return;
        }
        
        clearAlgoliaIndex();
    });
}

/**
 * Clear Algolia index
 */
function clearAlgoliaIndex() {
    const clearButton = document.getElementById('clear-index');
    const originalText = clearButton.textContent;
    
    clearButton.disabled = true;
    clearButton.textContent = 'Clearing...';
    
    const formData = new FormData();
    formData.append('action', 'tostishop_algolia_clear_index');
    formData.append('nonce', getAlgoliaNonce());
    
    fetch(ajaxurl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Index cleared successfully!');
        } else {
            throw new Error(data.data || 'Failed to clear index');
        }
    })
    .catch(error => {
        console.error('Clear index error:', error);
        alert('Failed to clear index: ' + error.message);
    })
    .finally(() => {
        clearButton.disabled = false;
        clearButton.textContent = originalText;
    });
}

/**
 * Get Algolia nonce from WordPress
 */
function getAlgoliaNonce() {
    // Try to get nonce from a hidden field or localized script
    const nonceField = document.querySelector('[name="tostishop_algolia_nonce"]');
    if (nonceField) {
        return nonceField.value;
    }
    
    // Fallback: try to get from global variable
    if (typeof tostishopAlgoliaNonce !== 'undefined') {
        return tostishopAlgoliaNonce;
    }
    
    // Create a basic nonce (this should be replaced with proper WordPress nonce)
    return 'algolia_admin_' + Date.now();
}

/**
 * Show notification
 */
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notice notice-${type} is-dismissible`;
    notification.innerHTML = `<p>${message}</p>`;
    
    const wrap = document.querySelector('.wrap h1');
    if (wrap) {
        wrap.parentNode.insertBefore(notification, wrap.nextSibling);
    }
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

/**
 * Auto-save settings preview
 */
function initializeSettingsPreview() {
    const settingsForm = document.querySelector('form[action="options.php"]');
    if (!settingsForm) return;
    
    const inputs = settingsForm.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            // Show unsaved changes indicator
            showUnsavedChangesIndicator();
        });
    });
}

/**
 * Show unsaved changes indicator
 */
function showUnsavedChangesIndicator() {
    let indicator = document.querySelector('.unsaved-changes-indicator');
    
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.className = 'unsaved-changes-indicator';
        indicator.innerHTML = `
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 10px; margin: 10px 0; border-radius: 4px;">
                <strong>Unsaved Changes:</strong> You have unsaved changes. Don't forget to save your settings.
            </div>
        `;
        
        const form = document.querySelector('form[action="options.php"]');
        if (form) {
            form.insertBefore(indicator, form.firstChild);
        }
    }
}

/**
 * Initialize settings preview functionality
 */
initializeSettingsPreview();
