<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900"
        rel="stylesheet">
    <title>MANTRA - Verify OTP</title>
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

        .otp-input {
            font-size: 24px;
            letter-spacing: 10px;
            text-align: center;
            font-weight: bold;
        }

        .countdown {
            color: #f5a425;
            font-size: 14px;
            margin-top: 10px;
        }

        .countdown.expired {
            color: #ff6b6b;
        }

        .resend-link {
            color: #f5a425;
            cursor: pointer;
            text-decoration: underline;
        }

        .resend-link.disabled {
            color: #666;
            cursor: not-allowed;
            text-decoration: none;
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
                        <h2>Verify OTP</h2>
                    </div>

                    @if (session('success'))
                        <div class="success-box">
                            <i class="fa fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="server-error">
                            <i class="fa fa-exclamation-circle"></i> {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="info-box">
                        <i class="fa fa-envelope"></i>
                        Enter the 6-digit OTP sent to <strong>{{ session('masked_email') }}</strong>
                    </div>



                    <form method="POST" action="{{ route('password.verify.post') }}" id="otp-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <fieldset>
                                    <input name="otp" type="text" class="form-control otp-input" id="otp"
                                        placeholder="000000" required maxlength="6" pattern="[0-9]{6}" autofocus>
                                </fieldset>
                                <div class="text-center countdown" id="countdown">
                                    OTP expires in: <span id="timer">30</span>s
                                </div>
                            </div>
                            <div class="col-md-12 mt-3 text-center">
                                <fieldset>
                                    <button type="submit" class="button" id="verify-btn">Verify OTP</button>
                                </fieldset>
                            </div>
                            <div class="col-md-12 mt-3 text-center">
                                <form method="POST" action="{{ route('password.resend') }}" id="resend-form"
                                    style="display: inline;">
                                    @csrf
                                    <span id="resend-text" class="resend-link disabled">Resend OTP</span>
                                </form>
                            </div>
                            <div class="col-md-12 mt-3 text-center">
                                <p style="color: white;">
                                    <a href="{{ route('password.request') }}" style="color: #ccc;">
                                        <i class="fa fa-arrow-left"></i> Change email
                                    </a>
                                </p>
                            </div>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('password.resend') }}" id="resend-form-hidden"
                        style="display: none;">
                        @csrf
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
        $(document).ready(function () {
            let timeLeft = 30;
            const timerEl = $('#timer');
            const countdownEl = $('#countdown');
            const resendText = $('#resend-text');

            const countdown = setInterval(function () {
                timeLeft--;
                timerEl.text(timeLeft);

                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    countdownEl.addClass('expired').html('<i class="fa fa-exclamation-triangle"></i> OTP expired!');
                    resendText.removeClass('disabled').text('Click to Resend OTP');
                }
            }, 1000);

            // Resend OTP
            resendText.on('click', function () {
                if (!$(this).hasClass('disabled')) {
                    $('#resend-form-hidden').submit();
                }
            });

            // Only allow numeric input
            $('#otp').on('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
</body>

</html>