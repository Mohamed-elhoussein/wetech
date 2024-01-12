@extends('layout.partials.app')

@section('title', 'قائمة الإحصائيات')

@section('dashbord_content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18 ">قائمة الإحصائيات</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body" style="background: ">
                            <div class="row">
                                <h2 class="my-3" style="text-decoration: underline;">إحصائيات الهواتف المستعملة :
                                </h2>

                                <div class="col-lg-6 col-xl-3">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-primary">
                                        <a href="{{ route('user.providers') }}" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">عدد المزودين أندرويد</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $phones['provider']['android'] }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-3">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-primary">
                                        <a href="{{ route('user.providers') }}" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">عدد المزودين أيفون</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $phones['provider']['ios'] }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-3">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-primary">
                                        <a href="{{ route('user.providers') }}" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">عدد المستخدمين أندرويد</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $phones['user']['android'] }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-3">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-primary">
                                        <a href="{{ route('user.users') }}" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">عدد المستخدمين أيفون</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $phones['user']['ios'] }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <h2 class="my-3" style="text-decoration: underline;">إحصائيات مزودي الخدمات :
                                </h2>

                                <div class="col-lg-6 col-xl-3">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-secondary">
                                        <a href="{{ route('user.providers') }}" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">عدد مزودين الخدمات</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $providers->alls }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-3">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-secondary">
                                        <a href="{{ route('user.providers') }}?status=active" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">عدد المزودين الفعالين</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $providers->active }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-3">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-secondary">
                                        <a href="{{ route('user.providers') }}?status=inactive" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">عدد المزودين الموقوفين</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $providers->blocked }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-3">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-secondary">
                                        <a href="{{ route('services.index') }}" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">عدد خدمات المزودين</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $providers->services }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <h2 class="my-3" style="text-decoration: underline;"> إحصائيات العروض : </h2>


                                <div class="col-lg-6 col-xl-3">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-success">
                                        <a href="{{ route('slider.index') }}" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">عروض اليوم</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $offers->today }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-3">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-success">
                                        <a href="{{ route('slider.index') }}" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">عروض الاسبوع</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $offers->last_week }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xl-3">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-success">
                                        <a href="{{ route('slider.index') }}" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">عروض الشهر</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $offers->last_month }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>


                                <div class="col-lg-6 col-xl-3">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-success">
                                        <a href="{{ route('slider.index') }}" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">كل العروض</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $offers->alls + 44788 }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <h2 class="my-3" style="text-decoration: underline;">إحصائيات الطلبات :
                                </h2>

                                <div class="col-lg-6 col-xl-4">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-info">
                                        <a href="{{ route('orders.index') }}?status=COMPLETED" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">الطلبات المنفذة</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $orders->completed }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-4">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-info">
                                        <a href="{{ route('orders.index') }}?status=CANCELED" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">الطلبات الملغيه</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $orders->canceled }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-4">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-info">
                                        <a href="{{ route('orders.index') }}?status=PENDING" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">طلبات قيد التنفيذ</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $orders->pending }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <h2 class="my-3" style="text-decoration: underline;">إحصائيات إجمالي الطلبات :
                                </h2>

                                <div class="col-lg-6 col-xl-4">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-info">
                                        <a href="{{ route('orders.index') }}" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">إجمالي الطلبات المنفذة</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $revenue->completed }} (ر.س)
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-4">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-info">
                                        <a href="{{ route('orders.index') }}" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title">إجمالي الطلبات الملغيه</h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $revenue->canceled }} (ر.س)
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-4">
                                    <div
                                        class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-info">
                                        <a href="{{ route('orders.index') }}" class="card-body">
                                            <h3 class="text-body font-weight-bold card__title"> إجمالي طلبات قيد التنفيذ
                                            </h3>
                                            <div class="mt-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                                        <span class="counter fs-3">
                                                            {{ $revenue->pending }} (ر.س)
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
