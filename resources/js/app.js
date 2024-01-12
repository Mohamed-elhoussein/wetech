import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window._ = require('lodash');

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "463710d4b4941fc20a78",
    cluster: "eu",
    forceTLS: true
});

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}






window.axios = require('axios');



window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';



import Swal from 'sweetalert2';
import $ from 'jquery';
import Alpine from 'alpinejs'
import axios from 'axios';
window.Alpine = Alpine
Alpine.start()

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name=csrf_token]').attr('value')
    }
})



$('a.delete').click(function(event) {

    var href = this.href;

    const deleteForm = $($(this).data('delete-form'))

    event.preventDefault();

    Swal.fire({
        title: 'هل أنت متأكد ',
        text: "بعد الضغط على نعم لا يمكن التراجع",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم',
        cancelButtonText: 'لا'
    }).then((result) => {
        if (result.isConfirmed) {
            if (deleteForm.length) {
                console.log(deleteForm)
                deleteForm.submit()
            } else {
                window.location = href;
            }
        }
    })
});

$('a.accept').click(function(event) {

    var href = this.href;


    event.preventDefault();
    result = Swal.fire({
        title: 'هل أنت متأكد ',
        text: "بعد الضغط على نعم لا يمكن التراجع",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم',
        cancelButtonText: 'لا'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = href;
        }
    })
});
$('a.Reject').click(function(event) {

    var href = this.href;


    event.preventDefault();
    result = Swal.fire({
        title: 'هل أنت متأكد ',
        text: "بعد الضغط على نعم لا يمكن التراجع",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم',
        cancelButtonText: 'لا'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = href;
        }
    })
});

$('a.verified').click(function(event) {

    let provider_id = $(event.target).parent().attr('data-href').split('/')[3];

    event.preventDefault();
    axios.get('/api/provider/identity/' + provider_id)
        .then(function(response) {
            let identities = response.data;

            Swal.fire({
                title: '<u style="font-weight: bold;font-size: larger;" >الهوية</u>',
                type: 'info',
                html: identities,
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'قبول',
                denyButtonText: `رفض`,
                cancelButtonText: 'إغلاق'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.get('/api/provider/verified/' + provider_id)
                        .then(function(response) {
                            location.reload(true);
                        })
                        .catch(function(error) {

                        });
                } else if (result.isDenied) {
                    axios.get('/api/provider/unverified/' + provider_id)
                        .then(function(response) {
                            location.reload(true);
                        })
                        .catch(function(error) {

                        });
                }
            });

        })
        .catch(function(error) {

        });

});

$("#service").change(function(e) { getCategoriesServices(e); });
$("#service_category").change(function(e) { getSubcategoriesServices(e); });
$("#service_subcategory").change(function(e) { getSub2(e); });
$("#service_sub2").change(function(e) { getSub3(e); });
$(document).on('change', '#countries', function(e) { getCities(e) })
$('#cities').change(function(e) { getStreet(e) })
$('#product_type').change(function(e) { getBrands(e) })

function getCategoriesServices(e) {

    let service_id = e.target.value;
    let services_option = '<option value="">إختر تصنيف الخدمة </option>';
    axios.get('/api/services/categories/' + service_id)
        .then(function(response) {
            let services = response.data;
            for (let index = 0; index < services.length; index++) {

                services_option += '<option value="' + services[index].id + '">' + services[index].name +
                    '</option>';

            }
            $('#service_category').html(services_option);

        })
        .catch(function(error) {

        });
}

function getSubcategoriesServices(e) {

    let serviceCategoryId = e.target.value;

    let services_option = '<option value="">إختر التصنيف الفرعي الخدمة </option>';
    axios.get('/api/services/subcategories/' + serviceCategoryId)
        .then(function(response) {

            let services = response.data;

            for (let index = 0; index < services.length; index++) {

                services_option += '<option value="' + services[index].id + '">' + services[index].name +
                    '</option>';

            }
            $('#service_subcategory').html(services_option);

        })
        .catch(function(error) {

        });
}

