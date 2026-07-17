<?php

namespace App\Notifications;

use App\Enums\EmailCodePurpose;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Deliberately NOT ShouldQueue, unlike DailyDigestNotification: this mail is on
 * the critical path of signing in. Queueing it would make every login depend on
 * the queue worker being healthy and would add delivery latency to a code that
 * expires in minutes.
 */
class EmailCodeNotification extends Notification
{
    public function __construct(
        public readonly string $code,
        public readonly EmailCodePurpose $purpose,
        private readonly int $expiresInMinutes,
    ) {}

    /** @return list<string> */
    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject())
            ->greeting("Hi {$notifiable->name},")
            ->line($this->intro())
            ->line("Your code is: {$this->code}")
            ->line("It expires in {$this->expiresInMinutes} minutes and can only be used once.")
            ->line("If you didn't request this, you can safely ignore this email.");
    }

    private function subject(): string
    {
        return match ($this->purpose) {
            EmailCodePurpose::Login => 'Your Hablas sign-in code',
            EmailCodePurpose::Confirm => 'Confirm it\'s you on Hablas',
        };
    }

    private function intro(): string
    {
        return match ($this->purpose) {
            EmailCodePurpose::Login => 'Use this code to sign in to Hablas.',
            EmailCodePurpose::Confirm => 'Use this code to confirm this action.',
        };
    }
}
