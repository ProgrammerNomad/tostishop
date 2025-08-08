# TostiShop WordPress Theme - Copilot Instructions

## Architecture Overview
TostiShop is a modular WordPress/WooCommerce theme with strict separation of concerns:

### Core Architecture
- **Modular PHP structure**: All functionality split into `/inc/` modules loaded by `functions.php`
- **Tailwind CSS + Alpine.js**: Utility-first styling with lightweight JavaScript interactivity  
- **Mobile-first responsive**: Mobile optimization prioritized throughout
- **Firebase Auth integration**: Optional phone number authentication system
- **Component-based CSS**: Organized in `/assets/css/components/` with specific files per feature

### Key Architectural Decisions
- **No jQuery dependency**: Pure JavaScript + Alpine.js for interactivity
- **Conditional Firebase loading**: Only loads if API key is configured
- **AJAX-first cart operations**: All cart interactions happen without page reloads
- **Single shipping address**: Billing address is automatically copied to shipping

## 🎨 Brand Colors (From TostiShop Logo)

The theme uses these primary colors extracted from the TostiShop logo:

| Color Name         | Hex Code  | Tailwind Class | Usage                    |
| ------------------ | --------- | -------------- | ------------------------ |
| Deep Navy Blue     | `#14175b` | `navy-900`     | Primary brand color      |
| Bright Red         | `#e42029` | `accent`       | Call-to-action buttons  |
| Light Silver White | `#ecebee` | `silver-50`    | Background highlights    |

### Extended Color Palettes
- **Navy**: 50-950 scale (primary brand color family)
- **Red**: 50-950 scale (accent/CTA color family)  
- **Silver**: 50-900 scale (neutral color family)

## 🖼️ Logo & Branding

### Logo Configuration
- **Location**: `/assets/images/logo.png`
- **Auto-setup**: Automatically set as custom logo on theme activation
- **Responsive Classes**: `h-8 md:h-10 w-auto`
- **Display Dimensions**: Optimized for 200x60px
- **Fallback**: Site name in `text-primary` color if no logo

### Logo Implementation
```php
// Automatic logo setup in functions.php
add_theme_support('custom-logo', array(
    'height'      => 60,
    'width'       => 200,
    'flex-height' => true,
    'flex-width'  => true,
));
```

## 🏗️ Project Structure

```
tostishop-theme/
├── style.css                  # Theme meta + compiled Tailwind CSS
├── functions.php              # Main functions file (modular includes)
├── header.php                 # Site header with navigation
├── footer.php                 # Site footer
├── index.php                  # Homepage template
├── page.php                   # Single page template
├── tailwind.config.js         # Tailwind configuration
├── package.json               # NPM dependencies
├── assets/
│   ├── css/
│   │   ├── main.css           # Tailwind source CSS
│   │   ├── custom.css         # Custom styles
│   │   ├── homepage.css       # Homepage specific styles
│   │   └── firebase-auth.css  # Firebase auth UI styles
│   ├── js/
│   │   ├── ui.js              # Alpine.js interactions
│   │   ├── theme.js           # General theme functionality
│   │   ├── cart.js            # Cart specific interactions
│   │   └── firebase-auth.js   # Firebase authentication
│   └── images/
│       ├── logo.png           # Main logo (200x60px)
│       └── logo-big.png       # Large logo variant
├── inc/                       # Modular theme functions
│   ├── theme-setup.php        # Core theme initialization
│   ├── assets-enqueue.php     # Scripts and styles loading
│   ├── woocommerce-customizations.php # WooCommerce modifications
│   ├── ajax-handlers.php      # AJAX endpoints and handlers
│   ├── theme-customizer.php   # WordPress Customizer settings
│   ├── helper-functions.php   # Utility functions and helpers
│   ├── theme-options.php      # Theme options panel
│   ├── tosti-admin-menu.php   # Admin menu customizations
│   └── firebase/              # Firebase authentication module
│       ├── init.php           # Firebase initialization
│       ├── config.php         # Firebase configuration
│       ├── auth-ui.php        # Authentication UI components
│       ├── ajax-handlers.php  # Firebase AJAX handlers
│       └── token-verification.php # Token verification
└── woocommerce/               # WooCommerce template overrides
    ├── archive-product.php    # Shop/category pages
    ├── single-product.php     # Product detail page
    ├── content-product.php    # Product card component
    ├── cart/
    │   └── cart.php           # Cart page template
    ├── checkout/              # Checkout templates
    │   ├── form-checkout.php  # Main checkout form
    │   ├── review-order.php   # Order review section
    │   └── thankyou.php       # Order confirmation
    └── myaccount/             # My account templates
        ├── dashboard.php      # Account dashboard
        ├── orders.php         # Order history
        └── form-login.php     # Login/register form
```

