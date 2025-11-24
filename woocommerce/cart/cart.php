<?php
/**
 * Cart Page adaptado
 * Ubicación: /wp-content/themes/tu-tema-hijo/woocommerce/cart/cart.php
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<main class="carrito-page">
    <div class="carrito-container">
        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="carrito-volver">Volver atrás</a>
        <h1 class="carrito-titulo">Tu carrito</h1>

        <?php if ( WC()->cart->is_empty() ) : ?>
            <?php do_action( 'woocommerce_cart_is_empty' ); ?>
        <?php else : ?>
            <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                <div class="carrito-contenido">
                    <div class="carrito-items">
                        <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                            $_product   = $cart_item['data'];
                            $product_id = $cart_item['product_id'];

                            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) :
                                $product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
                                ?>
                                <div class="carrito-item">
                                    <!-- Imagen -->
                                    <?php
                                    $thumbnail = $_product->get_image();
                                    if ( $product_permalink ) {
                                        echo '<a href="' . esc_url( $product_permalink ) . '" class="carrito-item-imagen">' . $thumbnail . '</a>';
                                    } else {
                                        echo '<div class="carrito-item-imagen">' . $thumbnail . '</div>';
                                    }
                                    ?>

                                    <!-- Nombre -->
                                    <div class="carrito-item-nombre">
                                        <?php
                                        if ( $product_permalink ) {
                                            echo '<a href="' . esc_url( $product_permalink ) . '">' . esc_html( $_product->get_name() ) . '</a>';
                                        } else {
                                            echo esc_html( $_product->get_name() );
                                        }
                                        echo wc_get_formatted_cart_item_data( $cart_item );
                                        ?>
                                    </div>

                                    <!-- Cantidad con botones + y - -->
                                    <div class="carrito-item-cantidad">
                                        <div class="quantity-wrapper">
                                            <button type="button" class="qty-btn minus">-</button>
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
                                            <button type="button" class="qty-btn plus">+</button>
                                        </div>
                                    </div>

                                    <!-- Precio -->
                                    <div class="carrito-item-precio">
                                        <?php echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); ?>
                                    </div>

                                    <!-- Botón eliminar con ícono de basurero -->
                                    <div class="carrito-item-remove">
                                        <a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" 
                                           class="remove" 
                                           aria-label="<?php esc_attr_e( 'Eliminar producto', 'woocommerce' ); ?>">
                                           🗑️
                                        </a>
                                    </div>
                                </div>
                            <?php endif; endforeach; ?>
                    </div>

                    <!-- Resumen -->
                    <div class="carrito-resumen">
                        <?php woocommerce_cart_totals(); ?>
                        <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="carrito-btn-comprar">
                            Comprar
                        </a>
                    </div>
                </div>
                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php do_action( 'woocommerce_after_cart' ); ?>
