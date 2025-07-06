<!DOCTYPE html>
<html lang="en" @if (Route::currentRouteName() == 'rtl_layout') dir="rtl" @endif
    @if (Route::currentRouteName() === 'layout_dark') data-theme="dark" @endif>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="pixelstrap">
        <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
        <title>@yield('title') | Cuba - Premium Admin Template By Pixelstrap</title>
        <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">
        
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
