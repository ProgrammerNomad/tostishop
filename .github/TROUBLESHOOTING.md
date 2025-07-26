# TostiShop Theme - Troubleshooting Guide

## 🔧 Common Issues & Solutions

Comprehensive troubleshooting guide for TostiShop WordPress theme issues and quick resolution steps.

---

## 🚨 Critical Issues

### Theme Won't Activate
**Symptoms**: Error message when trying to activate theme
```bash
✅ SOLUTIONS:
1. Check WordPress version (6.0+ required)
2. Verify PHP version (8.0+ required)
3. Ensure WooCommerce is installed
4. Check file permissions (755 for folders, 644 for files)
5. Look for syntax errors in functions.php
6. Upload theme files again

💡 Quick Test:
- Activate default WordPress theme first
- If that works, theme files may be corrupted
```

### White Screen of Death (WSOD)
**Symptoms**: Blank white page, no content
```bash
✅ SOLUTIONS:
1. Enable WordPress debug mode
   - Add to wp-config.php: define('WP_DEBUG', true);
2. Check error logs in cPanel or hosting panel
3. Deactivate all plugins
4. Switch to default theme temporarily
5. Increase PHP memory limit (256MB+)
6. Check for PHP fatal errors

💡 Emergency Fix:
- Access via FTP and rename current theme folder
- WordPress will revert to default theme
```

---

## 🛍️ WooCommerce Issues

### Products Not Displaying
**Symptoms**: Shop page empty or products missing
```bash
✅ SOLUTIONS:
1. Check product visibility settings
   - WooCommerce → Products → Edit product
   - Set "Catalog visibility" to "Shop and search"

2. Verify category assignments
   - Ensure products are assigned to categories
   - Check category display settings

3. Refresh permalinks
   - Go to Settings → Permalinks
   - Click "Save Changes" (don't change anything)

4. Check WooCommerce pages
   - WooCommerce → Settings → Advanced → Page setup
   - Ensure Shop page is set correctly
```

### Cart Not Working
**Symptoms**: Items don't add to cart or cart page errors
```bash
✅ SOLUTIONS:
1. Clear browser cache and cookies
2. Test in incognito/private browser window
3. Disable caching plugins temporarily
4. Check for JavaScript errors in browser console
5. Verify AJAX is working properly
6. Test with default WooCommerce theme

💡 JavaScript Test:
- Open browser console (F12)
- Look for red errors when adding to cart
- Common issue: jQuery conflicts with other plugins
```

### Checkout Problems
**Symptoms**: Checkout page errors or payment failures
```bash
✅ SOLUTIONS:
1. Test payment methods in sandbox/test mode
2. Check SSL certificate is active and valid
3. Verify payment gateway settings
4. Clear any checkout field customizations
5. Test with guest checkout enabled
6. Check for plugin conflicts

💡 Payment Gateway Test:
- Enable test/sandbox mode
- Use test credit card numbers
- Check gateway documentation for test credentials
```

---

## 🎨 Styling Issues

### CSS Not Loading
**Symptoms**: Site looks unstyled or broken layout
```bash
✅ SOLUTIONS:
1. Clear all caching (plugins, browser, CDN)
2. Check if CSS files exist in theme folder
3. Regenerate CSS
   - Run: npm run build (if developing)
4. Check for file permission issues
5. Verify theme is properly activated
6. Look for CSS conflicts in browser inspector

💡 Quick CSS Test:
- View page source, look for style.css link
- Click the CSS link - should load stylesheet
- If 404 error, file path issue
```

### Mobile Layout Broken
**Symptoms**: Site doesn't display correctly on mobile
```bash
✅ SOLUTIONS:
1. Check viewport meta tag in header.php
2. Clear mobile caching
3. Test with browser dev tools mobile view
4. Check for CSS overrides
5. Verify responsive images are working
6. Test with actual mobile devices

💡 Mobile Debug:
- Use Chrome DevTools mobile simulation
- Check console for mobile-specific errors
- Verify touch interactions work properly
```

### Color Scheme Issues
**Symptoms**: Wrong colors or theme colors not applying
```bash
✅ SOLUTIONS:
1. Regenerate CSS with correct color variables
2. Check Tailwind configuration
3. Clear any custom CSS conflicts
4. Verify brand colors in tailwind.config.js
5. Check for CSS specificity issues
6. Rebuild theme styles

💡 Color Variables Check:
- Verify tailwind.config.js has correct colors
- Check if custom CSS is overriding theme colors
- Use browser inspector to trace color sources
```

---

## ⚡ Performance Issues

### Slow Loading Times
**Symptoms**: Pages take long time to load
```bash
✅ SOLUTIONS:
1. Enable caching plugin (WP Rocket, W3 Total Cache)
2. Optimize images (compress, WebP format)
3. Minimize plugins (deactivate unused ones)
4. Use CDN service (Cloudflare, MaxCDN)
5. Check hosting performance
6. Optimize database

💡 Performance Test:
- Use GTmetrix or Google PageSpeed Insights
- Check Time to First Byte (TTFB)
- Identify largest content elements
```

