<?php

namespace App\Listeners;

use App\Actions\Languages\SwitchCurrentLanguage;
use App\Actions\Languages\UnlockLanguageForUser;
use App\Models\Language;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;

class UnlockSpanishForNewUser
{
    /**
     * Every new user starts with Spanish unlocked and selected — Portuguese
     * is the only language that requires the suggest-and-confirm activation
     * flow. Also sets current_language_id explicitly (rather than leaving
     * GetCurrentLanguage to fall back to the earliest-unlocked language on
     * every request) since this is the only place a brand new user's
     * language gets chosen for them. No-ops if Spanish hasn't been seeded
     * yet.
     */
    public function handle(Registered $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $spanish = Language::query()->where('code', 'es')->first();

        if ($spanish === null) {
            $this->reportMissingSpanish($user);

            return;
        }

        (new UnlockLanguageForUser)->handle($user, $spanish);
        (new SwitchCurrentLanguage)->handle($user, $spanish->id);
    }

    /**
     * A missing 'es' row is legitimate in tests — UserFactory creates users
     * without seeding languages — so this stays a no-op rather than throwing.
     * But in a real environment it means the new user silently registered
     * with no course, which is exactly the invisible-broken-account failure.
     * Surface it in the logs (as an error in production, where a missing 'es'
     * row is never legitimate) instead of returning silently.
     */
    private function reportMissingSpanish(User $user): void
    {
        if (app()->environment('testing')) {
            return;
        }

        $message = 'UnlockSpanishForNewUser: no "es" language row found; new user registered with no course. The languages table is likely unseeded.';
        $context = ['user_id' => $user->id];

        app()->isProduction()
            ? Log::error($message, $context)
            : Log::warning($message, $context);
    }
}
