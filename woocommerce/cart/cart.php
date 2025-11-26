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
                <?php do_action( 'woocommerce_before_cart_table' ); ?>

                <div class="carrito-contenido">
                    <div class="carrito-items">
                        <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                            $_product   = $cart_item['data'];
                            $product_id = $cart_item['product_id'];

                            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) :
                                $product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
                                ?>
                                <div class="carrito-item" data-key="<?php echo esc_attr( $cart_item_key ); ?>">
                                    
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
                                        <?php
                                        if ( $_product->is_sold_individually() ) {
                                            $min_quantity = 1;
                                            $max_quantity = 1;
                                        } else {
                                            $min_quantity = 0;
                                            $max_quantity = $_product->get_max_purchase_quantity();
                                        }

                                        $product_quantity = woocommerce_quantity_input(
                                            array(
                                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                                'input_value'  => $cart_item['quantity'],
                                                'max_value'    => $max_quantity,
                                                'min_value'    => $min_quantity,
                                                'product_name' => $_product->get_name(),
                                            ),
                                            $_product,
                                            false
                                        );

                                        echo $product_quantity;
                                        ?>
                                    </div>

                                    <!-- Precio -->
                                    <div class="carrito-item-precio">
                                        <?php echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); ?>
                                    </div>

                                    <!-- Botón eliminar -->
                                    <div class="carrito-item-remove">
                                        <?php
                                        echo apply_filters(
                                            'woocommerce_cart_item_remove_link',
                                            sprintf(
                                                '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6"/>
                                                    </svg>
                                                </a>',
                                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                esc_attr__( 'Remove this item', 'woocommerce' ),
                                                esc_attr( $product_id ),
                                                esc_attr( $_product->get_sku() )
                                            ),
                                            $cart_item_key
                                        );
                                        ?>
                                    </div>
                                </div>
                            <?php endif; 
                        endforeach; ?>
                    </div>

                    <!-- Resumen -->
                    <?php woocommerce_cart_totals(); ?>
                </div>

                <?php do_action( 'woocommerce_cart_actions' ); ?>

                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

                <!-- Botón actualizar carrito (oculto pero necesario) -->
                <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>" style="display: none;">
                    <?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
                </button>

                <?php do_action( 'woocommerce_after_cart_table' ); ?>
            </form>

            <?php do_action( 'woocommerce_after_cart' ); ?>
        <?php endif; ?>
    </div>
</main>