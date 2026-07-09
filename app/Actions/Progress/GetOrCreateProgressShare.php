<?php

namespace App\Actions\Progress;

use App\Models\Language;
use App\Models\ProgressShare;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GetOrCreateProgressShare
{
    public function handle(User $user, Language $language): ProgressShare
    {
        // Locks the row (or the gap, on engines that support it) for the
        // duration of the transaction so two concurrent calls — two tabs, a
        // double-click on "regenerate" — can't both see no active share and
        // both insert one, leaving two simultaneously-valid tokens.
        return DB::transaction(function () use ($user, $language): ProgressShare {
            $share = ProgressShare::active()
                ->where('user_id', $user->id)
                ->where('language_id', $language->id)
                ->lockForUpdate()
                ->first();

            if ($share !== null) {
                return $share;
            }

            return ProgressShare::query()->create([
                'user_id' => $user->id,
                'language_id' => $language->id,
                'token' => Str::random(48),
            ]);
        });
    }
}
