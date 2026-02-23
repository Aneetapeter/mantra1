<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900"
        rel="stylesheet">
    <title>MANTRA - Reset Password</title>
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mantra.css') }}">
    <style>
        .success-box {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid #28a745;
            color: #5dd879;
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

        .password-wrapper {
            position: relative;
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
        }

        .password-toggle:hover {
            color: #f5a425;
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
            </ul>
        </nav>
    </header>

    <section class="section contact" style="padding-top: 150px; min-height: 100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="section-heading">
                        <h2>Reset Password</h2>
                    </div>

                    @if ($errors->any())
                        <div class="server-error">
                            <i class="fa fa-exclamation-circle"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="success-box">
                        <i class="fa fa-check-circle"></i> OTP verified! Create your new password.
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset class="password-wrapper">
                                    <input name="password" type="password" class="form-control" id="password"
                                        placeholder="New Password (min 6 characters)" required minlength="6" autofocus>
                                    <i class="fa fa-eye password-toggle" data-target="password"></i>
                                </fieldset>
                            </div>
                            <div class="col-md-12 mt-3">
                                <fieldset class="password-wrapper">
                                    <input name="password_confirmation" type="password" class="form-control"
                                        id="password_confirmation" placeholder="Confirm New Password" required
                                        minlength="6">
                                    <i class="fa fa-eye password-toggle" data-target="password_confirmation"></i>
                                </fieldset>
                            </div>
                            <div class="col-md-12 mt-3 text-center">
                                <fieldset>
                                    <button type="submit" class="button">Reset Password</button>
                                </fieldset>
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
        $('.password-toggle').on('click', function () {
            const targetId = $(this).data('target');
            const input = $('#' + targetId);
            const type = input.attr('type') === 'password' ? 'text' : 'password';
            input.attr('type', type);
            $(this).toggleClass('fa-eye fa-eye-slash');
        });
    </script>
</body>

</html>