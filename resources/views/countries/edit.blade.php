@extends('layout.partials.app')

@section('title', 'تعديل دولة')

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
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                        الإسم</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" value="{{ $country->name }}"
                                            placeholder="أدخل الإسم" class="form-control" id="horizontal-title-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                        الاسم الدولي</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="code" placeholder="أدخل الاسم الدولي (  كمثال SA  ) "
                                            class="form-control" id="horizontal-title-input"
                                            value="{{ $country->code }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-unit-input" class="col-sm-3 col-form-label fs-4">
                                        العملة</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="unit" placeholder="أدخل العملة"
                                            value="{{ $country->unit }}" class="form-control"
                                            id="horizontal-unit-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-unit-input" class="col-sm-3 col-form-label fs-4">
                                        العملة بالإنجليزية</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="unit_en" placeholder=" أدخل العملة بالإنجليزية"
                                            value="{{ $country->unit_en }}" class="form-control"
                                            id="horizontal-unit-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-country_code-input" class="col-sm-3 col-form-label fs-4">
                                        الرقم الدولي</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="country_code" placeholder="أدخل الرقم الدولي"
                                            class="form-control" id="horizontal-country_code-input"
                                            value="{{ $country->country_code }}" dir="ltr" style="text-align: right">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-status-input" class="col-sm-3 col-form-label fs-4">
                                        الحالة</label>
                                    <div class="col-sm-9">
                                        <select name="status" class="form-select">
                                            <option @if ($country->status == 'ACTIVE') selected @endif value="ACTIVE">ACTIVE
                                            </option>
                                            <option @if ($country->status == 'UNACTIVE') selected @endif value="UNACTIVE">
                                                UNACTIVE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-description-input"
                                        class="col-sm-3 col-form-label fs-4">الرسالة</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" placeholder="أدخل الرسالة" name="message" id="horizontal-description-input"
                                            rows="4">{{ $country->message }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-pin-input" class="col-sm-3 col-form-label fs-4">
                                        الإثبات</label>
                                    <div class="col-sm-9">
                                        <select name="pin" class="form-select">
                                            <option @if ($country->pin == 'PINED') selected @endif value="PINED">PINED
                                            </option>
                                            <option @if ($country->pin == 'UNPINED') selected @endif value="UNPINED">
                                                UNPINED</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row d-flex justify-content-end mb-4">
                                    <button type="submit" class=" col-sm-6 col-md-2 btn btn-warning w-md fs-4 p-1">تحديث
                                        الدولة</button>
                                </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- End Page-content -->



    @endsection
