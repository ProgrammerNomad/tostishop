# TostiShop Theme - Quick Start Guide

## 🚀 5-Minute Setup

Get your TostiShop WooCommerce store running in minutes with this streamlined setup guide.

---

## 📋 Prerequisites

### Required Software
- **WordPress**: 6.0 or higher
- **WooCommerce**: 8.0 or higher  
- **PHP**: 8.0 or higher
- **Node.js**: 16+ (for development)
- **NPM**: Latest version

### Recommended Environment
- **Memory Limit**: 256MB minimum
- **Max Execution Time**: 60 seconds
- **Upload Size**: 64MB minimum
- **SSL Certificate**: For secure checkout

---

## ⚡ Instant Installation

### Step 1: Upload Theme (2 minutes)
```bash
# Upload via WordPress Admin
1. Go to Appearance → Themes
2. Click "Add New" → "Upload Theme"
3. Select tostishop.zip file
4. Click "Install Now"
5. Click "Activate"
```

### Step 2: Install WooCommerce (1 minute)
```bash
# Auto-install via theme
1. Follow WooCommerce setup wizard
2. Configure store location and currency
3. Set up payment methods (Stripe/PayPal)
4. Configure shipping options
5. Skip Jetpack (optional)
```

### Step 3: Import Demo Content (2 minutes)
```bash
# Quick demo setup
1. Go to Appearance → Customize
2. Look for "Demo Import" option
3. Click "Import Demo Data"
4. Wait for import completion
5. Set homepage to "Home" template
```

---

## 🎨 Instant Customization

### Logo Setup (30 seconds)
```bash
1. Go to Appearance → Customize → Site Identity
2. Upload your logo (recommended: 200x60px)
3. Set site title and tagline
4. Click "Publish"
```

### Colors & Branding (30 seconds)
```bash
1. Go to Appearance → Customize → Colors
2. Primary Color: #14175b (Navy)
3. Accent Color: #e42029 (Red)
4. Background: #ecebee (Silver)
5. Click "Publish"
```

### Menu Setup (1 minute)
```bash
1. Go to Appearance → Menus
2. Create new menu "Primary Navigation"
3. Add pages: Home, Shop, About, Contact
4. Assign to "Primary Menu" location
5. Save Menu
```

---

## 🛍️ Store Configuration

### Essential WooCommerce Settings
```bash
# Payment Methods (1 minute)
WooCommerce → Settings → Payments
- Enable Stripe (recommended)
- Enable PayPal Standard
- Configure test mode first

# Shipping Setup (1 minute)  
WooCommerce → Settings → Shipping
- Add shipping zones
- Set flat rate or free shipping
- Configure local pickup (optional)

# Tax Configuration (30 seconds)
WooCommerce → Settings → Tax
- Enable tax calculation
- Set tax rates by location
- Include/exclude tax in prices
```

### Product Import (Optional)
```bash
# Sample Products
1. Download sample-products.csv
2. Go to WooCommerce → Products
3. Click "Import" → Select CSV
4. Map fields and import
5. Review imported products
```

---

## 📱 Mobile Optimization

### Automatic Features
- ✅ **Responsive Design** - Works on all devices
- ✅ **Touch Navigation** - Mobile-friendly menus
- ✅ **Fast Loading** - Optimized performance
- ✅ **Mobile Checkout** - Streamlined process
- ✅ **Sticky Cart** - Easy mobile shopping

### Testing Your Mobile Site
```bash
1. Open site on mobile device
2. Test navigation menu
3. Try adding products to cart
4. Complete a test purchase
5. Check all pages load correctly
```

---

## 🔧 Development Setup (Optional)

### For Customization
```bash
# Clone/download theme files
git clone [repository-url]
cd tostishop-theme

# Install dependencies
npm install

# Start development
npm run dev

# Build for production
npm run build
```

### CSS Customization
```css
/* Add to Appearance → Customize → Additional CSS */
.custom-style {
    /* Your custom styles here */
    color: #14175b;
    background: #ecebee;
}
```

---

## ✅ Launch Checklist

### Pre-Launch Tests (5 minutes)
- [ ] **Homepage loads correctly**
- [ ] **Shop page displays products**  
- [ ] **Cart functionality works**
- [ ] **Checkout process completes**
- [ ] **Mobile responsiveness**
- [ ] **Contact forms working**
- [ ] **SSL certificate active**

### SEO Setup (2 minutes)
```bash
1. Install Yoast SEO plugin
2. Configure site title/description
3. Set up Google Analytics
4. Submit sitemap to Google
5. Enable site search console
```

### Performance Check (1 minute)
```bash
1. Test site speed at GTmetrix
2. Optimize images if needed
3. Enable caching plugin
4. Configure CDN (optional)
5. Monitor loading times
```

---

## 🆘 Quick Troubleshooting

### Common Issues & Fixes

**Theme not activating?**
```bash
- Check WordPress version (6.0+)
- Verify PHP version (8.0+)
- Ensure WooCommerce is installed
- Check file permissions
```

**Styling looks broken?**
```bash
- Clear any caching plugins
- Regenerate CSS (if using custom CSS)
- Check for plugin conflicts
- Refresh browser cache
```

**Products not displaying?**
```bash
- Check WooCommerce settings
- Verify product visibility
- Clear permalinks (Settings → Permalinks → Save)
- Check for category assignments
```

**Cart not working?**
```bash
- Test in incognito/private browser
- Disable caching temporarily
- Check for JavaScript errors
- Verify AJAX is working
```

---

## 📞 Getting Help

### Support Resources
- **Documentation**: [Full documentation link]
- **Video Tutorials**: [YouTube playlist link]
- **Community Forum**: [Support forum link]
- **Direct Support**: [Contact email]

### Before Contacting Support
1. Check this quick start guide
2. Review the troubleshooting section
3. Test with default WordPress theme
4. Disable all plugins temporarily
5. Note any error messages

---

## 🎯 Next Steps

### After Basic Setup
1. **Add Products** - Upload your inventory
2. **Configure Shipping** - Set delivery options
3. **Payment Testing** - Test all payment methods
4. **Content Creation** - Add about/contact pages
5. **SEO Optimization** - Configure search engine settings
6. **Marketing Setup** - Email lists, social media
7. **Analytics** - Install tracking codes
8. **Backup Setup** - Configure automatic backups

### Advanced Features
- **Multi-language support** (WPML/Polylang)
- **Advanced product filters** 
- **Custom product types**
- **Subscription products**
- **Membership integration**
- **Advanced reporting**

---

**Total Setup Time**: ~10 minutes  
**Skill Level**: Beginner-friendly  
**Support Available**: Full documentation + direct support  
**Result**: Professional WooCommerce store ready for business! 🎉
