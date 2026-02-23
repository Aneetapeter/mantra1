$(document).ready(function () {

    // ========== GLOBAL LOADER ==========
    // Hide loader on load
    const loader = document.getElementById('global-loader');
    if (loader) {
        setTimeout(() => {
            loader.style.opacity = '0';
            setTimeout(() => loader.style.display = 'none', 500);
        }, 500);
    }

    // Intercept links for loader effect
    $('a').on('click', function (e) {
        const url = $(this).attr('href');
        if (url && !url.startsWith('#') && !url.startsWith('javascript') && $(this).attr('target') !== '_blank') {
            e.preventDefault();
            if (loader) {
                $(loader).css('display', 'flex').css('opacity', '1');
            }
            setTimeout(() => window.location.href = url, 300);
        }
    });

    // ========== BROWSER NOTIFICATIONS ==========
    // Request permission on page load
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }

    // Helper function to show browser notification
    window.showBrowserNotification = function (title, body, icon = '/images/icon.png') {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(title, {
                body: body,
                icon: icon,
                badge: icon,
                tag: 'mantra-reminder'
            });
        }
    };

    // 1. Sidebar Toggle
    let sidebar = document.querySelector(".sidebar");
    let closeBtn = document.querySelector("#btn");
    let searchBtn = document.querySelector(".fa-search");

    closeBtn.addEventListener("click", () => {
        sidebar.classList.toggle("open");
        menuBtnChange();
    });

    // optional: open sidebar when searching
    // searchBtn.addEventListener("click", ()=>{
    //   sidebar.classList.toggle("open");
    //   menuBtnChange();
    // });

    function menuBtnChange() {
        if (sidebar.classList.contains("open")) {
            closeBtn.classList.replace("fa-bars", "fa-align-right");
        } else {
            closeBtn.classList.replace("fa-align-right", "fa-bars");
        }
    }

    // 2. Personalization (Load User)


    // 3. Logout
    $('#logout-btn').on('click', function () {
        document.getElementById('logout-form').submit();
    });

    // 4. Mood Selector Logic (Premium Toast)
    function showToast(message, type = 'info') {
        // Remove existing
        $('.toast-notification').remove();

        // Create new
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-info-circle';
        $('body').append(`
            <div class="toast-notification">
                <i class="fa ${icon}"></i>
                <span>${message}</span>
            </div>
        `);

        // Animate In
        setTimeout(() => $('.toast-notification').addClass('show'), 10);

        // Animate Out
        setTimeout(() => {
            $('.toast-notification').removeClass('show');
            setTimeout(() => $('.toast-notification').remove(), 400);
        }, 3000);
    }

    window.setMood = function (mood) {
        console.log("Setting mood to:", mood);
        let msg = "";
        switch (mood) {
            case 'focus': msg = "Target Acquired! 🎯 Distractions filtered."; break;
            case 'chill': msg = "Vibes only. 🎧 Lo-fi playlist loaded."; break;
            case 'quick': msg = "Speed run! ⚡ Let's do this."; break;
            case 'fun': msg = "Gamified! 🎮 XP earning enabled."; break;
        }
        showToast(msg);

        // Switch Music
        if (typeof window.MusicPlayer !== 'undefined') {
            window.MusicPlayer.loadPlaylist(mood);
        } else {
            console.error("MusicPlayer not found!");
        }
    };

    // 5. Simple Timer Logic
    let timeLeft = 25 * 60; // 25 minutes
    let timerId = null;

    function updateTimerDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        $('.timer-display').text(`${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);
    }

    $('.t-btn.start').click(function () {
        if (timerId) return; // already running
        $(this).text('Pause');
        timerId = setInterval(async () => {
            timeLeft--;
            updateTimerDisplay();
            if (timeLeft <= 0) {
                clearInterval(timerId);
                timerId = null;
                showToast("Session Complete! Streak Updated! 🔥", "success");

                // Call API to update streak & time & XP
                const res = await API.post('study/complete', { duration: 1500 });
                if (res && res.success) {
                    $('#streak-display').text(`${res.current_streak} ${res.current_streak === 1 ? 'Day' : 'Days'}`);
                    if (res.readable_time) {
                        $('#study-time-display').text(res.readable_time);
                    }

                    // Update XP & Level
                    $('#xp-display').text(`${res.xp} XP`);
                    $('#level-display').text(`Level ${res.level} ${res.title}`);

                    let msg = res.message; // "Streak Updated! +50 XP"
                    showToast(msg, "success");

                    // Handle Reward
                    if (res.reward) {
                        setTimeout(() => {
                            if (res.reward.type === 'sticker') {
                                showToast(`🎁 Unlocked New Sticker: ${res.reward.name.toUpperCase()}! Check your Notes!`, "fun");
                            } else if (res.reward.type === 'badge') {
                                showToast(`🏆 Unlocked Badge: ${res.reward.name}!`, "success");
                            }
                        }, 2500);
                    }
                }

                timeLeft = 25 * 60; // reset
                $(this).text('Start');
            }
        }, 1000);
    });

    $('.t-btn.break').click(function () {
        clearInterval(timerId);
        timerId = null;
        timeLeft = 5 * 60; // 5 min break
        updateTimerDisplay();
        $('.t-btn.start').text('Start');
    });

    // 6. Smart Upload Logic (With AI Integration)
    const dropArea = document.getElementById('drop-area');
    const fileElem = document.getElementById('notes-file');

    let activeAIContext = 'notes'; // Default

    if (dropArea) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropArea.classList.add('highlight');
        }

        function unhighlight(e) {
            dropArea.classList.remove('highlight');
        }

        dropArea.addEventListener('drop', handleDrop, false);
        dropArea.addEventListener('click', () => fileElem.click());
        fileElem.addEventListener('change', handleFiles);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles({ target: { files: files } });
        }

        async function handleFiles(e) {
            const files = e.target.files;
            if (files.length > 0) {
                const file = files[0];

                // Show uploading state
                $('.upload-icon').hide();
                $('.upload-area h3').text(activeAIContext === 'quiz' ? "Generating Quiz..." : "Analyzing file...");
                $('.upload-area p').hide();
                $('.upload-status').fadeIn().html('<i class="fa fa-spinner fa-spin"></i> Processing...');

                try {
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('type', activeAIContext);

                    let response;
                    if (activeAIContext === 'quiz') {
                        response = await fetch('/api/quiz/generate', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            body: formData
                        }).then(res => res.json());

                        if (response.success) {
                            renderQuiz(response);
                            $('.upload-section').hide();
                            $('#quiz-container').fadeIn();
                            showToast("Quiz Generated! 🚀", "success");
                        } else {
                            throw new Error(response.message || "Quiz generation failed");
                        }

                    } else {
                        // ===== STEP 1: Extract text only (no note saved yet) =====
                        response = await fetch('/app/smart-notes/extract', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            body: formData
                        }).then(res => res.json());

                        if (response.success) {
                            // Reset upload area back to normal
                            $('.upload-icon').show();
                            $('.upload-area h3').text("Drag & Drop your materials here");
                            $('.upload-area p').show();
                            $('.upload-status').hide();
                            // Reset file input so user can re-upload same file
                            fileElem.value = '';

                            // ===== STEP 2: Show modal pre-filled =====
                            $('#stn-title').val(response.title || '');
                            $('#stn-preview').val(response.text || '');

                            // Populate folder dropdown
                            const folderSelect = $('#stn-folder-select');
                            folderSelect.empty().append('<option value="">📂 No Folder (General)</option>');
                            const folders = window.allFolders || [];
                            if (folders.length > 0) {
                                folders.forEach(f => {
                                    folderSelect.append(`<option value="${f.id}">📁 ${f.name}</option>`);
                                });
                            } else {
                                // Try fetching from API in case SmartNotes hasn't loaded yet
                                try {
                                    const data = await fetch('/app/smart-notes').then(r => r.json());
                                    if (data.folders) {
                                        data.folders.forEach(f => {
                                            folderSelect.append(`<option value="${f.id}">📁 ${f.name}</option>`);
                                        });
                                        window.allFolders = data.folders;
                                    }
                                } catch (e) { /* ignore */ }
                            }

                            // Show the modal
                            $('#save-to-notes-overlay').css('display', 'flex');
                            $('#stn-title').focus();

                            // ===== STEP 3: Handle Cancel =====
                            $('#stn-cancel').off('click').on('click', function () {
                                $('#save-to-notes-overlay').hide();
                                showToast("Cancelled. File not saved.", "info");
                            });

                            // ===== STEP 4: Handle Save =====
                            $('#stn-save').off('click').on('click', async function () {
                                const title = $('#stn-title').val().trim() || 'Untitled Note';
                                const previewText = $('#stn-preview').val().trim();
                                const folderId = $('#stn-folder-select').val() || null;

                                // Build Editor.js JSON content
                                const blocks = [];
                                if (previewText) {
                                    // Split into chunks of ~500 chars for multiple paragraphs
                                    const chunks = previewText.match(/.{1,800}(\s|$)/gs) || [previewText];
                                    chunks.forEach(chunk => {
                                        if (chunk.trim()) {
                                            blocks.push({ type: 'paragraph', data: { text: chunk.trim() } });
                                        }
                                    });
                                }
                                const editorContent = JSON.stringify({
                                    time: Date.now(),
                                    blocks: blocks.length > 0 ? blocks : [{ type: 'paragraph', data: { text: '' } }]
                                });

                                // Show spinner
                                $('#stn-progress').show();
                                $('#stn-save').prop('disabled', true).text('Saving...');

                                try {
                                    const saveRes = await fetch('/app/smart-notes', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        body: JSON.stringify({ title, content: editorContent, folder_id: folderId })
                                    }).then(r => r.json());

                                    if (saveRes && saveRes.id) {
                                        showToast("Note saved! Opening in Study Space... 📝", "success");
                                        setTimeout(() => {
                                            window.location.href = '/study?note_id=' + saveRes.id;
                                        }, 800);
                                    } else {
                                        throw new Error("Failed to save note");
                                    }
                                } catch (saveErr) {
                                    console.error("Save error", saveErr);
                                    $('#stn-progress').hide();
                                    $('#stn-save').prop('disabled', false).html('✅ Save &amp; Open Note');
                                    showToast("Error saving note. Please try again.", "error");
                                }
                            });

                        } else {
                            throw new Error(response.message || "Text extraction failed");
                        }
                    }

                } catch (err) {
                    console.error("AI Error", err);
                    showToast(err.message || "Failed to process file", "error");
                    $('.upload-area h3').text("Upload Failed");
                    $('.upload-status').text("Error");
                    // Reset UI
                    setTimeout(() => {
                        $('.upload-icon').show();
                        $('.upload-area h3').text("Drag & Drop your materials here");
                        $('.upload-area p').show();
                        $('.upload-status').hide();
                    }, 2000);
                }
            }
        }
    }

    // Render AI Logic
    function renderAIResult(data, filename) {
        $('#study-filename').text(filename);

        if (activeAIContext === 'notes') {
            $('.note-view').show();
            const htmlContent = data.content
                .replace(/^# (.*$)/gim, '<h1>$1</h1>')
                .replace(/^## (.*$)/gim, '<h2>$1</h2>')
                .replace(/\*\*(.*)\*\*/gim, '<b>$1</b>')
                .replace(/\n/gim, '<br>');

            if ($('.note-editor').length === 0) {
                // Sticker Toolbar
                let stickerHtml = '';
                const stickers = window.userStickers || []; // ['cat', 'coffee', 'star']

                if (stickers.length > 0) {
                    stickerHtml = '<div class="sticker-toolbar" style="padding:10px; background:rgba(255,255,255,0.05); border-bottom:1px solid rgba(255,255,255,0.1); display:flex; gap:10px;">';
                    stickers.forEach(s => {
                        stickerHtml += `<img src="/images/stickers/${s}.png" class="sticker-btn" data-sticker="${s}" style="width:30px; height:30px; cursor:pointer; transition:transform 0.2s;">`;
                    });
                    stickerHtml += '</div>';
                } else {
                    stickerHtml = '<div class="sticker-toolbar" style="padding:10px; font-size:12px; color:#888;">Complete study sessions to unlock stickers!</div>';
                }

                $('.note-view').html(stickerHtml + '<div class="note-editor" contenteditable="true" style="width:100%; height:100%; outline:none; color:#eee; padding:20px;"></div>');

                // Bind Sticker Click
                $(document).on('click', '.sticker-btn', function () {
                    const stickerName = $(this).data('sticker');
                    const img = `<img src="/images/stickers/${stickerName}.png" style="width:100px; display:block; margin:10px 0;">`;
                    document.execCommand('insertHTML', false, img);
                });
            }
            $('.note-editor').html(htmlContent);

            $('.source-view').show().css('flex', '1').html('<div class="pdf-placeholder"><i class="fa fa-file-text-o"></i><p>Source Extracted</p></div>');
            $('.header-left h2').text("📝 AI Smart Notes");

        } else if (activeAIContext === 'map') {
            $('.note-view').hide();
            $('.header-left h2').text("🧠 AI Mind Map");
            $('.source-view').css('flex', '1').html(`
                <div class="pdf-placeholder" style="border:1px solid #444; background:#222;">
                    <div style="font-family:monospace; color:#0f0; white-space:pre; text-align:left; padding:20px;">${data.content}</div>
                    <p>(Mermaid.js diagram would render here)</p>
                </div>
            `);

        } else if (activeAIContext === 'flashcards') {
            $('.note-view').hide();
            $('.header-left h2').text("🃏 AI Flashcards");

            let cardsHtml = '';
            data.content.forEach((card, i) => {
                cardsHtml += `
                    <div class="flashcard" style="background:var(--card-color); border:1px solid rgba(255,255,255,0.1); padding:20px; margin-bottom:10px; border-radius:10px;">
                        <div style="font-weight:bold; color:var(--accent-gold); margin-bottom:5px;">Q: ${card.q}</div>
                        <div style="color:#ddd;">A: ${card.a}</div>
                    </div>
                `;
            });
            $('.source-view').css('flex', '1').html(`<div style="padding:20px; overflow-y:auto; height:100%;">${cardsHtml}</div>`);

        } else if (activeAIContext === 'quiz') {
            $('.note-view').hide();
            $('.header-left h2').text("✅ AI Quiz");

            let quizHtml = '<div style="padding:20px; overflow-y:auto; height:100%;">';
            data.content.forEach((q, i) => {
                const optionsHtml = q.options.map((opt, idx) => `
                    <div class="quiz-option" data-correct="${idx === q.correct}" style="padding:10px; margin-top:5px; background:rgba(255,255,255,0.05); cursor:pointer; border-radius:5px;">
                        <span style="font-weight:bold; margin-right:10px;">${String.fromCharCode(65 + idx)}.</span> ${opt}
                    </div>
                `).join('');

                quizHtml += `
                    <div class="quiz-item" style="margin-bottom:20px;">
                        <h4 style="color:#fff; margin-bottom:10px;">${i + 1}. ${q.question}</h4>
                        ${optionsHtml}
                    </div>
                `;
            });
            quizHtml += '</div>';

            $('.source-view').css('flex', '1').html(quizHtml);

            // Add click interaction for quiz
            $('.quiz-option').off().click(function () {
                const isCorrect = $(this).data('correct') === true;
                if (isCorrect) {
                    $(this).css('background', 'rgba(0, 255, 0, 0.2)').css('border', '1px solid #0f0');
                } else {
                    $(this).css('background', 'rgba(255, 0, 0, 0.2)').css('border', '1px solid #f00');
                }
            });
        }
    }

    // 7. Note Taking Modal Logic
    $('#close-study-btn').click(function () {
        $('#study-modal').removeClass('active');
    });

    // Tool toggling (Sets Context)
    $('.header-tools .tool-btn').not('.close-modal').click(function () {
        $('.header-tools .tool-btn').removeClass('active');
        $(this).addClass('active');
    });

    // Open Notes from Grid
    $('.tool-notes').click(function () {
        activeAIContext = 'notes';
        triggerUpload();
    });

    // Open Mind Map
    $('.tool-map').click(function () {
        activeAIContext = 'map';
        triggerUpload();
    });

    // Open Flashcards
    $('.tool-flashcards').click(function () {
        activeAIContext = 'flashcards';
        triggerUpload();
    });

    // Open Quiz
    $('.tool-quiz').click(function () {
        activeAIContext = 'quiz';
        triggerUpload();
    });

    function triggerUpload() {
        // Scroll to upload area or highlight it
        $('html, body').animate({
            scrollTop: $(".upload-section").offset().top - 100
        }, 500);

        $('.upload-area').addClass('highlight');
        setTimeout(() => $('.upload-area').removeClass('highlight'), 1000);
        showToast(`Selected: ${activeAIContext.toUpperCase()}. Drop a file to generate!`, "info");
    }

    // 8. Music Widget Logic (Direct Audio with LocalStorage persistence)
    window.MusicPlayer = {
        audio: new Audio(),
        playlists: {
            focus: Array.from({ length: 10 }, (_, i) => `/music/focus/${i + 1}.mp3`),
            chill: Array.from({ length: 10 }, (_, i) => `/music/chill/${i + 1}.mp3`),
            quick: Array.from({ length: 10 }, (_, i) => `/music/quick/${i + 1}.mp3`),
            fun: []
        },
        currentPlaylist: [],
        currentMood: 'chill',
        currentIndex: 0,
        isPlaying: false,
        isDragging: false,

        init: function () {
            const self = this;

            // Restore state from localStorage
            this.restoreState();

            // Audio event listeners
            this.audio.addEventListener('ended', () => this.next());
            this.audio.addEventListener('timeupdate', () => {
                if (!this.isDragging) this.updateProgress();
                this.saveState(); // Save state periodically
            });
            this.audio.addEventListener('play', () => {
                this.isPlaying = true;
                this.updatePlayBtn();
            });
            this.audio.addEventListener('pause', () => {
                this.isPlaying = false;
                this.updatePlayBtn();
            });

            // Bind Controls - use event delegation for better reliability
            $(document).on('click', '.m-btn.play-pause', function (e) {
                e.preventDefault();
                e.stopPropagation();
                self.togglePlay();
            });
            $(document).on('click', '.m-btn:has(.fa-step-forward)', function (e) {
                e.preventDefault();
                self.next();
            });
            $(document).on('click', '.m-btn:has(.fa-step-backward)', function (e) {
                e.preventDefault();
                self.prev();
            });

            // Toggle Widget
            $(document).on('click', '.music-toggle', function (e) {
                e.stopPropagation();
                $('.music-widget').toggleClass('closed');
            });

            // Click Seek
            $(document).on('click', '.progress-bar-music', (e) => {
                if ($(e.target).hasClass('seek-thumb')) return;
                const bar = $('.progress-bar-music');
                const rect = bar[0].getBoundingClientRect();
                const percent = (e.clientX - rect.left) / bar.width();
                if (this.audio.duration) {
                    this.audio.currentTime = percent * this.audio.duration;
                }
            });

            // Drag Seek Logic
            const self2 = this;
            $(document).on('mousedown', '.seek-thumb', function (e) {
                self2.isDragging = true;
                e.stopPropagation();
            });

            $(document).on('mousemove', function (e) {
                if (self2.isDragging) {
                    const bar = $('.progress-bar-music');
                    if (bar.length === 0) return;
                    const barOffset = bar.offset().left;
                    const barWidth = bar.width();
                    let relativeX = e.pageX - barOffset;
                    if (relativeX < 0) relativeX = 0;
                    if (relativeX > barWidth) relativeX = barWidth;
                    const percent = (relativeX / barWidth) * 100;
                    $('.progress-bar-music .fill').css('width', `${percent}%`);
                    $('.seek-thumb').css('left', `${percent}%`);
                }
            });

            $(document).on('mouseup', function (e) {
                if (self2.isDragging) {
                    self2.isDragging = false;
                    const bar = $('.progress-bar-music');
                    if (bar.length === 0) return;
                    const barOffset = bar.offset().left;
                    const barWidth = bar.width();
                    let relativeX = e.pageX - barOffset;
                    if (relativeX < 0) relativeX = 0;
                    if (relativeX > barWidth) relativeX = barWidth;
                    const percent = relativeX / barWidth;
                    if (self2.audio.duration) {
                        self2.audio.currentTime = percent * self2.audio.duration;
                    }
                }
            });

            // Save state before page unload
            window.addEventListener('beforeunload', () => this.saveState());

            // Update UI
            this.updatePlayBtn();
            this.updateMoodDisplay();
        },

        restoreState: function () {
            try {
                const saved = localStorage.getItem('mantra_music_state');
                if (saved) {
                    const state = JSON.parse(saved);
                    this.currentMood = state.mood || 'chill';
                    this.currentIndex = state.trackIndex || 0;
                    this.currentPlaylist = this.playlists[this.currentMood] || this.playlists.chill;

                    if (this.currentPlaylist[this.currentIndex]) {
                        this.audio.src = this.currentPlaylist[this.currentIndex];

                        // Wait for audio to be ready then seek
                        this.audio.addEventListener('loadedmetadata', () => {
                            if (state.currentTime && state.currentTime > 0) {
                                this.audio.currentTime = state.currentTime;
                            }
                            // Auto-resume if was playing
                            if (state.isPlaying) {
                                this.audio.play().catch(e => {
                                    console.log('Autoplay blocked, click play to start');
                                });
                            }
                        }, { once: true });

                        this.audio.load();
                    }
                } else {
                    // Default setup
                    this.currentPlaylist = this.playlists.chill;
                    this.loadTrack(0);
                }
            } catch (e) {
                console.error('Error restoring music state:', e);
                this.currentPlaylist = this.playlists.chill;
                this.loadTrack(0);
            }
        },

        saveState: function () {
            try {
                localStorage.setItem('mantra_music_state', JSON.stringify({
                    mood: this.currentMood,
                    trackIndex: this.currentIndex,
                    currentTime: this.audio.currentTime || 0,
                    isPlaying: this.isPlaying
                }));
            } catch (e) {
                console.error('Error saving music state:', e);
            }
        },

        loadPlaylist: function (mood) {
            if (mood === 'fun' && this.playlists.fun.length === 0) {
                mood = 'chill';
            }
            this.currentMood = mood;
            this.currentPlaylist = this.playlists[mood] || this.playlists.chill;
            this.currentIndex = 0;
            this.loadTrack(this.currentIndex);
            this.play();
            this.updateMoodDisplay();
        },

        loadTrack: function (index) {
            if (!this.currentPlaylist[index]) return;
            this.audio.src = this.currentPlaylist[index];
            this.audio.load();
        },

        play: function () {
            this.audio.play().then(() => {
                this.isPlaying = true;
                this.updatePlayBtn();
                this.saveState();
            }).catch(e => {
                console.error("Play error:", e);
                showToast("Click play to start music", "info");
            });
        },

        pause: function () {
            this.audio.pause();
            this.isPlaying = false;
            this.updatePlayBtn();
            this.saveState();
        },

        togglePlay: function () {
            console.log('Toggle play, current state:', this.isPlaying);
            if (this.isPlaying) {
                this.pause();
            } else {
                this.play();
            }
        },

        next: function () {
            this.currentIndex = (this.currentIndex + 1) % this.currentPlaylist.length;
            this.loadTrack(this.currentIndex);
            this.play();
        },

        prev: function () {
            this.currentIndex = (this.currentIndex - 1 + this.currentPlaylist.length) % this.currentPlaylist.length;
            this.loadTrack(this.currentIndex);
            this.play();
        },

        updatePlayBtn: function () {
            const icon = $('.m-btn.play-pause i');
            if (this.isPlaying) {
                icon.removeClass('fa-play').addClass('fa-pause');
            } else {
                icon.removeClass('fa-pause').addClass('fa-play');
            }
        },

        updateProgress: function () {
            if (this.audio.duration) {
                const percent = (this.audio.currentTime / this.audio.duration) * 100;
                $('.progress-bar-music .fill').css('width', `${percent}%`);
                $('.seek-thumb').css('left', `${percent}%`);
            }
        },

        updateMoodDisplay: function () {
            let moodName = this.currentMood.charAt(0).toUpperCase() + this.currentMood.slice(1);
            $('.track-name').text(`${moodName} Vibes 🎵`);
            $('.artist-name').text('Mantra Radio');
        }
    };

    window.MusicPlayer.init();

    // 12. Search & Filter Logic
    // Global Search
    $('.search-box input').on('keyup', function () {
        const term = $(this).val().toLowerCase();

        // Local search for resource cards only
        $('.resource-card').each(function () {
            const title = $(this).find('h4').text().toLowerCase();
            const parentCol = $(this).parent();
            if (title.includes(term)) {
                parentCol.show();
            } else {
                parentCol.hide();
            }
        });
    });

    // Library Tabs Logic
    $('.tab-btn').click(function () {
        $('.tab-btn').removeClass('active');
        $(this).addClass('active');

        const filter = $(this).text().toLowerCase();

        $('.resource-card').each(function () {
            const card = $(this);
            const parentCol = card.parent();

            if (filter === 'all') {
                parentCol.show();
            } else if (filter === 'notes' && card.hasClass('note')) {
                parentCol.show();
            } else if (filter === 'mind maps' && card.hasClass('map')) {
                parentCol.show();
            } else if (filter === 'flashcards' && card.hasClass('card-deck')) {
                parentCol.show();
            } else if (filter === 'videos' && card.hasClass('video')) {
                parentCol.show();
            } else {
                parentCol.hide();
            }
        });
    });

    // --- QUIZ LOGIC ---

    // --- QUIZ LOGIC MERGED ABOVE ---

    let currentQuizId = null;

    function renderQuiz(data) {
        currentQuizId = data.quiz_id;
        $('#quiz-title').text(`Quiz: ${data.title}`);

        let html = '';
        data.questions.forEach((q, index) => {
            html += `
                <div class="quiz-question mb-4" data-answer="${q.answer}">
                    <p><strong>Q${index + 1}: ${q.question}</strong></p>
                    <div class="quiz-options">
                        ${q.options.map(opt => `
                            <label class="d-block p-2 border rounded mb-2" style="cursor:pointer; transition:0.2s;">
                                <input type="radio" name="q${index}" value="${opt}" class="mr-2"> ${opt}
                            </label>
                        `).join('')}
                    </div>
                </div>
            `;
        });

        $('#quiz-questions').html(html);
        $('#quiz-result').hide();
        $('#btn-submit-quiz').show();
    }

    // Handle Quiz Submission
    $('#btn-submit-quiz').click(async function () {
        let score = 0;
        let total = $('.quiz-question').length;
        let correctCount = 0;

        $('.quiz-question').each(function (index) {
            const correctAnswer = $(this).data('answer');
            const selected = $(this).find(`input[name="q${index}"]:checked`).val();

            // UI Feedback
            $(this).find('label').css('background', 'transparent');
            if (selected === correctAnswer) {
                correctCount++;
                $(this).find(`input[name="q${index}"]:checked`).parent().css('background', 'rgba(40, 167, 69, 0.2)');
            } else if (selected) {
                $(this).find(`input[name="q${index}"]:checked`).parent().css('background', 'rgba(220, 53, 69, 0.2)');
            }
        });

        score = Math.round((correctCount / total) * 100);

        // Send to Backend
        const res = await API.post('quiz/complete', {
            quiz_id: currentQuizId,
            score: score
        });

        if (res && res.success) {
            // Update Stats
            animateValue("quiz-score-display", parseInt($('#quiz-score-display').text()) || 0, res.avg_score, 1000);
            $('#xp-display').text(`${res.new_xp} XP`);

            // Show Result
            $('#quiz-result').html(`
                <div class="alert alert-success">
                    <h4>Score: ${score}%</h4>
                    <p>${res.message}</p>
                </div>
                <button class="btn btn-secondary" onclick="location.reload()">Take Another Quiz</button>
            `).fadeIn();

            $('#btn-submit-quiz').hide();
            showToast(`Quiz Complete! +${res.xp_gained} XP`, "success");
        }
    });

    // Helper: Animate Value
    function animateValue(id, start, end, duration) {
        if (start === end) return;
        let range = end - start;
        let current = start;
        let increment = end > start ? 1 : -1;
        let stepTime = Math.abs(Math.floor(duration / range));
        let obj = document.getElementById(id);
        let timer = setInterval(function () {
            current += increment;
            obj.innerHTML = current + "%";
            if (current == end) {
                clearInterval(timer);
            }
        }, stepTime);
    }

    // --- END QUIZ LOGIC ---

    // ========== TODAY'S FOCUS — TOGGLE COMPLETION ==========
    $(document).on('click', '.today-event-item, .today-event-item .task-toggle-icon', function (e) {
        // Allow clicking anywhere on the item or just the icon
        const $item = $(this).hasClass('today-event-item') ? $(this) : $(this).closest('.today-event-item');
        const eventId = $item.data('id');
        if (!eventId) return;

        const isDone = $item.hasClass('task-done');

        // Persist to backend first
        fetch(`/api/events/${eventId}/toggle`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            }
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const nowDone = data.status === 'completed';

                    if (nowDone) {
                        // Move to completed area
                        showToast('✅ Task completed!', 'success');
                        $item.addClass('task-done');
                        $item.find('.task-toggle-icon').removeClass('fa-circle-thin').addClass('fa-check-circle checked').css('color', '#4CAF50');
                        $item.find('.task-title').css({ 'text-decoration': 'line-through', 'opacity': '0.5' });

                        // Ensure completed section container exists
                        const $focusContainer = $item.closest('.today-events-list, .today-tasks-list, #today-events-container').length
                            ? $item.closest('.today-events-list, .today-tasks-list, #today-events-container')
                            : $item.parent();

                        let $doneSection = $focusContainer.siblings('.done-events-section');
                        if ($doneSection.length === 0) {
                            $focusContainer.after(`
                            <div class="done-events-section" style="margin-top:10px;">
                                <div style="font-size:11px; color:#4CAF50; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px; display:flex; align-items:center; gap:5px; cursor:pointer;" class="done-section-toggle">
                                    <i class="fa fa-check-circle"></i> Completed
                                    <i class="fa fa-chevron-down" style="font-size:9px; margin-left:auto;"></i>
                                </div>
                                <div class="done-section-items"></div>
                            </div>
                        `);
                            $doneSection = $focusContainer.siblings('.done-events-section');

                            // Collapse/expand toggle
                            $doneSection.on('click', '.done-section-toggle', function () {
                                $doneSection.find('.done-section-items').slideToggle(200);
                                $doneSection.find('.fa-chevron-down, .fa-chevron-up').toggleClass('fa-chevron-down fa-chevron-up');
                            });
                        }

                        // Clone item and move to done section
                        const $clone = $item.clone();
                        $item.fadeOut(300, function () { $(this).remove(); });
                        $clone.appendTo($doneSection.find('.done-section-items')).hide().fadeIn(300);

                    } else {
                        // Was completed, now pending — put back in active list and remove from done section
                        showToast('↩️ Task marked pending', 'success');
                        $item.removeClass('task-done');
                        $item.find('.task-toggle-icon').removeClass('fa-check-circle checked').addClass('fa-circle-thin').css('color', 'inherit');
                        $item.find('.task-title').css({ 'text-decoration': '', 'opacity': '' });

                        // Move back to active list
                        const $doneSection = $item.closest('.done-events-section');
                        if ($doneSection.length) {
                            const $activeList = $doneSection.siblings('.today-events-list, .today-tasks-list, #today-events-container').first();
                            const $clone = $item.clone();
                            $item.fadeOut(300, function () {
                                $(this).remove();
                                if ($doneSection.find('.done-section-items .today-event-item').length === 0) {
                                    $doneSection.remove();
                                }
                            });
                            $clone.prependTo($activeList.length ? $activeList : $doneSection.parent()).hide().fadeIn(300);
                        }
                    }
                }
            })
            .catch(err => {
                console.error('Toggle error:', err);
                showToast('Failed to update task', 'error');
            });
    });
    // ========== END TODAY'S FOCUS ==========

    // 13. API & Data Persistence
    const API = {
        baseUrl: '/api',

        // Get CSRF token from meta tag
        getToken: function () {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        },

        get: async function (endpoint) {
            try {
                const res = await fetch(`${this.baseUrl}/${endpoint}`);
                return await res.json();
            } catch (err) {
                console.error(`API Get Error (${endpoint}):`, err);
                return [];
            }
        },

        post: async function (endpoint, data) {
            try {
                const res = await fetch(`${this.baseUrl}/${endpoint}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.getToken()
                    },
                    body: JSON.stringify(data)
                });
                return await res.json();
            } catch (err) {
                console.error(`API Post Error (${endpoint}):`, err);
            }
        },

        put: async function (endpoint, id, data) {
            try {
                await fetch(`${this.baseUrl}/${endpoint}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.getToken()
                    },
                    body: JSON.stringify(data)
                });
            } catch (err) {
                console.error(`API Put Error (${endpoint}):`, err);
            }
        },

        patch: async function (endpoint, id, action, data = {}) {
            try {
                const res = await fetch(`${this.baseUrl}/${endpoint}/${id}/${action}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.getToken()
                    },
                    body: JSON.stringify(data)
                });
                return await res.json();
            } catch (err) {
                console.error(`API Patch Error (${endpoint}):`, err);
            }
        },

        delete: async function (endpoint, id) {
            try {
                await fetch(`${this.baseUrl}/${endpoint}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': this.getToken()
                    }
                });
                return true;
            } catch (err) {
                console.error(`API Delete Error (${endpoint}):`, err);
                return false;
            }
        }
    };


    // 15. To-Do List CRUD (Async)
    async function renderTodos() {
        const todos = await API.get('todos');
        const listContainer = $('.todo-list');
        listContainer.empty();

        const pending = todos.filter(t => !t.completed);
        const done = todos.filter(t => t.completed);

        // ---- Active tasks ----
        if (pending.length === 0) {
            listContainer.append('<p style="font-size:12px; color:var(--text-muted); text-align:center; padding:8px 0;">No pending tasks 🎉</p>');
        }
        pending.forEach(todo => {
            listContainer.append(`
                <div class="todo-item" data-id="${todo.id}">
                    <div class="todo-check">
                        <i class="fa fa-check"></i>
                    </div>
                    <span class="todo-text">${todo.text}</span>
                    <i class="fa fa-trash delete-todo" style="margin-left:auto; cursor:pointer; color:var(--accent-red); opacity:0.6;"></i>
                </div>
            `);
        });

        // ---- Completed section ----
        if (done.length > 0) {
            listContainer.append(`
                <div class="todo-section-divider" style="margin:10px 0 4px; font-size:11px; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; display:flex; align-items:center; gap:6px; cursor:pointer;" id="completed-section-toggle">
                    <i class="fa fa-check-circle" style="color:#4CAF50;"></i> Completed (${done.length})
                    <i class="fa fa-chevron-down" id="completed-chevron" style="margin-left:auto; font-size:9px;"></i>
                </div>
                <div id="completed-todo-list" style="display:none;">
                </div>
            `);
            const doneContainer = $('#completed-todo-list');
            done.forEach(todo => {
                doneContainer.append(`
                    <div class="todo-item task-done" data-id="${todo.id}" style="opacity:0.6;">
                        <div class="todo-check checked">
                            <i class="fa fa-check"></i>
                        </div>
                        <span class="todo-text" style="text-decoration:line-through;">${todo.text}</span>
                        <i class="fa fa-trash delete-todo" style="margin-left:auto; cursor:pointer; color:var(--accent-red); opacity:0.6;"></i>
                    </div>
                `);
            });

            // Toggle collapse/expand
            $(document).off('click', '#completed-section-toggle').on('click', '#completed-section-toggle', function () {
                $('#completed-todo-list').slideToggle(200);
                $('#completed-chevron').toggleClass('fa-chevron-down fa-chevron-up');
            });
        }
    }

    if ($('.todo-list').length) renderTodos();

    $('#add-todo-btn').click(async function () {

        const input = $('#new-todo-input');
        const text = input.val().trim();
        if (text) {
            await API.post('todos', { id: Date.now(), text: text, completed: false });
            input.val('');
            renderTodos();
        }
    });

    // Toggle Todo
    $(document).on('click', '.todo-check', async function () {
        const id = $(this).parent().data('id');
        const todos = await API.get('todos');
        const todo = todos.find(t => t.id == id);
        if (todo) {
            await API.put('todos', id, { completed: !todo.completed });
            renderTodos();
        }
    });

    // Delete Todo
    $(document).on('click', '.delete-todo', async function () {
        const id = $(this).parent().data('id');
        await API.delete('todos', id);
        renderTodos();
    });

    // 16. Notion-Style Calendar Logic (Async)
    const CalendarApp = {
        currentDate: new Date(),
        selectedDate: null,
        currentView: 'month', // 'month' or 'week'
        activeFilters: ['exam', 'study', 'birthday', 'meeting', 'review'],
        editingEventId: null, // Track if we are editing
        eventsCache: [], // Shared cache for all views

        // Load events from API into shared cache
        loadEvents: async function () {
            try {
                const events = await API.get('events');
                this.eventsCache = Array.isArray(events) ? events : [];
            } catch (e) {
                console.error('Failed to load events', e);
                this.eventsCache = [];
            }
            return this.eventsCache;
        },

        // Helper: Convert Hex to RGBA
        hexToRgba: function (hex, alpha) {
            let r = 0, g = 0, b = 0;
            // 3 digits
            if (hex.length == 4) {
                r = "0x" + hex[1] + hex[1];
                g = "0x" + hex[2] + hex[2];
                b = "0x" + hex[3] + hex[3];
                // 6 digits
            } else if (hex.length == 7) {
                r = "0x" + hex[1] + hex[2];
                g = "0x" + hex[3] + hex[4];
                b = "0x" + hex[5] + hex[6];
            }
            return "rgba(" + +r + "," + +g + "," + +b + "," + alpha + ")";
        },

        init: function () {
            if ($('#main-calendar-grid').length === 0) return;

            // Inject Date Jumper UI if not present
            if ($('#date-jumper').length === 0) {
                $('.cal-title').css('position', 'relative').append(`
                    <div id="date-jumper" style="display:none; position:absolute; top:100%; left:0; background:var(--card-color); padding:10px; border:1px solid rgba(255,255,255,0.1); border-radius:8px; z-index:100; box-shadow:0 5px 15px rgba(0,0,0,0.3); display: flex; gap: 5px;">
                        <select id="jump-month" class="form-control-dark" style="padding: 5px; font-size: 14px; width: 100px;">
                            ${Array.from({ length: 12 }, (_, i) => `<option value="${i}">${new Date(0, i).toLocaleString('default', { month: 'short' })}</option>`).join('')}
                        </select>
                        <input type="number" id="jump-year" class="form-control-dark" style="padding: 5px; font-size: 14px; width: 70px;" placeholder="Year">
                        <button id="jump-btn" class="btn-primary-small" style="padding: 5px 10px;">Go</button>
                    </div>
                `);

                // Add Delete Btn to Modal
                if ($('#delete-event-btn').length === 0) {
                    $('#save-event-btn').before('<button id="delete-event-btn" class="btn-text" style="color:var(--accent-red); margin-right:auto; display:none;">Delete</button>');
                    $('#save-event-btn').parent().css('justify-content', 'space-between'); // Adjust flex
                }
            }

            this.render();
            this.renderMini();
            this.renderSidebarTasks();
            this.renderInbox(); // Load inbox
            this.bindEvents(); // Call bindEvents
        },

        bindEvents: function () {
            const self = this;

            // ================= SELECTION MODE =================
            // Toggle Selection Mode
            $('#toggle-selection-mode').off().on('click', function () {
                self.toggleSelectionMode();
            });

            // Single Click on Event (Selection or Edit)
            $(document).off('click', '.event-block, .week-event, .task-mini-item').on('click', '.event-block, .week-event, .task-mini-item', function (e) {
                e.stopPropagation();
                const id = $(this).data('id');
                if (self.isSelectionMode) {
                    self.toggleEventSelection(id, $(this));
                } else {
                    // Use cache first, fallback to API
                    const cachedEvt = self.eventsCache.find(x => x.id == id);
                    if (cachedEvt) {
                        self.openModal(cachedEvt);
                    } else {
                        API.get('events').then(events => {
                            self.eventsCache = Array.isArray(events) ? events : [];
                            const evt = self.eventsCache.find(x => x.id == id);
                            if (evt) self.openModal(evt);
                        });
                    }
                }
            });

            // Bulk Delete
            $('#bulk-delete-events').off().on('click', function () {
                if (self.selectedEventIds.size > 0) {
                    self.bulkDeleteEvents();
                }
            });

            // Action Bar Handlers
            $('#cancel-selection').off().click(() => self.exitSelectionMode());
            $('#delete-selection').off().click(() => self.deleteSelectedEvents());

            // Double Click to Enter Selection Mode (Desktop)
            $(document).on('dblclick', '.event-block, .week-event, .task-mini-item, .grid-cell, .week-day-column', function (e) {
                if ($(e.target).closest('.date-jumper').length) return;
                e.preventDefault();
                e.stopPropagation();
                self.enterSelectionMode();
            });

            // Long Press to Enter Selection Mode (Touch)
            let longPressTimer;
            $(document).on('touchstart', '.event-block, .week-event, .task-mini-item, .grid-cell, .week-day-column', function (e) {
                longPressTimer = setTimeout(() => {
                    self.enterSelectionMode();
                    if ($(this).data('id')) {
                        self.toggleEventSelection($(this).data('id'), $(this));
                    }
                }, 800);
            }).on('touchend touchmove', function () {
                clearTimeout(longPressTimer);
            });


            // ================= NAVIGATION =================
            // Navigation Buttons
            $('#main-prev, #mini-prev').off().click((e) => {
                e.preventDefault();
                self.navigate(-1);
            });

            $('#main-next, #mini-next').off().click((e) => {
                e.preventDefault();
                self.navigate(1);
            });

            $('#main-today').off().click((e) => {
                e.preventDefault();
                self.currentDate = new Date();
                if (self.currentView === 'week') {
                    self.renderWeek();
                } else if (self.currentView === 'day') {
                    self.renderDay();
                } else {
                    self.render();
                }
                self.renderMini();
            });

            // Date Jumper
            $('#calendar-picker-icon').off().click((e) => {
                e.stopPropagation();
                $('#jump-month').val(self.currentDate.getMonth());
                $('#jump-year').val(self.currentDate.getFullYear());
                $('#date-jumper').toggle();
            });

            $('#date-jumper').click((e) => e.stopPropagation());
            $(document).click(() => $('#date-jumper').hide());

            $('#jump-btn').off().click(() => {
                const m = parseInt($('#jump-month').val());
                const y = parseInt($('#jump-year').val());

                if (!isNaN(m) && !isNaN(y)) {
                    self.currentDate.setFullYear(y);
                    self.currentDate.setMonth(m);
                    if (self.currentView === 'year') {
                        // self.renderYear(); 
                        self.render();
                    } else if (self.currentView === 'month') {
                        self.render();
                    }
                    self.renderMini();
                    $('#date-jumper').hide();
                }
            });

            // View Toggle
            $('.view-btn').off().click(function () {
                const view = $(this).data('view');
                self.switchView(view);
            });

            // Filters
            $('.cal-filters input').on('change', function () {
                const type = $(this).parent().data('type');
                if ($(this).is(':checked')) {
                    if (!self.activeFilters.includes(type)) self.activeFilters.push(type);
                } else {
                    self.activeFilters = self.activeFilters.filter(f => f !== type);
                }
                self.render();
            });


            // ================= MODAL & EDITING =================
            // Open Modal (Click Grid Cell OR Event Block)
            $('#create-event-main').off().click(() => self.openModal(null));

            $(document).on('click', '.grid-cell', function (e) {
                if (e.target === this || $(e.target).hasClass('cell-date')) {
                    self.openModal(null, $(this).data('date'));
                }
            });

            // ===== WEEK & DAY VIEW: click empty slot to create event =====
            $(document).on('click', '.week-day-column', function (e) {
                // Ignore if the click was directly on an event block inside the column
                if ($(e.target).closest('.week-event').length) return;
                const dateStr = $(this).data('date');  // DD-MM-YYYY
                const hour = parseInt($(this).data('hour'));
                self.openModal(null, dateStr, isNaN(hour) ? null : hour);
            });

            $('#close-event-modal, #event-modal-overlay').click(function (e) {
                if (e.target === this || e.target.id === 'close-event-modal') $('#event-modal-overlay').fadeOut();
            });

            // Save (Create or Update)
            $('#save-event-btn').off().click(() => self.saveEvent());

            // Delete
            $('#delete-event-btn').off().click(() => self.deleteEvent());


            // ================= TASKS =================
            $('#quick-add-task').off().click(async () => {
                const text = prompt("New Task:");
                if (text) {
                    await API.post('todos', { id: Date.now(), text: text, completed: false });
                    self.renderSidebarTasks();
                }
            });
            $(document).on('click', '.mini-check', async function () {
                const id = $(this).parent().data('id');
                const todos = await API.get('todos');
                const t = todos.find(x => x.id == id);
                if (t) {
                    await API.put('todos', id, { completed: !t.completed });
                    self.renderSidebarTasks();
                }
            });


            // ================= DRAG AND DROP =================
            // Drag Start
            $(document).on('dragstart', '.event-block', function (e) {
                const eventId = $(this).data('id');
                const eventDate = $(this).data('date');
                // Check if dataTransfer exists
                if (e.originalEvent.dataTransfer) {
                    e.originalEvent.dataTransfer.setData('text/plain', JSON.stringify({ id: eventId, date: eventDate }));
                }
                $(this).addClass('dragging');
            });

            // Drag End
            $(document).on('dragend', '.event-block', function () {
                $(this).removeClass('dragging');
                $('.grid-cell').removeClass('drag-over');
            });

            // Drag Over (allow drop)
            $(document).on('dragover', '.grid-cell:not(.inactive)', function (e) {
                e.preventDefault();
                $(this).addClass('drag-over');
            });

            // Drag Leave
            $(document).on('dragleave', '.grid-cell', function () {
                $(this).removeClass('drag-over');
            });

            // Drop
            $(document).on('drop', '.grid-cell:not(.inactive)', async function (e) {
                e.preventDefault();
                $(this).removeClass('drag-over');

                const targetDate = $(this).data('date');
                if (!targetDate) return;

                try {
                    const data = JSON.parse(e.originalEvent.dataTransfer.getData('text/plain'));
                    const eventId = data.id;

                    if (eventId && targetDate !== data.date) {
                        const [d, m, y] = targetDate.split('-');
                        const apiDate = `${y}-${m}-${d}`;

                        await API.patch('events', eventId, 'date', { date: apiDate });
                        showToast('Event moved successfully!');
                        self.render();
                        self.renderMini();
                    }
                } catch (err) {
                    console.error('Drop error:', err);
                }
            });
        },

        render: async function () {
            try {
                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth();
                const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                $('#main-month-year').text(`${monthNames[month]} ${year}`).css('cursor', 'pointer').attr('title', 'Click to change month/year');

                const grid = $('#main-calendar-grid');
                grid.empty();

                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const prevMonthDays = new Date(year, month, 0).getDate();

                // Use shared events cache
                const allEvents = await this.loadEvents();

                const todayStr = this.formatDate(new Date());

                // 1. Prev Padding
                for (let i = firstDay - 1; i >= 0; i--) {
                    grid.append(`<div class="grid-cell inactive"><span class="cell-date">${prevMonthDays - i}</span></div>`);
                }

                // 2. Current Month
                for (let d = 1; d <= daysInMonth; d++) {
                    const date = new Date(year, month, d);
                    const dateStr = this.formatDate(date);

                    let cellClasses = 'grid-cell';
                    if (dateStr === todayStr) cellClasses += ' today';

                    // Safe Filter
                    const dayEvents = allEvents.filter(e => {
                        return e && e.date === dateStr && this.activeFilters.includes(e.type);
                    });

                    let eventsHtml = '';
                    dayEvents.forEach(evt => {
                        const statusClass = evt.status === 'completed' ? 'completed' : '';
                        const displayColor = evt.display_color || evt.color || '#5C7CFA';
                        const priorityLabel = evt.priority ? evt.priority.charAt(0).toUpperCase() : 'M';
                        const priorityClass = evt.priority || 'medium';
                        const checkedAttr = evt.status === 'completed' ? 'checked' : '';
                        eventsHtml += `<div class="event-block ${evt.type} ${statusClass}" draggable="true" data-id="${evt.id}" data-date="${dateStr}" style="border-left-color: ${displayColor}; background: ${displayColor}10;">
                            <div class="event-content">
                                <input type="checkbox" class="event-check" data-id="${evt.id}" ${checkedAttr} title="Mark as done">
                                <span class="event-title">${evt.title}</span>
                            </div>
                            <span class="priority-badge ${priorityClass}">${priorityLabel}</span>
                        </div>`;
                    });

                    grid.append(`
                        <div class="${cellClasses}" data-date="${dateStr}">
                            <span class="cell-date">${d}</span>
                            ${eventsHtml}
                        </div>
                    `);
                }

                // Click on Month Cell -> Switch to Day
                $('.grid-cell').click((e) => {
                    // Prevent if clicked on event
                    if ($(e.target).closest('.event-block').length) return;

                    const dateStr = $(e.currentTarget).data('date');
                    if (dateStr) {
                        const [d, m, y] = dateStr.split('-');
                        this.currentDate = new Date(y, m - 1, d);
                        this.switchView('day');
                    }
                });

                // Checkbox click handler — toggle completed status
                grid.on('click', '.event-check', async function (e) {
                    e.stopPropagation();
                    const checkbox = $(this);
                    const eventId = checkbox.data('id');
                    const block = checkbox.closest('.event-block');

                    try {
                        const res = await API.patch('events', eventId, 'toggle');
                        if (res && res.success) {
                            if (res.status === 'completed') {
                                // Remove from calendar — it's done!
                                block.fadeOut(400, function () { $(this).remove(); });
                                showToast('✅ Event marked complete and removed from calendar', 'success');
                                // Invalidate cache so next render excludes it
                                CalendarApp.eventsCache = CalendarApp.eventsCache.map(e => {
                                    if (e.id === eventId) e.status = 'completed';
                                    return e;
                                });
                            } else {
                                // Re-add (reload view to restore it)
                                block.addClass('pending');
                                checkbox.prop('checked', false);
                                CalendarApp.refreshCurrentView();
                            }
                        }
                    } catch (err) {
                        console.error('Toggle status failed', err);
                    }
                });

                // 3. Next Padding
                const totalCells = firstDay + daysInMonth;
                const remaining = (totalCells <= 35) ? 35 - totalCells : 42 - totalCells;
                for (let j = 1; j <= remaining; j++) {
                    grid.append(`<div class="grid-cell inactive"><span class="cell-date">${j}</span></div>`);
                }
            } catch (err) {
                console.error("Calendar Render Error:", err);
                $('#main-calendar-grid').html('<div style="color:white; padding:20px;">Error loading calendar. Please refresh.</div>');
            }
        },

        renderMini: async function () {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();
            const monthNamesShort = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            $('#mini-month-year').text(`${monthNamesShort[month]} ${year}`);

            const allEvents = await API.get('events');

            const miniGrid = $('#mini-grid');
            miniGrid.find('.mini-date, .mini-empty').remove();

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            for (let i = 0; i < firstDay; i++) miniGrid.append(`<div class="mini-empty"></div>`);
            const todayStr = this.formatDate(new Date());

            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = this.formatDate(new Date(year, month, d));
                let classes = 'mini-date';
                if (dateStr === todayStr) classes += ' active';
                if (allEvents.some(e => e.date === dateStr)) classes += ' has-event';
                miniGrid.append(`<div class="${classes}" data-date="${dateStr}">${d}</div>`);
            }
        },

        renderSidebarTasks: async function () {
            const tasks = await API.get('todos');
            const container = $('#sidebar-task-list');
            container.empty();

            // Only show pending (not completed) tasks in the inbox
            const pending = tasks.filter(t => !t.completed);

            if (pending.length === 0) {
                container.html('<p style="font-size:12px; color:var(--text-muted); text-align:center;">No pending tasks 🎉</p>');
            } else {
                pending.forEach(t => {
                    container.append(`
                        <div class="task-mini-item" data-id="${t.id}">
                            <div style="width: 14px; height: 14px; border: 1px solid var(--text-muted); border-radius: 4px; margin-right: 8px; display:flex; align-items:center; justify-content:center; cursor:pointer;" class="mini-check">
                            </div>
                            <span style="flex:1;">${t.text}</span>
                        </div>
                    `);
                });
            }

            // Show count of completed tasks (compact, no full list)
            const doneCount = tasks.filter(t => t.completed).length;
            if (doneCount > 0) {
                container.append(`
                    <div style="font-size:11px; color:#4CAF50; text-align:center; margin-top:8px; opacity:0.8;">
                        <i class="fa fa-check-circle"></i> ${doneCount} task${doneCount > 1 ? 's' : ''} completed today
                    </div>
                `);
            }
        },

        // Refresh whatever view is currently showing (used after status change)
        refreshCurrentView: async function () {
            await this.loadEvents();
            if (this.currentView === 'day') {
                this.renderDay();
            } else if (this.currentView === 'week') {
                this.renderWeek();
            } else {
                this.render();
            }
            this.renderMini();
        },

        // Navigate Calendar
        navigate: function (direction) {
            // direction: 1 (next) or -1 (prev)
            if (this.currentView === 'day') {
                this.currentDate.setDate(this.currentDate.getDate() + direction);
                this.renderDay();
            } else if (this.currentView === 'week') {
                // Move by 7 days
                this.currentDate.setDate(this.currentDate.getDate() + (direction * 7));
                this.renderWeek();
            } else {
                // Move by 1 month
                this.currentDate.setMonth(this.currentDate.getMonth() + direction);
                this.render();
            }
            this.renderMini();
        },



        // Helper: Parse Date (ISO or Local)
        parseEventDate: function (dateStr) {
            if (!dateStr) return null;
            return new Date(dateStr);
        },

        openModal: function (evtOrNull, dateToFill = null, hourToFill = null) {
            // Reset all fields
            $('#event-description').val('');
            $('#event-priority').val('medium');
            $('#event-reminder').val('none');
            $('#event-recurrence').val('');
            $('#event-time-input').val('');
            $('#event-end-time-input').val('');
            $('.color-option').removeClass('selected').first().addClass('selected');

            if (evtOrNull) {
                // Edit Mode
                this.editingEventId = evtOrNull.id;
                $('#event-title').val(evtOrNull.title);
                $('#event-type').val(evtOrNull.type);
                $('#event-description').val(evtOrNull.description || '');
                $('#event-priority').val(evtOrNull.priority || 'medium');
                $('#event-reminder').val(evtOrNull.reminder || 'none');
                $('#event-recurrence').val(evtOrNull.recurrence_rule || '');

                let dateObj = null;
                if (evtOrNull.start_datetime) {
                    dateObj = new Date(evtOrNull.start_datetime);
                } else if (evtOrNull.date) {
                    // Fallback for old events or untimed
                    const [y, m, d] = evtOrNull.date.split('-');
                    dateObj = new Date(y, m - 1, d);
                }

                if (dateObj) {
                    const y = dateObj.getFullYear();
                    const m = (dateObj.getMonth() + 1).toString().padStart(2, '0');
                    const d = dateObj.getDate().toString().padStart(2, '0');
                    $('#event-date-input').val(`${d}-${m}-${y}`);

                    if (evtOrNull.start_datetime) {
                        const h = dateObj.getHours().toString().padStart(2, '0');
                        const min = dateObj.getMinutes().toString().padStart(2, '0');
                        $('#event-time-input').val(`${h}:${min}`);
                    }
                }

                if (evtOrNull.end_datetime) {
                    const endObj = new Date(evtOrNull.end_datetime);
                    const h = endObj.getHours().toString().padStart(2, '0');
                    const min = endObj.getMinutes().toString().padStart(2, '0');
                    $('#event-end-time-input').val(`${h}:${min}`);
                }

                // Color
                if (evtOrNull.color) {
                    $('.color-option').removeClass('selected');
                    $(`.color-option[data-color="${evtOrNull.color}"]`).addClass('selected');
                }

                $('#save-event-btn').text('Update');
                $('#delete-event-btn').show();
                $('#modal-title').text('Edit Event');
            } else {
                // Create Mode
                this.editingEventId = null;
                $('#event-title').val('');

                if (dateToFill) {
                    const [d, m, y] = dateToFill.split('-');
                    $('#event-date-input').val(`${d}-${m}-${y}`); // Input expects DD-MM-YYYY
                } else {
                    // Default today
                    const today = new Date();
                    const y = today.getFullYear();
                    const m = (today.getMonth() + 1).toString().padStart(2, '0');
                    const d = today.getDate().toString().padStart(2, '0');
                    $('#event-date-input').val(`${d}-${m}-${y}`);
                }

                // Pre-fill start/end time if a time-slot hour was clicked
                if (hourToFill !== null) {
                    const hh = hourToFill.toString().padStart(2, '0');
                    const endHour = (hourToFill + 1 < 24 ? hourToFill + 1 : 23).toString().padStart(2, '0');
                    $('#event-time-input').val(`${hh}:00`);
                    $('#event-end-time-input').val(`${endHour}:00`);
                }

                $('#save-event-btn').text('Create Event');
                $('#delete-event-btn').hide();
                $('#modal-title').text('New Event');
            }

            $('#event-modal-overlay').css('display', 'flex').hide().fadeIn();
        },

        saveEvent: async function () {
            const title = $('#event-title').val().trim();
            // Custom Date Parsing for DD-MM-YYYY -> YYYY-MM-DD
            let rawDate = $('#event-date-input').val().trim();
            if (rawDate && rawDate.includes('-')) {
                const parts = rawDate.split('-');
                // Detect DD-MM-YYYY format (first part is 2 digits, last is 4)
                if (parts.length === 3 && parts[0].length === 2 && parts[2].length === 4) {
                    rawDate = `${parts[2]}-${parts[1]}-${parts[0]}`; // -> YYYY-MM-DD
                }
                // If already YYYY-MM-DD (first part is 4 digits), leave as-is
            }

            if (!title) return showToast("Title is required!");

            // rawDate is now reliably YYYY-MM-DD (or empty for inbox)
            const formattedDate = rawDate || null;

            const type = $('#event-type').val();
            const description = $('#event-description').val().trim();
            const priority = $('#event-priority').val();
            const reminder = $('#event-reminder').val();
            const recurrence = $('#event-recurrence').val();
            const color = $('.color-option.selected').data('color') || null;
            const startTime = $('#event-time-input').val();
            const endTime = $('#event-end-time-input').val();

            let startDt = null;
            let endDt = null;

            if (startTime) {
                // Construct Date Object
                // rawDate is YYYY-MM-DD
                const [y, m, d] = rawDate.split('-').map(Number);
                const [sh, sm] = startTime.split(':').map(Number);

                // Create Date object in Local Time
                const startObj = new Date(y, m - 1, d, sh, sm);

                // Convert to ISO String (UTC)
                startDt = startObj.toISOString();

                if (endTime) {
                    const [eh, em] = endTime.split(':').map(Number);
                    const endObj = new Date(y, m - 1, d, eh, em);

                    // Handle overnight events (if end time is before start time, assume next day)
                    if (endObj < startObj) {
                        endObj.setDate(endObj.getDate() + 1);
                    }
                    endDt = endObj.toISOString();
                } else {
                    // Default 1 hour
                    const endObj = new Date(startObj.getTime() + 60 * 60 * 1000);
                    endDt = endObj.toISOString();
                }
            } else {
                // Untimed Event
                startDt = null;
                endDt = null;
            }

            // Conflict Detection
            if (startDt && endDt) {
                // Fetch events for checking?
                // For simplicity, we assume we might have them in a local cache or do a quick check
                // However, without a clean local store, best to just proceed or do a quick GET (expensive)
                // Let's rely on the user interface warning.

                // Let's do a quick check if we have events loaded (this is accessed via modal, so events should be loaded ideally)
                // But we don't have easy access to `allEvents` here without refetching. 
                // We'll perform a quick check:
                try {
                    const existingEvents = this.eventsCache.length > 0 ? this.eventsCache : await this.loadEvents();
                    if (Array.isArray(existingEvents)) {
                        const newStart = new Date(startDt.replace(' ', 'T'));
                        const newEnd = new Date(endDt.replace(' ', 'T'));

                        const overlap = existingEvents.find(e => {
                            if (e.id === this.editingEventId) return false; // skip self
                            if (e.date !== formattedDate) return false; // different day
                            if (!e.start_datetime || !e.end_datetime) return false; // ignore full day events/legacy

                            const eStart = new Date(e.start_datetime.replace(' ', 'T'));
                            const eEnd = new Date(e.end_datetime.replace(' ', 'T'));

                            // Check overlap
                            return (newStart < eEnd && newEnd > eStart);
                        });

                        if (overlap) {
                            if (!confirm(`Time slot occupied by "${overlap.title}". Continue?`)) {
                                return; // Stop save
                            }
                        }
                    }
                } catch (e) { console.error("Conflict check failed", e); }
            }

            const eventData = {
                date: formattedDate,
                title: title,
                type: type,
                description: description,
                priority: priority,
                reminder: reminder,
                recurrence_rule: recurrence || null,
                is_recurring: !!recurrence,
                color: color,
                start_datetime: startDt,
                end_datetime: endDt
            };

            if (this.editingEventId) {
                // Update
                await API.put('events', this.editingEventId, {
                    id: this.editingEventId,
                    ...eventData
                });
                showToast('Event updated!');
            } else {
                // Create
                await API.post('events', {
                    id: Date.now(),
                    ...eventData
                });
                showToast('Event created!');
            }

            $('#event-modal-overlay').fadeOut();
            await this.loadEvents(); // Reload cache after save
            this.refreshCurrentView();
        },

        deleteEvent: async function () {
            if (!this.editingEventId) return;
            if (!confirm("Delete this event?")) return;

            await API.delete('events', this.editingEventId);

            $('#event-modal-overlay').fadeOut();
            await this.loadEvents(); // Reload cache after delete
            this.refreshCurrentView();
        },

        // ================= SELECTION MODE METHODS =================

        enterSelectionMode: function () {
            if (!this.selectedEventIds) this.selectedEventIds = new Set();
            if (this.isSelectionMode) return;
            this.isSelectionMode = true;
            this.selectedEventIds.clear();
            $('body').addClass('selection-mode');
            $('#selection-action-bar').fadeIn().css('display', 'flex');
            this.updateSelectionUI();
        },

        exitSelectionMode: function () {
            this.isSelectionMode = false;
            if (this.selectedEventIds) this.selectedEventIds.clear();
            $('body').removeClass('selection-mode');
            $('#selection-action-bar').fadeOut();
            $('.selected').removeClass('selected');
            $('#selection-count').text('0 Selected');
        },

        toggleEventSelection: function (id, $el) {
            id = parseInt(id); // Ensure ID type consistency
            if (this.selectedEventIds.has(id)) {
                this.selectedEventIds.delete(id);
                // Unselect all instances of this event (e.g. in month and inbox if both visible)
                $(`[data-id="${id}"]`).removeClass('selected');
            } else {
                this.selectedEventIds.add(id);
                $(`[data-id="${id}"]`).addClass('selected');
            }
            this.updateSelectionUI();
        },

        updateSelectionUI: function () {
            const count = this.selectedEventIds.size;
            $('#selection-count').text(`${count} Selected`);
            if (count > 0) {
                $('#delete-selection').css('opacity', '1').css('pointer-events', 'auto');
            } else {
                $('#delete-selection').css('opacity', '0.5').css('pointer-events', 'none');
            }
        },

        deleteSelectedEvents: async function () {
            if (this.selectedEventIds.size === 0) return;
            if (!confirm(`Delete ${this.selectedEventIds.size} events? This will remove them from all views.`)) return;

            const ids = Array.from(this.selectedEventIds);
            let deletedCount = 0;

            // Delete sequentially to avoid race conditions or API limits
            for (let id of ids) {
                try {
                    await API.delete('events', id);
                    deletedCount++;
                } catch (e) {
                    console.error(`Failed to delete event ${id}`, e);
                }
            }

            this.exitSelectionMode();
            await this.loadEvents(); // Reload cache after bulk delete
            this.refreshCurrentView();
            showToast(`${deletedCount} events deleted`);
        },

        // ==========================================================

        refreshCurrentView: function () {
            if (this.currentView === 'week') {
                this.renderWeek();
            } else if (this.currentView === 'day') {
                this.renderDay();
            } else if (this.currentView === 'year') {
                this.renderYear();
            } else {
                this.render(); // Month view
            }
            this.renderMini();
            this.renderInbox();
        },

        renderInbox: async function () {
            const list = $('#inbox-list');
            list.empty();
            try {
                // Use shared cache
                const events = this.eventsCache.length > 0 ? this.eventsCache : await this.loadEvents();
                if (Array.isArray(events)) {
                    // Filter: Untimed events (start_datetime is null)
                    const untimed = events.filter(e => !e.start_datetime);

                    if (untimed.length === 0) {
                        list.html('<div style="font-size: 11px; color: rgba(255,255,255,0.3); text-align: center; padding: 10px;">No untimed events</div>');
                    } else {
                        // Group by Date
                        const grouped = {};
                        untimed.forEach(e => {
                            if (!grouped[e.date]) grouped[e.date] = [];
                            grouped[e.date].push(e);
                        });

                        // Sort Dates
                        const sortedDates = Object.keys(grouped).sort();

                        sortedDates.forEach(dateStr => {
                            const [d, m, y] = dateStr.split('-');
                            // Create nicely formatted date header (e.g., "Feb 4")
                            const dateObj = new Date(y, m - 1, d);
                            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                            const niceDate = `${monthNames[dateObj.getMonth()]} ${d}`;

                            const groupHtml = `
                                <div style="margin-bottom: 15px;">
                                    <div style="font-size: 10px; font-weight: bold; color: #8A8F98; margin-bottom: 5px; text-transform: uppercase; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 2px;">
                                        ${niceDate}
                                    </div>
                                    <div class="inbox-date-group" id="inbox-group-${dateStr}"></div>
                                </div>
                            `;
                            list.append(groupHtml);
                            const groupContainer = list.find(`#inbox-group-${dateStr}`);

                            grouped[dateStr].forEach(e => {
                                const displayColor = e.display_color || e.color || '#5C7CFA';
                                groupContainer.append(`
                                    <div class="task-mini-item" data-id="${e.id}" style="border-left: 3px solid ${displayColor}; cursor: pointer; padding: 4px 8px; margin-bottom: 4px; background: rgba(255,255,255,0.05); border-radius: 4px;">
                                        <div class="event-checkbox"><i class="fa fa-check"></i></div>
                                        <span style="font-size: 12px; color: #E6E6E6;">${e.title}</span>
                                    </div>
                                 `);
                            });
                        });

                        // Click to edit
                        $('#inbox-list .task-mini-item').click((e) => {
                            const id = $(e.currentTarget).data('id');
                            const evt = events.find(x => x.id == id);
                            if (evt) this.openModal(evt);
                        });
                    }
                }
            } catch (e) { console.error("Inbox load failed", e); }
        },

        formatDate: function (date) {
            const d = date.getDate().toString().padStart(2, '0');
            const m = (date.getMonth() + 1).toString().padStart(2, '0');
            const y = date.getFullYear();
            return `${d}-${m}-${y}`;
        },

        // Render weekly time view
        renderWeek: async function () {
            // Update Header
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            $('#main-month-year').text(`${monthNames[month]} ${year}`);

            const grid = $('#week-grid');
            grid.empty();

            // Get week start (Sunday)
            const weekStart = new Date(this.currentDate);
            weekStart.setDate(weekStart.getDate() - weekStart.getDay());

            const todayStr = this.formatDate(new Date());

            // Update header with day numbers
            for (let i = 0; i < 7; i++) {
                const day = new Date(weekStart);
                day.setDate(weekStart.getDate() + i);
                const dayNum = day.getDate();
                const dateStr = this.formatDate(day);
                $(`#wh-${i}`).text(dayNum);
                if (dateStr === todayStr) {
                    $(`.week-day-header[data-index="${i}"]`).addClass('today');
                } else {
                    $(`.week-day-header[data-index="${i}"]`).removeClass('today');
                }
            }

            // Use shared events cache
            const allEvents = await this.loadEvents();

            // Create time slots (6AM - 10PM)
            const startHour = 6;
            const endHour = 22;

            for (let hour = startHour; hour <= endHour; hour++) {
                // Time label
                const displayHour = hour > 12 ? hour - 12 : hour;
                const amPm = hour >= 12 ? 'PM' : 'AM';
                grid.append(`<div class="week-time-label"><span>${displayHour} ${amPm}</span></div>`);

                // 7 day columns for this hour
                for (let day = 0; day < 7; day++) {
                    const cellDate = new Date(weekStart);
                    cellDate.setDate(weekStart.getDate() + day);
                    const dateStr = this.formatDate(cellDate);

                    grid.append(`<div class="week-day-column" data-date="${dateStr}" data-hour="${hour}"></div>`);
                }
            }

            // Place events in the grid
            for (let day = 0; day < 7; day++) {
                const cellDate = new Date(weekStart);
                cellDate.setDate(weekStart.getDate() + day);
                const dateStr = this.formatDate(cellDate);

                const dayEvents = allEvents.filter(e => e && e.date === dateStr && this.activeFilters.includes(e.type));

                // Sort by start time
                // Sort by start time
                dayEvents.sort((a, b) => {
                    const timeA = a.start_datetime ? new Date(a.start_datetime).getTime() : 0;
                    const timeB = b.start_datetime ? new Date(b.start_datetime).getTime() : 0;
                    return timeA - timeB;
                });

                // Algorithm to position concurrent events sharing width
                // 1. Map events to time ranges (minutes from start of day)
                const eventRanges = dayEvents.map(evt => {
                    let startMin = 9 * 60; // default 9am
                    let endMin = 10 * 60;

                    if (evt.start_datetime) {
                        const dateObj = new Date(evt.start_datetime);
                        startMin = dateObj.getHours() * 60 + dateObj.getMinutes();

                        if (evt.end_datetime) {
                            const endObj = new Date(evt.end_datetime);
                            endMin = endObj.getHours() * 60 + endObj.getMinutes();
                        } else {
                            endMin = startMin + 60;
                        }
                    }

                    // Force min duration
                    if (endMin - startMin < 30) endMin = startMin + 30;

                    return { id: evt.id, start: startMin, end: endMin, evt: evt };
                });

                // 2. Identify overlaps groups
                // Simple greedy overlap layout:
                // For each event, find all intersecting events, determine width = 1 / max_concurrent

                // Improve visual: Columns approach.
                const columns = []; // array of arrays of eventRef

                eventRanges.forEach(visEvt => {
                    // Try to place in first column where it doesn't overlap
                    let placed = false;
                    for (let col of columns) {
                        const lastInCol = col[col.length - 1];
                        if (visEvt.start >= lastInCol.end) {
                            col.push(visEvt);
                            placed = true;
                            visEvt.colIndex = columns.indexOf(col);
                            break;
                        }
                    }
                    if (!placed) {
                        columns.push([visEvt]);
                        visEvt.colIndex = columns.length - 1;
                    }
                });

                // Now we know how many columns needed for each "cluster" of overlapping events? 
                // Actually simple approach:
                // width = 100 / columns.length
                // left = colIndex * width
                // BUT this assumes whole day has uniform columns. Better to do clusters.

                // Re-do: For each event, find MAX concurrent overlap at its time
                // Then width = 100 / max_concurrent
                // left = (position_index) * width

                // Simplified "Smart Layout":
                // Just use the columns we calculated above. 
                // Width = 100% / columns.length
                // Left = colIndex * Width%

                const totalCols = columns.length;
                const colWidth = totalCols > 0 ? (100 / totalCols) : 100;

                eventRanges.forEach(item => {
                    const evt = item.evt;
                    const eventHour = Math.floor(item.start / 60);
                    // We render into the hour cell of the start time

                    const displayColor = evt.display_color || evt.color || '#5C7CFA';
                    const bgColor = this.hexToRgba(displayColor, 0.2);

                    const topOffset = item.start - (eventHour * 60); // minutes offset in that hour cell
                    // If event starts at 9:30, eventHour=9, topOffset=30.
                    // But wait, renderWeek renders 6AM to 10PM slots.
                    // We need to find the specific hour cell.

                    const durationMins = item.end - item.start;
                    const height = (durationMins / 60) * 55;

                    // Fix: topOffset is relative to the *Hour Cell*.
                    // If start is 9:45, we find cell 9, top is 45px (assuming 1min=~1px? No, 60min=55px)
                    const pxPerMin = 55 / 60;
                    const topPx = topOffset * pxPerMin;

                    const cell = $(`.week-day-column[data-date="${dateStr}"][data-hour="${eventHour}"]`);
                    if (cell.length) {
                        const left = item.colIndex * colWidth;
                        const width = colWidth;

                        cell.append(`
                            <div class="week-event ${evt.type}" data-id="${evt.id}" 
                                 style="background: ${bgColor}; border-left: 3px solid ${displayColor}; border-radius: 4px; 
                                 top: ${topPx}px; height: ${height}px; 
                                 left: ${left}%; width: ${width}%;
                                 display: flex; flex-direction: column; justify-content: center; padding: 2px 4px; 
                                 box-sizing: border-box; z-index: 5; position: absolute; overflow: hidden;">
                                <div class="event-checkbox"><i class="fa fa-check"></i></div>
                                <div style="flex: 1; overflow: hidden; display: flex; align-items: center;">
                                    <div class="event-title" style="color: #fff; font-size: 11px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${evt.title}</div>
                                </div>
                            </div>
                        `);
                    }
                });
            }
        },

        // Switch between views
        switchView: function (view) {
            this.currentView = view;
            $('.view-btn').removeClass('active');
            $(`.view-btn[data-view="${view}"]`).addClass('active');

            if (view === 'month') {
                $('#month-view').show();
                $('#week-view').hide();
                $('#day-view').hide();
                $('#year-view').hide();
                this.render();
            } else if (view === 'week') {
                $('#month-view').hide();
                $('#week-view').show();
                $('#day-view').hide();
                $('#year-view').hide();
                this.renderWeek();
            } else if (view === 'day') {
                $('#month-view').hide();
                $('#week-view').hide();
                $('#day-view').css('display', 'flex').hide().fadeIn();
                $('#year-view').hide();
                this.renderDay();
            } else if (view === 'year') {
                $('#month-view').hide();
                $('#week-view').hide();
                $('#day-view').hide();
                $('#year-view').show();
                this.renderYear();
            }
        },

        refreshCurrentView: function () {
            if (this.currentView === 'month') {
                this.render();
            } else if (this.currentView === 'week') {
                this.renderWeek();
            } else if (this.currentView === 'day') {
                this.renderDay();
            } else if (this.currentView === 'year') {
                this.renderYear();
            }
            this.renderMini();
            if (typeof this.renderSidebarTasks === 'function') this.renderSidebarTasks();
        },

        // Render Year View
        renderYear: async function () {
            const year = this.currentDate.getFullYear();
            $('#main-month-year').text(`${year}`);

            const grid = $('#year-grid');
            grid.empty();

            // Use shared events cache
            const allEvents = await this.loadEvents();

            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"];

            for (let m = 0; m < 12; m++) {
                const firstDay = new Date(year, m, 1).getDay();
                const daysInMonth = new Date(year, m + 1, 0).getDate();

                let monthHtml = `
                    <div class="year-month-card" style="background: #161A22; padding: 10px; border-radius: 8px;">
                        <h4 style="text-align: center; color: #8A8F98; margin-bottom: 10px; font-size: 14px;">${monthNames[m]}</h4>
                        <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px;">
                            <div style="font-size: 10px; color: #5C7CFA; text-align: center;">S</div>
                            <div style="font-size: 10px; color: #5C7CFA; text-align: center;">M</div>
                            <div style="font-size: 10px; color: #5C7CFA; text-align: center;">T</div>
                            <div style="font-size: 10px; color: #5C7CFA; text-align: center;">W</div>
                            <div style="font-size: 10px; color: #5C7CFA; text-align: center;">T</div>
                            <div style="font-size: 10px; color: #5C7CFA; text-align: center;">F</div>
                            <div style="font-size: 10px; color: #5C7CFA; text-align: center;">S</div>
                 `;

                // Empty slots
                for (let i = 0; i < firstDay; i++) {
                    monthHtml += `<div></div>`;
                }

                // Days
                for (let d = 1; d <= daysInMonth; d++) {
                    const dateStr = `${d.toString().padStart(2, '0')}-${(m + 1).toString().padStart(2, '0')}-${year}`;

                    // Check events
                    const hasTimed = allEvents.some(e => e.date === dateStr && e.start_datetime);
                    const hasUntimed = allEvents.some(e => e.date === dateStr && !e.start_datetime);

                    const dateObj = new Date(year, m, d);
                    const isToday = dateObj.toDateString() === new Date().toDateString();

                    let bg = 'transparent';
                    let color = '#E6E6E6';
                    let border = 'none';

                    if (isToday) {
                        bg = 'transparent';
                        color = '#fff';
                        border = '1px solid #5C7CFA';
                    }

                    if (hasTimed && hasUntimed) {
                        bg = '#8E6CEF'; // Purple
                        color = '#fff';
                        border = 'none';
                    } else if (hasTimed) {
                        bg = 'rgba(92, 124, 250, 0.4)'; // Blueish
                        color = '#5C7CFA';
                        border = 'none';
                    } else if (hasUntimed) {
                        bg = 'rgba(251, 192, 45, 0.4)'; // Yellowish
                        color = '#FBC02D';
                        border = 'none';
                    }

                    if (isToday && (hasTimed || hasUntimed)) {
                        // If today acts as event day, keep event color but maybe add border or glow?
                        // Let's just keep event color.
                        border = '1px solid #fff';
                    }

                    monthHtml += `
                        <div class="mini-day-cell" data-date="${dateStr}"
                             style="font-size: 10px; text-align: center; padding: 4px; border-radius: 4px; cursor: pointer; background: ${bg}; color: ${color}; aspect-ratio: 1; border: ${border};">
                             ${d}
                        </div>
                     `;
                }

                monthHtml += `</div></div>`;
                grid.append(monthHtml);
            }

            // Click to jump to day / Double click to add event
            $('.mini-day-cell').on('dblclick', (e) => {
                e.stopPropagation();
                const dateStr = $(e.currentTarget).data('date');
                // Open modal for this date
                this.openModal(null, dateStr);
            });

            $('.mini-day-cell').click((e) => {
                const dateStr = $(e.currentTarget).data('date');
                const [d, m, y] = dateStr.split('-');
                this.currentDate = new Date(y, m - 1, d);
                this.switchView('day');
            });
        },

        // Render Day View
        renderDay: async function () {
            // Update Header
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            $('#main-month-year').text(`${monthNames[month]} ${year}`);

            // Update Day Header
            const days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            const dayName = days[this.currentDate.getDay()];
            const dayNum = this.currentDate.getDate();

            $('#day-view-name').text(dayName);
            $('#day-view-num').text(dayNum);

            // Check if today
            const today = new Date();
            if (this.currentDate.toDateString() === today.toDateString()) {
                $('.day-header-wrapper .week-day-header').addClass('today');
            } else {
                $('.day-header-wrapper .week-day-header').removeClass('today');
            }

            const grid = $('#day-grid');
            grid.empty();
            const untimedList = $('#day-untimed-list');
            untimedList.empty();

            // Use shared events cache
            const allEvents = await this.loadEvents();

            const activeDateStr = this.formatDate(this.currentDate);

            // Create time slots (6AM - 10PM)
            const startHour = 6;
            const endHour = 22;

            for (let i = 0; i < (endHour - startHour + 1); i++) {
                const hour = startHour + i;
                const displayHour = hour > 12 ? hour - 12 : hour;
                const amPm = hour >= 12 ? 'PM' : 'AM';
                grid.append(`<div class="week-time-label"><span>${displayHour} ${amPm}</span></div>`);

                // 1 day column
                grid.append(`<div class="week-day-column" data-date="${activeDateStr}" data-hour="${hour}"></div>`);
            }

            // Filter events for this day
            const dayEvents = allEvents.filter(e => e && e.date === activeDateStr && this.activeFilters.includes(e.type));

            // Split into Timed and Untimed
            const timedEvents = dayEvents.filter(e => e.start_datetime);
            const untimedEvents = dayEvents.filter(e => !e.start_datetime);

            // Helper: reliably parse datetime strings from backend
            // Handles "2026-02-21T04:30:00.000000Z" and "2026-02-21 04:30:00" (no tz)
            const parseDateTime = (dtStr) => {
                if (!dtStr) return null;
                const normalized = String(dtStr).replace(' ', 'T');
                // If no timezone info, append 'Z' so it's treated as UTC (matches toISOString() saves)
                const withTz = /[Z+\-]\d*$/.test(normalized) ? normalized : normalized + 'Z';
                return new Date(withTz);
            };

            // Render Untimed (All Day / Important)
            if (untimedEvents.length === 0) {
                untimedList.append('<div style="font-size: 11px; color: rgba(255,255,255,0.3);">No important tasks for today</div>');
            } else {
                untimedEvents.forEach(evt => {
                    const displayColor = evt.display_color || evt.color || '#FBC02D';
                    untimedList.append(`
                        <div class="task-mini-item" data-id="${evt.id}" style="border-left: 3px solid ${displayColor}; padding: 6px 10px; background: rgba(255,255,255,0.05); border-radius: 4px; display: flex; align-items: center; justify-content: space-between; cursor: pointer;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="event-checkbox" style="position: static; margin-right: 5px;"><i class="fa fa-check"></i></div>
                                <span style="font-size: 13px; color: #E6E6E6; font-weight: 500;">${evt.title}</span>
                            </div>
                            <span style="font-size: 10px; color: rgba(255,255,255,0.5);">${evt.type}</span>
                        </div>
                    `);
                });
                $('#day-untimed-list .task-mini-item').off('click').click((e) => {
                    const id = $(e.currentTarget).data('id');
                    const evt = allEvents.find(x => x.id == id);
                    if (evt) this.openModal(evt);
                });
            }

            // Render Timed (Schedule)
            timedEvents.sort((a, b) => {
                const tA = parseDateTime(a.start_datetime);
                const tB = parseDateTime(b.start_datetime);
                return (tA ? tA.getTime() : 0) - (tB ? tB.getTime() : 0);
            });

            const eventRanges = timedEvents.map(evt => {
                let startMin = 9 * 60;
                let endMin = 10 * 60;

                const dateObj = parseDateTime(evt.start_datetime);
                if (dateObj) {
                    startMin = dateObj.getHours() * 60 + dateObj.getMinutes();
                    const endObj = parseDateTime(evt.end_datetime);
                    endMin = endObj ? endObj.getHours() * 60 + endObj.getMinutes() : startMin + 60;
                }
                if (endMin - startMin < 30) endMin = startMin + 30;
                return { id: evt.id, start: startMin, end: endMin, evt: evt };
            });

            const columns = [];
            eventRanges.forEach(visEvt => {
                let placed = false;
                for (let col of columns) {
                    if (visEvt.start >= col[col.length - 1].end) {
                        col.push(visEvt);
                        placed = true;
                        visEvt.colIndex = columns.indexOf(col);
                        break;
                    }
                }
                if (!placed) {
                    columns.push([visEvt]);
                    visEvt.colIndex = columns.length - 1;
                }
            });

            const totalCols = columns.length;
            const colWidth = totalCols > 0 ? (100 / totalCols) : 100;

            eventRanges.forEach(item => {
                const evt = item.evt;
                const eventHour = Math.floor(item.start / 60);
                // Clamp to visible range (6-22) so events at odd hours still render
                const clampedHour = Math.max(startHour, Math.min(endHour, eventHour));

                const displayColor = evt.display_color || evt.color || '#5C7CFA';
                const bgColor = this.hexToRgba(displayColor, 0.2);
                const topOffset = item.start - (clampedHour * 60);
                const pxPerMin = 55 / 60;
                const topPx = Math.max(0, topOffset * pxPerMin);
                const height = Math.max(30, ((item.end - item.start) / 60) * 55);
                const left = item.colIndex * colWidth;

                const cell = $(`#day-grid .week-day-column[data-hour="${clampedHour}"]`);
                if (cell.length) {
                    cell.append(`
                        <div class="week-event ${evt.type}" data-id="${evt.id}"
                             style="background: ${bgColor}; border-left: 3px solid ${displayColor}; border-radius: 4px;
                             top: ${topPx}px; height: ${height}px;
                             left: ${left}%; width: ${colWidth}%;
                             display: flex; flex-direction: column; justify-content: center; padding: 2px 4px;
                             box-sizing: border-box; z-index: 5; position: absolute; overflow: hidden; cursor: pointer;">
                            <div class="event-checkbox"><i class="fa fa-check"></i></div>
                            <div style="flex:1; overflow:hidden; display:flex; align-items:center;">
                                <div class="event-title" style="color:#fff; font-size:11px; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${evt.title}</div>
                            </div>
                        </div>
                    `);
                }
            });

            // Click to edit timed events
            $('#day-grid .week-event').off('click').click((e) => {
                e.stopPropagation();
                const id = $(e.currentTarget).data('id');
                const evt = timedEvents.find(x => x.id == id);
                if (evt) this.openModal(evt);
            });

        } // end renderDay

    }; // end CalendarApp

    window.CalendarApp = CalendarApp;
    window.dashboard = CalendarApp;
    CalendarApp.init();


    // ================= NOTES SAVE LOGIC =================
    $('#save-note').click(async function () {

        const title = $('#note-title').val().trim();
        const content = $('#note-content').val().trim();

        if (!title || !content) {
            alert("Please enter title and content");
            return;
        }

        try {
            await API.post('notes', { title, content });
            alert("Note Saved Successfully!");

            // Clear fields
            $('#note-title').val('');
            $('#note-content').val('');

        } catch (err) {
            console.error(err);
            alert("Error saving note");
        }
    });

});

