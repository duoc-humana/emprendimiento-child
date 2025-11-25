<?php
/**
 * Checkout: Review Order
 *
 * Copia este archivo en tu theme hijo en:
 * /woocommerce/checkout/review-order.php
 */

defined( 'ABSPATH' ) || exit;
?>

<table class="shop_table woocommerce-checkout-review-order-table">
    <thead>
        <tr>
            <th class="product-name">Producto</th>
            <th class="product-total">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product   = $cart_item['data'];
            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
                ?>
                <tr class="cart_item">
                    <td class="product-name">
                        <?php echo esc_html( $_product->get_name() ); ?>
                        <strong class="product-quantity">× <?php echo esc_html( $cart_item['quantity'] ); ?></strong>
                    </td>
                    <td class="product-total">
                        <?php echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); ?>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
    <tfoot>
        <?php
        // Subtotal
        wc_cart_totals_subtotal_html();

        // Envío
        wc_cart_totals_shipping_html();

        // Cupones / descuentos
        wc_cart_totals_coupon_html();

        // Impuestos
        wc_cart_totals_taxes_total_html();

        // Total final
        wc_cart_totals_order_total_html();
        ?>
    </tfoot>
</table>

<?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>

<div id="payment" class="woocommerce-checkout-payment">
    <?php if ( WC()->cart->needs_payment() ) : ?>
        <ul class="wc_payment_methods payment_methods methods">
            <?php
            if ( ! empty( WC()->payment_gateways()->get_available_payment_gateways() ) ) {
                foreach ( WC()->payment_gateways()->get_available_payment_gateways() as $gateway ) {
                    wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
                }
            } else {
                echo '<li>' . esc_html__( 'No hay métodos de pago disponibles.', 'woocommerce' ) . '</li>';
            }
            ?>
        </ul>
    <?php endif; ?>

    <div class="form-row place-order">
        <?php do_action( 'woocommerce_review_order_before_submit' ); ?>
        <?php woocommerce_checkout_payment(); ?>
        <?php do_action( 'woocommerce_review_order_after_submit' ); ?>
    </div>
</div>
