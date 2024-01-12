@extends('layout.store')

@section('content')
    <section class="main-banner bg-white pt-4">
        <div class="container">
            <div id="carouselExampleFade" class="carousel slide carousel-fade mb-4" data-bs-ride="carousel">
                <div class="carousel-inner rounded">
                    @foreach ($sliders as $slider)
                        <div class="carousel-item active">
                           <a><img src="{{ $slider->image }}" class="d-block w-100" alt="{{ $slider->text }}"></a>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <div class="row">

                @foreach ($categories as $category)
                    <div class="col listing-item">
                        <a href="{{ route('store.by_category', ['category' => $category]) }}">
                            <img src="{{ $category->icon }}" alt="#" class="img-fluid rounded-3">
                            <span>{{ $category->name }}</span>
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="container py-5">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0 fw-bold">اكتشف مجموعة منتجاتنا</h5>
                <a class="text-decoration-none text-success" href="listing.html">
                    مشاهدة الكل
                    <i class="bi bi-arrow-right-circle-fill ms-1"></i>
                </a>
            </div>
            <div class="row row-cols-1 row-cols-md-4 g-4 homepage-products-range">
                @foreach ($top_products as $product)
                    <div class="col product-item">
                        <div class="text-center position-relative border rounded pb-4">
                            <div class="img-wrapper p-3">
                                <img src="{{ json_decode($product->images)[0] }}" class="img-fluid"
                                    alt="{{ $product->name }} image">
                            </div>
                            <div class="listing-card-body pt-0 px-3 product-content">
                                <h6 class="card-title mb-1 fs-14">{{ $product->name }}</h6>
                                <p class="mb-2">
                                    البائع: {{ $product->provider->username }}
                                </p>
                                <p class="card-text small text-success" dir="rtl">
                                    <span>{{ !$product->is_offer ? $product->price : $product->offer_price }} ر.س</span>
                                    <span>يشمل الضريبة</span>
                                </p>
                            </div>
                            <div class="mt-3 text-right d-flex align-items-center px-3">
                                @auth
                                    @if (!in_array($product->id, $product_cart_ids))
                                        <a style="font-size: 12px" href="javascript:;" data-product-id="{{ $product->id }}"
                                            class="add_to_cart w-100 btn btn-success">
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
                                        class="w-100 btn btn-success">
                                        <i class="bi bi-cart-plus"></i>
                                        أضف للسلة
                                    </a>
                                @endauth
                            </div>
                            <div class="mt-2 px-3">
                                <a href="javascript:;"
                                    data-url="{{ route('store.confirmation', ['product' => $product]) }}"
                                    class="w-100 ms-2 btn btn-success product-order-btn">
                                    <i class="bi bi-chat-fill"></i>
                                    اطلبها
                                </a>
                            </div>
                            {{-- <a href="{{ route('store.products') }}" class="stretched-link"></a> --}}
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="d-flex">
                <a href="{{ route('store.products') }}" class="btn btn-success mx-auto mt-4">
                    إكتشف المزيد من المنتجات
                    <i class="bi bi-arrow-right-circle-fill rotate-180 ms-1"></i>
                </a>
            </div>
        </div>
    </section>
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
