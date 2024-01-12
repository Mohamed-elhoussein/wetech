@extends('layout.partials.app')

@section('title', 'المعلومات الشخصية')

@section('dashbord_content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">المعلومات الشخصية</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4"> </h4>

                            <form class="m-5" action="/user/edit/{{ $user->id }}" method="POST">
                                @csrf
                                <div class="row mb-4">
                                    <label for="horizontal-firstname-input" class="col-sm-3 col-form-label fs-4 ">إسم
                                        المستخدم</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="username" value="{{ $user->username }}"
                                            placeholder="أدخل إسم المستخدم " class="form-control"
                                            id="horizontal-firstname-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-phone-input" class="col-sm-3 col-form-label fs-4">الهاتف</label>
                                    <div class="col-sm-9">
                                        <input type="text" value="{{ $user->number_phone }}" name="number_phone"
                                            placeholder=" أدخل الهاتف" class="form-control" id="horizontal-phone-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-email-input" class="col-sm-3 col-form-label fs-4">البريد
                                        الإلكتروني</label>
                                    <div class="col-sm-9">
                                        <input type="email" name="email" value="{{ $user->email }}"
                                            placeholder=" أدخل البريد الإلكتروني" class="form-control"
                                            id="horizontal-email-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-password-input" class="col-sm-3 col-form-label fs-4">كلمة
                                        السر</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="password" placeholder="كلمة السر "
                                            class="form-control" id="horizontal-password-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-password-input" class="col-sm-3 col-form-label fs-4">تأكيد
                                        كلمةالسر </label>
                                    <div class="col-sm-9">
                                        <input type="password" name="password_confirmation" placeholder="تأكيد كلمةالسر"
                                            class="form-control" id="horizontal-password-input">
                                    </div>
                                </div>

                                <div class="row d-flex justify-content-end">
                                    <button type="submit"
                                        class=" col-sm-6 col-md-2  btn btn-warning w-md fs-4">تحديث</button>
                                </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
