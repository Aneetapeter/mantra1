<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900"
        rel="stylesheet">

    <title>MANTRA - Logout</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">


    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mantra.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lightbox.css') }}">
</head>

<body>

    <!-- Header -->
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
                <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            </ul>
        </nav>
    </header>

    <!-- Logout Section -->
    <section class="section contact" style="padding-top: 150px; min-height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center">

                    <div class="section-heading">
                        <h2>Logout</h2>
                    </div>

                    <p style="color: white; font-size: 18px; margin-bottom: 30px;">
                        Are you sure you want to logout from MANTRA?
                    </p>

                    <!-- Logout Form -->
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="button" style="margin-right: 10px;">
                            Yes, Logout
                        </button>

                        <a href="{{ url('/dashboard') }}" class="button" style="background: #444;">
                            Cancel
                        </a>
                    </form>

                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p>
                        © {{ date('Y') }} MANTRA — Designed & Developed by Aneeta Peter
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

</body>

</html>