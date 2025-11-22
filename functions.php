<?php
/**
 * Funciones del tema hijo Emprendimiento
 */

// --------------------------------------------------
// 1. Estilos del tema padre e hijo
// --------------------------------------------------
function mi_tema_hijo_estilos() {
    // Estilo del tema padre
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    // Estilo del hijo compilado desde SCSS
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/assets/scss/mi-estilo.css',
        array('parent-style'), // depende del padre
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'mi_tema_hijo_estilos', 99);

// --------------------------------------------------
// 2. Scripts del tema hijo + WooCommerce
// --------------------------------------------------
function custom_woo_scripts() {
    // jQuery ya incluido por WP, no es necesario encolar de nuevo

    // Scripts oficiales de WooCommerce para AJAX
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_script('wc-add-to-cart');
        wp_enqueue_script('wc-cart-fragments');
    }

    // Script personalizado para tienda, con dependencias de WooCommerce y jQuery
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        wp_enqueue_script(
            'script-tienda',
            get_stylesheet_directory_uri() . '/assets/js/script-tienda.js',
            array('jquery', 'wc-add-to-cart', 'wc-cart-fragments'),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'custom_woo_scripts');

// --------------------------------------------------
// 3. Sidebars personalizados para WooCommerce
// --------------------------------------------------
function custom_woo_register_sidebars() {
    $sidebars = array(
        'shop-sidebar' => 'Sidebar Tienda - General',
        'shop-sidebar-maceteros' => 'Sidebar Tienda - Maceteros',
        'shop-sidebar-confeccion' => 'Sidebar Tienda - Confección',
        'shop-sidebar-revestimiento' => 'Sidebar Tienda - Revestimiento',
    );

    foreach ($sidebars as $id => $name) {
        register_sidebar(array(
            'name'          => $name,
            'id'            => $id,
            'description'   => 'Widgets para ' . $name,
            'before_widget' => '<div class="widget %2$s mb-4">',
            'after_widget'  => '</div>',
            'before_title'  => '<h5 class="fw-bold mb-3">',
            'after_title'   => '</h5>',
        ));
    }
}
add_action('widgets_init', 'custom_woo_register_sidebars');

// --------------------------------------------------
// 4. Personalización WooCommerce
// --------------------------------------------------

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

// AJAX Add to Cart
function custom_ajax_add_to_cart() {
    check_ajax_referer('woocommerce-cart', 'security', false);

    $product_id = absint($_POST['product_id']);
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);

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

// --------------------------------------------------
// 5. Soporte WooCommerce
// --------------------------------------------------
function mi_tema_woocommerce_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'mi_tema_woocommerce_support');

// --------------------------------------------------
// 6. Plantillas WooCommerce en el tema hijo
// --------------------------------------------------
add_filter('woocommerce_locate_template', function($template, $template_name, $path) {
    $theme_template = get_stylesheet_directory() . '/woocommerce/' . $template_name;
    return file_exists($theme_template) ? $theme_template : $template;
}, 10, 3);

// --------------------------------------------------
// 7. Debug de URL del carrito (solo admins)
// --------------------------------------------------
add_action('wp_footer', function() {
    if (is_user_logged_in() && current_user_can('administrator')) {
        ?>
        <script>
        console.log('Cart URL:', '<?php echo esc_js(wc_get_cart_url()); ?>');
        console.log('Home URL:', '<?php echo esc_js(home_url()); ?>');
        console.log('Page ID 10:', '<?php echo esc_js(get_permalink(10)); ?>');
        </script>
        <?php
    }
});
