<?php

// 1. Quitar CSS principal del tema padre
function quitar_css_padre() {
    wp_dequeue_style('estilo');        // ID del CSS del padre
    wp_deregister_style('estilo');
}
add_action('wp_enqueue_scripts', 'quitar_css_padre', 20);


// 2. Cargar Bootstrap (CDN)
function cargar_librerias_base() {

    // Bootstrap
    wp_enqueue_style(
        'bootstrap-header',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css',
        array(),
        '5.3.8'
    );

    // Swiper
    wp_enqueue_style(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        array(),
        '11'
    );

    // Iconos (Font Awesome)
    wp_enqueue_style(
        'iconos',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );
}
add_action('wp_enqueue_scripts', 'cargar_librerias_base', 10);


// 3. Cargar tu CSS del tema hijo
function mi_tema_hijo_estilos() {
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/assets/scss/mi-estilo.css',
        array('bootstrap-header', 'swiper', 'iconos'), // dependencias
        time()
    );
}
add_action('wp_enqueue_scripts', 'mi_tema_hijo_estilos', 99);




// Asegurar jQuery
function cargar_jquery() {
    if (!is_admin()) {
        wp_enqueue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'cargar_jquery');

// Encolar JavaScript personalizado
function custom_woo_scripts() {
    // Scripts oficiales de WooCommerce necesarios para AJAX
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_script( 'wc-add-to-cart' );
        wp_enqueue_script( 'wc-cart-fragments' );
    }

    // Encolar tu script en tienda, categorías, etiquetas, carrito y checkout
    if ( is_shop() || is_product_category() || is_product_tag() || is_cart() || is_checkout() ) {
        wp_enqueue_script(
            'script-tienda',
            get_stylesheet_directory_uri() . '/assets/js/script-tienda.js',
            array( 'jquery', 'wc-add-to-cart', 'wc-cart-fragments' ), // dependencias añadidas
            '1.0.0',
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'custom_woo_scripts' );



// Registrar sidebar para widgets
/* -----------------------------------------------------------
   2. SIDEBAR WIDGETS - POR CATEGORÍA
----------------------------------------------------------- */

function custom_woo_register_sidebars() {
    
    // Sidebar general (por defecto)
    register_sidebar(array(
        'name'          => 'Sidebar Tienda - General',
        'id'            => 'shop-sidebar',
        'description'   => 'Widgets para todas las categorías',
        'before_widget' => '<div class="widget %2$s mb-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="fw-bold mb-3">',
        'after_title'   => '</h5>',
    ));
    
    // Sidebar para Maceteros
    register_sidebar(array(
        'name'          => 'Sidebar Tienda - Maceteros',
        'id'            => 'shop-sidebar-maceteros',
        'description'   => 'Widgets solo para categoría Maceteros',
        'before_widget' => '<div class="widget %2$s mb-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="fw-bold mb-3">',
        'after_title'   => '</h5>',
    ));
    
    // Sidebar para Confección
    register_sidebar(array(
        'name'          => 'Sidebar Tienda - Confección',
        'id'            => 'shop-sidebar-confeccion',
        'description'   => 'Widgets solo para categoría Confección',
        'before_widget' => '<div class="widget %2$s mb-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="fw-bold mb-3">',
        'after_title'   => '</h5>',
    ));
    
    // Sidebar para Revestimiento
    register_sidebar(array(
        'name'          => 'Sidebar Tienda - Revestimiento',
        'id'            => 'shop-sidebar-revestimiento',
        'description'   => 'Widgets solo para categoría Revestimiento',
        'before_widget' => '<div class="widget %2$s mb-4">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="fw-bold mb-3">',
        'after_title'   => '</h5>',
    ));
}
add_action('widgets_init', 'custom_woo_register_sidebars');

add_action( 'after_setup_theme', function() {
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
});
// Quitar el sidebar por defecto de WooCommerce
add_action( 'after_setup_theme', function() {
    remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
});


/* -----------------------------------------------------------
   3. WOOCOMMERCE – LIMPIEZA Y PERSONALIZACIÓN
----------------------------------------------------------- */

// Quitar elementos del loop
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

// Productos por página
add_filter('loop_shop_per_page', function() { return 9; }, 20);

// Quitar ordenamiento y contador
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);



/* -----------------------------------------------------------
   4. AJAX ADD TO CART
----------------------------------------------------------- */

function custom_ajax_add_to_cart() {

    check_ajax_referer('woocommerce-cart', 'security', false);

    $product_id      = absint($_POST['product_id']);
    $quantity        = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id    = absint($_POST['variation_id']);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);

    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id)) {

        do_action('woocommerce_ajax_added_to_cart', $product_id);
        WC_AJAX::get_refreshed_fragments();

    } else {

        wp_send_json(array(
            'error' => true,
            'product_url' => get_permalink($product_id),
        ));
    }

    wp_die();
}

