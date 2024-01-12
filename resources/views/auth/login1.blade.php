@extends('auth.layout')

@section('title', 'تسجيل الدخول')

@section('content')

    <div class="row nm-row">
        <div class="col-lg-7 nm-bgi d-none d-lg-flex">
            <div class="overlay">
                <div class="hero-item">
                    <a href="" aria-label="Nimoy">
                        <img src="/assets/images/logo-light.svg" alt="Logo">
                    </a>
                </div>
                <div class="hero-item hero-item-1">
                    <h2>Go all the way.</h2>
                    <h2>Don't give up. Ever.</h2>
                    <h2>It's that simple.</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-5 nm-mb-1 nm-mb-md-1 nm-aic">
            <div class="card">
                <div class="card-content">
                    <div class="header">
                        <p class="h2">مرحبا بك</p>
                        <p class="h2">سجل الدخول لمواصلة عملك في دكتور تك</p>
                    </div>
                    <form method="POST" action="{{ route('login') }}" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label for="inputEmail">إسم المستخدم</label>
                            <div class="input-group nm-gp">
                                <span class="nm-gp-pp"><i class="fas fa-envelope-open"></i></span>
                                <input id="inputEmail" value="{{ old('username') }}"
                                    class="form-control @if ($errors->any() || session('error')) is-invalid @endif" type="text"
                                    name="username" tabindex="1" placeholder="ادخل إسم المستخدم" required="">
                                @if ($errors->any() || session('error'))
                                    @if (session('error'))
                                        <span class="invalid-feedback">
                                            {{ session('error') }}
                                        </span>
                                    @endif
                                    @if ($errors->any())
                                        <span class="invalid-feedback">
                                            المعلومات غير صحيحة
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword">كلمة السر</label>
                            <div class="input-group nm-gp">
                                <span class="nm-gp-pp"><i class="fas fa-lock"></i></span>
                                <input id="inputPassword" class="form-control" type="password" name="password"
                                    tabindex="2" placeholder="أدخل كلمة السر" required="">
                            </div>
                        </div>

                        <div class="d-flex nm-jcb nm-mb-1 nm-mt-1">
                            <a class="nm-ft-b" href="/password/reset">هل نسيت كلمة السر ؟</a>
                        </div>

                        <button type="submit" class="btn btn-block btn-primary nm-btn">تسجيل الدخول</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
