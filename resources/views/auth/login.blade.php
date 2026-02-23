<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900"
        rel="stylesheet">

    <title>MANTRA - Login</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mantra.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lightbox.css') }}">

    <style>
        /* Login Form Enhancements */
        .password-wrapper {
            position: relative;
            display: block;
        }

        .password-wrapper .form-control {
            padding-right: 45px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 12px;
            cursor: pointer;
            color: #888;
            font-size: 16px;
            z-index: 10;
            line-height: 1;
        }

        .password-toggle:hover {
            color: #f5a425;
        }

        .form-error {
            color: #ff6b6b;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .form-error.show {
            display: block;
        }

        .caps-warning {
            color: #ffc107;
            font-size: 11px;
            margin-top: 5px;
            display: none;
        }

        .caps-warning.show {
            display: block;
        }

        .caps-warning i {
            margin-right: 5px;
        }

        .remember-forgot-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 15px 0 25px 0;
            padding: 0;
        }

        .remember-me {
            display: inline-flex;
            align-items: center;
            color: #ccc;
            font-size: 13px;
            margin: 0;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 15px;
            height: 15px;
            cursor: pointer;
            margin: 0 8px 0 0;
        }

        .forgot-link {
            color: #f5a425;
            font-size: 13px;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
            color: #f5a425;
        }

        .button.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .button .spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid #fff;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
        }

        .button.loading .spinner {
            display: inline-block;
        }

        .button.loading .btn-text {
            display: none;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .server-error {
            background: rgba(255, 107, 107, 0.1);
            border: 1px solid #ff6b6b;
            color: #ff6b6b;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .form-control.error {
            border-color: #ff6b6b !important;
        }

        .input-wrapper {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>


    <!--header-->
    <header class="main-header clearfix" role="header">
        <div class="logo">
            <a href="{{ url('/') }}">
                <em>MANTRA</em>
                <span class="tagline">Study. Focus. Achieve</span>
            </a>
        </div>
        <a href="#menu" class="menu-link"><i class="fa fa-bars"></i></a>
        <nav id="menu" class="main-nav" role="navigation">
            <ul class="main-menu">
                <li><a href="{{ url('/') }}">Home</a></li>

                <!-- Auth links will be injected here by auth.js -->
                @auth
                    <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>

                    <li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </nav>
    </header>

    <section class="section contact" data-section="section6" style="padding-top: 150px; min-height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="section-heading">
                        <h2>Login</h2>
                    </div>

                    <!-- Info message for redirected users -->
                    @if (session('info'))
                        <div class="welcome-notice" style="
                                background: linear-gradient(135deg, rgba(40, 167, 69, 0.15) 0%, rgba(32, 134, 55, 0.1) 100%);
                                border-left: 4px solid #28a745;
                                color: #a8e6cf;
                                padding: 16px 20px;
                                border-radius: 8px;
                                margin-bottom: 25px;
                                font-size: 14px;
                                display: flex;
                                align-items: center;
                                gap: 12px;
                                animation: slideIn 0.4s ease-out;
                                box-shadow: 0 4px 15px rgba(40, 167, 69, 0.1);
                            ">
                            <i class="fa fa-check-circle" style="font-size: 20px; color: #28a745;"></i>
                            <span>{{ session('info') }}</span>
                        </div>
                        <style>
                            @keyframes slideIn {
                                from {
                                    opacity: 0;
                                    transform: translateY(-10px);
                                }

                                to {
                                    opacity: 1;
                                    transform: translateY(0);
                                }
                            }
                        </style>
                    @endif

                    <!-- Server-side error display -->
                    @if ($errors->any())
                        <div class="server-error">
                            <i class="fa fa-exclamation-circle"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ url('/login') }}" id="login-form" novalidate>
                        @csrf
                        <div class="row">
                            <!-- Email Field -->
                            <div class="col-md-12">
                                <div class="input-wrapper">
                                    <fieldset>
                                        <input name="email" type="email" class="form-control" id="email"
                                            placeholder="Your Email" autofocus value="{{ old('email') }}">
                                    </fieldset>
                                    <div class="form-error" id="email-error">
                                        <i class="fa fa-times-circle"></i> <span></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Field -->
                            <div class="col-md-12">
                                <div class="input-wrapper">
                                    <fieldset class="password-wrapper">
                                        <input name="password" type="password" class="form-control" id="password"
                                            placeholder="Password">
                                        <i class="fa fa-eye password-toggle" id="toggle-password"></i>
                                    </fieldset>
                                    <div class="form-error" id="password-error">
                                        <i class="fa fa-times-circle"></i> <span></span>
                                    </div>
                                    <div class="caps-warning" id="caps-warning">
                                        <i class="fa fa-exclamation-triangle"></i> Caps Lock is ON
                                    </div>
                                </div>
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="col-md-12">
                                <div class="remember-forgot-row">
                                    <label class="remember-me">
                                        <input type="checkbox" name="remember" id="remember">
                                        Remember me
                                    </label>
                                    <a href="{{ url('/forgot-password') }}" class="forgot-link">Forgot password?</a>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-md-12 text-center">
                                <fieldset style="text-align: center;">
                                    <button type="submit" id="login-btn" class="button" style="margin: 0 auto;">
                                        <span class="spinner"></span>
                                        <span class="btn-text">Login Now</span>
                                        <span class="loading-text" style="display: none;">Logging in...</span>
                                    </button>
                                </fieldset>
                            </div>

                            <div class="col-md-12 mt-3 text-center">
                                <p style="color: white;">Don't have an account? <a href="{{ route('register') }}"
                                        style="color: #f5a425;">Register here</a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p>
                        © {{ date('Y') }} MANTRA — All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="jquery/jquery.min.js"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/isotope.min.js') }}"></script>
    <script src="{{ asset('js/owl-carousel.js') }}"></script>
    <script src="{{ asset('js/lightbox.js') }}"></script>
    <script src="{{ asset('js/tabs.js') }}"></script>
    <script src="{{ asset('js/slick-slider.js') }}"></script>
    <script src="{{ asset('js/video.js') }}"></script>
    <script src="js/custom.js"></script>

    <script>
        $(document).ready(function () {
            const form = $('#login-form');
            const emailInput = $('#email');
            const passwordInput = $('#password');
            const loginBtn = $('#login-btn');

            // ========== Password Visibility Toggle ==========
            $('#toggle-password').on('click', function () {
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });

            // ========== Caps Lock Detection ==========
            passwordInput.on('keyup keydown', function (e) {
                const capsOn = e.originalEvent.getModifierState && e.originalEvent.getModifierState('CapsLock');
                $('#caps-warning').toggleClass('show', capsOn);
            });

            // ========== Client-side Validation ==========
            function showError(inputId, message) {
                const errorEl = $('#' + inputId + '-error');
                errorEl.find('span').text(message);
                errorEl.addClass('show');
                $('#' + inputId).addClass('error');
            }

            function clearError(inputId) {
                $('#' + inputId + '-error').removeClass('show');
                $('#' + inputId).removeClass('error');
            }

            function clearAllErrors() {
                clearError('email');
                clearError('password');
            }

            function validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }

            function validateForm() {
                let isValid = true;
                clearAllErrors();

                const email = emailInput.val().trim();
                const password = passwordInput.val();

                // Email validation
                if (!email) {
                    showError('email', 'Please enter your email');
                    isValid = false;
                } else if (!validateEmail(email)) {
                    showError('email', 'Please enter a valid email address');
                    isValid = false;
                }

                // Password validation
                if (!password) {
                    showError('password', 'Please enter your password');
                    isValid = false;
                } else if (password.length < 6) {
                    showError('password', 'Password must be at least 6 characters');
                    isValid = false;
                }

                return isValid;
            }

            // Clear errors on input
            emailInput.on('input', function () {
                clearError('email');
            });

            passwordInput.on('input', function () {
                clearError('password');
            });

            // ========== Form Submission with Loading State ==========
            form.on('submit', function (e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }

                // Show loading state
                loginBtn.addClass('loading');
                loginBtn.find('.btn-text').hide();
                loginBtn.find('.loading-text').show();
            });

            // ========== Keyboard Support ==========
            // Enter key already works by default
            // Tab navigation works by default
        });
    </script>

    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</body>

</html>