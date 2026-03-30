<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

/**
 * StreakService — tracks daily task-completion streaks.
 *
 * Rules:
 *   - Streak increments ONLY if user had ≥1 task yesterday AND all were completed.
 *   - No tasks yesterday → streak resets to 0.
 *   - Any task incomplete yesterday → streak resets to 0.
 *   - Consecutive days with all tasks done → streak grows day by day.
 *   - 7-day milestone → +50 XP bonus (via XpService).
 */
class StreakService
{
    /**
     * Check yesterday's tasks and update the streak accordingly.
     * Call this when the user opens the dashboard.
     */
    public static function checkAndUpdateStreak(User $user): array
    {
        $today = Carbon::today()->startOfDay();
        $yesterday = Carbon::yesterday()->startOfDay();

        // Has this already been evaluated today?
        $lastChecked = $user->last_study_date
            ? Carbon::parse($user->last_study_date)->startOfDay()
            : null;

        if ($lastChecked && $lastChecked->equalTo($today)) {
            // Already evaluated for today — nothing to change
            return [
                'streak' => $user->current_streak,
                'incremented' => false,
                'milestone' => false,
                'reason' => 'already_checked_today',
            ];
        }

        // ── Evaluate yesterday's tasks ──────────────────────────────
        $yesterdaysEvents = Event::where('user_id', $user->id)
            ->whereDate('date', $yesterday->format('Y-m-d'))
            ->get();

        $totalTasks = $yesterdaysEvents->count();
        $completedTasks = $yesterdaysEvents->where('status', 'completed')->count();

        $allCompleted = $totalTasks > 0 && $completedTasks === $totalTasks;

        if (!$allCompleted) {
            // No tasks created, or some tasks still incomplete → reset streak
            $user->current_streak = 0;
            $user->last_study_date = $today;
            $user->save();

            return [
                'streak' => 0,
                'incremented' => false,
                'milestone' => false,
                'reason' => $totalTasks === 0 ? 'no_tasks_yesterday' : 'incomplete_tasks_yesterday',
                'total' => $totalTasks,
                'completed' => $completedTasks,
            ];
        }

        // ── All tasks completed yesterday — determine increment ──────
        $wasYesterday = $lastChecked && $lastChecked->equalTo($yesterday);

        if ($wasYesterday) {
            // Perfect consecutive day — increment
            $user->current_streak++;
        } else {
            // First completion ever, or gap in days
            $user->current_streak = 1;
        }

        $user->last_study_date = $today;
        $user->save();

        // 7-day milestone bonus
        $milestone = ($user->current_streak % 7 === 0);
        if ($milestone) {
            XpService::award($user, 'streak_7_day');
        }

        return [
            'streak' => $user->current_streak,
            'incremented' => true,
            'milestone' => $milestone,
            'reason' => 'all_tasks_completed',
            'total' => $totalTasks,
            'completed' => $completedTasks,
        ];
    }

    /**
     * Legacy method — kept for backward compatibility with completeStudySession.
     * Does NOT affect `current_streak` directly anymore.
     */
    public static function record(User $user): array
    {
        return [
            'streak' => $user->current_streak,
            'incremented' => false,
            'milestone' => false,
        ];
    }
}
