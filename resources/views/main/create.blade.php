@extends('maintenance')


@section('content')
    <h2 class="mb-4">إضافة عرض صيانة جديد</h2>
    <div class="card">
        <div class="card-body">

            @if ($errors->any())
                <div class="col-md-8 offset-2 text-center badge-soft-danger rounded border border-danger">
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                </div>
            @endif

            <form class="m-5" action="{{ route('main.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-4">
                    <label for="service_id" class="col-sm-3 col-form-label fs-4">
                        الخدمة
                    </label>
                    <div class="col-sm-9">
                        <select name="service_id" id="service_id" class="form-select">
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <label for="brand_id" class="col-sm-3 col-form-label fs-4">
                        نوع الجهاز
                    </label>
                    <div class="col-sm-7 flex-grow-1">
                        <select name="brand_id" id="brand_id" class="form-select">
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-auto mt-sm-0 mt-3">
                        <button type="button" data-ajax-url="{{ route('brands.ajax.create') }}" class="_trigger_modal w-100 btn btn-success">
                            <i class='bx bx-plus' ></i>
                        </button>
                    </div>
                </div>
                <div class="row mb-4">
                    <label for="models_id" class="col-sm-3 col-form-label fs-4">
                        الموديل
                    </label>
                    <div class="col-sm-7 flex-grow-1">
                        <select name="models_id" id="models_id" class="form-select">
                            @foreach ($models as $model)
                                <option value="{{ $model->id }}">{{ $model->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-auto mt-sm-0 mt-3">
                        <button type="button" data-ajax-url="{{ route('models.ajax.create') }}" class="_trigger_modal w-100 btn btn-success">
                            <i class='bx bx-plus' ></i>
                        </button>
                    </div>
                </div>
                <div class="row mb-4">
                    <label for="color_id" class="col-sm-3 col-form-label fs-4">
                        اللون
                    </label>
                    <div class="col-sm-7 flex-grow-1 d-flex flex-wrap">
                        @foreach ($colors as $color)
                            <label class="d-flex align-items-center me-3">
                                <input type="checkbox" class="me-1 form-check-input form-check-label" name="colors[]" value="{{ $color->id }}">
                                {{ $color->name }}
                            </label>
                        @endforeach
                    </div>
                    <div class="col-sm-auto mt-sm-0 mt-3">
                        <button type="button" data-ajax-url="{{ route('colors.ajax.create') }}" class="_trigger_modal w-100 btn btn-success">
                            <i class='bx bx-plus' ></i>
                        </button>
                    </div>
                </div>
                <div class="row mb-4">
                    <label for="issues_id" class="col-sm-3 col-form-label fs-4">
                        المشكلة
                    </label>
                    <div class="col-sm-7 flex-grow-1">
                        <select name="issues_id" id="issues_id" class="form-select">
                            @foreach ($issues as $issue)
                                <option value="{{ $issue->id }}">{{ $issue->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-auto mt-sm-0 mt-3">
                        <button type="button" data-ajax-url="{{ route('issues.ajax.create') }}" class="_trigger_modal w-100 btn btn-success">
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
                                <input type="checkbox" class="me-1 form-check-input form-check-label countries" name="country_id[]" value="{{ $country->id }}">
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

                <div class="row mb-4 cities-row" style="display: none;">
                    <label for="city_id" class="col-sm-3 col-form-label fs-4">
                        المدينة
                    </label>
                    <div class="col-sm-7 flex-grow-1 d-flex flex-wrap cities_checkboxes">
                        @foreach ($cities as $city)
                        <label class="d-flex align-items-center me-3">
                            <input type="checkbox" class="me-1 form-check-input form-check-label cities" name="city_id[]" value="{{ $city->id }}">
                            <span>{{$city->name}}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="col-sm-auto mt-sm-0 mt-3">
                        <button type="button" data-ajax-url="{{ route('citiesajax.create') }}" class="_trigger_modal w-100 btn btn-success">
                            <i class='bx bx-plus' ></i>
                        </button>
                    </div>
                </div>

                <div class="row mb-4 street-row" style="display: none;">
                    <label for="street_id" class="col-sm-3 col-form-label fs-4">
                        اﻷحياء
                    </label>
                    <div class="col-sm-7 flex-grow-1 street_checkboxes d-flex flex-wrap">
                        @foreach ($streets as $street)
                        <label class="d-flex align-items-center me-3">
                            <input type="checkbox" class="me-1 form-check-input form-check-label" name="street_id[]" value="{{ $street->id }}">
                            <span>{{$street->name}}</span>
                        </label>
                        @endforeach
                    </div>
                    <div class="col-sm-auto mt-sm-0 mt-3">
                        <button type="button" data-ajax-url="{{ route('streetajax.create') }}" class="_trigger_modal w-100 btn btn-success">
                            <i class='bx bx-plus' ></i>
                        </button>
                    </div>
                </div>

                <div class="parent-row row mb-md-0 mb-4">
                    <div class="col-auto flex-grow-1">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="row mb-4">
                                    <label for="type" class="col-sm-3 col-form-label fs-4">
                                        النوع
                                    </label>
                                    <div class="col-sm-7">
                                        <select name="meta[0][type_id]" class="form-select">
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-auto mt-sm-0 mt-3">
                                        <button type="button" data-ajax-url="{{ route('types.ajax.create') }}" class="_trigger_modal w-100 btn btn-success">
                                            <i class='bx bx-plus' ></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-4">
                                    <label for="price" class="col-sm-3 col-form-label fs-4">
                                        السعر
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="price" name="meta[0][price]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="w-100 btn btn-success _add_new_row"><i class='bx bx-plus'></i></button>
                    </div>
                </div>

                <button class="btn btn-primary">إضافة</button>
            </form>
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
            let k = 0

            $('.cities:checked').map((key, city) => {
                let street_city = streets[$(city).val()]

                if (street_city) {
                    _streets += street_city.map((street) => {
                        k = k + 1
                        console.log(k);
                        return `
                        <label class="d-flex align-items-center me-3">
                            <input type="hidden" class="me-1 form-check-input form-check-label" name="street_id[${k}][city_id]" value="${street.city_id}">
                            <input type="checkbox" class="me-1 form-check-input form-check-label" name="street_id[${k}][street_id]" value="${street.id}">
                            <span>${street.name}</span>
                        </label>
                    `
                    }).join("")
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
                success: html => {
                    $('#ajax-result').html(html)
                    $('#add-new-modal').modal('show')
                }
            })

        })

        $(document).on('hidden.bs.modal', '#add-new-modal', function () {
            $(this).remove()
        })

        $(document).on('click', '.close', function () {
            $(this).parents('#add-new-modal').modal('hide')
        })

        $(document).on('click', '._add_new_row', function (e) {
            e.preventDefault();

            const index = $('.parent-row').length

            const options = types.map(item => `<option value="${item.id}">${item.name}</option>`)

            let template = `
                <div class="parent-row row mb-md-0 mb-4">
                    <div class="col-auto flex-grow-1">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="row mb-4">
                                    <label for="type" class="col-sm-3 col-form-label fs-4">
                                        النوع
                                    </label>
                                    <div class="col-sm-9">
                                        <select name="meta[0][type_id]" class="form-select">${options}</select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-4">
                                    <label for="price" class="col-sm-3 col-form-label fs-4">
                                        السعر
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="price" name="meta[${index}][price]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="w-100 btn btn-danger _remove_row"><i class='bx bx-minus'></i></button>
                    </div>
                </div>
            `

            $('.parent-row').last().after(template)
        })

        $(document).on('click', '._remove_row', function (e) {
            e.preventDefault();

            $(this).parents('.parent-row').remove()
        })
    </script>
@endsection
