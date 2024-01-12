@extends('layout.partials.app')

@section('title', 'قائمة طلبات السحب')

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
                        <h4 class="mb-sm-0 font-size-18 ">قائمة طلبات السحب</h4>

                        <form method="GET" class="d-flex align-items-center ms-2">
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
                            <select name="status" class="form-select">
                                <option value="" @if (!request()->has('status')) selected @endif>إختر حالة</option>
                                <option value="confirmed" @if (request()->get('status') === 'confirmed') selected @endif>العمليات الناجحة
                                </option>
                                <option value="not_confirmed" @if (request()->get('status') === 'not_confirmed') selected @endif>العمليات
                                    الغير الناجحة</option>
                            </select>

                            <button class="btn btn-success ms-2">
                                فرز
                            </button>
                            @if (request()->has('status'))
                                <a href="/withdraw" class="btn btn-danger ms-2" style="white-space: nowrap">إعادة
                                    التعيين</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="mb-2 d-flex align-items-end">
                                <div class="me-2" style="position: relative">
                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        تعديل
                                        <img src="{{ asset('/assets/icons/expand.svg') }}" alt="">
                                    </button>
                                    <div class="dropdown-menu">
                                        <button data-bulk-url="{{ route('withdraw.bulk-action') }}"
                                            class="dropdown-item bulk__submit" data-value="confirm" data-action="status">
                                            قبول
                                        </button>
                                        <button data-bulk-url="{{ route('withdraw.bulk-action') }}"
                                            class="dropdown-item bulk__submit" data-value="deny" data-action="status">
                                            رفض
                                        </button>
                                    </div>
                                    <input type="hidden" name="ids" id="ids">
                                </div>
                                @include('partials.search-input', [
                                    'placeholder' => 'إبحث عن طلبات السحب عبر إسم المستخدم',
                                    'hide' => true,
                                ])

                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">
                                                <input type="checkbox" class="form-check" id="checkAll">
                                            </th>
                                            <th scope="col">المزود</th>
                                            <th scope="col"> المبلغ </th>
                                            <th scope="col"> العملة </th>
                                            <th scope="col"> الإميل </th>
                                            <th scope="col"> تمت العملية</th>
                                            <th scope="col"> التاريخ </th>
                                            <th scope="col"> تأكيد </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payments as $payment)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="bulk__check form-check"
                                                        value="{{ $payment->id }}">
                                                </td>
                                                <td>
                                                    {{ $payment->user->username }}
                                                </td>
                                                <td style="white-space: inherit">
                                                    {{ $payment->amount }}
                                                </td>
                                                <td style="white-space: inherit">
                                                    {{ $payment->currency }}
                                                </td>
                                                <td style="white-space: inherit">
                                                    {{ $payment->paypal_email }}
                                                </td>


                                                <td>
                                                    @if ($payment->is_confirmed)
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

                                                <td>
                                                    <ul class="list-inline font-size-20 contact-links mb-0">

                                                        <li class="list-inline-item px-2">
                                                            <a class="accept"
                                                                href="{{ route('withdraw.status', ['id' => $payment->id]) }}"
                                                                title="Accept"><i class="fas fa-check "></i></a>
                                                        </li>

                                                    </ul>
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
