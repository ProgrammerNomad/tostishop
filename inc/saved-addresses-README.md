# Saved Addresses Feature for TostiShop

## Overview

The Saved Addresses feature allows customers to save multiple billing and shipping addresses to their account for faster checkout. This implementation provides a comprehensive address book system that integrates seamlessly with WooCommerce.

## Features

### Customer Features
- **Multiple Addresses**: Save unlimited billing and shipping addresses
- **Default Addresses**: Set default addresses for automatic selection
- **Address Management**: Add, edit, delete addresses from My Account
- **Quick Selection**: Choose saved addresses during checkout
- **Mobile Optimized**: Fully responsive design for all devices
- **Address Validation**: Server-side validation and sanitization

### Admin Features
- **Statistics Dashboard**: View usage statistics and metrics
- **Migration Tools**: Import existing customer addresses
- **Database Management**: Install and manage database tables
- **Recent Activity**: Monitor recent address additions

## Installation

The feature automatically installs when the theme is activated. The installation includes:

1. **Database Table Creation**: Creates `wp_tostishop_saved_addresses` table
2. **Rewrite Endpoints**: Adds `address-book` endpoint to My Account
3. **Asset Registration**: Enqueues required CSS and JavaScript files

### Manual Installation

If needed, you can manually trigger installation:

```php
// Install database tables
tostishop_install_saved_addresses();

// Import existing addresses
tostishop_import_existing_addresses();
```

## Usage

### For Customers

#### Accessing Address Book
1. Go to **My Account** → **Address Book**
2. View all saved addresses
3. Add, edit, or delete addresses
4. Set default addresses

#### During Checkout
1. Saved addresses appear at the top of checkout
2. Click any address to auto-fill checkout form
3. Use "Add New Address" to create addresses on-the-fly
4. Toggle "Use different address" for manual entry

### For Administrators

#### Admin Dashboard
- Access via **WooCommerce** → **Saved Addresses**
- View statistics and recent activity
- Install database tables if needed
- Import existing customer addresses

## File Structure

```
inc/
├── saved-addresses.php              # Main feature class
├── saved-addresses-install.php      # Database installation
└── saved-addresses-admin.php        # Admin interface

woocommerce/
├── myaccount/
│   └── address-book.php             # Address book template
└── checkout/
    └── saved-addresses.php          # Checkout address selection

assets/
├── js/
│   └── saved-addresses.js           # JavaScript functionality
└── css/
    └── components/
        └── saved-addresses.css      # Component styles
```

## Database Schema

The feature uses a single table: `wp_tostishop_saved_addresses`

```sql
CREATE TABLE wp_tostishop_saved_addresses (
    id int(11) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) NOT NULL,
    address_type varchar(10) NOT NULL DEFAULT 'billing',
    address_name varchar(100) NOT NULL,
    first_name varchar(100) NOT NULL,
    last_name varchar(100) NOT NULL,
    company varchar(100) DEFAULT '',
    address_1 varchar(255) NOT NULL,
    address_2 varchar(255) DEFAULT '',
    city varchar(100) NOT NULL,
    state varchar(100) NOT NULL,
    postcode varchar(20) NOT NULL,
    country varchar(5) NOT NULL,
    phone varchar(20) DEFAULT '',
    email varchar(100) DEFAULT '',
    is_default tinyint(1) DEFAULT 0,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY user_id (user_id),
    KEY address_type (address_type),
    KEY is_default (is_default),
    KEY user_type (user_id, address_type)
);
```

## API Reference

### Main Class: `TostiShop_Saved_Addresses`

#### Public Methods

```php
// Get user's saved addresses
get_user_addresses($user_id = null, $address_type = null)

// Get default address
get_default_address($user_id = null, $address_type = 'billing')

// Save new address
save_address($address_data, $user_id = null)

// Update existing address
update_address($address_id, $address_data, $user_id = null)

// Delete address
delete_address($address_id, $user_id = null)
```

### AJAX Endpoints

- `tostishop_save_address` - Save/update address
- `tostishop_delete_address` - Delete address
- `tostishop_set_default_address` - Set default address
- `tostishop_get_saved_address` - Get address details

### Installation Functions

```php
// Install database tables
tostishop_install_saved_addresses()

// Import existing WooCommerce addresses
tostishop_import_existing_addresses()

// Get usage statistics
tostishop_get_saved_addresses_stats()
```

## Hooks and Filters

### Actions
- `woocommerce_account_address-book_endpoint` - Address book content
- `woocommerce_checkout_before_customer_details` - Checkout address selection

### Filters
- `woocommerce_account_menu_items` - Add address book menu item

## Customization

### Styling
The feature uses Tailwind CSS classes. Customize in:
- `assets/css/components/saved-addresses.css`

### Templates
Override templates by copying to your child theme:
- `woocommerce/myaccount/address-book.php`
- `woocommerce/checkout/saved-addresses.php`

### JavaScript
Extend functionality in:
- `assets/js/saved-addresses.js`

## Security

The feature implements comprehensive security measures:

- **Nonce Verification**: All AJAX requests use WordPress nonces
- **User Authentication**: Checks user login status
- **Data Sanitization**: All input is sanitized using WordPress functions
- **SQL Injection Prevention**: Uses prepared statements
- **User Isolation**: Users can only access their own addresses

## Performance

- **Efficient Queries**: Optimized database queries with proper indexing
- **Lazy Loading**: Scripts only load on relevant pages
- **Caching**: Results can be cached for better performance
- **Minimal Impact**: No impact on non-logged-in users

## Browser Support

- **Modern Browsers**: Chrome, Firefox, Safari, Edge
- **Mobile**: iOS Safari, Chrome Mobile, Samsung Internet
- **Accessibility**: WCAG 2.1 AA compliant
- **JavaScript**: Graceful degradation when JS is disabled

## Troubleshooting

### Common Issues

#### Database Table Not Created
```php
// Manually create table
tostishop_install_saved_addresses();
```

#### Addresses Not Appearing
1. Check if user is logged in
2. Verify database table exists
3. Check for JavaScript errors
4. Ensure proper nonce values

#### Import Not Working
1. Verify existing addresses in user meta
2. Check for duplicate addresses
3. Review error logs

### Debug Mode
Enable WordPress debug mode to see detailed error messages:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Changelog

### Version 1.0.0
- Initial release
- Address book management
- Checkout integration
- Admin dashboard
- Import functionality
- Mobile optimization
- Security implementation

## Support

For support and customization:
1. Check the admin dashboard for statistics
2. Review error logs for issues
3. Test with default WordPress theme
4. Disable other plugins to isolate conflicts

## License

This feature is part of the TostiShop theme and follows the same licensing terms.
