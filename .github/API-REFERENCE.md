# TostiShop Theme - API Reference

## 🔌 Custom Functions & Hooks

Complete reference for TostiShop theme's custom functions, hooks, and development APIs.

---

## 🎯 Theme Functions Overview

### Core Theme Functions
Located in `functions.php` - Essential theme functionality and WooCommerce integration.

### JavaScript APIs  
Located in `assets/js/` - Alpine.js components and AJAX handlers.

### CSS Framework
Located in `assets/css/` - Tailwind utilities and custom components.

---

## 🔧 PHP Functions Reference

### Theme Setup Functions

#### `tostishop_setup()`
**Purpose**: Initialize theme support and features
```php
function tostishop_setup() {
    // Add theme support for various features
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    // Register navigation menus
    register_nav_menus(array(
        'primary' => 'Primary Navigation',
        'footer' => 'Footer Navigation'
    ));
}
add_action('after_setup_theme', 'tostishop_setup');
```

#### `tostishop_enqueue_scripts()`
**Purpose**: Load theme CSS and JavaScript files
```php
function tostishop_enqueue_scripts() {
    // Enqueue styles
    wp_enqueue_style('tostishop-style', get_stylesheet_uri());
    
    // Enqueue scripts
    wp_enqueue_script('tostishop-ui', 
        get_template_directory_uri() . '/assets/js/ui.js', 
        array(), '1.0.0', true);
    
    // Localize AJAX
    wp_localize_script('tostishop-ui', 'tostishop_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('tostishop_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'tostishop_enqueue_scripts');
```

### Cart & AJAX Functions

#### `tostishop_add_to_cart()`
**Purpose**: Handle AJAX add to cart requests
```php
function tostishop_add_to_cart() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_nonce')) {
        wp_die('Security check failed');
    }
    
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    
    // Add to cart
    $added = WC()->cart->add_to_cart($product_id, $quantity);
    
    if ($added) {
        wp_send_json_success(array(
            'message' => 'Product added to cart',
            'cart_count' => WC()->cart->get_cart_contents_count()
        ));
    } else {
        wp_send_json_error('Failed to add product to cart');
    }
}
add_action('wp_ajax_tostishop_add_to_cart', 'tostishop_add_to_cart');
add_action('wp_ajax_nopriv_tostishop_add_to_cart', 'tostishop_add_to_cart');
```

#### `tostishop_get_cart_count()`
**Purpose**: Get current cart item count
```php
function tostishop_get_cart_count() {
    if (WC()->cart) {
        return WC()->cart->get_cart_contents_count();
    }
    return 0;
}
```

#### `tostishop_update_cart_item()`
**Purpose**: Update cart item quantity via AJAX
```php
function tostishop_update_cart_item() {
    // Verify nonce
    check_ajax_referer('tostishop_nonce', 'nonce');
    
    $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    $quantity = intval($_POST['quantity']);
    
    if ($quantity <= 0) {
        WC()->cart->remove_cart_item($cart_item_key);
    } else {
        WC()->cart->set_quantity($cart_item_key, $quantity);
    }
    
    wp_send_json_success(array(
        'cart_total' => WC()->cart->get_cart_total(),
        'cart_count' => WC()->cart->get_cart_contents_count()
    ));
}
add_action('wp_ajax_tostishop_update_cart', 'tostishop_update_cart_item');
add_action('wp_ajax_nopriv_tostishop_update_cart', 'tostishop_update_cart_item');
```

### Product Display Functions

#### `tostishop_get_product_card($product_id)`
**Purpose**: Generate product card HTML
```php
function tostishop_get_product_card($product_id) {
    $product = wc_get_product($product_id);
    if (!$product) return '';
    
    ob_start();
    ?>
    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
        <div class="aspect-square overflow-hidden rounded-t-lg">
            <?php echo $product->get_image('large', array('class' => 'w-full h-full object-cover')); ?>
        </div>
        <div class="p-4">
            <h3 class="font-semibold text-gray-900 mb-2">
                <?php echo esc_html($product->get_name()); ?>
            </h3>
            <div class="flex items-center justify-between">
                <span class="text-lg font-bold text-navy-900">
                    <?php echo $product->get_price_html(); ?>
                </span>
                <button class="bg-accent text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-accent-600 transition-colors"
                        data-product-id="<?php echo $product_id; ?>">
                    Add to Cart
                </button>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
```

