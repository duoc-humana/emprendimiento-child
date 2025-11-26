<?php get_header(); ?>

<div class="container">
    <h1>Resultados de búsqueda</h1>

    <?php if ( have_posts() ) : ?>
        <div class="productos">
            <?php while ( have_posts() ) : the_post(); global $product; ?>
                <div class="producto">
                    <!-- Imagen -->
                    <a href="<?php the_permalink(); ?>">
                        <?php echo $product->get_image( 'woocommerce_thumbnail'); ?>
                    </a>

                    <!-- Título -->
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

                    <!-- Precio -->
                    <span class="precio"><?php echo $product->get_price_html(); ?></span>

                    <!-- Descripción corta -->
                    <p><?php echo $product->get_short_description(); ?></p>

                    <!-- Botón comprar -->
                    <?php woocommerce_template_loop_add_to_cart(); ?>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <p>No se encontraron productos.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
