<?php
/**
 * Empty cart page
 * Ubicación: /wp-content/themes/tu-tema-hijo/woocommerce/cart/cart-empty.php
 * 
 * Este template se muestra cuando el carrito está vacío
 * 
 */

defined( 'ABSPATH' ) || exit;
/*
 * Hook: woocommerce_cart_is_empty
 */
do_action( 'woocommerce_cart_is_empty' );
?>

<div class="empty-cart-container">
    <div class="empty-cart-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
    </div>
    
    <h1 class="empty-cart-title">Tu carrito se encuentra vacío</h1>
    <p class="empty-cart-text">Agrega productos para iniciar el proceso de compra</p>
    
    <?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
        <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="btn-ir-tienda">
            <?php esc_html_e( 'Ir a tienda', 'woocommerce' ); ?>
        </a>
    <?php endif; ?>
</div>

<?php
/**
 * Hook: woocommerce_after_cart_is_empty
 */
do_action( 'woocommerce_after_cart_is_empty' );