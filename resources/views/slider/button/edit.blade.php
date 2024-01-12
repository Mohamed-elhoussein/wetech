@extends('layout.partials.app')

@section('title', '')

@section('dashbord_content')


    <div class="page-content">
        <div class="container-fluid">
            @if (session('deleted'))
                <div class=" w-50 m-auto rounded p-2 bg-danger text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('deleted') }}
                </div>
            @endif
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18"></h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4"></h4>
                            @if ($errors->any())
                                <div
                                    class="col-md-8 offset-2 text-center badge-soft-danger rounded border border-danger mb-4">
                                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                                </div>
                            @endif
                            <div class="row ">
                                <div class="col-xl-10 m-auto border rounded bg-light">
                                    <form class="m-md-5" action="" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="row mb-4">
                                            <label for="horizontal-titel-input"
                                                class="col-sm-3 col-form-label fs-4">العنوان</label>
                                            <div class="col-sm-9">
                                                <input value="{{ $button->text }}" type="text" name="text"
                                                    placeholder=" أدخل عنوان الزر" class="form-control"
                                                    value="{{ old('text') }}" id="horizontal-titel-input">
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="horizontal-title_en-input"
                                                class="col-sm-3 col-form-label fs-4">العنوان
                                                بالإنجليزي</label>
                                            <div class="col-sm-9">
                                                <input type="text" value="{{ $button->text_en }}" name="text_en"
                                                    value="{{ old('text_en') }}" placeholder="أدخل عنوان الزر بالإنجليزي"
                                                    class="form-control" id="horizontal-title_en-input">
                                            </div>
                                        </div>
                                        {{-- <div class="row mb-4">
                                            <label for="horizontal-title_en-input"
                                                class="col-sm-3 col-form-label fs-4">الرابط</label>
                                            <div class="col-sm-9">
                                                <input type="text" value="{{ $button->url }}" name="url"
                                                    value="{{ old('url') }}" placeholder="أدخل الرابط"
                                                    class="form-control" id="horizontal-url-input">
                                            </div>
                                        </div> --}}
                                        <div class="row mb-4">
                                            <label for="horizontal-image-input" class="col-sm-3 col-form-label fs-4"> إسم
                                                أيقونة</label>
                                            <div class="col-sm-9">
                                                <input type="text"
                                                    placeholder=" أدخل إسم أيقونة  (هذه الخاصية في حالة لن تستعمل الصورة) "
                                                    value="{{ $button->icon }}" name="icon" class="form-control"
                                                    id="horizontal-image-input">
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="horizontal-image-input"
                                                class="col-sm-3 col-form-label fs-4">الصورة</label>
                                            <div class="col-sm-9">
                                                <input type="file" value="{{ $button->image }}" name="image"
                                                    class="form-control" id="horizontal-image-input">
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label for="horizontal-target-input" class="col-sm-3 col-form-label fs-4">موجه
                                                إلى</label>
                                            <div class="col-sm-9">
                                                <div class="d-flex flex-column">
                                                    <div class="d-flex">
                                                        <div class="me-4">
                                                            <input type="radio" checked name="go_to" value="url"
                                                                id="url">
                                                            <label for="url">التوجيه إلى رابط</label>
                                                        </div>
                                                        <div class="me-4">
                                                            <input type="radio" name="go_to" value="product"
                                                                id="product">
                                                            <label for="product">التوجيه إلى المنتج</label>
                                                        </div>
                                                        <div class="me-4">
                                                            <input type="radio" name="go_to" value="provider_service"
                                                                id="provider_service">
                                                            <label for="provider_service">التوجيه إلى خدمات المزودين</label>
                                                        </div>
                                                        <div class="me-4">
                                                            <input type="radio" name="go_to" value="provider_offers"
                                                                id="provider_offers">
                                                            <label for="provider_offers">التوجيه إلى العروض السريعة</label>
                                                        </div>
                                                    </div>
                                                    <input type="text" data-target="url" name="url"
                                                        placeholder=" أدخل التوجيه مثال (HOME)" class="form-control"
                                                        id="horizontal-target-input">
                                                    <div data-target="product" class="d-none">
                                                        <div class="d-flex align-items-center">
                                                            <select name="product_categories" class="me-3 form-select">
                                                                <option disabled selected>المرجو اختيار تصنيف المنتج
                                                                </option>
                                                                @foreach ($productCategories as $category)
                                                                    <option value="{{ $category->id }}">
                                                                        {{ $category->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <select name="product_id" class="form-select">
                                                                <option disabled selected>المرجو اختيار المنتج</option>
                                                                @foreach ($products as $product)
                                                                    <option value="{{ $product->id }}">
                                                                        {{ $product->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div data-target="provider_service" class="d-none">
                                                        <div class="d-flex align-items-center">
                                                            <select name="service_id" class="me-3 form-select">
                                                                <option disabled selected>المرجو اختيار الخدمة</option>
                                                                @foreach ($services as $service)
                                                                    <option value="{{ $service->id }}">
                                                                        {{ $service->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <select name="provider_service_id" class="form-select">
                                                                <option disabled selected>المرجو اختيار القسم</option>
                                                                @foreach ($providerServices as $provider)
                                                                    <option value="{{ $provider->id }}">
                                                                        {{ $provider->title }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div data-target="provider_offers" class="d-none">
                                                        <div class="d-flex align-items-center">
                                                            <select name="service_id" class="me-3 form-select">
                                                                <option disabled selected>المرجو اختيار الخدمة</option>
                                                                @foreach ($services as $service)
                                                                    <option value="{{ $service->id }}">
                                                                        {{ $service->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <select name="offer_id" class="form-select">
                                                                <option selected>المرجو اختيار عرض سريع</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row d-flex justify-content-end mb-4">
                                            <button type="submit"
                                                class=" col-sm-6 col-md-2 btn btn-primary w-md  p-1 font-size-18">
                                                تعديل الزر
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection





@section('scripts')

    <script>
        $('[name="go_to"]').on('change', e => {
            $('[data-target]').addClass('d-none')

            const value = e.target.value

            $(`[data-target="${value}"`).removeClass('d-none')
        })

        function insertOffers(offers) {
            $('select[name="offer_id"] option').remove()
            if (offers) {
                Array.from(offers).forEach((offer, index) => {
                    const option = document.createElement('option')
                    option.innerText = offer.title
                    option.value = offer.id
                    $('[name="offer_id"]').append(option)
                })
            }
        }

        function insertProducts(products) {
            $('select[name="product_id"] option').remove()
            if (products) {
                Array.from(products).forEach((product, index) => {
                    const option = document.createElement('option')
                    option.innerText = product.name
                    option.value = product.id
                    $('[name="product_id"]').append(option)
                })

                if (products.length == 0) {
                    $('[name="product_id"]').addClass('d-none')
                    return
                }
                $('[name="product_id"]').removeClass('d-none')
            }
        }

        function insertServiceProviders(provider_services) {
            $('select[name="provider_service_id"] option').remove()
            if (provider_services) {
                Array.from(provider_services).forEach((service, index) => {
                    const option = document.createElement('option')
                    option.innerText = service.title
                    option.value = service.id
                    $('[name="provider_service_id"]').append(option)
                })

                if (provider_services.length == 0) {
                    $('[name="provider_service_id"]').addClass('d-none')
                    return
                }
                $('[name="provider_service_id"]').removeClass('d-none')
            }
        }

        $('[name="go_to"]').on('change', e => {
            $('[data-target]').addClass('d-none')

            const value = e.target.value

            $(`[data-target="${value}"`).removeClass('d-none')
        })

        $(`[data-target="provider_offers"] [name="service_id"]`).change(function(e) {
            $.ajax({
                url: `/quick_offers/get-offers/${e.target.value}`,
                success: r => {
                    insertOffers(r.offers)
                }
            })
        })

        $(`[data-target="product"] [name="product_categories"]`).change(function(e) {
            $.ajax({
                url: `/product/category/${e.target.value}`,
                method: "POST",
                success: r => {
                    insertProducts(r.data)
                }
            })
        })

        $(`[data-target="provider_service"] [name="service_id"]`).change(function(e) {
            $.ajax({
                url: `/services/provider-services/${e.target.value}`,
                method: "POST",
                success: r => {
                    insertServiceProviders(r.data)
                }
            })
        })
    </script>


    @if (is_array($url))
        <script>
            $('[data-target]').addClass('d-none')

            $(`[data-target="{{ $url['go_to'] }}"`).removeClass('d-none')
            $("[value='{{ $url['go_to'] }}']").prop('checked', true)

            @if ($url['go_to'] == 'url')
                $(`[name="url"]`).val("{{ $url['url'] }}")
            @elseif ($url['go_to'] == 'product')
                let catId = "{{ $url['product_categories'] }}"

                $.ajax({
                    url: `/product/category/${catId}`,
                    method: "POST",
                    success: r => {
                        insertProducts(r.data)
                        $('[name="product_categories"]').val('{{ $url['product_categories'] }}')
                        $('[name="product_id"]').val('{{ $url['product_id'] }}').change()
                    }
                })
            @elseif ($url['go_to'] == 'provider_service')
                let serId = "{{ $url['service_id'] }}"
                $.ajax({
                    url: `/services/provider-services/${serId}`,
                    method: "POST",
                    success: r => {
                        insertServiceProviders(r.data)
                        $('[name="service_id"]').val('{{ $url['service_id'] }}')
                        $('[name="provider_service_id"]').val('{{ $url['provider_service_id'] }}')
                    }
                })
            @elseif ($url['go_to'] == 'provider_offers')
                const __id = {{ $url['service_id'] }}
                $.ajax({
                    url: `/quick_offers/get-offers/${__id}`,
                    success: r => {
                        insertOffers(r.offers)
                        $('[name="offer_id"]').val('{{ $url['offer_id'] }}').change()
                    }
                })
                $('[name="service_id"]').val(__id)
            @endif
        </script>
    @endif


@endsection
