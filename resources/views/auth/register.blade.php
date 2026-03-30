<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <title>MANTRA — Create Account</title>
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

        /* ════ LEFT ════ */
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

        .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(135deg, rgba(245, 164, 37, 0.06) 0%, transparent 50%),
                linear-gradient(to right, rgba(245, 164, 37, 0.03), transparent 70%);
            pointer-events: none;
        }

        .panel-left::after {
            content: '';
            position: absolute;
            top: 10%;
            bottom: 10%;
            right: 0;
            width: 1px;
            background: linear-gradient(to bottom, transparent, rgba(245, 164, 37, 0.6), transparent);
        }

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

        .dot-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

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

        /* Brand */
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

        /* Hero */
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
            font-size: 55px;
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

        /* Stats */
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

        /* ════ RIGHT ════ */
        .panel-right {
            flex: 0 0 455px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 44px 50px;
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

        .panel-right::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #f5a425 0%, #bb5d10 100%);
        }

        .corner-mark {
            position: absolute;
            bottom: 28px;
            right: 28px;
            opacity: 0.06;
            pointer-events: none;
        }

        .corner-mark img {
            width: 72px;
        }

        /* Form header */
        .fh-tag {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 3.5px;
            text-transform: uppercase;
            color: #f5a425;
            margin-bottom: 8px;
        }

        .fh-title {
            font-size: 30px;
            font-weight: 900;
            color: #06090f;
            line-height: 1.1;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .fh-sub {
            font-size: 13px;
            color: #b0b0b0;
            margin-bottom: 26px;
        }

        /* Fields */
        .field {
            margin-bottom: 15px;
        }

        .field label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #06090f;
            margin-bottom: 7px;
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
            height: 50px;
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

        /* Strength */
        .str-track {
            height: 3px;
            border-radius: 4px;
            background: #ececec;
            margin-top: 7px;
            overflow: hidden;
        }

        .str-fill {
            height: 100%;
            width: 0%;
            border-radius: 4px;
            transition: width 0.4s, background 0.4s;
        }

        .str-label {
            font-size: 10px;
            font-weight: 600;
            color: #ccc;
            margin-top: 4px;
            letter-spacing: 0.5px;
            min-height: 14px;
        }

        /* Button */
        .btn-main {
            width: 100%;
            height: 52px;
            margin-top: 10px;
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

        /* Footer */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 18px 0 14px;
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

        .terms {
            font-size: 11px;
            color: #ccc;
            text-align: center;
            margin-top: 10px;
            line-height: 1.7;
        }

        .terms a {
            color: #f5a425;
            font-weight: 600;
            text-decoration: none;
        }

        /* Alert */
        .alert-box {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 13px 16px;
            border-radius: 10px;
            font-size: 12.5px;
            margin-bottom: 18px;
            animation: fadeUp 0.4s ease;
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

            <div class="brand">
                <div class="brand-logo">
                    <img src="{{ asset('images/mantra.png') }}" alt="MANTRA">
                </div>
                <div class="brand-text">
                    <span class="name"><span>M</span>ANTRA</span>
                    <span class="tagline">Study · Focus · Achieve</span>
                </div>
            </div>

            <div class="hero">
                <p class="hero-eyebrow">New here?</p>
                <h1 class="hero-title">
                    <span class="word">Start&nbsp;</span><span class="word">Your&nbsp;</span><br>
                    <span class="word accent">Best&nbsp;</span><span class="word">Semester.</span>
                </h1>
                <p class="hero-sub">
                    Join students mastering their academics with MANTRA's powerful study tools.
                </p>
            </div>

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

            <p class="fh-tag">Get started</p>
            <h2 class="fh-title">Create Account</h2>
            <p class="fh-sub">Fill in your details to join MANTRA.</p>

            @if($errors->any())
                <div class="alert-box alert-error">
                    <i class="fa fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form id="reg-form" action="{{ route('register.post') }}" method="POST">
                @csrf

                <div class="field">
                    <label for="name">User Name </label>
                    <div class="fi">
                        <i class="fa fa-at lead"></i>
                        <input type="text" id="name" name="name" placeholder="Choose a unique username"
                            value="{{ old('name', request('name')) }}" required>
                    </div>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <div class="fi">
                        <i class="fa fa-envelope lead"></i>
                        <input type="email" id="email" name="email" placeholder="you@example.com"
                            value="{{ old('email', request('email')) }}" required>
                    </div>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="fi">
                        <i class="fa fa-lock lead"></i>
                        <input type="password" id="password" name="password" placeholder="Create a strong password"
                            required>
                        <i class="fa fa-eye pw-btn" id="pw-toggle"></i>
                    </div>
                    <div class="str-track">
                        <div class="str-fill" id="str-fill"></div>
                    </div>
                    <div class="str-label" id="str-label"></div>
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="fi">
                        <i class="fa fa-lock lead"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Re-enter your password" required>
                        <i class="fa fa-eye pw-btn" id="pw-toggle-c"></i>
                    </div>
                </div>

                <button type="submit" class="btn-main">Create Account</button>

                <p class="terms">
                    By registering you agree to our
                    <a href="#">Terms of Service</a> &amp; <a href="#">Privacy Policy</a>.
                </p>

                <div class="divider"><span>OR</span></div>

                <p class="form-footer">
                    Already have an account? <a href="{{ route('login') }}">Sign in</a>
                </p>
            </form>
        </div>
    </div>

    <script src="{{ asset('jquery/jquery.min.js') }}"></script>
    <script>
        $(function () {
            $('#pw-toggle').on('click', function () {
                var $p = $('#password');
                $p.attr('type', $p.attr('type') === 'password' ? 'text' : 'password');
                $(this).toggleClass('fa-eye fa-eye-slash');
            });

            $('#pw-toggle-c').on('click', function () {
                var $p = $('#password_confirmation');
                $p.attr('type', $p.attr('type') === 'password' ? 'text' : 'password');
                $(this).toggleClass('fa-eye fa-eye-slash');
            });

            $('#password').on('input', function () {
                var v = $(this).val(), s = 0;
                if (v.length >= 8) s++;
                if (/[A-Z]/.test(v)) s++;
                if (/[0-9]/.test(v)) s++;
                if (/[^A-Za-z0-9]/.test(v)) s++;
                var m = { 0: { w: '0%', c: '#ececec', l: '' }, 1: { w: '25%', c: '#e74c3c', l: 'Weak' }, 2: { w: '50%', c: '#f5a425', l: 'Fair' }, 3: { w: '75%', c: '#3b82f6', l: 'Good' }, 4: { w: '100%', c: '#22c55e', l: 'Strong' } };
                $('#str-fill').css({ width: m[s].w, background: m[s].c });
                $('#str-label').text(m[s].l).css('color', m[s].c || '#ccc');
            });
        });
    </script>

    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display:none;">@csrf</form>
</body>

</html>