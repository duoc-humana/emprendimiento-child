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
    });
})(jQuery);

// Cambiar imagen principal en galería
function changeImage(src) {
    const mainImage = document.getElementById('mainImage');
    if (mainImage) {
        mainImage.src = src;
    }
}

// Botones de cantidad en carrito WooCommerce
jQuery(document).ready(function($) {
    if (!$('.woocommerce-cart').length) return;

    $('.woocommerce-cart-form .quantity').each(function() {
        const $input = $(this).find('input.qty');
        if (!$input.length) return;

        $input.wrap('<div class="quantity-wrapper"></div>');
        const $wrapper = $input.parent();

        $wrapper.prepend('<button type="button" class="qty-btn minus">−</button>');
        $wrapper.append('<button type="button" class="qty-btn plus">+</button>');
        $input.attr('readonly', true);
    });

    $(document).on('click', '.woocommerce-cart-form .minus', function(e) {
        e.preventDefault();
        const $input = $(this).siblings('input.qty');
        const min = parseInt($input.attr('min')) || 0;
        const currentVal = parseInt($input.val()) || 1;

        if (currentVal > min) {
            $input.val(currentVal - 1).trigger('change');
            $('button[name="update_cart"]').prop('disabled', false);
        }
    });

    $(document).on('click', '.woocommerce-cart-form .plus', function(e) {
        e.preventDefault();
        const $input = $(this).siblings('input.qty');
        const max = parseInt($input.attr('max')) || 999;
        const currentVal = parseInt($input.val()) || 1;

        if (currentVal < max) {
            $input.val(currentVal + 1).trigger('change');
            $('button[name="update_cart"]').prop('disabled', false);
        }
    });
});

// Botones de cantidad en carrito personalizado
jQuery(document).ready(function($) {
    if (!$('.carrito-page').length) return;

    console.log('Script del carrito cargado');

    $('.carrito-item-cantidad .quantity').each(function() {
        const $input = $(this).find('input.qty');
        if (!$input.length) return;

        $input.wrap('<div class="quantity-wrapper"></div>');
        const $wrapper = $input.parent();

        $wrapper.prepend('<button type="button" class="qty-btn qty-minus">−</button>');
        $wrapper.append('<button type="button" class="qty-btn qty-plus">+</button>');
        $input.attr('readonly', true);
    });

    $(document).on('click', '.carrito-item-cantidad .qty-minus', function(e) {
        e.preventDefault();
        const $input = $(this).siblings('input.qty');
        const min = parseInt($input.attr('min')) || 0;
        const currentVal = parseInt($input.val()) || 1;

        if (currentVal > min) {
            $input.val(currentVal - 1).trigger('change');
            triggerCarritoUpdate();
        }
    });

    $(document).on('click', '.carrito-item-cantidad .qty-plus', function(e) {
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
        }, 800);
    }

    function actualizarCarrito() {
        console.log('Actualizando carrito...');
        $('button[name="update_cart"]').prop('disabled', false).trigger('click');
        $('.carrito-resumen-box').css('opacity', '0.6');

        $(document.body).on('updated_cart_totals', function() {
            $('.carrito-resumen-box').css('opacity', '1');
            console.log('Carrito actualizado');
        });
    }

    $('.woocommerce-cart-form').on('submit', function(e) {
        if (e.originalEvent?.submitter) {
            const submitter = e.originalEvent.submitter;
            if (!$(submitter).is('[name="apply_coupon"], [name="update_cart"]')) {
                e.preventDefault();
            }
        }
    });
});
