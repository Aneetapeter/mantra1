<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900"
        rel="stylesheet">
    <title>MANTRA - Forgot Password</title>
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mantra.css') }}">
    <style>
        .info-box {
            background: rgba(92, 124, 250, 0.1);
            border: 1px solid #5C7CFA;
            color: #8ab4f8;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 13px;
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

        .button.loading {
            pointer-events: none;
            opacity: 0.7;
        }
    </style>
</head>

<body>
    <header class="main-header clearfix" role="header">
        <div class="logo">
            <a href="{{ url('/') }}"><em>MANTRA</em><span class="tagline">Study. Focus. Achieve</span></a>
        </div>
        <nav id="menu" class="main-nav" role="navigation">
            <ul class="main-menu">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            </ul>
        </nav>
    </header>

    <section class="section contact" style="padding-top: 150px; min-height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="section-heading">
                        <h2>Forgot Password</h2>
                    </div>

                    @if ($errors->any())
                        <div class="server-error">
                            <i class="fa fa-exclamation-circle"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="info-box">
                        <i class="fa fa-info-circle"></i>
                        Enter your registered email address. We'll send a 6-digit OTP to it.
                    </div>

                    <form method="POST" action="{{ route('password.email') }}" id="forgot-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <input name="email" type="email" class="form-control" id="email"
                                        placeholder="Your registered email address" required autofocus
                                        value="{{ old('email') }}">
                                </fieldset>
                            </div>
                            <div class="col-md-12 mt-3 text-center">
                                <fieldset>
                                    <button type="submit" class="button" id="send-btn">
                                        <span class="btn-text">Send OTP</span>
                                    </button>
                                </fieldset>
                            </div>
                            <div class="col-md-12 mt-3 text-center">
                                <p style="color: white;">
                                    <a href="{{ route('login') }}" style="color: #f5a425;">
                                        <i class="fa fa-arrow-left"></i> Back to Login
                                    </a>
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
                    <p>© {{ date('Y') }} MANTRA — All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script>
        $('#forgot-form').on('submit', function () {
            $('#send-btn').addClass('loading').find('.btn-text').text('Sending...');
        });
    </script>
</body>

</html>