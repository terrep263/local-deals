<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeVendorEmail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $password;

    public function __construct(User $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Welcome to Lake County Local Deals!')
            ->view('emails.welcome_vendor')
            ->with([
                'user' => $this->user,
                'password' => $this->password,
                'loginUrl' => route('login'),
            ]);
    }
}

