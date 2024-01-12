@extends('layout.partials.app')

@section('title', 'تعديل مزود خدمة')

@section('dashbord_content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18"> تعديل مزود خدمة</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="d-flex justify-content-center mt-4">
                            <div class="custom__avatar">
                                <img src="{{ url($provider->avatar) }}"
                                    style="width: 100%; height: 100%; object-fit: cover" alt="">
                            </div>
                        </div>


                        <form class="m-5" action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-4">
                                <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">إسم
                                    المستخدم</label>
                                <div class="col-sm-9">
                                    <input type="text" name="username" placeholder="أضف إسم المستخدم" class="form-control"
                                        id="horizontal-firstname-input" value="{{ $provider->username }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                    الدولة</label>
                                <div class="col-sm-9">
                                    <select name="country_id" class="form-select" id="country"
                                        onchange="getCountryDetails()">
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}"
                                                @if ($country->id == $provider->country_id) selected @endif>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-phone-input" class="col-sm-3 col-form-label fs-4">الهاتف</label>
                                <div class="col-sm-8">
                                    <input type="text" name="number_phone" placeholder="أضف هاتف " class="form-control"
                                        id="horizontal-phone-input" value="{{ $provider->number_phone }}">
                                </div>
                                <div class="col-sm-1">
                                    <input type="text" id="code_phone" class="form-control " disabled
                                        value="{{ $provider->country->country_code }}" style="direction: ltr"
                                        id="horizontal-country-code-input">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-image-input" class="col-sm-3 col-form-label fs-4">الصورة</label>
                                <div class="col-sm-9">
                                    <input type="file" name="avatar" placeholder="أدخل الرابط" class="form-control"
                                        id="horizontal-image-input">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-image-input" class="col-sm-3 col-form-label fs-4">الهوية</label>
                                <div class="col-sm-9">
                                    <input type="file" name="identity" class="form-control" id="horizontal-image-input">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">الإسم
                                    الأول</label>
                                <div class="col-sm-9">
                                    <input type="text" name="first_name" placeholder="أضف الإسم الأول"
                                        class="form-control" id="horizontal-firstname-input"
                                        value="{{ $provider->first_name }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-SecondName-input" class="col-sm-3 col-form-label  fs-4">الإسم
                                    الثاني</label>
                                <div class="col-sm-9">
                                    <input type="text" name="second_name" placeholder="أضف الإسم الثاني "
                                        class="form-control" id="horizontal-SecondName-input"
                                        value="{{ $provider->second_name }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-lastname-input" class="col-sm-3 col-form-label  fs-4">الإسم
                                    الأخير</label>
                                <div class="col-sm-9">
                                    <input type="text" name="last_name" placeholder="أضف الإسم الأخير"
                                        class="form-control" id="horizontal-lastname-input"
                                        value="{{ $provider->last_name }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="order" class="col-sm-3 col-form-label  fs-4">
                                    الترتيب
                                </label>
                                <div class="col-sm-9">
                                    <input type="number" name="order" placeholder=""
                                        class="form-control" id="order"
                                        value="{{ $provider->order }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-phone-input" class="col-sm-3 col-form-label fs-4">هاتف
                                    صديق</label>
                                <div class="col-sm-8">
                                    <input type="text" id="code_friend" name="friend_number" placeholder="أضف هاتف صديق"
                                        class="form-control" id="horizontal-phone-input"
                                        value="{{ $provider->friend_number }}">
                                </div>
                                <div class="col-sm-1">
                                    <input type="text" id="code_phone_friend" class="form-control " disabled
                                        style="direction: ltr" value="{{ $provider->country->country_code }}"
                                        id="horizontal-country-code-input">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-email-input" class="col-sm-3 col-form-label fs-4">البريد
                                    الإلكتروني</label>
                                <div class="col-sm-9">
                                    <input type="email" name="email" placeholder="أضف البريد الإلكتروني"
                                        class="form-control" id="horizontal-email-input"
                                        value="{{ $provider->email }}">
                                </div>
                            </div>



                            <div class="row d-flex justify-content-end">
                                <button type="submit" class=" col-sm-6 col-md-2 btn btn-warning w-md fs-4">تحديث
                                    مزود
                                    الخدمة</button>
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
