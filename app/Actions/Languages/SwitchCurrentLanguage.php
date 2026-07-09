<?php

namespace App\Actions\Languages;

use App\Models\Language;
use App\Models\User;

class SwitchCurrentLanguage
{
    /**
     * Scopes the lookup through the user's own unlocked languages rather
     * than trusting the caller to have validated ownership — naturally
     * 404s if $languageId isn't unlocked for this specific user.
     */
    public function handle(User $user, int $languageId): Language
    {
        $language = $user->unlockedLanguages()->where('languages.id', $languageId)->firstOrFail();

        $user->forceFill(['current_language_id' => $language->id])->save();

        return $language;
    }
}
