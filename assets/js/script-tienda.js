(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        console.log('Script tienda cargado');
        
        // Función para mostrar notificación
        function mostrarNotificacion(mensaje) {
            var $notificacion = $('<div class="alert alert-success alert-dismissible fade show custom-cart-notification" role="alert">' +
                '<strong>' + mensaje + '</strong>' +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
            '</div>');
            
            $('body').append($notificacion);
            
            setTimeout(function() {
                $notificacion.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
        
        // IMPORTANTE: Solo capturar clics en botones específicos, NO en todos los enlaces
        $(document).on('click', '.add-cart-hover', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Evitar que el evento se propague
            
            var $button = $(this);
            var product_id = $button.attr('data-product_id');
            
            console.log('ID del producto:', product_id);
            
            if (!product_id) {
                console.error('Error: No se encontró el ID del producto');
                return;
            }
            
            var originalText = $button.text();
            $button.text('Agregando...').prop('disabled', true);
            
            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id: product_id,
                    quantity: 1
                },
                success: function(response) {
                    console.log('Respuesta:', response);
                    
                    if (response.error) {
                        $button.text('Error').prop('disabled', false);
                        mostrarNotificacion('❌ Error al agregar al carrito');
                    } else {
                        $button.text('✓ Agregado');
                        mostrarNotificacion('✓ Producto agregado al carrito correctamente');
                        
                        // Actualizar fragmentos del carrito
                        $(document.body).trigger('wc_fragment_refresh');
                        
                        setTimeout(function() {
                            $button.text(originalText).prop('disabled', false);
                        }, 2000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', error);
                    $button.text('Error').prop('disabled', false);
                    mostrarNotificacion('❌ Error de conexión');
                }
            });
        });
        
        // Evento cuando se agrega desde single product
        $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
            console.log('Producto agregado desde single product');
            mostrarNotificacion('✓ Producto agregado al carrito correctamente');
        });
        
    });
    
})(jQuery);

// Función para cambiar imagen en galería de producto
function changeImage(src) {
    var mainImage = document.getElementById('mainImage');
    if (mainImage) {
        mainImage.src = src;
    }
}

// Botones de cantidad en el carrito (solo si estamos en el carrito)
jQuery(document).ready(function($) {
    
    if (!$('.woocommerce-cart').length) {
        return; // No ejecutar si no estamos en el carrito
    }
    
    // Convertir input de cantidad en botones +/-
    $('.woocommerce-cart-form .quantity').each(function() {
        var $quantity = $(this);
        var $input = $quantity.find('input.qty');
        
        if ($input.length === 0) return;
        
        var currentVal = parseInt($input.val()) || 1;
        var min = parseInt($input.attr('min')) || 0;
        var max = parseInt($input.attr('max')) || 999;
        
        $input.wrap('<div class="quantity-wrapper"></div>');
        var $wrapper = $input.parent();
        
        $wrapper.prepend('<button type="button" class="qty-btn minus">−</button>');
        $wrapper.append('<button type="button" class="qty-btn plus">+</button>');
        
        $input.attr('readonly', true);
    });
    
    // Botón menos
    $(document).on('click', '.woocommerce-cart-form .minus', function(e) {
        e.preventDefault();
        var $input = $(this).siblings('input.qty');
        var currentVal = parseInt($input.val()) || 1;
        var min = parseInt($input.attr('min')) || 0;
        
        if (currentVal > min) {
            $input.val(currentVal - 1).trigger('change');
            $('button[name="update_cart"]').prop('disabled', false);
        }
    });
    
    // Botón más
    $(document).on('click', '.woocommerce-cart-form .plus', function(e) {
        e.preventDefault();
        var $input = $(this).siblings('input.qty');
        var currentVal = parseInt($input.val()) || 1;
        var max = parseInt($input.attr('max')) || 999;
        
        if (currentVal < max) {
            $input.val(currentVal + 1).trigger('change');
            $('button[name="update_cart"]').prop('disabled', false);
        }
    });
    
});