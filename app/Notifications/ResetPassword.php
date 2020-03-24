<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\ResetPassword as PasswordReset;

class ResetPassword extends PasswordReset
{
    public function toMail($notifiable)
    {
        $url = url(config('app.client_url') . '/password/reset/'.$this->token).'?email='.urlencode($notifiable->email);
        return (new MailMessage)
                    ->line('You receiving this email because we received a password to reset request for your password')
                    ->action('Reset password', $url)
                    ->line('If you did not request a password reset, no further action is required.');
    }
}
