@extends('layout.partials.app')

@section('title', 'نظرة عامة')

@section('dashbord_content')
    <div class="page-content">
        @if (session('status'))
            <div class="alert alert-success fs-5 text-center">
                {{ session('status') }}
            </div>
        @endif
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">نظرة عامة</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-6 col-xl-3">
                    <div class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-primary">
                        <a href="{{ route('services.index') }}" class="card-body">
                            <h3 class="text-body font-weight-bold card__title">عدد الخدمات</h3>
                            <div class="mt-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                        <span class="counter fs-3">{{ $services }}</span>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-3">
                    <div class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-success">
                        <a href="{{ route('user.providers') }}" class="card-body">
                            <h3 class="text-body font-weight-bold card__title">عدد مزودي الخدمة</h3>
                            <div class="mt-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                        <span class="counter fs-3">{{ $statistics->providers }}</span>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-3">
                    <div class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-secondary">
                        <a href="{{ route('user.users') }}" class="card-body">
                            <h3 class="text-body font-weight-bold card__title">عدد الأعضاء</h3>
                            <div class="mt-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                        <span class="counter fs-3">{{ $statistics->users }}</span>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-3">
                    <div class="card card-custom gutter-b bg-white border-0 theme-circle theme-circle-info">
                        <a href="{{ route('orders.index') }}" class="card-body">
                            <h3 class="text-body font-weight-bold card__title">عدد الطلبات</h3>
                            <div class="mt-3">
                                <div class="d-flex align-items-center">
                                    <span class="text-dark font-weight-bold font-size-h1 mr-3">
                                        <span class="counter fs-3">{{ $orderCount }}</span>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body border border-2">
                                    <h4 class="text-center mb-4">عدد الطلبات حسب الحالة</h4>
                                    <div class="p-4">
                                        <canvas id="orderStatus" style="width: 100%"></canvas>
                                    </div>

                                    <div class="mt-4">
                                        <div class="row">
                                            <div class="col-8 mx-auto">
                                                <div class="row">
                                                    @foreach (App\Enum\OrderStatus::toArrayWithColors() as $status => $color)
                                                        <div class="col-6">
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"
                                                                    style="height: 12px; width: 12px; border-radius: 0.25rem;background-color: {{ $color }}"></span>
                                                                <span>{{ __($status) }}</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body border border-2 pb-0">
                                    <h4 class="text-center mb-4">آخر تسجيل دخول</h4>

                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>إسم المستخدم</th>
                                                    <th>آخر تسجيل الدخول</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($lastLoginUsers as $user)
                                                    <tr>
                                                        <td>{{ $user->username }}</td>
                                                        <td>{{ $user->last_login->format('M d, Y H:i') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body border border-2">
                            <h2>لديك {{ $ordersCount }} طلبات</h2>
                            <canvas id="ordersChart" style="width: 100%"></canvas>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body border border-2">
                            <h4 class="card-title mb-4">آخر الطلبات</h4>
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="align-middle">مزود الخدمة</th>
                                            <th class="align-middle">الخدمة</th>
                                            <th class="align-middle">تاريخ</th>
                                            <th class="align-middle">الثمن</th>
                                            <th class="align-middle"> حالة الدفع</th>
                                            <th class="align-middle">طريقة الدفع</th>
                                            {{-- <th class="align-middle">View Details</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td><a href="javascript: void(0);" class="text-body fw-bold"></a>
                                                    {{ $order->provider->username }}</td>
                                                <td>{{ $order->provider_service->title }}</td>
                                                <td>
                                                    {{ optional($order->created_at)->diffForHumans() }}
                                                </td>
                                                <td>
                                                    <b> {{ $order->price }}</b>(ر.س)
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-pill badge-soft-success font-size-11">Paid</span>
                                                </td>
                                                <td>
                                                    <i class="fab fa-cc-visa me-1"></i> Visa
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- end table-responsive -->
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body border border-2">
                            <h4 class="card-title mb-4">آخر التحويلات</h4>
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">الزبون</th>
                                            <th scope="col">الخدمة</th>
                                            <th scope="col">النوع</th>
                                            <th scope="col">المبلغ</th>
                                            <th scope="col">وقت التحويل</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $transaction)
                                            <tr>
                                                <td>
                                                    {{ $transaction->user->username }}
                                                </td>
                                                <td>
                                                    {{ optional(optional($transaction->order)->provider_service)->title ?? 'غير محدد' }}
                                                </td>
                                                <td style="white-space: inherit">
                                                    {{ $transaction->type }}
                                                </td>
                                                <td style="font-weight: bold">
                                                    {{ $transaction->amount . ' ريال  ' }}
                                                </td>

                                                <td style="white-space: inherit">
                                                    <div>
                                                        <a href="javascript: void(0);"
                                                            class="badge badge-soft-primary font-size-11 m-1">{{ date('d-y-M', strtotime($transaction->created_at)) }}</a>

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body border border-2">
                            <h4 class="card-title mb-4">تقييمات التطبيق</h4>
                            <div class="d-flex align-items-center w-100 flex-row-reverse">
                                <div class="d-flex flex-column align-items-center ms-4">
                                    <span class="text-warning"
                                        style="font-size: 80px; font-weight: 900; line-height: 4rem;">
                                        {{ $avgRates }}
                                    </span>
                                    <div class="d-flex align-items-center flex-row-reverse" style="margin-top: 0.7rem">
                                        {!! starsFromNumber($avgRates) !!}
                                    </div>
                                </div>
                                <div class="w-100">
                                    {!! showRates($stars) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body border border-2">
                            <h4 class="card-title mb-4">آخر مزودي الخدمات</h4>
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 70px;">#</th>
                                            <th scope="col">الإسم</th>
                                            <th scope="col">البلد</th>
                                            <th scope="col">وقت الإنشاء</th>
                                            <th scope="col">الحالة</th>
                                            <th scope="col">العمولة</th>
                                            <th scope="col">الحساب</th>
                                            <th scope="col">التحقق</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($providers as $user)
                                            <tr class="commission" data-info="{{ $user->id }}">
                                                <td>
                                                    <div class="avatar-xs">
                                                        <span class="avatar-title avatar-sm rounded-circle">
                                                            <img class="avatar-sm rounded"
                                                                src="{{ url($user->avatar) }}" alt="avatar">
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 class="font-size-14 mb-1"><a href="javascript: void(0);"
                                                            class="text-dark">{{ $user->username }}</a>
                                                    </h5>

                                                </td>
                                                <td>{{ optional($user->country)->name ?: 'لا توجد' }}</td>
                                                <td>
                                                    <div>
                                                        <a href="javascript: void(0);"
                                                            class="badge badge-soft-primary font-size-11 m-1">{{ date('d-y-M', strtotime($user->created_at)) }}</a>

                                                    </div>
                                                </td>
                                                <td>
                                                    @if (!$user->is_blocked)
                                                        <span
                                                            class=" border  badge-soft-success px-1 rounded border-2 border-success ">
                                                            {{ 'يعمل' }}
                                                        @else
                                                            <span
                                                                class=" border  badge-soft-danger px-1 rounded border-2 border-danger ">
                                                                {{ 'تم حظره' }}
                                                    @endif
                                                    </span>
                                                </td>
                                                <td style="font-weight: bold">
                                                    {{ optional($user->commission)->commission ?: 'الإعتيادية' }}</td>
                                                <td style="font-weight: bold">{{ $user->balance ?: 0 }}</td>

                                                <td>
                                                    @if ($user->verified)
                                                        <span
                                                            class=" border  badge-soft-primary px-1 rounded border-2 border-primary ">
                                                            {{ 'تم التحقق' }}
                                                        @else
                                                            <span
                                                                class=" border  badge-soft-warning px-1 rounded border-2 border-warning ">
                                                                {{ 'ليس بعد ' }}
                                                    @endif
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- end table-responsive -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection



@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

    <script defer>
        const ctx1 = document.getElementById('ordersChart');
        const ctx2 = document.getElementById('orderStatus');
        // const ctx3 = document.getElementById('providerStatus');

        const ordersChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'طلبات',
                    data: {!! json_encode(array_values($data)) !!},
                    fill: {
                        target: 'origin',
                        above: 'rgb(176, 206, 255)',
                    },
                    borderColor: 'rgb(59, 133, 255)',
                    borderWidth: 3,
                    tension: 0.4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        }
                    },
                    y: {
                        grid: {
                            display: false,
                        }
                    }
                }
            }
        });

        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($dataOrdersStatus)) !!},
                datasets: [{
                    label: 'طلبات',
                    data: {!! json_encode(array_values($dataOrdersStatus)) !!},
                    backgroundColor: [
                        'rgb(0 248 104)',
                        'rgb(255 231 0)',
                        'rgb(255 80 80)',
                    ],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false,
                    }
                },
            }
        });
    </script>
@endsection
