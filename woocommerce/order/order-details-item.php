<?php
/**
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
    return;
}
?>
<tr class="woocommerce-table__line-item order_item border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">

    <td class="woocommerce-table__product-name product-name px-4 py-3">
        <?php
        $is_visible        = $product && $product->is_visible();
        $product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );

        echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s" class="text-navy-700 hover:text-primary transition-colors duration-200 font-medium">%s</a>', $product_permalink, $item->get_name() ) : $item->get_name(), $item, $is_visible ) );

        $qty          = $item->get_quantity();
        $refunded_qty = $order->get_qty_refunded_for_item( $item_id );

        if ( $refunded_qty ) {
            $qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
        } else {
            $qty_display = esc_html( $qty );
        }

        echo ' <strong class="product-quantity text-gray-600">×&nbsp;' . apply_filters( 'woocommerce_order_item_quantity_html', $qty_display, $item ) . '</strong>';

        do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );

        wc_display_item_meta( $item, array(
            'before'    => '<div class="text-sm text-gray-600 mt-1">',
            'after'     => '</div>',
            'separator' => '<br>',
        ) );

        if ( $show_purchase_note && $purchase_note ) {
            echo '<div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded text-sm text-blue-800">' . wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) ) . '</div>';
        }

        do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );
        ?>
    </td>

    <td class="woocommerce-table__product-total product-total text-right px-4 py-3 font-semibold text-gray-900">
        <?php echo $order->get_formatted_line_subtotal( $item ); ?>
    </td>

</tr>

<?php if ( $show_purchase_note && $purchase_note ) : ?>

<tr class="woocommerce-table__product-purchase-note product-purchase-note">

    <td colspan="2" class="px-4 py-2 bg-blue-50 border-t border-blue-200">
        <div class="text-sm text-blue-800">
            <?php echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) ); ?>
        </div>
    </td>

</tr>

<?php endif; ?>