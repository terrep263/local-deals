<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\UpgradeSuggestion;

class UpgradeSuggestion extends Notification
{
    use Queueable;

    protected $suggestion;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(UpgradeSuggestion $suggestion)
    {
        $this->suggestion = $suggestion;
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
        $savings = number_format($this->suggestion->monthly_savings, 2);
        $annual = number_format($this->suggestion->monthly_savings * 12, 2);
        $tierName = ucfirst($this->suggestion->to_tier);
        
        $message = (new MailMessage)
            ->subject("{$notifiable->first_name}, you could save \${$savings}/month")
            ->greeting("Hi {$notifiable->first_name},")
            ->line("I was looking at your account and noticed something:")
            ->line("Last month you paid $" . number_format($this->suggestion->current_monthly_cost, 2) . " in total fees.")
            ->line("If you'd been on the {$tierName} plan, you would have paid $" . number_format($this->suggestion->suggested_monthly_cost, 2) . ".")
            ->line("That's a savings of \${$savings} per month, or \${$annual} per year.");
        
        // Add tier features
        $features = $this->getTierFeatures($this->suggestion->to_tier);
        if ($features) {
            $message->line('Plus you\'d get:');
            foreach ($features as $feature) {
                $message->line("- {$feature}");
            }
        }
        
        $message->action('See Full Comparison', route('vendor.upgrade.index'))
            ->line('This is just a suggestion based on the math. No pressure!')
            ->salutation('- Vincent');
        
        return $message;
    }
    
    protected function getTierFeatures(string $tier): array
    {
        $features = [
            'basic' => [
                'Priority listing placement',
                'Featured in weekly email',
                '24hr support response',
                'Better analytics',
            ],
            'pro' => [
                'Homepage featured placement',
                'Email/SMS campaigns',
                '4hr support response',
                'Advanced analytics',
                'AI scoring enabled',
            ],
            'enterprise' => [
                'Everything in Pro',
                'Dedicated account manager',
                'API access',
                'White-label options',
                'Custom campaigns',
                '0% commission',
            ],
        ];
        
        return $features[$tier] ?? [];
    }
}


