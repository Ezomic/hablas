<?php

namespace App\Actions\Progress;

use App\Models\Language;
use App\Models\ProgressShare;
use App\Models\User;
use Illuminate\Support\Str;

class GetOrCreateProgressShare
{
    public function handle(User $user, Language $language): ProgressShare
    {
        $share = ProgressShare::query()
            ->where('user_id', $user->id)
            ->where('language_id', $language->id)
            ->whereNull('revoked_at')
            ->first();

        if ($share !== null) {
            return $share;
        }

        return ProgressShare::query()->create([
            'user_id' => $user->id,
            'language_id' => $language->id,
            'token' => Str::random(48),
        ]);
    }
}
