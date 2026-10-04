<?php

declare(strict_types=1);

namespace App\Actions\Languages;

use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class ActivateLanguage
{
    public function __construct(
        private readonly SwitchCurrentLanguage $switchCurrentLanguage = new SwitchCurrentLanguage,
        private readonly UnlockLanguageForUser $unlockLanguageForUser = new UnlockLanguageForUser,
    ) {}

    /**
     * Unlocks the language for this specific user and immediately switches
     * their current language to it, since the confirmation already happened
     * at the dashboard CTA click. Wrapped in a transaction so a partial
     * failure can't leave it unlocked while the user is still pointed at
     * another language.
     */
    public function handle(User $user, Language $language): Language
    {
        return DB::transaction(function () use ($user, $language): Language {
            $this->unlockLanguageForUser->handle($user, $language);

            $this->switchCurrentLanguage->handle($user, $language->id);

            return $language;
        });
    }
}
