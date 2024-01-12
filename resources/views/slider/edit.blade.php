@extends('layout.partials.app')

@section('title', '')

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

                            <form class="m-5" action="" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <div class="d-flex justify-content-center rounded">
                                        <img src="{{ $slider->image ?: default_image() }}" alt="slider image"
                                            class="rounded shadow-sm" style="height: 15em">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-name-input" class="col-sm-3 col-form-label fs-4">اسم
                                        الشريحة</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" value="{{ $slider->name }}"
                                            placeholder="أدخل اسم الشريحة" class="form-control"
                                            id="horizontal-name-input">
                                    </div>
                                </div>
                                {{-- <div class="row mb-4">
                                    <label for="horizontal-email-input" class="col-sm-3 col-form-label fs-4">الرابط</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="url" value="{{ $slider->url }}" placeholder="أدخل الرابط"
                                            class="form-control" id="horizontal-email-input">
                                    </div>
                                </div> --}}
                                <div class="row mb-4">
                                    <label for="horizontal-phone-input" class="col-sm-3 col-form-label fs-4">الهاتف</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="phone" value="{{ $slider->phone }}"
                                            placeholder="أدخل الهاتف" class="form-control" id="horizontal-phone-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-target-input" class="col-sm-3 col-form-label fs-4">موجه
                                        إلى</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="target" value="{{ $slider->target }}"
                                            placeholder="أدخل التوجيه مثال (HOME)" class="form-control"
                                            id="horizontal-target-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-email-input" class="col-sm-3 col-form-label fs-4">الصورة</label>
                                    <div class="col-sm-9">
                                        <input type="file" name="image" class="form-control" id="horizontal-email-input">
                                    </div>
                                </div>
                                <div class="row mb-4 justify-end">
                                    <label class=" col-sm-3 form-check-label fs-4" for="btn_visible">
                                        إظهار زر الإتصال
                                    </label>

                                    <div class=" ps-5 col-sm-9 form-check form-switch form-switch-lg mb-3">
                                        <input class="form-check-input" type="checkbox" name="visitableBtn" id="btn_visible"
                                            @if ($slider->visitableBtn == 1) checked @endif>
                                    </div>
                                </div>
                                <div class="row mb-4 justify-end">
                                    <label class=" col-sm-3 form-check-label fs-4" for="slider">
                                        تفعيل الشريحة
                                    </label>

                                    <div class=" ps-5 col-sm-9 form-check form-switch form-switch-lg mb-3">
                                        <input class="form-check-input" type="checkbox" name="active" id="slider"
                                            @if ($slider->active == 1) checked @endif>
                                    </div>
                                </div>


                                <div class="row d-flex justify-content-end mb-4">
                                    <button type="submit" class=" col-sm-6 col-md-2 btn btn-warning w-md fs-4 p-1">تحديث
                                        شريحة</button>
                                </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
