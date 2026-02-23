<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Mantra Settings">
    <meta name="author" content="Mantra">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Mantra | Settings</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v=5">

    <style>
        /* ── Settings Page Overrides ── */
        body {
            font-family: 'Inter', sans-serif;
        }

        .settings-wrapper {
            max-width: 860px;
            margin: 0 auto;
            padding: 0 20px 40px;
        }

        /* ── Page Header ── */
        .settings-page-header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 28px 0 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
            margin-bottom: 28px;
        }

        .settings-page-header .header-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            border: 3px solid rgba(92, 124, 250, 0.5);
            object-fit: cover;
            flex-shrink: 0;
        }

        .settings-page-header .header-info h2 {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            margin: 0 0 3px;
        }

        .settings-page-header .header-info p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.45);
            margin: 0;
        }

        .settings-page-header .header-badge {
            margin-left: auto;
            background: rgba(92, 124, 250, 0.15);
            border: 1px solid rgba(92, 124, 250, 0.3);
            color: #5C7CFA;
            font-size: 11px;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 20px;
        }

        /* ── Tab Nav ── */
        .settings-tab-nav {
            display: flex;
            gap: 4px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 5px;
            margin-bottom: 28px;
        }

        .settings-tab-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 9px;
            border: none;
            background: transparent;
            color: rgba(255, 255, 255, 0.45);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }

        .settings-tab-btn:hover {
            color: rgba(255, 255, 255, 0.75);
            background: rgba(255, 255, 255, 0.06);
        }

        .settings-tab-btn.active {
            background: rgba(92, 124, 250, 0.18);
            color: #5C7CFA;
            border: 1px solid rgba(92, 124, 250, 0.3);
        }

        .settings-tab-btn i {
            font-size: 14px;
        }

        /* ── Tab Panel ── */
        .settings-tab-panel {
            display: none;
        }

        .settings-tab-panel.active {
            display: block;
        }

        /* ── Section Card ── */
        .s-card {
            background: rgba(255, 255, 255, 0.035);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 24px 26px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .s-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #5C7CFA, #00CEC9);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .s-card:hover::before {
            opacity: 1;
        }

        .s-card-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: rgba(255, 255, 255, 0.5);
            margin: 0 0 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .s-card-title i {
            font-size: 14px;
            color: #5C7CFA;
        }

        /* ── Form Fields ── */
        .s-form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .s-form-row.single {
            grid-template-columns: 1fr;
        }

        .s-field {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .s-field label {
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.55);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .s-field input {
            width: 100%;
            padding: 11px 14px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
            box-sizing: border-box;
        }

        .s-field input:focus {
            border-color: rgba(92, 124, 250, 0.6);
            background: rgba(92, 124, 250, 0.06);
        }

        .s-field input::placeholder {
            color: rgba(255, 255, 255, 0.25);
        }

        .s-field .field-hint {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.3);
            margin-top: 2px;
        }

        /* ── Toggle Items ── */
        .s-toggle-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .s-toggle-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .s-toggle-item:first-child {
            padding-top: 0;
        }

        .s-toggle-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            margin: 0 0 3px;
        }

        .s-toggle-info p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.38);
            margin: 0;
        }

        /* iOS-style Toggle */
        .ios-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            flex-shrink: 0;
        }

        .ios-switch input {
            display: none;
        }

        .ios-switch .track {
            position: absolute;
            inset: 0;
            border-radius: 99px;
            background: rgba(255, 255, 255, 0.12);
            transition: background 0.2s;
            cursor: pointer;
        }

        .ios-switch input:checked~.track {
            background: #5C7CFA;
        }

        .ios-switch .thumb {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #fff;
            transition: transform 0.2s;
            pointer-events: none;
        }

        .ios-switch input:checked~.thumb {
            transform: translateX(20px);
        }

        /* ── Danger Zone ── */
        .s-danger-card {
            background: rgba(229, 57, 53, 0.06);
            border: 1px solid rgba(229, 57, 53, 0.2);
            border-radius: 16px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .s-danger-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: #E53935;
            margin: 0 0 4px;
        }

        .s-danger-info p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.38);
            margin: 0;
        }

        /* ── Buttons ── */
        .s-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border-radius: 10px;
            border: none;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .s-btn-primary {
            background: linear-gradient(135deg, #5C7CFA, #7c5cfa);
            color: #fff;
        }

        .s-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(92, 124, 250, 0.35);
        }

        .s-btn-outline {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: rgba(255, 255, 255, 0.7);
        }

        .s-btn-outline:hover {
            background: rgba(255, 255, 255, 0.07);
            color: #fff;
        }

        .s-btn-danger {
            background: rgba(229, 57, 53, 0.15);
            border: 1px solid rgba(229, 57, 53, 0.35);
            color: #E53935;
        }

        .s-btn-danger:hover {
            background: rgba(229, 57, 53, 0.25);
            transform: translateY(-1px);
        }

        /* ── Form Action Row ── */
        .s-form-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 22px;
        }

        /* ── Avatar Section ── */
        .s-avatar-section {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        .s-avatar-wrap {
            position: relative;
            width: 72px;
            height: 72px;
            flex-shrink: 0;
        }

        .s-avatar-wrap img {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            border: 3px solid rgba(92, 124, 250, 0.4);
            object-fit: cover;
        }

        .s-avatar-hint h4 {
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            margin: 0 0 4px;
        }

        .s-avatar-hint p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.35);
            margin: 0;
        }

        /* ── Alert ── */
        .s-alert-success {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 18px;
            background: rgba(0, 184, 148, 0.12);
            border: 1px solid rgba(0, 184, 148, 0.3);
            border-radius: 10px;
            color: #00B894;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
        }

        /* ── Info Row ── */
        .s-info-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .s-info-row:last-child {
            border-bottom: none;
        }

        .s-info-label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 500;
        }

        .s-info-value {
            font-size: 13px;
            color: #fff;
            font-weight: 500;
        }

        .s-info-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .s-info-badge.green {
            background: rgba(0, 184, 148, 0.15);
            color: #00B894;
        }

        .s-info-badge.blue {
            background: rgba(92, 124, 250, 0.15);
            color: #5C7CFA;
        }
    </style>