## 🔧 Essential Development Workflows

### CSS Build Process
```bash
# Development with file watching
npm run dev

# Production build (minified)
npm run build

# One-time development build
npm run build-dev
```

### Module Structure (Critical for Code Organization)
All PHP functionality is modularized in `/inc/`:
- `theme-setup.php` - WordPress core setup, logo auto-upload on activation
- `assets-enqueue.php` - Conditional script/style loading based on page context
- `woocommerce-customizations.php` - WooCommerce hooks and checkout modifications
- `ajax-handlers.php` - All AJAX endpoints with proper nonce verification
- `firebase/` - Complete Firebase phone auth system (conditionally loaded)

### Key Development Patterns
```php
// Always check for direct access
if (!defined('ABSPATH')) { exit; }

// Use development mode flag
if (TOSTISHOP_DEV_MODE) { /* dev-only code */ }

// Modular includes with existence checks
if (file_exists($firebase_init_file)) {
    require_once $firebase_init_file;
}
```

### AJAX Security Pattern
```php
function tostishop_ajax_handler() {
    if (!wp_verify_nonce($_POST['nonce'], 'tostishop_nonce')) {
        wp_die('Security check failed');
    }
    // Handler logic...
    wp_send_json_success($response);
}
```

## 🎨 Component System & Styling

### CSS Architecture
- **Tailwind source**: `assets/css/main.css` (compiled to `style.css`)
- **Component files**: `assets/css/components/` - organized by feature
- **Custom utilities**: Component layer classes like `.btn-primary`, `.card`, `.input`

### Brand Colors (Logo-derived)
```javascript
// tailwind.config.js extended colors
navy: { 900: '#14175b' },    // Primary brand
red: { 600: '#e42029' },     // Accent/CTA  
silver: { 50: '#ecebee' }    // Light backgrounds
```

### Alpine.js Interaction Patterns
```javascript
// Mobile menu (common pattern)
x-data="{ mobileMenuOpen: false }"

// Product gallery
x-data="{ currentImage: 0 }"

// Form states
x-data="{ loading: false, errors: {} }"
```

### WooCommerce Integration Specifics
- **Product cards**: `woocommerce/content-product.php` - uses `group` hover states
- **Checkout flow**: Single-address system (billing auto-copies to shipping)
- **Cart operations**: All AJAX-driven via `inc/ajax-handlers.php`
- **Mobile optimizations**: Sticky add-to-cart, accordion footers

## � Modular Code Organization

TostiShop uses a modular approach for better code maintainability and organization:

### Core Module Files (`/inc/` directory)

#### **theme-setup.php**
- Theme initialization and WordPress setup
- Custom logo handling and automatic upload
- Navigation menus registration
- Image sizes and theme supports
- Widget areas configuration

#### **assets-enqueue.php**
- Centralized script and style loading
- Page-specific asset management
- Firebase authentication scripts
- Development/production mode handling
- Resources (Alpine.js, fonts)

#### **woocommerce-customizations.php**
- WooCommerce checkout modifications
- Product display customizations
- Cart functionality enhancements
- Order process customizations
- Mobile-optimized checkout flow

