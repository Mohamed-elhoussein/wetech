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
                        @if (session('created'))
                            <div
                                class=" w-50 m-auto rounded p-2 bg-success text-white bg-gradient text-center zindex-fixed fs-4">
                                {{ session('created') }}</div>
                        @endif
                        @if (session('deleted'))
                            <div
                                class=" w-50 m-auto rounded p-2 bg-danger text-white bg-gradient text-center zindex-fixed fs-4">
                                {{ session('deleted') }}</div>
                        @endif
                        @if (session('updated'))
                            <div
                                class=" w-50 m-auto rounded p-2 bg-warning text-white bg-gradient text-center zindex-fixed fs-4">
                                {{ session('updated') }}</div>
                        @endif
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18 ">قائمة الخدمات</h4>

                                    <div class="">
                                        <a href="/coupons/create" class="btn btn-success w-md fs-5">أضف خصم جديد</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table align-middle table-nowrap table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col">الخصم</th>
                                                        <th scope="col">قيمة الخصم</th>
                                                        <th scope="col">وقت الانشاء</th>
                                                        <th scope="col"> ينتهي في</th>
                                                        <th scope="col">تعديل</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($coupons as $coupon)
                                                        <tr>
                                                            <td>
                                                                <h5 class="font-size-14 mb-1"><a href="javascript: void(0);"
                                                                        class="text-dark">{{ $coupon->coupon }}</a>
                                                                </h5>
                                                            </td>
                                                            <td>{{ $coupon->discount }}</td>
                                                            <td>
                                                                <div>
                                                                    <a href="javascript: void(0);"
                                                                        class="badge badge-soft-primary font-size-11 m-1">{{ date('d-y-M', strtotime($coupon->created_at)) }}</a>

                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <a href="javascript: void(0);"
                                                                        class="badge badge-soft-danger font-size-11 m-1">{{ date('d-Y-M', strtotime($coupon->expired_at)) }}</a>

                                                                </div>
                                                            </td>

                                                            <td>
                                                                <ul class="list-inline font-size-20 contact-links mb-0">
                                                                    <li class="list-inline-item px-2">
                                                                        <a class="delete"
                                                                            href="/coupons/delete/{{ $coupon->id }}"
                                                                            title="Delete"><i
                                                                                class="bx bx-trash-alt "></i></a>
                                                                    </li>
                                                                    <li class="list-inline-item px-2">
                                                                        <a href="/coupons/edit/{{ $coupon->id }}"
                                                                            title="Edit"><i class="bx bx-pencil"></i></a>
                                                                    </li>

                                                                </ul>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

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
    @endsection
