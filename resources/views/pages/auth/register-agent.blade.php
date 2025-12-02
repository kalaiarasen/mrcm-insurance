<!DOCTYPE html>
<html lang="en">
  <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="pixelstrap">
        <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">
        <title>MRCM Insurance | Agent Registration</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fontawesome.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/icofont.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/themify.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/flag-icon.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/feather-icon.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/scrollbar.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
        <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
        <style>
            .form-check-input {
                width: 1.2em;
                height: 1.2em;
                margin-top: 0.25em;
                border: 2px solid #495057;
                cursor: pointer;
                background-color: #fff;
            }
            .form-check-input:checked {
                background-color: #fff;
                border-color: #198754;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23198754' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
            }
            .form-check-label {
                cursor: pointer;
                padding-left: 0.5em;
                color: #333;
            }
        </style>
  </head>
  <body>
    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">
            <div class="col-12 p-0 d-flex align-items-center justify-content-center">
                <div class="login-card login-dark" style="max-width: 600px; width: 100%;">
                    <div class="px-4 py-5">
                        <div class="text-center mb-4">
                            <a class="logo" href="{{ route('home') }}">
                                <img class="img-fluid for-light" src="{{ asset('img/logo.png') }}" alt="logo">
                                <img class="img-fluid for-dark" src="{{ asset('img/logo.png') }}" alt="logo">
                            </a>
                        </div>
                        <div class="login-main">
                            <form class="theme-form" method="POST" action="{{ route('agent.register.submit') }}">
                                @csrf
                                <h4>Become an Agent</h4>
                                <p>Fill in your details to apply as an agent</p>
                                
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

                                <!-- Personal Information -->
                                <div class="form-group mb-3">
                                    <label class="col-form-label">Full Name <span class="text-danger">*</span></label>
                                    <input class="form-control @error('name') is-invalid @enderror" 
                                           type="text" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required 
                                           placeholder="Enter your full name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="col-form-label">Email Address <span class="text-danger">*</span></label>
                                    <input class="form-control @error('email') is-invalid @enderror" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           placeholder="Enter your email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="col-form-label">Contact Number <span class="text-danger">*</span></label>
                                    <input class="form-control @error('contact_no') is-invalid @enderror" 
                                           type="text" 
                                           name="contact_no" 
                                           value="{{ old('contact_no') }}" 
                                           required 
                                           placeholder="60123456789">
                                    @error('contact_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="col-form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <input class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           type="date" 
                                           name="date_of_birth" 
                                           value="{{ old('date_of_birth') }}" 
                                           required>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="col-form-label">Location <span class="text-danger">*</span></label>
                                    <input class="form-control @error('location') is-invalid @enderror" 
                                           type="text" 
                                           name="location" 
                                           value="{{ old('location') }}" 
                                           required 
                                           placeholder="City, State">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="col-form-label">Bank Account Number <span class="text-danger">*</span></label>
                                    <input class="form-control @error('bank_account_number') is-invalid @enderror" 
                                           type="text" 
                                           name="bank_account_number" 
                                           value="{{ old('bank_account_number') }}" 
                                           required 
                                           placeholder="Enter your bank account number">
                                    @error('bank_account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="col-form-label">Password <span class="text-danger">*</span></label>
                                    <input class="form-control @error('password') is-invalid @enderror" 
                                           type="password" 
                                           name="password" 
                                           required 
                                           placeholder="Enter password (min. 8 characters)">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label class="col-form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input class="form-control" 
                                           type="password" 
                                           name="password_confirmation" 
                                           required 
                                           placeholder="Confirm your password">
                                </div>

                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="subscribe_newsletter" 
                                               id="subscribe_newsletter" 
                                               value="1"
                                               {{ old('subscribe_newsletter') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="subscribe_newsletter">
                                            Subscribe to newsletter and receive offers
                                        </label>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> Your application will be reviewed by our admin team. You will receive an email notification once approved.
                                </div>

                                <div class="form-group mb-3">
                                    <button class="btn btn-primary btn-block w-100" type="submit">Submit Application</button>
                                </div>

                                <p class="mt-4 mb-0 text-center">
                                    Already have an account? 
                                    <a class="ms-2" href="{{ route('login') }}">Sign in</a>
                                </p>
                                <p class="mt-2 mb-0 text-center">
                                    Want to register as a client? 
                                    <a class="ms-2" href="{{ route('register') }}">Client Registration</a>
                                </p>
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
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
  </body>
</html>
