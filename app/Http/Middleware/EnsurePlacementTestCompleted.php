<?php

namespace App\Http\Middleware;

use App\Actions\Languages\GetCurrentLanguage;
use App\Concerns\InteractsWithCurrentUser;
use App\Models\PlacementTestAttempt;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlacementTestCompleted
{
    use InteractsWithCurrentUser;

    public function __construct(
        private readonly GetCurrentLanguage $getCurrentLanguage = new GetCurrentLanguage,
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $language = $this->getCurrentLanguage->handle($this->currentUser());

        if ($language === null) {
            return $next($request);
        }

        $hasCompletedPlacement = PlacementTestAttempt::query()
            ->where('user_id', $this->currentUser()->id)
            ->where('language_id', $language->id)
            ->whereNotNull('completed_at')
            ->exists();

        if (! $hasCompletedPlacement) {
            return redirect()->route('placement.index');
        }

        return $next($request);
    }
}
