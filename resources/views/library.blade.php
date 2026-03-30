<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Mantra Student Library">
    <meta name="author" content="Mantra">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <title>Mantra | Library</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>
    <div id="global-loader"
        style="position:fixed; top:0; left:0; width:100%; height:100%; background-color:#1a1b21; z-index:9999; display:flex; align-items:center; justify-content:center; transition:opacity 0.5s ease;">
        <div class="spinner-border text-primary" role="status"
            style="width: 3rem; height: 3rem; color: #5C7CFA !important;">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

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
                    <a href="{{ route('dashboard') }}">

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
                    <a href="{{ route('chat') }}">
                        <i class="fa fa-comments"></i>
                        <span class="links_name">Chat</span>
                    </a>
                    <span class="tooltip">Chat</span>
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
                        <img src="{{ asset('images/author-01.png') }}" alt="profileImg">
                        <div class="name_job">
                            <div class="name" id="user-name">Student</div>
                            <div class="job">Learner</div>
                        </div>
                    </div>
                    <i class="fa fa-sign-out" id="logout-btn"></i>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <section class="home-section">
            <div class="top-bar">
                <div class="text">Your Knowledge Vault 📚</div>
                <div class="top-tools">
                    <div class="search-box">
                        <i class="fa fa-search"></i>
                        <input type="text" placeholder="Search resources...">
                    </div>
                </div>
            </div>

            <div class="main-content">
                <div class="library-tabs mb-4">
                    <button class="tab-btn active" data-target="all">All</button>
                    <button class="tab-btn" data-target="notes">Notes</button>
                    <button class="tab-btn" data-target="channels">Channels</button>
                </div>

                <!-- Recently Visited Channels -->
                @if(isset($recentChannels) && $recentChannels->count() > 0)
                    <div class="resource-section mb-4" id="recent-channels-section">
                        <h3 class="section-title">Recently Visited <i class="fa fa-history"></i></h3>
                        <div class="row">
                            @foreach($recentChannels as $channel)
                                <div class="col-md-3 mb-4">
                                    <div class="resource-card channel" data-url="{{ $channel->channel_url }}"
                                        data-name="{{ $channel->channel_name }}">
                                        <div class="thumb" style="height: 140px; overflow: hidden; border-radius: 8px 8px 0 0;">
                                            <img src="{{ $channel->channel_image }}" alt="{{ $channel->channel_name }}"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <div class="res-info" style="padding: 15px;">
                                            <h4 style="font-size: 16px; margin-bottom: 5px;">{{ $channel->channel_name }}</h4>
                                            <p style="font-size: 12px; color: #aaa; margin-bottom: 0;">
                                                Visited {{ $channel->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <button class="btn-open" style="width: 100%; border-radius: 0 0 8px 8px;">Visit
                                            Again</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Recent Watch History REMOVED -->



                <!-- Channel Viewer (Hidden by default) -->
                <div id="channel-viewer" style="display: none; height: calc(100vh - 150px); flex-direction: column;">
                    <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 15px;">
                        <button id="back-to-library" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i>
                            Back</button>
                        <h4 id="viewer-title" style="color: white; margin: 0;">Channel Name</h4>
                        <a id="viewer-external-link" href="#" target="_blank" class="btn btn-outline-warning btn-sm"
                            style="margin-left: auto;">
                            <i class="fa fa-external-link"></i> Open in New Tab
                        </a>
                    </div>
                    <div
                        style="flex: 1; background: #1a1b21; border-radius: 8px; overflow: hidden; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <img src="{{ asset('images/mantra.png') }}"
                            style="height: 60px; margin-bottom: 20px; opacity: 0.8;">
                        <p style="color:white; margin-bottom:20px; font-size: 16px;">Unable to access channel within
                            Mantra.
                        </p>
                        <a id="viewer-error-link" href="#" target="_blank" class="btn btn-warning"
                            style="padding: 10px 25px;">
                            <i class="fa fa-external-link"></i> Open in New Tab
                        </a>
                    </div>
                </div>

                <!-- Channels Grid -->
                <div id="channels-section" class="resource-section" style="display: none;">
                    <h3 class="section-title">YouTube Channels</h3>
                    <div class="row">
                        @if(isset($channels))
                            @foreach($channels as $channel)
                                <div class="col-md-3 mb-4">
                                    <div class="resource-card channel" data-url="{{ $channel['url'] }}"
                                        data-name="{{ $channel['name'] }}" style="cursor: pointer; overflow: hidden;">
                                        <div class="thumb" style="height: 140px; overflow: hidden; border-radius: 8px 8px 0 0;">
                                            <img src="{{ asset($channel['image']) }}" alt="{{ $channel['name'] }}"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <div class="res-info" style="padding: 15px;">
                                            <h4 style="font-size: 16px; margin-bottom: 5px;">{{ $channel['name'] }}</h4>
                                            <p style="font-size: 12px; color: #aaa; margin-bottom: 0;">
                                                {{ Str::limit($channel['desc'], 50) }}
                                            </p>
                                        </div>
                                        <button class="btn-open" style="width: 100%; border-radius: 0 0 8px 8px;">Visit</button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Resources Grid -->
                <div id="notes-section" class="resource-section">
                    <h3 class="section-title">Latest & Uploaded Notes</h3>
                    <div class="row">
                        @forelse($notes as $note)
                            <div class="col-md-3 mb-4">
                                <div class="resource-card note">
                                    <div class="res-icon">
                                        @if($note->file_path)
                                            <i class="fa fa-file-pdf-o" style="color: #FF5252;"></i>
                                        @else
                                            <i class="fa fa-file-text-o" style="color: #4CAF50;"></i>
                                        @endif
                                    </div>
                                    <div class="res-info">
                                        <h4 title="{{ $note->title }}">{{ Str::limit($note->title, 20) }}</h4>
                                        <p>{{ $note->file_path ? 'Uploaded File' : 'Self-Written' }}</p>
                                        <span class="date">{{ $note->updated_at->format('M d, Y') }}</span>
                                    </div>

                                    @if($note->file_path)
                                        <a href="{{ asset('storage/' . $note->file_path) }}" target="_blank" class="btn-open"
                                            style="text-align:center; padding-top:8px;">Open File</a>
                                    @else
                                        <button class="btn-open" onclick="alert('Open Note: {{ $note->id }}')">Read
                                            Note</button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted">No notes found. Create one in the Dashboard!</p>
                            </div>
                        @endforelse
                    </div>
                </div>


            </div>
        </section>
    </div>


    <!-- Scripts -->
    <script src="{{ asset('jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/music-player.js') }}?v={{ time() }}"></script>
    <script>
        // Hide global loader
        $(document).ready(function () {
            const loader = document.getElementById('global-loader');
            if (loader) {
                setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => loader.style.display = 'none', 500);
                }, 500);
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

            // TABS LOGIC
            $('.tab-btn').click(function () {
                $('.tab-btn').removeClass('active');
                $(this).addClass('active');

                const target = $(this).data('target');

                if (target === 'all') {
                    $('.resource-section').show();
                } else if (target === 'channels') {
                    $('.resource-section').hide();
                    $('#channels-section').show();
                } else if (target === 'notes') {
                    $('.resource-section').hide();
                    $('#notes-section').show();
                } else {
                    // Placeholders
                    $('.resource-section').hide();
                }

                // Ensure viewer is hidden
                $('#channel-viewer').hide();
                $('.history-section').show(); // Show history? Maybe hide if focusing on specific tab.
            });

            // OPEN CHANNEL VIEWER
            $('.resource-card.channel').click(function () {
                const url = $(this).data('url');
                const name = $(this).data('name');
                const image = $(this).find('img').attr('src');

                // TRACK VISIT
                $.ajax({
                    url: '/library/track-visit',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        channel_name: name,
                        channel_url: url,
                        channel_image: image
                    },
                    success: function (res) {
                        console.log('Visit tracked');
                    },
                    error: function (err) {
                        console.error('Tracking error', err);
                    }
                });

                $('#viewer-title').text(name);

                $('#viewer-title').text(name);
                $('#viewer-external-link').attr('href', url);
                $('#viewer-error-link').attr('href', url);

                // Hide Grid, Show Viewer
                $('.resource-section').hide();
                $('.history-section').hide();
                $('.library-tabs').hide();

                $('#channel-viewer').css('display', 'flex');

                $('#channel-viewer').css('display', 'flex');

                // Set the link for the note button (No iframe loading)
                $('#viewer-error-link').attr('href', url);
            });

            // BACK BUTTON
            $('#back-to-library').click(function () {
                $('#channel-viewer').hide();
                $('#viewer-iframe').attr('src', ''); // Stop video
                // Reset link
                $('#viewer-external-link').attr('href', '#');

                $('.library-tabs').show();
                $('.history-section').show();
                $('.tab-btn.active').click(); // Restore previous view
            });

        });
    </script>

    <!-- Lo-Fi Player Widget -->
    <div class="music-widget closed">
        <div class="music-controls">
            <div class="music-art">
                <img src="{{ asset('images/mantra.png') }}" alt="Album Art">
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