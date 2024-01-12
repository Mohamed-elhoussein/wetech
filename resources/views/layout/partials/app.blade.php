<!doctype html>
<html lang="en" style="direction: rtl;">

<head>

    <meta charset="utf-8" />
    <title>{{ env('APP_NAME') }} - @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scal*e=1.0">

    <meta name="csrf_token" value="{{ csrf_token() }}">


    <!-- App favicon -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico">
    {{-- <link href="/assets/css/app.min.css?v={{ Str::random(10) . rand(1, 1000) }}" rel="stylesheet" type="text/css" /> --}}

    <!-- Bootstrap Css -->
    <link href="/assets/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/app-rtl.min.css?v={{ Str::random(10) . rand(1, 1000) }}" rel="stylesheet" type="text/css" />
    <link href="/assets/css/style.css?v={{ Str::random(10) . rand(1, 1000) }}" rel="stylesheet" type="text/css" />

    <!-- Icons Css -->
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->

</head>


<body data-sidebar="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('layout.partials.nav')

        @if (!isset($main_app))
            @include('layout.partials.sidebare')
        @else
            @include('layout.partials.main-sidebare')
        @endif


        <div class="main-content">

            @yield('dashbord_content')

        </div>
        <!-- end main content-->


        @if (!isset($hide_footer))
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © DcTech.
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end d-none d-sm-block">

                        </div>
                    </div>
                </div>
            </div>
        </footer>
        @endif


    </div>
    <!-- END layout-wrapper -->


    <!-- JAVASCRIPT -->
    <script src="/assets/libs/jquery/jquery.min.js"></script>
    <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="/assets/libs/node-waves/waves.min.js"></script>
    @yield('alpine_scripts')
    <!-- App js -->
    <script src="/assets/js/app.js"></script>
    <script src="{{ asset('js/app.js') }}?ver=1.0"></script>

    <script>
        function exportTasks(_this) {
            let _url = $(_this).data('href');
            console.log(_url)
            window.location.href = _url;
        }
    </script>

    @yield('scripts')
</body>

</html>
