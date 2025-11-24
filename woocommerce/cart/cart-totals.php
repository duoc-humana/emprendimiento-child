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

                            <!-- Productos -->
                            <div class="carrito-resumen-linea productos">
                                <span>Productos (<?php echo WC()->cart->get_cart_contents_count(); ?>)</span>
                                <span class="carrito-resumen-precio"><?php wc_cart_totals_subtotal_html(); ?></span>
                            </div>

                            <!-- Envío -->
                            <div class="carrito-resumen-linea envio">
                                <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                                    <?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
                                    <?php wc_cart_totals_shipping_html(); ?>
                                    <?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
                                <?php else : ?>
                                    <span>Calcular costo de envío</span>
                                <?php endif; ?>
                            </div>

                            <!-- Cupón (opcional) -->
                            <?php if ( wc_coupons_enabled() ) : ?>
                                <div class="carrito-cupon">
                                    <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />
                                    <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>">
                                        <?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?>
                                    </button>
                                    <?php do_action( 'woocommerce_cart_coupon' ); ?>
                                </div>
                            <?php endif; ?>

                            <!-- Total -->
                            <div class="carrito-resumen-total">
                                <span class="carrito-total-label">Total</span>
                                <span class="carrito-total-monto"><?php wc_cart_totals_order_total_html(); ?></span>
                            </div>

                            <!-- Botón actualizar carrito (oculto) -->
                            <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>" style="display: none;">
                                <?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
                            </button>

                            <?php do_action( 'woocommerce_cart_actions' ); ?>

                            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

                            <!-- Botón comprar -->
                            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="carrito-btn-comprar checkout-button button alt wc-forward">
                                Comprar
                            </a>
                        </div>
                    </div>