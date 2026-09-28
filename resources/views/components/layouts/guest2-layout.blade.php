<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script> --}}
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
        /* Page bodies only pad from `sm:` up, which leaves cards touching the screen edge on phones. */
        @media (max-width: 640px) {
            main .max-w-7xl.mx-auto { padding-left: 16px; padding-right: 16px; }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <img src="{{ asset('/img/phn-logo.png') }}" alt="PHN Logo"
                                style="height: 28px; width: auto; display: block;">
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <x-nav-link :href="route('ShowSearchTicketForm')" :active="request()->routeIs('dashboard')">
                            Back to Enter Staff ID
                        </x-nav-link>
                    </div>

                    {{-- Phones: the link above is hidden, so give them a compact version. --}}
                    <div class="flex items-center sm:hidden">
                        <a href="{{ route('ShowSearchTicketForm') }}"
                            style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 13px; font-weight: 600; color: #374151; text-decoration: none;">
                            <i class="bi bi-person-badge"></i> Change Staff ID
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
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
