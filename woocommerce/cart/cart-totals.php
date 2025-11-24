<?php
/**
 * Cart totals
 * 
 * Colocar en: /wp-content/themes/tu-tema-hijo/woocommerce/cart/cart-totals.php
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="carrito-resumen">
    <div class="carrito-resumen-box">
        
        <h3 class="carrito-resumen-titulo">Resumen</h3>

        <?php do_action( 'woocommerce_before_cart_totals' ); ?>

        <!-- Productos (Subtotal) -->
        <div class="carrito-resumen-linea productos">
            <span>Productos (<?php echo WC()->cart->get_cart_contents_count(); ?>)</span>
            <span class="carrito-resumen-precio">
                <?php wc_cart_totals_subtotal_html(); ?>
            </span>
        </div>

        <!-- Cupones aplicados -->
        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
            <div class="carrito-resumen-linea cupon">
                <span>
                    <?php wc_cart_totals_coupon_label( $coupon ); ?>
                </span>
                <span class="carrito-resumen-precio">
                    <?php wc_cart_totals_coupon_html( $coupon ); ?>
                </span>
            </div>
        <?php endforeach; ?>

        <!-- Envío -->
        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
            
            <?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
            
            <div class="carrito-resumen-linea envio">
                <?php 
                // Obtener métodos de envío
                $packages = WC()->shipping()->get_packages();
                $package = reset( $packages );
                $available_methods = $package['rates'];
                
                if ( ! empty( $available_methods ) ) {
                    foreach ( $available_methods as $method ) {
                        ?>
                        <span>Envío (<?php echo esc_html( $method->get_label() ); ?>)</span>
                        <span class="carrito-resumen-precio"><?php echo wc_price( $method->get_cost() ); ?></span>
                        <?php
                        break; // Solo mostrar el primero
                    }
                } else {
                    ?>
                    <span>Calcular costo de envío</span>
                    <?php
                }
                ?>
            </div>
            
            <?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
            
        <?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>
            
            <div class="carrito-resumen-linea envio">
                <span>Calcular costo de envío</span>
            </div>
            
        <?php endif; ?>

        <!-- Impuestos (si están habilitados) -->
        <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
            
            <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                    <div class="carrito-resumen-linea impuesto">
                        <span><?php echo esc_html( $tax->label ); ?></span>
                        <span class="carrito-resumen-precio"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="carrito-resumen-linea impuesto">
                    <span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
                    <span class="carrito-resumen-precio"><?php wc_cart_totals_taxes_total_html(); ?></span>
                </div>
            <?php endif; ?>
            
        <?php endif; ?>

        <!-- Tarifas adicionales -->
        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div class="carrito-resumen-linea tarifa">
                <span><?php echo esc_html( $fee->name ); ?></span>
                <span class="carrito-resumen-precio"><?php wc_cart_totals_fee_html( $fee ); ?></span>
            </div>
        <?php endforeach; ?>

        <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

        <!-- Total -->
        <div class="carrito-resumen-total">
            <span class="carrito-total-label">Total</span>
            <span class="carrito-total-monto">
                <?php wc_cart_totals_order_total_html(); ?>
            </span>
        </div>

        <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

        <!-- Botón Comprar (Proceder al pago) -->
        <div class="wc-proceed-to-checkout">
            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="carrito-btn-comprar">
                <?php esc_html_e( 'Comprar', 'woocommerce' ); ?>
            </a>
        </div>

        <?php do_action( 'woocommerce_after_cart_totals' ); ?>

    </div>
</div>