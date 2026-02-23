<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StreakService;
use App\Services\XpService;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = \Illuminate\Support\Facades\Auth::id();

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

    public function completeStudySession(Request $request)
    {
        $user = $request->user();
        $duration = $request->input('duration', 1500);

        // Update streak and study time
        $streak = StreakService::record($user);

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
        if ($streak['milestone'])
            $message .= ' 🔥 7-Day Streak Bonus!';

        return response()->json([
            'success' => true,
            'current_streak' => $user->current_streak,
            'readable_time' => $readableTime,
            'xp' => $user->xp,
            'level' => $user->level,
            'title' => $user->title,
            'leveled_up' => $xp['leveled_up'],
            'reward' => $reward,
            'streak_info' => $streak,
            'xp_progress' => XpService::getProgress($user),
            'message' => $message,
        ]);
    }
}
