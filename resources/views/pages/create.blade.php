@extends('layout.partials.app')

@section('title', '')

@section('dashbord_content')


    <div class="main-content">

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
                                    <div
                                        class="col-md-8 offset-2 text-center badge-soft-danger rounded border border-danger">
                                        {!! implode('', $errors->all('<div>:message</div>')) !!}
                                    </div>
                                @endif

                                <form class="m-5" action="" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-4">
                                        <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">العنوان
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="title" placeholder="أدخل العنوان"
                                                class="form-control" id="horizontal-title-input">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="horizontal-content-input"
                                            class="col-sm-3 col-form-label fs-4">المحتوى</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" placeholder="أدخل محتوى الصفحة" name="content" id="horizontal-content-input"
                                                rows="4"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="horizontal-thumbnail-input"
                                            class="col-sm-3 col-form-label fs-4">الصورة</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="thumbnail" class="form-control"
                                                id="horizontal-thumbnail-input">
                                        </div>
                                    </div>
                                    <div class="row mb-4 justify-end">
                                        <label class=" col-sm-3 form-check-label fs-4" for="pages">
                                            تفعيل الصفحة
                                        </label>
                                        <div class=" ps-5 col-sm-9 form-check form-switch form-switch-lg mb-3">
                                            <input class="form-check-input" type="checkbox" name="active" id="pages"
                                                checked>
                                        </div>
                                    </div>


                                    <div class="row d-flex justify-content-end mb-4">
                                        <button type="submit" class=" col-sm-6 col-md-2 btn btn-primary w-md fs-4 p-1">أضف
                                            محتوى
                                            الصفحة</button>
                                    </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- container-fluid -->


@endsection
