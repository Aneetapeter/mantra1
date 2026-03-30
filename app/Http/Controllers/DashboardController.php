<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StreakService;
use App\Services\XpService;

class DashboardController extends Controller
{
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $userId = $user->id;

        // ── Evaluate streak based on yesterday's task completion ──
        StreakService::checkAndUpdateStreak($user);
        $user = $user->fresh(); // Reload updated streak

        $recentNotes = \App\Models\Note::where('user_id', $userId)
            ->latest()
            ->take(3)
            ->get();

        $today = \Carbon\Carbon::today()->format('Y-m-d');
        $todayEvents = \App\Models\Event::where('user_id', $userId)
            ->whereDate('date', $today)
            ->orderByRaw("CASE WHEN status = 'completed' THEN 1 ELSE 0 END")
            ->orderBy('priority', 'desc')
            ->get();

        return view('dashboard', compact('recentNotes', 'todayEvents'));
    }

    /**
     * Called when a Pomodoro study session is completed.
     */
    public function completeStudySession(Request $request)
    {
        $user = $request->user();
        $duration = $request->input('duration', 1500);

        $user->total_study_seconds += $duration;
        $user->save();

        // XP for study session
        $xp = XpService::award($user, 'complete_study');

        // Format total time
        $totalSeconds = $user->total_study_seconds;
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $readableTime = "{$hours}h {$minutes}m";

        // Unlock sticker reward occasionally
        $reward = $user->unlockRandomReward();

        $message = 'Study session complete! +' . $xp['xp_gained'] . ' XP';
        if ($xp['leveled_up'])
            $message .= ' 🎉 Level Up!';

        return response()->json([
            'success' => true,
            'current_streak' => $user->current_streak,
            'readable_time' => $readableTime,
            'xp' => $user->xp,
            'level' => $user->level,
            'title' => $user->title,
            'leveled_up' => $xp['leveled_up'],
            'reward' => $reward,
            'streak_info' => ['streak' => $user->current_streak, 'incremented' => false, 'milestone' => false],
            'xp_progress' => XpService::getProgress($user),
            'message' => $message,
        ]);
    }

    /**
     * Heartbeat — called periodically while the user has the app open.
     * Adds elapsed seconds to total_study_seconds (passive app-open time).
     */
    public function heartbeat(Request $request)
    {
        $user = $request->user();
        $seconds = (int) $request->input('seconds', 60);

        // Cap per heartbeat to 5 minutes to prevent abuse/tab-sleep inflating numbers
        $seconds = min($seconds, 300);

        if ($seconds > 0) {
            $user->total_study_seconds += $seconds;
            $user->save();
        }

        $total = $user->total_study_seconds;
        $hours = floor($total / 3600);
        $minutes = floor(($total % 3600) / 60);

        return response()->json([
            'success' => true,
            'total_time' => "{$hours}h {$minutes}m",
            'seconds' => $user->total_study_seconds,
        ]);
    }
}
