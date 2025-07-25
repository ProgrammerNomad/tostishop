# TostiShop Theme (Mobile-First WooCommerce Theme)

A blazing-fast, fully responsive WooCommerce theme designed for mobile-first eCommerce experiences.

---

## ✅ Features

* ⚡ Ultra-lightweight and fast (Tailwind CSS-based)
* 📱 Mobile-first design
* 🛒 Fully WooCommerce-compatible
* 🗭 Off-canvas navigation & filters
* 🧷 Sticky add-to-cart CTA on product pages
* 🌙 Dark mode-ready (optional)
* 🔍 Minimal JS (Alpine.js or Vanilla JS)

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

## 📁 Folder Structure

```
tostishop-theme/
├── style.css                  # Theme meta + imports
├── functions.php              # Enqueues, Woo support, theme setup
├── tailwind.config.js         # Tailwind config
├── woocommerce/               # Custom Woo templates
│   ├── archive-product.php
│   ├── single-product.php
│   └── checkout/, cart/, etc.
├── templates/
│   ├── header.php
│   ├── footer.php
│   ├── page.php
│   └── index.php
├── assets/
│   ├── css/
│   │   └── main.css           # Tailwind compiled CSS
│   └── js/
│       └── ui.js              # Minimal JS (optional Alpine.js)
└── screenshot.png             # Theme preview
```

---

## 🧰 Tools & Stack

* [Tailwind CSS](https://tailwindcss.com/)
* WooCommerce Core
* [Alpine.js](https://alpinejs.dev/) (optional JS)
* No jQuery dependencies
* Optional CDN: Cloudflare or BunnyCDN

---

## 🧭 Homepage Blocks (Block Editor)

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