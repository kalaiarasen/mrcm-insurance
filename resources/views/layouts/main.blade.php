<!DOCTYPE html>
<html lang="en" @if (Route::currentRouteName() == 'rtl_layout') dir="rtl" @endif
    @if (Route::currentRouteName() === 'layout_dark') data-theme="dark" @endif>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">
    <title>MRCM Insurance | @yield('title')</title>
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap"
        rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fontawesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/icofont.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/themify.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/flag-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/feather-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/slick-theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/scrollbar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/jquery.dataTables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/select.bootstrap5.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
    @yield('css')
</head>
@switch(Route::currentRouteName())
    @case('box_layout')

        <body class="box-layout">
        @break

        @case('rtl_layout')

            <body class="rtl">
            @break

            @case('layout_dark')

                <body class="dark-only">
                @break

                @default

                    <body>
                @endswitch
                <div class="loader-wrapper">
                    <div class="loader-index"> <span></span></div>
                    <svg>
                        <defs></defs>
                        <filter id="goo">
                            <fegaussianblur in="SourceGraphic" stddeviation="11" result="blur"></fegaussianblur>
                            <fecolormatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9"
                                result="goo"> </fecolormatrix>
                        </filter>
                    </svg>
                </div>
                <div class="tap-top"><i data-feather="chevrons-up"></i></div>
                <div class="page-wrapper compact-wrapper" id="pageWrapper">
                    @include('layouts.header')
                    <div class="page-body-wrapper horizontal-menu">
                        @include('layouts.sidebar')
                        <div class="page-body">
                            @yield('main_content')
                        </div>
                        @include('layouts.footer')
                    </div>
                </div>

                {{-- WhatsApp Floating Button - Only for Client Role --}}
                @auth
                    @if (auth()->user()->hasRole('Client'))
                        <a href="https://wa.me/60183693433" target="_blank" class="whatsapp-float"
                            title="Chat with us on WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>

                        <style>
                            .whatsapp-float {
                                position: fixed;
                                width: 60px;
                                height: 60px;
                                bottom: 30px;
                                right: 30px;
                                background-color: #25d366;
                                color: #FFF;
                                border-radius: 50px;
                                text-align: center;
                                font-size: 30px;
                                box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
                                z-index: 1000;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: all 0.3s ease;
                                text-decoration: none;
                            }

                            .whatsapp-float:hover {
                                background-color: #128c7e;
                                transform: scale(1.1);
                                box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.4);
                                color: #FFF;
                            }
                        </style>
                    @endif
                @endauth
                <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
                <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
                <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
                <script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
                <script src="{{ asset('assets/js/scrollbar/simplebar.min.js') }}"></script>
                <script src="{{ asset('assets/js/scrollbar/custom.js') }}"></script>
                <script src="{{ asset('assets/js/config.js') }}"></script>
                <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
                <script src="{{ asset('assets/js/sidebar-pin.js') }}"></script>
                <script src="{{ asset('assets/js/slick/slick.min.js') }}"></script>
                <script src="{{ asset('assets/js/slick/slick.js') }}"></script>
                <script src="{{ asset('assets/js/header-slick.js') }}"></script>
                <script src="{{ asset('assets/js/prism/prism.min.js') }}"></script>
                <script src="{{ asset('assets/js/clipboard/clipboard.min.js') }}"></script>
                <script src="{{ asset('assets/js/custom-card/custom-card.js') }}"></script>
                <script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
                <script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
                <script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
                <script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
                <script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>
                @yield('scripts')
                <script src="{{ asset('assets/js/script.js') }}"></script>
                <script src="{{ asset('assets/js/script1.js') }}"></script>
                <script src="{{ asset('assets/js/theme-customizer/customizer.js') }}"></script>
            </body>

</html>