add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'custom_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'custom_ajax_add_to_cart');



// Agregar clases correctas al botón del loop
add_filter('woocommerce_loop_add_to_cart_args', function($args, $product) {

    $args['class'] = implode(' ', array_filter(array(
        'button',
        'product_type_' . $product->get_type(),
        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
        $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
    )));

    $args['attributes']['data-product_id'] = $product->get_id();

    return $args;
}, 10, 2);



/* -----------------------------------------------------------
   5. SOPORTE WOOCOMMERCE
----------------------------------------------------------- */

function mi_tema_woocommerce_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'mi_tema_woocommerce_support');

// Debug: Verificar URL del carrito
add_action('wp_footer', 'debug_cart_url');
function debug_cart_url() {
    if (is_user_logged_in() && current_user_can('administrator')) {
        ?>
        <script>
        console.log('Cart URL:', '<?php echo esc_js(wc_get_cart_url()); ?>');
        console.log('Home URL:', '<?php echo esc_js(home_url()); ?>');
        console.log('Page ID 10:', '<?php echo esc_js(get_permalink(10)); ?>');
        </script>
        <?php
    }
}

add_filter( 'woocommerce_locate_template', function( $template, $template_name, $path ) {
    $theme_template = get_stylesheet_directory() . '/woocommerce/' . $template_name;
    return file_exists( $theme_template ) ? $theme_template : $template;
}, 10, 3 );

/* -----------------------------------------------------------
   6. PERSONALIZACIÓN CARRITO VACÍO
----------------------------------------------------------- */

// Cambiar el texto del botón "Return to shop" en carrito vacío
add_filter( 'gettext', 'recicla2_cambiar_texto_carrito_vacio', 20, 3 );
function recicla2_cambiar_texto_carrito_vacio( $translated_text, $text, $domain ) {
    if ( $domain === 'woocommerce' ) {
        switch ( $text ) {
            case 'Return to shop':
                $translated_text = 'Ir a tienda';
                break;
            case 'Your cart is currently empty.':
                $translated_text = 'Tu carrito se encuentra vacío';
                break;
        }
    }
    return $translated_text;
}

// Asegurar que la página de tienda redireccione correctamente
add_filter( 'woocommerce_return_to_shop_redirect', 'recicla2_custom_return_to_shop_url' );
function recicla2_custom_return_to_shop_url( $url ) {
    // Redirige a la página de tienda de WooCommerce
    if ( get_permalink( wc_get_page_id( 'shop' ) ) ) {
        return get_permalink( wc_get_page_id( 'shop' ) );
    }
    return $url;
}

// Agregar clase personalizada al body cuando el carrito está vacío
add_filter( 'body_class', 'recicla2_empty_cart_body_class' );
function recicla2_empty_cart_body_class( $classes ) {
    if ( is_cart() && WC()->cart->is_empty() ) {
        $classes[] = 'woocommerce-cart-empty';
    }
    return $classes;
}

//Activación de barra de búsqueda
function incluir_productos_en_busqueda( $query ) {
    if ( $query->is_search() && $query->is_main_query() && !is_admin() ) {
        //Filtra solo post de productos 
        $query->set( 'post_type', array( 'product' ) );
        // Excluir borradores
        $query->set( 'post_status', array( 'publish' ) ); 
    }
}
add_action( 'pre_get_posts', 'incluir_productos_en_busqueda' );

//Filtro de búsqueda
add_filter( 'posts_where', 'buscar_solo_en_titulo', 10, 2 );
function buscar_solo_en_titulo( $where, $query ) {
    global $wpdb;

    // Solo afectar búsquedas en el frontend
    if ( $query->is_search() && !is_admin() ) {

        $search = $query->get('s');

        if ( !empty($search) ) {
            $where = " AND {$wpdb->posts}.post_title LIKE '" . esc_sql( $wpdb->esc_like( $search ) ) . "%' ";
        }
    }

    return $where;
}

