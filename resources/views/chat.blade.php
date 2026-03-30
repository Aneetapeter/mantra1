<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Mantra Student Dashboard">
    <meta name="author" content="Mantra">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <title>Mantra | Chat</title>
    <link rel="icon" type="image/png" href="{{ asset('images/mantra.png') }}">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v=2">

    <style>
        :root {
            /* Base Colors */
            --chat-bg: #14151a;
            --sidebar-bg: #111216;
            --primary-accent: #5C7CFA;
            --primary-accent-hover: #4b6bf5;

            /* Text */
            --text-main: #ffffff;
            --text-muted: #8b92a5;

            /* Borders & Elements */
            --border-color: rgba(255, 255, 255, 0.05);
            --hover-bg: rgba(255, 255, 255, 0.03);

            /* Message Bubbles */
            --msg-sent-bg: linear-gradient(135deg, #5C7CFA, #748ffc);
            --msg-sent-shadow: 0 4px 15px rgba(92, 124, 250, 0.3);
            --msg-received-bg: #1e2028;
            --msg-received-border: rgba(255, 255, 255, 0.04);

            /* Glassmorphism */
            --glass-bg: rgba(20, 21, 26, 0.85);
            --glass-border: rgba(255, 255, 255, 0.05);
        }

        body.light-mode {
            --chat-bg: #fdfdfd;
            --sidebar-bg: #f5f6fa;
            --text-main: #1a1d2e;
            --text-muted: #6b7280;
            --border-color: rgba(0, 0, 0, 0.06);
            --hover-bg: rgba(0, 0, 0, 0.03);
            --msg-sent-bg: linear-gradient(135deg, #5C7CFA, #748ffc);
            --msg-sent-shadow: 0 4px 15px rgba(92, 124, 250, 0.2);
            --msg-received-bg: #ffffff;
            --msg-received-border: rgba(0, 0, 0, 0.08);
            --glass-bg: rgba(253, 253, 253, 0.9);
            --glass-border: rgba(0, 0, 0, 0.05);
        }

        /* ── LAYOUT ── */
        .chat-container {
            display: flex;
            height: calc(100vh - 120px);
            margin: 0;
            border-radius: 20px;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            background: var(--chat-bg);
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        /* ── LEFT SIDEBAR ── */
        .chat-sidebar-inner {
            width: 320px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            z-index: 10;
        }

        .chat-header {
            padding: 24px 20px;
            display: flex;
            align-items: center;
        }

        .chat-header h3 {
            color: var(--text-main);
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .chat-tabs {
            display: flex;
            padding: 0 20px 15px 20px;
            gap: 8px;
        }

        .chat-tab-btn {
            flex: 1;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
            padding: 10px;
            cursor: pointer;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
        }

        body.light-mode .chat-tab-btn {
            background: rgba(0, 0, 0, 0.02);
        }

        .chat-tab-btn.active {
            background: var(--primary-accent);
            color: white;
            border-color: var(--primary-accent);
            box-shadow: var(--msg-sent-shadow);
        }

        .chat-tab-btn:not(.active):hover {
            background: var(--hover-bg);
            color: var(--text-main);
        }

        /* Lists */
        .chat-list {
            flex: 1;
            overflow-y: auto;
            padding: 10px 15px;
        }

        .chat-list::-webkit-scrollbar {
            width: 5px;
        }

        .chat-list::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 10px;
        }

        .chat-list-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 8px;
            border: 1px solid transparent;
        }

        .chat-list-item:hover {
            background: var(--hover-bg);
            transform: translateY(-1px);
        }

        .chat-list-item.active {
            background: var(--hover-bg);
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .chat-avatar {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(92, 124, 250, 0.15);
            color: var(--primary-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            margin-right: 15px;
            flex-shrink: 0;
            text-transform: uppercase;
            box-shadow: inset 0 0 0 1px rgba(92, 124, 250, 0.2);
        }

        .chat-avatar.friend {
            background: rgba(0, 184, 148, 0.15);
            color: #00B894;
            box-shadow: inset 0 0 0 1px rgba(0, 184, 148, 0.2);
        }

        .chat-info {
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .chat-name {
            color: var(--text-main);
            font-size: 15px;
            font-weight: 600;
            margin: 0 0 5px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-preview {
            color: var(--text-muted);
            font-size: 13px;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }

        .chat-meta {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
        }

        .chat-time {
            font-size: 11px;
            color: var(--text-muted);
            margin-bottom: 6px;
            font-weight: 500;
        }

        .chat-badge {
            background: #FF7675;
            color: white;
            font-size: 11px;
            font-weight: 700;
            min-width: 20px;
            height: 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
            box-shadow: 0 2px 5px rgba(255, 118, 117, 0.4);
        }

        /* ── SEARCH MODAL IN SIDEBAR ── */
        .search-box {
            padding: 0 20px 15px 20px;
            position: relative;
            border-bottom: 1px solid var(--border-color);
        }

        .search-box input {
            width: 100%;
            background: var(--hover-bg);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            padding: 12px 15px 12px 40px;
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }

        .search-box input:focus {
            border-color: var(--primary-accent);
            background: rgba(92, 124, 250, 0.05);
        }

        .search-box i {
            position: absolute;
            left: 35px;
            top: 14px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .req-btn {
            background: var(--primary-accent);
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(92, 124, 250, 0.2);
        }

        .req-btn:hover:not(:disabled) {
            background: var(--primary-accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(92, 124, 250, 0.3);
        }

        .req-btn.outline {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-main);
            box-shadow: none;
        }

        .req-btn.outline:hover:not(:disabled) {
            background: var(--hover-bg);
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* ── MAIN CHAT AREA ── */
        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--chat-bg);
            position: relative;
        }

        .chat-bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.2;
            pointer-events: none;
            background-image: radial-gradient(var(--border-color) 1px, transparent 1px);
            background-size: 20px 20px;
            z-index: 0;
        }

        .chat-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--text-muted);
            z-index: 1;
        }

        .empty-icon-wrapper {
            width: 90px;
            height: 90px;
            border-radius: 25px;
            background: linear-gradient(135deg, rgba(92, 124, 250, 0.1), rgba(0, 206, 201, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            color: var(--primary-accent);
            box-shadow: inset 0 0 0 1px rgba(92, 124, 250, 0.2);
        }

        .chat-empty i {
            font-size: 38px;
        }

        .active-chat-header {
            padding: 15px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 5;
            position: sticky;
            top: 0;
        }

        .chat-messages {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            z-index: 1;
            scroll-behavior: smooth;
        }

        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        body.light-mode .chat-messages::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
        }

        .msg-bubble-wrap {
            display: flex;
            margin-bottom: 8px;
            /* Tighter vertical spacing like IG */
            max-width: 75%;
            /* Slightly wider max-width */
            animation: fadeIn 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .msg-bubble-wrap.sent {
            align-self: flex-end;
            flex-direction: column;
            align-items: flex-end;
        }

        .msg-bubble-wrap.received {
            align-self: flex-start;
            flex-direction: column;
            align-items: flex-start;
        }

        .msg-bubble {
            padding: 10px 16px;
            /* IG style padding */
            border-radius: 22px;
            /* Very rounded IG style */
            font-size: 15px;
            /* Slightly larger text for readability */
            line-height: 1.4;
            word-wrap: break-word;
            position: relative;
        }

        .msg-bubble-wrap.sent .msg-bubble {
            background: #3797F0;
            /* Instagram Primary Blue */
            color: white;
            /* Instagram does not have sharp corners on the bottom for sent messages usually, 
               but subtle flattening can happen. We'll use a strong round on all sides for the IG look. */
            box-shadow: none;
            /* No shadow */
        }

        .msg-bubble-wrap.received .msg-bubble {
            background: #262626;
            /* Dark mode gray for received */
            color: var(--text-main);
            border: 1px solid var(--border-color);
            /* Subtle border */
            box-shadow: none;
            /* No shadow */
        }

        body.light-mode .msg-bubble-wrap.received .msg-bubble {
            background: #EFEFEF;
            /* Light mode gray for received */
            border: none;
        }

        .msg-time {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
            /* Tighter time spacing */
            font-weight: 500;
            opacity: 0.8;
            /* IG hides time by default and shows on hover/click, but keeping it visible is fine for usability */
        }

        /* ── INPUT AREA ── */
        .chat-input-area {
            padding: 15px 24px;
            background: transparent;
            /* IG is usually flat or transparent to the bg */
            border-top: none;
            /* No harsh top border */
            display: flex;
            align-items: center;
            gap: 15px;
            z-index: 5;
        }

        .input-wrapper {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
        }

        .chat-input {
            width: 100%;
            background: #262626;
            /* IG dark gray input */
            border: 1px solid var(--border-color);
            color: var(--text-main);
            padding: 14px 20px;
            border-radius: 30px;
            /* Pill shape input */
            font-size: 15px;
            outline: none;
            transition: all 0.2s ease;
        }

        body.light-mode .chat-input {
            background: #EFEFEF;
            /* IG light gray input */
            border: 1px solid rgba(0, 0, 0, 0.05);
            /* Very subtle light border */
        }

        .chat-input:focus {
            border-color: #3797F0;
            /* Focus ring */
            background: #262626;
        }

        body.light-mode .chat-input:focus {
            background: #EFEFEF;
        }

        .btn-send {
            background: transparent;
            color: #3797F0;
            /* Instagram Blue text */
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            font-size: 20px;
            font-weight: 600;
            transition: all 0.2s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: none;
            /* Flat IG send button */
        }

        .btn-send:hover:not(:disabled) {
            transform: scale(1.05);
            /* Slight scale */
            color: #1877F2;
            /* Darker blue on hover */
            background: transparent;
            /* Remains transparent */
            box-shadow: none;
        }

        .btn-send:active:not(:disabled) {
            transform: scale(0.95);
        }

        .btn-send:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            color: var(--text-muted);
            /* Gray out when disabled */
            background: transparent;
        }

        .no-data {
            color: var(--text-muted);
            text-align: center;
            padding: 30px 20px;
            font-size: 13px;
        }

        .no-data i {
            display: block;
            font-size: 24px;
            margin-bottom: 15px;
            opacity: 0.3;
        }

        /* ── INSTAGRAM STYLE FRIEND LIST ── */
        .friend-item {
            padding: 12px 15px;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 2px;
            border: 1px solid transparent;
        }

        .friend-item:hover {
            background: var(--hover-bg);
        }

        .friend-item.active {
            background: var(--hover-bg);
            border: 1px solid var(--border-color);
        }

        .profile-circle {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(0, 184, 148, 0.15);
            color: #00B894;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
            text-transform: uppercase;
            box-shadow: inset 0 0 0 1px rgba(0, 184, 148, 0.2);
        }

        .friend-name {
            color: var(--text-main);
            font-size: 15px;
            font-weight: 600;
        }

        .last-message {
            font-size: 13px;
        }

        /* ── SHARED NOTE DOCUMENT CARD (WHATSAPP STYLE) ── */
        .doc-share-card {
            background: #025C4C; /* WhatsApp Dark Green */
            border-radius: 8px;
            overflow: hidden;
            width: 280px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
            color: #E9EDEF;
            margin: -8px; /* Offset the msg-bubble padding to fill */
            font-family: 'Segoe UI', 'Inter', sans-serif;
            text-align: left;
        }

        .doc-share-card .doc-preview-top {
            background: #ffffff;
            color: #111b21;
            padding: 12px 15px;
            font-size: 13px;
            line-height: 1.4;
            height: 70px;
            overflow: hidden;
            mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
            -webkit-mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
        }

        .doc-share-card .doc-header {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            gap: 12px;
            background: rgba(0,0,0,0.1);
        }

        .doc-share-card .doc-icon {
            background: #F15C6D; /* Red PDF style */
            width: 40px;
            height: 44px;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 11px;
            color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            position: relative;
        }
        
        .doc-share-card .doc-icon::before {
            content: "PDF";
            position: absolute;
            bottom: 4px;
            font-size: 9px;
            letter-spacing: 0.5px;
        }

        .doc-share-card .doc-info {
            flex: 1;
            overflow: hidden;
        }

        .doc-share-card .doc-title {
            font-size: 15px;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 2px;
            color: #E9EDEF;
        }

        .doc-share-card .doc-meta {
            font-size: 12px;
            color: rgba(233, 237, 239, 0.7);
        }

        .doc-share-card .doc-actions {
            display: flex;
            border-top: 1px solid rgba(255,255,255,0.08);
            background: rgba(0,0,0,0.15);
        }

        .doc-share-card .btn-doc-action {
            flex: 1;
            background: transparent;
            border: none;
            color: #00A884; /* WhatsApp primary light green */
            padding: 12px 0;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            text-transform: capitalize;
            letter-spacing: 0.5px;
        }

        .doc-share-card .btn-doc-action:hover {
            background: rgba(255,255,255,0.05);
        }
        
        .doc-share-card .btn-doc-action:not(:last-child) {
            border-right: 1px solid rgba(255,255,255,0.08);
        }

        /* ── USER PROFILE MODAL ── */
        .profile-modal-content {
            background: var(--chat-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .profile-banner {
            height: 120px;
            background: linear-gradient(135deg, #5C7CFA, #00B894);
            position: relative;
        }

        .profile-avatar-large {
            width: 100px;
            height: 100px;
            border-radius: 30px;
            background: #262626;
            border: 4px solid var(--chat-bg);
            position: absolute;
            bottom: -50px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: 800;
            color: var(--primary-accent);
            text-transform: uppercase;
        }

        .profile-body {
            padding: 70px 30px 30px;
            text-align: center;
        }

        .profile-name-title h2 {
            font-weight: 800;
            font-size: 24px;
            margin-bottom: 5px;
            color: var(--text-main);
        }

        .profile-name-title p {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 25px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--hover-bg);
            padding: 15px;
            border-radius: 18px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: var(--primary-accent);
            transform: translateY(-2px);
        }

        .stat-value {
            font-weight: 700;
            font-size: 18px;
            color: var(--text-main);
            display: block;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .rewards-section {
            text-align: left;
        }

        .rewards-section h5 {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 15px;
            color: var(--text-muted);
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(92, 124, 250, 0.1);
            color: #5C7CFA;
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            margin: 0 6px 6px 0;
            border: 1px solid rgba(92, 124, 250, 0.2);
        }

        .sticker-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(0, 184, 148, 0.1);
            color: #00B894;
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            margin: 0 6px 6px 0;
            border: 1px solid rgba(0, 184, 148, 0.2);
        }

        /* ── SHARED NOTE CARD ── */
        .note-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 15px;
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
        }

        body.light-mode .note-card {
            background: rgba(0, 0, 0, 0.03);
        }

        .note-card-header {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .note-card-icon {
            width: 36px;
            height: 36px;
            background: rgba(92, 124, 250, 0.15);
            color: var(--primary-accent);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .note-card-title {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-main);
            margin: 0;
        }

        .btn-save-note {
            background: var(--primary-accent);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            width: 100%;
        }

        .btn-save-note:hover {
            background: var(--primary-accent-hover);
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
                        <a href="{{ route('progress') }}"><i class="fa fa-pie-chart"></i><span
                                class="links_name">Progress</span></a>
                        <span class="tooltip">Progress</span>
                    </li>
                    <li>
                        <a href="{{ route('chat') }}" class="active"><i class="fa fa-comments"></i><span
                                class="links_name">Chat</span></a>
                        <span class="tooltip">Chat</span>
                    </li>
                    <li>
                        <a href="{{ route('settings') }}"><i class="fa fa-cog"></i><span
                                class="links_name">Settings</span></a>
                        <span class="tooltip">Settings</span>
                    </li>
                    <li class="profile">
                        <div class="profile-details">
                            <div class="name_job">
                                <div class="name">{{ Auth::user()->name }}</div>
                                <div class="job">{{ Auth::user()->title ?? 'Student' }}</div>
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
            <section class="home-section" style="height: 100vh; overflow: hidden; display: flex; flex-direction: column;">
                <div class="top-bar">
                    <div class="text">💬 Messaging</div>
                </div>

                <div class="main-content" style="flex: 1; padding: 20px 30px 20px 30px;">
                    <div class="chat-container">

                        <!-- LEFT SIDEBAR -->
                        <div class="chat-sidebar-inner">
                            <div class="chat-header">
                                <h3>Chats</h3>
                            </div>

                            <div class="chat-tabs">
                                <button class="chat-tab-btn active" data-tab="friends"
                                    style="padding: 8px 10px; font-size: 13px;"><i class="fa fa-users"
                                        style="margin-right:4px"></i> Friends</button>
                                <button class="chat-tab-btn" data-tab="requests"
                                    style="padding: 8px 10px; font-size: 13px;">
                                    <i class="fa fa-bell-o" style="margin-right:4px"></i> Req
                                    <span id="req-badge" class="chat-badge"
                                        style="display:none; position:absolute; top:-5px; right:-5px;">0</span>
                                </button>
                                <button class="chat-tab-btn" data-tab="add_friends"
                                    style="padding: 8px 10px; font-size: 13px;">
                                    <i class="fa fa-user-plus" style="margin-right:4px"></i> Add
                                </button>
                                <button class="chat-tab-btn" data-tab="blocked" style="padding: 8px 10px; font-size: 13px;">
                                    <i class="fa fa-ban" style="margin-right:4px"></i> Blocked
                                </button>
                            </div>

                            <div class="search-box">
                                <i class="fa fa-search"></i>
                                <input type="text" id="student-search" placeholder="Search students...">
                            </div>

                            <div class="chat-list" id="search-results-list" style="display:none;"></div>
                            <div class="chat-list" id="friends-list"></div>
                            <div class="chat-list" id="requests-list" style="display:none;"></div>
                            <div class="chat-list" id="add-friends-list" style="display:none;"></div>
                            <div class="chat-list" id="blocked-users-list" style="display:none;"></div>
                        </div>

                        <!-- MAIN CHAT AREA -->
                        <div class="chat-main">
                            <div class="chat-bg-pattern"></div>

                            <div class="chat-empty" id="chat-empty-state">
                                <div class="empty-icon-wrapper">
                                    <i class="fa fa-comments-o"></i>
                                </div>
                                <h4 style="color:var(--text-main);font-weight:700;margin:0 0 12px; font-size: 22px;">Welcome
                                    to Mantra Chat</h4>
                                <p style="margin:0; max-width: 300px; text-align: center; line-height: 1.6;">Stay connected
                                    with your classmates. Select a friend from the sidebar to start a conversation.</p>
                            </div>

                            <div id="active-chat-wrapper"
                                style="display:none; flex-direction:column; height: 100%; width: 100%;">
                                <div class="active-chat-header">
                                    <div style="display: flex; align-items: center; flex: 1; cursor: pointer;"
                                        onclick="showUserProfile(currentChatFriendId)">
                                        <div class="chat-avatar friend" id="active-chat-avatar"></div>
                                        <div>
                                            <h4 id="active-chat-name"
                                                style="color:var(--text-main); margin:0 0 4px; font-size:16px; font-weight:700;">
                                                Name</h4>
                                            <p
                                                style="color:var(--text-muted); margin:0; font-size:13px; display: flex; align-items: center;">
                                                <span
                                                    style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#00B894; margin-right:6px;"></span>
                                                Active Friend
                                            </p>
                                        </div>
                                    </div>
                                    <div class="chat-actions" style="display: flex; gap: 8px;">
                                        <button class="req-btn outline"
                                            onclick="sendSystemMessage('🔥 Let\'s focus! I sent you a Focus Reminder!')"
                                            title="Send Focus Reminder" style="padding: 6px 10px; font-size: 13px;">
                                            <i class="fa fa-fire"></i> <span class="d-none d-sm-inline"
                                                style="margin-left:4px;">Focus</span>
                                        </button>
                                        <button class="req-btn outline"
                                            onclick="sendSystemMessage('🏆 I just shared an XP Badge with you! Keep up the great work!')"
                                            title="Share XP Badge" style="padding: 6px 10px; font-size: 13px;">
                                            <i class="fa fa-trophy"></i>
                                        </button>
                                        <button id="btn-block-user" class="req-btn outline" onclick="blockUser()"
                                            title="Block User"
                                            style="padding: 6px 10px; font-size: 13px; color: #ff6b6b; border-color: rgba(255, 107, 107, 0.3);">
                                            <i class="fa fa-ban"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Block State Overlay -->
                                <div id="chat-blocked-state"
                                    style="display:none; text-align:center; padding: 20px; background: rgba(255,0,0,0.05); border-top: 1px solid var(--border-color); color: var(--text-muted); font-size: 14px;">
                                    <i class="fa fa-lock" style="margin-right: 5px;"></i> You cannot reply to this
                                    conversation.
                                </div>

                                <div class="chat-messages" id="chat-messages-box"></div>

                                <div class="chat-input-area">
                                    <button class="btn-attach" onclick="openShareNoteModal()" title="Share Note"
                                        style="background: transparent; border: none; color: var(--text-muted); font-size: 20px; cursor: pointer; transition: 0.2s; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%;">
                                        <i class="fa fa-folder-open"></i>
                                    </button>
                                    <div class="input-wrapper">
                                        <input type="text" id="message-input" class="chat-input" placeholder="Message..."
                                            autocomplete="off">
                                    </div>
                                    <button class="btn-send" id="btn-send" disabled><i
                                            class="fa fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @else
        <script>window.location.href = "{{ route('login') }}";</script>
    @endauth

    <!-- User Profile Modal -->
    <div class="modal fade" id="userProfileModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content profile-modal-content">
                <div class="profile-banner">
                    <div class="profile-avatar-large" id="profile-modal-avatar">?</div>
                </div>
                <div class="profile-body">
                    <div class="profile-name-title">
                        <h2 id="profile-modal-name">User Name</h2>
                        <p id="profile-modal-title">Student</p>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <span class="stat-value" id="profile-modal-streak">0</span>
                            <span class="stat-label">🔥 Streak</span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-value" id="profile-modal-xp">0</span>
                            <span class="stat-label">✨ EXP</span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-value" id="profile-modal-level">1</span>
                            <span class="stat-label">🎓 Level</span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-value" id="profile-modal-time">0m</span>
                            <span class="stat-label">⏳ Study Time</span>
                        </div>
                    </div>

                    <div class="rewards-section">
                        <h5><i class="fa fa-trophy"></i> Achievements & Rewards</h5>
                        <div id="profile-modal-rewards" class="d-flex flex-wrap">
                            <!-- Badges and Stickers will be injected here -->
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="button" class="req-btn outline w-100" data-bs-dismiss="modal">Close
                            Profile</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Share Note Modal -->
    <div class="modal fade" id="shareNoteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 20px; color: var(--text-main);">
                <div class="modal-header" style="border-bottom: 1px solid var(--border-color); padding: 20px;">
                    <h5 class="modal-title" style="font-weight: 700;"><i class="fa fa-share-alt"
                            style="color: var(--primary-accent); margin-right: 10px;"></i> Share Study Note</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <div id="share-note-list" class="list-group list-group-flush"
                        style="max-height: 400px; overflow-y: auto;">
                        <!-- Notes will be injected here -->
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border-color); padding: 15px;">
                    <button type="button" class="req-btn outline" data-bs-dismiss="modal"
                        style="padding: 8px 20px;">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script src="{{ asset('jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/music-player.js') }}?v={{ time() }}"></script>

    <script>
        $(document).ready(function () {
            const loader = document.getElementById('global-loader');
            if (loader) {
                setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => loader.style.display = 'none', 500);
                }, 600);
            }
            // Sidebar toggle (mobile overlay aware)
            (function() {
                var sb = document.querySelector('.sidebar');
                var btn = document.getElementById('btn');
                if (!document.getElementById('sidebar-overlay')) {
                    var ov = document.createElement('div');
                    ov.id = 'sidebar-overlay'; ov.className = 'sidebar-overlay';
                    document.body.appendChild(ov);
                }
                var overlay = document.getElementById('sidebar-overlay');
                btn.addEventListener('click', function() {
                    var isOpen = sb.classList.toggle('open');
                    if (isOpen && window.innerWidth <= 768) overlay.classList.add('active');
                    else overlay.classList.remove('active');
                });
                overlay.addEventListener('click', function() {
                    sb.classList.remove('open'); overlay.classList.remove('active');
                });
            })();
        });

        (function () {
            if (localStorage.getItem('mantra_pref_dark') === '0') document.body.classList.add('light-mode');
            if (localStorage.getItem('mantra_pref_compact') === '1') {
                var sb = document.querySelector('.sidebar');
                if (sb) sb.classList.add('compact');
            }
        })();

        document.addEventListener('DOMContentLoaded', function () {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (!csrfMeta) return; // Prevent JS errors if logged out completely
            const CSRF = csrfMeta.content;

            let currentTab = 'friends';
            let currentChatFriendId = null;
            let pollingInterval = null;
            let lastMessageId = 0;
            let isFetching = false; // Prevent race conditions on polling

            const studentSearch = document.getElementById('student-search');
            const searchResultsList = document.getElementById('search-results-list');
            const friendsList = document.getElementById('friends-list');
            const requestsList = document.getElementById('requests-list');
            const tabBtns = document.querySelectorAll('.chat-tab-btn');
            const reqBadge = document.getElementById('req-badge');

            const emptyState = document.getElementById('chat-empty-state');
            const activeWrapper = document.getElementById('active-chat-wrapper');
            const messagesBox = document.getElementById('chat-messages-box');
            const msgInput = document.getElementById('message-input');
            const btnSend = document.getElementById('btn-send');

            // Delay initial loads until after function declarations
            // (moved to bottom of DOMContentLoaded)

            // Tab Switching Logic
            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    tabBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    currentTab = btn.getAttribute('data-tab');

                    studentSearch.value = '';
                    searchResultsList.style.display = 'none';
                    friendsList.style.display = 'none';
                    requestsList.style.display = 'none';
                    document.getElementById('add-friends-list').style.display = 'none';
                    document.getElementById('blocked-users-list').style.display = 'none';

                    if (currentTab === 'friends') {
                        friendsList.style.display = 'block';
                        loadFriends();
                    } else if (currentTab === 'requests') {
                        requestsList.style.display = 'block';
                        loadRequests();
                    } else if (currentTab === 'add_friends') {
                        document.getElementById('add-friends-list').style.display = 'block';
                        loadDiscover();
                    } else if (currentTab === 'blocked') {
                        document.getElementById('blocked-users-list').style.display = 'block';
                        loadBlocked();
                    }
                });
            });

            // Discovery Render Logic
            window.renderStudentList = function (data, container) {
                container.innerHTML = '';
                if (data.length === 0) {
                    container.innerHTML = `<div class="no-data"><i class="fa fa-search"></i> No students found.</div>`;
                    return;
                }

                data.forEach(student => {
                    const initial = student.name.charAt(0).toUpperCase();
                    const item = document.createElement('div');
                    item.className = 'chat-list-item';

                    let actionHtml = '';
                    if (student.status === 'sent') {
                        actionHtml = `<button class="req-btn" disabled style="background:var(--hover-bg);color:var(--text-muted);border:1px solid var(--border-color);box-shadow:none;">Requested</button>`;
                    } else if (student.status === 'received') {
                        actionHtml = `<button class="req-btn blur" onclick="document.querySelector('[data-tab=\\'requests\\']').click();" style="background:#00B894;">Accept</button>`;
                    } else {
                        actionHtml = `<button class="req-btn" style="background:#3797F0;" onclick="sendRequest(${student.id}, this)">Add Friend</button>`;
                    }

                    item.innerHTML = `
                        <div class="chat-avatar" style="cursor:pointer" onclick="event.stopPropagation(); showUserProfile(${student.id})">${initial}</div>
                        <div class="chat-info">
                            <h4 class="chat-name">${escapeHtml(student.name)}</h4>
                            <p class="chat-preview">${escapeHtml(student.email)}</p>
                        </div>
                        <div>
                            ${actionHtml}
                        </div>
                    `;
                    container.appendChild(item);
                });
            };

            window.loadDiscover = function () {
                fetch('/chat/search')
                    .then(res => res.json())
                    .then(data => renderStudentList(data, document.getElementById('add-friends-list')))
                    .catch(err => console.error(err));
            };

            // Search Logic
            let searchTimeout;
            studentSearch.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                const val = e.target.value.trim();

                if (val.length === 0) {
                    searchResultsList.style.display = 'none';
                    if (currentTab === 'friends') friendsList.style.display = 'block';
                    if (currentTab === 'requests') requestsList.style.display = 'block';
                    if (currentTab === 'add_friends') document.getElementById('add-friends-list').style.display = 'block';
                    return;
                }

                searchTimeout = setTimeout(() => {
                    fetch(`/chat/search?q=${encodeURIComponent(val)}`)
                        .then(res => res.json())
                        .then(data => {
                            friendsList.style.display = 'none';
                            requestsList.style.display = 'none';
                            document.getElementById('add-friends-list').style.display = 'none';
                            searchResultsList.style.display = 'block';

                            renderStudentList(data, searchResultsList);
                        }).catch(err => console.error(err));
                }, 400); // 400ms debounce
            });

            // Load Requests
            window.loadRequests = function () {
                fetch('/chat/requests')
                    .then(res => res.json())
                    .then(data => {
                        requestsList.innerHTML = '';
                        if (data.length > 0) {
                            reqBadge.style.display = 'flex';
                            reqBadge.innerText = data.length;
                        } else {
                            reqBadge.style.display = 'none';
                            requestsList.innerHTML = `<div class="no-data"><i class="fa fa-bell-slash-o"></i> No pending requests.</div>`;
                        }

                        data.forEach(req => {
                            const initial = req.sender_name.charAt(0).toUpperCase();
                            const item = document.createElement('div');
                            item.className = 'chat-list-item';
                            item.innerHTML = `
                                <div class="chat-avatar">${initial}</div>
                                <div class="chat-info">
                                    <h4 class="chat-name">${escapeHtml(req.sender_name)}</h4>
                                    <p class="chat-preview">${req.time_ago}</p>
                                </div>
                                <div style="display:flex;gap:5px;flex-direction:column;">
                                    <button class="req-btn" style="padding:4px 8px;font-size:11px;" onclick="respondRequest(${req.id}, 'accept', this)">Accept</button>
                                    <button class="req-btn outline" style="padding:4px 8px;font-size:11px;" onclick="respondRequest(${req.id}, 'reject', this)">Reject</button>
                                </div>
                            `;
                            requestsList.appendChild(item);
                        });
                    }).catch(err => console.error(err));
            };

            // Send Request
            window.sendRequest = function (receiverId, btn) {
                btn.disabled = true;
                btn.innerText = '...';

                fetch('/chat/request', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ receiver_id: receiverId })
                }).then(r => r.json()).then(res => {
                    if (res.success) {
                        btn.innerText = 'Requested';
                        btn.style.background = 'var(--hover-bg)';
                        btn.style.color = 'var(--text-muted)';
                        btn.style.border = '1px solid var(--border-color)';
                        btn.style.boxShadow = 'none';
                    } else {
                        btn.innerText = 'Error';
                        btn.disabled = false;
                    }
                }).catch(err => {
                    btn.innerText = 'Error';
                    btn.disabled = false;
                });
            };

            // Load Blocked Users
            window.loadBlocked = function () {
                fetch('/chat/blocked')
                    .then(res => res.json())
                    .then(data => {
                        const blockedList = document.getElementById('blocked-users-list');
                        blockedList.innerHTML = '';
                        if (data.length === 0) {
                            blockedList.innerHTML = `<div class="no-data"><i class="fa fa-check-circle"></i> You have no blocked users.</div>`;
                            return;
                        }

                        data.forEach(user => {
                            const initial = user.name.charAt(0).toUpperCase();
                            const item = document.createElement('div');
                            item.className = 'chat-list-item';

                            item.innerHTML = `
                                <div class="chat-avatar" style="background: rgba(255, 107, 107, 0.15); color: #ff6b6b; box-shadow: inset 0 0 0 1px rgba(255, 107, 107, 0.2);">${initial}</div>
                                <div class="chat-info">
                                    <h4 class="chat-name">${escapeHtml(user.name)}</h4>
                                    <p class="chat-preview">Blocked</p>
                                </div>
                                <div>
                                    <button class="req-btn" style="background:#ff6b6b;" onclick="unblockUser(${user.id}, this)">Unblock</button>
                                </div>
                            `;
                            blockedList.appendChild(item);
                        });
                    }).catch(err => console.error(err));
            };

            // Unblock User (List view)
            window.unblockUser = function (blockedId, btn) {
                btn.disabled = true;
                btn.innerText = '...';

                fetch('/chat/unblock', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ blocked_id: blockedId })
                }).then(r => r.json()).then(res => {
                    if (res.success) {
                        btn.innerText = 'Unblocked';
                        btn.style.background = 'var(--hover-bg)';
                        btn.style.color = 'var(--text-muted)';
                        btn.style.border = '1px solid var(--border-color)';
                        setTimeout(() => loadBlocked(), 600);
                    } else {
                        btn.innerText = 'Error';
                        btn.disabled = false;
                    }
                }).catch(err => {
                    btn.innerText = 'Error';
                    btn.disabled = false;
                });
            };

            // Respond to Request
            window.respondRequest = function (reqId, action, btn) {
                btn.disabled = true;
                fetch('/chat/respond', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ request_id: reqId, action: action })
                }).then(r => r.json()).then(res => {
                    if (res.success) {
                        loadRequests();
                        if (action === 'accept') loadFriends();
                    }
                }).catch(err => {
                    btn.disabled = false;
                });
            };

            // Load Friends
            window.loadFriends = function () {
                fetch('/chat/friends')
                    .then(res => res.json())
                    .then(data => {
                        friendsList.innerHTML = '';
                        if (data.length === 0) {
                            friendsList.innerHTML = `<div class="no-data"><i class="fa fa-user-times"></i> You haven't added any friends yet.</div>`;
                            return;
                        }

                        data.forEach(friend => {
                            const initial = friend.name.charAt(0).toUpperCase();
                            const isActive = currentChatFriendId == friend.id ? 'active' : '';
                            const unreadHtml = friend.unread > 0 ? `<div class="chat-badge">${friend.unread}</div>` : '';

                            const item = document.createElement('div');
                            item.className = `friend-item d-flex justify-content-between align-items-center chat-list-item ${isActive}`;
                            item.onclick = () => openChat(friend.id, friend.name, initial, item);

                            item.innerHTML = `
                                <div class="d-flex align-items-center" style="overflow: hidden; max-width: 70%;">
                                    <div class="profile-circle" style="cursor:pointer" onclick="event.stopPropagation(); showUserProfile(${friend.id})">${initial}</div>
                                    <div style="margin-left: 15px; overflow: hidden; min-width: 0;">
                                        <div class="friend-name" style="${friend.unread > 0 ? 'color:var(--text-main); font-weight:700;' : ''}">${escapeHtml(friend.name)}</div>
                                        <div class="last-message text-muted">${friend.last_msg ? escapeHtml(friend.last_msg) : 'Say hi! 👋'}</div>
                                    </div>
                                </div>
                                <div class="time text-muted small" style="text-align: right;">
                                    ${friend.last_time || ''}
                                    ${unreadHtml}
                                </div>
                            `;
                            friendsList.appendChild(item);
                        });
                    }).catch(err => console.error(err));
            };

            // Open Chat Window
            window.openChat = function (friendId, name, initial, el) {
                document.querySelectorAll('.chat-list-item').forEach(i => i.classList.remove('active'));
                if (el) el.classList.add('active');

                currentChatFriendId = friendId;
                emptyState.style.display = 'none';
                activeWrapper.style.display = 'flex';
                document.getElementById('chat-blocked-state').style.display = 'none';
                document.querySelector('.chat-input-area').style.display = 'flex';

                document.getElementById('active-chat-avatar').innerText = initial;
                document.getElementById('active-chat-name').innerText = escapeHtml(name);

                if (pollingInterval) clearInterval(pollingInterval);

                messagesBox.innerHTML = '<div style="text-align:center;color:var(--text-muted);padding:40px;"><div class="spinner-border spinner-border-sm" style="color:var(--primary-accent);margin-bottom:10px;"></div><br>Loading secure chat...</div>';
                lastMessageId = 0;

                fetchMessages(true);
                pollingInterval = setInterval(() => fetchMessages(false), 2000); // 2 second polling

                // Clear UI badge
                const badge = el.querySelector('.chat-badge');
                if (badge) badge.remove();
                const preview = el.querySelector('.chat-preview');
                if (preview) { preview.style.color = ''; preview.style.fontWeight = ''; }
            };

            // Fetch Messages (Initial + Polling)
            function fetchMessages(initialLoad = false) {
                if (!currentChatFriendId || isFetching) return;
                isFetching = true;

                fetch(`/chat/messages/${currentChatFriendId}?last_id=${lastMessageId}`)
                    .then(res => {
                        if (res.status === 403) {
                            document.getElementById('chat-blocked-state').style.display = 'block';
                            document.querySelector('.chat-input-area').style.display = 'none';
                            clearInterval(pollingInterval);
                            throw new Error('Blocked');
                        }
                        return res.json();
                    })
                    .then(res => {
                        isFetching = false;
                        if (!res.success) return;

                        if (initialLoad) {
                            messagesBox.innerHTML = '';
                            if (res.messages.length === 0) {
                                messagesBox.innerHTML = `<div style="text-align:center;color:var(--text-muted);padding:40px;">This is the beginning of your chat history.</div>`;
                            }
                        }

                        const msgs = res.messages;
                        if (msgs.length > 0) {
                            if (initialLoad && msgs.length > 0) messagesBox.innerHTML = ''; // clear initial load message

                            msgs.forEach(msg => {
                                appendMessage(msg.message, msg.is_sender, msg.time);
                                lastMessageId = Math.max(lastMessageId, msg.id);
                            });
                            scrollToBottom();
                        }
                    }).catch(err => {
                        isFetching = false;
                        if (err.message === 'Blocked') {
                            // Handled above
                        } else {
                            console.error(err);
                        }
                    });
            }

            // Append HTML Bubble
            function appendMessage(text, isSender, time) {
                if (!text) return;
                const side = isSender ? 'sent' : 'received';

                // Detect new doc share format
                const fileMatch = text.match(/\[FILE_SHARE:(\d+):([^:]+):?(.*)\]/);
                // Detect old note format for backwards compatibility
                const legacyNoteMatch = text.match(/\[NOTE_ID:(\d+)\]/);
                
                let content = escapeHtml(text);
                let isDocCard = false;

                if (fileMatch) {
                    const noteId = fileMatch[1];
                    const title = fileMatch[2] || 'Document';
                    const previewText = fileMatch[3] ? escapeHtml(fileMatch[3]).substring(0, 100) : 'Study material shared via Mantra.';
                    isDocCard = true;
                    
                    content = `
                        <div class="doc-share-card">
                            <div class="doc-preview-top">
                                ${previewText}...
                            </div>
                            <div class="doc-header">
                                <div class="doc-icon"><i class="fa fa-file-text-o"></i></div>
                                <div class="doc-info">
                                    <div class="doc-title">${escapeHtml(title)}.pdf</div>
                                    <div class="doc-meta">Study Note • PDF • Mantra</div>
                                </div>
                            </div>
                            <div class="doc-actions">
                                <button class="btn-doc-action" onclick="window.open('/study?note=${noteId}', '_blank')">Open</button>
                                ${!isSender ? `<button class="btn-doc-action" onclick="handleSaveNote(${noteId})">Save as...</button>` : ''}
                            </div>
                        </div>
                    `;
                } else if (legacyNoteMatch) {
                    const noteId = legacyNoteMatch[1];
                    const titleMatch = text.match(/\*\*([^*]+)\*\*/);
                    const title = titleMatch ? titleMatch[1] : 'Shared Note';
                    isDocCard = true;
                    
                    content = `
                        <div class="doc-share-card">
                            <div class="doc-header">
                                <div class="doc-icon"><i class="fa fa-file-text-o"></i></div>
                                <div class="doc-info">
                                    <div class="doc-title">${escapeHtml(title)}.pdf</div>
                                    <div class="doc-meta">Legacy Note • PDF • Mantra</div>
                                </div>
                            </div>
                            <div class="doc-actions">
                                <button class="btn-doc-action" onclick="window.open('/study?note=${noteId}', '_blank')">Open</button>
                                ${!isSender ? `<button class="btn-doc-action" onclick="handleSaveNote(${noteId})">Save as...</button>` : ''}
                            </div>
                        </div>
                    `;
                }

                // Make the bubble seamless for doc cards
                const bubbleStyle = isDocCard ? 'style="padding: 0; background: transparent; box-shadow: none;"' : '';
                const timeStyle = isDocCard ? 'style="margin-top: 10px; margin-right: 5px;"' : '';

                const html = `
                    <div class="msg-bubble-wrap ${side}">
                        <div class="msg-bubble" ${bubbleStyle}>
                            ${content}
                        </div>
                        <div class="msg-time" ${timeStyle}>${time}</div>
                    </div>
                `;
                messagesBox.insertAdjacentHTML('beforeend', html);
            }

            // Prevent scroll bounce
            function scrollToBottom() {
                setTimeout(() => {
                    messagesBox.scrollTop = messagesBox.scrollHeight;
                }, 10);
            }

            // Input Handling
            msgInput.addEventListener('input', () => {
                btnSend.disabled = msgInput.value.trim().length === 0;
            });

            msgInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !btnSend.disabled) sendChat();
            });

            btnSend.addEventListener('click', sendChat);

            function sendChat() {
                const text = msgInput.value.trim();
                if (!text || !currentChatFriendId) return;

                msgInput.value = '';
                btnSend.disabled = true;
                msgInput.focus();

                fetch('/chat/message', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({
                        receiver_id: currentChatFriendId,
                        message: text
                    })
                }).then(res => {
                    if (res.status === 403) {
                        document.getElementById('chat-blocked-state').style.display = 'block';
                        document.querySelector('.chat-input-area').style.display = 'none';
                        clearInterval(pollingInterval);
                        throw new Error('Blocked');
                    }
                    return res.json();
                }).then(res => {
                    if (res.success) {
                        // Clear empty state if it's the first message
                        if (messagesBox.innerHTML.includes('beginning of your chat')) messagesBox.innerHTML = '';

                        appendMessage(res.message.message, res.message.is_sender, res.message.time);
                        lastMessageId = Math.max(lastMessageId, res.message.id);
                        scrollToBottom();
                        setTimeout(loadFriends, 1000);
                    } else {
                        btnSend.disabled = false;
                        alert(res.message || "Failed to send message");
                    }
                }).catch(err => {
                    btnSend.disabled = false;
                });
            }

            // Send System Features (Focus, Invite, etc)
            window.sendSystemMessage = function (systemText) {
                if (!currentChatFriendId) return;

                fetch('/chat/message', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({
                        receiver_id: currentChatFriendId,
                        message: systemText
                    })
                }).then(r => r.json()).then(res => {
                    if (res.success) {
                        if (messagesBox.innerHTML.includes('beginning of your chat')) messagesBox.innerHTML = '';
                        appendMessage(res.message.message, res.message.is_sender, res.message.time);
                        lastMessageId = Math.max(lastMessageId, res.message.id);
                        scrollToBottom();
                        setTimeout(loadFriends, 1000);
                    }
                });
            };

            // Block User Feature
            window.blockUser = function () {
                if (!currentChatFriendId) return;

                if (!confirm("Are you sure you want to block this user? You won't be able to chat with them anymore.")) return;

                fetch('/chat/block', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ blocked_id: currentChatFriendId })
                }).then(r => r.json()).then(res => {
                    if (res.success) {
                        alert("User blocked successfully.");
                        // Close chat and refresh list
                        activeWrapper.style.display = 'none';
                        emptyState.style.display = 'flex';
                        currentChatFriendId = null;
                        if (pollingInterval) clearInterval(pollingInterval);
                        loadFriends();
                        loadDiscover();
                    } else {
                        alert(res.message || "Failed to block user.");
                    }
                });
            };

            // Escape HTML for XSS protection
            function escapeHtml(unsafe) {
                if (!unsafe) return '';
                return unsafe.toString()
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            // Execute Initial Loads (Placed here to avoid hoisting issues)
            loadFriends();
            loadRequests();
            // Share Notes Feature
            window.openShareNoteModal = function () {
                if (!currentChatFriendId) {
                    alert('Please select a chat first.');
                    return;
                }

                // Show modal using jQuery for better compatibility
                $('#shareNoteModal').modal('show');

                const list = document.getElementById('share-note-list');
                list.innerHTML = `<div class="text-center p-3 text-muted"><div class="spinner-border spinner-border-sm" style="color:var(--primary-accent); margin-bottom:10px;"></div><br>Loading notes...</div>`;

                // Fetch user notes
                fetch('/api/notes')
                    .then(res => res.json())
                    .then(notes => {
                        list.innerHTML = '';

                        if (notes.length === 0) {
                            list.innerHTML = `<div class="text-center p-3 text-muted"><i class="fa fa-file-text-o" style="font-size: 24px; opacity: 0.3; margin-bottom: 10px; display: block;"></i> You have no notes to share.<br>Create one in the Library!</div>`;
                            return;
                        }

                        notes.forEach(note => {
                            const item = document.createElement('a');
                            item.href = '#';
                            item.className = 'list-group-item list-group-item-action';
                            item.style = 'background: transparent; color: var(--text-main); border-bottom: 1px solid var(--border-color); padding: 12px 15px;';

                            // Truncate content for preview (strip HTML tags if any)
                            let rawContent = note.content || '';
                            let plainText = rawContent.replace(/<[^>]+>/g, '');
                            let preview = plainText.length > 60 ? plainText.substring(0, 60) + '...' : plainText;
                            if (!preview) preview = 'No content';

                            item.innerHTML = `
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div style="overflow: hidden;">
                                        <h6 style="font-weight: 600; margin: 0 0 4px 0; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text-main);">${escapeHtml(note.title)}</h6>
                                        <p style="font-size: 12px; margin: 0; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${escapeHtml(preview)}</p>
                                    </div>
                                    <button class="btn btn-sm btn-primary" style="background: rgba(92,124,250,0.1); color: #5C7CFA; border: none; font-weight: 600; font-size: 12px; border-radius: 6px; flex-shrink: 0; margin-left: 10px;">Share</button>
                                </div>
                            `;

                            item.onclick = (e) => {
                                e.preventDefault();

                                // Generate formatted chat message
                                let safeTitle = note.title.replace(/:/g, ''); // prevent parsing errors
                                let safePreview = plainText.substring(0, 100).replace(/:/g, '');
                                let noteMsg = `[FILE_SHARE:${note.id}:${safeTitle}:${safePreview}]`;

                                sendSystemMessage(noteMsg);
                                $('#shareNoteModal').modal('hide');
                            };

                            list.appendChild(item);
                        });
                    }).catch(err => {
                        list.innerHTML = `<div class="text-center p-3 text-danger">Failed to load notes. Please try again.</div>`;
                    });
            };

            // Profile View Feature
            window.showUserProfile = function (userId) {
                if (!userId) return;

                $('#userProfileModal').modal('show');

                // Reset modal to loading state
                document.getElementById('profile-modal-avatar').innerText = '?';
                document.getElementById('profile-modal-name').innerText = 'Loading...';
                document.getElementById('profile-modal-title').innerText = 'Please wait';
                document.getElementById('profile-modal-streak').innerText = '-';
                document.getElementById('profile-modal-xp').innerText = '-';
                document.getElementById('profile-modal-level').innerText = '-';
                document.getElementById('profile-modal-time').innerText = '-';
                document.getElementById('profile-modal-rewards').innerHTML = '';

                fetch(`/chat/profile/${userId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const p = data.profile;
                            document.getElementById('profile-modal-avatar').innerText = p.name.charAt(0).toUpperCase();
                            document.getElementById('profile-modal-name').innerText = escapeHtml(p.name);
                            document.getElementById('profile-modal-title').innerText = escapeHtml(p.title);
                            document.getElementById('profile-modal-streak').innerText = p.current_streak;
                            document.getElementById('profile-modal-xp').innerText = p.xp;
                            document.getElementById('profile-modal-level').innerText = p.level;
                            document.getElementById('profile-modal-time').innerText = p.study_time;

                            let rewardsHtml = '';
                            if (p.badges && p.badges.length > 0) {
                                p.badges.forEach(b => {
                                    rewardsHtml += `<span class="badge-pill"><i class="fa fa-certificate"></i> ${escapeHtml(b)}</span>`;
                                });
                            }
                            if (p.stickers && p.stickers.length > 0) {
                                p.stickers.forEach(s => {
                                    rewardsHtml += `<span class="sticker-pill"><i class="fa fa-star"></i> ${escapeHtml(s)}</span>`;
                                });
                            }

                            if (!rewardsHtml) {
                                rewardsHtml = '<p class="text-muted small">No achievements yet. Keep studying!</p>';
                            }

                            document.getElementById('profile-modal-rewards').innerHTML = rewardsHtml;
                        } else {
                            alert(data.message || 'Failed to load profile.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('An error occurred while loading the profile.');
                    });
            };

            // Save Shared Note Logic
            window.handleSaveNote = function (noteId) {
                if (!confirm("Would you like to save this shared note to your library?")) return;

                fetch('/chat/save-shared-note', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ note_id: noteId })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                        } else {
                            alert(data.message || "Failed to save note.");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("An error occurred while saving the note.");
                    });
            };

        });
    </script>
</body>

</html>