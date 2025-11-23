
<?php
defined( 'ABSPATH' ) || exit;

get_header(); ?>

<div class="woocommerce">
    <?php
    /**
     * Hook: woocommerce_before_main_content.
     *
     * @hooked woocommerce_output_content_wrapper - 10
     * @hooked woocommerce_breadcrumb - 20
     */
    do_action( 'woocommerce_before_main_content' );

    // Avisos (ej. producto añadido al carrito)
    woocommerce_output_all_notices();

    // Contenido del carrito
    do_action( 'woocommerce_before_cart' );
    ?>

    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
        <?php do_action( 'woocommerce_before_cart_table' ); ?>

        <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
            <thead>
                <tr>
                    <th class="product-remove"><?php esc_html_e( 'Remove', 'woocommerce' ); ?></th>
                    <th class="product-thumbnail"><?php esc_html_e( 'Thumbnail', 'woocommerce' ); ?></th>
                    <th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                    <th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
                    <th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
                    <th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) : ?>
                    <?php
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) :
                        ?>
                        <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                            <td class="product-remove">
                                <?php
                                echo apply_filters(
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a href="%s" class="remove" aria-label="%s">&times;</a>',
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                        esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), $_product->get_name() ) )
                                    ),
                                    $cart_item_key
                                );
                                ?>
                            </td>
                            <td class="product-thumbnail">
                                <?php echo $_product->get_image(); ?>
                            </td>
                            <td class="product-name"><?php echo $_product->get_name(); ?></td>
                            <td class="product-price"><?php echo WC()->cart->get_product_price( $_product ); ?></td>
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
                            <td class="product-subtotal"><?php echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php do_action( 'woocommerce_cart_contents' ); ?>
            </tbody>
        </table>

        <?php do_action( 'woocommerce_after_cart_table' ); ?>
    </form>

    <?php do_action( 'woocommerce_cart_collaterals' ); ?>

    <?php do_action( 'woocommerce_after_cart' ); ?>

    <?php
    /**
     * Hook: woocommerce_after_main_content.
     *
     * @hooked woocommerce_output_content_wrapper_end - 10
     */
    do_action( 'woocommerce_after_main_content' );
    ?>
</div>

<?php get_footer(); ?>


