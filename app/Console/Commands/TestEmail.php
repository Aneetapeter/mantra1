<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify SMTP configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending test email...');

        try {
            Mail::raw('This is a test email from Mantra confirming your SMTP settings are working!', function ($message) {
                // To the from address so the user receives it in the same inbox
                $message->to(config('mail.from.address'))
                    ->subject('Mantra SMTP Test successful!');
            });
            $this->info('Test email sent successfully to ' . config('mail.from.address'));
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
        }
    }
}
