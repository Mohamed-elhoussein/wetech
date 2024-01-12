@extends('layout.partials.app')

@section('title', 'مشرف جديد')

@section('dashbord_content')

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18"> مشرف جديد</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">إضافة مشرف جديد</h4>
                            @if ($errors->any())
                                <div class="col-md-8 offset-2 text-center badge-soft-danger rounded border border-danger">
                                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                                </div>
                            @endif
                            <form class="m-5" action="" method="POST">
                                @csrf
                                <div class="row mb-4">
                                    <label for="horizontal-firstname-input" class="col-sm-3 col-form-label  fs-4">إسم
                                        المستخدم</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="username" placeholder="أدخل إسم المستخدم"
                                            class="form-control" id="horizontal-firstname-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-phone-input" class="col-sm-3 col-form-label fs-4">الهاتف</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="number_phone" placeholder="أدخل رقم الهاتف"
                                            class="form-control" id="horizontal-phone-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-email-input" class="col-sm-3 col-form-label fs-4">البريد
                                        الإلكتروني</label>
                                    <div class="col-sm-9">
                                        <input type="email" name="email" placeholder="أدخل البريد الإلكتروني"
                                            class="form-control" id="horizontal-email-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-password-input" class="col-sm-3 col-form-label fs-4">كلمة
                                        السر</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="password" placeholder="أدخل كلمة السر "
                                            class="form-control" id="horizontal-password-input">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="horizontal-password-input" class="col-sm-3 col-form-label fs-4">تأكيد كلمة
                                        السر</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="password_confirmation" placeholder="تأكيد كلمة السر"
                                            class="form-control" id="horizontal-password-input">
                                    </div>

                                </div>

                                <div class="row mb-4 border font-size-16 p-4  bg-gray rounded shadow ">
                                    <h3 class="bg-transparent text-center mb-4  " style="text-decoration: underline">
                                        الصلاحيات </h3>
                                    @foreach (policies() as $page_policy)
                                        @if (isset($page_policy['policy']))
                                            <div class="form-check col-sm-3 mb-3">
                                                <input class="form-check-input" type="checkbox"
                                                    id="{{ $page_policy['policy'] . '_id' }}"
                                                    name="permission[{{ $page_policy['policy'] }}]" checked>
                                                <label class="form-check-label"
                                                    for="{{ $page_policy['policy'] . '_id' }}">
                                                    {{ $page_policy['name'] }}
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>


                                <div class="row d-flex justify-content-end">
                                    <button type="submit"
                                        class=" col-sm-6 col-md-2    btn btn-primary w-md fs-4">إضافة</button>
                                </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