function getSub2(e) {

    let servicesubategoryId = e.target.value;
    let services_option = '<option value="">إختر التصنيف الفرعي الثاني الخدمة </option>';

    axios.get('/api/services/sub2/' + servicesubategoryId)
        .then(function(response) {
            let services = response.data;

            for (let index = 0; index < services.length; index++) {

                services_option += '<option value="' + services[index].id + '">' + services[index].name +
                    '</option>';

            }


            $('#service_sub2').html(services_option);

        })
        .catch(function(error) {

        });
}

function getSub3(e) {

    let sub2Id = e.target.value;
    let services_option = '<option value="">إختر التصنيف الفرعي الثالت الخدمة </option>';

    axios.get('/api/services/sub3/' + sub2Id)
        .then(function(response) {
            let services = response.data;

            for (let index = 0; index < services.length; index++) {

                services_option += '<option value="' + services[index].id + '">' + services[index].name +
                    '</option>';

            }

            $('#service_sub3').html(services_option);

        })
        .catch(function(error) {

        });
}

function getCities(e) {

    let country_id = e.target.value;
    let cities_option = '<option value=""> ----  إخترالمدينة   ----  </option>';
    axios.get('/api/country/cities/' + country_id)
        .then(function(response) {
            let cities = response.data;
            for (let index = 0; index < cities.length; index++) {

                cities_option += '<option value="' + cities[index].id + '">' + cities[index].name +
                    '</option>';

            }
            $('#cities').html(cities_option);

        })
        .catch(function(error) {

        });
}

function getStreet(e) {

    let city_id = e.target.value;
    let street_option = '<option value=""> ----  إختر الحي   ----  </option>';
    axios.get('/api/city/street/' + city_id)
        .then(function(response) {
            let street = response.data;
            for (let index = 0; index < street.length; index++) {

                street_option += '<option value="' + street[index].id + '">' + street[index].name +
                    '</option>';

            }
            $('#street').html(street_option);

        })
        .catch(function(error) {

        });
}

function getBrands(e) {

    let product_category_id = e.target.value;
    let brands_option = '<option value=""> ----  إختر البراند   ----  </option>';
    axios.get('/api/product/brands/' + product_category_id)
        .then(function(response) {
            let brands = response.data;
            console.log(brands);
            for (let index = 0; index < brands.length; index++) {

                brands_option += '<option value="' + brands[index].id + '">' + brands[index].name +
                    '</option>';

            }
            $('#brands').html(brands_option);

        })
        .catch(function(error) {

        });
}

if ($("#is_country_city_street").length) {

    let is_country_city_street = $('#is_country_city_street').val().split('-');

    $('#service_country').prop('checked', parseInt(is_country_city_street[0]) ? true : false);
    $('#service_city').prop('checked', parseInt(is_country_city_street[1]) ? true : false);
    $('#service_street').prop('checked', parseInt(is_country_city_street[2]) ? true : false);
}


