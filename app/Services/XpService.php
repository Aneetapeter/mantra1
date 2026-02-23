<?php

namespace App\Services;

use App\Models\User;

/**
 * XpService — awards XP for user actions and handles levelling.
 *
 * XP Rules:
 *   create_task      → +5
 *   complete_task    → +20
 *   create_note      → +5
 *   edit_note        → +5
 *   complete_study   → +50
 *   streak_7_day     → +50 bonus
 */
class XpService
{
    const AMOUNTS = [
        'create_task' => 5,
        'complete_task' => 20,
        'create_note' => 5,
        'edit_note' => 5,
        'complete_study' => 50,
        'streak_7_day' => 50,
    ];

    /**
     * Award XP for a named action.
     * Returns array with xp_gained, new_xp, level, leveled_up, title.
     */
    public static function award(User $user, string $action): array
    {
        $amount = self::AMOUNTS[$action] ?? 0;
        $oldLevel = $user->level;

        $user->xp += $amount;
        $user->save();

        $newLevel = $user->level;
        $leveledUp = $newLevel > $oldLevel;

        return [
            'xp_gained' => $amount,
            'new_xp' => $user->xp,
            'level' => $newLevel,
            'title' => $user->title,
            'leveled_up' => $leveledUp,
        ];
    }

    /**
     * Get XP progress toward the next level.
     */
    public static function getProgress(User $user): array
    {
        $thresholds = [0, 100, 250, 450, 700, 1000, 1500, PHP_INT_MAX];
        $level = $user->level;
        $xp = $user->xp;
        $start = $thresholds[$level - 1] ?? 0;
        $end = $thresholds[$level] ?? $start + 500;
        $inLevel = $xp - $start;
        $needed = $end - $start;
        $percent = $needed > 0 ? min(100, round(($inLevel / $needed) * 100)) : 100;

        return [
            'xp' => $xp,
            'level' => $level,
            'title' => $user->title,
            'xp_in_lvl' => $inLevel,
            'xp_needed' => $needed,
            'percent' => $percent,
        ];
    }
}
