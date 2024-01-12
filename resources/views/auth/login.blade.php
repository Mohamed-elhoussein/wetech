@extends('layout.partials.auth.app')


@section('auth_content')

    <body>
        <div class="account-pages my-5 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            <div class="bg-primary bg-soft">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-4">
                                            <h5 class="text-primary" dir="rtl">مرحبا بك </h5>
                                            <p dir="rtl">سجل الدخول لمواصلة عملك في دكتور تك</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="./../assets/images/profile-img.png" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">

                                <div class="p-2 mt-5" dir="rtl">
                                    <form class="form-horizontal form" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="username" class="form-label">اسم المستخدم </label>
                                            <input name="username" type="text" class="form-control" id="username"
                                                placeholder="أدخل اسم المستخدم">
                                        </div>

                                        <div class="mb-3" x-data="{show:false}">
                                            <label class="form-label">كلمة السر</label>
                                            <div class="input-group ">
                                                <input name="password" :type="!show?'password':'text'"
                                                    class="form-control" placeholder="أدخل كلمة السر">
                                                <button x-on:click="show = !show" class="btn btn-light " type="button"><i
                                                        class="mdi mdi-eye-outline"></i></button>
                                            </div>
                                        </div>

                                        <div class="form-check row m-auto">
                                            @if (session('error'))
                                                <div
                                                    class="col-md-8 m-auto mt-2 text-center badge-soft-danger rounded border border-danger">
                                                    {{ session('error') }}
                                                </div>
                                            @endif
                                            @if ($errors->any())
                                                <div
                                                    class="col-md-8 m-auto mt-2 text-center badge-soft-danger rounded border border-danger">
                                                    {{ 'المعلومات غير صحيحة' }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mt-3 d-grid" x-data="{closed:false}">
                                            <button x-on:click="closed = true" @click="$('.form').submit()"
                                                class="btn btn-primary waves-effect waves-light" :disabled="closed"
                                                x-html="closed ? 'جاري تسجيل الدخول ...': 'تسجيل الدخول'"
                                                type="submit"></button>
                                        </div>



                                    </form>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- end account-pages -->
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
@endsection
