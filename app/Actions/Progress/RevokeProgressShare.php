<?php

namespace App\Actions\Progress;

use App\Models\Language;
use App\Models\ProgressShare;
use App\Models\User;

class RevokeProgressShare
{
    /**
     * No-ops if the user has no active share for this language — callers
     * (e.g. "regenerate my link") don't need to look one up first just to
     * find out there's nothing to revoke.
     */
    public function handle(User $user, Language $language): void
    {
        $share = ProgressShare::active()
            ->where('user_id', $user->id)
            ->where('language_id', $language->id)
            ->first();

        if ($share !== null) {
            $share->forceFill(['revoked_at' => now()])->save();
        }
    }
}
