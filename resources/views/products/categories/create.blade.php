@extends('layout.partials.app')

@section('title', 'إضافة تصنيف جديد')

@section('dashbord_content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">تصنيفات المنتجات</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">إضافة تصنيف جديد</h4>

                            @if ($errors->any())
                                <div class="col-md-8 offset-2 text-center badge-soft-danger rounded border border-danger">
                                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                                </div>
                            @endif

                            <form class="m-md-5" action="{{ route('product-categories.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <label for="horizontal-name-input" class="col-sm-3 col-form-label  fs-4">إسم
                                        التصنيف
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" placeholder="أضف إسم التصنيف"
                                            class="form-control" id="horizontal-name-input"
                                            value="{{ old('name') }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-name-en-input" class="col-sm-3 col-form-label  fs-4">إسم
                                        التصنيف (en)
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name_en" placeholder="أضف إسم التصنيف"
                                            class="form-control" id="horizontal-name-en-input"
                                            value="{{ old('name_en') }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-icon-input" class="col-sm-3 col-form-label  fs-4">
                                        الأيقونة
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="file" name="image" class="form-control" id="horizontal-icon-input" accept="image/jpeg,image/jpg,image/png,image/svg+xml">
                                    </div>
                                </div>

                                <div class="row d-flex justify-content-end">
                                    <button type="submit"
                                        class=" col-sm-6 col-md-2 btn btn-primary w-md fs-4">إضافة</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