#### **ajax-handlers.php**
- AJAX endpoint management
- Cart operations (add, remove, update)
- Newsletter signup handling
- Security nonce verification
- JSON response formatting

#### **theme-customizer.php**
- WordPress Customizer integration
- Hero section settings
- Brand color controls
- Typography options
- Theme-specific customization panels

#### **helper-functions.php**
- Utility functions library
- Product query helpers
- Formatting functions
- WooCommerce integration helpers
- Development and debugging utilities

### Firebase Authentication Module (`/inc/firebase/`)
- Complete Firebase Phone Auth integration
- OTP verification system
- User registration and profile management
- Token verification and security
- Mobile-optimized auth UI

### Usage Guidelines

#### Adding New Functionality
1. **Theme Setup**: Add to `theme-setup.php`
2. **Scripts/Styles**: Add to `assets-enqueue.php`
3. **WooCommerce**: Add to `woocommerce-customizations.php`
4. **AJAX/API**: Add to `ajax-handlers.php`
5. **Settings**: Add to `theme-customizer.php`
6. **Utilities**: Add to `helper-functions.php`

#### Module Dependencies
- All modules are included in `functions.php`
- Each module is self-contained but can use WordPress/WooCommerce functions
- Firebase module is conditionally loaded if present
- Admin modules are conditionally loaded if present

#### Best Practices
- Keep related functions together in appropriate modules
- Use descriptive function names with `tostishop_` prefix
- Document complex functions with DocBlocks
- Add appropriate hooks and filters for extensibility
- Test functionality after adding to modules

## 📱 Mobile-First Optimizations

### Development Approach
- **Breakpoints**: Follow Tailwind defaults (sm:640px, md:768px, lg:1024px, xl:1280px)
- **Touch interactions**: Proper target sizes, swipe gestures
- **Performance**: Lazy loading, minimal JavaScript, optimized images
- **Navigation**: Off-canvas menu, sticky headers, accordion patterns

### Key Mobile Features
```php
// Sticky elements (common pattern)
class="sticky top-0 z-50 bg-white/95 backdrop-blur"

// Touch-friendly sizing
class="min-h-[44px] px-4 py-3"

// Mobile-specific visibility
class="block md:hidden" // Mobile only
class="hidden md:block" // Desktop only
```

## 🛍️ WooCommerce Integration Patterns

### Checkout Architecture
- **Single address system**: Billing automatically copies to shipping via `woocommerce-customizations.php`
- **Checkout removal**: `add_filter('woocommerce_cart_needs_shipping_address', '__return_false')`
- **Auto-copy logic**: `add_action('woocommerce_checkout_process', 'tostishop_auto_copy_billing_to_shipping')`

### Product Display Patterns
```php
// Product card structure (content-product.php)
<div class="product-item group bg-white rounded-lg shadow-sm hover:shadow-md">
    <div class="product-image aspect-square bg-gray-100">
        // Image with hover effects
    </div>
    <div class="product-info p-4">
        // Title, price, rating, add-to-cart
    </div>
</div>
```

### AJAX Cart Operations
```javascript
// Add to cart pattern (ui.js)
fetch(ajax_url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({
        action: 'tostishop_add_to_cart',
        product_id: productId,
        quantity: quantity,
        nonce: tostishop_ajax.nonce
    })
})
```

### Template Override Strategy
- **Archive/Shop**: `woocommerce/archive-product.php`
- **Single Product**: `woocommerce/single-product.php`
- **Cart/Checkout**: Individual form components in `woocommerce/checkout/`
- **My Account**: Customized dashboard and forms in `woocommerce/myaccount/`

## 🔄 Interactive Features & JavaScript Architecture

### Alpine.js Component Patterns
```javascript
// Mobile menu toggle
x-data="{ mobileMenuOpen: false }"

// Product gallery
x-data="{ currentImage: 0 }"

// Product tabs
x-data="{ activeTab: 'description' }"

// Form states with loading
x-data="{ loading: false, errors: {} }"
```

