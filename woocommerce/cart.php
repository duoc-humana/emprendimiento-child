<?php
/**
 * Plantilla personalizada de Carrito
 * Ubicación: /wp-content/themes/tu-tema-hijo/woocommerce/cart/cart.php
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="carrito-page">
    <h1 class="carrito-titulo"><?php esc_html_e( 'Tu carrito', 'woocommerce' ); ?></h1>

    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
        <?php do_action( 'woocommerce_before_cart_table' ); ?>

        <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">
            <thead>
                <tr>
                    <th class="product-thumbnail"><?php esc_html_e( 'Imagen', 'woocommerce' ); ?></th>
                    <th class="product-name"><?php esc_html_e( 'Producto', 'woocommerce' ); ?></th>
                    <th class="product-price"><?php esc_html_e( 'Precio', 'woocommerce' ); ?></th>
                    <th class="product-quantity"><?php esc_html_e( 'Cantidad', 'woocommerce' ); ?></th>
                    <th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
                    <th class="product-remove"><?php esc_html_e( 'Eliminar', 'woocommerce' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $_product   = $cart_item['data'];
                    $product_id = $cart_item['product_id'];

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
                        ?>
                        <tr class="woocommerce-cart-form__cart-item">
                            <td class="product-thumbnail">
                                <?php echo $_product->get_image(); ?>
                            </td>
                            <td class="product-name">
                                <?php echo $_product->get_name(); ?>
                            </td>
                            <td class="product-price">
                                <?php echo WC()->cart->get_product_price( $_product ); ?>
                            </td>
                            <td class="product-quantity">
                                <?php
                                echo woocommerce_quantity_input(
                                    array(
                                        'input_name'   => "cart[{$cart_item_key}][qty]",
                                        'input_value'  => $cart_item['quantity'],
                                        'max_value'    => $_product->get_max_purchase_quantity(),
                                        'min_value'    => 0,
                                    ),
                                    $_product,
                                    false
                                );
                                ?>
                            </td>
                            <td class="product-subtotal">
                                <?php echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); ?>
                            </td>
                            <td class="product-remove">
                                <?php
                                echo sprintf(
                                    '<a href="%s" class="remove">&times;</a>',
                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) )
                                );
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                <tr>
                    <td colspan="6" class="actions">
                        <button type="submit" class="button" name="update_cart">
                            <?php esc_html_e( 'Actualizar carrito', 'woocommerce' ); ?>
                        </button>
                        <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php do_action( 'woocommerce_after_cart_table' ); ?>
    </form>

    <?php do_action( 'woocommerce_cart_collaterals' ); ?>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
