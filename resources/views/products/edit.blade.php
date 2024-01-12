@extends('layout.partials.app')

@section('title', 'تعديل المنتج')

@section('dashbord_content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18"> تعديل المنتج</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">


                    <form class="m-md-5" action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">إسم
                                المنتوج</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" placeholder="أضف إسم المنتوج" class="form-control" id="horizontal-firstname-input" value="{{ old('name') ?? $product->name }}">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">إسم
                                المنتوج بالإنجليزي</label>
                            <div class="col-sm-9">
                                <input type="text" name="name_en" placeholder="أضف إسم المنتوج بالإنجليزي" class="form-control" id="horizontal-firstname-input" value="{{ old('name_en') ?? $product->name_en }}">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                المزود</label>
                            <div class="col-sm-9">
                                <select class="form-select" name="user_id">
                                    <option value=""> ---- إختر المزود ---- </option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @if ($user->id == $product->user->id) selected @endif>
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
                                    <option value="{{ $country->id }}" @if ($country->id == $country_id) selected @endif>
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
                                    @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" @if ($city->id == $product->city_id) selected @endif>
                                        {{ $city->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                الحي</label>
                            <div class="col-sm-9">
                                <select name="street_id" class="form-select" id="street">
                                    <option value=""> ---- إختر الحي ---- </option>
                                    @foreach ($streets as $street)
                                    <option value="{{ $street->id }}" @if ($street->id == $product->street_id) selected @endif>
                                        {{ $street->name }}
                                    </option>
                                    @endforeach
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
                                    <option value="{{ $category->id }}" @if ($category->id == $product->product_category_id) selected @endif>
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
                                    <option value="{{ $types->id }}" @if ($types->id == $product->product_type_id) selected @endif>
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
                                    @foreach ($product_brands as $brand)
                                    <option value="{{ $brand->id }}" @if ($brand->id == $product->product_brand_id) selected @endif>
                                        {{ $brand->name }}
                                    </option>
                                    @endforeach


                                </select>
                            </div>
                        </div> -->
                        <div class="row mb-4">
                            <label for="horizontal-image-input" class="col-sm-3 col-form-label fs-4">الصورة</label>
                            <div class="col-sm-9">
                                <input type="file" name="image_0" class="form-control" id="horizontal-image-input" multiple>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">اللون
                            </label>
                            <div class="col-sm-9">
                                <input type="text" name="color" placeholder="أضف لون المنتوج  " class="form-control" id="horizontal-firstname-input" value="{{ old('color') ?? $product->color }}">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">السعة</label>
                            <div class="col-sm-9">
                                <input type="text" name="disk_info" placeholder=" أضف السعة " class="form-control" id="horizontal-firstname-input" value="{{ old('disk_info') ?? $product->disk_info }}">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                الحالة</label>
                            <div class="col-sm-9">
                                <select name="status" class="form-select">
                                    <option @if ($product->status == 'NEW') selected @endif value="NEW">جديد </option>
                                    <option @if ($product->status == 'USED') selected @endif value="USED">مستعمل
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4 duration-container @if($product->status === 'NEW') d-none @endif ">
                            <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">
                                مدة الإستخدام
                            </label>
                            <div class="col-sm-9">
                                <input type="text" name="duration_of_use" placeholder="  أضف المدة  " class="form-control" id="horizontal-firstname-input" value="{{ old('duration_of_use') ?? $product->duration_of_use }}">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                الضمان</label>
                            <div class="col-sm-9">
                                <select name="guarantee" class="form-select">
                                    <option @if ($product->guarantee == '1') selected @endif value="1">مع ضمان
                                    </option>
                                    <option @if ($product->guarantee == '0') selected @endif value="0">بدون ضمان
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">
                                الثمن</label>
                            <div class="col-sm-9">
                                <input type="number" name="price" placeholder="  أضف ثمن  " class="form-control" id="horizontal-firstname-input" value="{{ old('price') ?? $product->price }}">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">
                                كلفة توصيل المنتج</label>
                            <div class="col-sm-9">
                                <input type="number" name="delivery_fee" placeholder="  أضف كلفة توصيل المنتج  " class="form-control" id="horizontal-firstname-input" value="{{ old('price') ?? $product->delivery_fee }}">
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
                                <input type="number" name="offer_price" placeholder="  أضف ثمن العرض" class="form-control" id="horizontal-firstname-input" value="{{ old('offer_price') ?? $product->offer_price }}">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="active" class="col-sm-3 col-form-label  fs-4">
                                هل المنتوج مفعل ؟
                            </label>
                            <div class="col-sm-9">
                                <input type="checkbox" name="active" placeholder="  أضف ثمن العرض" class="form-checkbox" id="active" @if($product->active) checked @endif>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="horizontal-description-input" class="col-sm-3 col-form-label fs-4">الوصف</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" placeholder="أدخل الوصف " name="description" id="horizontal-description-input" rows="4">{{ old('description') ?? $product->description }}</textarea>
                            </div>
                        </div>



                        <div class="row d-flex justify-content-end">
                            <button type="submit" class=" col-sm-6 col-md-2 btn btn-warning w-md fs-4">تحديث
                                المنتوج
                            </button>
                        </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('footer_scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


<script>
    country = document.getElementById('country');
    code_phone = document.getElementById('code_phone');
    code_phone_friend = document.getElementById('code_phone_friend');


    function getCountryDetails() {
        country_id = event.target.value;

        axios.get('/api/country/' + country_id)
            .then(function(response) {

                country = response.data;

                code_phone.value = country.country_code;

                code_phone_friend.value = country.country_code;

            })
            .catch(function(error) {

            });
    }
</script>

@endsection

@section('scripts')
<script>
    $('select[name=status]').on('change', e => {
        console.log(e.target.value)

        const isUsed = e.target.value == 'USED'

        if (!isUsed) {
            $('.duration-container').addClass('d-none')

            return
        }
        $('.duration-container').removeClass('d-none')

    })
</script>
@endsection