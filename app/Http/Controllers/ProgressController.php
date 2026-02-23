<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Note;
use App\Models\Todo;
use App\Services\XpService;
use Carbon\Carbon;

class ProgressController extends Controller
{
    /**
     * Each achievement key maps to:
     *   type  => 'sticker' | 'reward'
     *   asset => filename without extension (file lives in public/stickers/ or public/rewards/)
     *   label => human-readable name
     */
    const ACHIEVEMENT_REWARDS = [
        'First Steps' => ['type' => 'reward', 'asset' => 'congrats', 'label' => 'Congrats!'],
        'Note Taker' => ['type' => 'sticker', 'asset' => 'notes', 'label' => 'Notes'],
        'Task Creator' => ['type' => 'sticker', 'asset' => 'grade', 'label' => 'Grade A'],
        'On Fire!' => ['type' => 'reward', 'asset' => 'champion', 'label' => 'Champion'],
        'Streak Keeper' => ['type' => 'sticker', 'asset' => 'study-time', 'label' => 'Study Time'],
        'Bookworm' => ['type' => 'sticker', 'asset' => 'reading-book', 'label' => 'Bookworm'],
        'Scholar' => ['type' => 'sticker', 'asset' => 'university', 'label' => 'University'],
        'Prolific Writer' => ['type' => 'sticker', 'asset' => 'lighbulb', 'label' => 'Lightbulb'],
        'Task Master' => ['type' => 'reward', 'asset' => 'trophy', 'label' => 'Trophy'],
        'Collector' => ['type' => 'reward', 'asset' => 'crown', 'label' => 'Crown'],
    ];

    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        // ── 1. Weekly chart: REAL counts per day (notes + todos) ──────────
        $weeklyLabels = [];
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $weeklyLabels[] = Carbon::now()->subDays($i)->format('D');
            $notesOnDay = Note::where('user_id', $userId)->whereDate('created_at', $date)->count();
            $todosCreated = Todo::where('user_id', $userId)->whereDate('created_at', $date)->count();
            $todosCompleted = Todo::where('user_id', $userId)->where('completed', true)->whereDate('updated_at', $date)->count();
            $weeklyData[] = $notesOnDay + $todosCreated + $todosCompleted;
        }
        $hasActivity = array_sum($weeklyData) > 0;

        // ── 2. XP / Level ─────────────────────────────────────────────────
        $xpProgress = XpService::getProgress($user);
        $xpPercent = $xpProgress['percent'];
        $xpInLevel = $xpProgress['xp_in_lvl'];
        $xpNeeded = $xpProgress['xp_needed'];
        $level = $xpProgress['level'];

        // ── 3. Achievements ───────────────────────────────────────────────
        $noteCount = Note::where('user_id', $userId)->count();
        $todoCount = Todo::where('user_id', $userId)->count();
        $completedCount = Todo::where('user_id', $userId)->where('completed', true)->count();
        $studyHours = ($user->total_study_seconds ?? 0) / 3600;

        $achievements = [
            ['key' => 'First Steps', 'name' => 'First Steps', 'desc' => 'Complete your first study session', 'icon' => 'fa-star', 'color' => 'success', 'unlocked' => ($user->total_study_seconds ?? 0) > 0],
            ['key' => 'Note Taker', 'name' => 'Note Taker', 'desc' => 'Create your first note', 'icon' => 'fa-file-text-o', 'color' => 'info', 'unlocked' => $noteCount >= 1],
            ['key' => 'Task Creator', 'name' => 'Task Creator', 'desc' => 'Create your first task', 'icon' => 'fa-plus-square', 'color' => 'info', 'unlocked' => $todoCount >= 1],
            ['key' => 'On Fire!', 'name' => 'On Fire! 🔥', 'desc' => 'Maintain a 7-day streak', 'icon' => 'fa-fire', 'color' => 'warning', 'unlocked' => ($user->current_streak ?? 0) >= 7, 'progress' => min(100, round((($user->current_streak ?? 0) / 7) * 100))],
            ['key' => 'Streak Keeper', 'name' => 'Streak Keeper', 'desc' => 'Maintain a 3-day streak', 'icon' => 'fa-calendar-check-o', 'color' => 'warning', 'unlocked' => ($user->current_streak ?? 0) >= 3, 'progress' => min(100, round((($user->current_streak ?? 0) / 3) * 100))],
            ['key' => 'Bookworm', 'name' => 'Bookworm 📚', 'desc' => 'Study for 10 hours total', 'icon' => 'fa-book', 'color' => 'info', 'unlocked' => $studyHours >= 10, 'progress' => min(100, round(($studyHours / 10) * 100))],
            ['key' => 'Scholar', 'name' => 'Scholar 🎓', 'desc' => 'Reach Level 5', 'icon' => 'fa-graduation-cap', 'color' => 'warning', 'unlocked' => $level >= 5, 'progress' => min(100, round(($level / 5) * 100))],
            ['key' => 'Prolific Writer', 'name' => 'Prolific Writer ✍️', 'desc' => 'Create 10 notes', 'icon' => 'fa-pencil', 'color' => 'success', 'unlocked' => $noteCount >= 10, 'progress' => min(100, round(($noteCount / 10) * 100))],
            ['key' => 'Task Master', 'name' => 'Task Master', 'desc' => 'Complete 10 tasks', 'icon' => 'fa-check-square', 'color' => 'success', 'unlocked' => $completedCount >= 10, 'progress' => min(100, round(($completedCount / 10) * 100))],
            ['key' => 'Collector', 'name' => 'Collector', 'desc' => 'Earn 3 stickers', 'icon' => 'fa-certificate', 'color' => 'danger', 'unlocked' => count($user->stickers ?? []) >= 3, 'progress' => min(100, round((count($user->stickers ?? []) / 3) * 100))],
        ];

        // ── 4. Auto-award stickers/rewards for newly unlocked achievements ─
        $ownedStickers = $user->stickers ?? [];
        $ownedBadges = $user->badges ?? [];
        $changed = false;

        foreach ($achievements as &$ach) {
            $key = $ach['key'];
            $reward = self::ACHIEVEMENT_REWARDS[$key] ?? null;

            if (!$reward)
                continue;

            $ach['reward'] = $reward; // always pass reward info to view

            if (!$ach['unlocked'])
                continue;

            // Auto-add to user's collection if not yet earned
            if ($reward['type'] === 'sticker' && !in_array($reward['asset'], $ownedStickers)) {
                $ownedStickers[] = $reward['asset'];
                $changed = true;
            } elseif ($reward['type'] === 'reward' && !in_array($reward['asset'], $ownedBadges)) {
                $ownedBadges[] = $reward['asset'];
                $changed = true;
            }
        }
        unset($ach);

        if ($changed) {
            $user->stickers = $ownedStickers;
            $user->badges = $ownedBadges;
            $user->save();
        }

        // ── 5. Recent Activity ─────────────────────────────────────────────
        $recentNotes = Note::where('user_id', $userId)->latest()->take(6)->get()->map(fn($n) => [
            'title' => 'Created note: ' . ($n->title ?: 'Untitled'),
            'sub' => null,
            'icon' => 'fa-file-text-o',
            'color' => 'info',
            'time' => $n->created_at,
            'url' => url('/library?note=' . $n->id),
            'note_id' => $n->id,
        ]);
        $recentTodosCreated = Todo::where('user_id', $userId)->latest('created_at')->take(4)->get()->map(fn($t) => [
            'title' => 'Created task: ' . $t->text,
            'sub' => null,
            'icon' => 'fa-plus-square',
            'color' => 'info',
            'time' => $t->created_at,
            'url' => null,
            'note_id' => null,
        ]);
        $recentTodosDone = Todo::where('user_id', $userId)->where('completed', true)->latest('updated_at')->take(4)->get()->map(fn($t) => [
            'title' => 'Completed task: ' . $t->text,
            'sub' => null,
            'icon' => 'fa-check-circle',
            'color' => 'success',
            'time' => $t->updated_at,
            'url' => null,
            'note_id' => null,
        ]);
        $recentActivity = $recentNotes->concat($recentTodosCreated)->concat($recentTodosDone)->sortByDesc('time')->take(10)->values();

        // ── 6. Build reward display arrays (fresh after auto-award) ────────
        $stickerAssets = collect($ownedStickers)->map(fn($s) => [
            'name' => $s,
            'path' => 'stickers/' . $s . '.png',
            'label' => ucwords(str_replace(['-', '_'], ' ', $s)),
        ])->values()->toArray();

        $badgeAssets = collect($ownedBadges)->map(fn($b) => [
            'name' => $b,
            'label' => ucwords($b),
            'path' => 'rewards/' . $b . '.png',
        ])->values()->toArray();

        $totalNotesCount = $noteCount;
        $totalTodosCount = $todoCount;
        $totalCompletedTasks = $completedCount;

        return view('progress', compact(
            'user',
            'weeklyLabels',
            'weeklyData',
            'hasActivity',
            'xpPercent',
            'xpInLevel',
            'xpNeeded',
            'level',
            'achievements',
            'recentActivity',
            'stickerAssets',
            'badgeAssets',
            'totalNotesCount',
            'totalTodosCount',
            'totalCompletedTasks'
        ));
    }

    public function data()
    {
        $user = Auth::user();
        return response()->json([
            'streak' => $user->current_streak,
            'study_time' => $user->total_study_seconds,
            'xp_progress' => XpService::getProgress($user),
        ]);
    }
}
