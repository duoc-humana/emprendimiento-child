//links activos

document.addEventListener('DOMContentLoaded', () => {
    let links = document.querySelectorAll("current-menu-item")
    const currentPath = window.location.pathname;

    links.forEach(link => {
        const linkPath = new URL(link.href).pathname;

        if (linkPath === currentPath) {
            link.classList.add('link-activo');
        }
    });
});

(function($) {
    'use strict';

    $(document).ready(function() {
        console.log('Script del carrito cargado');

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
                $notificacion.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }

        // Agregar al carrito desde hover
        $(document).on('click', '.add-cart-hover', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $button = $(this);
            const product_id = $button.data('product_id');

            if (!product_id) {
                console.error('Error: No se encontró el ID del producto');
                return;
            }

            const originalText = $button.text();
            $button.text('Agregando...').prop('disabled', true);

            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id,
                    quantity: 1
                },
                success: function(response) {
                    if (response.error) {
                        $button.text('Error').prop('disabled', false);
                        mostrarNotificacion('❌ Error al agregar al carrito');
                    } else {
                        $button.text('✓ Agregado');
                        mostrarNotificacion('✓ Producto agregado al carrito correctamente');
                        $(document.body).trigger('wc_fragment_refresh');

                        setTimeout(() => {
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

        // Notificación desde single product
        $(document.body).on('added_to_cart', function() {
            mostrarNotificacion('✓ Producto agregado al carrito correctamente');
        });

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

       /*  // Notificación al agregar producto (desde single o AJAX)
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
        } */
    });
})(jQuery);
