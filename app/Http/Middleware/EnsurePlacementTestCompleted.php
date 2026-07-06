<?php

namespace App\Http\Middleware;

use App\Models\Language;
use App\Models\PlacementTestAttempt;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlacementTestCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $language = Language::query()->where('is_active', true)->first();

        if ($language === null) {
            return $next($request);
        }

        $hasCompletedPlacement = PlacementTestAttempt::query()
            ->where('user_id', $request->user()->id)
            ->where('language_id', $language->id)
            ->whereNotNull('completed_at')
            ->exists();

        if (! $hasCompletedPlacement) {
            return redirect()->route('placement.index');
        }

        return $next($request);
    }
}
