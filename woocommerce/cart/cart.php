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
        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="carrito-volver">Volver atrás</a>

        <h1 class="carrito-titulo">Tu carrito</h1>

        <?php if ( WC()->cart->is_empty() ) : ?>
            
            <!-- Carrito vacío -->
            <?php do_action( 'woocommerce_cart_is_empty' ); ?>

        <?php else : ?>

            <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                
                <?php do_action( 'woocommerce_before_cart_table' ); ?>

                <div class="carrito-contenido">
                    
                    <!-- Lista de productos -->
                    <div class="carrito-items">
                        
                        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                        <?php
                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                            $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

                            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                ?>
                                
                                <div class="carrito-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                                    
                                    <!-- Botón eliminar -->
                                    <div class="carrito-item-remove">
                                        <?php
                                            echo apply_filters(
                                                'woocommerce_cart_item_remove_link',
                                                sprintf(
                                                    '<a role="button" href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                    esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
                                                    esc_attr( $product_id ),
                                                    esc_attr( $_product->get_sku() )
                                                ),
                                                $cart_item_key
                                            );
                                        ?>
                                    </div>

                                    <!-- Imagen -->
                                    <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                    
                                    if ( ! $product_permalink ) {
                                        echo '<div class="carrito-item-imagen">' . $thumbnail . '</div>';
                                    } else {
                                        echo '<a href="' . esc_url( $product_permalink ) . '" class="carrito-item-imagen">' . $thumbnail . '</a>';
                                    }
                                    ?>

                                    <!-- Nombre del producto -->
                                    <div class="carrito-item-nombre">
                                        <?php
                                        if ( ! $product_permalink ) {
                                            echo wp_kses_post( $product_name );
                                        } else {
                                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                        }

                                        // Metadatos (variaciones, etc.)
                                        echo wc_get_formatted_cart_item_data( $cart_item );

                                        // Notificación de backorder
                                        if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
                                        }
                                        ?>
                                    </div>

                                    <!-- Cantidad -->
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
                                                'product_name' => $product_name,
                                            ),
                                            $_product,
                                            false
                                        );

                                        echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
                                        ?>
                                    </div>

                                    <!-- Precio -->
                                    <div class="carrito-item-precio">
                                        <?php
                                            echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                        ?>
                                    </div>
                                </div>

                                <?php
                            }
                        }
                        ?>

                        <?php do_action( 'woocommerce_cart_contents' ); ?>

                        <!-- Botón actualizar carrito (oculto, se activa con JS) -->
                        <div style="display: none;">
                            <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
                                <?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
                            </button>

                            <?php do_action( 'woocommerce_cart_actions' ); ?>

                            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                        </div>

                        <?php do_action( 'woocommerce_after_cart_contents' ); ?>

                    </div>

                    <!-- Resumen del carrito (ahora usa cart-totals.php) -->
                    <?php woocommerce_cart_totals(); ?>

                </div>

                <?php do_action( 'woocommerce_after_cart_table' ); ?>

            </form>

            <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

            <!-- Sección de productos cruzados -->
            

        <?php endif; ?>

    </div>
</main>

<?php do_action( 'woocommerce_after_cart' ); ?>