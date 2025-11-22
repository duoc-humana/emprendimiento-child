<?php
// Cargar estilos del tema padre e hijo
function mi_tema_hijo_estilos() {

    // Estilo del tema padre
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    // Estilo del tema hijo (CSS compilado desde SCSS)
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/assets/scss/mi-estilo.css',
        array('parent-style'),
        '1.0.0'
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

    // Encolar tu script en páginas de tienda/categoría/etiqueta
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        wp_enqueue_script(
            'script-tienda',
            get_stylesheet_directory_uri() . '/assets/js/script-tienda.js',
            array( 'jquery', 'wc-add-to-cart', 'wc-cart-fragments' ), // 👈 dependencias añadidas
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