### AJAX Functionality Structure
```javascript
// Main theme functionality (ui.js)
document.addEventListener('DOMContentLoaded', function() {
    initializeTheme();        // Basic theme features
    initializeWooCommerce();  // Cart operations
});

// Security pattern for all AJAX calls
body: new URLSearchParams({
    action: 'tostishop_action_name',
    nonce: tostishop_ajax.nonce,
    // ... other data
})
```

### JavaScript File Organization
- `assets/js/ui.js` - Main theme interactions and AJAX handlers
- `assets/js/cart.js` - Cart-specific functionality
- `assets/js/firebase-auth.js` - Authentication handling
- `assets/js/theme.js` - General utilities and helpers
- `assets/js/homepage.js` - Homepage-specific features

## 📝 Common Development Tasks

## 📝 Common Development Tasks

### Adding New Product Card Features
1. Edit `woocommerce/content-product.php`
2. Add styling in `assets/css/main.css`
3. Add interactions in `assets/js/ui.js`
4. Add utility functions to `inc/helper-functions.php`

### Customizing Header/Footer
1. Edit `header.php` or `footer.php`
2. Update navigation menus in WordPress admin
3. Add any new scripts to `inc/assets-enqueue.php`
4. Rebuild CSS: `npm run dev`

### Adding Custom Pages
1. Create new PHP template file
2. Follow WordPress template hierarchy
3. Use existing components and styling
4. Add page-specific assets in `inc/assets-enqueue.php`

### Modifying WooCommerce Templates
1. Copy template from WooCommerce plugin
2. Place in `woocommerce/` directory
3. Customize with Tailwind classes
4. Add related functionality to `inc/woocommerce-customizations.php`

### Adding New AJAX Functionality
1. Add AJAX handler to `inc/ajax-handlers.php`
2. Add frontend JavaScript to appropriate file in `assets/js/`
3. Ensure proper nonce verification
4. Test functionality thoroughly

### Adding Theme Options
1. Add customizer controls to `inc/theme-customizer.php`
2. Implement settings usage in templates
3. Add any related CSS/JS to appropriate asset files
4. Test in WordPress Customizer

## 📁 Modular Code Organization

TostiShop uses a modular approach for better code maintainability and organization:

### Core Module Files (`/inc/` directory)

#### **theme-setup.php**
- Theme initialization and WordPress setup
- Custom logo handling and automatic upload
- Navigation menus registration
- Image sizes and theme supports
- Widget areas configuration

#### **assets-enqueue.php**
- Centralized script and style loading
- Page-specific asset management
- Firebase authentication scripts
- Development/production mode handling
- Resources (Alpine.js, fonts)

#### **woocommerce-customizations.php**
- WooCommerce checkout modifications
- Product display customizations
- Cart functionality enhancements
- Order process customizations
- Mobile-optimized checkout flow

#### **ajax-handlers.php**
- AJAX endpoint management
- Cart operations (add, remove, update)
- Newsletter signup handling
- Security nonce verification
- JSON response formatting

#### **theme-customizer.php**
- WordPress Customizer integration
- Hero section settings
- Brand color controls
- Typography options
- Theme-specific customization panels

#### **helper-functions.php**
- Utility functions library
- Product query helpers
- Formatting functions
- WooCommerce integration helpers
- Development and debugging utilities

### Firebase Authentication Module (`/inc/firebase/`)
- Complete Firebase Phone Auth integration
- OTP verification system
- User registration and profile management
- Token verification and security
- Mobile-optimized auth UI

### Usage Guidelines

#### Adding New Functionality
1. **Theme Setup**: Add to `theme-setup.php`
2. **Scripts/Styles**: Add to `assets-enqueue.php`
3. **WooCommerce**: Add to `woocommerce-customizations.php`
4. **AJAX/API**: Add to `ajax-handlers.php`
5. **Settings**: Add to `theme-customizer.php`
6. **Utilities**: Add to `helper-functions.php`

#### Module Dependencies
- All modules are included in `functions.php`
- Each module is self-contained but can use WordPress/WooCommerce functions
- Firebase module is conditionally loaded if present
- Admin modules are conditionally loaded if present

