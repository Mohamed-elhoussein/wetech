@extends('layout.partials.app')

@section('title', 'إرسال الإشعارات')

@section('dashbord_content')
    <div class="page-content">
        <div class="container-fluid">
            @if (session('created'))
                <div class=" w-50 m-auto rounded p-2 bg-success text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('created') }}</div>
            @endif
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
                                    <label for="horizontal-name-input" class="col-sm-3 col-form-label fs-4">العنوان </label>
                                    <div class="col-sm-9">
                                        <input type="text" name="title" placeholder="أدخل العنوان" class="form-control"
                                            id="horizontal-name-input">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="horizontal-body-input" class="col-sm-3 col-form-label fs-4">الرسالة</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" placeholder="أدخل الرسالة" name="message" id="horizontal-body-input"
                                            rows="4">{{ old('message') }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-3 ">
                                    <label for="horizontal-sel-input" name="role"
                                        class="col-sm-3 col-form-label fs-4">إلى</label>
                                    <div class="col-sm-9">
                                        <select class="form-select" name="target" id="horizontal-sel-input">
                                            <option value="all" selected>الكل</option>
                                            <option value="users">الزبناء</option>
                                            <option value="provider">المزودين</option>
                                        </select>
                                    </div>
                                </div>



                                <div class="row d-flex justify-content-end mb-4">
                                    <button type="submit"
                                        class=" col-sm-6 col-md-2 btn btn-primary w-md fs-4 p-1">إرسال</button>
                                </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
