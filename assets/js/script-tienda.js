(function($) {
    'use strict';

    $(document).ready(function() {
        console.log('Script del carrito cargado');

        // Botones de cantidad en carrito personalizado
        $(document).on('click', '.carrito-item-cantidad .qty-btn.minus', function(e) {
            e.preventDefault();
            const $input = $(this).closest('.quantity-wrapper').find('input.qty');
            const min = parseInt($input.attr('min')) || 1;
            const currentVal = parseInt($input.val()) || 1;

            if (currentVal > min) {
                $input.val(currentVal - 1).trigger('change');
                triggerCarritoUpdate();
            }
        });

        $(document).on('click', '.carrito-item-cantidad .qty-btn.plus', function(e) {
            e.preventDefault();
            const $input = $(this).closest('.quantity-wrapper').find('input.qty');
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

        
      
    });
})(jQuery);

