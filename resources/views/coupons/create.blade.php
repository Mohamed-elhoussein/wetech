@extends('layout.partials.app')

@section('title', '')

@section('dashbord_content')

    <body data-sidebar="dark">
        <!-- Begin page -->
        <div id="layout-wrapper">
            @include('layout.partials.nav')

            @include('layout.partials.sidebare')



            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
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

                                        <form class="m-5" action="" method="POST">
                                            @csrf
                                            <div class="row mb-4">
                                                <label for="horizontal-coupon-input"
                                                    class="col-sm-3 col-form-label fs-4">قسيمة الخصم</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="coupon" placeholder="أدخل اسم فئة الخدمة"
                                                        class="form-control" id="horizontal-coupon-input">
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label for="horizontal-discount-input"
                                                    class="col-sm-3 col-form-label fs-4">خصم</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="discount" placeholder="قيمة الخصم"
                                                        class="form-control" id="horizontal-discount-input">
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <label for="horizontal-expired_at-input"
                                                    class="col-sm-3 col-form-label fs-4">ينتهي في</label>
                                                <div class="col-sm-9">
                                                    <input type="date" name="expired_at" placeholder=""
                                                        class="form-control" id="horizontal-expired_at-input">
                                                </div>
                                            </div>


                                            <div class="row d-flex justify-content-end mb-4">
                                                <button type="submit"
                                                    class=" col-sm-6 col-md-2 btn btn-primary w-md fs-4 p-1">أضف
                                                    الخصم</button>
                                            </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->



        </div>
        <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->



        </div>
        <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

    @endsection
