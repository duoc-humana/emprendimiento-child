<?php get_header(); ?>

<div class="container">
    <h1>Resultados de búsqueda</h1>

    <?php if ( have_posts() ) : ?>
        <div class="productos">
            <?php while ( have_posts() ) : the_post(); global $product; ?>
                <div class="product-item">
                    <!-- Imagen -->
                    <a href="<?php the_permalink(); ?>">
                        <?php echo $product->get_image( 'woocommerce_thumbnail', 'full', array( 'class' => 'product-img' )); ?>
                    </a>

                    <!-- Título -->
                    <h2 class="product-size"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

                    <!-- Precio -->
                    <span class="product-price"><?php echo $product->get_price_html(); ?></span>

                    <!-- Descripción corta -->
                    <p><?php echo $product->get_short_description(); ?></p>

                    <!-- Botón comprar -->
                    <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="add-cart-hover" data-quantity="1" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
                    rel="nofollow">Comprar</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <p>No se encontraron productos.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
