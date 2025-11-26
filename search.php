<?php get_header(); ?>

<div class="container mt-5">
    <h1>Resultados de búsqueda</h1>

    <?php if ( have_posts() ) : ?>
        <?php $productos_validos = 0; ?>
        <div class="row py-5">
            <?php while ( have_posts() ) : the_post(); global $product; ?>

                <?php 
                // Saltar si no es producto
                if ( get_post_type() !== 'product' ) {
                    continue;
                }

                // Saltar si no está publicado 
                if ( get_post_status() !== 'publish' ) {
                    continue;
                }
                $product = wc_get_product( get_the_ID() );

                //Contador
                 $productos_validos++; 
                ?>
                <div class="product-item col-3 px-5 py-5">
                    <!-- Imagen -->
                    <a href="<?php echo esc_url(get_permalink()); ?>">
                        <?php
                            if (has_post_thumbnail()) {
                                the_post_thumbnail('woocommerce_thumbnail', array('class' => 'product-img'));
                            } else {
                                echo '<img src="' . wc_placeholder_img_src() . '" class="product-img" alt="placeholder">';
                            }
                        ?>
                    </a>

                    <!-- Título -->
                    <h2 class="product-size"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

                    <!-- Precio -->
                    <span class="product-price"><?php echo $product->get_price_html(); ?></span>

                    <div class="product-line"></div>

                    <!-- Botón comprar -->
                    <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="add-cart-hover" data-quantity="1" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
                    rel="nofollow">Comprar</a>
                </div>
            <?php endwhile; ?>
            <?php if ( $productos_validos === 0 ) : ?>
            <div class="col-12 py-5">
                <h2>No se encontraron productos.</h2>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php else : ?>

    <h2>No se encontraron productos.</h2>

    <?php endif; ?>

<?php get_footer(); ?>