$('#service_country').click(function() {
    if ($('#service_country').is(":checked")) {
        let is_country_city_street = $('#is_country_city_street').val().split('-')
        is_country_city_street[0] = '1';
        $('#is_country_city_street').val(is_country_city_street.join("-"))
    } else {
        let is_country_city_street = $('#is_country_city_street').val().split('-')
        is_country_city_street[0] = '0';
        $('#is_country_city_street').val(is_country_city_street.join("-"))

    }
});
$('#service_city').click(function() {
    if ($('#service_city').is(":checked")) {
        let is_country_city_street = $('#is_country_city_street').val().split('-')
        is_country_city_street[1] = '1';
        $('#is_country_city_street').val(is_country_city_street.join("-"))
    } else {

        let is_country_city_street = $('#is_country_city_street').val().split('-')
        is_country_city_street[1] = '0';
        $('#is_country_city_street').val(is_country_city_street.join("-"))
    }
});
$('#service_street').click(function() {
    if ($('#service_street').is(":checked")) {

        let is_country_city_street = $('#is_country_city_street').val().split('-')
        is_country_city_street[2] = '1';
        $('#is_country_city_street').val(is_country_city_street.join("-"))

    } else {
        let is_country_city_street = $('#is_country_city_street').val().split('-')
        is_country_city_street[2] = '0';
        $('#is_country_city_street').val(is_country_city_street.join("-"))

    }
});
$('#settings').click(function() {
    $('.settings').removeClass('d-none');
    $('.settings').addClass('d-block');

    $('.settings_store').addClass('d-none');
    $('.settings_store').removeClass('d-block');

    $('.settings_media').addClass('d-none');
    $('.settings_media').removeClass('d-block');

    $('.oder_settings').addClass('d-none');
    $('.oder_settings').removeClass('d-block');

    $('.payment_settings').addClass('d-none');
    $('.payment_settings').removeClass('d-block');

    $('.email_settings').addClass('d-none')
    $('.email_settings').removeClass('d-block')

    $('.btn-active').removeClass('btn-active');
    $(this).addClass('btn-active');
});
$('#settings_store').click(function() {
    $('.settings').removeClass('d-block')
    $('.settings').addClass('d-none')
    $('.settings_store').addClass('d-block')
    $('.settings_store').removeClass('d-none')
    $('.settings_media').addClass('d-none')
    $('.settings_media').removeClass('d-block')
    $('.oder_settings').addClass('d-none')
    $('.oder_settings').removeClass('d-block');
    $('.payment_settings').addClass('d-none');
    $('.payment_settings').removeClass('d-block');

    $('.email_settings').addClass('d-none')
    $('.email_settings').removeClass('d-block')

    $('.btn-active').removeClass('btn-active');
    $(this).addClass('btn-active');
});
$('#settings_media').click(function() {
    $('.settings').removeClass('d-block')
    $('.settings').addClass('d-none')

    $('.settings_store').addClass('d-none')
    $('.settings_store').removeClass('d-block')

    $('.settings_media').addClass('d-block')
    $('.settings_media').removeClass('d-none')

    $('.oder_settings').addClass('d-none')
    $('.oder_settings').removeClass('d-block');

    $('.payment_settings').addClass('d-none');
    $('.payment_settings').removeClass('d-block');

    $('.email_settings').addClass('d-none')
    $('.email_settings').removeClass('d-block')

    $('.btn-active').removeClass('btn-active');
    $(this).addClass('btn-active');
});
$('#oder_settings').click(function() {
    $('.settings').removeClass('d-block')
    $('.settings').addClass('d-none');

    $('.settings_store').addClass('d-none');
    $('.settings_store').removeClass('d-block');

    $('.settings_media').addClass('d-none');
    $('.settings_media').removeClass('d-block');

    $('.oder_settings').addClass('d-block');
    $('.oder_settings').removeClass('d-none');

    $('.payment_settings').addClass('d-none');
    $('.payment_settings').removeClass('d-block');

    $('.email_settings').addClass('d-none')
    $('.email_settings').removeClass('d-block')

    $('.btn-active').removeClass('btn-active');
    $(this).addClass('btn-active');
});
$('#payment_settings').click(function() {
    $('.settings').removeClass('d-block')
    $('.settings').addClass('d-none')

    $('.settings_store').addClass('d-none')
    $('.settings_store').removeClass('d-block')

    $('.settings_media').addClass('d-none')
    $('.settings_media').removeClass('d-block')

    $('.oder_settings').addClass('d-none')
    $('.oder_settings').removeClass('d-block')

    $('.email_settings').addClass('d-none')
    $('.email_settings').removeClass('d-block')

    $('.payment_settings').addClass('d-block')
    $('.payment_settings').removeClass('d-none')

    $('.btn-active').removeClass('btn-active');
    $(this).addClass('btn-active');
});
$('#email_settings').click(function() {
    $('.settings').removeClass('d-block')
    $('.settings').addClass('d-none')

    $('.settings_store').addClass('d-none')
    $('.settings_store').removeClass('d-block')

    $('.settings_media').addClass('d-none')
    $('.settings_media').removeClass('d-block')

    $('.oder_settings').addClass('d-none')
    $('.oder_settings').removeClass('d-block')

    $('.payment_settings').addClass('d-none')
    $('.payment_settings').removeClass('d-block')

    $('.email_settings').addClass('d-block')
    $('.email_settings').removeClass('d-none')

    $('.btn-active').removeClass('btn-active');
    $(this).addClass('btn-active');
});
$("body").on("click", ".translations-page #removeRow", function() {
    $(this).closest("tr").remove();
});

