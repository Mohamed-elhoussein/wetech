@extends('layout.partials.app')

@section('title', 'ثحديث المراقب')

@section('dashbord_content')


<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">ثحديث المراقب</h4>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4"> </h4>
                        @if ($errors->any())
                        <div class="col-md-8 offset-2 text-center badge-soft-danger rounded border border-danger">
                            {!! implode('', $errors->all('<div>:message</div>')) !!}
                        </div>
                        @endif


                        <form class="m-5" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row mb-4">
                                <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">إسم
                                    المراقب</label>
                                <div class="col-sm-9">
                                    <input type="text" name="username" placeholder="أضف إسم المراقب" class="form-control" id="horizontal-firstname-input" value="{{ old('username') ?? $observer->number_phone }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-title-input" class="col-sm-3 col-form-label fs-4">
                                    الدولة</label>
                                <div class="col-sm-9">
                                    <select name="country_id" class="form-select" id="country" onchange="getCountryDetails()">
                                        @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" @if ($country->id == optional($observer->country)->id ?? 193) selected @endif>
                                            {{ $country->name }}
                                        </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-phone-input" class="col-sm-3 col-form-label fs-4">الهاتف</label>
                                <div class="col-sm-8">
                                    <input type="text" name="number_phone" placeholder="أضف هاتف " class="form-control" id="horizontal-phone-input" value="{{ old('number_phone') ?? $observer->number_phone }}">
                                </div>
                                <div class="col-sm-1">
                                    <input type="text" id="code_phone" class="form-control " disabled value="+ 966 " style="direction: ltr" id="horizontal-country-code-input">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-image-input" class="col-sm-3 col-form-label fs-4">الصورة</label>
                                <div class="col-sm-9">
                                    <input type="file" name="avatar" class="form-control" id="horizontal-image-input">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">الإسم
                                    الأول</label>
                                <div class="col-sm-9">
                                    <input type="text" name="first_name" placeholder="أضف الإسم الأول" class="form-control" id="horizontal-firstname-input" value="{{ old('first_name') ?? $observer->first_name }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-SecondName-input" class="col-sm-3 col-form-label  fs-4">الإسم
                                    الثاني</label>
                                <div class="col-sm-9">
                                    <input type="text" name="second_name" placeholder="أضف الإسم الثاني " class="form-control" id="horizontal-SecondName-input" value="{{ old('second_name') ?? $observer->second_name }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-lastname-input" class="col-sm-3 col-form-label  fs-4">الإسم
                                    الأخير</label>
                                <div class="col-sm-9">
                                    <input type="text" name="last_name" placeholder="أضف الإسم الأخير" class="form-control" id="horizontal-lastname-input" value="{{ old('last_name') ?? $observer->last_name }}">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="horizontal-email-input" class="col-sm-3 col-form-label fs-4">البريد
                                    الإلكتروني</label>
                                <div class="col-sm-9">
                                    <input type="email" style="text-align: right;" name="email" placeholder="أضف البريد الإلكتروني" class="form-control" id="horizontal-email-input" value="{{ old('email') ?? $observer->email }}">
                                </div>
                            </div>

                            <div class="row mb-4 border font-size-16 p-4  bg-gray rounded shadow ">
                                <h3 class="bg-transparent text-center mb-4  " style="text-decoration: underline">
                                    المدن المراقبة </h3>
                                @foreach ($countries->where('status', 'ACTIVE') as $country)
                                <div class="form-check col-sm-3 mb-3">
                                    <input class="form-check-input" type="checkbox" id="{{ $country->code . '_id' }}" name="permission[{{ $country->id }}]" @if (in_array($country->id, $observer->permissions ?? [])) checked @endif>
                                    <label class="form-check-label" for="{{ $country->code . '_id' }}">
                                        {{ $country->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <div class="row mb-4 border font-size-16 p-4  bg-gray rounded shadow ">
                                <h3 class="bg-transparent text-center mb-4  " style="text-decoration: underline">
                                    الصلاحيات
                                </h3>
                                <!-- @foreach (chatReviewsPermission() as $name => $permission)
                                <div class="form-check col-sm-3 mb-3">
                                    <input class="form-check-input" type="checkbox" id="{{ $permission }}" name="chat_review[{{ $permission }}]" @if (isset($observer->chat_reviews_permissions[$permission]) && $observer->chat_reviews_permissions[$permission]) checked @endif>
                                    <label class="form-check-label" for="{{ $permission }}">
                                        {{ $name }}
                                    </label>
                                </div>
                                @endforeach -->

<!-- test -->

                                @foreach ($status as $key=>$value)

                                <div class="form-check col-sm-3 mb-3">
                                    <input class="form-check-input" type="checkbox" id="{{ $value->permission->id }}" name="chat_review[{{ $value->permission->id }}]" @if($value->user_has_per) checked @endif >
                                    <label class="form-check-label" for="{{$value->permission->id }}">
                                        {{ $value->permission->name }}
                                        

                                    </label>
                                </div>
                                @endforeach
                            </div>

                            <div class="row d-flex justify-content-end">
                                <button type="submit" class=" col-sm-6 col-md-2 btn btn-warning w-md fs-5 p-1">تحديث
                                    المراقب</button>
                            </div>

                        </form>
                    </div>
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
