<?php

namespace Tests\Unit;

use Tests\TestCase; // Change from PHPUnit\Framework\TestCase to satisfy Laravel model usage (RefreshDatabase etc if needed, but here simple mockery or partial is fine, actually better to use Tests\TestCase to boot app)
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

class UserStreakTest extends TestCase
{
    use RefreshDatabase; // Use in-memory DB for tests

    /** @test */
    public function it_starts_streak_at_zero()
    {
        $user = User::factory()->create();
        $this->assertEquals(0, $user->current_streak);
    }

    /** @test */
    public function first_study_sets_streak_to_one()
    {
        $user = User::factory()->create();

        $user->updateStreak();

        $this->assertEquals(1, $user->current_streak);
        $this->assertTrue($user->last_study_date->isToday());
    }

    /** @test */
    public function studying_twice_same_day_does_not_increment_streak()
    {
        $user = User::factory()->create();

        $user->updateStreak();
        $this->assertEquals(1, $user->current_streak);

        $user->updateStreak();
        $this->assertEquals(1, $user->current_streak);
    }

    /** @test */
    public function studying_next_day_increments_streak()
    {
        $user = User::factory()->create();

        // Simulating "Yesterday"
        Carbon::setTestNow(now()->subDay());
        $user->updateStreak();
        $this->assertEquals(1, $user->current_streak);

        // Back to "Today"
        Carbon::setTestNow();
        $user = $user->fresh(); // Reload from DB

        $user->updateStreak();

        $this->assertEquals(2, $user->current_streak);
        $this->assertTrue($user->last_study_date->isToday());
    }

    /** @test */
    public function missing_a_day_resets_streak_to_one()
    {
        $user = User::factory()->create();

        // Studied 2 days ago
        Carbon::setTestNow(now()->subDays(2));
        $user->updateStreak();
        $this->assertEquals(1, $user->current_streak);

        // Today (skipped yesterday)
        Carbon::setTestNow();
        $user = $user->fresh();

        $user->updateStreak();

        // Streak should reset to 1 (new streak starting today)
        $this->assertEquals(1, $user->current_streak);
    }
}
