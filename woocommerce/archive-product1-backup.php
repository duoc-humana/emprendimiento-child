<?php
/**
 * The Template for displaying product archives
 * 
 * Colocar en: /wp-content/themes/tu-tema-hijo/woocommerce/archive-product.php
 * 
 * Esto es la distribucion de la tienda, solo distribucion 
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>

<div class="productos-container">
    
    <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
        <h1 class="productos-titulo"><?php woocommerce_page_title(); ?></h1>
    <?php endif; ?>

    <div class="productos-layout">
        
        <!-- Sidebar de filtros -->
        <aside class="productos-sidebar">
            <?php dynamic_sidebar( 'shop-sidebar' ); ?>
        </aside>

        <!-- Área principal de productos -->
        <div class="productos-main">
            
            <?php if ( woocommerce_product_loop() ) : ?>

                <?php
                /**
                 * Hook: woocommerce_before_shop_loop
                 */
                do_action( 'woocommerce_before_shop_loop' );
                ?>

                <div class="productos-grid">
                    <?php
                    if ( wc_get_loop_prop( 'total' ) ) {
                        while ( have_posts() ) {
                            the_post();
                            
                            /**
                             * Hook: woocommerce_shop_loop
                             */
                            do_action( 'woocommerce_shop_loop' );
                            
                            wc_get_template_part( 'content', 'product' );
                        }
                    }
                    ?>
                </div>

                <?php
                /**
                 * Hook: woocommerce_after_shop_loop
                 */
                do_action( 'woocommerce_after_shop_loop' );
                ?>

            <?php else : ?>
                
                <p class="no-productos">No se encontraron productos.</p>

            <?php endif; ?>

        </div>

    </div>

</div>

<?php get_footer( 'shop' ); ?>