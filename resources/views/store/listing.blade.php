@extends('layout.store')

@section('content')
    <section class="bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="py-5 d-flex align-items-center gap-4">
                    @isset($category)
                        <div class="d-none d-md-block">
                            <img alt="#" src="{{ $category->icon }}" class="img-fluid ch-100 rounded-pill bg-light p-4">
                        </div>
                        <div class="text-md-start text-center">
                            <h2 class="mb-2 fw-bold">{{ $category->name }}</h2>
                            <p class="lead m-0 text-right"><i class="bi bi-shop me-2"></i> {{ $products->total() }} منتج</p>
                        </div>
                    @else
                        <div class="text-md-start text-center">
                            <h2 class="mb-2 fw-bold">جميع المنتوجات</h2>
                            <p class="lead m-0 text-right"><i class="bi bi-shop me-2"></i> {{ $products->total() }} منتج</p>
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach ($products as $product)
                <div class="col">
                    <div class="bg-white listing-card shadow-sm rounded-3 p-3 position-relative">
                        <div class="img-wrapper" style="height: 260px;">
                            <img style="height: 100%; width: 100%; object-fit: cover;"
                                src="{{ !is_array($product->images) ? json_decode($product->images)[0] : $product->images[0] }}"
                                class="img-fluid rounded-3" alt="...">
                        </div>
                        <div class="listing-card-body pt-3 product-content">
                            <h6 class="card-title fw-bold mb-1">{{ $product->name }}</h6>
                            <p class="mb-2">
                                البائع: {{ $product->provider->username }}
                            </p>
                            <p class="card-text small text-success" dir="rtl">
                                <span>{{ !$product->is_offer ? $product->price : $product->offer_price }} ر.س</span>
                                <span>يشمل الضريبة</span>
                            </p>
                        </div>
                        <div class="mt-3 text-right d-flex align-items-center">
                            @auth
                                @if (!in_array($product->id, $product_cart_ids))
                                    <a style="font-size: 12px" href="javascript:;" data-product-id="{{ $product->id }}"
                                        class="add_to_cart w-100 btn btn-success">
                                        <i class="bi bi-cart-plus ms-1"></i>
                                        أضف للسلة
                                    </a>
                                @else
                                    <a style="font-size: 12px" href="javascript:;" data-product-id="{{ $product->id }}"
                                        class="remove_from_cart w-100 btn btn-warning">
                                        <i class="bi bi-cart-dash ms-1"></i>
                                        إزالة للسلة
                                    </a>
                                @endif
                            @else
                                <a style="font-size: 12px" href="{{ route('store.login') }}"
                                    class="w-100 btn btn-success">
                                    <i class="bi bi-cart-plus ms-1"></i>
                                    أضف للسلة
                                </a>
                            @endauth
                            {{-- <a style="font-size: 12px"
                                href="{{ !auth()->check() ? route('store.login') : "tel:{$product->provider->country->country_code}{$product->provider->number_phone}" }}"
                                class="w-100 btn btn-outline-success">
                                <i class="bi bi-phone-fill ms-1"></i>
                                مكالمة
                            </a> --}}
                        </div>
                        <div class="mt-2">
                            <a href="javascript:;" data-url="{{ route('store.confirmation', ['product' => $product]) }}"
                                class="w-100 btn btn-success product-order-btn">
                                <i class="bi bi-chat-fill ms-1"></i>
                                اطلبها
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5 d-flex justify-content-center" dir="ltr">
            {{ $products->links() }}
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('.product-order-btn').click(function() {
            $.ajax({
                url: $(this).data('url'),
                success: r => {
                    $('body').append(r)
                    $('#product-confirmation-modal').modal('show')
                }
            })
        })

        $(document).on('hidden.bs.modal', '#product-confirmation-modal', function() {
            $('#product-confirmation-modal').remove()
        })
    </script>
@endsection
