# TostiShop Theme Development Guide

## Quick Start

### 1. Run Setup Script
**Windows (PowerShell):**
```powershell
./setup.ps1
```

**Linux/Mac:**
```bash
chmod +x setup.sh
./setup.sh
```

### 2. Start Development
```bash
npm run dev
```

### 3. Activate Theme
1. Go to WordPress Admin → Appearance → Themes
2. Activate "TostiShop" theme
3. Install WooCommerce plugin if not already installed

## Development Workflow

### CSS Development
- Edit files in `assets/css/main.css`
- Use Tailwind utility classes in PHP templates
- Run `npm run dev` to watch for changes
- CSS is compiled to `style.css`

### JavaScript Development
- Edit `assets/js/ui.js` for custom interactions
- Use Alpine.js for reactive components
- No build step needed for JavaScript

### PHP Templates
- Follow WordPress template hierarchy
- Use WooCommerce hooks and filters
- Customize in `woocommerce/` directory for WooCommerce templates

## Common Customizations

### Adding New Product Features
1. Edit `woocommerce/content-product.php`
2. Add new HTML structure
3. Style with Tailwind classes
4. Add interactions in `ui.js` if needed

### Customizing Colors
1. Edit `tailwind.config.js`
2. Update color palette in `theme.extend.colors`
3. Rebuild CSS: `npm run build`

### Adding Custom Pages
1. Create new PHP file (e.g., `page-custom.php`)
2. Use existing header/footer
3. Follow WordPress coding standards

### Mobile Menu Customization
1. Edit mobile menu section in `header.php`
2. Customize Alpine.js data in `x-data` attribute
3. Style with responsive Tailwind classes

## File Structure Reference

```
tostishop/
├── 📄 style.css              # Compiled CSS (auto-generated)
├── 📄 functions.php          # Theme functionality
├── 📄 header.php             # Site header
├── 📄 footer.php             # Site footer
├── 📄 index.php              # Homepage
├── 📄 page.php               # Single pages
├── 📁 assets/
│   ├── 📁 css/
│   │   └── 📄 main.css       # Tailwind source
│   └── 📁 js/
│       └── 📄 ui.js          # Custom JavaScript
├── 📁 woocommerce/           # WooCommerce templates
│   ├── 📄 archive-product.php
│   ├── 📄 single-product.php
│   ├── 📄 content-product.php
│   └── 📁 cart/
└── 📄 tailwind.config.js     # Tailwind configuration
```

## Testing Checklist

### Mobile Testing
- [ ] Test on actual mobile devices
- [ ] Check touch interactions
- [ ] Verify responsive design
- [ ] Test mobile menu functionality

### WooCommerce Testing
- [ ] Product grid display
- [ ] Single product page
- [ ] Add to cart functionality
- [ ] Cart page layout
- [ ] Checkout process

### Performance Testing
- [ ] Run Lighthouse audit
- [ ] Check Core Web Vitals
- [ ] Test image loading
- [ ] Verify CSS/JS minification

## Troubleshooting

### CSS Not Updating
```bash
# Clear and rebuild
rm style.css
npm run build
```

### JavaScript Errors
1. Check browser console
2. Verify Alpine.js is loaded
3. Check for syntax errors in `ui.js`

### WooCommerce Issues
1. Check WooCommerce → Status
2. Test with default theme
3. Clear WooCommerce cache

### Permission Issues (Linux/Mac)
```bash
# Fix file permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
```

## Useful Commands

```bash
# Development
npm run dev           # Watch mode
npm run build-dev     # Build for development
npm run build         # Build for production

# WordPress CLI (if available)
wp theme activate tostishop
wp plugin install woocommerce --activate
wp wc tool run regenerate_product_lookup_tables

# Git commands
git add .
git commit -m "Update theme"
git push origin main
```

## Resources

- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)
- [WooCommerce Template Structure](https://docs.woocommerce.com/document/template-structure/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Alpine.js Documentation](https://alpinejs.dev/)

## Need Help?

1. Check the browser console for errors
2. Review WordPress debug.log
3. Test with a default theme
4. Check WooCommerce status page
5. Refer to `COPILOT-INSTRUCTIONS.md` for detailed guidance

---

Happy coding! 🚀
