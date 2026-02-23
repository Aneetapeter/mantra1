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
            background: linear-gradient(135deg, #5C7CFA, #7c5cfa);
            padding: 32px 32px 24px;
            text-align: center;
        }

        .header img {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            margin-bottom: 12px;
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
            <h1>📚 Mantra</h1>
        </div>
        <div class="body">
            <h2>Hey {{ $userName }}, keep your streak alive! 🔥</h2>
            <p>You haven't studied today yet. Head back to Mantra and keep your streak going — every session counts!</p>
            <p>Even 15 minutes of focused study can make a big difference over time.</p>
            <a href="{{ url('/dashboard') }}" class="btn">→ Go to Dashboard</a>
            <p style="margin-top:20px; font-size:12px; color:rgba(255,255,255,0.3);">You're receiving this because Study
                Reminders are enabled in your settings. <a href="{{ url('/settings') }}" style="color:#5C7CFA;">Manage
                    preferences</a></p>
        </div>
        <div class="footer">© {{ date('Y') }} Mantra · Your Learning HQ</div>
    </div>
</body>

</html>