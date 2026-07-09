<?php

namespace App\Actions\Languages;

use App\Models\Language;
use App\Models\User;

class UnlockLanguageForUser
{
    /**
     * The single place that ever writes to the user_languages pivot —
     * both the default Spanish unlock on registration and the
     * suggest-and-confirm Portuguese activation flow go through here.
     */
    public function handle(User $user, Language $language): void
    {
        $user->unlockedLanguages()->syncWithoutDetaching([$language->id]);
    }
}
