<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $eventDate = \Carbon\Carbon::parse($this->event->date)->format('l, F j, Y');
        $eventTime = $this->event->start_datetime
            ? \Carbon\Carbon::parse($this->event->start_datetime)->format('g:i A')
            : 'All day';

        return (new MailMessage)
            ->subject('⏰ Reminder: ' . $this->event->title)
            ->greeting('Hi ' . $notifiable->name . '!')
            ->line('This is a friendly reminder about your upcoming event:')
            ->line('')
            ->line('📅 **' . $this->event->title . '**')
            ->line('📆 Date: ' . $eventDate)
            ->line('🕐 Time: ' . $eventTime)
            ->line($this->event->description ? '📝 ' . $this->event->description : '')
            ->action('Open Mantra Calendar', url('/study'))
            ->line('Stay focused and keep studying! 📚')
            ->salutation('Best wishes, The Mantra Team');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'title' => $this->event->title,
            'date' => $this->event->date,
        ];
    }
}
