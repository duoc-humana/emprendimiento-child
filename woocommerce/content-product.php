<?php
/**
 * The template for displaying product content within loops
 *
 * Colocar en: /wp-content/themes/tu-tema-hijo/woocommerce/content-product.php
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
?>
<div class="product-item">
    <a href="<?php echo esc_url(get_permalink()); ?>">
        <?php
            if (has_post_thumbnail()) {
                the_post_thumbnail('woocommerce_thumbnail', array('class' => 'product-img'));
            } else {
                echo '<img src="' . wc_placeholder_img_src() . '" class="product-img" alt="placeholder">';
            }
         ?>
    </a>

    <div class="product-info">
        <h4 class="product-name">
            <a href="<?php echo esc_url(get_permalink()); ?>">
                <?php the_title(); ?>
            </a>
        </h4>
          <?php
                    $product = wc_get_product( get_the_ID() );
                    $atributo = $product->get_attribute( 'tamanos' ); // cambia 'pa_marca' por tu atributo

                    if ( $atributo ) {
                        echo '<div class="product-atributo text-muted mb-3">' . esc_html( $atributo ) . '</div>';
                    }
                ?>
                                    
        <?php                          
            $size_terms = wp_get_post_terms(get_the_ID(), 'pa_size');
            if (!empty($size_terms) && !is_wp_error($size_terms)) :
        ?>
        <p class="product-size"><?php echo esc_html($size_terms[0]->name); ?></p>
        <?php endif; ?>
        
        <p class="product-price"><?php echo $product->get_price_html(); ?></p>
        
        <div class="product-line"></div>

        <button class="add-cart-hover" 
            data-product_id="<?php echo esc_attr($product->get_id()); ?>"
            data-quantity="1">
            Añadir al carrito
        </button>
    </div>
</div> 
