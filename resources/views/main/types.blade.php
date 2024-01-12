@extends('maintenance')


@section('content')
    <h2 class="mb-4">

    @if ($type)
        تعديل نوع الصيانة رقم {{ $type->id }}
    @else
        إضافة اﻷنواع لطلب الصيانة رقم #{{ $request->id }}
    @endif

    </h2>
    <div class="card">
        <div class="card-body">

            <div class="col-md-8 offset-2 text-center badge-soft-danger rounded border border-danger" id="error-messages" style="display: none;">
                {!! implode('', $errors->all('<div>:message</div>')) !!}
            </div>

            <form class="m-5" action="{{ !$type ? route('main.types', ['request' => $request]) : route('main.types.edit', ['request' => $request, 'type' => $type]) }}" id="main_form" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-4">
                    <label for="type" class="col-sm-3 col-form-label fs-4">
                        النوع
                    </label>
                    <div class="col-sm-7 flex-grow-1">
                        <select name="type_id" class="form-select">
                            @foreach ($types as $_type)
                                <option value="{{ $_type->id }}" {{ $type && $type->type_id == $_type->id ? 'selected' : '' }}>{{ $_type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-auto mt-sm-0 mt-3">
                        <button type="button" data-ajax-url="{{ route('types.ajax.create') }}" class="_trigger_modal w-100 btn btn-success">
                            <i class='bx bx-plus' ></i>
                        </button>
                    </div>
                </div>

                <div class="row mb-4">
                    <label for="country_id" class="col-sm-3 col-form-label fs-4">
                        الدول
                    </label>
                    <div class="col-sm-7 flex-grow-1 d-flex flex-wrap">
                        @foreach ($countries as $country)
                            <label class="d-flex align-items-center me-3">
                                <input type="checkbox" class="me-1 form-check-input form-check-label countries" name="country_id[]" {{ $type && $type->countries_id->contains($country->id) ? 'checked' : '' }}  value="{{ $country->id }}">
                                <span>{{$country->name}}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="col-sm-auto mt-sm-0 mt-3">
                        <button type="button" data-ajax-url="{{ route('countriesajax.create') }}" class="_trigger_modal w-100 btn btn-success">
                            <i class='bx bx-plus' ></i>
                        </button>
                    </div>
                </div>

                <div class="row mb-4 cities-row" style="display: @if(!$type || ($type && $type->cities_id->count() === 0)) none; @endif">
                    <label for="city_id" class="col-sm-3 col-form-label fs-4">
                        المدينة
                    </label>
                    <div class="col-sm-7 flex-grow-1 d-flex flex-wrap cities_checkboxes">
                        @if ($type)
                            @foreach ($cities as $city)
                            <label class="d-flex align-items-center me-3">
                                <input type="checkbox" class="me-1 form-check-input form-check-label cities" {{ $type->cities_id->contains($city->id) ? 'checked' : '' }} name="city_id[]" value="{{ $city->id }}">
                                <span>{{$city->name}}</span>
                            </label>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-sm-auto mt-sm-0 mt-3">
                        <button type="button" data-ajax-url="{{ route('citiesajax.create') }}" class="_trigger_modal w-100 btn btn-success">
                            <i class='bx bx-plus' ></i>
                        </button>
                    </div>
                </div>

                <div class="row mb-4 street-row" style="display: @if(!$type || ($type && $type->streets_id->count() === 0)) none; @endif">
                    <label for="street_id" class="col-sm-3 col-form-label fs-4">
                        اﻷحياء
                    </label>
                    <div class="col-sm-7 flex-grow-1 street_checkboxes d-flex flex-wrap">
                        @if ($type)
                            @foreach ($streets as $street)
                            <label class="d-flex align-items-center me-3">
                                <input type="checkbox" class="me-1 form-check-input form-check-label" {{ $type->streets_id->contains($street->id) ? 'checked' : '' }} name="street_id[]" value="{{ $street->id }}">
                                <span>{{$street->name}}</span>
                            </label>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-sm-auto mt-sm-0 mt-3">
                        <button type="button" data-ajax-url="{{ route('streetajax.create') }}" class="_trigger_modal w-100 btn btn-success">
                            <i class='bx bx-plus' ></i>
                        </button>
                    </div>
                </div>


                <div class="row mb-4">
                    <label for="price" class="col-sm-3 col-form-label fs-4">
                        السعر
                    </label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" id="price" name="price" value="{{ $type ? $type->price : '' }}">
                    </div>
                </div>

                <button class="btn btn-primary">
                    {{ $type ? 'تعديل' : 'إضافة' }}
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-nowrap table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>النوع</th>
                            <th>الدول</th>
                            <th>المدن</th>
                            <th>الحي</th>
                            <th>السعر</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($request_types as $type)
                            <tr>
                                <td>{{ $type->id }}</td>
                                <td>{{ $type->type->name }}</td>
                                <td>{{ $type->countries_name }}</td>
                                <td>{{ $type->cities_name }}</td>
                                <td>{{ $type->streets_name }}</td>
                                <td>{{ $type->price }} ر.س</td>
                                <td>
                                    <a href="{{ route('main.types.edit', ['request' => $request, 'type' => $type], false) }}" class="btn btn-info btn-sm">
                                        تعديل
                                    </a>
                                    <a href="/maintenance-store/{{ $request->id }}/types/{{ $type->id }}/delete" class="delete btn btn-danger btn-sm">
                                        حذف
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="ajax-result">

    </div>
@endsection

@section('scripts')

<script>
    let cities = @json($cities->groupBy('country_id')->toArray());
    let streets = @json($streets->groupBy('city_id')->toArray());
    let types = @json($types->toArray());

    $('.countries').on('change', function () {

        $('.cities_checkboxes').html('')

        $('.countries:checked').map((key, country) => {
            let country_cities = cities[$(country).val()]

            if (country_cities) {
                $('.cities_checkboxes').html(
                    country_cities.map(city => `
                        <label class="d-flex align-items-center me-3">
                            <input type="checkbox" class="me-1 form-check-input form-check-label cities" name="city_id[]" value="${city.id}">
                            <span>${city.name}</span>
                        </label>
                    `)
                )
            }
        })

        if ($('.cities_checkboxes').html().trim() != '') {
            $('.cities-row').show()
        }
        else {
            $('.cities-row').hide()
            $('.street-row').hide()
        }
    })

    $(document).on('change', '.cities', function () {

        $('.street_checkboxes').html('')

        let _streets = ''

        $('.cities:checked').map((key, city) => {
            let street_city = streets[$(city).val()]

            if (street_city) {
                _streets += street_city.map(street => `
                    <label class="d-flex align-items-center me-3">
                        <input type="checkbox" class="me-1 form-check-input form-check-label" name="street_id[]" value="${street.id}">
                        <span>${street.name}</span>
                    </label>
                `).join("")
            }
        })

        $('.street_checkboxes').html(_streets.trim())

        if ($('.street_checkboxes').html().trim() != '') {
            $('.street-row').show()
        }
        else {
            $('.street-row').hide()
        }
    })

</script>


<script>

    function getCities(selectedCountry) {
        const citySelect = $('.city_select');
        citySelect.empty();
        $.each(cities[selectedCountry], function(index, city) {
            const option = $('<option>').val(city.id).text(city.name);
            citySelect.append(option);
        });
    }

    function getStreets(item) {

        const streetSelect = $('.street_select');
        streetSelect.empty();
        $.each(streets[item], function(index, street) {
            const option = $('<option>').val(street.id).text(street.name);
            streetSelect.append(option);
        });
    }

    const countrySelect = $('.country_select');
    countrySelect.change(function() {
        const selectedCountry = $(this).val();
        getCities(selectedCountry);
        getStreets(
            $('.city_select').val()
        );
    });

    const citySelect = $('.city_select');
    citySelect.change(function() {
        const selectedCity = $(this).val();
        getStreets(selectedCity);
    });

    $('._trigger_modal').click(function (e) {
        e.preventDefault()
        e.stopPropagation()

        $.ajax({
            url: $(this).data('ajax-url'),
            data: {
                url: '{{ request()->url() }}'
            },
            success: html => {
                $('#ajax-result').html(html)
                $('#add-new-modal').modal('show')
            }
        })

    })


    $(document).on('submit', '#main_form', function ($event) {
        $event.preventDefault();
        $event.stopPropagation();

        // Get the form element
        const $form = $('#main_form');

        // Serialize the form data
        const formDataString = $form.serialize();

        // Create a new FormData object from the serialized string
        const formData = new FormData($form[0]);

        // Submit the FormData object to the server
        // e.g. using AJAX
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: () => {
                $('#error-messages').hide();
                $('#error-messages').html(``);
            },
            success: function(data) {
                if (data.is_new) {
                    $('.table tbody').prepend(`<tr>
                        <td>${data.type.id}</td>
                        <td>${data.type.type.name}</td>
                        <td>${data.type.country.name}</td>
                        <td>${data.type.city.name}</td>
                        <td>${data.type.street.name}</td>
                        <td>${data.type.price} ر.س</td>
                        <td>
                            <a href="/maintenance-store/{{ $request->id }}/types/${data.type.id}/edit" class="btn btn-info btn-sm">
                                تعديل
                            </a>
                            <a href="/maintenance-store/{{ $request->id }}/types/${data.type.id}/delete" class="delete btn btn-danger btn-sm">
                                حذف
                            </a>
                        </td>
                    </tr>`);
                }
                else {
                    window.location.href = '{{ route("main.types", ["request" => $request]) }}'
                }
            },
            error: function(err) {
                displayErrors(err.responseJSON)
            }
        });

        function displayErrors(data) {
            var errorMessages = '<ul style="list-style: none">';

            $.each(data.errors, function(key, value) {
                errorMessages += '<li>' + value[0] + '</li>';
            });

            errorMessages += '</ul>';

            $('#error-messages').html(errorMessages);
            $('#error-messages').show();
        }
    })
</script>

@endsection
