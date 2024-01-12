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

                            @if ($errors->any())
                                <div class="col-md-8 offset-2 text-center badge-soft-danger rounded border border-danger">
                                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                                </div>
                            @endif

                            <form class="m-5" action="" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                        الاسم</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" placeholder="أدخل الاسم" class="form-control"
                                            id="horizontal-title-input" value="{{ old('name') }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-title_en-input" class="col-sm-3 col-form-label fs-4">
                                        الإسم بالانجليزية</label>
                                    <div class="col-sm-9">
                                        <input type="text" value="{{ old('name_en') }}" name="name_en"
                                            placeholder=" أدخل الإسم بالانجليزية" class="form-control"
                                            id="horizontal-title_en-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-image-input"
                                        class="col-sm-3 col-form-label fs-4">عددالأيام</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="days" class="form-control" id="horizontal-image-input"
                                            value="{{ old('days') }}" placeholder=" أدخل عدد الأيام      ">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-image-input" class="col-sm-3 col-form-label fs-4"> الثمن
                                        بالريال</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="price_sar" class="form-control"
                                            id="horizontal-image-input" value="{{ old('price_sar') }}"
                                            placeholder=" أدخل الثمن بالريال     ">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-image-input" class="col-sm-3 col-form-label fs-4">الثمن
                                        بالدولار</label>
                                    <div class="col-sm-9">
                                        <input type="number" name="price_usd" class="form-control"
                                            id="horizontal-image-input" value="{{ old('price_usd') }}"
                                            placeholder=" أدخل الثمن بالدولار      ">
                                    </div>
                                </div>

                                <div class="row d-flex justify-content-end mb-4">
                                    <button type="submit" class=" col-sm-6 col-md-2 btn btn-primary w-md fs-4 p-1">أضف
                                        الإشتراك</button>
                                </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
