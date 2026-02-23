<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

/**
 * StreakService — tracks daily productivity streaks.
 *
 * Meaningful actions: create task, complete task, save/edit note.
 * Rules:
 *   - Same day action → no change
 *   - Yesterday action → increment
 *   - Older / first time → reset to 1
 *   - 7-day milestone → +50 XP bonus (via XpService)
 */
class StreakService
{
    /**
     * Record a productive action for the user and update streak.
     * Returns an array with streak info.
     */
    public static function record(User $user): array
    {
        $today = Carbon::today()->startOfDay();
        $lastDate = $user->last_study_date
            ? Carbon::parse($user->last_study_date)->startOfDay()
            : null;

        $daysDiff = $lastDate ? $today->diffInDays($lastDate, false) : null;
        $alreadyToday = $lastDate && $today->equalTo($lastDate);
        $wasYesterday = $lastDate && $today->diffInDays($lastDate) === 1 && $today->greaterThan($lastDate);

        if ($alreadyToday) {
            // Already recorded a streak action today — no change
            return [
                'streak' => $user->current_streak,
                'incremented' => false,
                'milestone' => false,
            ];
        }

        // Determine new streak value
        if ($wasYesterday) {
            $user->current_streak++;
        } else {
            // First time or gap > 1 day
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
        ];
    }
}
