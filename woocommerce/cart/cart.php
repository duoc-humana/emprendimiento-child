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
                                                    'min_value'    => 1,
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
                                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; endforeach; ?>
                    </div>

                    <!-- Resumen -->
                    <div class="carrito-resumen">
                        <?php woocommerce_cart_totals(); ?>
                        
                    </div>
                </div>
                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

                <!-- Botón actualizar carrito (oculto, se activa con JS) -->
                <div style="display:none;">
                    <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
                        <?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php do_action( 'woocommerce_after_cart' ); ?>
