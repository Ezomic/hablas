<?php

namespace App\Console\Commands;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Reflections\HasSubmittedReflectionThisWeek;
use App\Actions\Srs\GetDueSrsCards;
use App\Actions\Streaks\ReconcileStreak;
use App\Enums\NotificationFrequency;
use App\Models\User;
use App\Models\UserSetting;
use App\Notifications\DailyDigestNotification;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('digests:send')]
#[Description('Send the daily/weekly email digest to users based on their notification frequency preference')]
class SendDailyDigests extends Command
{
    /**
     * How many days must pass since the last digest before a Weekly user is
     * sent another one, so a once-a-day scheduler run doesn't resend daily.
     */
    private const WEEKLY_INTERVAL_DAYS = 7;

    public function handle(
        GetCurrentLanguage $getCurrentLanguage,
        GetDueSrsCards $getDueSrsCards,
        ReconcileStreak $reconcileStreak,
        HasSubmittedReflectionThisWeek $hasSubmittedReflectionThisWeek,
    ): int {
        User::query()->chunkById(50, function ($users) use (
            $getCurrentLanguage,
            $getDueSrsCards,
            $reconcileStreak,
            $hasSubmittedReflectionThisWeek,
        ) {
            foreach ($users as $user) {
                $settings = UserSetting::query()->firstOrCreate(
                    ['user_id' => $user->id],
                    ['notification_frequency' => NotificationFrequency::Daily],
                );

                if (! $this->isDue($settings)) {
                    continue;
                }

                $language = $getCurrentLanguage->handle($user);

                if ($language === null) {
                    continue;
                }

                $user->notify(new DailyDigestNotification(
                    languageName: $language->name,
                    dueReviewCount: $getDueSrsCards->count($user, $language),
                    streakCurrentLength: $reconcileStreak->handle($user)->current_length,
                    hasUnsubmittedWeeklyReflection: ! $hasSubmittedReflectionThisWeek->handle($user, $language),
                ));

                $settings->forceFill(['last_digest_sent_at' => now()])->save();
            }
        });

        return self::SUCCESS;
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
