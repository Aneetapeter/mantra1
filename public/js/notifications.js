/**
 * Mantra Notifications System
 * Handles browser notifications, background study timers, and milestone rewards.
 * All localStorage keys are scoped per user ID to prevent cross-user data mixing.
 */

class MantraNotifications {
    constructor() {
        // ── Scope all keys to the logged-in user so no data leaks between users ──
        const uid = window.MANTRA_USER_ID || 'guest';
        this.KEY = {
            notifyEnabled: `mantra_notify_enabled_${uid}`,
            dailyReminderTime: `mantra_daily_reminder_time_${uid}`,
            lastDailyReminder: `mantra_last_daily_reminder_${uid}`,
            totalStudyMinutes: `mantra_total_study_minutes_${uid}`,
            dailyStudyMinutes: `mantra_daily_study_minutes_${uid}`,
            lastStudyDate: `mantra_last_study_date_${uid}`,
            unlockedRewards: `mantra_unlocked_rewards_${uid}`,
            inAppNotifications: `mantra_in_app_notifications_${uid}`,
            notifiedTasks: `mantra_notified_tasks_${uid}`,
            dailyTasksFinished: `mantra_daily_tasks_finished_${uid}`,
        };

        this.timers = {
            studyTimer: null,
            dailyCheckTimer: null
        };

        this.preferences = {
            enabled: localStorage.getItem(this.KEY.notifyEnabled) !== 'false',
            dailyReminderTime: localStorage.getItem(this.KEY.dailyReminderTime) || '18:00',
            lastDailyReminder: localStorage.getItem(this.KEY.lastDailyReminder) || null
        };

        this.stats = {
            totalStudyMinutes: parseInt(localStorage.getItem(this.KEY.totalStudyMinutes)) || 0,
            dailyStudyMinutes: parseInt(localStorage.getItem(this.KEY.dailyStudyMinutes)) || 0,
            lastStudyDate: localStorage.getItem(this.KEY.lastStudyDate) || new Date().toDateString()
        };

        this.rewards = JSON.parse(localStorage.getItem(this.KEY.unlockedRewards) || '[]');
        this.inAppNotifications = JSON.parse(localStorage.getItem(this.KEY.inAppNotifications) || '[]');

        this.init();
    }

    init() {
        this.checkNewDay();

        if (this.preferences.enabled) {
            this.requestPermission();
        }

        this.startStudyTimer();
        this.startDailyCheckTimer();
        this.renderInAppNotifications();
        this.setupDropdownListeners();
    }

    /**
     * Request Browser Notification Permission
     */
    async requestPermission() {
        if (!('Notification' in window)) return false;
        if (Notification.permission === 'granted') return true;
        if (Notification.permission !== 'denied') {
            const permission = await Notification.requestPermission();
            return permission === 'granted';
        }
        return false;
    }

    /**
     * Send a Notification (Browser + In-App)
     */
    sendNotification(title, options = {}) {
        // Add to in-app array
        this.inAppNotifications.unshift({
            id: Date.now(),
            title: title,
            body: options.body || '',
            time: new Date().getTime(),
            read: false
        });

        // Keep only top 20
        if (this.inAppNotifications.length > 20) {
            this.inAppNotifications.pop();
        }

        // Save scoped to this user
        localStorage.setItem(this.KEY.inAppNotifications, JSON.stringify(this.inAppNotifications));
        this.renderInAppNotifications();

        // Browser notification
        if (!this.preferences.enabled || Notification.permission !== 'granted') return;

        const defaultOptions = {
            icon: '/images/mantra.png',
            badge: '/images/mantra.png',
            requireInteraction: false
        };

        try {
            const notification = new Notification(title, { ...defaultOptions, ...options });
            notification.onclick = function () {
                window.focus();
                this.close();
            };
            return notification;
        } catch (e) {
            console.error('Error displaying notification:', e);
        }
    }

    /**
     * Background Study Tracking (runs every 1 minute)
     */
    startStudyTimer() {
        this.timers.studyTimer = setInterval(() => {
            this.checkNewDay();

            this.stats.totalStudyMinutes++;
            this.stats.dailyStudyMinutes++;

            localStorage.setItem(this.KEY.totalStudyMinutes, this.stats.totalStudyMinutes.toString());
            localStorage.setItem(this.KEY.dailyStudyMinutes, this.stats.dailyStudyMinutes.toString());

            this.checkMilestoneRewards();
        }, 60000);
    }

