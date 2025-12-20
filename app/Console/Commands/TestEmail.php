<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'email:test {recipient}';

    /**
     * The console command description.
     */
    protected $description = 'Test Resend email configuration';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $recipient = $this->argument('recipient');

        try {
            Mail::raw(
                'Test email from Lake County Local Deals via Resend. SMTP is working!',
                function ($message) use ($recipient) {
                    $message->to($recipient)
                        ->subject('Test Email - Resend Working');
                }
            );

            $this->info("[OK] Email sent to {$recipient} via Resend");
            $this->info('Check inbox (and spam folder)');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('[ERROR] Failed: ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}
