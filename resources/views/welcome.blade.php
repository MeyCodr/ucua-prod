<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/img/zero_harm.png">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap-icons.min.css') }}">

    <style>
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 3rem;
            padding-top: 4%;
            text-align: center;
        }

        header img {
            width: 150px;
        }

        .headertext {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            white-space: nowrap;
        }

        .headertext h1 {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .headertext h5 {
            font-size: 1.5rem;
            font-weight: bold;
        }

        header .py-4 {
            margin-left: auto;
        }

        .banner {
            padding-top: 10%;
            padding-left: 5%;
            position: relative;
        }

        .general-ucua {
            background-color: #ffec99;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }

        .general-ucua:hover {
            background-color: white;
            transition: background-color 0.3s ease;
        }

        .general-ucua h4 {
            color: rgb(252, 71, 26);
            font-weight: bold;
        }

        .card-general {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 70%;
            height: 70%;
        }

        .card-body {
            flex-grow: 1;
        }

        footer {
            width: 100%;
            margin: 0;
            padding: 15px 20px;
            background-color: #011333;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        footer p {
            color: #262626;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        @media screen and (max-width: 768px) {
            body {
                background-image: url('{{ asset('/img/app_background.jpg') }}');
                background-size: cover;
                background-repeat: no-repeat;
            }

            header {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 20px;
                padding-top: 10%;
            }

            .header-top {
                display: flex;
                justify-content: space-between;
                width: 100%;
                align-items: center;
                padding: 0 15px;
            }

            header img {
                width: 100px;
                margin: 0;
            }

            .py-4 {
                margin: 0;
            }

            .headertext {
                margin-top: 15px;
                position: static;
                transform: none;
            }

            .headertext h1 {
                font-size: 1.5rem;
                font-weight: bold;
            }

            .headertext h5 {
                font-size: 1rem;
                font-weight: bold;
            }

            header h1 {
                font-size: 1.5rem;
            }

            .banner {
                padding-top: 12%;
                padding-left: 12%;
                padding-right: 3%;
                /* display: flex; */
                align-items: center;
                justify-content: flex-start;
                position: relative;
            }

            .hide_display {
                display: none;
            }
        }
    </style>
</head>

<body class="antialiased">
    <div
        style="background-image: url('{{ asset('/img/ucua_background.jpg') }}'); background-size: cover; background-repeat: no-repeat;">
        <header>
            <!-- Logo -->
            <img src="{{ asset('/img/phn-logo.png') }}" alt="PHN Logo">
            <!-- Text -->
            <div class="headertext text-center" style="color: rgb(252, 71, 26)">
                <h1>BEHAVIOUR-BASED SAFETY</h1>
                <h5>UCUA 2.0 SYSTEM</h5>
            </div>
            <img src="{{ asset('/img/zero_harm.png') }}" alt="Zero Harm Logo">
        </header>

        <!-- Banner -->
        <div class="banner">
            <h2 class="fw-bold" style="color: #011333">Noticed a Potential <span class="text-white">Safety Risk</span>
                at the Workplace?</h2>
            <h5 class="fw-bold" style="color: #011333">Terlihat Sebarang <span class="text-white">Risiko
                    Keselamatan</span> di Kawasan Kerja?</h5>

            <h5 class="fw-bold pt-5" style="color: #011333">What is Behaviour-Based Safety (BBS)? Click <a
                    href="{{ config('app.youtube_link') }}" target="_blank" class="text-white">HERE&nbsp;<i
                        class="bi bi-youtube"></i></a> for more info.</h5>
            <h6 class="fw-bold" style="color: #011333">Apa itu Keselamatan Berasaskan Tingkah Laku (BBS)? Klik <a
                    href="{{ config('app.youtube_link') }}" target="_blank" class="text-white">SINI&nbsp;<i
                        class="bi bi-youtube"></i></a> untuk maklumat lanjut.</h6>
        </div>

        <!-- Main Content -->
        <div class="container py-5">
            <div class="row">
                <!-- Submit Ticket -->
                <div class="col-sm-12 col-md-6 mb-4">
                    <a href="{{ route('ShowNewTicketForm') }}" class="card general-ucua"
                        style="text-decoration: none;">
                        <h4>Submit a Ticket&nbsp;<i class="bi bi-exclamation-triangle"></i></h4>
                        <p class="fw-bold text-dark">I want to raise a UCUA observation</p>
                        <p class="text-dark">Saya ingin melaporkan suatu pemerhatian UCUA</p>
                    </a>
                </div>

                <!-- Existing Ticket -->
                <div class="col-sm-12 col-md-6 mb-4">
                    <a href="{{ route('ShowSearchTicketForm') }}" class="card general-ucua" style="text-decoration: none;">
                        <h4>View Ticket&nbsp;<i class="bi bi-cone-striped"></i></h4>
                        <p class="fw-bold text-dark">View tickets you submitted in the past</p>
                        <p class="text-dark">Lihat tiket yang anda telah hantar sebelum ini</p>
                    </a>
                </div>

                @if (Route::has('login'))
                    <div class="text-center py-4 sm:block">
                        @auth
                            <a class="btn btn-outline-danger fw-bold text-dark" type="button"
                                href="{{ url('/dashboard') }}">Dashboard</a>
                        @else
                            <a class="btn fw-bold text-dark" style="background-color: #ffec99" type="button"
                                href="{{ route('login') }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Selected Users and Admin Only">Go to
                                Administration Panel</a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </div>

    <footer class="d-flex justify-content-between align-items-center py-3">
        <p class="text-light mb-0">SHE Department, PHN</p>
        {{-- <img src="{{ asset('/img/drb_hicom.png') }}" class="img-fluid" style="max-width: 100px;"> --}}
    </footer>

    <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>

</html>
