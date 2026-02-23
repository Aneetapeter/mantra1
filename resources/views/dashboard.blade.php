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

    <title>Mantra | Student Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v=2">
</head>

<body>
    <div id="global-loader"
        style="position:fixed; top:0; left:0; width:100%; height:100%; background-color:#1a1b21; z-index:9999; display:flex; align-items:center; justify-content:center; transition:opacity 0.5s ease;">
        <div class="spinner-border text-primary" role="status"
            style="width: 3rem; height: 3rem; color: #5C7CFA !important;">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    @auth {{-- Only logged-in users can see this page --}}
        <div class="dashboard-container">
            <!-- Sidebar -->
            <nav class="sidebar">
                <div class="logo-details">
                    <img src="{{ asset('images/mantra.png') }}" alt="Mantra Logo"
                        style="width:36px; height:36px; object-fit:contain; border-radius:8px; flex-shrink:0;">
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
                        <a href="{{ route('dashboard') }}" class="active">
                            <i class="fa fa-th-large"></i>
                            <span class="links_name">Dashboard</span>
                        </a>
                        <span class="tooltip">Dashboard</span>
                    </li>
                    <li>
                        <a href="{{ route('library') }}" class="active">
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
                        <a href="{{ route('settings') }}">
                            <i class="fa fa-cog"></i>
                            <span class="links_name">Settings</span>
                        </a>
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
                                <div class="name" id="user-name">{{ Auth::user()->name }}</div>
                                <div class="job">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <!-- Logout -->
                        <a href="#" id="logout-btn"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            title="Sign Out">
                            <i class="fa fa-sign-out" style="font-size:20px;"></i>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <section class="home-section">
                <!-- Top Header -->
                <div class="top-bar">
                    <div class="text">Study. Focus. Achieve</div>
                    <div class="top-tools">
                        <div class="search-box">
                            <i class="fa fa-search"></i>
                            <input type="text" placeholder="Search...">
                        </div>
                        <div class="icon-wrap">
                            <i class="fa fa-bell"></i>
                            <span class="badge">2</span>
                        </div>
                    </div>
                </div>

                <!-- Welcome Banner & Mood Selector -->
                <div class="main-content">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="welcome-card">
                                <div class="welcome-text">
                                    <h2 id="greeting"><span id="greeting-text">Good Morning</span>,
                                        {{ Auth::user()->name }}!
                                    </h2>

                                    <p>Ready to crush your goals today?</p>
                                    <div class="mood-selector">
                                        <span>I'm in the mood for:</span>
                                        <div class="mood-buttons">
                                            <button class="mood-btn focus" onclick="setMood('focus')">🔥 Focus Mode</button>
                                            <button class="mood-btn chill" onclick="setMood('chill')">🎧 Chill Mode</button>
                                            <button class="mood-btn quick" onclick="setMood('quick')">⚡ Quick Study</button>
                                            <button class="mood-btn fun" onclick="setMood('fun')">🎮 Fun Learning</button>

                                        </div>
                                    </div>
                                </div>
                                <div class="welcome-img">
                                    <img src="images/mantra.png" alt="Welcome">
                                </div>
                            </div>

                            <!-- Smart Study Gen with Upload -->
                            <div class="upload-section mt-4 mb-4">
                                <div class="mb-3">
                                    <h3>🚀 Smart Study Gen</h3>
                                    <p class="text-muted">Upload your notes (PDF/Text) to generate an instant quiz.</p>
                                </div>

                                <div class="upload-area" id="drop-area">
                                    <i class="fa fa-cloud-upload upload-icon"></i>
                                    <h3>Drag & Drop your materials here</h3>
                                    <p>or <span class="highlight" style="color:var(--accent-teal); font-weight:600;">Browse
                                            Files</span></p>
                                    <input type="file" id="notes-file" style="display: none;"
                                        accept=".txt,.pdf,.doc,.docx,.xls,.xlsx">
                                    <p id="file-name" class="text-muted mt-2" style="font-size: 14px; display:none;"></p>
                                    <div
                                        style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap; margin-top:8px;">
                                        <button class="btn-primary-small" id="btn-generate-quiz" style="display:none;">⚡
                                            Generate Quiz</button>
                                        <button class="btn-primary-small" id="btn-open-in-notes"
                                            style="display:none; background:linear-gradient(135deg,#00b894,#00cec9);">📝
                                            Open in Notes</button>
                                    </div>
                                </div>

                                {{-- ===== SAVE TO NOTES MODAL ===== --}}
                                <div id="save-to-notes-overlay"
                                    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.75); z-index:9999; align-items:center; justify-content:center;">
                                    <div
                                        style="background:#1e2130; border-radius:16px; padding:32px; max-width:520px; width:94%; border:1px solid rgba(255,255,255,0.12); box-shadow:0 20px 60px rgba(0,0,0,0.6); max-height:90vh; overflow-y:auto;">
                                        <h4 style="margin:0 0 4px; color:#fff; font-size:18px;">📄 Open in My Notes?</h4>
                                        <p style="color:rgba(255,255,255,0.55); font-size:13px; margin-bottom:20px;">We
                                            extracted the content below. Save it as an editable note?</p>

                                        <div style="margin-bottom:14px;">
                                            <label
                                                style="color:rgba(255,255,255,0.7); font-size:11px; text-transform:uppercase; letter-spacing:.5px; display:block; margin-bottom:6px;">Note
                                                Title</label>
                                            <input type="text" id="stn-title" placeholder="Note title..."
                                                style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid rgba(255,255,255,0.15); background:rgba(255,255,255,0.06); color:#fff; font-size:14px; outline:none; box-sizing:border-box;">
                                        </div>

                                        <div style="margin-bottom:14px;">
                                            <label
                                                style="color:rgba(255,255,255,0.7); font-size:11px; text-transform:uppercase; letter-spacing:.5px; display:block; margin-bottom:6px;">Content
                                                Preview <span
                                                    style="color:rgba(255,255,255,0.35); text-transform:none; font-size:10px;">(editable)</span></label>
                                            <textarea id="stn-preview" rows="7" placeholder="(No text extracted)"
                                                style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid rgba(255,255,255,0.15); background:rgba(255,255,255,0.04); color:#ccc; font-size:13px; outline:none; box-sizing:border-box; resize:vertical; font-family:inherit; line-height:1.5;"></textarea>
                                        </div>

                                        <div style="margin-bottom:22px;">
                                            <label
                                                style="color:rgba(255,255,255,0.7); font-size:11px; text-transform:uppercase; letter-spacing:.5px; display:block; margin-bottom:6px;">Save
                                                to Folder</label>
                                            <select id="stn-folder-select"
                                                style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid rgba(255,255,255,0.15); background:#1e2130; color:#fff; font-size:14px; outline:none; box-sizing:border-box;">
                                                <option value="">📂 No Folder (General)</option>
                                            </select>
                                        </div>

                                        <div id="stn-progress"
                                            style="display:none; margin-bottom:14px; text-align:center; color:#00b894; font-size:13px;">
                                            <i class="fa fa-spinner fa-spin"></i> Saving &amp; uploading...
                                        </div>

                                        <div style="display:flex; gap:12px; justify-content:flex-end;">
                                            <button id="stn-cancel"
                                                style="padding:10px 20px; border-radius:8px; border:1px solid rgba(255,255,255,0.2); background:transparent; color:rgba(255,255,255,0.7); cursor:pointer; font-size:14px;">Cancel</button>
                                            <button id="stn-save"
                                                style="padding:10px 24px; border-radius:8px; border:none; background:linear-gradient(135deg,#00b894,#00cec9); color:#fff; font-weight:600; cursor:pointer; font-size:14px;">✅
                                                Save &amp; Open Note</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quiz Container (Hidden initially) -->
                                <div id="quiz-container"
                                    style="display: none; margin-top: 30px; background: rgba(255,255,255,0.05); padding: 20px; border-radius: 15px;">
                                    <h3 id="quiz-title">Generated Quiz</h3>
                                    <div id="quiz-questions"></div>
                                    <button class="btn btn-success mt-3" id="btn-submit-quiz">Submit Answers</button>
                                    <div id="quiz-result" class="mt-3" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card urgent" id="todays-focus-card">
                                <h3>Today's Focus
                                    <span style="font-size:11px; font-weight:400; color:var(--cal-muted); margin-left:6px;">
                                        {{ \Carbon\Carbon::today()->format('M d') }}
                                    </span>
                                </h3>

                                {{-- Dynamic events from calendar --}}
                                <div id="todays-tasks-list">
                                    @forelse($todayEvents as $event)
                                        @php
                                            $typeColors = [
                                                'study' => '#5C7CFA',
                                                'exam' => '#E53935',
                                                'meeting' => '#8E6CEF',
                                                'birthday' => '#FBC02D',
                                                'review' => '#FBC02D',
                                            ];
                                            $color = $typeColors[$event->type] ?? '#5C7CFA';
                                            $done = $event->status === 'completed';
                                        @endphp
                                        <div class="daily-task today-event-item {{ $done ? 'task-done' : '' }}"
                                            data-id="{{ $event->id }}"
                                            style="cursor:pointer; align-items:flex-start; gap:10px; padding:6px 0;">
                                            <i class="fa {{ $done ? 'fa-check-circle checked' : 'fa-circle-thin' }} task-toggle-icon"
                                                style="margin-top:2px; color:{{ $done ? '#4CAF50' : 'inherit' }}; flex-shrink:0;"></i>
                                            <div style="flex:1; min-width:0;">
                                                <span class="task-title"
                                                    style="{{ $done ? 'text-decoration:line-through; opacity:0.5;' : '' }} display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                    {{ $event->title }}
                                                </span>
                                                <span
                                                    style="font-size:10px; padding:2px 6px; border-radius:4px; background:{{ $color }}22; color:{{ $color }}; font-weight:500; text-transform:capitalize; margin-top:2px; display:inline-block;">
                                                    {{ $event->type }}
                                                    @if($event->priority && $event->priority !== 'medium')
                                                        · {{ $event->priority }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @empty
                                        <div style="text-align:center; padding:18px 0; color:var(--cal-muted); font-size:13px;">
                                            <i class="fa fa-calendar-o"
                                                style="font-size:24px; margin-bottom:8px; display:block; opacity:0.4;"></i>
                                            No tasks for today.<br>
                                            <span style="font-size:12px;">Add one in your calendar →</span>
                                        </div>
                                    @endforelse
                                </div>

                                <a href="{{ route('study') }}" class="btn-primary-small"
                                    style="display:block; text-align:center; margin-top:12px; text-decoration:none;">
                                    📅 View Calendar
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Row -->
                    <div class="row mt-4">
                        <div class="col-md-3 mb-3">
                            <div class="stat-mini-card">
                                <div class="icon-box warning">
                                    <i class="fa fa-fire"></i>
                                </div>
                                <div class="text-box">
                                    <h4 id="streak-display">{{ Auth::user()->current_streak }}
                                        {{ Str::plural('Day', Auth::user()->current_streak) }}
                                    </h4>
                                    <span>Study Streak</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-mini-card">
                                <div class="icon-box info">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <div class="text-box">
                                    <h4 id="study-time-display">
                                        {{ floor(Auth::user()->total_study_seconds / 3600) }}h
                                        {{ floor((Auth::user()->total_study_seconds % 3600) / 60) }}m
                                    </h4>
                                    <span>Total Study Time</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-mini-card">
                                <div class="icon-box success">
                                    <i class="fa fa-trophy"></i>
                                </div>
                                <div class="text-box">
                                    <h4 id="xp-display">{{ Auth::user()->xp }} XP</h4>
                                    <span id="level-display">Level {{ Auth::user()->level }}
                                        {{ Auth::user()->title }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-mini-card">
                                <div class="icon-box danger">
                                    <i class="fa fa-bolt"></i>
                                </div>
                                <div class="text-box">
                                    <h4>85%</h4>
                                    <span>Avg. Quiz Score</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Courses & Activities -->
                    <div class="row mt-4">
                        <!-- My Courses -->
                        <!-- My Notes -->
                        <div class="col-md-8 mb-4">
                            <div class="content-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3>My Notes</h3>
                                    <a href="{{ route('study') }}" class="btn btn-sm btn-primary"
                                        style="background: var(--accent-primary); border: none;">+ New Note</a>
                                </div>
                                <div id="dashboard-notes-list" class="list-group list-group-flush"
                                    style="min-height: 200px;">
                                    <div class="text-center p-3 text-muted">Loading notes...</div>
                                </div>
                                <div class="card-footer text-center bg-transparent border-0">
                                    <a href="{{ route('study') }}" class="text-muted small">View all in Study Space</a>
                                </div>
                            </div>
                        </div>

                        <!-- Study Tools / Side Widget -->
                        <div class="col-md-4">
                            <div class="content-card">
                                <div class="card-header">
                                    <h3>Quick Study</h3>
                                </div>
                                <div class="timer-widget">
                                    <div class="timer-display">25:00</div>
                                    <div class="timer-controls">
                                        <button class="t-btn start">Start</button>
                                        <button class="t-btn break">Break</button>
                                    </div>
                                </div>
                                <hr style="border-color: rgba(255,255,255,0.1);">
                                <div class="tools-grid">
                                    <div class="tool-item tool-map">
                                        <span class="emoji">🧠</span>
                                        <span>Mind Map</span>
                                    </div>
                                    <div class="tool-item tool-flashcards">
                                        <span class="emoji">🃏</span>
                                        <span>Flashcards</span>
                                    </div>
                                    <div class="tool-item tool-notes">
                                        <span class="emoji">📝</span>
                                        <span>Notes</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>



                </div>
            </section>
        </div>

        </div>

        <!-- Smart Notes Section (Notion Style) -->


        <!-- Study Mode Modal -->
        <div id="study-modal" class="study-modal">
            <div class="study-content">
                <div class="study-header">
                    <div class="header-left">
                        <h2>📝 Smart Note Taker</h2>
                        <span class="file-name" id="study-filename">Biology_Chapter_1.pdf</span>
                    </div>
                    <div class="header-tools">
                        <button class="tool-btn active" title="Select"><i class="fa fa-mouse-pointer"></i></button>
                        <button class="tool-btn" title="Highlight"><i class="fa fa-paint-brush"></i></button>
                        <button class="tool-btn" title="Pen"><i class="fa fa-pencil"></i></button>
                        <button class="tool-btn" title="Add Text"><i class="fa fa-font"></i></button>
                        <button class="tool-btn" title="Eraser"><i class="fa fa-eraser"></i></button>
                        <div class="separator"></div>
                        <button class="tool-btn close-modal" id="close-study-btn"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="study-body">
                    <div class="source-view">
                        <!-- Placeholder for PDF/Content -->
                        <div class="pdf-placeholder">
                            <i class="fa fa-file-pdf-o"></i>
                            <p>Source Material Preview</p>
                            <span class="text-muted">(PDF Rendering Mockup)</span>
                        </div>
                    </div>
                    <div class="note-view">
                        <div class="note-toolbar">
                            <button class="format-btn"><i class="fa fa-bold"></i></button>
                            <button class="format-btn"><i class="fa fa-italic"></i></button>
                            <button class="format-btn"><i class="fa fa-list-ul"></i></button>
                            <span class="ai-badge">✨ AI Suggestions Active</span>
                        </div>
                        <textarea class="note-area"
                            placeholder="Start taking notes here... AI will auto-suggest topics!"></textarea>
                    </div>
                </div>
            </div>
        </div>

        </div>


    @else
        <script>         window.location.href = "{{ route('login') }}";
        </script>
    @endauth
    <!-- Greeting Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hour = new Date().getHours();
            let greeting = 'Good Evening';
            if (hour >= 5 && hour < 12) {
                greeting = 'Good Morning';
            } else if (hour >= 12 && hour < 17) {
                greeting = 'Good Afternoon';
            }
            if (document.getElementById('greeting-text')) {
                document.getElementById('greeting-text').innerText = greeting;
            }
        });
    </script>
    <!-- Persistent Audio Player (Hidden Iframe) -->
    <iframe id="audio-frame" src="/audio-player.html" style="display:none; width:0; height:0; border:none;"
        allow="autoplay"></iframe>

    <!-- Lo-Fi Player Widget (Moved to Root) -->
    <div class="music-widget closed">
        <div class="music-controls">
            <div class="music-art">
                <img src="images/mantra.png" alt="Album Art">
            </div>
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
            <div class="equalizer">
                <span></span><span></span><span></span>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- Inject User Data for JS -->
    <script>
        window.userStickers = @json(Auth::user()->stickers ?? []);
        window.userLevel = {{ Auth::user()->level }};
    </script>
    <script src="{{ asset('js/dashboard.js') }}?v=5"></script>
    <script>
        // Apply saved appearance prefs from localStorage
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