#### `tostishop_get_related_products($product_id, $limit = 4)`
**Purpose**: Get related products for product slider
```php
function tostishop_get_related_products($product_id, $limit = 4) {
    $product = wc_get_product($product_id);
    if (!$product) return array();
    
    $related_ids = wc_get_related_products($product_id, $limit);
    $related_products = array();
    
    foreach ($related_ids as $related_id) {
        $related_product = wc_get_product($related_id);
        if ($related_product && $related_product->is_in_stock()) {
            $related_products[] = $related_product;
        }
    }
    
    return $related_products;
}
```

### Utility Functions

#### `tostishop_is_mobile()`
**Purpose**: Detect mobile devices
```php
function tostishop_is_mobile() {
    return wp_is_mobile();
}
```

#### `tostishop_get_breadcrumbs()`
**Purpose**: Generate breadcrumb navigation
```php
function tostishop_get_breadcrumbs() {
    if (function_exists('woocommerce_breadcrumb')) {
        woocommerce_breadcrumb(array(
            'delimiter' => ' &gt; ',
            'wrap_before' => '<nav class="breadcrumb">',
            'wrap_after' => '</nav>',
            'before' => '<span>',
            'after' => '</span>',
            'home' => 'Home'
        ));
    }
}
```

---

## 🎨 CSS/Tailwind API

### Custom Color Classes
```css
/* Brand Colors */
.text-navy-900 { color: #14175b; }
.bg-navy-900 { background-color: #14175b; }
.text-accent { color: #e42029; }
.bg-accent { background-color: #e42029; }
.text-silver-50 { color: #ecebee; }
.bg-silver-50 { background-color: #ecebee; }
```

### Component Classes
```css
/* Product Card */
.product-card {
    @apply bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow;
}

/* Button Styles */
.btn-primary {
    @apply bg-accent text-white px-6 py-3 rounded-md font-medium hover:bg-accent-600 transition-colors;
}

.btn-secondary {
    @apply bg-gray-200 text-gray-800 px-6 py-3 rounded-md font-medium hover:bg-gray-300 transition-colors;
}

/* Mobile Menu */
.mobile-menu {
    @apply fixed inset-y-0 left-0 z-50 w-full max-w-sm bg-white shadow-xl transform transition-transform;
}
```

### Responsive Utilities
```css
/* Mobile-First Breakpoints */
@media (min-width: 640px) { /* sm */ }
@media (min-width: 768px) { /* md */ }
@media (min-width: 1024px) { /* lg */ }
@media (min-width: 1280px) { /* xl */ }
```

---

## 🚀 JavaScript API

### Alpine.js Components

#### Mobile Menu Component
```javascript
// Usage in HTML
<div x-data="mobileMenu()">
    <button @click="toggle()">Menu</button>
    <div x-show="isOpen" x-transition>
        <!-- Menu content -->
    </div>
</div>

// Component Definition
function mobileMenu() {
    return {
        isOpen: false,
        toggle() {
            this.isOpen = !this.isOpen;
        },
        close() {
            this.isOpen = false;
        }
    }
}
```

#### Product Gallery Component
```javascript
// Usage in HTML
<div x-data="productGallery()">
    <div class="main-image">
        <img :src="currentImage" alt="Product">
    </div>
    <div class="thumbnails">
        <img x-for="(image, index) in images"
             @click="setCurrentImage(index)"
             :src="image"
             :class="{ 'active': currentIndex === index }">
    </div>
</div>

// Component Definition
function productGallery() {
    return {
        currentIndex: 0,
        images: [],
        get currentImage() {
            return this.images[this.currentIndex];
        },
        setCurrentImage(index) {
            this.currentIndex = index;
        }
    }
}
```

#### Shopping Cart Component
```javascript
// Cart Management
function shoppingCart() {
    return {
        items: [],
        isOpen: false,
        
        addItem(productId, quantity = 1) {
            fetch(tostishop_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'tostishop_add_to_cart',
                    product_id: productId,
                    quantity: quantity,
                    nonce: tostishop_ajax.nonce
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.updateCartCount(data.data.cart_count);
                    this.showNotification('Product added to cart!');
                }
            });
        },
        
        updateCartCount(count) {
            document.querySelector('.cart-count').textContent = count;
        },
        
        showNotification(message) {
            // Display success notification
            window.dispatchEvent(new CustomEvent('cart-updated', {
                detail: { message }
            }));
        }
    }
}
```

### AJAX Functions

#### Add to Cart
```javascript
function addToCart(productId, quantity = 1) {
    const formData = new FormData();
    formData.append('action', 'tostishop_add_to_cart');
    formData.append('product_id', productId);
    formData.append('quantity', quantity);
    formData.append('nonce', tostishop_ajax.nonce);
    
    return fetch(tostishop_ajax.ajax_url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.data.cart_count);
            showNotification(data.data.message);
        } else {
            showError(data.data);
        }
        return data;
    });
}
```

