<?php

declare(strict_types=1);

namespace App\Actions\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Cookie;

final class UpdateInterfaceLocale
{
    private const int ONE_YEAR_IN_MINUTES = 525600;

    public function handle(?User $user, string $locale): void
    {
        $user?->forceFill(['interface_locale' => $locale])->save();

        Cookie::queue(Cookie::make('interface_locale', $locale, self::ONE_YEAR_IN_MINUTES, sameSite: 'lax'));
    }
}
