<?php

declare(strict_types=1);

namespace App\Actions\Notifications;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Settings\GetUserSettings;
use App\Enums\NotificationFrequency;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;
use App\Notifications\DueReviewsReminder;
use Carbon\CarbonImmutable;

final class SendDueReviewReminder
{
    public const int THRESHOLD = 20;

    private const int HOURS_AFTER_LAST_REVIEW = 4;

    private const string TIMEZONE = 'Europe/Amsterdam';

    private const int FIRST_HOUR = 12;

    private const int LAST_HOUR = 21;

    public function __construct(
        private readonly GetCurrentLanguage $getCurrentLanguage = new GetCurrentLanguage,
        private readonly GetUserSettings $getUserSettings = new GetUserSettings,
    ) {}

    /**
     * Pushes when the due repetitions in the current deck cross THRESHOLD:
     * below it the last time the learner either reviewed or was told (by the
     * digest or an earlier reminder), at or above it now. That needs no stored
     * count: only a review moves a card's due date, and none happened since
     * that moment, so the due count then can be read back from the cards.
     */
    public function handle(User $user): bool
    {
        if (! $this->withinReminderHours()) {
            return false;
        }

        $settings = $this->getUserSettings->handle($user);

        if ($settings->notification_frequency !== NotificationFrequency::Daily
            || $settings->last_due_reminder_sent_at?->isToday() === true
            || ! $user->pushSubscriptions()->exists()) {
            return false;
        }

        $language = $this->getCurrentLanguage->handle($user);

        if ($language === null) {
            return false;
        }

        $lastReviewedAt = $this->lastReviewedAt($user, $language);

        if ($lastReviewedAt?->isAfter(now()->subHours(self::HOURS_AFTER_LAST_REVIEW)) === true) {
            return false;
        }

        $dueNow = $this->dueRepetitionsAt($user, $language, now()->toImmutable());
        $lastSeenAt = $this->latest($lastReviewedAt, $settings->last_digest_sent_at, $settings->last_due_reminder_sent_at);

        if ($dueNow < self::THRESHOLD
            || ($lastSeenAt !== null && $this->dueRepetitionsAt($user, $language, $lastSeenAt) >= self::THRESHOLD)) {
            return false;
        }

        $user->notify(new DueReviewsReminder($language->name, $dueNow));
        $settings->forceFill(['last_due_reminder_sent_at' => now()])->save();

        return true;
    }

    /**
     * Starts after the 08:00 UTC digest (10:00 or 09:00 in Amsterdam), so a
     * reminder never lands just before it, and stops before the night.
     */
    private function withinReminderHours(): bool
    {
        $hour = now(self::TIMEZONE)->hour;

        return $hour >= self::FIRST_HOUR && $hour <= self::LAST_HOUR;
    }

    private function lastReviewedAt(User $user, Language $language): ?CarbonImmutable
    {
        return SrsCard::query()
            ->where('user_id', $user->id)
            ->where('language_id', $language->id)
            ->whereNotNull('last_reviewed_at')
            ->latest('last_reviewed_at')
            ->first(['last_reviewed_at'])
            ?->last_reviewed_at;
    }

    private function dueRepetitionsAt(User $user, Language $language, CarbonImmutable $moment): int
    {
        return SrsCard::query()
            ->repetitionsInDeck($user, $language)
            ->where('due_at', '<=', $moment)
            ->count();
    }

    private function latest(?CarbonImmutable ...$moments): ?CarbonImmutable
    {
        $latest = null;

        foreach ($moments as $moment) {
            if ($moment !== null && ($latest === null || $moment->isAfter($latest))) {
                $latest = $moment;
            }
        }

        return $latest;
    }
}
