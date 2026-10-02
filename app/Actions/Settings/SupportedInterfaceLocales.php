<?php

declare(strict_types=1);

namespace App\Actions\Settings;

final class SupportedInterfaceLocales
{
    /** @return list<string> */
    public function handle(): array
    {
        $configured = config('app.supported_locales');

        return is_array($configured)
            ? array_values(array_filter($configured, is_string(...)))
            : [];
    }
}
