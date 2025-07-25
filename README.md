# 🛍️ TostiShop Theme (Mobile-First WooCommerce Theme)

A blazing-fast, fully responsive WooCommerce theme designed for mobile-first eCommerce experiences.

---

## ✅ Features

- ⚡ Ultra-lightweight and fast (Tailwind CSS-based)
- 📱 Mobile-first design
- 🛒 Fully WooCommerce-compatible
- 🧭 Off-canvas navigation & filters
- 🧷 Sticky add-to-cart CTA on product pages
- 🌙 Dark mode-ready (optional)
- 🔍 Minimal JS (Alpine.js or Vanilla JS)

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

````

---

## 🧰 Tools & Stack

- [Tailwind CSS](https://tailwindcss.com/)
- WooCommerce Core
- [Alpine.js](https://alpinejs.dev/) (optional JS)
- No jQuery dependencies
- Optional CDN: Cloudflare or BunnyCDN

---

## 🔨 Setup Instructions

### 1. Clone or Copy Theme

```bash
wp-content/themes/tostishop-theme/
````

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
    extend: {},
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
**License**: MIT or GPLv2 (as per WP standards)

---

> Start small. Ship fast. Optimize later.

```