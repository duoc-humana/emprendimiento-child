<?php
/**
 * The Template for displaying all single products
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>

<div class="producto-single-container">
    
    <?php while ( have_posts() ) : ?>
        <?php the_post(); ?>

        <?php wc_get_template_part( 'content', 'single-product' ); ?>

    <?php endwhile; ?>

</div>

<?php get_footer( 'shop' ); ?>