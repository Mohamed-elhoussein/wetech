@extends('maintenance')


@section('content')
    <h2 class="mb-4">تعديل عرض الصيانة</h2>
    <div class="card">
        <div class="card-body">

            @if ($errors->any())
                <div class="col-md-8 offset-2 text-center badge-soft-danger rounded border border-danger">
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                </div>
            @endif

            <form class="m-5" action="{{ route('main.update', $request) }}" method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <label for="service_id" class="col-sm-3 col-form-label fs-4">
                        الخدمة
                    </label>
                    <div class="col-sm-9">
                        <select name="service_id" id="service_id" class="form-select">
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" {{ $request->service_id == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <label for="brand_id" class="col-sm-3 col-form-label fs-4">
                        نوع الجهاز
                    </label>
                    <div class="col-sm-9">
                        <select name="brand_id" id="brand_id" class="form-select">
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $request->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <label for="models_id" class="col-sm-3 col-form-label fs-4">
                        الموديل
                    </label>
                    <div class="col-sm-9">
                        <select name="models_id" id="models_id" class="form-select">
                            @foreach ($models as $model)
                                <option value="{{ $model->id }}" {{ $request->models_id == $model->id ? 'selected' : '' }}>{{ $model->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <label for="color_id" class="col-sm-3 col-form-label fs-4">
                        اللون
                    </label>
                    <div class="col-sm-7 flex-grow-1 d-flex flex-wrap">
                        @foreach ($colors as $color)
                            <label class="d-flex align-items-center me-3">
                                <input type="checkbox" class="me-1 form-check-input form-check-label" {{ $request->colors->pluck('id')->contains($color->id) ? 'checked' : '' }} value="{{ $color->id }}" name="colors[]">
                                {{ $color->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="row mb-4">
                    <label for="issues_id" class="col-sm-3 col-form-label fs-4">
                        المشكلة
                    </label>
                    <div class="col-sm-9">
                        <select name="issues_id" id="issues_id" class="form-select">
                            @foreach ($issues as $issue)
                                <option value="{{ $issue->id }}" {{ $request->issues_id == $issue->id ? 'selected' : '' }}>{{ $issue->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-4">
                    <label for="country_id" class="col-sm-3 col-form-label fs-4">
                        الدول
                    </label>
                    <div class="col-sm-7 flex-grow-1 d-flex flex-wrap">
                        @foreach ($countries as $country)
                            <label class="d-flex align-items-center me-3">
                                <input type="checkbox" class="me-1 form-check-input form-check-label countries" name="country_id[]" {{ $request->country_ids->contains($country->id) ? 'checked' : '' }} value="{{ $country->id }}">
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

                <div class="row mb-4 cities-row" @if($request->city_ids->count() == 0) style="display: none;" @endif>
                    <label for="city_id" class="col-sm-3 col-form-label fs-4">
                        المدينة
                    </label>
                    <div class="col-sm-7 flex-grow-1 d-flex flex-wrap cities_checkboxes">
                        @foreach ($cities as $city)
                            @if ($request->country_ids->contains($city->country_id))
                                <label class="d-flex align-items-center me-3">
                                    <input type="checkbox" class="me-1 form-check-input form-check-label cities" name="city_id[]" {{ $request->city_ids->contains($city->id) ? 'checked' : '' }} value="{{ $city->id }}">
                                    <span>{{$city->name}}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                    <div class="col-sm-auto mt-sm-0 mt-3">
                        <button type="button" data-ajax-url="{{ route('citiesajax.create') }}" class="_trigger_modal w-100 btn btn-success">
                            <i class='bx bx-plus' ></i>
                        </button>
                    </div>
                </div>

                <div class="row mb-4 street-row" @if($request->city_ids->count() == 0) style="display: none;" @endif>
                    <label for="street_id" class="col-sm-3 col-form-label fs-4">
                        اﻷحياء
                    </label>
                    <div class="col-sm-7 flex-grow-1 street_checkboxes d-flex flex-wrap">
                        @foreach ($streets as $key => $street)
                            @if ($request->city_ids->contains($street->city_id))
                                <label class="d-flex align-items-center me-3">
                                    <input type="hidden" class="me-1 form-check-input form-check-label" name="street_id[{{ $key }}][city_id]" value="{{ optional($street)->city_id }}">
                                    <input type="checkbox" class="me-1 form-check-input form-check-label" name="street_id[{{ $key }}][street_id]" {{ $request->street_ids->contains($street->id) ? 'checked' : '' }} value="{{ optional($street)->id }}">
                                    <span>{{ optional($street)->name }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                    <div class="col-sm-auto mt-sm-0 mt-3">
                        <button type="button" data-ajax-url="{{ route('streetajax.create') }}" class="_trigger_modal w-100 btn btn-success">
                            <i class='bx bx-plus' ></i>
                        </button>
                    </div>
                </div>

                @foreach ($request->types as $key => $item)
                    <div class="parent-row row mb-md-0 mb-4">
                        <input type="hidden" name="meta[{{ $key }}][id]" value="{{ $item->id }}">
                        <div class="col-auto flex-grow-1">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="row mb-4">
                                        <label for="type" class="col-sm-3 col-form-label fs-4">
                                            النوع
                                        </label>
                                        <div class="col-sm-9">
                                            <select name="meta[{{ $key }}][type_id]" class="form-select">
                                                @foreach ($types as $type)
                                                    <option {{ $item->type_id == $type->id ? 'selected' : '' }} value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-4">
                                        <label for="price" class="col-sm-3 col-form-label fs-4">
                                            السعر
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="price" value="{{ $item->price }}" name="meta[{{ $key }}][price]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($key === 0)
                        <div class="col-auto">
                            <button class="w-100 btn btn-success _add_new_row"><i class='bx bx-plus'></i></button>
                        </div>
                        @else
                        <div class="col-auto">
                            <button class="w-100 btn btn-danger _remove_row"><i class='bx bx-minus'></i></button>
                        </div>
                        @endif
                    </div>
                @endforeach

                <button class="btn btn-primary">تعديل</button>
            </form>
        </div>
    </div>

    <div id="ajax-result">

    </div>
@endsection


@section('scripts')
    <script>
        let types = @json($types->toArray());
        let cities = @json($cities->groupBy('country_id')->toArray());
        let streets = @json($streets->groupBy('city_id')->toArray());


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

            $('.cities:checked').map((_key, city) => {
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
            return new Promise((res, rej) => {
                const cities = @json($cities->groupBy('country_id')->toArray())

                const citySelect = $('.city_select');
                citySelect.empty();
                $.each(cities[selectedCountry], function(index, city) {
                    const option = $('<option>').val(city.id).text(city.name);
                    citySelect.append(option);
                });
            })
        }

        function getStreets(item) {
            const streets = @json($streets->groupBy('city_id')->toArray())

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
                                        <select name="meta[${index}][type_id]" class="form-select">${options}</select>
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
    </script>
@endsection
