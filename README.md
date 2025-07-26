# TostiShop Theme (Mobile-First WooCommerce Theme)

A blazing-fast, fully responsive WooCommerce theme designed for mobile-first eCommerce experiences with modern features and optimal performance.

---

## ✅ Core Features

* ⚡ **Ultra-lightweight** - Optimized Tailwind CSS build
* 📱 **Mobile-first design** - Perfect on all devices  
* 🛒 **Full WooCommerce integration** - Cart, checkout, account pages
* 🏠 **Custom homepage template** - 9 sections with dynamic content
* 🎨 **Product variations** - Color swatches & smart selection
* 🗭 **Off-canvas navigation** - Smooth mobile menu
* 🧷 **Sticky add-to-cart** - Enhanced mobile experience
* 🔔 **Smart notifications** - AJAX cart updates
* 📊 **SEO optimized** - Structured data & performance
* 🎯 **Conversion optimized** - Streamlined checkout flow

---

## 🎮 Brand Colors

These are the primary colors extracted from the TostiShop logo:

| Color Name         | Hex Code  | Preview   |
| ------------------ | --------- | --------- |
| Deep Navy Blue     | `#14175b` | `#14175b` |
| Bright Red         | `#e42029` | `#e42029` |
| Light Silver White | `#ecebee` | `#ecebee` |

Use these in your Tailwind config and throughout your UI for consistent branding.

---

## 📁 Project Structure

```
tostishop-theme/
├── style.css                  # Compiled Tailwind CSS + theme info
├── functions.php              # Theme setup, WooCommerce integration
├── header.php                 # Responsive header with navigation
├── footer.php                 # Brand footer with widgets
├── index.php                  # Blog template
├── page.php                   # Default page template
├── page-home.php              # Custom homepage template (9 sections)
├── tailwind.config.js         # Tailwind configuration with brand colors
├── package.json               # Build tools and dependencies
├── .github/                   # Documentation and guides
│   ├── HOMEPAGE-IMPLEMENTATION.md
│   ├── DEVELOPMENT.md
│   ├── COPILOT-INSTRUCTIONS.md
│   └── [feature-specific guides]
├── assets/
│   ├── css/
│   │   ├── main.css           # Tailwind source
│   │   ├── custom.css         # Custom styles and fixes
│   │   └── homepage.css       # Homepage-specific styles
│   ├── js/
│   │   ├── ui.js              # Core interactions
│   │   ├── theme.js           # Product variations & cart
│   │   ├── homepage.js        # Homepage functionality
│   │   └── notifications.js   # Toast notifications
│   └── images/
│       ├── logo.png           # Auto-setup brand logo
│       └── logo-big.png       # High-res version
└── woocommerce/               # WooCommerce template overrides
    ├── archive-product.php    # Shop pages with grid/list view
    ├── single-product.php     # Product pages with variations
    ├── content-product.php    # Product cards
    ├── cart/cart.php          # Optimized cart page
    ├── checkout/              # Streamlined checkout flow
    ├── myaccount/             # Enhanced account pages
    └── order/                 # Order details with images
```

---

## 🚀 Latest Features & Improvements

### ✅ Custom Homepage Template
- **9 comprehensive sections**: Hero, Categories, Featured Products, Deals, Services, Testimonials, Blog, Newsletter, CTA
- **Real product integration**: Featured products, categories, and deals
- **Mobile-optimized layouts**: Responsive grids and touch-friendly interactions
- **SEO structured data**: JSON-LD for better search visibility

### ✅ Enhanced Product Experience  
- **Smart product variations**: Color swatches for colors, dropdowns for other attributes
- **Image switching**: Product images change with variation selection
- **Related products slider**: Amazon-style with 3.2 products visible on mobile
- **AJAX notifications**: Instant feedback for cart actions

### ✅ Streamlined Cart & Checkout
- **Conversion optimized**: Minimal header/footer on cart/checkout pages
- **Wide desktop layouts**: Better space utilization on large screens
- **Mobile-first forms**: Touch-friendly inputs and validation
- **Trust signals**: SSL badges and security indicators

### ✅ Enhanced Account Pages
- **Desktop improvements**: Better navigation, hover effects, wider layouts
- **Order management**: Product images in order history, enhanced details
- **Address management**: Visual status indicators and clear forms
- **Dashboard enhancements**: Quick stats and action cards

---

## 🧰 Technology Stack

## 🧰 Technology Stack

