@extends('layout.partials.app')

@section('title', 'تعديل الخدمة')

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

                        <form class="m-5" action="{{ route('service-types.update', ['service_type' => $serviceType]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-4">
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                    الإسم
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" value="{{ $serviceType->name }}" class="form-control @error('name') @enderror" id="horizontal-title-input">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                    الإسم (en)
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" name="name_en" value="{{ $serviceType->name_en }}" class="form-control @error('name_en') @enderror" id="horizontal-title-input">
                                    @error('name_en')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row d-flex justify-content-end mb-4">
                                <button type="submit" class=" col-sm-6 col-md-2 btn btn-warning w-md fs-4 p-1">
                                    تحديث نوع الخدمة
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- End Page-content -->



@endsection