<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/img/zero_harm.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/img/zero_harm.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/img/zero_harm.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/img/zero_harm.png') }}">
    <meta name="msapplication-TileImage" content="{{ asset('/img/zero_harm.png') }}">
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('/js/app.js') }}" defer></script>


    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap-icons.min.css') }}">

    <style>
        #formOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(32, 32, 32, 0.75);
            z-index: 1000;
            display: none;
        }

        /* Style the loading spinner */
        .fa-spinner {
            margin-right: 8px;
        }
    </style>
</head>

<body>
    <main class="main min-h-screen flex items-center justify-center"
        style="background-image: url('{{ asset('/img/ucua_background.jpg') }}');
               background-size: cover;
               background-repeat: no-repeat;
               background-attachment: fixed">
        <div class="container">
            {{ $slot }}
        </div>
    </main>
</body>

</html>
