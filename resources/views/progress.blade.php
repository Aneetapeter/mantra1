<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mantra | My Progress</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        /* ── Progress page only ── */
        .p-card {
            background: var(--card-color, #1e2130);
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.07);
            padding: 20px 22px;
            height: 100%;
        }

        .p-card-title {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            margin: 0 0 16px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .p-badge {
            font-size: 11px;
            background: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.5);
            padding: 2px 9px;
            border-radius: 20px;
        }

        /* stat cards */
        .p-stat {
            background: var(--card-color, #1e2130);
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.07);
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .p-ico {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .p-ico.streak {
            background: rgba(255, 165, 0, .13);
            color: #FFA500;
        }

        .p-ico.time {
            background: rgba(92, 124, 250, .13);
            color: #5C7CFA;
        }

        .p-ico.xp {
            background: rgba(0, 184, 148, .13);
            color: #00B894;
        }

        .p-ico.tasks {
            background: rgba(255, 118, 117, .13);
            color: #FF7675;
        }

        .p-stat-val {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
        }

        .p-stat-lbl {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.45);
            margin-top: 2px;
        }

        /* xp bar */
        .xp-labels {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 6px;
        }

        .xp-bg {
            height: 10px;
            background: rgba(255, 255, 255, 0.07);
            border-radius: 99px;
            overflow: hidden;
        }

        .xp-fill {
            height: 100%;
            background: linear-gradient(90deg, #00B894, #00CEC9);
            border-radius: 99px;
            transition: width 1.2s cubic-bezier(.25, .8, .25, 1);
        }

        /* achievements */
        .ach-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(255, 255, 255, 0.03);
            margin-bottom: 8px;
        }

        .ach-item.unlocked {
            background: rgba(0, 184, 148, .07);
            border-color: rgba(0, 184, 148, .2);
        }

        .ach-item.locked {
            opacity: .45;
        }

        .ach-ico {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .ach-ico.success {
            background: rgba(0, 184, 148, .18);
            color: #00B894;
        }

        .ach-ico.info {
            background: rgba(92, 124, 250, .18);
            color: #5C7CFA;
        }

        .ach-ico.warning {
            background: rgba(255, 165, 0, .18);
            color: #FFA500;
        }

        .ach-ico.danger {
            background: rgba(255, 118, 117, .18);
            color: #FF7675;
        }

        .ach-name {
            font-size: 12px;
            font-weight: 600;
            color: #fff;
        }

        .ach-desc {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 1px;
        }

        .ach-mini {
            height: 3px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 99px;
            overflow: hidden;
            margin-top: 5px;
        }

        .ach-mini-fill {
            height: 100%;
            background: linear-gradient(90deg, #5C7CFA, #00CEC9);
        }

        /* activity feed */
        .feed-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 8px;
        }

        .feed-ico {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
        }

        .feed-ico.info {
            background: rgba(92, 124, 250, .13);
            color: #5C7CFA;
        }

        .feed-ico.success {
            background: rgba(0, 184, 148, .13);
            color: #00B894;
        }

        .feed-ico.warning {
            background: rgba(255, 165, 0, .13);
            color: #FFA500;
        }

        .feed-title {
            font-size: 12px;
            font-weight: 500;
            color: #fff;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 200px;
        }

        .feed-sub {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.38);
        }

        .feed-time {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.3);
            margin-left: auto;
            white-space: nowrap;
        }

        /* stickers */
        .sticker-chip {
            text-align: center;
        }

        .sticker-chip img {
            width: 56px;
            height: 56px;
            object-fit: contain;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.06);
            padding: 6px;
            transition: transform .2s;
        }

        .sticker-chip img:hover {
            transform: scale(1.12);
        }

        .sticker-chip span {
            display: block;
            font-size: 10px;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 4px;
        }

        .badge-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 8px 12px;
            margin: 0 8px 8px 0;
        }

        .badge-chip img {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }

        .badge-chip span {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
        }

        .mini-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
        }

        .mini-val {
            font-size: 22px;
            font-weight: 700;
            text-align: center;
        }

        .mini-lbl {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.4);
            text-align: center;
            margin-top: 1px;
        }

        .empty-st {
            text-align: center;
            padding: 36px 20px;
            color: rgba(255, 255, 255, 0.3);
            font-size: 12px;
        }

        .empty-st i {
            font-size: 28px;
            display: block;
            margin-bottom: 10px;
            opacity: .5;
        }

        canvas {
            max-height: 220px;
        }

        /* ── LIGHT MODE: progress page overrides ── */
        body.light-mode {
            background: #f0f4ff !important;
            color: #1a1d2e !important;
        }

        body.light-mode .p-card {
            background: #ffffff !important;
            border-color: rgba(0, 0, 0, 0.07) !important;
            color: #1a1d2e !important;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        body.light-mode .p-card-title {
            color: #1a1d2e !important;
        }

        body.light-mode .p-badge {
            background: rgba(0, 0, 0, 0.06) !important;
            color: #6b7280 !important;
        }

        body.light-mode .p-stat {
            background: #ffffff !important;
            border-color: rgba(0, 0, 0, 0.07) !important;
        }

        body.light-mode .p-stat-val {
            color: #1a1d2e !important;
        }

        body.light-mode .p-stat-lbl {
            color: #6b7280 !important;
        }

        body.light-mode .xp-labels {
            color: #6b7280 !important;
        }

        body.light-mode .xp-bg {
            background: rgba(0, 0, 0, 0.08) !important;
        }

        body.light-mode .ach-item {
            background: rgba(0, 0, 0, 0.02) !important;
            border-color: rgba(0, 0, 0, 0.07) !important;
        }

        body.light-mode .ach-item.unlocked {
            background: rgba(0, 184, 148, .07) !important;
            border-color: rgba(0, 184, 148, .2) !important;
        }

        body.light-mode .ach-name {
            color: #1a1d2e !important;
        }

        body.light-mode .ach-desc {
            color: #6b7280 !important;
        }

        body.light-mode .ach-mini {
            background: rgba(0, 0, 0, 0.08) !important;
        }

        body.light-mode .feed-item {
            background: rgba(0, 0, 0, 0.02) !important;
            border-color: rgba(0, 0, 0, 0.06) !important;
        }

        body.light-mode .feed-title {
            color: #1a1d2e !important;
        }

        body.light-mode .feed-sub {
            color: #6b7280 !important;
        }

        body.light-mode .feed-time {
            color: #9ca3af !important;
        }

        body.light-mode .sticker-chip img {
            background: rgba(0, 0, 0, 0.05) !important;
        }

        body.light-mode .sticker-chip span {
            color: #6b7280 !important;
        }

        body.light-mode .badge-chip {
            background: rgba(0, 0, 0, 0.03) !important;
            border-color: rgba(0, 0, 0, 0.08) !important;
        }

        body.light-mode .badge-chip span {
            color: #1a1d2e !important;
        }

        body.light-mode .mini-stats {
            border-top-color: rgba(0, 0, 0, 0.07) !important;
        }

        body.light-mode .mini-lbl {
            color: #6b7280 !important;
        }

        body.light-mode .empty-st {
            color: #9ca3af !important;
        }

        body.light-mode #global-loader {
            background: #f0f4ff !important;
        }
    </style>
