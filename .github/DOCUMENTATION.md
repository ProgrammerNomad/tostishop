# TostiShop Theme Documentation

Welcome to the comprehensive documentation for the TostiShop WordPress theme.

## 📞 Contact & Support

- **Email**: contact@tostishop.com
- **WhatsApp**: +91 79829 99145 (Chat only, no calls)
- **Support**: Email and WhatsApp chat for all inquiries

---

## 📚 Documentation Index

### Getting Started
- [Installation Guide](#installation)
- [Quick Setup](#quick-setup)
- [Configuration](#configuration)

### Development
- [API Reference](.github/API-REFERENCE.md)
- [Contributing Guidelines](.github/CONTRIBUTING.md)
- [Changelog](.github/CHANGELOG.md)

### Features
- [WooCommerce Integration](#woocommerce)
- [Mobile-First Design](#mobile-design)
- [Performance Optimization](#performance)

---

## 🚀 Installation

### Requirements
- WordPress 5.8 or higher
- WooCommerce 6.0 or higher
- PHP 7.4 or higher
- Modern web browser

### Installation Steps

1. **Download the Theme**
   - Extract the theme files
   - Upload to `/wp-content/themes/tostishop/`

2. **Activate the Theme**
   - Go to WordPress Admin → Appearance → Themes
   - Click "Activate" on TostiShop theme

3. **Install Required Plugins**
   - WooCommerce (required)
   - Contact Form 7 (recommended)

4. **Import Demo Content** (Optional)
   - Go to Tools → Import
   - Import sample products and pages

## ⚡ Quick Setup

### Logo Configuration
The theme automatically sets up your logo:

```php
// Logo is automatically configured in functions.php
add_theme_support('custom-logo', array(
    'height'      => 60,
    'width'       => 200,
    'flex-height' => true,
    'flex-width'  => true,
));
```

### Menu Setup
1. Go to Appearance → Menus
2. Create a new menu
3. Assign to "Primary Menu" location
4. Add your pages and categories

### WooCommerce Setup
1. Run WooCommerce setup wizard
2. Configure payment methods
3. Set up shipping zones
4. Add your products

## 🎨 Configuration

### Theme Customizer
Access via Appearance → Customize:

- **Site Identity**: Logo, site title, tagline
- **Colors**: Primary and accent colors
- **Typography**: Font selections
- **Layout**: Header and footer options
- **WooCommerce**: Shop page settings

### Custom Color Scheme
TostiShop uses a carefully crafted color palette:

```css
:root {
  --navy-900: #14175b;     /* Primary brand color */
  --accent: #e42029;       /* Call-to-action color */
  --silver-50: #ecebee;    /* Background highlights */
}
```

## 🛍️ WooCommerce Integration

### Product Display Features
- **Responsive product grids**: 2 columns mobile, 3-4 desktop
- **Product image galleries**: Zoom, lightbox, slider
- **Quick add to cart**: AJAX-powered for smooth experience
- **Star ratings**: Customer review integration
- **Sale badges**: Automatic percentage calculations

### Cart & Checkout
- **AJAX cart updates**: No page reloads
- **Mobile-optimized checkout**: Single-column layout
- **Payment method icons**: Visual payment options
- **Real-time validation**: Instant form feedback

### My Account Area
- **Order management**: View, track, reorder
- **Address book**: Billing and shipping addresses
- **Account details**: Profile and password management
- **Download area**: Digital product access

## 📱 Mobile-First Design

### Responsive Breakpoints
```css
/* Mobile First Approach */
.container {
  /* Mobile: 375px - 639px */
  @apply px-4;
  
  /* Small: 640px+ */
  @screen sm { @apply px-6; }
  
  /* Medium: 768px+ */
  @screen md { @apply px-8; }
  
  /* Large: 1024px+ */
  @screen lg { @apply px-8; }
  
  /* Extra Large: 1280px+ */
  @screen xl { @apply max-w-7xl mx-auto; }
}
```

### Mobile Features
- **Touch-friendly navigation**: Large tap targets
- **Off-canvas menu**: Slide-out mobile menu
- **Sticky elements**: Cart button and header
- **Swipe gestures**: Product image galleries
- **Optimized forms**: Mobile keyboard support

## 🚄 Performance Optimization

### CSS Optimization
- **Tailwind CSS**: Utility-first framework
- **PurgeCSS**: Remove unused styles
- **Critical CSS**: Above-the-fold optimization
- **CSS minification**: Reduced file sizes

### JavaScript Optimization
- **Alpine.js**: Lightweight interactivity
- **Lazy loading**: Images and components
- **Code splitting**: Modular architecture
- **Minification**: Compressed scripts

### Image Optimization
- **WebP support**: Modern image formats
- **Responsive images**: Multiple sizes
- **Lazy loading**: Performance boost
- **Compression**: Optimized file sizes

## 🔧 Development

### Build Process
```bash
# Install dependencies
npm install

# Development build (watch mode)
npm run dev

# Production build
npm run build

# Clean build files
npm run clean
```

### File Structure
```
tostishop/
├── assets/
│   ├── css/          # Tailwind source files
│   ├── js/           # Alpine.js components
│   └── images/       # Theme images
├── woocommerce/      # Template overrides
├── .github/          # Documentation
├── functions.php     # Theme functions
├── style.css         # Compiled CSS
└── *.php            # Template files
```

### Custom Development
- **Hooks & Filters**: WordPress standard
- **Child Theme Support**: Override templates
- **Custom Post Types**: Extend functionality
- **Plugin Integration**: Popular plugin support

## 🎯 SEO Optimization

### Built-in SEO Features
- **Structured Data**: JSON-LD implementation
- **Semantic HTML**: Proper markup
- **Meta Tags**: Open Graph and Twitter
- **Site Speed**: Performance optimizations
- **Mobile-Friendly**: Google Mobile-First

### Schema Markup
```php
// Automatic product schema
function tostishop_product_schema($product) {
    return array(
        '@type' => 'Product',
        'name' => $product->get_name(),
        'description' => $product->get_short_description(),
        'offers' => array(
            '@type' => 'Offer',
            'price' => $product->get_price(),
            'priceCurrency' => get_woocommerce_currency()
        )
    );
}
```

## 🔒 Security Features

### Security Implementations
- **Input Sanitization**: All user inputs
- **Output Escaping**: Prevent XSS attacks
- **Nonce Verification**: CSRF protection
- **Capability Checks**: User permissions
- **SQL Injection Prevention**: Prepared statements

### Best Practices
```php
// Always sanitize input
$user_input = sanitize_text_field($_POST['field']);

// Always escape output
echo esc_html($user_input);

// Use nonces for forms
wp_nonce_field('tostishop_action', 'tostishop_nonce');
```

## 🔌 Plugin Compatibility

### Tested Plugins
- **WooCommerce**: Full integration
- **Contact Form 7**: Styled forms
- **Yoast SEO**: Meta optimization
- **WP Rocket**: Caching support
- **WPML**: Translation ready

### Plugin Integration
```php
// Example plugin integration
if (class_exists('WooCommerce')) {
    // WooCommerce specific code
}

if (function_exists('wpcf7_add_form_tag')) {
    // Contact Form 7 integration
}
```

## 🌐 Internationalization

### Translation Support
- **Text Domain**: `tostishop`
- **Translation Files**: `/languages/`
- **RTL Support**: Right-to-left languages
- **Date/Time Formats**: Localized

### Adding Translations
```php
// Translatable strings
__('Add to Cart', 'tostishop');
_e('Product added to cart', 'tostishop');
_n('1 item', '%s items', $count, 'tostishop');
```

## 🧪 Testing

### Testing Checklist
- [ ] Cross-browser compatibility
- [ ] Mobile responsiveness
- [ ] WooCommerce functionality
- [ ] Form submissions
- [ ] Performance metrics
- [ ] SEO validation

### Testing Tools
- **Browser DevTools**: Debugging
- **PageSpeed Insights**: Performance
- **Mobile-Friendly Test**: Google tool
- **WAVE**: Accessibility testing
- **Schema Validator**: Structured data

## 📈 Analytics & Tracking

### Google Analytics Integration
```javascript
// Google Analytics 4 integration
gtag('config', 'GA_MEASUREMENT_ID', {
    'custom_map.dimension1': 'product_category',
    'custom_map.dimension2': 'user_type'
});

// Enhanced ecommerce tracking
gtag('event', 'purchase', {
    'transaction_id': order.id,
    'value': order.total,
    'currency': 'INR'
});
```

### Conversion Tracking
- **Add to Cart**: Product interactions
- **Purchase**: Order completion
- **Form Submissions**: Lead generation
- **Page Views**: Content engagement

## 🆘 Troubleshooting

### Common Issues

#### Theme Not Loading Properly
1. Check file permissions
2. Verify WordPress requirements
3. Deactivate conflicting plugins
4. Clear caching

#### WooCommerce Issues
1. Update WooCommerce plugin
2. Check theme compatibility
3. Clear WooCommerce cache
4. Test with default theme

#### Performance Issues
1. Optimize images
2. Enable caching
3. Check plugin conflicts
4. Review hosting environment

### Debug Mode
```php
// Enable WordPress debug mode
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

## 📞 Support & Assistance

### Getting Help
- **Email**: contact@tostishop.com
- **WhatsApp**: +91 79829 99145 (Chat only, no calls)
- **Response Time**: Within 24 hours
- **Support Hours**: Monday - Saturday, 9 AM - 6 PM IST

### Support Includes
- Installation assistance
- Configuration guidance
- Bug fixes and updates
- Performance optimization
- Security updates

### Premium Support
For enhanced support options:
- Priority response
- Custom development
- Advanced customization
- Training sessions

---

## 📝 Additional Resources

### Documentation Links
- [WordPress Codex](https://codex.wordpress.org/)
- [WooCommerce Docs](https://docs.woocommerce.com/)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Alpine.js](https://alpinejs.dev/)

### Community Resources
- WordPress Forums
- WooCommerce Community
- GitHub Discussions
- Stack Overflow

---

*Last updated: January 26, 2025*
