(function($) {
  'use strict';

  $(document).ready(function() {
    console.log('Script tienda cargado');

    // Notificación simple
    function mostrarNotificacion(mensaje) {
      var $notificacion = $('<div class="custom-cart-notification">' + mensaje + '</div>');
      $('body').append($notificacion);
      setTimeout(function() {
        $notificacion.fadeOut(300, function() { $(this).remove(); });
      }, 3000);
    }

    // Botón personalizado añadir al carrito
    $(document).on('click', '.add-cart-hover', function(e) {
      e.preventDefault();
      var $button = $(this);
      var product_id = $button.attr('data-product_id');

      if (!product_id) return;

      var originalText = $button.text();
      $button.text('Agregando...').prop('disabled', true);

      $.post(wc_add_to_cart_params.ajax_url, {
        action: 'woocommerce_ajax_add_to_cart',
        product_id: product_id,
        quantity: 1
      })
      .done(function(response) {
        $button.text('✓ Agregado');
        mostrarNotificacion('✓ Producto agregado al carrito');
        $(document.body).trigger('wc_fragment_refresh');
        setTimeout(function() {
          $button.text(originalText).prop('disabled', false);
        }, 2000);
      })
      .fail(function() {
        $button.text('Error').prop('disabled', false);
        mostrarNotificacion('❌ Error de conexión');
      });
    });

    // Evento WooCommerce estándar
    $(document.body).on('added_to_cart', function() {
      mostrarNotificacion('✓ Producto agregado al carrito');
    });

    // Cantidad con + y -
    $('.woocommerce-cart-form .quantity input.qty').each(function() {
      var $input = $(this);

      // Evitar duplicar wrapper
      if ($input.parent('.quantity-wrapper').length) return;

      // Asegurar atributos
      if (!$input.attr('step')) $input.attr('step', '1');
      if (!$input.attr('min')) $input.attr('min', '0');

      $input.wrap('<div class="quantity-wrapper"></div>');
      var $wrapper = $input.parent();
      $wrapper.prepend('<button type="button" class="qty-btn qty-minus">−</button>');
      $wrapper.append('<button type="button" class="qty-btn qty-plus">+</button>');

      // Opcional: bloquear edición manual
      $input.attr('readonly', true);
    });

    // Botón menos
    $(document).on('click', '.qty-minus', function() {
      var $input = $(this).siblings('input.qty');
      var val = parseInt($input.val(), 10) || 1;
      var min = parseInt($input.attr('min'), 10) || 0;
      var step = parseInt($input.attr('step'), 10) || 1;

      if (val > min) {
        $input.val(val - step).trigger('change');
        triggerUpdate();
      }
    });

    // Botón más
    $(document).on('click', '.qty-plus', function() {
      var $input = $(this).siblings('input.qty');
      var val = parseInt($input.val(), 10) || 1;
      var max = parseInt($input.attr('max'), 10) || 999;
      var step = parseInt($input.attr('step'), 10) || 1;

      if (val + step <= max) {
        $input.val(val + step).trigger('change');
        triggerUpdate();
      }
    });

    // Actualizar carrito con debounce
    function triggerUpdate() {
      clearTimeout(window.cartUpdateTimer);
      window.cartUpdateTimer = setTimeout(function() {
        $('button[name="update_cart"]').prop('disabled', false).trigger('click');
      }, 500);
    }

    // Restaurar opacidad al actualizar totales
    $(document.body).on('updated_cart_totals', function() {
      console.log('Carrito actualizado');
    });

    // Quitar flechas de inputs tipo number (CSS inline fallback)
    $('input.qty').css({
      'MozAppearance': 'textfield',
      'appearance': 'textfield'
    }).on('focus', function(){
      this.blur(); // evita edición manual si quieres forzar solo botones
    });
  });
})(jQuery);
