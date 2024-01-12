$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name=csrf_token]').attr('content')
  }
})

$(document).on('submit', '#order-form', function (e) {
  e.preventDefault();
  const submit_btn = $(this).find('button[type="submit"]');

  $.ajax({
    url: $(this).attr('action'),
    method: "POST",
    data: {
      address: $(this).find('[name=address]').val(),
      phone: $(this).find('[name=phone]').val(),
      city: $(this).find('[name=city]').val(),
      street: $(this).find('[name=street]').val(),
      hay: $(this).find('[name=hay]').val(),
      payment_method: $(this).find('[name=payment_method]:checked').val(),
    },
    beforeSend: () => {
      $(this).find('.error-message').hide()
      submit_btn.prop('disabled', true)
    },
    success: r => {
      window.location.replace(
        r.redirect_url
      )
    },
    error: err => {
      let error = ''

      Object.keys(err.responseJSON.errors).map(item => {
        error += err.responseJSON.errors[item][0] + '<br />';
      })

      $(this).find('.error-message').html(error)
      $(this).find('.error-message').show()
    },
    complete: function () {
      submit_btn.prop('disabled', false)
    },
  })
})

$(document).on('change', '[name=payment_method]', function (e) {
  const { value } = e.target
  const myfatoorah_fees = 0.02;
  let product_fees = 0;

  if (value == 'credit_card') {
    product_fees = parseFloat($('#product_price').val()) * 0.02
  }

  $('#order-form > div:nth-child(8) > table > tbody > tr:nth-child(2) > td:nth-child(2)').text(
    product_fees + 'ر.س'
  )

  $('#order-form > div:nth-child(8) > table > tbody > tr:nth-child(4) > td:nth-child(2)').text(
    (parseFloat($('#product_price').val()) + product_fees) + 'ر.س'
  )
})

$(document).on('click', '.add_to_cart', function (e) {
  e.preventDefault();
  e.stopPropagation();

  const product_id = $(this).data('product-id')

  $.ajax({
    url: '/cart',
    method: "POST",
    data: {
      product_id: product_id
    },
    success: r => {
      $(this).removeClass('add_to_cart')
      $(this).addClass('remove_from_cart')
      $(this).addClass('btn-warning')
      $(this).removeClass('btn-success')
      $(this).html(`<i class="bi bi-cart-dash ms-1"></i>ازالة للسلة`)
      $('.cart-count').text(r.cart_count)
    }
  })
})

$(document).on('click', '.remove_from_cart', function (e) {
  e.preventDefault();
  e.stopPropagation();

  const product_id = $(this).data('product-id')

  $.ajax({
    url: '/cart',
    method: "DELETE",
    data: {
      product_id: product_id
    },
    success: r => {
      if ($(this).hasClass('remove-item')) {
        $(this).parents('.parent-item').remove()
        window.location.reload()
      }
      else {
        $(this).removeClass('remove_from_cart')
        $(this).addClass('add_to_cart')
        $(this).removeClass('btn-warning')
        $(this).addClass('btn-success')
        $(this).html(`<i class="bi bi-cart-plus ms-1"></i>إضافة للسلة`)
      }

      $('.cart-count').text(r.cart_count)
    }
  })
})
