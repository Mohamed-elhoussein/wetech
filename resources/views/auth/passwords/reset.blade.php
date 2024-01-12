@extends('auth.layout')

@section('content')
    <div class="row nm-row">
        <div class="col-lg-7 nm-bgi d-none d-lg-flex">
            <div class="overlay">
                <div class="hero-item">
                    <img src="/assets/images/logo-light.svg" alt="Logo">
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
                        <p class="h2">إعادة تعيين كلمة المرور</p>
                        <p class="h2">إذا نسيت كلمة مرورك ، حسنًا ، فسنرسل إليك تعليمات عبر البريد الإلكتروني
                            لإعادة تعيين كلمة مرورك.
                        </p>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.update') }}" autocomplete="off">
                        @csrf

                        <div class="form-group">
                            <label for="inputPassword">كلمة السر الجديدة</label>
                            <div class="input-group nm-gp">
                                <span class="nm-gp-pp"><i class="fas fa-lock"></i></span>
                                <input id="inputPassword" class="form-control" type="password" name="password"
                                    tabindex="2" placeholder="كلمة السر الجديدة" required="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword">تأكيد كلمة السر</label>
                            <div class="input-group nm-gp">
                                <span class="nm-gp-pp"><i class="fas fa-lock"></i></span>
                                <input id="inputPassword" class="form-control" type="password"
                                    name="password_confirmation" tabindex="2" placeholder="تأكيد كلمة السر" required="">
                            </div>
                        </div>

                        <input type="hidden" name="email" value="{{ $email }}">
                        <input type="hidden" name="token" value="{{ $token }}">

                        <button type="submit" class="btn btn-block btn-primary nm-btn">إسترجاع كلمة المرور</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
