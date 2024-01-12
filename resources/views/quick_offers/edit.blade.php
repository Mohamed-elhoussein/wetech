@extends('layout.partials.app')

@section('title', 'تعديل العرض السريع')

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
                                    <label for="horizontal-titel-input" class="col-sm-3 col-form-label fs-4">العنوان</label>
                                    <div class="col-sm-9">
                                        <input type="text" value="{{ old('title') ?? $quickOffers->title }}" name="title"
                                            placeholder=" أدخل عنوان العرض" class="form-control"
                                            id="horizontal-titel-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-title_en-input" class="col-sm-3 col-form-label fs-4">العنوان
                                        بالإنجليزي</label>
                                    <div class="col-sm-9">
                                        <input type="text" value="{{ old('title_en') ?? $quickOffers->title_en }}"
                                            name="title_en" placeholder="أدخل عنوان العرض بالإنجليزي  "
                                            class="form-control" id="horizontal-title_en-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-body-input" class="col-sm-3 col-form-label fs-4">الوصف</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" placeholder="أدخل الوصف" name="body" id="horizontal-body-input"
                                            rows="4">{{ old('body') ?? $quickOffers->body }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-body_en-input" class="col-sm-3 col-form-label fs-4">الوصف
                                        بالإنجليزي</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" placeholder="أدخل الوصف بالإنجليزي" name="body_en" id="horizontal-body_en-input"
                                            rows="4">{{ old('body_en') ?? $quickOffers->body_en }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-image-input" class="col-sm-3 col-form-label fs-4">الصورة</label>
                                    <div class="col-sm-9">
                                        <input type="file" name="image" class="form-control" id="horizontal-image-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-price-input" class="col-sm-3 col-form-label fs-4">السعر</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="price" class="form-control"
                                            value="{{ $quickOffers->price }}" id="horizontal-price-input">
                                    </div>
                                </div>


                                <div class="row d-flex justify-content-end mb-4">
                                    <button type="submit" class=" col-sm-6 col-md-2 btn btn-warning w-md fs-4 p-1">تحديث
                                        العرض</button>
                                </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
