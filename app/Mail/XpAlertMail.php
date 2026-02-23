<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class XpAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $userName;
    public int $xpEarned;
    public int $totalXp;
    public string $title;

    public function __construct(string $userName, int $xpEarned, int $totalXp, string $title)
    {
        $this->userName = $userName;
        $this->xpEarned = $xpEarned;
        $this->totalXp = $totalXp;
        $this->title = $title;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: '⚡ Mantra — You earned XP!');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.xp-alert');
    }
}