$(".translations-page #addRow").on("click", function() {

    const table = $(this).siblings("table");
    const tableHead = table.find("thead");
    const tableBody = table.find("tbody");
    let langs = [];
    let langsCols = '';

    tableHead.find("th").not(":first").not(":last").each(function() {
        langs.push($(this).text());
    });

    langs.forEach(function(lang) {
        langsCols += '<td>';
        langsCols += `<input type="text" class="form-control" name="${lang}[]">`;
        langsCols += '</td>';
    });

    tableBody.append(`
        <tr>
            <td>
                <input type="text" class="form-control" name="keys[]">
            </td>
            ${langsCols}
            <td class="col-1">
                <a id="removeRow" class="btn btn-danger">${$(".Delete").html()}</a>
            </td>
        </tr>
        `);
});







let typingTimer; //timer identifier
var doneTypingInterval = 800; //time in ms, 5 second for example
var $_search = $('._search');

//on keyup, start the countdown

$_search.on('keyup', function() {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping, doneTypingInterval);
});

//on keydown, clear the countdown
$_search.on('keydown', function() {
    clearTimeout(typingTimer);
});

function doneTyping() {
    $('.table_users').submit();

}
//commission alert

$("tr.commission").on('click', 'td:not(.action)', function(event) {

    const { target } = event

    if (!$(target).hasClass('bulk__check')) {

        let provider_id = $(event.currentTarget).attr('data-info') ? $(event.currentTarget).attr('data-info') : $(event.currentTarget).parent().attr('data-info');

        location.assign('/provider/profile/' + provider_id)
    }

})

$('.newCommission').click(function(event) {
    event.preventDefault();

    let result = Swal.fire({
        title: 'هل أنت متأكد ',
        text: "بعد الضغط على نعم لا يمكن التراجع",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم',
        cancelButtonText: 'لا'
    }).then((result) => {
        if (result.isConfirmed) {
            let value = $("#commission_value").val();
            if ($("#Commission_percentage").is(':checked') && !$(event.currentTarget).is('#deleteCommission')) {
                if (value > 100 || value < 0) {
                    return $('#commission_value').addClass('border border-danger');
                }
            }

            let newCommissionValue = $('#commission_value').val();
            let commissionProvider = $(event.currentTarget).attr('data-provider');
            let fields = $(event.currentTarget).is('#deleteCommission') ? { 'provider_id': commissionProvider } : { 'commission': newCommissionValue, 'provider_id': commissionProvider, 'percentage': $("#Commission_percentage").is(':checked') };
            axios.post('/api/provider/commission/' + commissionProvider, fields)
                .then(function(response) {
                    location.reload(true);
                })
                .catch(function(error) {

                });
        }
    })
})

$('#debt_ceiling').click(function(event) {
    event.preventDefault();
    result = Swal.fire({
        title: 'هل أنت متأكد ',
        text: "بعد الضغط على نعم لا يمكن التراجع",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم',
        cancelButtonText: 'لا'
    }).then((result) => {
        if (result.isConfirmed) {

            let debt_ceiling_value = $('#debt_ceiling_value').val();
            let provider_id = $(event.currentTarget).attr('data-provider');
            let fields = { 'debt_ceiling': debt_ceiling_value, 'provider_id': provider_id };
            axios.post('/api/provider/debt_ceiling/' + provider_id, fields)
                .then(function(response) {
                    location.reload(true);
                })
                .catch(function(error) {

                });
        }
    })
})



