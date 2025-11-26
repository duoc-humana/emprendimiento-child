(function($) {
    'use strict';

    $(document).ready(function() {
        console.log('Script del carrito cargado');

        // Botones de cantidad en carrito personalizado
        $(document).on('click', '.carrito-item-cantidad .qty-btn.minus', function(e) {
            e.preventDefault();
            const $input = $(this).siblings('input.qty');
            const min = parseInt($input.attr('min')) || 1;
            const currentVal = parseInt($input.val()) || 1;

            if (currentVal > min) {
                $input.val(currentVal - 1).trigger('change');
                triggerCarritoUpdate();
            }
        });

        $(document).on('click', '.carrito-item-cantidad .qty-btn.plus', function(e) {
            e.preventDefault();
            const $input = $(this).siblings('input.qty');
            const max = parseInt($input.attr('max')) || 999;
            const currentVal = parseInt($input.val()) || 1;

            if (currentVal < max) {
                $input.val(currentVal + 1).trigger('change');
                triggerCarritoUpdate();
            }
        });

        function triggerCarritoUpdate() {
            clearTimeout(window.cartUpdateTimer);
            window.cartUpdateTimer = setTimeout(() => {
                actualizarCarrito();
            }, 500);
        }

        function actualizarCarrito() {
            console.log('Actualizando carrito...');
            $('button[name="update_cart"]').prop('disabled', false).trigger('click');
        }

        // Notificación al agregar producto (desde single o AJAX)
        $(document.body).on('added_to_cart', function() {
            mostrarNotificacion('✓ Producto agregado al carrito correctamente');
        });

        // Función de notificación
        function mostrarNotificacion(mensaje) {
            const $notificacion = $(`
                <div class="alert alert-success alert-dismissible fade show custom-cart-notification" role="alert">
                    <strong>${mensaje}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);

            $('body').append($notificacion);

            setTimeout(() => {
                $notificacion.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
    });
})(jQuery);