</head>

<body>
    <!-- Global Loader -->
    <div id="global-loader"
        style="position:fixed;top:0;left:0;width:100%;height:100%;background:#1a1b21;z-index:9999;display:flex;align-items:center;justify-content:center;">
        <div class="spinner-border" style="width:3rem;height:3rem;color:#5C7CFA;" role="status"></div>
    </div>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="logo-details">
                <img src="{{ asset('images/mantra.png') }}" alt="Mantra Logo"
                    style="width:36px;height:36px;object-fit:contain;border-radius:8px;flex-shrink:0;">
                <div class="logo_name">MANTRA</div>
                <i class="fa fa-bars" id="btn"></i>
            </div>
            <ul class="nav-list">
                <li>
                    <a href="{{ url('/') }}">
                        <i class="fa fa-home"></i>
                        <span class="links_name">Home</span>
                    </a>
                    <span class="tooltip">Home</span>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-th-large"></i>
                        <span class="links_name">Dashboard</span>
                    </a>
                    <span class="tooltip">Dashboard</span>
                </li>
                <li>
                    <a href="{{ route('library') }}">
                        <i class="fa fa-folder-open"></i>
                        <span class="links_name">Library</span>
                    </a>
                    <span class="tooltip">Library</span>
                </li>
                <li>
                    <a href="{{ route('study') }}">
                        <i class="fa fa-check-square"></i>
                        <span class="links_name">Study Space</span>
                    </a>
                    <span class="tooltip">Study</span>
                </li>
                <li>
                    <a href="{{ route('progress') }}">
                        <i class="fa fa-pie-chart"></i>
                        <span class="links_name">Progress</span>
                    </a>
                    <span class="tooltip">Progress</span>
                </li>
                <li>
                    <a href="{{ route('settings') }}" class="active">
                        <i class="fa fa-cog"></i>
                        <span class="links_name">Settings</span>
                    </a>
                    <span class="tooltip">Settings</span>
                </li>
                <li class="profile">
                    <div class="profile-details">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff"
                            alt="profileImg">
                        <div class="name_job">
                            <div class="name" id="user-name">{{ $user->name }}</div>
                            <div class="job">Learner</div>
                        </div>
                    </div>
                    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf</form>
                    <i class="fa fa-sign-out" id="logout-btn"
                        onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();"
                        style="cursor:pointer;"></i>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <section class="home-section">
            <div class="top-bar">
                <div class="text">⚙️ Settings</div>
            </div>

            <div class="main-content" style="padding: 0 28px 40px;">
                <div class="settings-wrapper">

                    <!-- Page Header -->
                    <div class="settings-page-header">
                        <img class="header-avatar"
                            src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=5C7CFA&color=fff&size=128"
                            alt="Avatar">
                        <div class="header-info">
                            <h2>{{ $user->name }}</h2>
                            <p>{{ $user->email }} &nbsp;·&nbsp; Mantra Learner</p>
                        </div>
                        <span class="header-badge">⚡ Active</span>
                    </div>

                    @if(session('success'))
                        <div class="s-alert-success">
                            <i class="fa fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Tab Navigation -->
                    <div class="settings-tab-nav">
                        <button class="settings-tab-btn active" data-tab="profile">
                            <i class="fa fa-user"></i> Profile
                        </button>
                        <button class="settings-tab-btn" data-tab="security">
                            <i class="fa fa-lock"></i> Security
                        </button>
                        <button class="settings-tab-btn" data-tab="preferences">
                            <i class="fa fa-sliders"></i> Preferences
                        </button>
                        <button class="settings-tab-btn" data-tab="account">
                            <i class="fa fa-shield"></i> Account
                        </button>
                    </div>

                    <!-- ── TAB: PROFILE ── -->
                    <div class="settings-tab-panel active" id="tab-profile">
                        <div class="s-card">
                            <p class="s-card-title"><i class="fa fa-user-circle"></i> Profile Information</p>

                            <div class="s-avatar-section">
                                <div class="s-avatar-wrap">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=5C7CFA&color=fff&size=128"
                                        alt="Avatar">
                                </div>
                                <div class="s-avatar-hint">
                                    <h4>Profile Photo</h4>
                                    <p>Generated automatically from your name</p>
                                </div>
                            </div>

                            <form action="{{ route('settings.profile') }}" method="POST">
                                @csrf
                                <input type="hidden" name="_tab" value="profile">
                                <div class="s-form-row">
                                    <div class="s-field">
                                        <label>Display Name</label>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                            placeholder="Your name">
                                        @error('name')
                                            <span class="field-hint" style="color:#E53935;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="s-field">
                                        <label>Email Address</label>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                            placeholder="your@email.com">
                                        @error('email')
                                            <span class="field-hint" style="color:#E53935;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="s-form-actions">
                                    <button type="submit" class="s-btn s-btn-primary">
                                        <i class="fa fa-save"></i> Save Changes
                                    </button>
                                    <a href="{{ route('settings') }}" class="s-btn s-btn-outline">Discard</a>
                                </div>
                            </form>
                        </div>

                        <!-- Account Info -->
                        <div class="s-card">
                            <p class="s-card-title"><i class="fa fa-info-circle"></i> Account Overview</p>
                            <div class="s-info-row">
                                <span class="s-info-label">Member Since</span>
                                <span class="s-info-value">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="s-info-row">
                                <span class="s-info-label">Account Status</span>
                                <span class="s-info-badge green"><i class="fa fa-circle" style="font-size:7px;"></i>
                                    Active</span>
                            </div>
                            <div class="s-info-row">
                                <span class="s-info-label">Current Level</span>
                                <span class="s-info-badge blue">Level {{ $user->level }} · {{ $user->title }}</span>
                            </div>
                            <div class="s-info-row">
                                <span class="s-info-label">Total XP</span>
                                <span class="s-info-value">{{ $user->xp ?? 0 }} XP</span>
                            </div>
                        </div>
                    </div>

                    <!-- ── TAB: SECURITY ── -->
                    <div class="settings-tab-panel" id="tab-security">
                        <div class="s-card">
                            <p class="s-card-title"><i class="fa fa-key"></i> Change Password</p>
                            <form action="{{ route('settings.password') }}" method="POST" id="password-form">
                                @csrf
                                <input type="hidden" name="_tab" value="security">
                                <div class="s-form-row single">
                                    <div class="s-field">
                                        <label>Current Password</label>
                                        <div style="position:relative;">
                                            <input type="password" id="pw-current" name="current_password"
                                                placeholder="Enter current password" autocomplete="current-password"
                                                style="padding-right:42px;">
                                            <button type="button" class="pw-eye" data-target="pw-current">
                                                <i class="fa fa-eye"></i></button>
                                        </div>
                                        @error('current_password')
                                            <span class="field-hint" style="color:#E53935;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="s-form-row" style="margin-top:14px;">
                                    <div class="s-field">
                                        <label>New Password</label>
                                        <div style="position:relative;">
                                            <input type="password" id="pw-new" name="password"
                                                placeholder="Min. 8 characters" autocomplete="new-password"
                                                style="padding-right:42px;">
                                            <button type="button" class="pw-eye" data-target="pw-new">
                                                <i class="fa fa-eye"></i></button>
                                        </div>
                                        @error('password')
                                            <span class="field-hint" style="color:#E53935;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="s-field">
                                        <label>Confirm New Password</label>
                                        <div style="position:relative;">
                                            <input type="password" id="pw-confirm" name="password_confirmation"
                                                placeholder="Repeat new password" autocomplete="new-password"
                                                style="padding-right:42px;">
                                            <button type="button" class="pw-eye" data-target="pw-confirm">
                                                <i class="fa fa-eye"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="s-form-actions">
                                    <button type="submit" class="s-btn s-btn-primary">
                                        <i class="fa fa-lock"></i> Update Password
                                    </button>
                                    <a href="{{ route('password.request') }}" class="s-btn s-btn-outline"
                                        style="font-size:12px;text-decoration:none;">
                                        <i class="fa fa-question-circle"></i> Forgot current password?
                                    </a>
                                </div>
                            </form>
                        </div>

                        <div class="s-card">
                            <p class="s-card-title"><i class="fa fa-shield"></i> Session</p>
                            <div class="s-info-row">
                                <span class="s-info-label">Current Session</span>
                                <span class="s-info-badge green"><i class="fa fa-circle" style="font-size:7px;"></i>
                                    Active</span>
                            </div>
                            <div class="s-info-row">
                                <span class="s-info-label">Last Login</span>
                                <span class="s-info-value">{{ $user->updated_at->format('M d, Y · H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- ── TAB: PREFERENCES ── -->
                    <div class="settings-tab-panel" id="tab-preferences">
                        <div class="s-card">
                            <p class="s-card-title"><i class="fa fa-paint-brush"></i> Appearance</p>
                            <div class="s-toggle-item">
                                <div class="s-toggle-info">
                                    <h4>Dark Mode</h4>
                                    <p>Dark background for comfortable late-night studying.</p>
                                </div>
                                <label class="ios-switch">
                                    <input type="checkbox" checked id="pref-dark">
                                    <span class="track"></span>
                                    <span class="thumb"></span>
                                </label>
                            </div>
                            <div class="s-toggle-item">
                                <div class="s-toggle-info">
                                    <h4>Compact Sidebar</h4>
                                    <p>Show only icons in the sidebar by default.</p>
                                </div>
                                <label class="ios-switch">
                                    <input type="checkbox" id="pref-compact">
                                    <span class="track"></span>
                                    <span class="thumb"></span>
                                </label>
                            </div>
                        </div>

                        <div class="s-card">
                            <p class="s-card-title"><i class="fa fa-bell"></i> Notifications</p>
                            <div class="s-toggle-item">
                                <div class="s-toggle-info">
                                    <h4>Study Reminders</h4>
                                    <p>Get reminders to keep your streak alive.</p>
                                </div>
                                <label class="ios-switch">
                                    <input type="checkbox" checked id="pref-reminders">
                                    <span class="track"></span>
                                    <span class="thumb"></span>
                                </label>
                            </div>
                            <div class="s-toggle-item">
                                <div class="s-toggle-info">
                                    <h4>XP & Achievement Alerts</h4>
                                    <p>Celebrate when you level up or unlock badges.</p>
                                </div>
                                <label class="ios-switch">
                                    <input type="checkbox" checked id="pref-xp">
                                    <span class="track"></span>
                                    <span class="thumb"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- ── TAB: ACCOUNT ── -->
                    <div class="settings-tab-panel" id="tab-account">
                        <div class="s-card">
                            <p class="s-card-title"><i class="fa fa-sign-out"></i> Session Management</p>
                            <div
                                style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:14px;">
                                <div class="s-toggle-info">
                                    <h4>Sign Out</h4>
                                    <p>End your current session and return to login.</p>
                                </div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="s-btn s-btn-outline">
                                        <i class="fa fa-sign-out"></i> Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="s-danger-card">
                            <div class="s-danger-info">
                                <h4><i class="fa fa-exclamation-triangle"></i> Danger Zone</h4>
                                <p>Permanently delete your account and all associated data. This action cannot be
                                    undone.</p>
                            </div>
                            <button class="s-btn s-btn-danger" id="open-delete-modal">
                                <i class="fa fa-trash"></i> Delete Account
                            </button>
                        </div>
                    </div>

                    <!-- ── DELETE ACCOUNT MODAL ── -->
                    <div id="delete-modal"
                        style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
                        <div
                            style="background:#1e1f28;border:1px solid rgba(229,57,53,0.3);border-radius:18px;padding:32px;max-width:420px;width:90%;">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
                                <div
                                    style="width:44px;height:44px;background:rgba(229,57,53,0.12);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fa fa-trash" style="color:#E53935;font-size:18px;"></i>
                                </div>
                                <div>
                                    <h3 style="color:#fff;margin:0 0 3px;font-size:17px;font-weight:700;">Delete Account
                                    </h3>
                                    <p style="color:rgba(255,255,255,0.4);margin:0;font-size:12px;">This action is
                                        permanent and cannot be undone.</p>
                                </div>
                            </div>
                            <p style="color:rgba(255,255,255,0.6);font-size:13px;margin:0 0 20px;">Enter your password
                                to confirm you want to permanently delete your Mantra account and all associated data.
                            </p>
                            <form action="{{ route('settings.delete') }}" method="POST" id="delete-form">
                                @csrf
                                @method('DELETE')
                                <div class="s-field" style="margin-bottom:20px;">
                                    <label>Confirm Password</label>
                                    <input type="password" name="confirm_password" id="delete-pw"
                                        placeholder="Enter your password" autocomplete="current-password">
                                    @error('confirm_password')
                                        <span class="field-hint" style="color:#E53935;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div style="display:flex;gap:12px;">
                                    <button type="submit" class="s-btn s-btn-danger"
                                        style="flex:1;justify-content:center;">
                                        <i class="fa fa-trash"></i> Yes, Delete My Account
                                    </button>
                                    <button type="button" id="close-delete-modal" class="s-btn s-btn-outline"
                                        style="flex:1;justify-content:center;">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div><!-- /.settings-wrapper -->
            </div>
        </section>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const PREF_URL = '{{ route("settings.preferences") }}';

        // ── Loaded DB preferences from server ──────────────────────────────────
        const dbPrefs = @json($user->preferences ?? []);

        // ── Apply preference: dark mode ────────────────────────────────────────
        function applyDarkMode(on) {
            if (on) document.body.classList.remove('light-mode');
            else document.body.classList.add('light-mode');
            localStorage.setItem('mantra_pref_dark', on ? '1' : '0');
        }

        // ── Apply preference: compact sidebar ─────────────────────────────────
        function applyCompact(on) {
            const sb = document.querySelector('.sidebar');
            if (on) sb.classList.add('compact');
            else sb.classList.remove('compact');
            localStorage.setItem('mantra_pref_compact', on ? '1' : '0');
        }

        // ── Initialize prefs from DB (server-side) + localStorage fallback ────
        (function initPrefs() {
            const darkOn = dbPrefs.dark_mode !== undefined ? dbPrefs.dark_mode : true;
            const compactOn = dbPrefs.compact_sidebar !== undefined ? dbPrefs.compact_sidebar : false;
            const notifyStudy = dbPrefs.notify_study !== undefined ? dbPrefs.notify_study : true;
            const notifyXp = dbPrefs.notify_xp !== undefined ? dbPrefs.notify_xp : true;

            applyDarkMode(darkOn);
            applyCompact(compactOn);

            document.getElementById('pref-dark').checked = darkOn;
            document.getElementById('pref-compact').checked = compactOn;
            document.getElementById('pref-reminders').checked = notifyStudy;
            document.getElementById('pref-xp').checked = notifyXp;
        })();

        $(document).ready(function () {
            // ── Sidebar toggle ──
            $('#btn').click(function () { $('.sidebar').toggleClass('open'); });

            // ── Hide loader ──
            const loader = document.getElementById('global-loader');
            if (loader) { setTimeout(() => { loader.style.opacity = '0'; setTimeout(() => loader.style.display = 'none', 500); }, 400); }

            // ── Tab switching ──
            function switchTab(tab) {
                $('.settings-tab-btn').removeClass('active');
                $('[data-tab="' + tab + '"]').addClass('active');
                $('.settings-tab-panel').removeClass('active');
                $('#tab-' + tab).addClass('active');
                localStorage.setItem('mantra_settings_tab', tab);
            }
            $('.settings-tab-btn').on('click', function () { switchTab($(this).data('tab')); });

            // Restore correct tab after form submit / on errors
            @if($errors->has('current_password') || $errors->has('password'))
                switchTab('security');
            @elseif($errors->has('name') || $errors->has('email'))
                switchTab('profile');
            @elseif($errors->has('confirm_password'))
                switchTab('account');
                $('#delete-modal').css('display', 'flex');
            @elseif(session('_tab'))
                switchTab('{{ session("_tab") }}');
            @else
                        const savedTab = localStorage.getItem('mantra_settings_tab');
                if (savedTab) switchTab(savedTab);
            @endif

            // ── AJAX preference save ──
            function savePref(key, value) {
                $.ajax({
                    url: PREF_URL,
                    method: 'POST',
                    data: { _token: CSRF, key: key, value: value ? 1 : 0 },
                    success: function (r) {
                        if (!r.success) console.warn('Pref save failed', key);
                    }
                });
            }

            // ── Dark mode toggle ──
            $('#pref-dark').on('change', function () {
                applyDarkMode(this.checked);
                savePref('dark_mode', this.checked);
            });

            // ── Compact sidebar toggle ──
            $('#pref-compact').on('change', function () {
                applyCompact(this.checked);
                savePref('compact_sidebar', this.checked);
            });

            // ── Study reminder toggle ──
            $('#pref-reminders').on('change', function () {
                savePref('notify_study', this.checked);
                const msg = this.checked ? 'Study reminder emails turned ON ✓' : 'Study reminder emails turned OFF';
                showToast(msg, this.checked);
            });

            // ── XP alert toggle ──
            $('#pref-xp').on('change', function () {
                savePref('notify_xp', this.checked);
                const msg = this.checked ? 'XP alert emails turned ON ✓' : 'XP alert emails turned OFF';
                showToast(msg, this.checked);
            });

            // ── Password show/hide eye toggles ──
            $(document).on('click', '.pw-eye', function () {
                const id = $(this).data('target');
                const inp = document.getElementById(id);
                const isText = inp.type === 'text';
                inp.type = isText ? 'password' : 'text';
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });

            // ── Delete account modal ──
            $('#open-delete-modal').on('click', function () { $('#delete-modal').css('display', 'flex'); });
            $('#close-delete-modal').on('click', function () { $('#delete-modal').hide(); });
            $('#delete-modal').on('click', function (e) { if (e.target === this) $(this).hide(); });
        });

        // ── Toast helper ──────────────────────────────────────────────────────
        function showToast(msg, success) {
            const t = document.createElement('div');
            t.style.cssText = 'position:fixed;bottom:24px;right:24px;padding:12px 20px;border-radius:10px;font-size:13px;font-weight:600;z-index:99999;transition:opacity .4s;' +
                (success
                    ? 'background:rgba(0,184,148,0.12);border:1px solid rgba(0,184,148,0.3);color:#00B894;'
                    : 'background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.7);');
            t.textContent = msg;
            document.body.appendChild(t);
            setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 400); }, 2500);
        }
    </script>

    <!-- pw-eye button style -->
    <style>
        .pw-eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.35);
            cursor: pointer;
            padding: 4px;
            font-size: 14px;
            transition: color .2s;
        }

        .pw-eye:hover {
            color: #5C7CFA;
        }
    </style>
</body>