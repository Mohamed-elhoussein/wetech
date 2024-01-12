{{-- @extends('layout.partials.auth.app')

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
                                            <h5 class="text-primary">Free Register</h5>
                                            <p>Get your free Skote account now.</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="./../assets/images/profile-img.png" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div>
                                    <a href="index.html">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="" alt="" class="rounded-circle" height="34">
                                            </span>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-2">
                                    <form action="/user/create" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" id="useremail"
                                                placeholder="Enter email" required>
                                            <div class="emailfeedback invalid-feedback">
                                                Please Enter Email
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                placeholder="Enter username" required>
                                            <div class="invalid-feedback">
                                                Please Enter Username
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="userpassword" class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" id="userpassword"
                                                placeholder="Enter password" required>
                                            <div class="invalid-feedback">
                                                Please Enter Password
                                            </div>
                                        </div>
                                        <div class="mb-3 ">
                                            <label for="userpassword" name="role" class="form-label">Role</label>
                                            <div class="">
                                                <select class="form-select">
                                                    <option>User</option>
                                                    <option>Admin</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mt-4 d-grid">
                                            <button id="register" class="btn btn-primary waves-effect waves-light"
                                                type="submit">Register</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
@endsection --}}


@extends('layout.store')

@section('content')
    <section style="height: calc(100vh - 80px); display: flex; align-items: center;">
        <div class="container">
            <div class="row py-5">
                <div class="col-md-5 mx-auto">
                    <div class="text-center d-flex align-items-center flex-column mb-3">
                        <img src="/assets/images/favicon.ico" alt="#" class="text-center img-fluid">
                    </div>
                    <h5 class="mb-4 fs-4 fw-bold text-center">إنشاء حساب جديد</h5>
                    <div class="bg-white p-3 rounded-3 shadow-sm">
                        @if ($errors->count() > 0)
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                    <br>
                                @endforeach
                            </div>
                        @endif
                        <form action="{{ route('store.register') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="" class="mb-2">البريد الإلكتروني</label>
                                <div class="input-group bg-white border rounded mb-3 p-2">
                                    <input required type="text" class="form-control bg-white border-0 ps-0" name="email"
                                        value="{{ old('email') }}" placeholder="البريد الإلكتروني">
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="mb-2">إسم المستخدم</label>
                                <div class="input-group bg-white border rounded mb-3 p-2">
                                    <input required type="text" class="form-control bg-white border-0 ps-0" name="username"
                                        value="{{ old('username') }}" placeholder="إسم المستخدم">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="" class="mb-2">كلمة المرور</label>
                                <div class="input-group bg-white border rounded mb-3 p-2">
                                    <input required type="password" class="form-control bg-white border-0 ps-0" name="password"
                                        placeholder="أدخل كلمة المرور">
                                </div>
                            </div>

                            <button class="btn btn-success btn-lg px-4 text-uppercase w-100 mt-4">
                                إنشاء حساب جديد
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
