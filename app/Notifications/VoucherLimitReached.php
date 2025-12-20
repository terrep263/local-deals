<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VoucherLimitReached extends Notification
{
    use Queueable;

    protected $currentCount;
    protected $limit;
    protected $tier;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(int $currentCount, $tier)
    {
        $this->currentCount = $currentCount;
        $this->tier = $tier;
        $this->limit = $tier->monthly_voucher_limit ?? 0;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $tierName = ucfirst($this->tier->package_tier ?? 'starter');
        $nextMonth = now()->addMonth()->startOfMonth()->format('F 1, Y');
        
        return (new MailMessage)
            ->subject("You've reached your monthly voucher limit!")
            ->greeting("Hi {$notifiable->first_name},")
            ->line("Great news - you've sold {$this->currentCount} vouchers this month!")
            ->line("Not so great news - that's your limit on the {$tierName} plan, so your deals are temporarily paused.")
            ->line('To continue selling:')
            ->action('Upgrade to Growth', route('vendor.upgrade.index'))
            ->line("Or your deals will automatically unpause on {$nextMonth}.")
            ->line('Keep crushing it!')
            ->salutation('- Vincent');
    }
}


