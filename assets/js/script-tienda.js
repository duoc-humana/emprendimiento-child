
(function($) {
    'use strict';

    $(document).ready(function() {
        console.log('Script tienda cargado');

        // Mostrar notificación
        function mostrarNotificacion(mensaje) {
            const $notificacion = $(`
                <div class="alert alert-success alert-dismissible fade show custom-cart-notification" role="alert">
                    <strong>${mensaje}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
            $('body').append($notificacion);
            setTimeout(() => {
                $notificacion.fadeOut(300, function() { $(this).remove(); });
            }, 3000);
        }

        // Agregar al carrito desde hover
        $(document).on('click', '.add-cart-hover', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $button = $(this);
            const product_id = $button.data('product_id');
            if (!product_id) return;

            const originalText = $button.text();
            $button.text('Agregando...').prop('disabled', true);

            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: { action: 'woocommerce_ajax_add_to_cart', product_id, quantity: 1 },
                success: function(response) {
                    if (response.error) {
                        $button.text('Error').prop('disabled', false);
                        mostrarNotificacion('❌ Error al agregar al carrito');
                    } else {
                        $button.text('✓ Agregado');
                        mostrarNotificacion('✓ Producto agregado al carrito correctamente');
                        $(document.body).trigger('wc_fragment_refresh');
                        setTimeout(() => { $button.text(originalText).prop('disabled', false); }, 2000);
                    }
                },
                error: function() {
                    $button.text('Error').prop('disabled', false);
                    mostrarNotificacion('❌ Error de conexión');
                }
            });
        });

        $(document.body).on('added_to_cart', function() {
            mostrarNotificacion('✓ Producto agregado al carrito correctamente');
        });
    });
})(jQuery);


// Cambiar imagen principal en galería
function changeImage(src) {
    const mainImage = document.getElementById('mainImage');
    if (mainImage) mainImage.src = src;
}


/* ============================================
   CARRITO - BOTONES DE CANTIDAD
   ============================================ */
jQuery(document).ready(function($) {
    
    // Solo ejecutar en página de carrito
    if (!$('.carrito-page').length && !$('.woocommerce-cart').length) return;
    
    console.log('Script del carrito inicializado');

    // Variable para evitar múltiples actualizaciones simultáneas
    let isUpdating = false;

    // Función para inicializar los botones +/-
    function initQuantityButtons() {
        console.log('Inicializando botones de cantidad...');
        
        // Limpiar botones existentes primero
        $('.qty-btn').remove();
        
        // Seleccionar todos los inputs de cantidad en el carrito
        $('.carrito-item-cantidad input.qty, .woocommerce-cart-form input.qty').each(function() {
            const $input = $(this);
            
            // Si ya está dentro de un wrapper, unwrap primero
            if ($input.parent().hasClass('quantity-wrapper')) {
                $input.unwrap();
            }
            
            // Crear el wrapper y los botones
            $input.wrap('<div class="quantity-wrapper"></div>');
            const $wrapper = $input.parent();
            
            // Agregar botones FUERA del input
            $wrapper.prepend('<button type="button" class="qty-btn qty-minus">−</button>');
            $wrapper.append('<button type="button" class="qty-btn qty-plus">+</button>');
            
            // Hacer el input readonly para que solo se use con botones
            $input.attr('readonly', true);
        });
        
        console.log('Botones inicializados:', $('.qty-btn').length);
    }

    // Inicializar botones al cargar
    initQuantityButtons();

    // Reinicializar después de actualizaciones del carrito
    $(document.body).on('updated_cart_totals updated_wc_div', function() {
        console.log('Carrito actualizado, reinicializando botones');
        setTimeout(function() {
            initQuantityButtons();
            isUpdating = false;
            $('.carrito-resumen-box, .cart_totals').css('opacity', 1);
        }, 100);
    });

    // Función para actualizar el carrito
    function actualizarCarrito() {
        if (isUpdating) {
            console.log('Ya hay una actualización en curso...');
            return;
        }
        
        isUpdating = true;
        console.log('Actualizando carrito...');
        
        // Añadir efecto visual
        $('.carrito-resumen-box, .cart_totals').css('opacity', 0.6);
        
        // Encontrar y hacer clic en el botón de actualizar carrito
        const $updateButton = $('button[name="update_cart"]');
        
        if ($updateButton.length) {
            $updateButton.prop('disabled', false).trigger('click');
        } else {
            console.error('No se encontró el botón update_cart');
            isUpdating = false;
            $('.carrito-resumen-box, .cart_totals').css('opacity', 1);
        }
    }

    // Event listener para botones +/-
    $(document).on('click', '.qty-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (isUpdating) {
            console.log('Actualización en curso, esperando...');
            return;
        }
        
        const $button = $(this);
        const $input = $button.siblings('input.qty');
        
        if (!$input.length) {
            console.error('No se encontró el input de cantidad');
            return;
        }
        
        const min = parseInt($input.attr('min')) || 0;
        const max = parseInt($input.attr('max')) || 999;
        let val = parseInt($input.val()) || 1;
        
        console.log('Valor actual:', val, 'Min:', min, 'Max:', max);
        
        // Aumentar o disminuir
        if ($button.hasClass('qty-minus') && val > min) {
            val--;
        } else if ($button.hasClass('qty-plus') && val < max) {
            val++;
        } else {
            console.log('No se puede cambiar más (límite alcanzado)');
            return;
        }
        
        console.log('Nuevo valor:', val);
        
        // Actualizar el valor
        $input.val(val).trigger('change');
        
        // Actualizar carrito después de un pequeño delay
        setTimeout(actualizarCarrito, 300);
    });

    // Prevenir submit accidental del formulario
    $('.woocommerce-cart-form').on('submit', function(e) {
        // Solo permitir submit si es desde botón de cupón o actualizar carrito
        if (e.originalEvent && e.originalEvent.submitter) {
            const submitter = e.originalEvent.submitter;
            const isValidSubmit = $(submitter).is('[name="apply_coupon"], [name="update_cart"]');
            
            if (!isValidSubmit) {
                console.log('Submit prevenido (no es botón válido)');
                e.preventDefault();
                return false;
            }
        }
    });

    // Actualizar cuando WooCommerce refresque los fragments
    $(document.body).on('wc_fragments_refreshed', function() {
        console.log('Fragments refrescados');
        $('.carrito-resumen-box, .cart_totals').css('opacity', 1);
        isUpdating = false;
    });

    // Backup: Si después de 3 segundos sigue bloqueado, desbloquearlo
    setInterval(function() {
        if (isUpdating) {
            console.log('Comprobando estado de actualización...');
            // Si ha pasado mucho tiempo, resetear
            setTimeout(function() {
                if (isUpdating) {
                    console.warn('Actualización tomó mucho tiempo, reseteando...');
                    isUpdating = false;
                    $('.carrito-resumen-box, .cart_totals').css('opacity', 1);
                }
            }, 5000);
        }
    }, 1000);
});