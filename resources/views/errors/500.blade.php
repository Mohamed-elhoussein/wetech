@extends('errors.layout')

@section('title', 'خطأ في الخادم الداخلي')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="text-center mb-5">
                <h1 class="display-2 fw-medium">5<i class="bx bx-buoy bx-spin text-primary display-3"></i>0</h1>
                <h4 class="text-uppercase">خطأ في الخادم الداخلي</h4>
                <div class="mt-5 text-center">
                    <a class="btn btn-primary waves-effect waves-light" href="/">العودة إلى الرئيسية</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-xl-6">
            <div>
                <img src="/assets/images/error-img.png" alt="" class="img-fluid">
            </div>
        </div>
    </div>

@endsection
