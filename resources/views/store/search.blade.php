@extends('layout.store')

@section('content')

    <section class="bg-success">
        <div class="container py-4">
            <div class="row align-items-center gap-2">
                <div class="col-12">
                    <form action="">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="input-group input-group-lg border-0 p-1 bg-white shadow-sm rounded-3">
                                <span class="input-group-text bg-white border-0"><i class="icofont-search"></i></span>
                                <input name="q" value="{{ request()->get('q') }}" type="text"
                                    class="form-control bg-white border-0 ps-0" placeholder="ابحث عن منتج"
                                    aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                            <button class="btn d-flex align-items-center text-white fs-6">
                                <div class="d-flex justify-content-center"><i class="icofont-search"></i></div>
                                <p class="d-flex justify-content-center mb-0 me-2">بحث</p>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @if (is_array(session('searchs')))
        <div class="container pt-5">
            <div class="row mb-5">
                <div class="col-12 mb-3">
                    <h5 class="mb-0 fw-bold">عمليات البحث الأخيرة</h5>
                </div>
                <div class="col-12">
                    @if (is_array(session('searchs')))
                        @foreach (session('searchs') as $search)
                            <a href="{{ route('store.search', ['q' => $search]) }}"
                                class="badge trending-badge badge-soft-light rounded-pill text-bg-light"><i
                                    class="bi bi-clock me-1"></i> {{ $search }}</a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($products->count() > 0)
        <div class="container py-5">
            <h3 class="mb-2 fw-bold">نتائج البحث</h3>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach ($products as $product)
                    <div class="col">
                        <div class="bg-white listing-card shadow-sm rounded-3 p-3 position-relative">
                            <div class="img-wrapper" style="height: 260px;">
                                <img style="height: 100%; width: 100%; object-fit: cover;"
                                    src="{{ !is_array($product->images) ? json_decode($product->images)[0] : $product->images[0] }}"
                                    class="border img-fluid rounded-3" alt="...">
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
                                            class="add_to_cart w-100 ms-2 btn btn-success">
                                            <i class="bi bi-cart-plus ms-1"></i>
                                            أضف للسلة
                                        </a>
                                    @else
                                        <a style="font-size: 12px" href="javascript:;" data-product-id="{{ $product->id }}"
                                            class="remove_from_cart w-100 ms-2 btn btn-warning">
                                            <i class="bi bi-cart-dash ms-1"></i>
                                            إزالة للسلة
                                        </a>
                                    @endif
                                @else
                                    <a style="font-size: 12px" href="{{ route('store.login') }}"
                                        class="w-100 ms-2 btn btn-success">
                                        <i class="bi bi-cart-plus ms-1"></i>
                                        أضف للسلة
                                    </a>
                                @endauth
                                <a style="font-size: 12px"
                                    href="{{ !auth()->check() ? route('store.login') : "tel:{$product->provider->country->country_code}{$product->provider->number_phone}" }}"
                                    class="w-100 btn btn-outline-success">
                                    <i class="bi bi-phone-fill ms-1"></i>
                                    مكالمة
                                </a>
                            </div>
                            <div class="mt-2">
                                <a href="javascript:;"
                                    data-url="{{ route('store.confirmation', ['product' => $product]) }}"
                                    class="w-100 ms-2 btn btn-success product-order-btn">
                                    <i class="bi bi-chat-fill ms-1"></i>
                                    اطلبها
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-5 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    @elseif ($products->count() == 0 && !is_null(request()->get('q')))
        <div class="container py-5">
            <h3 class="mb-2 fw-bold text-danger text-center">
                لا توجد نتائج {{ request()->get('q') }}
            </h3>
        </div>
    @endif

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
