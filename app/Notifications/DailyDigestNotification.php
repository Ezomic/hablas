<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class DailyDigestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $languageName,
        private readonly int $dueReviewCount,
        private readonly int $streakCurrentLength,
        private readonly bool $hasUnsubmittedWeeklyReflection,
    ) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject("Your {$this->languageName} learning digest")
            ->greeting("Hi {$notifiable->name},");

        if ($this->dueReviewCount > 0) {
            $message->line("You have {$this->dueReviewCount} review ".Str::plural('card', $this->dueReviewCount).' due.');
        }

        $message->line("Current streak: {$this->streakCurrentLength} ".Str::plural('day', $this->streakCurrentLength).'.');

        if ($this->hasUnsubmittedWeeklyReflection) {
            $message->line("You haven't submitted this week's reflection yet.");
        }

        return $message->action('Open Hablas', url('/dashboard'));
    }
}
