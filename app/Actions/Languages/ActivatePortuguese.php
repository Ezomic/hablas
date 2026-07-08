<?php

namespace App\Actions\Languages;

use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ActivatePortuguese
{
    /**
     * Flips Portuguese to active and immediately switches the user's
     * current language to it — the confirmation already happened at the
     * dashboard CTA click, so a second switcher click isn't needed.
     * Wrapped in a transaction so a partial failure can't leave is_active
     * flipped while the user is still pointed at Spanish.
     */
    public function handle(User $user): Language
    {
        return DB::transaction(function () use ($user): Language {
            $portuguese = Language::query()->where('code', 'pt')->firstOrFail();

            $portuguese->forceFill(['is_active' => true])->save();

            (new SwitchCurrentLanguage)->handle($user, $portuguese->id);

            return $portuguese;
        });
    }
}
