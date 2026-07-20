<?php

namespace App\Http\Controllers;

use App\Actions\Languages\ActivatePortuguese;
use App\Actions\Languages\EvaluatePortugueseActivationEligibility;
use App\Concerns\InteractsWithCurrentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PortugueseActivationController extends Controller
{
    use InteractsWithCurrentUser;

    /**
     * Re-checks eligibility server-side rather than trusting the dashboard
     * CTA's client-side gating — the CTA is only ever shown when eligible,
     * but the endpoint itself must not assume that.
     */
    public function store(Request $request, EvaluatePortugueseActivationEligibility $evaluate, ActivatePortuguese $activate): RedirectResponse
    {
        if (! $evaluate->handle($this->currentUser())) {
            abort(403);
        }

        $activate->handle($this->currentUser());

        return redirect()->route('dashboard');
    }
}
