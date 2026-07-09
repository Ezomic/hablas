<?php

namespace App\Listeners;

use App\Actions\Languages\SwitchCurrentLanguage;
use App\Actions\Languages\UnlockLanguageForUser;
use App\Models\Language;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

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
            return;
        }

        (new UnlockLanguageForUser)->handle($user, $spanish);
        (new SwitchCurrentLanguage)->handle($user, $spanish->id);
    }
}
