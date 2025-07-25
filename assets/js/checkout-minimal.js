/**
 * Minimal Checkout JavaScript - Essential Functions Only
 */

jQuery(document).ready(function($) {
    
    console.log('Minimal checkout JS loaded');
    
    // Debug form submission
    $('form.checkout').on('submit', function(e) {
        console.log('Form submitted');
        console.log('Form action:', $(this).attr('action'));
        console.log('Form method:', $(this).attr('method'));
        
        // Show loading state
        $('#place_order').addClass('processing').prop('disabled', true);
    });
    
    // Debug payment method changes
    $(document.body).on('change', '.wc_payment_methods input[type="radio"]', function() {
        console.log('Payment method changed:', $(this).val());
    });
    
    // Debug checkout errors
    $(document.body).on('checkout_error', function(event, error_message) {
        console.log('Checkout error:', error_message);
        $('#place_order').removeClass('processing').prop('disabled', false);
    });
    
    // Debug checkout update
    $(document.body).on('updated_checkout', function() {
        console.log('Checkout updated');
        $('#place_order').removeClass('processing').prop('disabled', false);
    });
    
});