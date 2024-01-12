<!DOCTYPE html>

<html lang="en">

<head>
    <!-- // Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <!-- Required meta tags // -->

    <meta name="description" content="لوحة التحكم - دكتور تك">
    <meta name="author" content="ceosdesigns.sk">
    <meta content="دكتور تك حيث يمكن أن تجد كل الخدمات التي تحتاجها" name="description" />

    <title>{{ env('APP_NAME') }} - @yield('title')</title>

    <!-- // Favicon -->
    <link href="./images/favicon.png" rel="icon">
    <!-- Favicon // -->

    <!-- // Font Awesome 5 Free -->
    <link href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous" rel="stylesheet">
    <!-- Font Awesome 5 Free // -->

    <!-- // Template CSS files -->
    <link href="/assets/login/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/login/css/styles.css" rel="stylesheet">
    <!-- Template CSS files  // -->

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap');

        body {
            direction: rtl;
            text-align: right;
            font-family: 'Cairo', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif
        }
    </style>
</head>

<body dir="rtl">
    <!-- // Preloader -->
    <div id="nm-preloader" class="nm-aic nm-vh-md-100" style="display: none;">
        <div class="nm-ripple" style="display: none;">
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- Preloader // -->

    <main class="d-flex">
        <div class="container main-container">
            @yield('content')
        </div>
    </main>

    <!-- // Vendor JS files -->
    <script src="/assets/login/js/jquery-3.6.0.min.js"></script>
    <script src="/assets/login/js/bootstrap.bundle.min.js"></script>
    <!-- Vendor JS files // -->

    <!-- Template JS files // -->
    <script src="/assets/login/js/script.js"></script>
    <!-- Template JS files // -->
    <script defer>
        $('form').submit(function(e) {
            $(this).find('button').attr("disabled", true)
        })
    </script>
</body>

</html>
