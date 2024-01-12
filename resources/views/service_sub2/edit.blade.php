@extends('layout.partials.app')

@section('title', 'تعديل التصنيف الفرعي الثاني للخدمات')

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
                                        <input type="text" value="{{ $serviceSub2->name }}" name="name"
                                            placeholder="أدخل العنوان" class="form-control" id="horizontal-title-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                        الخدمة</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" id="service">
                                            @foreach ($services as $sercive)
                                                <option value="{{ $sercive->id }}"
                                                    @if ($sercive->id == $ids['service_id']) selected @endif>
                                                    {{ $sercive->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                        تصنيف الخدمة </label>
                                    <div class="col-sm-9">
                                        <select name="service_id" class="form-select" id="service_category">
                                            @foreach ($service_category as $sercive)
                                                <option value="{{ $sercive->id }}"
                                                    @if ($sercive->id == $ids['service_categories_id']) selected @endif>
                                                    {{ $sercive->name }}
                                                </option>
                                            @endforeach


                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                        التصنيف الفرعي للخدمة </label>
                                    <div class="col-sm-9">
                                        <select name="service_subcategories_id" class="form-select"
                                            id="service_subcategory">
                                            @foreach ($service_subcategory as $sercive)
                                                <option value="{{ $sercive->id }}"
                                                    @if ($sercive->id == $ids['service_subcategories_id']) selected @endif>
                                                    {{ $sercive->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>


                                <div class="row mb-4">
                                    <label for="horizontal-image-input" class="col-sm-3 col-form-label fs-4">
                                        صورة التصنيف الفرعي للخدمة </label>
                                    <div class="col-sm-9">
                                        <input type="file" name="image" class="form-control" id="horizontal-image-input">
                                    </div>
                                </div>

                                <div class="row mb-4 justify-end">
                                    <label class=" col-sm-3 form-check-label fs-4" for="service">
                                        تفعيل التصنيف الفرعي للخدمة
                                    </label>

                                    <div class=" ps-5 col-sm-9 form-check form-switch form-switch-lg mb-3">
                                        <input class="form-check-input" type="checkbox" name="active" id="service" checked>
                                    </div>
                                </div>


                                <div class="row d-flex justify-content-end mb-4">
                                    <button type="submit" class=" col-sm-6 col-md-2 btn btn-warning w-md fs-4 p-1">تحديث
                                        التصنيف
                                        الفرعي</button>
                                </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

        </div>
    </div>


@endsection
