<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_study_date',
        'current_streak',
        'total_study_seconds',
        'xp',
        'badges',
        'stickers',
        'quiz_attempts',
        'preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_study_date' => 'datetime',
            'badges' => 'array',
            'stickers' => 'array',
            'quiz_attempts' => 'array',
            'preferences' => 'array',
        ];
    }

    /**
     * Update the user's study streak.
     */
    public function updateStreak()
    {
        $lastStudyDate = $this->last_study_date;
        $today = now()->startOfDay();

        if ($lastStudyDate) {
            $lastStudyDateStart = $lastStudyDate->clone()->startOfDay();

            // If studied today, do nothing
            if ($lastStudyDateStart->equalTo($today)) {
                return;
            }

            // If studied yesterday, increment streak
            if ($lastStudyDateStart->equalTo($today->copy()->subDay())) {
                $this->current_streak++;
            } else {
                // If missed a day (or more), reset to 1
                $this->current_streak = 1;
            }
        } else {
            // First time studying
            $this->current_streak = 1;
        }

        $this->last_study_date = now();
        $this->save();
    }

    /**
     * Log study time in seconds.
     */
    public function logStudyTime($seconds)
    {
        $this->total_study_seconds += $seconds;
        $this->save();
    }

    /**
     * Add XP and return if leveled up.
     */
    public function addXp($amount)
    {
        $oldLevel = $this->level;
        $this->xp += $amount;
        $this->save();

        return $this->level > $oldLevel;
    }

    /**
     * Get Level based on XP.
     */
    public function getLevelAttribute()
    {
        $xp = $this->xp;
        if ($xp < 100)
            return 1;
        if ($xp < 250)
            return 2;
        if ($xp < 450)
            return 3;
        if ($xp < 700)
            return 4;
        if ($xp < 1000)
            return 5;
        if ($xp < 1500)
            return 6;
        return 7;
    }

    /**
     * Get Title based on Level.
     */
    public function getTitleAttribute()
    {
        $level = $this->level;
        if ($level <= 2)
            return 'Beginner';
        if ($level <= 4)
            return 'Learner';
        if ($level <= 6)
            return 'Scholar';
        return 'Master';
    }

    /**
     * Unlock a random reward (Badge or Sticker) not yet owned.
     */
    public function unlockRandomReward()
    {
        // 1. Stickers
        $allStickers = ['cat', 'coffee', 'star'];
        $ownedStickers = $this->stickers ?? [];
        $stickerPool = array_values(array_diff($allStickers, $ownedStickers));

        // 50% Chance for Sticker if available
        if (!empty($stickerPool) && rand(0, 1) === 1) {
            $newSticker = $stickerPool[array_rand($stickerPool)];
            $ownedStickers[] = $newSticker;
            $this->stickers = $ownedStickers;
            $this->save();
            return ['type' => 'sticker', 'name' => $newSticker];
        }

        // 2. Badges (Simple Logic for now)
        // If first session (total_study_seconds > 0 but badges empty)
        $ownedBadges = $this->badges ?? [];
        if (empty($ownedBadges) && $this->total_study_seconds > 0) {
            $newBadge = 'First Step';
            $ownedBadges[] = $newBadge;
            $this->badges = $ownedBadges;
            $this->save();
            return ['type' => 'badge', 'name' => $newBadge];
        }

        return null; // No reward
    }

    /**
     * Log a quiz attempt score.
     */
    public function logQuizAttempt($score)
    {
        $attempts = $this->quiz_attempts ?? [];
        $attempts[] = [
            'score' => $score,
            'date' => now()->toDateTimeString()
        ];
        $this->quiz_attempts = $attempts;
        $this->save();
    }

    /**
     * Get Average Quiz Score.
     */
    public function getAverageQuizScoreAttribute()
    {
        $attempts = $this->quiz_attempts ?? [];
        if (empty($attempts))
            return 0;

        $total = array_sum(array_column($attempts, 'score'));
        return round($total / count($attempts));
    }
}
