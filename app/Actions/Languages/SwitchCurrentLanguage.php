<?php

namespace App\Actions\Languages;

use App\Models\Language;
use App\Models\User;

class SwitchCurrentLanguage
{
    /**
     * Trusts the caller to have already validated that $languageId refers to
     * an active language (see UpdateCurrentLanguageRequest) rather than
     * re-checking is_active here.
     */
    public function handle(User $user, int $languageId): Language
    {
        $language = Language::query()->where('id', $languageId)->firstOrFail();

        $user->forceFill(['current_language_id' => $language->id])->save();

        return $language;
    }
}
