<?php
/**
 * Cart totals
 * 
 * Ubicación: /wp-content/themes/tu-tema-hijo/woocommerce/cart/cart-totals.php
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="carrito-resumen cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>" id="carrito-resumen">
    <div class="carrito-resumen-box">
        
        <h3 class="carrito-resumen-titulo">Resumen</h3>

        <?php do_action( 'woocommerce_before_cart_totals' ); ?>

        <table cellspacing="0" class="shop_table shop_table_responsive" style="display: none;">
            <tr class="cart-subtotal">
                <th><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
                <td data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
            </tr>

            <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                <tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                    <th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
                    <td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

                <?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

                <?php wc_cart_totals_shipping_html(); ?>

                <?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

            <?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

                <tr class="shipping">
                    <th><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></th>
                    <td data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
                </tr>

            <?php endif; ?>

            <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                <tr class="fee">
                    <th><?php echo esc_html( $fee->name ); ?></th>
                    <td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php
            if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
                $taxable_address = WC()->customer->get_taxable_address();
                $estimated_text  = '';

                if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
                    /* translators: %s location. */
                    $estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
                }

                if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
                    foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { ?>
                        <tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                            <th><?php echo esc_html( $tax->label ) . $estimated_text; ?></th>
                            <td data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr class="tax-total">
                        <th><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; ?></th>
                        <td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
                    </tr>
                <?php }
            }
            ?>

            <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

            <tr class="order-total">
                <th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
                <td data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
            </tr>

            <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

        </table>

        <!-- Vista personalizada del resumen -->
        
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
                <span class="carrito-resumen-precio descuento">
                    <?php wc_cart_totals_coupon_html( $coupon ); ?>
                </span>
            </div>
        <?php endforeach; ?>

        <!-- Envío -->
        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
            
            <?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
            
            <div class="carrito-resumen-linea envio">
                <?php 
                $packages = WC()->shipping()->get_packages();
                
                if ( ! empty( $packages ) ) {
                    $package = reset( $packages );
                    $available_methods = isset( $package['rates'] ) ? $package['rates'] : array();
                    
                    if ( ! empty( $available_methods ) ) {
                        $method = reset( $available_methods );
                        ?>
                        <span>Envío (<?php echo esc_html( $method->get_label() ); ?>)</span>
                        <span class="carrito-resumen-precio">
                            <?php if ( $method->get_cost() > 0 ) : ?>
                                <?php echo wc_price( $method->get_cost() ); ?>
                            <?php else : ?>
                                <strong>Gratis</strong>
                            <?php endif; ?>
                        </span>
                        <?php
                    } else {
                        ?>
                        <span>Envío</span>
                        <span class="carrito-resumen-precio">A calcular</span>
                        <?php
                    }
                } else {
                    ?>
                    <span>Envío</span>
                    <span class="carrito-resumen-precio">A calcular</span>
                    <?php
                }
                ?>
            </div>
            
            <?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
            
        <?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>
            
            <div class="carrito-resumen-linea envio">
                <span>Envío</span>
                <span class="carrito-resumen-precio">A calcular</span>
            </div>
            
        <?php endif; ?>

        <!-- Impuestos -->
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

        <!-- Botón Comprar -->
        <div class="wc-proceed-to-checkout">
            <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="carrito-btn-comprar checkout-button button alt wc-forward">
                <?php esc_html_e( 'Comprar', 'woocommerce' ); ?>
            </a>
        </div>

        <?php do_action( 'woocommerce_after_cart_totals' ); ?>

    </div>
</div>