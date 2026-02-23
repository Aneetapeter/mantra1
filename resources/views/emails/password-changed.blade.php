<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 520px;
            margin: 0 auto;
            background: #1a1b21;
            border-radius: 16px;
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #E53935, #c62828);
            padding: 32px 32px 24px;
            text-align: center;
        }

        .header h1 {
            color: #fff;
            font-size: 22px;
            margin: 0;
            font-weight: 700;
        }

        .body {
            padding: 28px 32px;
            color: #ccc;
            font-size: 15px;
            line-height: 1.7;
        }

        .body h2 {
            color: #fff;
            font-size: 18px;
            margin: 0 0 12px;
        }

        .alert-box {
            background: rgba(229, 57, 53, 0.1);
            border: 1px solid rgba(229, 57, 53, 0.3);
            border-radius: 10px;
            padding: 16px;
            margin: 16px 0;
            color: #ff7070;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 28px;
            background: linear-gradient(135deg, #5C7CFA, #7c5cfa);
            color: #fff;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .footer {
            padding: 16px 32px;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            text-align: center;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🔒 Security Alert</h1>
        </div>
        <div class="body">
            <h2>Hi {{ $userName }}, your password was changed.</h2>
            <div class="alert-box">
                ⚠️ Your Mantra account password was just changed. If you did this, you can ignore this email. If you did
                NOT make this change, please reset your password immediately.
            </div>
            <p>If this wasn't you, click the button below to reset your password right away:</p>
            <a href="{{ url('/forgot-password') }}" class="btn">→ Reset My Password</a>
            <p style="margin-top:20px; font-size:12px; color:rgba(255,255,255,0.3);">This is an automatic security
                notification from Mantra.</p>
        </div>
        <div class="footer">© {{ date('Y') }} Mantra · Your Learning HQ</div>
    </div>
</body>

</html>