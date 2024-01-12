@extends('layout.partials.app')

@section('title', '')

@section('dashbord_content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18"> مهارة جديدة</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">إضافة مهارة جديدة</h4>
                            {{-- @if ($errors->any())
                                <div class="col-md-8 offset-2 text-center alert alert-danger rounded border border-danger">
                                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                                </div> --}}
                            {{-- @endif --}}
                            <form class="m-5" action="{{ route('skills.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">
                                        إسم المهارة
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" placeholder="إسم المهارة"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="horizontal-firstname-input" value="{{ old('name') }}">

                                        @error('name')
                                            <span style="font-weight: bold; font-size: 95%;"
                                                class="invalid-feedback">{{ $message }}</span>
                                        @enderror
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
