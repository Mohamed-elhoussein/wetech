<div class="modal fade" id="product-confirmation-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header px-4">
                <h5 class="h6 modal-title fw-bold">تأكيد شراء المنتج</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="order-form" action="{{ route('store.order', ['product' => $product]) }}" dir="rtl" method="post">
                    @csrf
                    <h4 class="text-center mb-3">{{ $product->name }}</h4>
                    <div class="mb-3">
                        <img class="img-fluid rounded-3 border" height="250" src="{{ !is_array($product->images) ? json_decode($product->images)[0] : (is_array($product->images) ? $product->images[0] : null) }}"
                            alt="">
                    </div>
                    <div class="mb-3">
                        <span class="mt-3 text-success">
                            <strong>
                                وصف المنتج
                            </strong>
                        </span>
                        <p class="fs-6">{{ $product->description }}</p>
                    </div>
                    <hr>
                    <div style="display: none" class="alert alert-danger error-message"></div>
                    @if (auth()->check())
                        <div class="form-group mb-3">
                            <label for="address" class="mb-1 fs-6">اسم المدينة</label>
                            <input type="text" id="address" name="city" required
                                class="form-control form-control-lg mt-1" style="font-size: 13px">
                        </div>
                        <div class="form-group mb-3">
                            <label for="address" class="mb-1 fs-6">الحي</label>
                            <input type="text" id="address" name="hay" required
                                class="form-control form-control-lg mt-1" style="font-size: 13px">
                        </div>
                        <div class="form-group mb-3">
                            <label for="address" class="mb-1 fs-6">الشارع</label>
                            <input type="text" id="address" name="street" required
                                class="form-control form-control-lg mt-1" style="font-size: 13px">
                        </div>
                        <div class="form-group mb-3">
                            <label for="address" class="mb-1 fs-6">أكتب عنوانك بالتفصيل</label>
                            <input type="text" id="address" name="address"
                                class="form-control form-control-lg mt-1" style="font-size: 13px">
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone" class="mb-1 fs-6">أدخل رقم هاتف للتواصل</label>
                            <input type="text" id="phone" name="phone"
                                class="form-control form-control-lg mt-1" style="font-size: 13px">
                        </div>
                        <div class="form-group d-flex g-4">
                            <div class="col">
                                <label for="" class="mb-1 fs-6">دفع عبر</label>
                            </div>
                            <div class="col d-flex align-items-center">
                                <input type="radio" class="form-check-input" checked name="payment_method" id="cash" value="cash">
                                <label class="fs-6 me-2" for="cash">كاش</label>
                            </div>
                            <div class="col d-flex align-items-center">
                                <input type="radio" class="form-check-input" name="payment_method" id="credit_card" value="credit_card">
                                <label class="fs-6 me-2" for="credit_card">بطاقة بنكية</label>
                            </div>
                        </div>
                    @endif

                    <input type="hidden" id="product_price" value="{{ !$product->is_offer ? $product->price : $product->offer_price }}">

                    <div>
                        <table class="product-payment-table-info">
                            <tr>
                                <td>
                                    سعر المنتج
                                </td>
                                <td>
                                    {{ !$product->is_offer ? $product->price : $product->offer_price }} ر.س
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    شحن المنتج
                                </td>
                                <td>
                                    {{ $product->delivery_fee }} ر.س
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    رسوم الدفع
                                </td>
                                <td>
                                    {{ !$product->is_offer ? $product->price : $product->offer_price }} ر.س
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    المجموع
                                </td>
                                <td>
                                    {{ $product->price + $product->delivery_fee }} ر.س
                                </td>
                            </tr>
                        </table>

                        <div class="my-4 text-center ">
                            <strong class="fs-6">
                                لا يمكن استبدال او استرجاع المنتج
                            </strong>
                        </div>
                    </div>

                    @if (auth()->check())
                        <div class="d-flex mt-4">
                            <button type="button" style="width: 100%; margin-left: 10px" class="btn btn-secondary"
                                data-bs-dismiss="modal" aria-label="Close">تراجع</button>
                            <button type="submit" style="width: 100%;" class="btn btn-success">تأكيد</button>
                        </div>
                    @else
                        <a href="{{ route('store.login') }}"
                            class="text-center text-success mt-2 text-decoration-none fs-6 d-block">المرجو تسجيل
                            الدخول</a>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
