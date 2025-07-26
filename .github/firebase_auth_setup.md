# Firebase Authentication Integration for WordPress Theme (TostiShop)

This file guides you through setting up a **modular Firebase Authentication system** for your WooCommerce theme without relying on plugins. It focuses on secure and fast login using mobile OTP, Google, and default email-password methods — in that priority.

---

## 🔧 Folder Structure

```
tostishop/
├── functions.php
├── inc/
│   ├── firebase/
│   │   ├── init.php              # Loads all Firebase modules
│   │   ├── config.php            # Firebase credentials from theme settings
│   │   ├── enqueue.php           # Enqueue Firebase SDK and custom JS
│   │   ├── auth-ui.php           # Hardcoded UI output (no shortcodes)
│   │   └── api.php               # Optional: REST API (future)
├── assets/
│   └── js/
│       └── firebase-auth.js      # Frontend auth logic
```

---

## 1. `functions.php`
Add:
```php
require_once get_template_directory() . '/inc/firebase/init.php';
```

---

## 2. `init.php`
```php
<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/enqueue.php';
require_once __DIR__ . '/auth-ui.php';
```

---

## 3. `config.php`
```php
<?php
function tostishop_register_firebase_settings() {
    add_option('tostishop_firebase_api_key', '');
    add_option('tostishop_firebase_project_id', '');
    add_option('tostishop_firebase_auth_domain', '');
    add_option('tostishop_firebase_sender_id', '');
    add_option('tostishop_firebase_app_id', '');
    add_option('tostishop_firebase_measurement_id', '');

    register_setting('general', 'tostishop_firebase_api_key');
    register_setting('general', 'tostishop_firebase_project_id');
    register_setting('general', 'tostishop_firebase_auth_domain');
    register_setting('general', 'tostishop_firebase_sender_id');
    register_setting('general', 'tostishop_firebase_app_id');
    register_setting('general', 'tostishop_firebase_measurement_id');
}
add_action('admin_init', 'tostishop_register_firebase_settings');
```

---

## 4. `enqueue.php`
```php
<?php
function tostishop_enqueue_firebase_scripts() {
    $apiKey = get_option('tostishop_firebase_api_key');
    $authDomain = get_option('tostishop_firebase_auth_domain');
    $projectId = get_option('tostishop_firebase_project_id');
    $appId = get_option('tostishop_firebase_app_id');
    $messagingSenderId = get_option('tostishop_firebase_sender_id');
    $measurementId = get_option('tostishop_firebase_measurement_id');

    wp_enqueue_script('firebase-app', 'https://www.gstatic.com/firebasejs/10.7.0/firebase-app.js', [], null, true);
    wp_enqueue_script('firebase-auth', 'https://www.gstatic.com/firebasejs/10.7.0/firebase-auth.js', ['firebase-app'], null, true);
    wp_enqueue_script('tostishop-firebase', get_template_directory_uri() . '/assets/js/firebase-auth.js', ['firebase-app', 'firebase-auth'], null, true);

    wp_localize_script('tostishop-firebase', 'firebaseConfig', [
        'apiKey' => $apiKey,
        'authDomain' => $authDomain,
        'projectId' => $projectId,
        'appId' => $appId,
        'messagingSenderId' => $messagingSenderId,
        'measurementId' => $measurementId,
    ]);
}
add_action('wp_enqueue_scripts', 'tostishop_enqueue_firebase_scripts');
```

---

## 5. `auth-ui.php`
```php
<?php
function tostishop_render_firebase_login_ui() {
    ?>
    <div id="firebase-login-area">
        <button id="firebase-login-btn">Login with Phone</button>
        <div id="recaptcha-container"></div>
        <input type="text" id="otp-code" placeholder="Enter OTP" />
        <button id="verify-otp-btn">Verify OTP</button>
        <hr />
        <button id="google-login-btn">Login with Google</button>
        <hr />
        <form id="email-login-form">
            <input type="email" id="email" placeholder="Email" required />
            <input type="password" id="password" placeholder="Password" required />
            <button id="email-login-btn">Login with Email</button>
        </form>
    </div>
    <?php
}
```

---

## 🔍 Placement of Firebase Login UI (Hardcoded)

You can hardcode the login UI in these theme templates:

1. **Header (`header.php`)**:
   Nothing for now

2. **Checkout Page**:
   Hook into `woocommerce_before_checkout_form`:
   ```php
   add_action('woocommerce_before_checkout_form', function() {
       if (!is_user_logged_in()) {
           tostishop_render_firebase_login_ui();
       }
   }, 5);
   ```

3. **Account Page (`page-my-account.php`)**:
   Show login if guest:
   ```php
   if (!is_user_logged_in()) {
       tostishop_render_firebase_login_ui();
   }
   ```

4. **Cart Page** *(optional)*:
   Encourage users to log in for faster checkout.

---

## 6. `firebase-auth.js`

Handles 3 login types:
1. **Mobile OTP**
2. **Google**
3. **Email/Password**

```js
document.addEventListener("DOMContentLoaded", () => {
  const firebaseApp = firebase.initializeApp(firebaseConfig);
  const auth = firebase.auth();

  // Phone login
  document.getElementById('firebase-login-btn')?.addEventListener('click', () => {
    const phoneNumber = prompt("Enter mobile number with country code (e.g. +91...)");
    const appVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', { size: 'invisible' });

    auth.signInWithPhoneNumber(phoneNumber, appVerifier)
      .then(confirmResult => {
        window.confirmResult = confirmResult;
        alert("OTP sent");
      })
      .catch(console.error);
  });

  document.getElementById('verify-otp-btn')?.addEventListener('click', () => {
    const code = document.getElementById('otp-code').value;
    window.confirmResult.confirm(code).then(result => {
      const user = result.user;
      console.log("Logged in via phone:", user.phoneNumber);
      // TODO: Sync to WP
    }).catch(console.error);
  });

  // Google login
  document.getElementById('google-login-btn')?.addEventListener('click', () => {
    const provider = new firebase.auth.GoogleAuthProvider();
    auth.signInWithPopup(provider).then(result => {
      const user = result.user;
      console.log("Logged in via Google:", user.email);
      // TODO: Sync to WP
    }).catch(console.error);
  });

  // Email/Password login
  document.getElementById('email-login-btn')?.addEventListener('click', (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    auth.signInWithEmailAndPassword(email, password).then(result => {
      const user = result.user;
      console.log("Logged in via email:", user.email);
      // TODO: Sync to WP
    }).catch(console.error);
  });
});
```

---

## ✅ Next Steps

- 🔐 Create REST API to sync Firebase user with WordPress user
- 🔁 Maintain login session with secure cookie/token
- 📦 Allow multiple addresses (like Amazon/Flipkart)
- 🪄 Add login persistence + redirect to checkout if came from cart
- 🎨 Improve UI via modal or styled interface

---

This is a fully **hardcoded, shortcode-free**, security-conscious Firebase login system tailored for your theme `tostishop`. Mobile-based login is primary, followed by Google, then fallback email login — just like top e-commerce platforms.