* **[Tailwind CSS](https://tailwindcss.com/)** - Utility-first CSS framework
* **[Alpine.js](https://alpinejs.dev/)** - Lightweight JavaScript framework  
* **WooCommerce Core** - Full eCommerce integration
* **WordPress Hooks** - Proper theme integration
* **Custom CSS Grid** - Advanced layouts
* **AJAX Integration** - Smooth user interactions
* **JSON-LD Schema** - SEO structured data
* **Mobile-First Approach** - Progressive enhancement

---

## 🚀 Quick Start Guide

### 1. **Setup Theme**
```bash
# Navigate to theme directory
cd wp-content/themes/tostishop

# Install dependencies  
npm install

# Build CSS for development
npm run dev
```

### 2. **Activate Theme**
1. Go to **WordPress Admin → Appearance → Themes**
2. Activate **"TostiShop"** theme
3. Install **WooCommerce** plugin if not installed

### 3. **Setup Homepage**
1. **WordPress Admin → Pages → Add New**
2. Title: **"Home"** 
3. Template: **"TostiShop Homepage"**
4. **Settings → Reading → Static Page → Homepage**

### 4. **Configure WooCommerce**
1. **WooCommerce → Settings → Products**
2. Set featured products for homepage
3. **Categories**: Add images to product categories
4. **Checkout**: Configure payment methods

---

## 📱 Mobile-First Features

Use native WordPress **Block Editor** only (no page builders):

### ✅ Suggested Homepage Layout:

1. **Hero Banner Block**

   * Background color/image with CTA button
   * Highlight a collection or offer

2. **Product Categories Block**

   * Use WooCommerce Product Categories Block
   * Display with icons or thumbnails

3. **Featured Products Block**

   * Native WooCommerce block
   * Show 4-8 products (new arrivals, trending, etc.)

4. **Single Highlight Block**

   * Image + text for USPs (Made in India, Fast Delivery)

5. **Customer Testimonials Block**

   * Use core "Testimonials" block or custom block

6. **CTA Signup Block**

   * Email/phone capture form
   * Styled with Tailwind classes

7. **Blog or Tips Section (Optional)**

   * Latest articles or videos

### 📱 Mobile First Tips

* Use vertical stacking of blocks
* Avoid complex columns
* Use full-width images
* Keep text brief and tappable

---

## 🔧 Navigation Menu Strategy

### ✅ Single Menu, Adaptive Layout

Use **one WordPress menu** (e.g. `primary_menu`) and conditionally render it differently on mobile vs desktop:

* ✅ Single source of truth
* ✅ Simplified management
* ✅ Consistent UX
* ✅ SEO and accessibility friendly

### 📱 Mobile View

* Use **off-canvas menu** with Alpine.js or Vanilla JS
* Tailwind classes:

```html
<div class="fixed inset-0 bg-black bg-opacity-50 z-40" x-show="isOpen"></div>
<nav class="fixed left-0 top-0 h-full w-64 bg-white z-50 transform transition-transform" x-bind:class="{ '-translate-x-full': !isOpen }">
```

### 🖥️ Desktop View

* Horizontal nav with Tailwind:

```html
<ul class="hidden md:flex space-x-4">
  <li><a href="#" class="text-primary hover:text-accent">Shop</a></li>
</ul>
```

### 💡 PHP Integration Example

```php
wp_nav_menu([
  'theme_location' => 'primary',
  'menu_class' => 'hidden md:flex space-x-6 text-sm font-medium',
  'container' => false,
]);
```

Also render for mobile:

```php
wp_nav_menu([
  'theme_location' => 'primary',
  'menu_class' => 'block md:hidden space-y-4 text-lg p-4',
  'container' => false,
]);
```

### ⚠️ When to Use Separate Menus

Only use separate mobile/desktop menus if:

* You need **completely different links**
* You want to **simplify mobile UX**

In that case:

```php
register_nav_menus([
  'desktop_menu' => 'Desktop Menu',
  'mobile_menu'  => 'Mobile Menu'
]);
```

---

## 🔧 Header/Footer on Cart & Checkout Pages

### ✅ Recommended Practice

For optimal user experience and conversions:

| Page     | Header       | Footer     | Notes                                  |
| -------- | ------------ | ---------- | -------------------------------------- |
| Home     | ✅ Full       | ✅ Full     | Full branding and nav                  |
| Cart     | ☑️ Minimal   | ☑️ Minimal | Less distraction, allow return to shop |
| Checkout | ☑️ Logo only | ❌ Hidden   | Maximize focus & trust, no exits       |

### 🧠 PHP Snippet to Conditionally Hide

In `header.php` and `footer.php`:

```php
<?php if (!is_cart() && !is_checkout()) : ?>
  <!-- Full Header -->
<?php endif; ?>
```

```php
<?php if (!is_cart() && !is_checkout()) : ?>
  <!-- Full Footer -->
<?php endif; ?>
```

---

## 🔨 Setup Instructions

### 1. Clone or Copy Theme

```bash
wp-content/themes/tostishop-theme/
```

### 2. Install Tailwind CSS

```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init
```

### 3. Configure Tailwind

Update `tailwind.config.js`:

```js
module.exports = {
  content: ["./**/*.php", "./assets/js/*.js"],
  theme: {
    extend: {
      colors: {
        primary: '#14175b', // Deep Navy Blue
        accent: '#e42029',  // Bright Red
        light: '#ecebee'    // Light Silver White
      }
    },
  },
  plugins: [],
};
```

### 4. Create Tailwind Entry CSS

`assets/css/main.css`:

```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

### 5. Build CSS

```bash
npx tailwindcss -i ./assets/css/main.css -o ./style.css --watch
```

### 6. Enqueue in `functions.php`

```php
function tostishop_scripts() {
  wp_enqueue_style('tostishop-style', get_stylesheet_uri(), [], '1.0');
}
add_action('wp_enqueue_scripts', 'tostishop_scripts');
```

---

## 🔌 WooCommerce Template Overrides

Customize templates as needed:

* `woocommerce/archive-product.php` – Product grid
* `woocommerce/single-product.php` – Sticky add to cart, image slider
* `woocommerce/cart/`, `checkout/` – Clean mobile UX

---

## 🧪 Testing

* ✅ Use [Lighthouse](https://developers.google.com/web/tools/lighthouse) for performance + mobile UX score
* ✅ Test in Chrome, Safari Mobile, Firefox
* ✅ Use [WooCommerce sample products](https://github.com/woocommerce/sample-data)

---

## 📦 Optional Enhancements (After Theme)

* Firebase phone + social login (plugin)
* Shiprocket ETA plugin
* Add to home screen (PWA)
* Order tracking
* Push notifications

---

## 🧑‍💻 Author & License

**Author**: Nomad Programmer
**Github**: [https://github.com/ProgrammerNomad/tostishop](https://github.com/ProgrammerNomad/tostishop)
**License**: MIT or GPLv2 (as per WP standards)

---

> Start small. Ship fast. Optimize later.