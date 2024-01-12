@extends('layout.partials.app')

@section('title', 'قائمة الطلبات')

@section('dashbord_content')
    <div class="page-content">
        <div class="container-fluid">
            @if (session('created'))
                <div class=" w-50 m-auto rounded p-2 bg-success text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('created') }}
                </div>
            @endif
            @if (session('deleted'))
                <div class=" w-50 m-auto rounded p-2 bg-danger text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('deleted') }}
                </div>
            @endif
            @if (session('updated'))
                <div class=" w-50 m-auto rounded p-2 bg-warning text-white bg-gradient text-center zindex-fixed fs-4">
                    {{ session('updated') }}
                </div>
            @endif
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-end justify-content-between">
                        <h4 class="mb-sm-0 font-size-18 ">قائمة الطلبات</h4>

                        <form class="d-flex align-items-end" method="GET">
                            <div class="d-flex flex-column me-2">
                                <label for="limit">عرض</label>
                                <select name="limit" id="limit" class="form-select">
                                    <option value="">عرض</option>
                                    <option @if(request()->get('limit') == "5") selected @endif value="5">5</option>
                                    <option @if(request()->get('limit') == "10") selected @endif value="10">10</option>
                                    <option @if(request()->get('limit') == "15") selected @endif value="15">15</option>
                                    <option @if(request()->get('limit') == "20") selected @endif value="20">20</option>
                                    <option @if(request()->get('limit') == "50") selected @endif value="50">50</option>
                                </select>
                            </div>

                            <div class="d-flex flex-column">
                                <label for="period">إظهار الفلتر</label>
                                <select name="period" id="period" class="form-select">
                                    <option value="">إظهار الفلتر</option>
                                    <option @if(request()->get('period') == "today") selected @endif value="today">اليوم</option>
                                    <option @if(request()->get('period') == "yesterday") selected @endif value="yesterday">الأمس</option>
                                    <option @if(request()->get('period') == "current_week") selected @endif value="current_week">هذا الأسبوع</option>
                                    <option @if(request()->get('period') == "previous_week") selected @endif value="previous_week">الأسبوع الماضي</option>
                                    <option @if(request()->get('period') == "current_month") selected @endif value="current_month">هذا الشهر</option>
                                    <option @if(request()->get('period') == "previous_month") selected @endif value="previous_month">الشهر الماضي</option>
                                </select>
                            </div>

                            <div class="ms-2">
                                <label for="order-status" class="form-label">الحالة</label>
                                <select style="display: block !important" name="status" id="order-status"
                                    class="form-select">
                                    <option value="" selected>إختر حالة</option>

                                    @foreach (App\Enum\OrderStatus::toArray() as $status)
                                        <option value="{{ $status }}"
                                            @if ($status === request()->get('status')) selected @endif>{{ __($status) }}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group ms-2">
                                <label for="from" class="form-label">من</label>
                                <input @if(request()->get('period') && request()->get('period') !== '') disabled @endif type="date" value="{{ request()->get('from', '') }}" name="from"
                                    class="form-control">
                            </div>

                            <div class="form-group ms-2">
                                <label for="to" class="form-label">إلى</label>
                                <input @if(request()->get('period') && request()->get('period') !== '') disabled @endif type="date" value="{{ request()->get('to', '') }}" name="to"
                                    class="form-control">
                            </div>

                            <button class="btn btn-primary ms-2">
                                فلتر
                            </button>

                            @if (request()->has('from') || request()->has('to'))
                                <a href="{{ route('orders.index') }}" class="btn btn-danger ms-2">
                                    إعادة تعيين
                                </a>
                            @endif

                            <span onclick="exportTasks(event.target);" data-href="{{ route('orders.export') }}"
                                id="export" class="btn btn-primary ms-2">
                                إستخراج
                            </span>

                        </form>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">

                <div class="col-lg-6 col-xl-4">
                    <div class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-success">
                        <a href="{{ route('orders.index') }}?status=COMPLETED" class="card-body">
                            <h3 class="text-body font-weight-bold card__title">مكتمل</h3>
                            <div class="mt-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                        <span class="counter fs-3">
                                            {{ $ordersGroupedByStatus['COMPLETED'] ?? 0 }}
                                            {{ __(pluralize('order', $ordersGroupedByStatus['COMPLETED'] ?? 0)) }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 col-xl-4">
                    <div class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-primary">
                        <a href="{{ route('orders.index') }}?status=COMPLETED" class="card-body">
                            <h3 class="text-body font-weight-bold card__title">في الإنتظار</h3>
                            <div class="mt-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                        <span class="counter fs-3">
                                            {{ $ordersGroupedByStatus['PENDING'] ?? 0 }}
                                            {{ __(pluralize('order', $ordersGroupedByStatus['PENDING'] ?? 0)) }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 col-xl-4">
                    <div class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-secondary">
                        <a href="{{ route('orders.index') }}?status=COMPLETED" class="card-body">
                            <h3 class="text-body font-weight-bold card__title">ملغي</h3>
                            <div class="mt-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                        <span class="counter fs-3">
                                            {{ $ordersGroupedByStatus['CANCELED'] ?? 0 }}
                                            {{ __(pluralize('order', $ordersGroupedByStatus['CANCELED'] ?? 0)) }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">رقم الطلب</th>
                                            <th scope="col">المستفيد من الخدمة</th>
                                            <th scope="col">مقدم الخدمة</th>
                                            <th scope="col"> الخدمة</th>
                                            <th scope="col"> حالة الطلب</th>
                                            <th scope="col"> الثمن</th>
                                            <th scope="col">في</th>
                                            <th scope="col">تعديل</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>
                                                    {{ $order->id }}
                                                </td>
                                                <td>
                                                    {{ $order->user->username }}
                                                </td>
                                                <td>
                                                    {{ $order->provider->username }}
                                                </td>
                                                <td>
                                                    {{ $order->provider_service->title }}
                                                </td>
                                                <td>
                                                    <span class="badge {{ badgeColorFromStatus($order->status) }}">
                                                        {{ __($order->status) }}
                                                    </span>
                                                </td>
                                                <td style="font-weight: bold">
                                                    {{ $order->provider->country->unit ? $order->price . '  ' . $order->provider->country->unit : $order->price . '  ر.س ' }}
                                                </td>

                                                <td>
                                                    <div>
                                                        <a href="javascript: void(0);"
                                                            class="badge badge-soft-primary font-size-11 m-1">{{ date('d-y-M', strtotime($order->created_at)) }}</a>

                                                    </div>
                                                </td>

                                                <td>
                                                    <ul class="list-inline font-size-20 contact-links mb-0">
                                                        <li class="list-inline-item px-2">
                                                            <a class="delete"
                                                                href="/orders/delete/{{ $order->id }}" title="Delete"><i
                                                                    class="bx bx-trash-alt "></i></a>
                                                        </li>

                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection


@section('scripts')

<script>

    $('#period').change(function () {
        $('[name="from"]').prop('disabled', $(this).val() !== '')
        $('[name="to"]').prop('disabled', $(this).val() !== '')
    })

</script>

@endsection
