/**
 * Checkout Login Functionality
 * Handles "Returning customer? Click here to login" functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Handle login form toggle
    const loginToggle = document.querySelector('.woocommerce-form-login-toggle a');
    const loginForm = document.querySelector('.woocommerce-form-login');
    
    if (loginToggle && loginForm) {
        // Ensure the link doesn't have an empty href
        if (loginToggle.getAttribute('href') === '#' || !loginToggle.getAttribute('href')) {
            loginToggle.setAttribute('href', 'javascript:void(0)');
        }
        
        loginToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Toggle the login form visibility
            if (loginForm.style.display === 'none' || loginForm.style.display === '') {
                loginForm.style.display = 'block';
                loginToggle.textContent = 'Hide login form';
                
                // Smooth scroll to form
                loginForm.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Focus on username field
                const usernameField = loginForm.querySelector('#username, input[name="username"]');
                if (usernameField) {
                    setTimeout(() => usernameField.focus(), 300);
                }
            } else {
                loginForm.style.display = 'none';
                loginToggle.textContent = 'Click here to login';
            }
        });
    }
    
    // Handle coupon form toggle
    const couponToggle = document.querySelector('.woocommerce-form-coupon-toggle a');
    const couponForm = document.querySelector('.woocommerce-form-coupon');
    
    if (couponToggle && couponForm) {
        // Ensure the link doesn't have an empty href
        if (couponToggle.getAttribute('href') === '#' || !couponToggle.getAttribute('href')) {
            couponToggle.setAttribute('href', 'javascript:void(0)');
        }
        
        couponToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Toggle the coupon form visibility
            if (couponForm.style.display === 'none' || couponForm.style.display === '') {
                couponForm.style.display = 'block';
                couponToggle.textContent = 'Hide coupon form';
                
                // Smooth scroll to form
                couponForm.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Focus on coupon field
                const couponField = couponForm.querySelector('#coupon_code, input[name="coupon_code"]');
                if (couponField) {
                    setTimeout(() => couponField.focus(), 300);
                }
            } else {
                couponForm.style.display = 'none';
                couponToggle.textContent = 'Have a coupon?';
            }
        });
    }
    
    // Enhanced login form functionality
    const checkoutLoginForm = document.querySelector('.woocommerce-form-login form');
    if (checkoutLoginForm) {
        checkoutLoginForm.addEventListener('submit', function(e) {
            const username = this.querySelector('#username, input[name="username"]');
            const password = this.querySelector('#password, input[name="password"]');
            const submitButton = this.querySelector('button[type="submit"], input[type="submit"]');
            
            // Basic validation
            if (!username?.value.trim() || !password?.value.trim()) {
                e.preventDefault();
                
                // Show error message
                let errorDiv = this.querySelector('.login-error');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'login-error woocommerce-error';
                    errorDiv.style.marginTop = '1rem';
                    this.insertBefore(errorDiv, submitButton);
                }
                
                errorDiv.innerHTML = '<strong>Error:</strong> Please enter both username and password.';
                errorDiv.style.display = 'block';
                
                // Focus on empty field
                if (!username?.value.trim()) {
                    username.focus();
                } else {
                    password.focus();
                }
                
                return;
            }
            
            // Add loading state to submit button
            if (submitButton) {
                const originalText = submitButton.textContent || submitButton.value;
                submitButton.textContent = submitButton.value = 'Logging in...';
                submitButton.disabled = true;
                
                // Reset after 10 seconds if no response
                setTimeout(() => {
                    submitButton.textContent = submitButton.value = originalText;
                    submitButton.disabled = false;
                }, 10000);
            }
        });
    }
});