<?php

namespace App\Listeners;

use App\Actions\Languages\UnlockLanguageForUser;
use App\Models\Language;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class UnlockSpanishForNewUser
{
    /**
     * Every new user starts with Spanish unlocked — Portuguese is the only
     * language that requires the suggest-and-confirm activation flow.
     * No-ops if Spanish hasn't been seeded yet.
     */
    public function handle(Registered $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        $spanish = Language::query()->where('code', 'es')->first();

        if ($spanish === null) {
            return;
        }

        (new UnlockLanguageForUser)->handle($event->user, $spanish);
    }
}
