<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <title>MANTRA — Sign In</title>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
            font-family: 'Montserrat', sans-serif;
            background: #06090f;
            overflow: hidden;
        }

        .page {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        /* ════════════════════════════════
           LEFT PANEL
        ════════════════════════════════ */
        .panel-left {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 52px 60px 52px 56px;
            background: #06090f;
            overflow: hidden;
        }

        /* Diagonal gradient cut */
        .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(135deg, rgba(245, 164, 37, 0.06) 0%, transparent 50%),
                linear-gradient(to right, rgba(245, 164, 37, 0.03), transparent 70%);
            pointer-events: none;
        }

        /* Right edge separator */
        .panel-left::after {
            content: '';
            position: absolute;
            top: 10%;
            bottom: 10%;
            right: 0;
            width: 1px;
            background: linear-gradient(to bottom, transparent, rgba(245, 164, 37, 0.6), transparent);
        }

        /* Ambient glow spots */
        .glow {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .glow-a {
            width: 480px;
            height: 480px;
            background: radial-gradient(circle, rgba(245, 164, 37, 0.09) 0%, transparent 65%);
            bottom: -160px;
            left: -120px;
            animation: drift 9s ease-in-out infinite alternate;
        }

        .glow-b {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(187, 93, 16, 0.07) 0%, transparent 65%);
            top: -60px;
            right: 80px;
            animation: drift 12s ease-in-out infinite alternate-reverse;
        }

        .glow-c {
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(245, 164, 37, 0.12) 0%, transparent 65%);
            top: 40%;
            left: 50%;
            animation: drift 7s ease-in-out infinite alternate;
        }

        @keyframes drift {
            0% {
                transform: translate(0, 0) scale(1);
            }

            100% {
                transform: translate(15px, -20px) scale(1.15);
            }
        }

        /* Fine dot grid */
        .dot-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        /* Thin horizontal line accent */
        .h-line {
            position: absolute;
            left: 56px;
            right: 1px;
            height: 1px;
            background: linear-gradient(to right, rgba(245, 164, 37, 0.25), transparent);
        }

        .h-line-top {
            top: 140px;
        }

        .h-line-bottom {
            bottom: 140px;
        }

        /* ─── BRAND ─── */
        .brand {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 16px;
            opacity: 0;
            animation: fadeUp 0.6s 0.1s ease forwards;
        }

        .brand-logo {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(245, 164, 37, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(245, 164, 37, 0.15), 0 0 0 1px rgba(245, 164, 37, 0.08);
            transition: box-shadow 0.3s;
        }

        .brand-logo img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            filter: drop-shadow(0 2px 6px rgba(245, 164, 37, 0.3));
        }

        .brand-text .name {
            display: block;
            font-size: 18px;
            font-weight: 900;
            color: #fff;
            letter-spacing: 4px;
            text-transform: uppercase;
            line-height: 1;
        }

        .brand-text .name span {
            color: #f5a425;
        }

        .brand-text .tagline {
            display: block;
            font-size: 8px;
            color: rgba(255, 255, 255, 0.3);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 5px;
        }

        /* ─── HERO ─── */
        .hero {
            position: relative;
            z-index: 2;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #f5a425;
            margin-bottom: 22px;
            opacity: 0;
            animation: fadeUp 0.6s 0.35s ease forwards;
        }

        .hero-eyebrow::before {
            content: '';
            width: 28px;
            height: 1px;
            background: #f5a425;
            flex-shrink: 0;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 58px;
            font-weight: 700;
            color: #fff;
            line-height: 1.08;
            letter-spacing: -1px;
        }

        .hero-title .word {
            display: inline-block;
            opacity: 0;
            transform: translateY(32px);
        }

        .hero-title .accent {
            color: transparent;
            -webkit-text-stroke: 1.5px #f5a425;
            font-style: italic;
        }

        .hero-title .word:nth-child(1) {
            animation: wordUp 0.65s 0.55s cubic-bezier(.22, 1, .36, 1) forwards;
        }

        .hero-title .word:nth-child(2) {
            animation: wordUp 0.65s 0.70s cubic-bezier(.22, 1, .36, 1) forwards;
        }

        .hero-title .word:nth-child(3) {
            animation: wordUp 0.65s 0.85s cubic-bezier(.22, 1, .36, 1) forwards;
        }

        .hero-title .word:nth-child(4) {
            animation: wordUp 0.65s 1.00s cubic-bezier(.22, 1, .36, 1) forwards;
        }

        @keyframes wordUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-sub {
            margin-top: 24px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.35);
            line-height: 2;
            max-width: 340px;
            opacity: 0;
            animation: fadeUp 0.6s 1.15s ease forwards;
        }

        /* ─── STATS ─── */
        .stats {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: flex-start;
            gap: 0;
            opacity: 0;
            animation: fadeUp 0.6s 1.4s ease forwards;
        }

        .stat {
            flex: 1;
            text-align: center;
            padding: 20px 0;
        }

        .stat+.stat {
            border-left: 1px solid rgba(255, 255, 255, 0.06);
        }

        .stat .val {
            display: block;
            font-size: 30px;
            font-weight: 900;
            color: #fff;
            line-height: 1;
            letter-spacing: -1px;
        }

        .stat .val em {
            font-style: normal;
            color: #f5a425;
        }

        .stat .key {
            display: block;
            font-size: 9px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.25);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 7px;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ════════════════════════════════
           RIGHT PANEL
        ════════════════════════════════ */
        .panel-right {
            flex: 0 0 455px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 52px 50px;
            position: relative;
            overflow-y: auto;
            animation: panelIn 0.85s 0.15s cubic-bezier(.22, 1, .36, 1) both;
        }

        @keyframes panelIn {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Top bar */
        .panel-right::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #f5a425 0%, #bb5d10 100%);
        }

        /* Corner brand mark (subtle) */
        .corner-mark {
            position: absolute;
            bottom: 32px;
            right: 32px;
            opacity: 0.06;
            pointer-events: none;
        }

        .corner-mark img {
            width: 80px;
        }

        /* ─── FORM HEADER ─── */
        .fh-tag {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 3.5px;
            text-transform: uppercase;
            color: #f5a425;
            margin-bottom: 8px;
        }

        .fh-title {
            font-size: 32px;
            font-weight: 900;
            color: #06090f;
            line-height: 1.1;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .fh-sub {
            font-size: 13px;
            color: #b0b0b0;
            margin-bottom: 36px;
        }

        /* ─── FIELDS ─── */
        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #06090f;
            margin-bottom: 8px;
        }

        .fi {
            position: relative;
        }

        .fi i.lead {
            position: absolute;
            left: 17px;
            top: 50%;
            transform: translateY(-50%);
            color: #d0d0d0;
            font-size: 13px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .field:focus-within .fi i.lead {
            color: #f5a425;
        }

        .fi input {
            width: 100%;
            height: 52px;
            padding: 0 18px 0 46px;
            border: 1.5px solid #ececec;
            border-radius: 10px;
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            color: #06090f;
            background: #f9f9f9;
            outline: none;
            transition: border-color 0.25s, box-shadow 0.25s, background 0.25s;
        }

        .fi input::placeholder {
            color: #ccc;
            font-size: 13px;
        }

        .fi input:focus {
            border-color: #f5a425;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(245, 164, 37, 0.1);
        }

        .fi input.err {
            border-color: #e74c3c;
        }

        .pw-btn {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #ccc;
            font-size: 14px;
            transition: color 0.2s;
        }

        .pw-btn:hover {
            color: #f5a425;
        }

        .field-err {
            font-size: 11px;
            color: #e74c3c;
            margin-top: 5px;
            display: none;
        }

        .field-err.show {
            display: block;
        }

        .caps-alert {
            font-size: 11px;
            color: #f5a425;
            margin-top: 5px;
            display: none;
        }

        .caps-alert.show {
            display: block;
        }

        /* ─── REMEMBER ROW ─── */
        .check-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .check-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #888;
            cursor: pointer;
            user-select: none;
        }

        .check-label input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: #f5a425;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 12px;
            font-weight: 700;
            color: #f5a425;
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #bb5d10;
        }

        /* ─── BUTTON ─── */
        .btn-main {
            width: 100%;
            height: 54px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #f5a425 0%, #c07010 100%);
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        /* Shimmer */
        .btn-main::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -75%;
            width: 50%;
            height: 200%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.22), transparent);
            transform: skewX(-20deg);
            transition: left 0.55s ease;
        }

        .btn-main:hover::after {
            left: 130%;
        }

        .btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 34px rgba(245, 164, 37, 0.38);
        }

        .btn-main:active {
            transform: translateY(0);
        }

        .btn-main.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        /* ─── OR / FOOTER ─── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0 16px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #ececec;
        }

        .divider span {
            font-size: 10px;
            font-weight: 700;
            color: #d0d0d0;
            letter-spacing: 2px;
        }

        .form-footer {
            text-align: center;
            font-size: 13px;
            color: #aaa;
        }

        .form-footer a {
            color: #f5a425;
            font-weight: 800;
            text-decoration: none;
            transition: color 0.2s;
        }

        .form-footer a:hover {
            color: #bb5d10;
        }

        /* ─── ALERTS ─── */
        .alert-box {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 13px 16px;
            border-radius: 10px;
            font-size: 12.5px;
            margin-bottom: 22px;
            animation: fadeUp 0.4s ease;
        }

        .alert-success {
            background: #f0fdf4;
            border-left: 3px solid #22c55e;
            color: #15803d;
        }

        .alert-error {
            background: #fff5f5;
            border-left: 3px solid #e74c3c;
            color: #b91c1c;
        }

        @media (max-width: 860px) {
            .panel-left {
                display: none;
            }

            .panel-right {
                flex: 1;
            }
        }
    </style>
