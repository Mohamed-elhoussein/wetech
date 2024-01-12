@extends('layout.partials.app')

@section('title', 'قائمة الإشتراكات')

@section('dashbord_content')
    <div class="page-content">
        <div class="container-fluid">
            @if (session('created'))
                <div class=" w-50 m-auto rounded p-2 bg-success text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('created') }}</div>
            @endif
            @if (session('deleted'))
                <div class=" w-50 m-auto rounded p-2 bg-danger text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('deleted') }}</div>
            @endif
            @if (session('updated'))
                <div class=" w-50 m-auto rounded p-2 bg-warning text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('updated') }}</div>
            @endif
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18 ">قائمة الإشتراكات</h4>

                        <div class="d-flex align-items-end">
                            <div class="position-relative me-2">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <span>عرض</span>
                                    <img src="{{ asset('/assets/icons/expand.svg') }}" alt="">
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="?limit=5">5</a>
                                    <a class="dropdown-item" href="?limit=10">10</a>
                                    <a class="dropdown-item" href="?limit=15">15</a>
                                    <a class="dropdown-item" href="?limit=20">20</a>
                                    <a class="dropdown-item" href="?limit=50">50</a>
                                </div>
                            </div>
                            <form action="" class="d-flex align-items-end">
                                <div class="form-group">
                                    <label for="from" class="form-label">من</label>
                                    <input type="date" value="{{ request()->get('from', '') }}" name="from"
                                        class="form-control">
                                </div>

                                <div class="form-group ms-2">
                                    <label for="to" class="form-label">إلى</label>
                                    <input type="date" value="{{ request()->get('to', '') }}" name="to"
                                        class="form-control">
                                </div>

                                <button class="btn btn-primary ms-2">
                                    فلتر
                                </button>
                            </form>
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
                                            <th scope="col">المستخدم</th>
                                            <th scope="col">المبلغ</th>

                                            <th scope="col">وسيلة الدفع</th>
                                            <th scope="col">ينتهي في </th>
                                            <th scope="col"> التاريخ </th>
                                            <th scope="col"> عدد الأيام </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subscribers as $subscriber)
                                            <tr>
                                                <td>
                                                    {{ $subscriber->user->username }}
                                                </td>
                                                <td style="white-space: inherit">
                                                    {{ $subscriber->amount }}
                                                </td>

                                                <td>
                                                    {{ $subscriber->method }}
                                                </td>


                                                <td style="font-weight: bold">
                                                    <div>
                                                        <a
                                                            class="badge badge-soft-danger font-size-11 m-1">{{ date('d-y-M', strtotime($subscriber->die_at)) }}</a>

                                                    </div>
                                                </td>

                                                <td style="font-weight: bold">
                                                    <div>
                                                        <a
                                                            class="badge badge-soft-primary font-size-11 m-1">{{ date('d-y-M', strtotime($subscriber->created_at)) }}</a>

                                                    </div>
                                                </td>
                                                <td style="font-weight: bold">
                                                    {{ $subscriber->created_at->diffInDays($subscriber->die_at) }}
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $subscribers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
