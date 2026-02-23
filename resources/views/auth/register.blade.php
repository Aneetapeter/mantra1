<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900"
        rel="stylesheet">

    <title>MANTRA | Register</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mantra.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lightbox.css') }}">
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

                @auth
                    <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                    </li>
                @else
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @endauth

            </ul>
        </nav>
    </header>

    <section class="section contact" data-section="section6" style="padding-top: 150px; min-height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="section-heading">
                        <h2>Register</h2>
                    </div>

                    <!-- Error display -->
                    @if ($errors->any())
                        <div class="server-error" style="
                                background: rgba(255, 107, 107, 0.1);
                                border-left: 4px solid #ff6b6b;
                                color: #ff6b6b;
                                padding: 16px 20px;
                                border-radius: 8px;
                                margin-bottom: 25px;
                                font-size: 14px;
                                display: flex;
                                align-items: center;
                                gap: 12px;
                                animation: slideIn 0.4s ease-out;
                            ">
                            <i class="fa fa-exclamation-circle" style="font-size: 20px;"></i>
                            <span>{{ $errors->first() }}</span>
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

                    <form id="registerRequest" action="{{ route('register.post') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <input name="name" type="text" class="form-control" id="name"
                                        placeholder="User ID (must be unique)" required="">
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset>
                                    <input name="email" type="email" class="form-control" id="email"
                                        placeholder="Your Email" required="">
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset>
                                    <input name="password" type="password" class="form-control" id="password"
                                        placeholder="Password" required="">
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset>
                                    <button type="submit" id="form-submit" class="button">Register Now</button>
                                </fieldset>
                            </div>
                            <div class="col-md-12 mt-3 text-center">
                                <p style="color: white;">Already have an account? <a href="{{ route('login') }}"
                                        style="color: #f5a425;">Login here</a>
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
    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>

</html>