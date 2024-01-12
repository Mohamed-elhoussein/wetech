@extends('layout.partials.app')

@section('title', 'تعديل السؤال الشائع')

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


                                <form class="m-5" action="/faq/update/{{ $faq->id }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-4">
                                        <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">السؤال
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="title" value="{{ $faq->title }}"
                                                placeholder="أدخل السؤال" class="form-control"
                                                id="horizontal-title-input">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="horizontal-content-input"
                                            class="col-sm-3 col-form-label fs-4">المحتوى</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" placeholder="أدخل محتوى الصفحة" name="content" id="horizontal-content-input"
                                                rows="4">{{ $faq->content }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row d-flex justify-content-end mb-4">
                                        <button type="submit" class=" col-sm-6 col-md-2 btn btn-warning w-md fs-4 p-1">تحديث
                                            محتوى
                                            السؤال</button>
                                    </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

        @endsection
