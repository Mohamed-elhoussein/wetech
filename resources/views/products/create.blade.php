@extends('layout.partials.app')

@section('title', 'إضافة منتوج جديد')

@section('dashbord_content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"> المنتوجات</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">إضافة منتوج جديد</h4>

                        @if ($errors->any())
                        <div class="col-md-8 offset-2 text-center badge-soft-danger rounded border border-danger">
                            {!! implode('', $errors->all('<div>:message</div>')) !!}
                        </div>
                        @endif

                        <form class="m-md-5" action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-4">
                                <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">إسم
                                    المنتوج</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" placeholder="أضف إسم المنتوج" class="form-control" id="horizontal-firstname-input" value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">إسم
                                    المنتوج بالإنجليزي</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name_en" placeholder="أضف إسم المنتوج بالإنجليزي" class="form-control" id="horizontal-firstname-input" value="{{ old('name_en') }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                    المزود</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="user_id">
                                        <option value=""> ---- إخترالمزود ---- </option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->username }}
                                        </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                    الدولة</label>
                                <div class="col-sm-9">
                                    <select class="form-select" id="countries">
                                        <option value=""> ---- إخترالدولة ---- </option>
                                        @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">
                                            {{ $country->name }}
                                        </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                    المدينة</label>
                                <div class="col-sm-9">
                                    <select name="city_id" class="form-select" id="cities">
                                        <option value=""> ---- إخترالمدينة ---- </option>

                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                    الحي</label>
                                <div class="col-sm-9">
                                    <select name="street_id" class="form-select" id="street">
                                        <option value=""> ---- إختر الحي ---- </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                    الصنف</label>
                                <div class="col-sm-9">
                                    <select name="product_category_id" class="form-select" id="product_category">
                                        <option value=""> ---- إختر الصنف ---- </option>
                                        @foreach ($product_categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                    النوع</label>
                                <div class="col-sm-9">
                                    <select name="product_type_id" class="form-select" id="product_type">
                                        <option value=""> ---- إختر النوع ---- </option>
                                        @foreach ($product_types as $types)
                                        <option value="{{ $types->id }}">
                                            {{ $types->name }}
                                        </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <!-- <div class="row mb-4">
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                    البراند</label>
                                <div class="col-sm-9">
                                    <select name="product_brand_id" class="form-select" id="brands">
                                        <option value=""> ---- إختر البراند ---- </option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="row mb-4">
                                <label for="horizontal-image-input" class="col-sm-3 col-form-label fs-4">الصور</label>
                                <div class="col-sm-9">
                                    <input type="file" name="images[]" class="form-control" id="horizontal-image-input" multiple>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">اللون
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" name="color" placeholder="أضف لون المنتوج  " class="form-control" id="horizontal-firstname-input" value="{{ old('color') }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">السعة</label>
                                <div class="col-sm-9">
                                    <input type="text" name="disk_info" placeholder=" أضف السعة " class="form-control" id="horizontal-firstname-input" value="{{ old('disk_info') }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                    الحالة</label>
                                <div class="col-sm-9">
                                    <select name="status" class="form-select">
                                        <option value="NEW">جديد </option>
                                        <option value="USED">مستعمل </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4 duration-container d-none">
                                <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">
                                    مدة الإستخدام
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" name="duration_of_use" placeholder="  أضف المدة  " class="form-control" id="horizontal-firstname-input" value="{{ old('duration_of_use') }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                    الضمان</label>
                                <div class="col-sm-9">
                                    <select name="guarantee" class="form-select">
                                        <option value="1">مع ضمان </option>
                                        <option value="0">بدون ضمان </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">
                                    الثمن</label>
                                <div class="col-sm-9">
                                    <input type="number" name="price" placeholder="  أضف ثمن  " class="form-control" id="horizontal-firstname-input" value="{{ old('price') }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">
                                    كلفة توصيل المنتج</label>
                                <div class="col-sm-9">
                                    <input type="number" name="delivery_fee" placeholder="  أضف كلفة توصيل المنتج  " class="form-control" id="horizontal-firstname-input" value="{{ old('price') }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                    إضهار في صنف العروض</label>
                                <div class="col-sm-9">
                                    <select name="is_offer" class="form-select">
                                        <option value="1">نعم </option>
                                        <option value="0"> لا </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">
                                    ثمن العرض </label>
                                <div class="col-sm-9">
                                    <input type="number" name="offer_price" placeholder="  أضف ثمن العرض" class="form-control" id="horizontal-firstname-input" value="{{ old('price') }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="active" class="col-sm-3 col-form-label  fs-4">
                                    هل المنتوج مفعل ؟
                                </label>
                                <div class="col-sm-9">
                                    <input type="checkbox" name="active" placeholder="  أضف ثمن العرض" class="form-checkbox" id="active" checked>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-description-input" class="col-sm-3 col-form-label fs-4">الوصف</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" placeholder="أدخل الوصف " name="description" id="horizontal-description-input" rows="4"></textarea>
                                </div>
                            </div>

                            <div class="row d-flex justify-content-end">
                                <button type="submit" class=" col-sm-6 col-md-2 btn btn-primary w-md fs-4">إضافة</button>
                            </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')

<script>
    $('select[name=status]').on('change', e => {

        const isUsed = e.target.value == 'USED'

        if (!isUsed) {
            $('.duration-container').addClass('d-none')

            return
        }
        $('.duration-container').removeClass('d-none')

    })
</script>

@endsection