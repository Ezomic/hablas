<?php

namespace App\Actions\Languages;

use App\Models\Language;
use App\Models\User;

class GetCurrentLanguage
{
    /**
     * The user's explicitly selected language, if set and still active,
     * otherwise the single globally active language (Milestone 1 behavior)
     * so users who never picked one keep working as before.
     */
    public function handle(User $user): ?Language
    {
        $current = $user->currentLanguage;

        if ($current !== null && $current->is_active) {
            return $current;
        }

        return Language::active();
    }
}
