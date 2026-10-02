<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Actions\Settings\ResolveInterfaceLocale;
use App\Actions\Settings\SupportedInterfaceLocales;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetLocale
{
    public function __construct(
        private readonly ResolveInterfaceLocale $resolveInterfaceLocale,
        private readonly SupportedInterfaceLocales $supportedInterfaceLocales,
    ) {}

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveInterfaceLocale->handle($request);

        app()->setLocale($locale);

        $user = $request->user();

        if ($user !== null && $user->interface_locale === null && count($this->supportedInterfaceLocales->handle()) > 1) {
            $user->forceFill(['interface_locale' => $locale])->save();
        }

        return $next($request);
    }
}
