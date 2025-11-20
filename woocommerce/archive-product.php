<?php
/**
 * The Template for displaying product archives
 * 
 * Colocar en: /wp-content/themes/tu-tema-hijo/woocommerce/archive-product.php
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>

<main class="container py-5">

    <!-- Título -->
    <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
        <h1 class="text-start mb-4 fw-bold ttprod"><?php woocommerce_page_title(); ?></h1>
    <?php endif; ?>

    <!-- Botones Categoría -->
    <div class="d-flex justify-content-center gap-3 mb-5 flex-wrap">
        <?php
        // Obtener las 3 categorías principales
        $product_categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => 0,
            'number' => 3,
        ));

        if (!empty($product_categories) && !is_wp_error($product_categories)) :
            $btn_classes = array('btn3', 'btn4', 'btn5');
            $index = 0;
            
            foreach ($product_categories as $category) :
                $category_link = get_term_link($category);
                ?>
                <div class="col-md-3">
                    <a href="<?php echo esc_url($category_link); ?>" class="<?php echo $btn_classes[$index]; ?>">
                        <?php echo esc_html($category->name); ?>
                    </a>
                </div>
                <?php if ($index < 2) : ?>
                    <div class="col-md-1"></div>
                <?php endif; ?>
                <?php
                $index++;
            endforeach;
        endif;
        ?>
    </div>

    <div class="row">
        
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
            <!-- Contador de productos -->
            <div class="product-count">
                <?php
                $total = wc_get_loop_prop('total');
                $per_page = wc_get_loop_prop('per_page');
                $current = wc_get_loop_prop('current_page');
                $showing_from = (($current - 1) * $per_page) + 1;
                $showing_to = min($current * $per_page, $total);
                ?>
                <p class="mb-0">Mostrando <?php echo $showing_from; ?>–<?php echo $showing_to; ?> de <?php echo $total; ?> resultados</p>
            </div>

            <!-- Ordenamiento -->
            <div class="product-ordering">
                <form class="woocommerce-ordering" method="get">
                    <select name="orderby" class="form-select w-auto" id="ordenarPrecio" onchange="this.form.submit()">
                        <?php
                        $catalog_orderby_options = array(
                            'menu_order' => 'Ordenar por defecto',
                            'date'       => 'Ordenar por más reciente',
                            'price'      => 'Precio: menor a mayor',
                            'price-desc' => 'Precio: mayor a menor',
                        );
                        
                        // Obtener el orden actual
                        if (isset($_GET['orderby'])) {
                            $orderby = wc_clean($_GET['orderby']);
                        } elseif (wc_get_loop_prop('is_search')) {
                            $orderby = 'relevance';
                        } else {
                            $orderby = apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby', 'menu_order'));
                        }
                        
                        foreach ($catalog_orderby_options as $id => $name) {
                            echo '<option value="' . esc_attr($id) . '" ' . selected($orderby, $id, false) . '>' . esc_html($name) . '</option>';
                        }
                        ?>
                    </select>
                    <input type="hidden" name="paged" value="1" />
                    <?php wc_query_string_form_fields(null, array('orderby', 'submit', 'paged', 'product-page')); ?>
                </form>
            </div>
        </div>

        <!-- ASIDE Sidebar -->
        <aside class="col-12 col-lg-3 mb-4 ">
            <?php dynamic_sidebar( 'shop-sidebar' ); ?>
        </aside>

        <!-- PRODUCTOS -->
        <section class="col-12 col-lg-9">

            <?php if ( woocommerce_product_loop() ) : ?>

                <?php
                /**
                 * Hook: woocommerce_before_shop_loop
                 */
                do_action( 'woocommerce_before_shop_loop' );
                ?>

                <div class="row g-4">

                    <?php
                    if ( wc_get_loop_prop( 'total' ) ) {
                        while ( have_posts() ) {
                            the_post();
                            
                            /**
                             * Hook: woocommerce_shop_loop
                             */
                            do_action( 'woocommerce_shop_loop' );
                            ?>
                            
                            <!-- Producto -->
                            <div class="col-6 col-md-4">
                                <?php wc_get_template_part( 'content', 'product' ); ?>
                            </div>

                            <?php
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
                
                <?php
                /**
                 * Hook: woocommerce_no_products_found
                 */
                do_action( 'woocommerce_no_products_found' );
                ?>

            <?php endif; ?>

        </section>

    </div>

</main>

<?php
get_footer( 'shop' );
?>