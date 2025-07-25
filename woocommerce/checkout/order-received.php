<?php
/**
 * "Order received" message - Modern Mobile-First Design
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/order-received.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.8.0
 *
 * @var WC_Order|false $order
 */

defined( 'ABSPATH' ) || exit;

/**
 * Filter the message shown after a checkout is complete.
 *
 * @since 2.2.0
 *
 * @param string         $message The message.
 * @param WC_Order|false $order   The order created during checkout, or false if order data is not available.
 */
$message = apply_filters(
	'woocommerce_thankyou_order_received_text',
	esc_html( __( 'Thank you. Your order has been received.', 'woocommerce' ) ),
	$order
);

// Don't output anything here as this is handled in the parent thankyou.php template
// This template is kept for compatibility but content is managed by thankyou.php
?>
