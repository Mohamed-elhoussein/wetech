@extends('layout.store')

@section('content')
    <section style="height: calc(100vh - 80px); display: flex; align-items: center;">
        <div class="container">
            <div class="row py-5">
                <div class="col-md-5 mx-auto">
                    <div class="text-center d-flex align-items-center flex-column mb-3">
                        <img src="/assets/images/favicon.ico" alt="#" class="text-center img-fluid">
                    </div>
                    <h5 class="mb-4 fs-4 fw-bold text-center">تسجيل الدخول</h5>
                    <div class="bg-white p-3 rounded-3 shadow-sm">
                        @if ($errors->count() > 0)
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                    <br>
                                @endforeach
                            </div>
                        @endif
                        <form action="{{ route('store.login') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="" class="mb-2">البريد الإلكتروني</label>
                                <div class="input-group bg-white border rounded mb-3 p-2">
                                    <input type="text" class="form-control bg-white border-0 ps-0" name="email"
                                        value="{{ old('email') }}" placeholder="البريد الإلكتروني">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="" class="mb-2">كلمة المرور</label>
                                <div class="input-group bg-white border rounded mb-3 p-2">
                                    <input type="password" class="form-control bg-white border-0 ps-0" name="password"
                                        placeholder="أدخل كلمة المرور">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="city" class="mb-2">المدينة</label>
                                <select name="city_id" id="city" class="form-select">
                                    @foreach ($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button class="btn btn-success btn-lg px-4 text-uppercase w-100 mt-4">
                                تسجيل الدخول
                            </button>

                            <a href="/user/register" class="link-warning text-center d-block mt-3 fs-6 text-decoration-none">
                                إنشاء حساب جديد
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
