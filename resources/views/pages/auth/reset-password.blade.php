<!DOCTYPE html>
<html lang="en">
  <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="pixelstrap">
        <link rel="icon" href="https://laravel.pixelstrap.com/cuba/assets/images/favicon.png" type="image/x-icon">
        <link rel="shortcut icon" href="https://laravel.pixelstrap.com/cuba/assets/images/favicon.png" type="image/x-icon">
        <title>Reset Password | Cuba - Premium Admin Template By Pixelstrap</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fontawesome.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/icofont.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/themify.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/flag-icon.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/feather-icon.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/slick.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/slick-theme.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/scrollbar.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
        <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
  </head>
  <body>
    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">
            <div class="col-xl-7 d-none d-xl-block">
                <div class="bg-img-cover bg-center h-100 position-relative" style="background-image: url('https://laravel.pixelstrap.com/cuba/assets/images/login/2.jpg'); min-height: 100vh;">
                </div>
            </div>
            <div class="col-xl-5 p-0 d-flex align-items-center">
                <div class="login-card login-dark w-100">
                    <div class="px-4 py-5">
                        <div class="text-center mb-4">
                            <a class="logo" href="{{ route('home') }}">
                                <img class="img-fluid for-light" src="https://laravel.pixelstrap.com/cuba/assets/images/logo/logo.png" alt="logo">
                                <img class="img-fluid for-dark" src="https://laravel.pixelstrap.com/cuba/assets/images/logo/logo_dark.png" alt="logo">
                            </a>
                        </div>
                        <div class="login-main">
                            <form class="theme-form" method="POST" action="{{ route('password.update') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <h4>Reset your password</h4>
                                <p>Enter your new password below</p>
                                
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label class="col-form-label">Email Address</label>
                                    <input class="form-control" type="email" name="email" value="{{ old('email', request()->email) }}" required placeholder="test@gmail.com">
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">New Password</label>
                                    <div class="form-input position-relative">
                                        <input class="form-control" type="password" name="password" required
                                            placeholder="*********">
                                        <div class="show-hide"><span class="show"> </span></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Confirm New Password</label>
                                    <div class="form-input position-relative">
                                        <input class="form-control" type="password" name="password_confirmation" required
                                            placeholder="*********">
                                        <div class="show-hide"><span class="show"> </span></div>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <button class="btn btn-primary btn-block w-100 mt-3" type="submit">Reset Password</button>
                                </div>
                                <p class="mt-4 mb-0 text-center">Remember your password?<a class="ms-2"
                                        href="{{ route('login') }}">Sign In</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
</body>
</html>
