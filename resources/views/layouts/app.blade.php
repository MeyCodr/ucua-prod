<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/img/zero_harm.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/img/zero_harm.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/img/zero_harm.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/img/zero_harm.png') }}">
    <meta name="msapplication-TileImage" content="{{ asset('/img/zero_harm.png') }}">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        {{--
            The compiled public/css/app.css on this install predates this markup and can't
            currently be rebuilt (Laravel Mix's toolchain doesn't run on this machine), so a
            couple of Tailwind utilities below were never generated. This rule backfills it
            with its standard Tailwind value.
        --}}
        <style>
            .py-8 { padding-top: 2rem; padding-bottom: 2rem; }

            /* Phones: page headers put a title and a (non-shrinking) button group on one row;
               let the buttons drop underneath instead of pushing past the screen edge. */
            @media (max-width: 640px) {
                header .justify-between { flex-wrap: wrap; row-gap: 12px; }
                header h2 { word-break: break-word; }
                /* Page bodies only pad from `sm:` up, which leaves cards touching the screen edge. */
                main .max-w-7xl.mx-auto { padding-left: 16px; padding-right: 16px; }
            }
        </style>

        <!-- Page Heading -->
        <header>
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