</head>

<body>
    <div class="page">

        <!-- ════ LEFT ════ -->
        <div class="panel-left">
            <div class="dot-grid"></div>
            <div class="glow glow-a"></div>
            <div class="glow glow-b"></div>
            <div class="glow glow-c"></div>
            <div class="h-line h-line-top"></div>
            <div class="h-line h-line-bottom"></div>

            <!-- Brand -->
            <div class="brand">
                <div class="brand-logo">
                    <img src="{{ asset('images/mantra.png') }}" alt="MANTRA Logo">
                </div>
                <div class="brand-text">
                    <span class="name"><span>M</span>ANTRA</span>
                    <span class="tagline">Study · Focus · Achieve</span>
                </div>
            </div>

            <!-- Hero -->
            <div class="hero">
                <p class="hero-eyebrow">Student Platform</p>
                <h1 class="hero-title">
                    <span class="word">Your&nbsp;</span><span class="word">Academic&nbsp;</span><br>
                    <span class="word accent">Journey&nbsp;</span><span class="word">Awaits.</span>
                </h1>
                <p class="hero-sub">
                    A powerful study platform built for students who take their academics seriously.
                </p>
            </div>

            <!-- Stats -->
            <div class="stats">
                <div class="stat">
                    <span class="val">500<em>+</em></span>
                    <span class="key">Students</span>
                </div>
                <div class="stat">
                    <span class="val">98<em>%</em></span>
                    <span class="key">Satisfaction</span>
                </div>
                <div class="stat">
                    <span class="val">24<em>/7</em></span>
                    <span class="key">Access</span>
                </div>
            </div>
        </div>

        <!-- ════ RIGHT ════ -->
        <div class="panel-right">
            <div class="corner-mark">
                <img src="{{ asset('images/mantra.png') }}" alt="">
            </div>

            <p class="fh-tag">Welcome back</p>
            <h2 class="fh-title">Sign In</h2>
            <p class="fh-sub">Enter your credentials to continue.</p>

            @if(session('info') || session('success'))
                <div class="alert-box alert-success">
                    <i class="fa fa-check-circle"></i>
                    <span>{{ session('success') ?? session('info') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-box alert-error">
                    <i class="fa fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form id="login-form" method="POST" action="{{ url('/login') }}" novalidate>
                @csrf

                <div class="field">
                    <label for="email">Email Address</label>
                    <div class="fi">
                        <i class="fa fa-envelope lead"></i>
                        <input type="email" id="email" name="email" placeholder="you@example.com"
                            value="{{ old('email') }}" autofocus>
                    </div>
                    <div class="field-err" id="email-error"></div>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="fi">
                        <i class="fa fa-lock lead"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••">
                        <i class="fa fa-eye pw-btn" id="pw-toggle"></i>
                    </div>
                    <div class="field-err" id="password-error"></div>
                    <div class="caps-alert" id="caps-alert">
                        <i class="fa fa-exclamation-triangle"></i>&nbsp;Caps Lock is on
                    </div>
                </div>

                <div class="check-row">
                    <label class="check-label">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="{{ url('/forgot-password') }}" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" id="login-btn" class="btn-main">
                    <span class="t">Sign In</span>
                    <span class="l" style="display:none">Signing in…</span>
                </button>

                <div class="divider"><span>OR</span></div>

                <p class="form-footer">
                    Don't have an account? <a href="{{ route('register') }}">Create one</a>
                </p>
            </form>
        </div>

    </div>

    <script src="{{ asset('jquery/jquery.min.js') }}"></script>
    <script>
        $(function () {
            var $email = $('#email'), $pw = $('#password'), $btn = $('#login-btn');

            $('#pw-toggle').on('click', function () {
                $pw.attr('type', $pw.attr('type') === 'password' ? 'text' : 'password');
                $(this).toggleClass('fa-eye fa-eye-slash');
            });

            $pw.on('keyup keydown', function (e) {
                var on = e.originalEvent.getModifierState && e.originalEvent.getModifierState('CapsLock');
                $('#caps-alert').toggleClass('show', on);
            });

            function showErr(id, msg) { $('#' + id + '-error').text(msg).addClass('show'); $('#' + id).addClass('err'); }
            function clearErr(id) { $('#' + id + '-error').removeClass('show').text(''); $('#' + id).removeClass('err'); }

            $email.on('input', function () { clearErr('email'); });
            $pw.on('input', function () { clearErr('password'); });

            $('#login-form').on('submit', function (e) {
                var ok = true;
                clearErr('email'); clearErr('password');
                var em = $email.val().trim(), pw = $pw.val();
                if (!em) { showErr('email', 'Email is required'); ok = false; }
                else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em)) { showErr('email', 'Enter a valid email'); ok = false; }
                if (!pw) { showErr('password', 'Password is required'); ok = false; }
                else if (pw.length < 6) { showErr('password', 'Minimum 6 characters'); ok = false; }
                if (!ok) { e.preventDefault(); return; }
                $btn.addClass('loading').find('.t').hide();
                $btn.find('.l').show();
            });
        });
    </script>
    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display:none;">@csrf</form>
</body>

</html>