$('.newTransaction').click(function(event) {
    event.preventDefault();
    let result = Swal.fire({
        title: 'هل أنت متأكد ',
        text: "بعد الضغط على نعم لا يمكن التراجع",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم',
        cancelButtonText: 'لا'
    }).then((result) => {
        if (result.isConfirmed) {

            let amount = $('#transaction_value').val();
            let type = $(event.currentTarget).attr('data-type');
            let provider_id = $(event.currentTarget).attr('data-provider');
            let is_usd = $('#is_usd').prop('checked');


            let fields = { 'user_id': provider_id, 'type': type, 'amount': amount, 'is_usd': is_usd };
            console.log(fields);

            axios.post('/api/provider/transaction/create', fields)
                .then(function(response) {
                    $('#balance_id').html(response.data.balance)

                })
                .catch(function(error) {

                });
        }
    })
})

$("#commission_value").click(function() {
    $(this).removeClass('border border-danger');
})
$("#newProviderKey").click(function() {
    let provider_id = $(event.currentTarget).attr('data-provider');
    event.preventDefault();
    let result = Swal.fire({
        title: 'هل أنت متأكد ',
        text: "بعد الضغط على نعم لا يمكن التراجع",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم',
        cancelButtonText: 'لا'
    }).then((result) => {

        if (result.isConfirmed) {


            axios.post('/api/provider/generate/key/' + provider_id)
                .then(function(response) {
                    $("#provider_key").html(response.data)
                })
                .catch(function(error) {

                });
        }
    })
})


let offerproviderServicsIdsArray = []
let removOfferproviderServicsIdsArray = []
$('.addQuickOffer').click(function() {

    let providerServicesId = $(event.currentTarget).attr('data-provider-service')
    let method = $(event.currentTarget).attr('data-method')
    if (method == 'add') {

        removOfferproviderServicsIdsArray = jQuery.grep(removOfferproviderServicsIdsArray, function(value) {
            return providerServicesId != value;
        });

        offerproviderServicsIdsArray.push(providerServicesId);


        $(event.currentTarget).removeClass(`btn-success`);
        $(event.currentTarget).addClass(`btn-danger`);
        $(event.currentTarget).html(`<strong>-</strong>`);
        $(event.currentTarget).attr(`data-method`, 'remove');

    } else {
        offerproviderServicsIdsArray = jQuery.grep(offerproviderServicsIdsArray, function(value) {
            return providerServicesId != value;
        });
        removOfferproviderServicsIdsArray.push(providerServicesId);
        $(event.currentTarget).removeClass(`btn-danger`);
        $(event.currentTarget).addClass(`btn-success`);
        $(event.currentTarget).html(`<strong>+</strong>`);
        $(event.currentTarget).attr(`data-method`, 'add');
    }

});

$('#saveQuick').click(function() {

    let quickOffersId = $(event.currentTarget).attr('data-quick-offer-id')
    let result = Swal.fire({
        title: 'هل أنت متأكد ',
        text: "بعد الضغط على نعم لا يمكن التراجع",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم',
        cancelButtonText: 'لا'
    }).then((result) => {

        if (result.isConfirmed) {
            axios.post('/quick/offers/' + quickOffersId, { 'ids_offer': offerproviderServicsIdsArray, 'ids_remove_offer': removOfferproviderServicsIdsArray })
                .then(function(response) {
                    location.reload(true);
                })
                .catch(function(error) {

                });


        }
    })
});
$('#service').change(function() {
    $('.search').submit();
})
$("#ProviderKey").click(function() {
    let number_phone = $('.number_phone').val();
    console.log(number_phone);
    axios.post('/api/provider/generate/phone/key/' + number_phone)
        .then(function(response) {
            $("#key_by_number").parent().addClass("show");
            $("#key_by_number").html(response.data);
        })
        .catch(function(error) {

        });
})

$('#verified_identity').on('change', async function() {
    let provider_id = $(this).data('provider')

    try {

        await axios({
            url: `/provider/${provider_id}/ajax`,
            method: "POST",
            data: {
                value: $(this).prop('checked'),
                type: 'identity_verification'
            }
        })

        location.reload(true)
    } catch (err) {
        console.log(err)
    }
})

