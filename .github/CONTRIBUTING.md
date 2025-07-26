# Contributing to TostiShop Theme

Thank you for your interest in contributing to the TostiShop WordPress theme! This document provides guidelines for contributing to the project.

## 📞 Contact & Support

Before contributing, please reach out to us:

- **Email**: contact@tostishop.com
- **WhatsApp**: +91 79829 99145 (Chat only, no calls)
- **Support**: Email and WhatsApp chat for all inquiries

## 🛠️ Development Setup

### Prerequisites
- WordPress 5.8 or higher
- WooCommerce 6.0 or higher
- Node.js 16.x or higher
- npm or yarn package manager

### Local Development
1. Clone the theme to your WordPress themes directory
2. Install dependencies: `npm install`
3. Start development build: `npm run dev`
4. Make your changes
5. Build for production: `npm run build`

## 📝 Code Standards

### PHP Standards
- Follow WordPress Coding Standards
- Use proper sanitization and escaping
- Include proper docblocks for functions
- Use WordPress hooks and filters appropriately

```php
/**
 * Function description
 *
 * @param string $param Description
 * @return mixed
 */
function tostishop_custom_function( $param ) {
    // Function implementation
}
```

### CSS Standards
- Use Tailwind CSS utility classes
- Follow mobile-first approach
- Maintain TostiShop brand colors
- Use semantic class names for custom CSS

```css
/* Custom components in assets/css/custom.css */
.custom-component {
    @apply bg-navy-900 text-white p-4 rounded-lg;
}
```

### JavaScript Standards
- Use Alpine.js for interactivity
- Follow ES6+ standards
- Include proper error handling
- Use meaningful variable names

```javascript
// Alpine.js component example
document.addEventListener('alpine:init', () => {
    Alpine.data('productSlider', () => ({
        currentSlide: 0,
        // Component logic
    }));
});
```

## 🎨 Design Guidelines

### Brand Colors
Always use the official TostiShop color palette:

- **Deep Navy Blue**: `#14175b` (navy-900)
- **Bright Red**: `#e42029` (accent)
- **Light Silver White**: `#ecebee` (silver-50)

### Typography
- Use system font stack for performance
- Maintain proper heading hierarchy
- Ensure good contrast ratios
- Follow mobile-first typography scaling

### Responsive Design
- Mobile-first approach (375px base)
- Test on all breakpoints
- Touch-friendly interactive elements
- Optimal loading performance

## 🧪 Testing Guidelines

### Browser Testing
Test your changes across:
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

### Functionality Testing
- WooCommerce functionality
- Cart and checkout process
- User account features
- Payment integrations
- Mobile responsiveness

### Performance Testing
- Page load speed
- Image optimization
- JavaScript performance
- CSS file size

## 📋 Submission Process

### Before Submitting
1. Test thoroughly on multiple devices
2. Ensure code follows standards
3. Update documentation if needed
4. Check for conflicts with plugins

### Submission Requirements
- Clear description of changes
- Screenshots of visual changes
- Testing information
- Performance impact notes

### Review Process
1. Initial review by development team
2. Testing on staging environment
3. Code quality assessment
4. Performance evaluation
5. Final approval and deployment

## 🐛 Bug Reports

### Bug Report Template
When reporting bugs, include:

```
**Bug Description**
Clear description of the issue

**Steps to Reproduce**
1. Step one
2. Step two
3. Step three

**Expected Behavior**
What should happen

**Actual Behavior**
What actually happens

**Environment**
- WordPress version:
- WooCommerce version:
- Theme version:
- Browser:
- Device:

**Screenshots**
Include relevant screenshots
```

### Contact for Bug Reports
- **Email**: contact@tostishop.com
- **WhatsApp**: +91 79829 99145 (Chat only)

## 💡 Feature Requests

### Request Guidelines
- Align with TostiShop brand goals
- Consider mobile-first approach
- Maintain performance standards
- Include detailed use cases

### Submission Process
1. Contact us via email or WhatsApp
2. Provide detailed feature description
3. Include mockups or wireframes if applicable
4. Explain business value and user benefit

## 📚 Documentation

### Required Documentation
- Code comments for complex functions
- Update API reference for new functions
- Include usage examples
- Update changelog for significant changes

### Documentation Style
- Clear, concise explanations
- Code examples with context
- Step-by-step instructions
- Screenshots for visual features

## 🔒 Security Guidelines

### Security Best Practices
- Validate and sanitize all inputs
- Use WordPress nonces for forms
- Escape all outputs
- Follow WordPress security standards
- Regular security audits

### Reporting Security Issues
Security issues should be reported privately:
- **Email**: contact@tostishop.com
- Mark as "SECURITY ISSUE" in subject
- Provide detailed description
- Include proof of concept if applicable

## 📄 License & Legal

### Code License
All contributions become part of the TostiShop theme and are subject to the theme's license terms.

### Asset Usage
- Only use royalty-free assets
- Provide proper attribution
- Ensure commercial usage rights
- Document asset sources

## 🤝 Community

### Communication Channels
- **Primary**: contact@tostishop.com
- **WhatsApp**: +91 79829 99145 (Chat only)
- **Support**: Available for all development questions

### Code of Conduct
- Be respectful and professional
- Focus on constructive feedback
- Help maintain a positive environment
- Follow WordPress community guidelines

## 📈 Performance Standards

### Performance Targets
- Page load time < 3 seconds
- First Contentful Paint < 1.5 seconds
- Cumulative Layout Shift < 0.1
- Mobile-optimized experience

### Optimization Requirements
- Optimize images and assets
- Minimize HTTP requests
- Use efficient CSS and JavaScript
- Implement proper caching strategies

## 🚀 Deployment

### Pre-deployment Checklist
- [ ] All tests passing
- [ ] Performance benchmarks met
- [ ] Cross-browser compatibility verified
- [ ] Mobile responsiveness confirmed
- [ ] Documentation updated
- [ ] Security review completed

### Deployment Process
1. Staging environment testing
2. Performance validation
3. Security audit
4. Final review and approval
5. Production deployment
6. Post-deployment monitoring

---

## 📞 Get Help

Need assistance with contributing? Contact us:

- **Email**: contact@tostishop.com
- **WhatsApp**: +91 79829 99145 (Chat only, no calls)

We're here to help make your contribution successful!

---

*Thank you for contributing to TostiShop! 🎉*