    checkNewDay() {
        const today = new Date().toDateString();
        if (this.stats.lastStudyDate !== today) {
            this.stats.dailyStudyMinutes = 0;
            this.stats.lastStudyDate = today;
            localStorage.setItem(this.KEY.dailyStudyMinutes, '0');
            localStorage.setItem(this.KEY.lastStudyDate, today);
        }
    }

    /**
     * Milestone Rewards
     */
    checkMilestoneRewards() {
        const milestones = {
            120: { id: 'focus_starter', name: 'Focus Starter' },
            300: { id: 'deep_work_pro', name: 'Deep Work Pro' },
            600: { id: 'study_champion', name: 'Study Champion' }
        };

        const currentMins = this.stats.totalStudyMinutes;

        for (const [minutes, reward] of Object.entries(milestones)) {
            if (currentMins >= parseInt(minutes) && !this.hasReward(reward.id)) {
                this.unlockReward(reward.id, reward.name);
            }
        }
    }

    hasReward(rewardId) {
        return this.rewards.includes(rewardId);
    }

    unlockReward(rewardId, rewardName) {
        this.rewards.push(rewardId);
        localStorage.setItem(this.KEY.unlockedRewards, JSON.stringify(this.rewards));

        this.sendNotification(`🏆 Reward Unlocked!`, {
            body: `You earned the ${rewardName} badge for your dedication!`,
            requireInteraction: true
        });

        if (typeof window.onRewardUnlocked === 'function') {
            window.onRewardUnlocked(rewardName);
        }
    }

    /**
     * Task completion
     */
    triggerTaskComplete(taskName) {
        this.sendNotification(`🎉 Task Completed!`, {
            body: `Great job finishing: ${taskName}`
        });
        this.incrementDailyCompletedTasks();
    }

    incrementDailyCompletedTasks() {
        this.checkNewDay();
        let tasksToday = parseInt(localStorage.getItem(this.KEY.dailyTasksFinished)) || 0;
        tasksToday++;
        localStorage.setItem(this.KEY.dailyTasksFinished, tasksToday.toString());

        if (tasksToday === 5 && !this.hasReward(`daily_5_tasks_${this.stats.lastStudyDate}`)) {
            this.unlockReward(`daily_5_tasks_${this.stats.lastStudyDate}`, 'Daily Goal Crusher (5 Tasks)');
        }
    }

    /**
     * Deadline Tracking
     */
    checkUpcomingDeadlines(tasks) {
        if (!tasks || !Array.isArray(tasks) || !this.preferences.enabled) return;

        const now = new Date();
        const notifiedTasks = JSON.parse(localStorage.getItem(this.KEY.notifiedTasks) || '[]');

        tasks.forEach(task => {
            if (task.completed || !task.deadline || notifiedTasks.includes(task.id)) return;

            const deadlineTime = new Date(task.deadline);
            const diffMinutes = (deadlineTime - now) / (1000 * 60);

            if (diffMinutes <= 30 && diffMinutes > -60) {
                const timeString = deadlineTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                this.sendNotification(`📌 Task Reminder`, {
                    body: `Complete your task: "${task.title}" before ${timeString}`
                });

                notifiedTasks.push(task.id);
                localStorage.setItem(this.KEY.notifiedTasks, JSON.stringify(notifiedTasks));
            }
        });
    }

    /**
     * Daily Study Reminder
     */
    startDailyCheckTimer() {
        this.timers.dailyCheckTimer = setInterval(() => {
            const now = new Date();
            const today = now.toDateString();
            const currentHour = now.getHours();
            const currentMinute = now.getMinutes();
            const [remindHour, remindMinute] = this.preferences.dailyReminderTime.split(':').map(Number);

            if (currentHour === remindHour && currentMinute === remindMinute) {
                if (this.preferences.lastDailyReminder !== today) {
                    if (this.stats.dailyStudyMinutes < 120) {
                        this.sendNotification(`⏰ Study Time!`, {
                            body: `It's time to focus and study 📚 Stay consistent!`
                        });
                    }
                    this.preferences.lastDailyReminder = today;
                    localStorage.setItem(this.KEY.lastDailyReminder, today);
                }
            }
        }, 60000);
    }

