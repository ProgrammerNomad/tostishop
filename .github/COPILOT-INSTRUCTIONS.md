# TostiShop WordPress Theme - Copilot Instructions

## Theme Overview
TostiShop is a blazing-fast, mobile-first WooCommerce theme built with:
- **Tailwind CSS** for utility-first styling 
- **Alpine.js** for lightweight interactivity
- **WordPress/WooCommerce** best practices
- **Mobile-first** responsive design approach
- **Ultra-lightweight** and fast performance
- **Off-canvas navigation** & filters
- **Sticky add-to-cart CTA** on product pages

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

## 🔧 Development Setup

### 1. Install Dependencies
```bash
npm install
```

### 2. Build CSS (Development)
```bash
npm run dev
```

### 3. Build CSS (Production)
```bash
npm run build
```

### 4. Watch for Changes
```bash
npm run dev
```

## 🎨 Design System

### Colors
- **Primary**: Blue (#3b82f6)
- **Success**: Green (#10b981)
- **Warning**: Yellow (#f59e0b)
- **Danger**: Red (#ef4444)
- **Gray Scale**: Tailwind gray palette

### Typography
- **Font**: Inter (system fallback)
- **Headings**: Bold, responsive sizes
- **Body**: Regular weight, good line height

### Components
Use Tailwind utility classes and custom components defined in `assets/css/main.css`

## 📱 Mobile-First Approach

### Breakpoints
- **sm**: 640px and up
- **md**: 768px and up  
- **lg**: 1024px and up
- **xl**: 1280px and up

### Mobile Features
- Sticky header with cart icon
- Off-canvas mobile menu
- Touch-friendly product gallery
- Sticky "Add to Cart" on mobile
- Accordion-style footer

## 🛍️ WooCommerce Integration

### Product Display
- Grid layout (2 cols mobile, 3-4 cols desktop)
- Product cards with hover effects
- Quick add to cart buttons
- Rating stars and price display

### Cart & Checkout
- Mobile-optimized cart page
- Sticky checkout button
- One-column checkout layout
- Real-time cart updates

### Product Pages
- Image gallery with thumbnails
- Sticky add to cart (mobile)
- Mobile-optimized tabs
- Quantity controls

## 🔄 Interactive Features

### Alpine.js Components
```javascript
// Mobile menu toggle
x-data="{ mobileMenuOpen: false }"

// Product gallery
x-data="{ currentImage: 0 }"

// Product tabs
x-data="{ activeTab: 'description' }"
```

### AJAX Functionality
- Add to cart without page reload
- Real-time cart count updates
- Product search suggestions
- Cart item quantity updates

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
- CDN resources (Alpine.js, fonts)

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
