@extends('layout.partials.app')

@section('title', 'أضف خدمة جديدة')

@section('dashbord_content')


    <div class="page-content">
        <div class="container-fluid">

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
                                <div class="col-md-8 offset-2 text-center badge-soft-danger rounded border border-danger">
                                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                                </div>
                            @endif

                            <form class="m-5" action="" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                        العنوان</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" placeholder="أدخل العنوان" class="form-control"
                                            id="horizontal-title-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-title_en-input" class="col-sm-3 col-form-label fs-4">
                                        العنوان بالانجليزية</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name_en" placeholder=" أدخل العنوان بالانجليزية"
                                            class="form-control" id="horizontal-title_en-input">
                                    </div>
                                </div>
                                <div class="mb-4 row">
                                    <label for="example-number-input" class="col-sm-3 col-form-label fs-4">الترتيب</label>
                                    <div class="col-sm-9">
                                        <input class="form-control" type="number" value="0" name="order_index"
                                            id="example-number-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-description-input"
                                        class="col-sm-3 col-form-label fs-4">الوصف</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" placeholder="أدخل وصف الخدمة" name="description" id="horizontal-description-input"
                                            rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-image-input" class="col-sm-3 col-form-label fs-4">
                                        صورة الخدمة </label>
                                    <div class="col-sm-9">
                                        <input type="file" name="image" class="form-control" id="horizontal-image-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class=" col-sm-3 form-check-label fs-4" for="service">
                                        خيار
                                    </label>

                                    <div class=" ps-5 col-sm-9 form-check form-switch form-switch-lg mb-3">
                                        <div class="row">

                                            <div class=" col-md-4 form-check form-check-primary">
                                                <input class="form-check-input" type="checkbox" id="service_country"
                                                    checked="">
                                                <label class=" px-1 form-check-label" for="service_country">
                                                    الدول
                                                </label>
                                            </div>
                                            <div class=" col-md-4 form-check form-check-primary">
                                                <input class="form-check-input" type="checkbox" id="service_city"
                                                    checked="">
                                                <label class="px-1 form-check-label" for="service_city">
                                                    المدن
                                                </label>
                                            </div>
                                            <div class=" col-md-4 form-check form-check-primary">
                                                <input class="form-check-input" type="checkbox" id="service_street"
                                                    checked="">
                                                <label class=" px-1 form-check-label" for="service_street">
                                                    الأحياء
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <input class="form-check-input" type="hidden" id="is_country_city_street"
                                        name="is_country_city_street" value="1-1-1" id="service" checked>
                                </div>

                                <div class="row mb-4 justify-end">
                                    <label class=" col-sm-3 form-check-label fs-4" for="service">
                                        تفعيل الخدمة
                                    </label>

                                    <div class=" ps-5 col-sm-9 form-check form-switch form-switch-lg mb-3">
                                        <input class="form-check-input" type="checkbox" name="active" id="service" checked>
                                    </div>
                                </div>
                                <div class="row mb-4 justify-end">
                                    <label class=" col-sm-3 form-check-label fs-4" for="service">
                                        خيار لمزود الخدمة
                                    </label>

                                    <div class=" ps-5 col-sm-9 form-check form-switch form-switch-lg mb-3">
                                        <input class="form-check-input" type="checkbox" name="join_option" id="service"
                                            checked>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-end mb-4">
                                    <button type="submit" class=" col-sm-6 col-md-2 btn btn-primary w-md fs-4 p-1">أضف
                                        الخدمة</button>
                                </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
