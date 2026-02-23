/**
 * Mantra Music Player - Persistent across all pages
 * Uses localStorage to save and restore playback state
 * Uses vanilla JavaScript for event handling to avoid conflicts
 */
(function () {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function () {
        initMusicPlayer();
    });

    // Also try to init immediately in case DOMContentLoaded already fired
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(initMusicPlayer, 100);
    }

    function initMusicPlayer() {
        // Prevent double initialization
        if (window.MusicPlayerInitialized) {
            console.log('MusicPlayer already initialized');
            return;
        }

        // Only initialize if music widget exists
        const musicWidget = document.querySelector('.music-widget');
        if (!musicWidget) {
            console.log('No music widget found on this page');
            return;
        }

        window.MusicPlayerInitialized = true;
        console.log('MusicPlayer initializing...');

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
            wasPlayingBeforeSeek: false,

            init: function () {
                const self = this;

                // Restore state from localStorage
                this.restoreState();

                // Setup Progress Bar (Replace custom div with Input Range)
                this.setupProgressBar();

                // Audio event listeners
                this.audio.addEventListener('ended', () => this.next());
                this.audio.addEventListener('timeupdate', () => {
                    if (!this.isDragging) this.updateProgress();
                });
                this.audio.addEventListener('play', () => {
                    this.isPlaying = true;
                    this.updatePlayBtn();
                    this.saveState();
                });
                this.audio.addEventListener('pause', () => {
                    this.isPlaying = false;
                    this.updatePlayBtn();
                    this.saveState();
                });

                // Bind controls using vanilla JavaScript
                this.bindControls();

                // Save state before page unload
                window.addEventListener('beforeunload', () => this.saveState());

                // Update UI
                this.updatePlayBtn();
                this.updateMoodDisplay();
                console.log('MusicPlayer initialized successfully');
            },

            setupProgressBar: function () {
                const oldBar = document.querySelector('.progress-bar-music');
                const existingRange = document.querySelector('.music-range');

                // If we already have the range input, just re-bind listeners? 
                // Creating a new one ensures clean state.
                let range;

                if (existingRange) {
                    range = existingRange;
                    // Remove old listeners by cloning
                    const newRange = range.cloneNode(true);
                    range.parentNode.replaceChild(newRange, range);
                    range = newRange;
                } else if (oldBar) {
                    range = document.createElement('input');
                    range.type = 'range';
                    range.className = 'music-range';
                    range.min = 0;
                    range.max = 100;
                    range.value = 0;
                    range.step = 0.1;
                    oldBar.parentNode.replaceChild(range, oldBar);
                }

                if (range) {
                    const self = this;

                    // Input event = Scrubbing (visual update)
                    range.addEventListener('input', function () {
                        self.isDragging = true;
                        const val = this.value;
                        this.style.backgroundSize = `${val}% 100%`;
                    });

                    // Change event = Seek committed (release)
                    range.addEventListener('change', function () {
                        self.isDragging = false;
                        const val = this.value;
                        if (self.audio.duration) {
                            const newTime = (val / 100) * self.audio.duration;
                            self.audio.currentTime = newTime;
                            console.log('Seeked to:', newTime);
                            self.saveState();

                            // Auto-resume if needed
                            if (self.wasPlayingBeforeSeek) {
                                self.audio.play().catch(e => console.log('Resume failed:', e));
                            }
                        }
                    });

                    // Mouse interactions to track playing state
                    range.addEventListener('mousedown', function () {
                        self.isDragging = true;
                        self.wasPlayingBeforeSeek = self.isPlaying;
                    });

                    range.addEventListener('touchstart', function () {
                        self.isDragging = true;
                        self.wasPlayingBeforeSeek = self.isPlaying;
                    }, { passive: true });

                    range.addEventListener('mouseup', function () {
                        self.isDragging = false;
                    });

                    range.addEventListener('touchend', function () {
                        self.isDragging = false;
                    });
                }
            },

            bindControls: function () {
                const self = this;

                // Play/Pause button
                const playPauseBtn = document.querySelector('.m-btn.play-pause');
                if (playPauseBtn) {
                    const newPlayBtn = playPauseBtn.cloneNode(true);
                    playPauseBtn.parentNode.replaceChild(newPlayBtn, playPauseBtn);

                    newPlayBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        console.log('Play/Pause clicked');
                        self.togglePlay();
                    });
                }

                // Next/Prev buttons
                const allBtns = document.querySelectorAll('.m-btn');
                allBtns.forEach(btn => {
                    const icon = btn.querySelector('i');
                    if (icon) {
                        const newBtn = btn.cloneNode(true);
                        btn.parentNode.replaceChild(newBtn, btn);
                        const newIcon = newBtn.querySelector('i');

                        if (newIcon && newIcon.classList.contains('fa-step-forward')) {
                            newBtn.addEventListener('click', function (e) {
                                e.preventDefault();
                                e.stopPropagation();
                                self.next();
                            });
                        } else if (newIcon && newIcon.classList.contains('fa-step-backward')) {
                            newBtn.addEventListener('click', function (e) {
                                e.preventDefault();
                                e.stopPropagation();
                                self.prev();
                            });
                        } else if (newBtn.classList.contains('play-pause')) {
                            newBtn.addEventListener('click', function (e) {
                                e.preventDefault();
                                e.stopPropagation();
                                self.togglePlay();
                            });
                        }
                    }
                });

                // Music toggle button
                const musicToggle = document.querySelector('.music-toggle');
                if (musicToggle) {
                    const newToggle = musicToggle.cloneNode(true);
                    musicToggle.parentNode.replaceChild(newToggle, musicToggle);

                    newToggle.addEventListener('click', function (e) {
                        e.stopPropagation();
                        const widget = document.querySelector('.music-widget');
                        if (widget) {
                            widget.classList.toggle('closed');
                        }
                    });
                }
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
                            const self = this;
                            const savedTime = state.currentTime || 0;
                            const wasPlaying = state.isPlaying;

                            this.audio.addEventListener('loadedmetadata', function onLoad() {
                                if (savedTime > 0) self.audio.currentTime = savedTime;
                                if (wasPlaying) self.audio.play().catch(e => console.log('Autoplay blocked:', e));
                                self.audio.removeEventListener('loadedmetadata', onLoad);
                            });
                            this.audio.load();
                        }
                    } else {
                        this.currentPlaylist = this.playlists.chill;
                        this.loadTrack(0);
                    }
                } catch (e) {
                    console.error('Error restoring state:', e);
                    this.currentPlaylist = this.playlists.chill;
                    this.loadTrack(0);
                }
            },

            saveState: function () {
                try {
                    const state = {
                        mood: this.currentMood,
                        trackIndex: this.currentIndex,
                        currentTime: this.audio.currentTime || 0,
                        isPlaying: this.isPlaying
                    };
                    localStorage.setItem('mantra_music_state', JSON.stringify(state));
                } catch (e) {
                    console.error('Error saving state:', e);
                }
            },

            loadPlaylist: function (mood) {
                if (mood === 'fun' && this.playlists.fun.length === 0) mood = 'chill';
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
                const self = this;
                this.audio.play().then(() => {
                    self.isPlaying = true;
                    self.updatePlayBtn();
                    self.saveState();
                }).catch(e => {
                    console.error('Play error:', e);
                });
            },

            pause: function () {
                this.audio.pause();
                this.isPlaying = false;
                this.updatePlayBtn();
                this.saveState();
            },

            togglePlay: function () {
                if (this.isPlaying) this.pause();
                else this.play();
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
                const icon = document.querySelector('.m-btn.play-pause i');
                if (icon) {
                    icon.className = this.isPlaying ? 'fa fa-pause' : 'fa fa-play';
                }
            },

            updateProgress: function () {
                if (this.audio.duration) {
                    const percent = (this.audio.currentTime / this.audio.duration) * 100;
                    const range = document.querySelector('.music-range');
                    if (range) {
                        range.value = percent;
                        range.style.backgroundSize = `${percent}% 100%`;
                    }
                }
            },

            updateMoodDisplay: function () {
                let moodName = this.currentMood.charAt(0).toUpperCase() + this.currentMood.slice(1);
                const trackName = document.querySelector('.track-name');
                const artistName = document.querySelector('.artist-name');
                if (trackName) trackName.textContent = `${moodName} Vibes 🎵`;
                if (artistName) artistName.textContent = 'Mantra Radio';
            }
        };

        window.MusicPlayer.init();
    }
})();
