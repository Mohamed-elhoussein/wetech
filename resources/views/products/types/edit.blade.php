@extends('layout.partials.app')

@section('title', 'تعديل النوع')

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
                            <h4 class="card-title mb-4">تعديل النوع</h4>

                            @if ($errors->any())
                                <div class="col-md-8 offset-2 text-center badge-soft-danger rounded border border-danger">
                                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                                </div>
                            @endif

                            <form class="m-md-5" action="{{ route('product-types.update', ['product_type' => $type]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="row mb-4">
                                    <label for="horizontal-name-input" class="col-sm-3 col-form-label  fs-4">إسم
                                        النوع
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" placeholder="أضف إسم النوع"
                                            class="form-control" id="horizontal-name-input"
                                            value="{{ $type->name }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-name-en-input" class="col-sm-3 col-form-label  fs-4">إسم
                                        النوع (en)
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name_en" placeholder="أضف إسم النوع"
                                            class="form-control" id="horizontal-name-en-input"
                                            value="{{ $type->name_en }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-type-input" class="col-sm-3 col-form-label fs-4">
                                        تصنيف النوع
                                    </label>
                                    <div class="col-sm-9">
                                        <select name="product_categories_id" class="form-select" id="horizontal-type-input">
                                            @foreach ($categories as $type)
                                            <option value="{{ $type->id }}" @if($type->id === $type->product_categories_id) selected @endif>
                                                {{ $type->name }} - {{ $type->name_en }}
                                            </option>
                                            @endforeach
                                        </select>
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
