<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900"
        rel="stylesheet">

    <title>MANTRA - Profile</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/mantra.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lightbox.css') }}">
</head>

<body>


    <!--header-->
    <header class="main-header clearfix" role="header">
        <div class="logo">
            <a href="{{ url('/') }}">
                <em>MANTRA</em>
                <span class="tagline">Turn your mood into progress.</span>
            </a>
        </div>
        <a href="#menu" class="menu-link"><i class="fa fa-bars"></i></a>
        <nav id="menu" class="main-nav" role="navigation">
            <ul class="main-menu">
                <li><a href="{{ url('/') }}">Home</a></li>
                <!-- Auth links will be injected here by auth.js -->
                <li><a href="{{ url('/login') }}" id="nav-login">Login</a></li>
                <li><a href="{{ url('/register') }}" id="nav-register">Register</a></li>
            </ul>
        </nav>
    </header>

    <section class="section contact" data-section="section6" style="padding-top: 150px; min-height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="section-heading">
                        <h2>Your Profile</h2>
                    </div>
                    <div style="background-color: rgba(250, 250, 250, 0.1); padding: 40px; border-radius: 5px;">
                        <div class="row">
                            <div class="col-md-12 text-center mb-4">
                                <div
                                    style="width: 100px; height: 100px; background-color: #f5a425; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; font-size: 40px; color: white;">
                                    <i class="fa fa-user"></i>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h4 style="color: white; margin-bottom: 20px;">Name: <span id="profile-name"
                                        style="color: #f5a425; font-weight: 400;">Loading...</span></h4>
                                <h4 style="color: white; margin-bottom: 30px;">Email: <span id="profile-email"
                                        style="color: #f5a425; font-weight: 400;">Loading...</span></h4>
                            </div>
                            <div class="col-md-12">
                                <button id="logout-btn" class="button"
                                    style="background-color: #d9534f;">Logout</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p><i class="fa fa-copyright"></i> {{ date('Y') }} MANTRA
                        | Designed & Developed by Aneeta Peter
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/isotope.min.js') }}"></script>
    <script src="{{ asset('js/owl-carousel.js') }}"></script>
    <script src="{{ asset('js/lightbox.js') }}"></script>
    <script src="{{ asset('js/tabs.js') }}"></script>
    <script src="{{ asset('js/slick-slider.js') }}"></script>
    <script src="{{ asset('js/video.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/auth.js') }}"></script>
</body>

</html>