<?php
/**
 * Cart Page
 *
 * Colocar en: /wp-content/themes/tu-tema-hijo/woocommerce/cart/cart.php
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<main class="carrito-page">
    <div class="carrito-container">
        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="carrito-volver">
            Volver atrás
        </a>

        <h1 class="carrito-titulo">Tu carrito</h1>

        <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <div class="carrito-contenido">
                <!-- Lista de productos -->
                <div class="carrito-items">
                    
                    <?php
                    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                            ?>
                            
                            <div class="carrito-item woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                                
                                <!-- Imagen -->
                                <?php
                                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                
                                if ( ! $product_permalink ) {
                                    echo wp_kses_post( str_replace( 'class="', 'class="carrito-item-imagen ', $thumbnail ) );
                                } else {
                                    printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( str_replace( 'class="', 'class="carrito-item-imagen ', $thumbnail ) ) );
                                }
                                ?>

                                <!-- Nombre -->
                                <div class="carrito-item-nombre">
                                    <?php
                                    if ( ! $product_permalink ) {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                                    } else {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                    }

                                    do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                    // Meta data.
                                    echo wc_get_formatted_cart_item_data( $cart_item );

                                    // Backorder notification.
                                    if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
                                    }
                                    ?>
                                </div>

                                <!-- Cantidad -->
                                <div class="carrito-item-cantidad">
                                    <?php
                                    if ( $_product->is_sold_individually() ) {
                                        $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                    } else {
                                        $product_quantity = woocommerce_quantity_input(
                                            array(
                                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                                'input_value'  => $cart_item['quantity'],
                                                'max_value'    => $_product->get_max_purchase_quantity(),
                                                'min_value'    => '0',
                                                'product_name' => $_product->get_name(),
                                            ),
                                            $_product,
                                            false
                                        );
                                    }

                                    echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
                                    ?>
                                </div>

                                <!-- Precio -->
                                <div class="carrito-item-precio">
                                    <?php
                                        echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                                    ?>
                                </div>

                                <!-- Botón eliminar (oculto, se puede mostrar si quieres) -->
                                <div class="carrito-item-remove" style="display: none;">
                                    <?php
                                    echo apply_filters(
                                        'woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                            esc_html__( 'Remove this item', 'woocommerce' ),
                                            esc_attr( $product_id ),
                                            esc_attr( $_product->get_sku() )
                                        ),
                                        $cart_item_key
                                    );
                                    ?>
                                </div>
                            </div>
                            
                            <?php
                        }
                    }
                    ?>

                </div>

                <!-- Resumen -->
                <div class="carrito-resumen">
                    <div class="carrito-resumen-box">
                        <h3 class="carrito-resumen-titulo">Resumen</h3>

                        <div class="carrito-resumen-linea productos">
                            <span>Productos (<?php echo WC()->cart->get_cart_contents_count(); ?>)</span>
                            <span class="carrito-resumen-precio"><?php wc_cart_totals_subtotal_html(); ?></span>
                        </div>

                        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                            <div class="carrito-resumen-linea coupon">
                                <span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
                                <span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
                            </div>
                        <?php endforeach; ?>

                        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                            <div class="carrito-resumen-linea envio">
                                <span>Calcular costo de envío</span>
                            </div>
                        <?php endif; ?>

                        <div class="carrito-resumen-total">
                            <span class="carrito-total-label">Total</span>
                            <span class="carrito-total-monto"><?php wc_cart_totals_order_total_html(); ?></span>
                        </div>

                        <button type="submit" class="carrito-btn-actualizar" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>" style="display: none;">
                            <?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
                        </button>

                        <?php do_action( 'woocommerce_cart_actions' ); ?>

                        <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

                        <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="carrito-btn-comprar">
                            Comprar
                        </a>
                    </div>
                </div>
            </div>

            <?php do_action( 'woocommerce_after_cart_table' ); ?>
        </form>

    </div>
</main>

<?php do_action( 'woocommerce_after_cart' ); ?>

