@extends('layout.store')

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8 mb-lg-0 mb-3">

                @if (session('success'))
                    <div class="fs-6 alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <section class="border bg-white p-3 shadow-sm rounded-3">
                    <h4>
                        <strong>سلة الشراء</strong>
                    </h4>
                    <p>لديك <span class="cart-count">{{ cartCount() }}</span> منتج في السلة</p>

                    @if ($carts->count() > 0)
                        @foreach ($carts as $cart)
                            <div class="position-relative border rounded-3 p-2 mb-2 d-flex align-items-center parent-item">
                                <div style="width: 100px; height: 100px; flex-shrink: 0;" class="">
                                    <img class="img-fluid w-100 h-100 rounded border" style="object-fit: cover;"
                                        src="{{ $cart->product->image }}" alt="">
                                </div>

                                <div class="me-3">
                                    <h6>
                                        المنتوج: {{ $cart->product->name }}
                                    </h6>
                                    <h6>
                                        البائع: {{ $cart->product->provider->username }}
                                    </h6>
                                    <span class="d-block text-success">
                                        {{ $cart->product->price }} ر.س
                                    </span>
                                </div>

                                <button class="remove_from_cart remove-item remove-btn btn btn-danger"
                                    data-product-id="{{ $cart->product_id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <span class="fs-5 text-center d-block mb-2">
                            لا توجد منتجات في سلتك
                        </span>
                    @endif
                </section>
            </div>
            <div class="col-lg-4">
                <form action="{{ route('store.checkout') }}" method="POST">
                    @csrf
                    <div class="fixed-sidebar">
                        <div class="bg-white shadow-sm rounded position-relative overflow-hidden">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item border-0">
                                    <div class="fw-bold fs-5 px-3 pt-2 mb-2">فاتورة</div>
                                    <div id="collapseOne" class="accordion-collapse collapse text-dark show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body px-3 pb-3 pt-0">
                                            <div class="d-flex justify-content-between align-items-center pb-1">
                                                <div class="text-muted">مجموع المشتريات</div>
                                                <div class="text-dark">{{ $total_items }} ر.س</div>
                                            </div>
                                            @if ($carts->count() > 0)
                                                <hr>
                                                <div>
                                                    <div class="form-group">
                                                        <label for="address" class="mb-1">أكتب عنوانك بالتفصيل</label>
                                                        <input type="text" id="address" name="address"
                                                            class="form-control form-control-lg mt-1 @error('address') is-invalid @enderror"
                                                            style="font-size: 13px">
                                                        @error('address')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group mt-2 mb-3">
                                                        <label for="phone" class="mb-1">أدخل رقم هاتف للتواصل</label>
                                                        <input type="text" id="phone" name="phone"
                                                            class="form-control form-control-lg mt-1 @error('phone') is-invalid @enderror"
                                                            style="font-size: 13px">
                                                        @error('phone')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group d-flex align-items-center g-4">
                                                        <div class="col">
                                                            <label for="">دفع عبر</label>
                                                        </div>
                                                        <div class="col d-flex align-items-center">
                                                            <input type="radio" class="form-check-input" checked
                                                                name="payment_method" id="cash" value="cash">
                                                            <label class="me-1" for="cash">كاش</label>
                                                        </div>
                                                        <div class="col d-flex align-items-center">
                                                            <input type="radio" class="form-check-input"
                                                                name="payment_method" id="credit_card" value="credit_card">
                                                            <label class="me-1" for="credit_card">بطاقة بنكية</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($carts->count() > 0)
                            <div class="d-grid my-3">
                                <button type="submit" class="btn btn-success btn-lg py-3 px-4">
                                    <div class="d-flex justify-content-between">
                                        <div>الدفع</div>
                                        <div class="fw-bold">{{ $total_items }} ر.س</div>
                                    </div>
                                </button>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