#### Best Practices
- Keep related functions together in appropriate modules
- Use descriptive function names with `tostishop_` prefix
- Document complex functions with DocBlocks
- Add appropriate hooks and filters for extensibility
- Test functionality after adding to modules


## 🎯 Key Files to Know

### `functions.php`
- **Purpose**: Main entry point that includes all modules
- **Structure**: Clean, organized includes of modular files
- **Best Practice**: Add new functionality to appropriate module files, not directly here

### `inc/theme-setup.php`
- **Purpose**: Core theme initialization and WordPress setup
- **Key Functions**: `tostishop_setup()`, `tostishop_upload_logo()`, `tostishop_widgets_init()`
- **Modifications**: Theme supports, menus, image sizes, logo handling

### `inc/assets-enqueue.php`
- **Purpose**: Centralized asset management
- **Key Functions**: `tostishop_scripts()`, page-specific loading logic
- **Modifications**: Add new CSS/JS files, modify loading conditions

### `inc/woocommerce-customizations.php`
- **Purpose**: All WooCommerce-related customizations
- **Key Functions**: Checkout modifications, product display, cart enhancements
- **Modifications**: WooCommerce hooks, filters, and custom functionality

### `inc/ajax-handlers.php`
- **Purpose**: AJAX endpoint management
- **Key Functions**: Cart operations, newsletter signup, security verification
- **Modifications**: Add new AJAX endpoints, modify existing handlers

### `header.php`
- Site navigation
- Mobile menu
- Search functionality
- Cart icon with count

### `assets/js/ui.js`
- AJAX add to cart
- Mobile interactions
- Product gallery
- Search functionality

### `tailwind.config.js`
- Custom colors and spacing
- Component definitions
- Plugin configurations

## 🔍 Debugging Tips

### CSS Not Updating
```bash
# Rebuild Tailwind CSS
npm run build-dev
```

### JavaScript Errors
- Check browser console
- Verify Alpine.js is loaded
- Check AJAX endpoints

### WooCommerce Issues
- Check WooCommerce status page
- Verify template overrides
- Test with default theme

## 📚 Useful WordPress/WooCommerce Functions

### Theme Functions
```php
get_header()              // Include header
get_footer()              // Include footer
the_content()             // Display post content
have_posts()              // Check for posts
the_post()                // Setup post data
```

### WooCommerce Functions
```php
WC()->cart->get_cart()           // Get cart items
wc_get_cart_url()                // Cart page URL
wc_get_checkout_url()            // Checkout page URL
$product->get_price_html()       // Formatted price
$product->is_in_stock()          // Stock status
```

### Utility Functions
```php
esc_url()                 // Escape URLs
esc_html()                // Escape HTML
esc_attr()                // Escape attributes
wp_nonce_field()          // Security nonces
home_url()                // Site home URL
```

## 🚀 Performance Tips

### Image Optimization
- Use WebP format when possible
- Implement lazy loading
- Optimize image sizes

### CSS Optimization
- Use Tailwind's purge feature
- Minimize custom CSS
- Use utility classes

### JavaScript Optimization
- Load scripts in footer
- Use Alpine.js for interactivity
- Minimize jQuery usage

## 🔐 Security Best Practices

### Input Validation
```php
// Always sanitize input
$input = sanitize_text_field($_POST['input']);

// Escape output
echo esc_html($user_input);

// Verify nonces
wp_verify_nonce($_POST['nonce'], 'action_name');
```

### AJAX Security
```php
// Check nonce in AJAX handlers
if (!wp_verify_nonce($_POST['nonce'], 'tostishop_nonce')) {
    wp_die('Security check failed');
}
```

## 📞 Support Resources

- [WordPress Codex](https://codex.wordpress.org/)
- [WooCommerce Docs](https://docs.woocommerce.com/)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)
- [Alpine.js Docs](https://alpinejs.dev/)

---

*Remember: Always test changes on a staging site first!*
