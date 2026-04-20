<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeRegisteredNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        return (new MailMessage)
            ->subject('Welcome to Smart School Feedback')
            ->greeting('Hello '.$notifiable->name)
            ->line('Your account has been registered successfully.')
            ->line('Role: '.ucfirst((string) $notifiable->role))
            ->line('Please verify your email address to secure your account and continue smoothly.')
            ->action('Open Dashboard', route('dashboard'))
            ->line('Thank you for using Smart School Feedback System.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $notifiable->id,
            'email' => $notifiable->email,
            'role' => $notifiable->role,
        ];
    }
}
