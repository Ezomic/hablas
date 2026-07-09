<?php

namespace App\Actions\Languages;

use App\Models\Language;
use App\Models\User;

class GetCurrentLanguage
{
    /**
     * The user's explicitly selected language, if set and still unlocked
     * for them, otherwise their earliest-unlocked language (for virtually
     * every current user this is Spanish, but stays generic without
     * hardcoding a code) so users who never picked one keep working as
     * before.
     */
    public function handle(User $user): ?Language
    {
        $current = $user->currentLanguage;

        if ($current !== null && $user->unlockedLanguages()->where('languages.id', $current->id)->exists()) {
            return $current;
        }

        // Ordered by created_at then id — the id tiebreak keeps this
        // deterministic when two unlocks land in the same second (e.g. the
        // one-time backfill migration inserts several rows for the same
        // user with an identical timestamp).
        return $user->unlockedLanguages()
            ->orderBy('user_languages.created_at')
            ->orderBy('user_languages.id')
            ->first();
    }
}
