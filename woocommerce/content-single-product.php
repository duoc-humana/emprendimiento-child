<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Colocar en: /wp-content/themes/tu-tema-hijo/woocommerce/content-single-product.php
 */

defined( 'ABSPATH' ) || exit;

global $product;

?>

<div class="container py-5">

    <div class="single-product-container">

        <!-- Imagen principal + galería -->
        <div>
            <div class="mb-3 imgprodsingl">
                <?php
                    // Imagen principal y galería
                    do_action( 'woocommerce_before_single_product_summary' );
                ?>
            </div>
        </div>

        <!-- Información -->
        <div>
            <p class="text-muted mb-4">
                <?php echo wc_get_product_category_list( get_the_ID() ); ?>
            </p>

            <h1 class="product-title mb-3">
                <?php woocommerce_template_single_title(); ?>
            </h1>

          
            <p class="mt-3 mb-5">
                <?php woocommerce_template_single_excerpt(); ?>
            </p>

            <div class="mt-4 fw-bold mb-5 ">
                <?php woocommerce_template_single_price(); ?>
            </div>

            <!-- Cantidad + Comprar -->
            <div class="d-flex align-items-center mt-3 gap-3">
                <?php woocommerce_template_single_add_to_cart(); ?>
            </div>
            <p class="mt-2 small text-muted">¿Eres empresa?</p>

            <div class="mt-5 especificaciones-container">
                <div class="especificaciones-header" data-bs-toggle="collapse" data-bs-target="#specs">
                    <span>Especificaciones</span>
                    <i class="fa-solid fa-angle-down" style="color: #6ca9cf;"></i>
                </div>

                <div class="collapse mt-3" id="specs">
                    <div class="card card-body">
                        <ul>
                            <?php woocommerce_product_additional_information_tab(); ?>
                        </ul>
                    </div>
                </div>

             </div>
        </div>
    </div>

    <!-- Productos similares -->
    <div class="mt-5">
        <h3 class="fw-bold mb-4">Productos similares</h3>

        <div class="row g-4 justify-content-center">

            <?php
            // Obtener productos relacionados
            $related_ids = wc_get_related_products( $product->get_id(), 3 );
            
            if ( $related_ids ) :
                foreach ( $related_ids as $related_id ) :
                    $related_product = wc_get_product( $related_id );
                    if ( ! $related_product ) continue;
            ?>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-item">
                        <a href="<?php echo esc_url( get_permalink( $related_id ) ); ?>">
                            <?php
                            if ( has_post_thumbnail( $related_id ) ) {
                                echo get_the_post_thumbnail( $related_id, 'woocommerce_thumbnail', array( 'class' => 'product-img' ) );
                            } else {
                                echo '<img src="' . wc_placeholder_img_src() . '" class="product-img" alt="placeholder">';
                            }
                            ?>
                        </a>

                        <div class="product-info">
                            <h4 class="product-name"><?php echo esc_html( $related_product->get_name() ); ?></h4>
                            
                            <?php
                            $size_terms = wp_get_post_terms( $related_id, 'pa_size' );
                            if ( ! empty( $size_terms ) && ! is_wp_error( $size_terms ) ) :
                            ?>
                                <p class="product-size"><?php echo esc_html( $size_terms[0]->name ); ?></p>
                            <?php endif; ?>
                            
                            <p class="product-price"><?php echo $related_product->get_price_html(); ?></p>

                            <div class="product-line"></div>

                            <a href="<?php echo esc_url( get_permalink( $related_id ) ); ?>">
                                <button class="add-cart-hover" 
                                    data-product_id="<?php echo esc_attr( $related_id ); ?>">
                                    Añadir al carrito
                                </button>
                            </a>
                        </div>
                    </div>
                </div>

            <?php
                endforeach;
            endif;
            ?>

        </div>
    </div>

</div>