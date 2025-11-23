
<?php
defined( 'ABSPATH' ) || exit;

get_header(); ?>

<div class="woocommerce">
    <?php
    // Muestra avisos (ej. producto añadido al carrito)
    woocommerce_output_all_notices();

    // Renderiza el contenido del carrito usando la plantilla original
    wc_get_template( 'cart/cart.php' );
    ?>
</div>

<?php get_footer(); ?>

