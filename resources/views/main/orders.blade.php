@extends('maintenance')


@section('content')
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h2 class="mb-sm-0">طلبات الصيانة</h2>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-nowrap table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">التاريخ</th>
                             <th scope="col">المشكلة</th>
                            <th scope="col">الخدمة</th>
                            <th scope="col">النوع</th>
                            <th scope="col">الموديل</th>
                            <th scope="col">المزود</th>
                            <th scope="col">النوع</th>
                            <th scope="col">ملاحظة</th>
                            <th scope="col">السعر</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            @if($order->maintenance_type)
                            <tr>
                               <td>{{  $order->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ optional($order->maintenance_request())->issue->name }}</td>
                                <td>{{ optional($order->maintenance_request())->service->name }}</td>
                                <td>{{ optional($order->maintenance_request())->brand->name }}</td>
                                <td>{{ optional($order->maintenance_request())->model->name }}</td>
                                <td>{{ $order->provider->username }}</td>
                                <td>{{ optional($order->maintenance_type)->type->name }}</td>
                                <td>{{ $order->note }}</td>
                                <td>{{ optional($order->maintenance_type)->price }} ر.س</td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