### Database Errors
**Symptoms**: Database connection errors or slow queries
```bash
✅ SOLUTIONS:
1. Check database credentials in wp-config.php
2. Contact hosting provider for database issues
3. Optimize database with plugin (WP-Optimize)
4. Check for corrupt database tables
5. Increase database connection limits
6. Monitor database error logs

💡 Database Test:
- Use phpMyAdmin to test direct database access
- Check WooCommerce database tables exist
- Look for database repair options in hosting panel
```

---

## 🔌 Plugin Conflicts

### Plugin Compatibility Issues
**Symptoms**: Features break after installing plugins
```bash
✅ SOLUTIONS:
1. Deactivate all plugins
2. Activate plugins one by one to identify conflict
3. Check for JavaScript/CSS conflicts
4. Update all plugins to latest versions
5. Contact plugin authors for compatibility
6. Use alternative plugins if necessary

💡 Conflict Detection:
- Deactivate all plugins → Test functionality
- Activate half the plugins → Test again
- Narrow down to specific conflicting plugin
```

### AJAX Conflicts
**Symptoms**: Dynamic features stop working
```bash
✅ SOLUTIONS:
1. Check for jQuery version conflicts
2. Look for JavaScript errors in console
3. Verify AJAX endpoints are correct
4. Check nonce verification
5. Test with default WordPress theme
6. Review plugin AJAX implementations

💡 AJAX Debug:
- Check Network tab in browser dev tools
- Look for failed AJAX requests
- Verify response format and status codes
```

---

## 📱 Mobile-Specific Issues

### Touch Navigation Problems
**Symptoms**: Menu or buttons don't work on mobile
```bash
✅ SOLUTIONS:
1. Check for hover-only CSS interactions
2. Verify touch event handlers
3. Test with different mobile browsers
4. Check for oversized touch targets
5. Verify Alpine.js is loading correctly
6. Test on actual devices, not just emulators

💡 Touch Test:
- Use browser dev tools touch simulation
- Check for CSS :hover states that need :active
- Verify button sizes meet accessibility guidelines (44px+)
```

### Mobile Menu Issues
**Symptoms**: Off-canvas menu not working
```bash
✅ SOLUTIONS:
1. Check Alpine.js functionality
2. Verify mobile menu toggle JavaScript
3. Check for CSS z-index conflicts
4. Test menu on different screen sizes
5. Verify touch events are properly bound
6. Check for viewport meta tag issues

💡 Menu Debug:
- Inspect mobile menu HTML structure
- Check Alpine.js data attributes
- Verify CSS transforms and transitions
```

---

## 🔍 Debugging Tools

### WordPress Debug Mode
```php
// Add to wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true);
```

### Browser Developer Tools
```bash
✅ Essential Tabs:
- Console: JavaScript errors and warnings
- Network: Failed requests and slow loading
- Elements: HTML/CSS inspection
- Application: Local storage and cookies
- Lighthouse: Performance and accessibility audit
```

### Useful Plugins for Debugging
```bash
✅ Recommended Debug Plugins:
- Query Monitor: Database and PHP debugging
- Debug Bar: WordPress-specific debugging
- Health Check: Plugin/theme conflict detection
- WP Debug: Enhanced error reporting
```

---

## 📞 Getting Additional Help

### Before Contacting Support
```bash
✅ Information to Gather:
1. WordPress version
2. WooCommerce version
3. PHP version
4. Active plugins list
5. Error messages (exact text)
6. Steps to reproduce issue
7. Browser and device information
8. Screenshots of the problem
```

### Support Channels
- **Documentation**: Check all .github/*.md files
- **Theme Support**: Contact theme developer
- **WordPress Forums**: WordPress.org support
- **WooCommerce Support**: WooCommerce.com
- **Hosting Support**: Contact your hosting provider

### Temporary Workarounds
```bash
✅ Quick Fixes:
1. Switch to default theme temporarily
2. Deactivate problematic plugins
3. Use child theme for customizations
4. Enable maintenance mode during fixes
5. Keep backups of working versions
```

---

## 🛠️ Maintenance Prevention

### Regular Maintenance Tasks
```bash
✅ Weekly:
- Update WordPress core, themes, plugins
- Check for broken links
- Monitor site performance
- Review security scans

✅ Monthly:
- Database optimization
- Backup verification
- Performance audit
- Security review
```

### Best Practices
- Always use child themes for customizations
- Test updates on staging site first
- Keep backups before major changes
- Monitor site regularly
- Document any customizations made

---

**Remember**: Most issues can be resolved with methodical troubleshooting. Start with the simplest solutions first! 🔧
