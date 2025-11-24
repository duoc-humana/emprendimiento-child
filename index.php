<!-- <?php get_header(); ?>

<main id="primary" class="site-main">
    <?php
    if ( have_posts() ) {
        while ( have_posts() ) {
            the_post();
            the_content(); // Aquí se imprime el contenido de la página (incluye el bloque del carrito)
        }
    }
    ?>
</main>

<?php get_footer(); ?> -->

<?php
/**
 * Template fallback principal
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container py-5">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>
                    <header class="entry-header mb-3">
                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                    </header>
                    
                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div>
                    
                    <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                        Leer más
                    </a>
                </article>
                <?php
            endwhile;
            
            // Paginación
            the_posts_pagination();
            
        else :
            ?>
            <p>No se encontró contenido.</p>
            <?php
        endif;
        ?>
    </div>
</main>

<?php get_footer(); ?>
