@extends('layout.partials.app')

@section('title', 'تعديل الحي')

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
                                        <input type="text" value="{{ $street->name }}" name="name"
                                            placeholder="أدخل الاسم" class="form-control" id="horizontal-title-input">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                        الدولة</label>
                                    <div class="col-sm-9">
                                        <select name="" class="form-select" id="countries">
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}"
                                                    @if ($country->id == $street->cities->country->id) selected @endif>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                        المدينة</label>
                                    <div class="col-sm-9">
                                        <select name="city_id" class="form-select" id="cities">
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}"
                                                    @if ($city->id == $street->cities->id) selected @endif>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-end mb-4">
                                    <button type="submit" class=" col-sm-6 col-md-2 btn btn-warning w-md fs-4 p-1">تحديث
                                        المدينة</button>
                                </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- End Page-content -->



    @endsection