</head>

<body>
    <div id="global-loader"
        style="position:fixed;top:0;left:0;width:100%;height:100%;background:#1a1b21;z-index:9999;display:flex;align-items:center;justify-content:center;">
        <div class="spinner-border" style="width:3rem;height:3rem;color:#5C7CFA;" role="status"></div>
    </div>

    @auth
        <div class="dashboard-container">

            <!-- ── Sidebar ── -->
            <nav class="sidebar">
                <div class="logo-details">
                    <img src="{{ asset('images/mantra.png') }}" alt="Mantra Logo"
                        style="width:36px; height:36px; object-fit:contain; border-radius:8px; flex-shrink:0;">
                    <div class="logo_name">MANTRA</div>
                    <i class="fa fa-bars" id="btn"></i>
                </div>
                <ul class="nav-list">
                    <li>
                        <a href="{{ url('/') }}"><i class="fa fa-home"></i><span class="links_name">Home</span></a>
                        <span class="tooltip">Home</span>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}"><i class="fa fa-th-large"></i><span
                                class="links_name">Dashboard</span></a>
                        <span class="tooltip">Dashboard</span>
                    </li>
                    <li>
                        <a href="{{ route('library') }}"><i class="fa fa-folder-open"></i><span
                                class="links_name">Library</span></a>
                        <span class="tooltip">Library</span>
                    </li>
                    <li>
                        <a href="{{ route('study') }}"><i class="fa fa-check-square"></i><span class="links_name">Study
                                Space</span></a>
                        <span class="tooltip">Study</span>
                    </li>
                    <li>
                        <a href="{{ route('progress') }}" class="active"><i class="fa fa-pie-chart"></i><span
                                class="links_name">Progress</span></a>
                        <span class="tooltip">Progress</span>
                    </li>
                    <li>
                        <a href="{{ route('settings') }}"><i class="fa fa-cog"></i><span
                                class="links_name">Settings</span></a>
                        <span class="tooltip">Settings</span>
                    </li>
                    <li class="profile">
                        <div class="profile-details">
                            <div class="profile-icon-wrap"
                                style="width:36px;height:36px;background:rgba(92,124,250,0.18);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fa fa-user"
                                    style="font-size:16px;min-width:unset;height:unset;line-height:unset;color:#5C7CFA;"></i>
                            </div>
                            <div class="name_job">
                                <div class="name">{{ $user->name }}</div>
                                <div class="job">{{ $user->title }}</div>
                            </div>
                        </div>
                        <a href="#" id="logout-btn"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            title="Sign Out">
                            <i class="fa fa-sign-out" style="font-size:20px;"></i>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf
                        </form>
                    </li>
                </ul>
            </nav>

            <!-- ── Main ── -->
            <section class="home-section">
                <div class="top-bar">
                    <div class="text">📊 My Progress</div>
                </div>

                <div class="main-content">

                    <!-- ── Row 1: Stat Cards ── -->
                    @php
                        $studySec = $user->total_study_seconds ?? 0;
                        $studyH = floor($studySec / 3600);
                        $studyM = floor(($studySec % 3600) / 60);
                        $userXp = $user->xp ?? 0;
                        $userStreak = $user->current_streak ?? 0;
                        $timeLabel = ($studySec > 0) ? "{$studyH}h {$studyM}m" : '0h 0m';
                    @endphp
                    <div class="row mb-4">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="p-stat">
                                <div class="p-ico streak"><i class="fa fa-fire"></i></div>
                                <div>
                                    <div class="p-stat-val" style="color:#FFA500;font-size:22px;font-weight:700;">
                                        {{ $userStreak }}
                                    </div>
                                    <div class="p-stat-lbl">Day Streak 🔥</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="p-stat">
                                <div class="p-ico time"><i class="fa fa-clock-o"></i></div>
                                <div>
                                    <div class="p-stat-val" style="color:#5C7CFA;font-size:22px;font-weight:700;">
                                        {{ $timeLabel }}
                                    </div>
                                    <div class="p-stat-lbl">Total Study Time</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="p-stat">
                                <div class="p-ico xp"><i class="fa fa-trophy"></i></div>
                                <div>
                                    <div class="p-stat-val" style="color:#00B894;font-size:22px;font-weight:700;">
                                        {{ $userXp }} XP
                                    </div>
                                    <div class="p-stat-lbl">Level {{ $level }} · {{ $user->title }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="p-stat">
                                <div class="p-ico tasks"><i class="fa fa-check-square"></i></div>
                                <div>
                                    <div class="p-stat-val" style="color:#FF7675;font-size:22px;font-weight:700;">
                                        {{ $totalCompletedTasks }} / {{ $totalTodosCount }}
                                    </div>
                                    <div class="p-stat-lbl">Tasks Completed</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── Row 2: XP Level Bar ── -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="p-card">
                                <div
                                    style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                                    <span style="font-size:13px;font-weight:600;color:#fff;">
                                        Level {{ $level }} — {{ $user->title }}
                                    </span>
                                    <span style="font-size:12px;font-weight:700;color:#00B894;">{{ $xpPercent }}%</span>
                                </div>
                                <div class="xp-labels">
                                    <span>{{ $xpInLevel }} XP earned this level</span>
                                    <span>{{ $xpNeeded - $xpInLevel }} XP to Level {{ $level + 1 }}</span>
                                </div>
                                <div class="xp-bg">
                                    <div class="xp-fill" id="xp-fill" style="width:0;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── Row 3: Chart + Achievements ── -->
                    <div class="row mb-4">
                        <div class="col-md-8 mb-3">
                            <div class="p-card">
                                <div class="p-card-title">
                                    📊 Weekly Activity
                                    <span class="p-badge">Real actions (notes + tasks)</span>
                                </div>
                                @if($hasActivity)
                                    <canvas id="weeklyChart"></canvas>
                                @else
                                    <div class="empty-st">
                                        <i class="fa fa-bar-chart"></i>
                                        No activity this week yet. Create notes or tasks to see your chart!
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-card">
                                <div class="p-card-title">
                                    🏆 Achievements
                                    <span
                                        class="p-badge">{{ collect($achievements)->where('unlocked', true)->count() }}/{{ count($achievements) }}</span>
                                </div>
                                <div style="max-height:340px;overflow-y:auto;padding-right:4px;">
                                    @foreach($achievements as $a)
                                        @php
                                            $ri = $a['reward'] ?? null;
                                            $rp = $ri ? ($ri['type'] === 'sticker' ? 'stickers/' . $ri['asset'] . '.png' : 'rewards/' . $ri['asset'] . '.png') : null;
                                        @endphp
                                        <div class="ach-item {{ $a['unlocked'] ? 'unlocked' : 'locked' }}">
                                            @if($rp)
                                                <div style="flex-shrink:0;width:44px;height:44px;position:relative;">
                                                    <img src="{{ asset($rp) }}" alt="{{ $ri['label'] }}"
                                                        style="width:44px;height:44px;object-fit:contain;border-radius:10px;padding:4px;
                                                                                                                                    background:{{ $a['unlocked'] ? 'rgba(0,184,148,0.12)' : 'rgba(255,255,255,0.04)' }};
                                                                                                                                    {{ $a['unlocked'] ? 'box-shadow:0 0 0 2px #00B894;' : 'filter:grayscale(1) opacity(0.25);' }}"
                                                        onerror="this.parentElement.style.display='none'">
                                                    <div
                                                        style="position:absolute;bottom:-4px;right:-4px;width:16px;height:16px;border-radius:50%;
                                                                                                                                    background:{{ $a['unlocked'] ? '#00B894' : 'rgba(255,255,255,0.15)' }};
                                                                                                                                    display:flex;align-items:center;justify-content:center;">
                                                        <i class="fa {{ $a['unlocked'] ? 'fa-check' : 'fa-lock' }}"
                                                            style="font-size:8px;color:{{ $a['unlocked'] ? '#fff' : 'rgba(255,255,255,0.5)' }};"></i>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="ach-ico {{ $a['color'] }}">
                                                    <i class="fa {{ $a['unlocked'] ? $a['icon'] : 'fa-lock' }}"></i>
                                                </div>
                                            @endif
                                            <div style="flex:1;min-width:0;">
                                                <div class="ach-name">{{ $a['name'] }}</div>
                                                <div class="ach-desc">{{ $a['desc'] }}</div>
                                                @if($a['unlocked'] && $ri)
                                                    <div style="font-size:9px;color:#00B894;margin-top:3px;">🎁
                                                        {{ ucfirst($ri['type']) }}: {{ $ri['label'] }}
                                                    </div>
                                                @elseif(!$a['unlocked'] && isset($a['progress']))
                                                    <div class="ach-mini">
                                                        <div class="ach-mini-fill" style="width:{{ $a['progress'] }}%;"></div>
                                                    </div>
                                                    @if($ri)
                                                        <div style="font-size:9px;color:rgba(255,255,255,0.28);margin-top:2px;">🔒
                                                    Unlock to earn: {{ $ri['label'] }}</div>@endif
                                                @elseif(!$a['unlocked'] && $ri)
                                                    <div style="font-size:9px;color:rgba(255,255,255,0.28);margin-top:2px;">🔒
                                                        Unlock to earn: {{ $ri['label'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── Row 4: Activity Feed + Rewards ── -->
                    <div class="row mb-4">
                        <div class="col-md-7 mb-3">
                            <div class="p-card">
                                <div class="p-card-title">📖 Recent Activity</div>
                                @if($recentActivity->isNotEmpty())
                                    @foreach($recentActivity as $item)
                                        <div class="feed-item">
                                            <div class="feed-ico {{ $item['color'] }}">
                                                <i class="fa {{ $item['icon'] }}"></i>
                                            </div>
                                            <div style="flex:1;min-width:0;">
                                                <div class="feed-title">{{ $item['title'] }}</div>
                                                @if($item['sub'])
                                                    <div class="feed-sub">{{ $item['sub'] }}</div>
                                                @endif
                                            </div>
                                            <div class="feed-time">{{ \Carbon\Carbon::parse($item['time'])->diffForHumans() }}</div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-st">
                                        <i class="fa fa-history"></i>
                                        No activity yet — start creating notes and tasks!
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-5 mb-3">
                            <div class="p-card">
                                <div class="p-card-title">
                                    🎖️ Rewards Earned
                                    <span class="p-badge">{{ count($stickerAssets) + count($badgeAssets) }}</span>
                                </div>

                                @if(empty($stickerAssets) && empty($badgeAssets))
                                    <div class="empty-st">
                                        <i class="fa fa-star-o"></i>
                                        Complete study sessions to earn stickers and badges!
                                    </div>
                                @else

                                    @if(!empty($stickerAssets))
                                        <p
                                            style="font-size:10px;text-transform:uppercase;letter-spacing:.6px;color:rgba(255,255,255,0.35);margin-bottom:10px;">
                                            Stickers</p>
                                        <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:16px;">
                                            @foreach($stickerAssets as $s)
                                                <div class="sticker-chip">
                                                    <img src="{{ asset($s['path']) }}" alt="{{ $s['label'] }}"
                                                        onerror="this.parentElement.style.display='none'">
                                                    <span>{{ $s['label'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if(!empty($badgeAssets))
                                        <p
                                            style="font-size:10px;text-transform:uppercase;letter-spacing:.6px;color:rgba(255,255,255,0.35);margin-bottom:10px;">
                                            Badges</p>
                                        <div style="display:flex;flex-wrap:wrap;">
                                            @foreach($badgeAssets as $b)
                                                <div class="badge-chip">
                                                    <img src="{{ asset($b['path']) }}" alt="{{ $b['label'] }}"
                                                        onerror="this.style.display='none'">
                                                    <span>{{ $b['label'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                @endif

                                <!-- Real summary mini stats -->
                                <div class="mini-stats">
                                    <div>
                                        <div class="mini-val" style="color:#00B894;font-size:24px;font-weight:700;">
                                            {{ $userStreak }}
                                        </div>
                                        <div class="mini-lbl">Streak Days</div>
                                    </div>
                                    <div>
                                        <div class="mini-val" style="color:#5C7CFA;font-size:24px;font-weight:700;">
                                            {{ $userXp }}
                                        </div>
                                        <div class="mini-lbl">Total XP</div>
                                    </div>
                                    <div>
                                        <div class="mini-val" style="color:#FFA500;font-size:24px;font-weight:700;">
                                            {{ $totalNotesCount }}
                                        </div>
                                        <div class="mini-lbl">Notes Created</div>
                                    </div>
                                    <div>
                                        <div class="mini-val" style="color:#FF7675;font-size:24px;font-weight:700;">
                                            {{ $totalCompletedTasks }}
                                        </div>
                                        <div class="mini-lbl">Tasks Done</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- /main-content -->
            </section>
        </div>
    @else
        <script>window.location.href = "{{ route('login') }}";</script>
    @endauth

    <!-- Scripts -->
    <script src="{{ asset('jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/music-player.js') }}?v={{ time() }}"></script>
    <script>
        $(document).ready(function () {
            // Loader
            const loader = document.getElementById('global-loader');
            if (loader) {
                setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => loader.style.display = 'none', 500);
                }, 600);
            }

            // Sidebar toggle
            $('#btn').click(() => $('.sidebar').toggleClass('open'));

            // XP bar animate in
            setTimeout(() => {
                const f = document.getElementById('xp-fill');
                if (f) f.style.width = '{{ $xpPercent }}%';
            }, 800);

            // Chart.js globals
            Chart.defaults.color = 'rgba(255,255,255,0.45)';
            Chart.defaults.font.family = 'Inter, sans-serif';
            Chart.defaults.font.size = 11;
            const grid = 'rgba(255,255,255,0.06)';

            // Weekly Activity — real action counts
            @if($hasActivity)
                const wCtx = document.getElementById('weeklyChart').getContext('2d');
                const wGrad = wCtx.createLinearGradient(0, 0, 0, 200);
                wGrad.addColorStop(0, 'rgba(92,124,250,0.9)');
                wGrad.addColorStop(1, 'rgba(0,206,201,0.3)');
                new Chart(wCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($weeklyLabels) !!},
                        datasets: [{
                            label: 'Actions',
                            data: {!! json_encode($weeklyData) !!},
                            backgroundColor: wGrad,
                            borderRadius: 7,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { color: grid }, border: { color: 'transparent' } },
                            y: {
                                grid: { color: grid }, border: { color: 'transparent' }, beginAtZero: true,
                                ticks: { stepSize: 1, callback: v => Number.isInteger(v) ? v : '' }
                            }
                        }
                    }
                });
            @endif
});
    </script>

    <!-- Lo-Fi Widget -->
    <div class="music-widget closed">
        <div class="music-controls">
            <div class="music-art"><img src="{{ asset('images/mantra.png') }}" alt="Art"></div>
            <div class="track-info">
                <span class="track-name">Chill Study Beats ☕</span>
                <span class="artist-name">Mantra Radio</span>
                <div class="progress-bar-music">
                    <div class="fill"></div>
                    <div class="seek-thumb"></div>
                </div>
            </div>
            <div class="control-btns">
                <button class="m-btn"><i class="fa fa-step-backward"></i></button>
                <button class="m-btn play-pause"><i class="fa fa-play"></i></button>
                <button class="m-btn"><i class="fa fa-step-forward"></i></button>
            </div>
        </div>
        <div class="music-toggle" title="Toggle Lo-Fi Radio">
            <i class="fa fa-music"></i>
            <div class="equalizer"><span></span><span></span><span></span></div>
        </div>
    </div>

    <script>
        (function () {
            if (localStorage.getItem('mantra_pref_dark') === '0') document.body.classList.add('light-mode');
            if (localStorage.getItem('mantra_pref_compact') === '1') {
                var sb = document.querySelector('.sidebar');
                if (sb) sb.classList.add('compact');
            }
        })();
    </script>
</body>

</html>