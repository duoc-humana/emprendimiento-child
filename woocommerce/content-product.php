<?php
/**
 * Ubicación: /wp-content/themes/tu-tema-hijo/woocommerce/content-product.php
 */
defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
?>
<div class="product-item">
    <a href="<?php echo esc_url( get_permalink() ); ?>">
        <?php
        if ( has_post_thumbnail() ) {
            the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'product-img' ) );
        } else {
            echo '<img src="' . esc_url( wc_placeholder_img_src() ) . '" class="product-img" alt="placeholder">';
        }
        ?>
    </a>

    <div class="product-info">
        <h4 class="product-name">
            <a href="<?php echo esc_url( get_permalink() ); ?>">
                <?php the_title(); ?>
            </a>
        </h4>

        <?php
        $size_terms = wp_get_post_terms( get_the_ID(), 'pa_size' );
        if ( ! empty( $size_terms ) && ! is_wp_error( $size_terms ) ) :
        ?>
            <p class="product-size"><?php echo esc_html( $size_terms[0]->name ); ?></p>
        <?php endif; ?>

        <p class="product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>

        <div class="product-line"></div>

        <div class="product-add-to-cart">
            <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>
    </div>
</div>