    /**
     * Settings
     */
    toggleNotifications(enabled) {
        this.preferences.enabled = enabled;
        localStorage.setItem(this.KEY.notifyEnabled, enabled);
        if (enabled) {
            this.requestPermission();
        }
    }

    /**
     * In-App Dropdown UI
     */
    renderInAppNotifications() {
        const badge = document.getElementById('notification-badge');
        const list = document.getElementById('notification-list');
        const emptyMsg = document.getElementById('no-notifications-msg');

        if (!badge || !list) return;

        const unreadCount = this.inAppNotifications.filter(n => !n.read).length;

        if (unreadCount > 0) {
            badge.style.display = 'flex';
            badge.innerText = unreadCount;
        } else {
            badge.style.display = 'none';
        }

        // Remove old items
        list.querySelectorAll('.notification-item').forEach(item => item.remove());

        if (this.inAppNotifications.length === 0) {
            if (emptyMsg) emptyMsg.style.display = 'block';
        } else {
            if (emptyMsg) emptyMsg.style.display = 'none';

            this.inAppNotifications.forEach(notif => {
                const dateObj = new Date(notif.time);
                const timeStr = dateObj.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                const itemHtml = `
                    <div class="notification-item" style="padding: 12px 15px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; gap: 12px; align-items: flex-start; background: ${notif.read ? 'transparent' : 'rgba(92,124,250,0.1)'}; transition: 0.2s;">
                        <div style="width: 30px; height: 30px; border-radius: 50%; background: var(--accent-light, #5C7CFA); color: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;">
                            <i class="fa fa-bell"></i>
                        </div>
                        <div style="flex: 1;">
                            <h6 style="margin: 0 0 3px 0; font-size: 13px; font-weight: 600; color: #fff;">${notif.title}</h6>
                            <p style="margin: 0 0 4px 0; font-size: 12px; color: rgba(255,255,255,0.7); line-height: 1.4;">${notif.body}</p>
                            <span style="font-size: 10px; color: rgba(255,255,255,0.4);">${timeStr}</span>
                        </div>
                    </div>
                `;
                list.insertAdjacentHTML('beforeend', itemHtml);
            });
        }
    }

    setupDropdownListeners() {
        // Use event delegation on document so PJAX replacement doesn't kill listeners
        if (this._listenersBound) return;
        this._listenersBound = true;

        document.addEventListener('click', (e) => {
            const toggle = e.target.closest('.icon-wrap.notification-wrap');
            const dropdown = document.getElementById('notifications-dropdown');
            const markReadBtn = e.target.closest('#mark-all-read');

            // Handle Clear All
            if (markReadBtn) {
                e.stopPropagation();
                this.inAppNotifications = [];
                localStorage.setItem(this.KEY.inAppNotifications, JSON.stringify([]));
                this.renderInAppNotifications();
                return;
            }

            // Handle Toggle Click
            if (toggle) {
                if (e.target.closest('.notifications-dropdown')) return;
                e.stopPropagation();
                if (dropdown && dropdown.style.display === 'none') {
                    dropdown.style.display = 'block';
                    this.markAllAsRead();
                } else if (dropdown) {
                    dropdown.style.display = 'none';
                }
                return;
            }

            // Handle Click Outside (close dropdown)
            if (dropdown && dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
            }
        });
    }

    markAllAsRead() {
        let changed = false;
        this.inAppNotifications.forEach(n => {
            if (!n.read) { n.read = true; changed = true; }
        });
        if (changed) {
            localStorage.setItem(this.KEY.inAppNotifications, JSON.stringify(this.inAppNotifications));
            this.renderInAppNotifications();
        }
    }
}

// Initialize globally
window.MantraNotify = new MantraNotifications();

// Listen for PJAX navigation to re-render DOM items if necessary
document.addEventListener('pjax:complete', () => {
    if (window.MantraNotify) {
        window.MantraNotify.renderInAppNotifications();
    }
});
