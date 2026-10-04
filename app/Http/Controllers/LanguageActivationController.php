<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Languages\ActivateLanguage;
use App\Actions\Languages\ListActivatableLanguages;
use App\Concerns\InteractsWithCurrentUser;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class LanguageActivationController extends Controller
{
    use InteractsWithCurrentUser;

    /**
     * Re-checks eligibility server-side rather than trusting the dashboard
     * CTA's client-side gating — the CTA is only ever shown when eligible,
     * but the endpoint itself must not assume that.
     */
    public function store(Request $request, Language $language, ListActivatableLanguages $activatable, ActivateLanguage $activate): RedirectResponse
    {
        if (! $activatable->handle($this->currentUser())->contains('id', $language->id)) {
            abort(403);
        }

        $activate->handle($this->currentUser(), $language);

        return redirect()->route('dashboard');
    }
}
