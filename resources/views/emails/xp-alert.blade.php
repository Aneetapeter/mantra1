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

        .xp-badge {
            display: inline-block;
            background: rgba(92, 124, 250, 0.15);
            border: 1px solid rgba(92, 124, 250, 0.4);
            color: #5C7CFA;
            padding: 8px 20px;
            border-radius: 99px;
            font-size: 22px;
            font-weight: 700;
            margin: 16px 0;
        }

        .stat-row {
            display: flex;
            gap: 16px;
            margin: 16px 0;
        }

        .stat {
            flex: 1;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 10px;
            padding: 14px;
            text-align: center;
        }

        .stat .val {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
        }

        .stat .lbl {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 4px;
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
            <h1>⚡ Mantra</h1>
        </div>
        <div class="body">
            <h2>You just earned XP, {{ $userName }}! 🎉</h2>
            <div class="xp-badge">+{{ $xpEarned }} XP</div>
            <div class="stat-row">
                <div class="stat">
                    <div class="val">{{ $totalXp }}</div>
                    <div class="lbl">Total XP</div>
                </div>
                <div class="stat">
                    <div class="val">{{ $title }}</div>
                    <div class="lbl">Your Title</div>
                </div>
            </div>
            <p>Keep studying to level up and unlock new achievements on Mantra!</p>
            <a href="{{ url('/progress') }}" class="btn">→ View Your Progress</a>
            <p style="margin-top:20px; font-size:12px; color:rgba(255,255,255,0.3);">You're receiving this because XP
                Alerts are enabled in your settings. <a href="{{ url('/settings') }}" style="color:#5C7CFA;">Manage
                    preferences</a></p>
        </div>
        <div class="footer">© {{ date('Y') }} Mantra · Your Learning HQ</div>
    </div>
</body>

</html>