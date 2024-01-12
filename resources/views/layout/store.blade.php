<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <base href="{{ config('app.url') }}/front/" />
    <meta name="robots" content="nofollow, noindex">
    <meta charset="utf-8">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/assets/images/favicon.ico">
    <title>{{ config('app.name') }}</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/icons/icofont.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/custom.css?ver={{ time() }}" rel="stylesheet">
    <style>
        .wtsp {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: auto;
            z-index: 999;
            background: #23b33a;
            border-radius: 50%;
            padding: 10px;
        }

        .no-icon {
            height: 85px;
            vertical-align: middle;
            display: flex;
            align-items: center;
        }

        @media screen and (max-width: 500px) {
            .no-icon {
                height: 48px;
            }

            .no-icon span {
                text-align: center;
                display: block;
                width: 100%;
            }

            .wtsp {
                bottom: 100px;
                right: 20px;
            }
        }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm osahan-header py-0">
        <div class="container">
            <a class="navbar-brand me-0 me-lg-3 me-md-3 d-flex align-items-center" href="/">
                <img src="/assets/images/favicon.ico" alt="#" class="img-fluid d-none d-md-block">
                <img src="/assets/images/favicon.ico" alt="#" class="d-block d-md-none d-lg-none img-fluid">
                <span class="fs-3 fw-bold me-2">
                    {{ config('app.name') }}
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto me-3 top-link">
                    <li class="nav-item">
                        @auth
                            <a class="nav-link" href="{{ route('store.my_orders') }}">
                                <i class="bi bi-bag fs-6"></i>
                                <span class="me-1">طلباتي</span>
                            </a>
                        @endauth
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('store.products') }}">
                            <i class="bi bi-shop-window fs-6"></i>
                            <span class="me-1">المنتوجات</span>
                        </a>
                    </li>
                    @foreach (\App\Models\ProductCategories::all() as $cat)
                    <li class="nav-item">
                        <a class="nav-link no-icon" href="{{ route('store.by_category', ['category' => $cat]) }}">
                            <span class="me-1">{{ $cat->name }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
                <div class="d-flex align-items-center gap-2">

                    <form id="search">
                        <select onchange="document.getElementById('search').submit()" name="city_id" class="form-select">
                            @foreach (\App\Models\Cities::where('status', 'ACTIVE')->get() as $city)
                                <option {{ session('city_id') == $city->id ? 'selected' : '' }} value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </form>
                    <a href="{{ route('store.search') }}"
                        class="btn btn-light position-relative rounded-pill rounded-icon">
                        <i class="icofont-search"></i>
                    </a>
                    @if (auth()->check())
                        <a href="{{ route('store.cart') }}"
                            class="btn btn-light position-relative rounded-pill rounded-icon">
                            <i class="bi bi-cart3"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning cart-count">
                                {{ cartCount() }}
                                <span class="visually-hidden">Cart</span>
                            </span>
                        </a>
                        <a class="btn btn-success rounded-pill px-3 text-uppercase ms-2" href="javascript:;"
                            onclick="document.querySelector('#logout-form').submit()">
                            تسجيل الخروج
                        </a>
                        <form id="logout-form" method="POST" action="{{ route('store.logout') }}" class="d-none">
                            @csrf
                        </form>
                    @else
                        <a class="btn btn-success rounded-pill px-3 text-uppercase ms-2"
                            href="{{ route('store.login') }}">
                            تسجيل الدخول
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>
    @yield('content')
    <section id="app-section" class="bg-white py-5 mobile-app-section" style="display: none">
        <div class="container">
            <div class="bg-light rounded px-4 pt-4 px-md-4 pt-md-4 px-lg-5 pt-lg-5 pb-0">
                <div class="row justify-content-center align-items-center app-2 px-lg-4">
                    <div class="col-md-7 px-lg-5">
                        <div class="text-md-start text-center">
                            <h1 class="fw-bold mb-2 text-dark">Get the Eatsie app</h1>
                            <div class="m-0 text-muted">We will send you a link, open it on your phone to download the
                                app</div>
                        </div>
                        <div class="my-4 me-lg-5">
                            <div class="input-group bg-white shadow-sm rounded-pill p-2">
                                <span class="input-group-text bg-white border-0"><i class="bi bi-phone pe-2"></i> +91
                                </span>
                                <input type="text" class="form-control bg-white border-0 ps-0 me-1"
                                    placeholder="Enter phone number">
                                <button class="btn btn-warning rounded-pill py-2 px-4 border-0" type="button">Send
                                    app link</button>
                            </div>
                        </div>
                        <div class="text-md-start text-center mt-5 mt-md-1 pb-md-4 pb-lg-5">
                            <p class="mb-3">Download app from</p>
                            <a href="#/"><img alt="#" src="assets/img/play-store.svg"
                                    class="img-fluid mobile-app-icon"></a>
                            <a href="#/"><img alt="#" src="assets/img/app-store.svg"
                                    class="img-fluid mobile-app-icon"></a>
                        </div>
                    </div>
                    <div class="col-md-5 pe-lg-5 mt-3 mt-md-0 mt-lg-0">
                        <img alt="#" src="assets/img/mobile-app.png" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="loginModal" aria-hidden="true" aria-labelledby="loginModalLabel" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered login-popup-main">
            <div class="modal-content border-0 shadow overflow-hidden rounded">
                <div class="modal-body p-0">
                    <div class="login-popup">
                        <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                        <div class="row g-0">
                            <div class="d-none d-md-flex col-md-4 col-lg-4 bg-image1"></div>
                            <div class="col-md-8 col-lg-8 py-lg-5">
                                <div class="login p-5">
                                    <div class="mb-4 pb-2">
                                        <h5 class="mb-2 fw-bold">تسجيل الدخول</h5>
                                        <p class="text-muted mb-0">الرجاء تسجيل الدخول بهذا الرقم في المرة القادمة التي
                                            تقوم فيها بتسجيل الدخول</p>
                                    </div>
                                    <form action="{{ route('store.login') }}">
                                        <div class="input-group bg-white border rounded mb-3 p-2">
                                            <span class="input-group-text bg-white border-0">
                                                <i class="bi bi-phone pe-2"></i> +91
                                            </span>
                                            <input type="text" class="form-control bg-white border-0 ps-0"
                                                name="phone" placeholder="أدخل رقم الهاتف">
                                        </div>
                                        <div class="input-group bg-white border rounded mb-3 p-2">
                                            <input type="password" class="form-control bg-white border-0 ps-0"
                                                name="phone" placeholder="أدخل كلمة المرور">
                                        </div>

                                        <button class="btn btn-success btn-lg py-3 px-4 text-uppercase w-100 mt-4">
                                            تسجيل الدخول
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
        tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered login-popup-main">
            <div class="modal-content border-0 shadow overflow-hidden rounded">
                <div class="modal-body p-0">
                    <div class="login-popup">
                        <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                        <div class="row g-0">
                            <div class="d-none d-md-flex col-md-4 col-lg-4 bg-image1"></div>
                            <div class="col-md-8 col-lg-8 py-lg-5">
                                <div class="login p-5">
                                    <div class="mb-4 pb-2">
                                        <h5 class="mb-2 fw-bold">Confirm your number</h5>
                                        <p class="text-muted mb-0">Enter the 4 digit OTP we’ve sent by SMS to
                                            123456-78909
                                            <a data-bs-target="#exampleModalToggle2" data-bs-toggle="modal"
                                                class="text-success text-decoration-none" href="#"><i
                                                    class="bi bi-pencil-square"></i> Edit</a>
                                        </p>
                                    </div>
                                    <form>
                                        <div class="d-flex gap-3 text-center">
                                            <div class="input-group bg-white border rounded mb-3 p-2">
                                                <input type="text" value="1"
                                                    class="form-control bg-white border-0 text-center">
                                            </div>
                                            <div class="input-group bg-white border rounded mb-3 p-2">
                                                <input type="text" value="3"
                                                    class="form-control bg-white border-0 text-center">
                                            </div>
                                            <div class="input-group bg-white border rounded mb-3 p-2">
                                                <input type="text" value="1"
                                                    class="form-control bg-white border-0 text-center">
                                            </div>
                                            <div class="input-group bg-white border rounded mb-3 p-2">
                                                <input type="text" value="3"
                                                    class="form-control bg-white border-0 text-center">
                                            </div>
                                        </div>
                                        <div class="form-check ps-0">
                                            <label class="small text-muted">Resend OTP in 0:55</label>
                                        </div>
                                    </form>
                                    <button class="btn btn-success btn-lg py-3 px-4 text-uppercase w-100 mt-4"
                                        data-bs-target="#exampleModalToggle3" data-bs-toggle="modal">Get OTP <i
                                            class="bi bi-arrow-right ms-2"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModalToggle3" aria-hidden="true" aria-labelledby="exampleModalToggleLabel3"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header p-4 border-0">
                    <h5 class="h6 modal-title fw-bold" id="exampleModalToggleLabel3"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-5 pb-2">
                        <div class="mb-3"><img src="assets/img/login2.png" class="col-6 mx-auto" alt="">
                        </div>
                        <h5 class="mb-2">Have a Referral or Invite Code?</h5>
                        <p class="text-muted">Use code GET50 to earn 50 Eatsie Cash</p>
                    </div>
                    <form>
                        <label class="form-label">Enter your referral/invite code</label>
                        <div class="input-group mb-2 border rounded-3 p-1">
                            <span class="input-group-text border-0 bg-white"><i
                                    class="bi bi bi-ticket-perforated  text-secondary"></i></span>
                            <input type="text" class="form-control border-0 bg-white ps-1"
                                placeholder="Enter the code" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </form>
                </div>
                <div class="modal-footer px-4 pb-4 pt-0 border-0">
                    <button class="btn btn-success btn-lg py-3 px-4 text-uppercase  w-100 m-0"
                        data-bs-target="#exampleModalToggle4" data-bs-toggle="modal">Claim Eatsie Cash</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModalToggle4" aria-hidden="true" aria-labelledby="exampleModalToggleLabel4"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header p-4 border-0">
                    <h5 class="h6 modal-title fw-bold" id="exampleModalToggleLabel4"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row justify-content-center">
                        <div class="col-10 text-center">
                            <div class="mb-5"><img src="assets/img/login3.png" alt=""
                                    class="col-6 mx-auto"></div>
                            <div class="my-3">
                                <h5 class="fw-bold">You got &#8377;50.0 Eatsie Cash!</h5>
                                <p class="text-muted h6">use this Eatsie Cash to save on your next orders</p>
                            </div>
                            <div class="my-4">
                                <p class="small text-muted mb-0">Your Eatsie Cash will expire in</p>
                                <div class="h5 text-success">6d:23h</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-4 pb-4 pt-0 border-0">
                    <a href="index-2.html" class="btn btn-success btn-lg py-3 px-4 text-uppercase w-100 m-0">Tap to
                        order</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add-delivery-location" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header px-4">
                    <h5 class="h6 modal-title fw-bold">Add Your Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form>
                        <div class="input-group border p-1 overflow-hidden osahan-search-icon shadow-sm rounded mb-3">
                            <span class="input-group-text bg-white border-0"><i class="icofont-search"></i></span>
                            <input type="text" class="form-control bg-white border-0 ps-0"
                                placeholder="Search for area, location name">
                        </div>
                    </form>
                    <div class="mb-4">
                        <a href="#" data-bs-dismiss="modal" aria-label="Close"
                            class="text-success d-flex gap-2 text-decoration-none fw-bold">
                            <i class="bi bi-compass text-success"></i>
                            <div>Use Current Location</div>
                        </a>
                    </div>
                    <div class="text-muted text-uppercase small">Search Results</div>
                    <div>
                        <div data-bs-dismiss="modal" aria-label="Close"
                            class="d-flex align-items-center gap-3 border-bottom py-3">
                            <i class="icofont-search h6"></i>
                            <div>
                                <p class="mb-1 fw-bold">Bangalore</p>
                                <p class="text-muted small m-0">Karnataka, India</p>
                            </div>
                        </div>
                        <div data-bs-dismiss="modal" aria-label="Close"
                            class="d-flex align-items-center gap-3 border-bottom py-3">
                            <i class="icofont-search h6"></i>
                            <div>
                                <p class="mb-1 fw-bold">Bangalore internaltional airport</p>
                                <p class="text-muted small m-0">Karmpegowda.in't Airport, Hunachur, karnataka, India
                                </p>
                            </div>
                        </div>
                        <div data-bs-dismiss="modal" aria-label="Close"
                            class="d-flex align-items-center gap-3 border-bottom py-3">
                            <i class="icofont-search h6"></i>
                            <div>
                                <p class="mb-1 fw-bold">Railway Station back gate</p>
                                <p class="text-muted small m-0">M.G. Railway Colony, Majestic, Bangaluru, Karnataka.
                                </p>
                            </div>
                        </div>
                        <div data-bs-dismiss="modal" aria-label="Close"
                            class="d-flex align-items-center gap-3 border-bottom py-3">
                            <i class="icofont-search h6"></i>
                            <div>
                                <p class="mb-1 fw-bold">Bangalore Cant</p>
                                <p class="text-muted small m-0">Cantonent Railway Station Road, Contonment Railway.</p>
                            </div>
                        </div>
                        <div data-bs-dismiss="modal" aria-label="Close" class="d-flex align-items-center gap-3 py-3">
                            <i class="icofont-search h6"></i>
                            <div>
                                <p class="mb-1 fw-bold">Bangalore Contonement Railway Station</p>
                                <p class="text-muted small m-0">Contonement Railway Quarters, Shivaji nagar.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a href="https://wa.me/{{ \App\Models\Setting::where('key', 'phone')->first()->value }}" class="wtsp">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="">
    </a>

    @auth
    <div
        class="w-100 d-block d-md-none d-lg-none mobile-nav-bottom position-fixed d-flex align-items-center justify-content-around shadow-sm">
        @auth
            <a href="javascript:;" onclick="document.querySelector('#logout-form').submit()">
                <span class="bi bi-box-arrow-right"></span>
                تسجيل الخروج
            </a>
            <a href="{{ route('store.products') }}"><span class="bi bi-card-heading"></span> المنتوجات</a>
            <a href="{{ route('store.cart') }}"><span class="bi bi-basket-fill"></span> السلة <b
                    class="cart-count">0</b></a>
        @else
            <a href="{{ route('store.login') }}">
                <span class="bi bi-unlock-fill"></span>
                تسجيل الدخول
            </a>
        @endauth
    </div>
    @endauth
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/custom.js?ver={{ time() }}"></script>
    <script src="assets/js/app.js?ver={{ time() }}"></script>

    @yield('js')
</body>

</html>
