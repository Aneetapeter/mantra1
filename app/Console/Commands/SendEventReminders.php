<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\EventReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     */
    protected $description = 'Send email reminders for upcoming events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $this->info('Checking for event reminders at: ' . $now->toDateTimeString());

        // Get all events with reminders that haven't been sent yet
        $events = Event::whereNotNull('reminder')
            ->where('reminder', '!=', 'none')
            ->whereNull('reminder_sent_at')
            ->whereDate('date', '>=', $now->toDateString())
            ->with('user')
            ->get();

        $sentCount = 0;

        foreach ($events as $event) {
            if (!$event->user) {
                $this->warn("Event #{$event->id} has no user, skipping.");
                continue;
            }

            $eventDate = Carbon::parse($event->date);
            $shouldSend = false;

            switch ($event->reminder) {
                case 'at_time':
                    // Send if event is today
                    if ($eventDate->isToday()) {
                        $shouldSend = true;
                    }
                    break;
                case '5min':
                case '10min':
                    // For minute-based reminders, check start_datetime
                    if ($event->start_datetime) {
                        $eventTime = Carbon::parse($event->start_datetime);
                        $minutesBefore = $event->reminder === '5min' ? 5 : 10;
                        if ($now->diffInMinutes($eventTime, false) <= $minutesBefore && $now->lt($eventTime)) {
                            $shouldSend = true;
                        }
                    }
                    break;
                case '1hour':
                    // Send 1 hour before
                    if ($event->start_datetime) {
                        $eventTime = Carbon::parse($event->start_datetime);
                        if ($now->diffInMinutes($eventTime, false) <= 60 && $now->lt($eventTime)) {
                            $shouldSend = true;
                        }
                    } elseif ($eventDate->isToday()) {
                        $shouldSend = true;
                    }
                    break;
                case '1day':
                    // Send 1 day before
                    if ($eventDate->isTomorrow()) {
                        $shouldSend = true;
                    }
                    break;
            }

            if ($shouldSend) {
                try {
                    $event->user->notify(new EventReminderNotification($event));

                    // Mark reminder as sent
                    $event->reminder_sent_at = $now;
                    $event->save();

                    $sentCount++;
                    $this->info("✓ Sent reminder for: {$event->title} to {$event->user->email}");
                } catch (\Exception $e) {
                    $this->error("✗ Failed to send reminder for: {$event->title} - {$e->getMessage()}");
                }
            }
        }

        $this->info("Done! Sent {$sentCount} reminder(s).");
        return Command::SUCCESS;
    }
}