$('#verified_email').on('change', async function() {
    let provider_id = $(this).data('provider')

    try {

        await axios({
            url: `/provider/${provider_id}/ajax`,
            method: "POST",
            data: {
                value: $(this).prop('checked'),
                type: 'email_verification'
            }
        })

        location.reload(true)
    } catch (err) {
        console.log(err)
    }
})

$('#verified_phone').on('change', async function() {
    let provider_id = $(this).data('provider')

    try {

        await axios({
            url: `/provider/${provider_id}/ajax`,
            method: "POST",
            data: {
                value: $(this).prop('checked'),
                type: 'phone_verification'
            }
        })

        location.reload(true)
    } catch (err) {
        console.log(err)
    }
})

// Delete skill form
$('.delete_form').on('click', function(e) {
    e.preventDefault();

    const form = $($(this).data('form'))

    Swal.fire({
        title: 'هل أنت متأكد ',
        text: "بعد الضغط على نعم لا يمكن التراجع",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم',
        cancelButtonText: 'لا'
    }).then((result) => {
        form.submit()
    })
})

// Add new skill
$(document).on('click', '.add-select-row', function(e) {
    e.preventDefault();

    let options = []

    skills.map(skill => {
        options.push(`<option value="${skill.id}">${skill.name}</option>`)
    })

    let row = `<div class="mt-2 select-row-container">
                <button type="button" class="remove-select-row btn btn-danger btn-sm">
                    <i class="bx bx-minus"></i>
                </button>
                <select name="skills[]" class="form-select">

                    <option value disabled selected>إختر مهارة</option>

                    ${options}
                </select>
            </div>`

    $('#skill_form .form_body').append(row)
})

$(document).on('click', '.remove-select-row', function(e) {
    e.preventDefault();

    $(this).parent().remove()
})


$(document).on('click', '.show_identity_proof', async function(e) {
    e.preventDefault();

    const identity = $(this).data('identity')
    const id = $(this).data('id')
    const isAccepted = $(this).data('is-accepted')

    $('#identityProof .modal-body').html(`<img class="mw-100" src="${identity}" />`);
    if (Boolean(isAccepted)) {
        $('#identityProof .modal-footer').addClass('d-none')
    } else {
        $('#identityProof .modal-footer').removeClass('d-none')
    }

    $('#identityProof').modal('show')
    $($('#identityProof button')[1]).data('form', `#form-deny-${id}`)
    $($('#identityProof button')[2]).data('form', `#form-accept-${id}`)
})


// Accept or deny

$(document).on('click', '.identity_control', function(e) {
    e.preventDefault();

    const form = $($(this).data('form'))

    Swal.fire({
        title: 'هل أنت متأكد ',
        text: "بعد الضغط على نعم لا يمكن التراجع",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم',
        cancelButtonText: 'لا'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit()
        }
    })
})


// Bulk action form
$('#checkAll').change(function(e) {
    const checked = e.target.checked
    $('input.bulk__check').prop('checked', checked)

    let ids = []
    Array.from($('input.bulk__check:checked')).forEach(element => {
        ids.push($(element).val())
    });

    $('input[name=ids]').val(ids.join(','))
})


$('.bulk__check').change(function() {
    let ids = []
    Array.from($('input.bulk__check:checked')).forEach(element => {
        ids.push($(element).val())
    });

    $('input[name=ids]').val(ids.join(','))

    $('#checkAll').prop('checked', $('input.bulk__check:checked').length > 0)
})


$(document).on('click', '.bulk__submit', function(e) {
    e.preventDefault()

    const data = new FormData

    data.append("ids", $('input[name=ids]').val())
    data.append("action", "delete")

    let value = $(this).data('value')

    $.ajax({
        url: $(this).data('bulk-url'),
        method: "POST",
        data: {
            "ids": $(this).parent().parent().find("#ids").val(),
            action: $(this).data('action'),
            value: value
        },
        success: () => {
            window.location.reload()
        }
    })
})


window.alertSucces = function(title, text) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'success',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'حسنا',
    })
}
