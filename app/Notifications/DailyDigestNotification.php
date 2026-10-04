<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Language;
use App\Models\User;
use App\Services\OutboundMailLimit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\Middleware\RateLimited;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class DailyDigestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Generous, because the rate-limit middleware releases the job back to the
     * queue when the allowance is spent and every release counts as an
     * attempt. Three tries would exhaust themselves inside a minute of waiting
     * rather than surviving to the next window.
     */
    public int $tries = 25;

    /**
     * Long enough that a released job lands in a fresh window rather than
     * spinning against a spent allowance.
     */
    public int $backoff = 60;

    public function __construct(
        private readonly string $languageName,
        private readonly int $dueReviewCount,
        private readonly int $streakCurrentLength,
        private readonly bool $hasUnsubmittedWeeklyReflection,
    ) {}

    /**
     * Digest mail is bulk: it can wait. Throttling it keeps the shared
     * provider allowance from being spent here, so the inline sign-in code
     * mail still has room to send. See OutboundMailLimit.
     *
     * @return array<int, object>
     */
    public function middleware(User $notifiable): array
    {
        return [new RateLimited(OutboundMailLimit::RATE_LIMITER)];
    }

    /** @return list<string|class-string> */
    public function via(User $notifiable): array
    {
        $channels = ['mail'];

        if ($notifiable->pushSubscriptions()->exists()) {
            $channels[] = WebPushChannel::class;
        }

        return $channels;
    }

    public function toMail(User $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject(__('Your :language learning digest', ['language' => Language::localize($this->languageName)]))
            ->greeting(__('Hi :name,', ['name' => $notifiable->name]));

        if ($this->dueReviewCount > 0) {
            $message->line(__('You have :reviews due.', ['reviews' => $this->dueReviewPhrase()]));
        }

        $message->line(__('Current streak: :streak.', ['streak' => $this->streakPhrase()]));

        if ($this->hasUnsubmittedWeeklyReflection) {
            $message->line(__("You haven't submitted this week's reflection yet."));
        }

        return $message->action(__('Open Hablas'), url('/dashboard'));
    }

    public function toWebPush(User $notifiable): WebPushMessage
    {
        $body = $this->dueReviewCount > 0
            ? __(':reviews due · :streak streak', ['reviews' => $this->dueReviewPhrase(), 'streak' => $this->streakPhrase()])
            : __(':streak streak', ['streak' => $this->streakPhrase()]);

        return (new WebPushMessage)
            ->title(__('Your :language learning digest', ['language' => Language::localize($this->languageName)]))
            ->body($body)
            ->data(['url' => '/dashboard']);
    }

    private function dueReviewPhrase(): string
    {
        return trans_choice(':count review card|:count review cards', $this->dueReviewCount);
    }

    private function streakPhrase(): string
    {
        return trans_choice(':count day|:count days', $this->streakCurrentLength);
    }
}
