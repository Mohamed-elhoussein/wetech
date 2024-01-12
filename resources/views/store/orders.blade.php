@extends('layout.store')

@section('content')
    <section class="bg-white">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="py-5 d-flex align-items-center gap-4">
                        <div class="text-md-end text-center">
                            <h2 class="mb-2 fw-bold">طلباتي</h2>
                            <p class="lead m-0 text-right">
                                <i class="bi bi-bag ms-1"></i>
                                <span>
                                    لديك {{ $orders->total() }} من الطلبات
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        
        $order_status = [
            'PENDING' => 'في الإنتظار',
            'CANCELED' => 'ملغية',
            'COMPLETED' => 'تمت',
        ];
        
        $order_status_color = [
            'PENDING' => 'warning',
            'CANCELED' => 'danger',
            'COMPLETED' => 'success',
        ];
        
    @endphp

    <section class="container mt-4">
        <div class="row">
            @foreach ($orders as $order)
                <div class="col-md-6 col-12">
                    <div class="d-flex w-100 align-items-center mb-3 bg-white border shadow-sm rounded-3 w-100 p-3">
                        <div style="width: 100px; height: 100px; flex-shrink: 0;" class="border rounded-2 overflow-hidden">
                            <img style="object-fit: cover;" class="w-100 h-100"
                                src="{{ !is_array($order->product->images) ? json_decode($order->product->images)[0] : $order->product->images[0] }}"
                                alt="">
                        </div>
                        <div class="me-3">
                            <h6>
                                المنتوج: {{ $order->product->name }}
                            </h6>
                            <h6>
                                البائع: {{ $order->product->provider->username }}
                            </h6>
                            <h6>
                                بتاريخ: {{ $order->created_at->translatedFormat('d M Y') }}
                            </h6>
                            <span class="d-block text-success">
                                المجموع: {{ $order->price }} ر.س
                            </span>
                            <span class="badge badge-soft-{{ $order_status_color[$order->status] }} py-1 mt-2">
                                {{ $order_status[$order->status] }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <div class="text-center mt-5 d-flex justify-content-center" dir="ltr">
        {{ $orders->links() }}
    </div>
@endsection
