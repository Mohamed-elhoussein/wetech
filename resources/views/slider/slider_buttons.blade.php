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
                                                <input type="text" name="text" placeholder=" أدخل عنوان الزر"
                                                    class="form-control" value="{{ old('text') }}"
                                                    id="horizontal-titel-input">
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="horizontal-title_en-input"
                                                class="col-sm-3 col-form-label fs-4">العنوان
                                                بالإنجليزي</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="text_en" value="{{ old('text_en') }}"
                                                    placeholder="أدخل عنوان الزر بالإنجليزي" class="form-control"
                                                    id="horizontal-title_en-input">
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="horizontal-title_en-input"
                                                class="col-sm-3 col-form-label fs-4">الرابط</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="url" value="{{ old('url') }}"
                                                    placeholder="أدخل الرابط" class="form-control"
                                                    id="horizontal-url-input">
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="horizontal-image-input" class="col-sm-3 col-form-label fs-4"> إسم
                                                أيقونة</label>
                                            <div class="col-sm-9">
                                                <input type="text"
                                                    placeholder=" أدخل إسم أيقونة  (هذه الخاصية في حالة لن تستعمل الصورة) "
                                                    name="icon" class="form-control" id="horizontal-image-input">
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="horizontal-image-input"
                                                class="col-sm-3 col-form-label fs-4">الصورة</label>
                                            <div class="col-sm-9">
                                                <input type="file" name="image" class="form-control"
                                                    id="horizontal-image-input">
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
                                                        placeholder="أدخل رابط" class="form-control"
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
                                                            <select name="product_id" class="form-select d-none">
                                                                <option disabled selected>المرجو اختيار المنتج</option>
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
                                                            <select name="provider_service_id"
                                                                class="form-select d-none">
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
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row d-flex justify-content-end mb-4">
                                            <button type="submit"
                                                class=" col-sm-6 col-md-2 btn btn-primary w-md  p-1 font-size-18">أضف
                                                الزر</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-10 m-auto">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table align-middle mb-0 table-nowrap">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>الصورة</th>
                                                            <th>العنوان</th>
                                                            <th>العنوان بالإنجليزي</th>
                                                            <th>الرابط</th>
                                                            <th>الحالة</th>
                                                            <th>تعديل</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        @foreach ($sliderButtons as $button)
                                                            <tr>
                                                                <td>
                                                                    <img src="{{ url($button->icon_name_or_url ?? '') }}"
                                                                        alt="button-icon" title="button-icon"
                                                                        class="avatar-sm">
                                                                </td>
                                                                <td>

                                                                    {{ $button->text }}

                                                                </td>
                                                                <td>
                                                                    {{ $button->text_en }}
                                                                </td>

                                                                <td>
                                                                    <p>
                                                                        @php
                                                                            $url = json_decode($button->url, true);
                                                                        @endphp
                                                                        @if ($url && $url['go_to'] == 'url')
                                                                            الرابط: {{ $url['url'] }}
                                                                        @elseif ($url && $url['go_to'] == 'product')
                                                                            تصنيف المنتج:
                                                                            {{ App\Models\ProductCategories::find($url['product_categories'])->name }}
                                                                            <br>
                                                                            المنتج:
                                                                            {{ App\Models\Product::find($url['product_id'])->name }}
                                                                        @elseif ($url && $url['go_to'] == 'provider_service')
                                                                            الخدمة:
                                                                            {{ App\Models\Service::find($url['service_id'])->name }}
                                                                            <br>
                                                                            خدمة المزود:
                                                                            {{ App\Models\ProviderServices::find($url['provider_service_id'])->title }}
                                                                        @elseif ($url && $url['go_to'] == 'provider_offers')
                                                                            خدمة:
                                                                            {{ App\Models\Service::find($url['service_id'])->name }}
                                                                            <br>
                                                                            العرض السريع:
                                                                            {{ App\Models\QuickOffers::find($url['offer_id'])->title }}
                                                                        @endif
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class=" border px-1 rounded border-2 @if ($button->active) badge-soft-success border-success @else badge-soft-danger border-danger @endif">

                                                                        @if ($button->active)
                                                                            مفعل
                                                                        @else
                                                                            غير مفعل
                                                                        @endif

                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <ul
                                                                        class="list-inline font-size-20 contact-links mb-0">
                                                                        <li class=" list-inline-item px-1">
                                                                            <a href="{{ route('slider.btn.active', compact('button')) }}"
                                                                                class="action-icon text-black ml-4 d-inline">
                                                                                @if ($button->active)
                                                                                    <i
                                                                                        class="bx bx-block font-size-18"></i>
                                                                                @else
                                                                                    <i
                                                                                        class="bx bx-check-square font-size-18"></i>
                                                                                @endif
                                                                            </a>
                                                                        </li>
                                                                        <li class=" list-inline-item px-1">
                                                                            <a href="{{ route('slider.editBtn', compact('slider_id', 'button')) }}"
                                                                                class="action-icon text-black ml-4 d-inline">
                                                                                <i class="bx bx-pencil font-size-18"></i>
                                                                            </a>
                                                                        </li>
                                                                        <li class=" list-inline-item px-1">
                                                                            <a href="{{ route('slider.btn.delete', $button->id) }}"
                                                                                class="action-icon text-black delete"> <i
                                                                                    class="bx bx-trash-alt font-size-18"></i></a>
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
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
                    option.innerText = `${service.title} - ${service.provider.username}`
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

@endsection
