<?php

namespace App\Actions\Languages;

use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ActivatePortuguese
{
    public function __construct(
        private readonly SwitchCurrentLanguage $switchCurrentLanguage = new SwitchCurrentLanguage,
        private readonly UnlockLanguageForUser $unlockLanguageForUser = new UnlockLanguageForUser,
    ) {}

    /**
     * Unlocks Portuguese for this specific user and immediately switches
     * their current language to it — the confirmation already happened at
     * the dashboard CTA click, so a second switcher click isn't needed.
     * Wrapped in a transaction so a partial failure can't leave Portuguese
     * unlocked while the user is still pointed at Spanish.
     */
    public function handle(User $user): Language
    {
        return DB::transaction(function () use ($user): Language {
            $portuguese = Language::query()->where('code', 'pt')->firstOrFail();

            $this->unlockLanguageForUser->handle($user, $portuguese);

            $this->switchCurrentLanguage->handle($user, $portuguese->id);

            return $portuguese;
        });
    }
}