// ================= SMART NOTES SYSTEM (Phase 1) =================
const SmartNotes = {
    currentNoteId: null,
    editor: null,
    editorTimeout: null,



    init: function () {
        // Load Data for Widget & App
        this.loadData().then(() => {
            this.renderDashboardWidget();

            // Check for note_id in URL (Deep Linking)
            const urlParams = new URLSearchParams(window.location.search);
            const noteId = urlParams.get('note_id');
            if (noteId && document.getElementById('quill-editor')) {
                setTimeout(() => {
                    const note = window.allNotes.find(n => n.id == noteId);
                    if (note) this.loadNote(noteId);
                }, 500);
            } else if (document.getElementById('quill-editor') && window.allNotes && window.allNotes.length > 0) {
                // Auto-load the first note so the placeholder doesn't show
                setTimeout(() => {
                    this.loadNote(window.allNotes[0].id);
                }, 300);
            }
        });

        // Only init editor listeners if editor exists
        if (!document.getElementById('quill-editor')) return;

        // Title Input Listener
        $('#note-title-input').on('input', () => {
            this.handleAutoSave();
        });

        // Search Listener
        $('#search-notes').on('keyup', function () {
            const val = $(this).val().toLowerCase();
            $('#notes-list .note-item').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
            });
        });

        // Folder Select Listener
        $('#note-folder-select').on('change', async function () {
            const folderId = $(this).val();
            if (!SmartNotes.currentNoteId) return;

            try {
                await fetch(`/app/smart-notes/${SmartNotes.currentNoteId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    body: JSON.stringify({ folder_id: folderId || null })
                });

                // Update local cache
                const note = window.allNotes.find(n => n.id === SmartNotes.currentNoteId);
                if (note) note.folder_id = folderId;

                $('#save-status').text('Saved').removeClass('text-warning').addClass('text-muted');
                SmartNotes.loadData();
            } catch (e) {
                console.error("Error moving note", e);
            }
        });

        // Filter clicks
        $('#notes-sidebar-menu a').click((e) => {
            e.preventDefault();
            $('#notes-sidebar-menu a').removeClass('active');
            $(e.currentTarget).addClass('active');
            const filter = $(e.currentTarget).data('filter');
            this.filterNotes(filter);
        });

        // ESC key to exit fullscreen
        $(document).on('keydown', (e) => {
            if (e.key === 'Escape' && $('#smart-notes-app').hasClass('smart-notes-fullscreen')) {
                this.toggleFullscreen();
            }
        });
    },

    initEditor: function (data = {}) {
        if (this.editor) {
            this.editor.destroy();
            this.editor = null;
        }

        if (typeof EditorJS === 'undefined') {
            console.warn('EditorJS not loaded yet. Please check your internet connection or reload.');
            $('#save-status').text('Editor not loaded').addClass('text-danger');
            return;
        }

        this.editor = new EditorJS({
            holder: 'quill-editor',
            placeholder: 'Type / for commands...',
            data: data,
            tools: {
                header: {
                    class: window.Header || window.HeaderTool,
                    config: {
                        levels: [1, 2, 3],
                        defaultLevel: 2
                    }
                },
                list: {
                    class: window.NestedList || window.List,
                    inlineToolbar: true,
                    config: { defaultStyle: 'unordered' }
                },
                table: {
                    class: window.Table,
                    inlineToolbar: true,
                    config: {
                        rows: 2,
                        cols: 3,
                    },
                },
                checklist: {
                    class: window.Checklist,
                    inlineToolbar: true,
                },
                quote: {
                    class: window.Quote,
                    inlineToolbar: true,
                    config: { quotePlaceholder: 'Enter a quote', captionPlaceholder: 'Quote\'s author' },
                },
                code: window.CodeTool || window.Code,
                inlineCode: {
                    class: window.InlineCode,
                    shortcut: 'CMD+SHIFT+C',
                },
                delimiter: window.Delimiter,
                underline: window.Underline,
                textColor: {
                    class: window.ColorPlugin,
                    config: {
                        colorCollections: ['#1E1E1E', '#EC7878', '#9C27B0', '#673AB7', '#3F51B5', '#0070FF', '#03A9F4', '#00BCD4', '#4CAF50', '#8BC34A', '#CDDC39', '#FFEB3B', '#FFC107', '#FF9800', '#FF5722', '#795548', '#9E9E9E', '#607D8B'],
                        defaultColor: '#FF1300',
                        type: 'text',
                        customPicker: true
                    }
                },
                marker: {
                    class: window.ColorPlugin, // Use ColorPlugin for Marker too for better colors
                    config: {
                        defaultColor: '#FFBF00',
                        type: 'marker',
                        icon: '<svg fill="#000000" height="200px" width="200px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 477.87 477.87" xml:space="preserve"><g><g><path d="M458.5,320.25c-9.9-10-14.8-21.7-14.8-35.1c0-13.4,4.9-25.1,14.8-35.1c9.9-10,21.6-15,35.1-15c13.4,0,25.1,5,35.1,15 c9.9,10,14.8,21.7,14.8,35.1c0,13.4-4.9,25.1-14.8,35.1c-9.9,10-21.6,15-35.1,15C480.1,335.25,468.4,330.25,458.5,320.25z"/></g></g></svg>'
                    }
                }
            },
            onChange: () => {
                this.handleAutoSave();
            }
        });
    },

    loadData: async function () {
        try {
            const response = await fetch('/app/smart-notes');
            const data = await response.json();
            window.allFolders = data.folders;
            this.renderFolders(data.folders);
            this.renderTags(data.tags);
            this.renderNotesList(data.notes);
            this.populateFolderDropdown();
            $('#smart-notes-app').fadeIn();
        } catch (error) {
            console.error('Error loading notes:', error);
        }
    },

    renderFolders: function (folders) {
        const container = $('#folder-list');
        container.empty();
        folders.forEach(folder => {
            container.append(`
                <a class="nav-link d-flex justify-content-between align-items-center" href="#" onclick="SmartNotes.filterByFolder(${folder.id}, this)">
                    <span><i class="fa fa-folder-o mr-2"></i> ${folder.name}</span>
                    <span class="badge badge-light">${folder.notes_count}</span>
                </a>
            `);
        });
    },

    populateFolderDropdown: function () {
        const select = $('#note-folder-select');
        const currentVal = select.val();
        select.empty();
        select.append('<option value="">No Folder</option>');

        if (window.allFolders) {
            window.allFolders.forEach(folder => {
                select.append(`<option value="${folder.id}">${folder.name}</option>`);
            });
        }
        select.val(currentVal);
    },

    renderTags: function (tags) {
        const container = $('#tags-list');
        container.empty();
        tags.forEach(tag => {
            container.append(`
                <span class="badge badge-pill mr-1 mb-1" style="background-color: ${tag.color}; color: #fff; cursor: pointer;" onclick="SmartNotes.filterByTag(${tag.id})">
                    #${tag.name}
                </span>
            `);
        });
    },

    renderNotesList: function (notes) {
        const container = $('#notes-list');
        container.empty();
        window.allNotes = notes;
        notes.forEach(note => {
            this.appendNoteToSidebar(note);
        });
    },

    appendNoteToSidebar: function (note) {
        const date = new Date(note.updated_at).toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
        const activeClass = (this.currentNoteId === note.id) ? 'active' : '';
        const pinIcon = note.is_pinned ? '<i class="fa fa-star text-warning ml-2"></i>' : '';

        // Preview logic for blocks
        let preview = 'No content';
        try {
            // If JSON
            if (note.content && (note.content.startsWith('{') || note.content.startsWith('['))) {
                const content = JSON.parse(note.content);
                if (content.blocks && content.blocks.length > 0) {
                    const firstBlock = content.blocks.find(b => b.type === 'paragraph' || b.type === 'header');
                    preview = firstBlock ? (firstBlock.data.text || 'Image/Media') : 'Media content';
                }
            } else {
                preview = this.stripHtml(note.content || 'No content');
            }
        } catch (e) {
            preview = this.stripHtml(note.content || 'No content');
        }

        const html = `
            <div class="list-group-item list-group-item-action note-item ${activeClass}" data-id="${note.id}" onclick="SmartNotes.loadNote(${note.id})">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1 note-list-title" style="font-weight: 600; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        ${note.title} ${pinIcon}
                    </h6>
                    <small class="text-muted">${date}</small>
                </div>
                <p class="mb-1 text-muted small" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; height: 20px;">
                    ${preview}
                </p>
            </div>
        `;
        $('#notes-list').prepend(html);
    },

    createNote: async function () {
        try {
            const response = await fetch('/app/smart-notes', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                body: JSON.stringify({ title: 'Untitled Note', content: JSON.stringify({ time: Date.now(), blocks: [] }) })
            });
            const note = await response.json();
            this.appendNoteToSidebar(note);
            this.loadNote(note.id);
        } catch (error) { console.error('Error creating note:', error); }
    },

    loadNote: function (id) {
        this.currentNoteId = id;
        const note = window.allNotes.find(n => n.id === id) || { title: '', content: '' };

        $('#notes-list .note-item').removeClass('active');
        $(`#notes-list .note-item[data-id="${id}"]`).addClass('active');

        // Force hide placeholder completely
        $('#editor-placeholder').hide().css('display', 'none');
        $('#note-editor-container').css('display', 'flex');
        $('.notes-editor-col').addClass('editor-active');

        // Render Toolbar
        this.renderEditorToolbar();

        $('#note-title-input').val(note.title);
        $('#note-folder-select').val(note.folder_id || "");

        // Parse Content
        let editorData = {};
        try {
            if (note.content && (note.content.startsWith('{') || note.content.startsWith('['))) {
                editorData = JSON.parse(note.content);
            } else {
                // Legacy HTML fallback: try to put it in a paragraph (dirty) or just empty
                editorData = {
                    time: Date.now(),
                    blocks: [
                        { type: 'paragraph', data: { text: "Legacy Content (HTML): " + this.stripHtml(note.content || '') } }
                    ]
                };
            }
        } catch (e) {
            console.error("JSON Parse Error", e);
            editorData = { time: Date.now(), blocks: [] };
        }

        // Initialize Editor with Data
        if (!editorData.blocks || editorData.blocks.length === 0) {
            // Force a default block if empty
            editorData = {
                time: Date.now(),
                blocks: [
                    { type: 'paragraph', data: { text: '' } }
                ]
            };
        }

        this.initEditor(editorData);

        // Update Pin Icon
        if (note.is_pinned) {
            $('#pin-icon').removeClass('fa-star-o').addClass('fa-star text-warning');
        } else {
            $('#pin-icon').removeClass('fa-star text-warning').addClass('fa-star-o');
        }
    },

    handleAutoSave: function () {
        clearTimeout(this.editorTimeout);
        $('#save-status').text('Saving...').removeClass('text-muted').addClass('text-warning');
        this.editorTimeout = setTimeout(() => {
            this.saveNote();
        }, 2000); // 2 seconds delay
    },

    saveNote: async function () {
        if (!this.currentNoteId || !this.editor) return;
        const title = $('#note-title-input').val();

        try {
            const outputData = await this.editor.save();
            const contentString = JSON.stringify(outputData);

            const response = await fetch(`/app/smart-notes/${this.currentNoteId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                body: JSON.stringify({ title, content: contentString })
            });

            if (!response.ok) {
                throw new Error(`Server error: ${response.status}`);
            }

            $('#save-status').text('Saved').removeClass('text-warning text-danger').addClass('text-muted');

            // Update local cache & Sidebar
            $(`.note-item[data-id="${this.currentNoteId}"] .note-list-title`).text(title);
            const noteIdx = window.allNotes.findIndex(n => n.id === this.currentNoteId);
            if (noteIdx > -1) {
                window.allNotes[noteIdx].title = title;
                window.allNotes[noteIdx].content = contentString;
            }

        } catch (error) {
            console.error('Save failed', error);
            if (error.message && error.message.includes('Failed to fetch')) {
                $('#save-status').text('Offline').addClass('text-danger');
                this.showSaveToast('⚠️ Server not running. Start Laravel: php artisan serve', 'error');
            } else {
                $('#save-status').text('Error').addClass('text-danger');
                this.showSaveToast('❌ Save failed: ' + error.message, 'error');
            }
        }
    },

    togglePin: async function () {
        if (!this.currentNoteId) return;
        const note = window.allNotes.find(n => n.id === this.currentNoteId);
        const newStatus = !note.is_pinned;

        try {
            await fetch(`/app/smart-notes/${this.currentNoteId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                body: JSON.stringify({ is_pinned: newStatus })
            });

            note.is_pinned = newStatus;
            if (newStatus) {
                $('#pin-icon').removeClass('fa-star-o').addClass('fa-star text-warning');
            } else {
                $('#pin-icon').removeClass('fa-star text-warning').addClass('fa-star-o');
            }
            this.loadData();

        } catch (e) {
            console.error(e);
        }
    },

    deleteCurrentNote: async function () {
        if (!confirm('Are you sure?')) return;
        try {
            await fetch(`/app/smart-notes/${this.currentNoteId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            this.currentNoteId = null;
            $('#notes-list .note-item.active').remove();
            if (this.editor) {
                this.editor.destroy();
                this.editor = null;
            }
            $('#editor-placeholder').show();
            $('#note-editor-container').hide();
            this.loadData();
        } catch (e) { console.error(e); }
    },

    renderEditorToolbar: function () {
        if ($('#editor-toolbar').length) return;

        const toolbarHtml = `
            <div id="editor-toolbar" class="d-flex flex-wrap p-2 mb-2 bg-white border rounded align-items-center shadow-sm" style="gap: 5px; position: sticky; top: 0; z-index: 100;">
                <!-- Fonts Style -->
                <div class="btn-group mr-2">
                    <button class="btn btn-sm btn-light border" onclick="document.execCommand('bold')" title="Bold"><i class="fa fa-bold"></i></button>
                    <button class="btn btn-sm btn-light border" onclick="document.execCommand('italic')" title="Italic"><i class="fa fa-italic"></i></button>
                    <button class="btn btn-sm btn-light border" onclick="document.execCommand('underline')" title="Underline"><i class="fa fa-underline"></i></button>
                    <button class="btn btn-sm btn-light border" onclick="document.execCommand('backColor', false, 'yellow')" title="Highlight (Comment)"><i class="fa fa-pencil" style="background:yellow; color:black; padding:0 2px;"></i></button>
                </div>

                <!-- Font Size -->
                 <div class="btn-group mr-2">
                    <button class="btn btn-sm btn-light border" onclick="document.execCommand('fontSize', false, '5')" title="Big Text"><i class="fa fa-text-height"></i>+</button>
                    <button class="btn btn-sm btn-light border" onclick="document.execCommand('fontSize', false, '3')" title="Normal Text"><i class="fa fa-font"></i></button>
                    <button class="btn btn-sm btn-light border" onclick="document.execCommand('fontSize', false, '1')" title="Small Text"><i class="fa fa-text-height" style="font-size: 10px;"></i>-</button>
                </div>

                <!-- Modifiers -->
                <div class="btn-group mr-2">
                     <button class="btn btn-sm btn-light border" onclick="SmartNotes.triggerBlock('header', {level:1})" title="H1">H1</button>
                     <button class="btn btn-sm btn-light border" onclick="SmartNotes.triggerBlock('header', {level:2})" title="H2">H2</button>
                </div>
                
                <!-- Lists -->
                 <div class="btn-group mr-2">
                    <button class="btn btn-sm btn-light border" onclick="SmartNotes.triggerBlock('list', {style:'unordered'})" title="Bullet List"><i class="fa fa-list-ul"></i></button>
                    <button class="btn btn-sm btn-light border" onclick="SmartNotes.triggerBlock('list', {style:'ordered'})" title="Numbered List"><i class="fa fa-list-ol"></i></button>
                    <button class="btn btn-sm btn-light border" onclick="SmartNotes.triggerBlock('checklist')" title="Checklist"><i class="fa fa-check-square-o"></i></button>
                </div>

                 <!-- Extras -->
                 <div class="btn-group">
                     <button class="btn btn-sm btn-light border" onclick="SmartNotes.triggerBlock('table')" title="Table"><i class="fa fa-table"></i></button>
                     <button class="btn btn-sm btn-light border" onclick="SmartNotes.triggerBlock('quote')" title="Quote"><i class="fa fa-quote-right"></i></button>
                     <button class="btn btn-sm btn-light border" onclick="SmartNotes.triggerBlock('code')" title="Code"><i class="fa fa-code"></i></button>
                </div>
            </div>
        `;

        $('#quill-editor').before(toolbarHtml);
    },

    triggerBlock: function (type, data) {
        if (!this.editor) return;
        this.editor.blocks.insert(type, data);
    },

    createFolder: async function () {
        const name = prompt("Folder Name:");
        if (!name) return;
        await fetch('/app/smart-notes/folders', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            body: JSON.stringify({ name })
        });
        this.loadData();
    },

    renderDashboardWidget: function () {
        const container = $('#dashboard-notes-list');
        if (!container.length) return;

        container.empty();

        if (!window.allNotes || window.allNotes.length === 0) {
            container.html('<div class="text-center p-3 text-muted">No notes yet. Create one!</div>');
            return;
        }

        const recent = window.allNotes.slice(0, 5); // Top 5

        recent.forEach(note => {
            const date = new Date(note.updated_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            const item = `
                <a href="/study?note_id=${note.id}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" 
                   style="background: transparent; border-bottom: 1px solid rgba(255,255,255,0.05); color: #e6e6e6; padding: 12px 15px;">
                    <div style="display: flex; align-items: center; gap: 10px; overflow: hidden;">
                        <i class="fa fa-sticky-note-o" style="color: #5C7CFA;"></i>
                        <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px;">${note.title}</span>
                    </div>
                    <span style="font-size: 11px; opacity: 0.5;">${date}</span>
                </a>
            `;
            container.append(item);
        });
    },

    stripHtml: function (html) {
        let tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || "";
    },

    filterNotes: function (filter) {
        this.renderNotesList(window.allNotes);
        if (filter === 'pinned') {
            const pinned = window.allNotes.filter(n => n.is_pinned);
            this.renderNotesList(pinned);
        }
    },

    // ===== FULLSCREEN MODE =====
    toggleFullscreen: function () {
        const app = $('#smart-notes-app');
        app.toggleClass('smart-notes-fullscreen');

        const isFullscreen = app.hasClass('smart-notes-fullscreen');
        $('#fullscreen-icon').toggleClass('fa-expand', !isFullscreen).toggleClass('fa-compress', isFullscreen);

        // Show/hide the exit hint
        if (isFullscreen) {
            $('.fullscreen-exit-hint').fadeIn(300);
            // Auto-hide hint after 3 seconds
            setTimeout(() => {
                $('.fullscreen-exit-hint').css('opacity', '0.5');
            }, 3000);
        } else {
            $('.fullscreen-exit-hint').fadeOut(200);
        }
    },

    // ===== COLLAPSIBLE SIDEBAR =====
    toggleSidebar: function () {
        const app = $('#smart-notes-app');
        app.toggleClass('sidebar-collapsed');

        // Toggle active state on the button
        const btn = $('[onclick="SmartNotes.toggleSidebar()"]');
        btn.toggleClass('active', app.hasClass('sidebar-collapsed'));
    },

    // ===== TOAST NOTIFICATIONS =====
    showSaveToast: function (message, type = 'error') {
        const toast = $('#save-toast');
        toast.text(message)
            .removeClass('error success')
            .addClass(type)
            .css('display', 'block');

        // Auto-hide after 5 seconds
        setTimeout(() => {
            toast.fadeOut(300);
        }, 5000);
    }


};

// Initialize SmartNotes on load
$(document).ready(function () {
    SmartNotes.init();
});
