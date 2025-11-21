<?php
/**
 * Funciones WooCommerce - Agregar a functions.php
 */

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

// AJAX para añadir al carrito
function custom_ajax_add_to_cart() {
    check_ajax_referer('woocommerce-cart', 'security', false);
    
    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);
    
    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {
        do_action('woocommerce_ajax_added_to_cart', $product_id);
        
        if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }
        
        WC_AJAX::get_refreshed_fragments();
    } else {
        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
        );
        echo wp_send_json($data);
    }
    
    wp_die();
}
add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'custom_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'custom_ajax_add_to_cart');

// Habilitar AJAX add to cart en loop
add_filter('woocommerce_loop_add_to_cart_args', 'custom_add_to_cart_class', 10, 2);
function custom_add_to_cart_class($args, $product) {
    $args['class'] = implode(' ', array_filter(array(
        'button',
        'product_type_' . $product->get_type(),
        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
        $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
    )));
    $args['attributes']['data-product_id'] = $product->get_id();
    return $args;
}

// Soporte para thumbnails de WooCommerce
add_theme_support('woocommerce');
add_theme_support('wc-product-gallery-zoom');
add_theme_support('wc-product-gallery-lightbox');
add_theme_support('wc-product-gallery-slider');

// Forzar uso de plantillas personalizadas de WooCommerce
add_filter('woocommerce_locate_template', 'custom_woocommerce_locate_template', 10, 3);
function custom_woocommerce_locate_template($template, $template_name, $template_path) {
    $child_theme_path = get_stylesheet_directory() . '/woocommerce/';
    
    if (file_exists($child_theme_path . $template_name)) {
        $template = $child_theme_path . $template_name;
    }
    
    return $template;
}