<?php

namespace App\Notifications;

use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeedbackSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(public Feedback $feedback)
    {
    }

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
            ->subject('New student feedback submitted')
            ->greeting('Hello '.$notifiable->name)
            ->line('A new student feedback entry has been submitted.')
            ->line('Title: '.$this->feedback->title)
            ->line('Week: '.$this->feedback->feedback_week)
            ->action('View Feedback', route('feedback.index'))
            ->line('Please review it from the feedback dashboard.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'feedback_id' => $this->feedback->id,
            'title' => $this->feedback->title,
            'feedback_week' => $this->feedback->feedback_week,
        ];
    }
}
