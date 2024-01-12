@extends('layout.store')

@section('content')


<style>
    .searchbar input {
    width: 100%;
    border: 1px solid #61B7E0;
    border-radius: 3px;
    padding: 10px;
    padding-right: 20px;
}

.searchbar {
    padding: 15px;
    padding-bottom: 0;
}

.ramadan-slider {
    padding: 10px;
}

.ramadan-slider img {
    width: 100%;
}

.icon-box {
    text-align: center;
    padding: 10px;
}

ul.termslinks a {
    text-decoration: none;
    display: block;
    text-align: center;
    color: white;
    font-size: 17px;
}

ul.termslinks li {list-style: none;padding: 10px 0;}

ul.termslinks {
    margin-bottom: 100px;
}

.links {
    background: #5576A4;
    padding: 25px;
}

.feautrss h3 {
    color: #18A2D4;
    font-weight: bold;
    margin-top: 45px;
    padding-right: 45px;
}

.navbar-brand {
    border: 0;
}

.navbar-toggler {
    border: 0;
}

section.feautrss.bg-white {
    overflow: hidden;
}

.icon-box img {
    margin-bottom: 15px;
}

.carousels .boxs {
    width: 33.33%;
}

.carousels {
    display: flex;
}

.carousels .boxs img {
    width: 70%;
    margin: 0 auto;
    display: block;
}

.carousels .boxs span {
    display: block;
    text-align: center;
    margin-top: 11px;
    font-weight: bold;
}

.icon-box img {
    width: 65px;
}

img.logo-footer {
    width: 110px;
}

footer {
    background: #5576a4;
    text-align: center;
    padding-top: 30px;
}

.addtocartbtn {
    background: #1BA2D3 !important;
    border-radius: 5px !important;
}


@media screen and (max-width: 767px) {
    .col.product-item {
        width: 50%;
        padding: 10px;
    }
    .product-item .img-wrapper {
    margin-bottom: 15px;
    }
    .product-item img {
        border:0;
    }
}


</style>

<section class="  bg-white pt-4 searchbar">
    <input type="text" placeholder="أدخل كلمة البحث">
</section>

<section class="ramadan-slider bg-white">
    <img src="https://drtech-api.com/ramadan/slider.png" alt="">
</section>




    <section class="main-banner bg-white pt-4">



        <div class="carousels">
            <div class="boxs">
<a href="https://drtech-api.com/5/products">
                <img src="/ramadan/cat1.png" >
                </a>

                <span>الشواحن</span>

            </div>
            <div class="boxs">
            <a href="https://drtech-api.com/2/products">
                <img src="/ramadan/cat2.png" >
                </a>

                <span>الاكسسوارات</span>

            </div>
            <div class="boxs">
            <a href="https://drtech-api.com/1/products">
                <img src="/ramadan/cat3.png" >
                </a>

                <span>قسم الجوالات</span>

            </div>
        </div>



    <section class="bg-white">
        <div class="container py-5">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0 fw-bold">  أحدث المنتجات </h5>

            </div>
            <div class="row row-cols-1 row-cols-md-4 g-4 homepage-products-range">
                @foreach ($top_products as $product)
                    <div class="col product-item">
                        <div class="text-center position-relative  rounded pb-4">
                            <div class="img-wrapper">
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
                                            class="add_to_cart w-100 btn btn-success addtocartbtn">
                                            <i class="bi bi-cart-plus ms-1"></i>
                                            إضافة للسلة
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
                                        class="w-100 btn btn-success addtocartbtn">
                                        <i class="bi bi-cart-plus"></i>
                                        إضافة للسلة
                                    </a>
                                @endauth
                            </div>

                            {{-- <a href="{{ route('store.products') }}" class="stretched-link"></a> --}}
                        </div>
                    </div>
                @endforeach

            </div>


        </div>
    </section>



    <section class="feautrss bg-white">

    <h3>مزايا المتجر</h3>
    <div class="icon-box">
        <img src="/ramadan/payment-1.png" >
        <h5>طرق الدفع </h5>
        <p>طرق آمنة ومتعددة لاختيار الطريقة المفضلة لديكم</p>
    </div>


    <div class="icon-box">
    <img src="/ramadan/2.png" >
        <h5>الجودة</h5>
        <p>منتجات أصلية بضمان</p>
    </div>


    <div class="icon-box">
    <img src="/ramadan/3.png" >
        <h5>الشحن</h5>
        <p>شحن سريع لجميع مناطق المملكة</p>
    </div>

    </section>




    <footer>


   <img src="/ramadan/footer-logo.png"  class="logo-footer">

    <img src="" >

    <div class="fotter-download-links">
        <a href="">
            <img src="" alt="">
            </a>
    </div>

    <div class="links">
        <ul class="termslinks">
            <li><a href="#">أوقات العمل</a></li>
            <li><a href="#"> الإسترجاع / الإستبدال </a></li>
            <li><a href="#"> سياسة الاستخدام والخصوصية </a></li>
        </ul>
        <ul>
            <a href=""></a>
            <a href=""></a><a href=""></a><a href=""></a>
        </ul>
        <a href=""></a>
    </div>
    </footer>










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
