@extends('layout.partials.app')

@section('title', 'قائمة المدفوعات')

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
                        <h4 class="mb-sm-0 font-size-18 ">قائمة المدفوعات</h4>

                        <div class="d-flex align-items-center">
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
                            <span onclick="exportTasks(event.target);" data-href="{{ route('payments.export') }}"
                                id="export" class="btn btn-primary">
                                إستخراج
                            </span>

                            <form method="GET" class="d-flex align-items-center ms-2">
                                <select name="status" class="form-select">
                                    <option value="" @if (!request()->has('status')) selected @endif>إختر حالة</option>
                                    <option value="paid" @if (request()->get('status') === 'paid') selected @endif>العمليات الناجحة
                                    </option>
                                    <option value="not_paid" @if (request()->get('status') === 'not_paid') selected @endif>العمليات
                                        الغير الناجحة</option>
                                </select>

                                <button class="btn btn-success ms-2">
                                    فرز
                                </button>
                                @if (request()->has('status'))
                                    <a href="/payments" class="btn btn-danger ms-2" style="white-space: nowrap">إعادة
                                        التعيين</a>
                                @endif
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

                            <div class="mb-2">

                                @include('partials.search-input', [
                                    'placeholder' => 'إبحث عن المدفوعات عبر إسم المستخدم',
                                ])

                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">المستخدم</th>
                                            <th scope="col"> المبلغ </th>
                                            <th scope="col"> العملة </th>
                                            <th scope="col"> وسيلة الدفع</th>
                                            <th scope="col"> رمز العملية</th>
                                            <th scope="col"> نجحت العملية</th>
                                            <th scope="col"> التاريخ </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payments as $payment)
                                            <tr>
                                                <td>
                                                    {{ $payment->user->username }}
                                                </td>
                                                <td style="white-space: inherit">
                                                    {{ $payment->amount }}
                                                </td>
                                                <td style="white-space: inherit">
                                                    {{ $payment->currency }}
                                                </td>
                                                <td>
                                                    {{ $payment->method }}
                                                </td>
                                                <td>
                                                    {{ $payment->payment_id ?: $payment->transaction_id }}
                                                </td>
                                                <td>
                                                    @if ($payment->paid)
                                                        <span
                                                            class=" border  badge-soft-success px-1 rounded border-2 border-success ">
                                                            {{ 'نعم' }}
                                                        @else
                                                            <span
                                                                class=" border  badge-soft-danger px-1 rounded border-2 border-danger ">
                                                                {{ ' لا' }}
                                                    @endif
                                                    </span>
                                                </td>
                                                <td style="font-weight: bold">
                                                    <div>
                                                        <a
                                                            class="badge badge-soft-primary font-size-11 m-1">{{ date('d-y-M', strtotime($payment->created_at)) }}</a>

                                                    </div>
                                                </td>


                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $payments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
