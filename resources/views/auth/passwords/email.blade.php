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
                        <p class="h2">إسترجاع كلمة المرور</p>
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
                    <form method="POST" action="{{ route('password.email') }}" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label for="inputEmail">البريد الإلكتروني</label>
                            <div class="input-group nm-gp">
                                <span class="nm-gp-pp"><i class="fas fa-envelope-open"></i></span>
                                <input id="inputEmail" value="{{ old('email') }}"
                                    class="form-control @if ($errors->any() || session('error')) is-invalid @endif" type="email"
                                    name="email" tabindex="1" placeholder="ادخل إسم المستخدم" required="">
                                @error('email')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-block btn-primary nm-btn">إسترجاع كلمة المرور</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
