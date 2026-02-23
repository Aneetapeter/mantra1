/**
 * PJAX Navigation - Enables seamless page transitions without full reload
 * This keeps the audio player running continuously across page navigations
 */
(function () {
    'use strict';

    // Only run on authenticated pages with sidebar navigation
    if (!document.querySelector('.sidebar')) return;

    // Store audio frame reference globally before any navigation
    window.persistentAudioFrame = document.getElementById('audio-frame');

    /**
     * Intercept navigation links and load via AJAX
     */
    function initPjax() {
        // Intercept sidebar navigation clicks
        document.querySelectorAll('.sidebar .nav-list a, .card-header a').forEach(link => {
            // Skip logout and external links
            if (link.href.includes('logout') || link.href.startsWith('javascript:')) return;

            link.addEventListener('click', function (e) {
                e.preventDefault();
                navigateTo(this.href);
            });
        });

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function (e) {
            if (e.state && e.state.url) {
                navigateTo(e.state.url, false);
            }
        });

        // Save initial state
        history.replaceState({ url: window.location.href }, '', window.location.href);
    }

    /**
     * Navigate to a new page via AJAX
     */
    async function navigateTo(url, pushState = true) {
        try {
            // Show loading indicator
            showLoader();

            // Fetch the new page
            const response = await fetch(url, {
                headers: {
                    'X-PJAX': 'true',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Navigation failed');
            }

            const html = await response.text();

            // Parse the response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Extract content to replace
            const newContent = doc.querySelector('.home-section');
            const newTitle = doc.querySelector('title')?.textContent;

            if (newContent) {
                // Replace main content
                const currentContent = document.querySelector('.home-section');
                if (currentContent) {
                    currentContent.innerHTML = newContent.innerHTML;
                }

                // Update page title
                if (newTitle) {
                    document.title = newTitle;
                }

                // Update active nav link
                updateActiveNavLink(url);

                // Update URL
                if (pushState) {
                    history.pushState({ url: url }, '', url);
                }

                // Re-initialize any page-specific scripts
                reinitializeScripts(doc);

                // Scroll to top
                window.scrollTo(0, 0);
            }

            hideLoader();

        } catch (error) {
            console.error('PJAX navigation failed:', error);
            // Fallback to regular navigation
            window.location.href = url;
        }
    }

    /**
     * Update active state on sidebar links
     */
    function updateActiveNavLink(url) {
        document.querySelectorAll('.sidebar .nav-list a').forEach(link => {
            link.classList.remove('active');
            if (link.href === url || url.includes(link.getAttribute('href'))) {
                link.classList.add('active');
            }
        });
    }

    /**
     * Re-initialize scripts after AJAX load
     */
    function reinitializeScripts(doc) {
        // Re-bind any event listeners that were lost
        // The dashboard.js handles most of this through event delegation

        // Re-initialize MusicPlayer controls if they exist
        if (window.MusicPlayer && window.MusicPlayer.init) {
            // Just rebind controls, don't reinitialize audio
            bindMusicControls();
        }

        // Trigger custom event for other scripts to hook into
        document.dispatchEvent(new CustomEvent('pjax:complete'));
    }

    /**
     * Rebind music player controls after PJAX navigation
     */
    function bindMusicControls() {
        const $ = window.jQuery;
        if (!$) return;

        // Rebind music widget controls
        $('.m-btn.play-pause').off('click').on('click', () => {
            if (window.MusicPlayer) window.MusicPlayer.sendCommand('toggle');
        });
        $('.m-btn:has(.fa-step-forward)').off('click').on('click', () => {
            if (window.MusicPlayer) window.MusicPlayer.sendCommand('next');
        });
        $('.m-btn:has(.fa-step-backward)').off('click').on('click', () => {
            if (window.MusicPlayer) window.MusicPlayer.sendCommand('prev');
        });
        $('.music-toggle').off('click').on('click', function (e) {
            e.stopPropagation();
            $('.music-widget').toggleClass('closed');
        });

        // Request current state from iframe
        if (window.MusicPlayer) {
            window.MusicPlayer.sendCommand('getState');
        }
    }

    /**
     * Show loading indicator
     */
    function showLoader() {
        let loader = document.getElementById('pjax-loader');
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'pjax-loader';
            loader.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 3px;
                background: linear-gradient(90deg, #5C7CFA, #00D9FF);
                z-index: 99999;
                animation: pjaxProgress 1s ease-in-out infinite;
            `;
            document.body.appendChild(loader);

            // Add animation keyframes
            if (!document.getElementById('pjax-styles')) {
                const style = document.createElement('style');
                style.id = 'pjax-styles';
                style.textContent = `
                    @keyframes pjaxProgress {
                        0% { transform: translateX(-100%); }
                        100% { transform: translateX(100%); }
                    }
                `;
                document.head.appendChild(style);
            }
        }
        loader.style.display = 'block';
    }

    /**
     * Hide loading indicator
     */
    function hideLoader() {
        const loader = document.getElementById('pjax-loader');
        if (loader) {
            loader.style.display = 'none';
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPjax);
    } else {
        initPjax();
    }

})();
