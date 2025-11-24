<?php
/**
 * Template para páginas individuales
 */

get_header(); ?>

<?php
while ( have_posts() ) :
    the_post();
    
    // Para páginas de WooCommerce, solo mostrar el contenido sin wrappers
    if ( function_exists('is_cart') && is_cart() ) {
        the_content();
    } 
    elseif ( function_exists('is_checkout') && is_checkout() ) {
        the_content();
    }
    elseif ( function_exists('is_account_page') && is_account_page() ) {
        the_content();
    }
    // Otras páginas normales
    else {
        ?>
        <div class="container py-5">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php if ( !is_front_page() ) : ?>
                    <header class="entry-header mb-4">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    </header>
                <?php endif; ?>
                
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        </div>
        <?php
    }
    
endwhile;
?>

<?php get_footer(); ?>