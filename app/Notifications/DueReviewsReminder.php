<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class DueReviewsReminder extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The send is stamped before the job runs, so a retry could only deliver
     * a reminder that is already stale.
     */
    public int $tries = 1;

    private const int TTL_SECONDS = 4 * 3600;

    public function __construct(
        private readonly string $languageName,
        private readonly int $dueRepetitionCount,
    ) {}

    /** @return list<class-string> */
    public function via(User $notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush(User $notifiable): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Reviews are due')
            ->body("{$this->dueRepetitionCount} {$this->languageName} cards are ready to review")
            ->data(['url' => route('review.index', absolute: false)])
            ->options(['TTL' => self::TTL_SECONDS]);
    }
}
