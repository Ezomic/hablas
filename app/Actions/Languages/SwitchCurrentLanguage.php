<?php

namespace App\Actions\Languages;

use App\Models\Language;
use App\Models\User;

class SwitchCurrentLanguage
{
    /**
     * Trusts the caller to have already validated that $language is active
     * (see UpdateCurrentLanguageRequest) rather than re-checking here.
     */
    public function handle(User $user, Language $language): void
    {
        $user->forceFill(['current_language_id' => $language->id])->save();
    }
}
