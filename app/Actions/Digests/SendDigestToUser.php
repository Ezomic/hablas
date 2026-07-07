<?php

namespace App\Actions\Digests;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Reflections\HasSubmittedReflectionThisWeek;
use App\Actions\Settings\GetUserSettings;
use App\Actions\Srs\GetDueSrsCards;
use App\Actions\Streaks\ReconcileStreak;
use App\Enums\NotificationFrequency;
use App\Models\User;
use App\Models\UserSetting;
use App\Notifications\DailyDigestNotification;

class SendDigestToUser
{
    /**
     * How many days must pass since the last digest before a Weekly user is
     * sent another one, so a once-a-day scheduler run doesn't resend daily.
     */
    private const WEEKLY_INTERVAL_DAYS = 7;

    public function handle(User $user): void
    {
        $settings = (new GetUserSettings)->handle($user);

        if (! $this->isDue($settings)) {
            return;
        }

        $language = (new GetCurrentLanguage)->handle($user);

        if ($language === null) {
            return;
        }

        $user->notify(new DailyDigestNotification(
            languageName: $language->name,
            dueReviewCount: (new GetDueSrsCards)->count($user, $language),
            streakCurrentLength: (new ReconcileStreak)->handle($user)->current_length,
            hasUnsubmittedWeeklyReflection: ! (new HasSubmittedReflectionThisWeek)->handle($user, $language),
        ));

        $settings->forceFill(['last_digest_sent_at' => now()])->save();
    }

    private function isDue(UserSetting $settings): bool
    {
        return match ($settings->notification_frequency) {
            NotificationFrequency::Never => false,
            NotificationFrequency::Daily => true,
            NotificationFrequency::Weekly => $settings->last_digest_sent_at === null
                || $settings->last_digest_sent_at->diffInDays(now()) >= self::WEEKLY_INTERVAL_DAYS,
        };
    }
}
