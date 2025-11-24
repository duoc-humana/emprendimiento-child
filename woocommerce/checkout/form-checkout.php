<?php
/**
 * Checkout Form
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// Si el checkout está deshabilitado
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
    return;
}
?>

<main class="checkout-page">
    <div class="checkout-container">
        
        <h1 class="checkout-titulo">Finalizar compra</h1>

        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

            <?php if ( $checkout->get_checkout_fields() ) : ?>

                <div class="checkout-contenido">
                    
                    <!-- Columna izquierda: Datos del cliente -->
                    <div class="checkout-datos">
                        
                        <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                        <!-- Datos de facturación -->
                        <div class="checkout-seccion">
                            <h3>Información de contacto</h3>
                            <?php do_action( 'woocommerce_checkout_billing' ); ?>
                        </div>

                        <!-- Datos de envío -->
                        <div class="checkout-seccion">
                            <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                        </div>

                        <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

                    </div>

                    <!-- Columna derecha: Resumen del pedido -->
                    <div class="checkout-resumen">
                        
                        <h3 id="order_review_heading">Tu pedido</h3>

                        <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                        </div>

                        <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

                    </div>

                </div>

            <?php endif; ?>

        </form>

    </div>
</main>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>