#### Update Cart Item
```javascript
function updateCartItem(cartItemKey, quantity) {
    const formData = new FormData();
    formData.append('action', 'tostishop_update_cart');
    formData.append('cart_item_key', cartItemKey);
    formData.append('quantity', quantity);
    formData.append('nonce', tostishop_ajax.nonce);
    
    return fetch(tostishop_ajax.ajax_url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartTotals(data.data);
        }
        return data;
    });
}
```

---

## 🎣 WordPress Hooks

### Custom Action Hooks

#### `tostishop_header_before`
**Purpose**: Insert content before header
```php
// Usage in templates
do_action('tostishop_header_before');

// Hook example
add_action('tostishop_header_before', function() {
    echo '<div class="header-notice">Free shipping on orders over $50!</div>';
});
```

#### `tostishop_footer_after`
**Purpose**: Insert content after footer
```php
// Usage in templates
do_action('tostishop_footer_after');

// Hook example
add_action('tostishop_footer_after', function() {
    echo '<script>console.log("TostiShop loaded");</script>';
});
```

### Custom Filter Hooks

#### `tostishop_cart_button_text`
**Purpose**: Customize add to cart button text
```php
// Usage in theme
$button_text = apply_filters('tostishop_cart_button_text', 'Add to Cart', $product);

// Filter example
add_filter('tostishop_cart_button_text', function($text, $product) {
    if ($product->is_on_sale()) {
        return 'Buy Now - On Sale!';
    }
    return $text;
}, 10, 2);
```

#### `tostishop_product_card_classes`
**Purpose**: Modify product card CSS classes
```php
// Usage in theme
$classes = apply_filters('tostishop_product_card_classes', 'bg-white rounded-lg shadow-sm');

// Filter example
add_filter('tostishop_product_card_classes', function($classes) {
    return $classes . ' hover:transform hover:scale-105';
});
```

---

## 📊 Data Structure Reference

### Product Data Structure
```php
// Product object properties
$product = wc_get_product($product_id);

$product_data = array(
    'id' => $product->get_id(),
    'name' => $product->get_name(),
    'slug' => $product->get_slug(),
    'price' => $product->get_price(),
    'regular_price' => $product->get_regular_price(),
    'sale_price' => $product->get_sale_price(),
    'image_url' => wp_get_attachment_url($product->get_image_id()),
    'gallery_ids' => $product->get_gallery_image_ids(),
    'in_stock' => $product->is_in_stock(),
    'stock_quantity' => $product->get_stock_quantity(),
    'categories' => wp_get_post_terms($product_id, 'product_cat'),
    'attributes' => $product->get_attributes()
);
```

### Cart Data Structure
```php
// Cart item structure
foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
    $cart_data = array(
        'key' => $cart_item_key,
        'product_id' => $cart_item['product_id'],
        'variation_id' => $cart_item['variation_id'],
        'quantity' => $cart_item['quantity'],
        'data' => $cart_item['data'], // Product object
        'line_total' => $cart_item['line_total'],
        'line_subtotal' => $cart_item['line_subtotal']
    );
}
```

---

## 🔐 Security Guidelines

### Nonce Verification
```php
// Always verify nonces for AJAX requests
if (!wp_verify_nonce($_POST['nonce'], 'tostishop_nonce')) {
    wp_die('Security check failed');
}
```

### Input Sanitization
```php
// Sanitize all inputs
$product_id = intval($_POST['product_id']);
$text_input = sanitize_text_field($_POST['text_input']);
$email = sanitize_email($_POST['email']);
$url = esc_url_raw($_POST['url']);
```

### Output Escaping
```php
// Escape all outputs
echo esc_html($user_input);
echo esc_attr($attribute_value);
echo esc_url($url_value);
```

---

## 📱 Mobile API Considerations

### Touch Events
```javascript
// Handle touch events for mobile
element.addEventListener('touchstart', function(e) {
    // Touch start logic
});

element.addEventListener('touchend', function(e) {
    // Touch end logic
});
```

### Viewport Management
```javascript
// Check if mobile viewport
function isMobile() {
    return window.innerWidth < 768;
}

// Adjust behavior for mobile
if (isMobile()) {
    // Mobile-specific code
}
```

---

**API Version**: 2.0  
**Compatibility**: WordPress 6.0+, WooCommerce 8.0+  
**Last Updated**: December 2024
