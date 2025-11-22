(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        console.log('Script cargado');
        
        // Función para mostrar notificación
        function mostrarNotificacion(mensaje) {
            // Crear notificación
            var $notificacion = $('<div class="alert alert-success alert-dismissible fade show custom-cart-notification" role="alert">' +
                '<strong>' + mensaje + '</strong>' +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
            '</div>');
            
            // Agregar al body
            $('body').append($notificacion);
            
            // Remover después de 3 segundos
            setTimeout(function() {
                $notificacion.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
        
        // Click en botón personalizado (del loop de productos)
        $(document).on('click', '.add-cart-hover', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var product_id = $button.attr('data-product_id');
            
            console.log('ID del producto:', product_id);
            
            if (!product_id) {
                alert('Error: No se encontró el ID del producto');
                return false;
            }
            
            // Guardar texto original
            var originalText = $button.text();
            
            // Cambiar texto
            $button.text('Agregando...').prop('disabled', true);
            
            // Hacer petición AJAX
            $.post(
                wc_add_to_cart_params.ajax_url,
                {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id: product_id,
                    quantity: 1
                },
                function(response) {
                    console.log('Respuesta:', response);
                    
                    if (response.error) {
                        $button.text('Error').prop('disabled', false);
                        alert('Error al agregar al carrito');
                    } else {
                        // Éxito
                        $button.text('✓ Agregado');
                        
                        // Mostrar notificación
                        mostrarNotificacion('✓ Producto agregado al carrito correctamente');
                        
                        // Actualizar carrito
                        $(document.body).trigger('wc_fragment_refresh');
                        
                        // Restaurar botón después de 2 segundos
                        setTimeout(function() {
                            $button.text(originalText).prop('disabled', false);
                        }, 2000);
                    }
                }
            ).fail(function(xhr, status, error) {
                console.error('Error AJAX:', error);
                $button.text('Error').prop('disabled', false);
                alert('Error de conexión');
            });
            
            return false;
        });
        
        // Evento para el botón de la página single product
        $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
            console.log('Producto agregado desde single product');
            
            // Mostrar notificación
            mostrarNotificacion('✓ Producto agregado al carrito correctamente');
        });
        
    });
    
})(jQuery);
$('form.cart').on('submit', function() {
    // Esperar un poco para que se procese
    setTimeout(function() {
        mostrarNotificacion('✓ Producto agregado al carrito correctamente');
    }, 500);
});

function changeImage(src) {
    document.getElementById('mainImage').src = src;
}

/**
 * Botones de cantidad en el carrito
 * Guarda en: tu-tema/js/cart-quantity.js
 */

jQuery(document).ready(function($) {
    
    // Convertir input de cantidad en botones +/-
    $('.carrito-item-cantidad .quantity').each(function() {
        var $quantity = $(this);
        var $input = $quantity.find('input.qty');
        
        // Crear estructura de botones
        var currentVal = parseInt($input.val());
        var min = parseInt($input.attr('min')) || 0;
        var max = parseInt($input.attr('max')) || 999;
        
        // Envolver input y agregar botones
        $input.wrap('<div class="quantity-wrapper"></div>');
        var $wrapper = $input.parent();
        
        $wrapper.prepend('<button type="button" class="qty-btn minus">-</button>');
        $wrapper.append('<button type="button" class="qty-btn plus">+</button>');
        
        // Hacer input readonly
        $input.attr('readonly', true);
    });
    
    // Botón menos
    $(document).on('click', '.carrito-item-cantidad .minus', function(e) {
        e.preventDefault();
        var $input = $(this).siblings('input.qty');
        var currentVal = parseInt($input.val());
        var min = parseInt($input.attr('min')) || 0;
        
        if (currentVal > min) {
            $input.val(currentVal - 1).trigger('change');
            actualizarCarrito();
        }
    });
    
    // Botón más
    $(document).on('click', '.carrito-item-cantidad .plus', function(e) {
        e.preventDefault();
        var $input = $(this).siblings('input.qty');
        var currentVal = parseInt($input.val());
        var max = parseInt($input.attr('max')) || 999;
        
        if (currentVal < max) {
            $input.val(currentVal + 1).trigger('change');
            actualizarCarrito();
        }
    });
    
    // Función para actualizar carrito automáticamente
    function actualizarCarrito() {
        $('button[name="update_cart"]').prop('disabled', false).trigger('click');
    }
    
});