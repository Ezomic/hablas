<?php

declare(strict_types=1);

namespace App\Actions\Settings;

use Illuminate\Http\Request;

final class ResolveInterfaceLocale
{
    public function __construct(
        private readonly SupportedInterfaceLocales $supportedInterfaceLocales = new SupportedInterfaceLocales,
    ) {}

    public function handle(Request $request): string
    {
        $supported = $this->supportedInterfaceLocales->handle();

        $stored = $request->user()?->interface_locale;

        if (is_string($stored) && in_array($stored, $supported, true)) {
            return $stored;
        }

        $cookie = $request->cookie('interface_locale');

        if (is_string($cookie) && in_array($cookie, $supported, true)) {
            return $cookie;
        }

        foreach ($request->getLanguages() as $language) {
            $primary = strtolower(strtok(str_replace('_', '-', $language), '-') ?: '');

            if (in_array($primary, $supported, true)) {
                return $primary;
            }
        }

        $default = config('app.locale');

        return is_string($default) ? $default : 'en';
    }
}
