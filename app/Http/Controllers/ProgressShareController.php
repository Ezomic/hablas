<?php

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Progress\BuildProgressSnapshot;
use App\Actions\Progress\GetOrCreateProgressShare;
use App\Actions\Progress\RevokeProgressShare;
use App\Concerns\InteractsWithCurrentUser;
use App\Http\Requests\Progress\RegenerateProgressShareRequest;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProgressShareController extends Controller
{
    use InteractsWithCurrentUser;

    public function show(
        Request $request,
        GetCurrentLanguage $getCurrentLanguage,
        GetOrCreateProgressShare $getOrCreateProgressShare,
        BuildProgressSnapshot $buildProgressSnapshot,
    ): Response {
        $language = $getCurrentLanguage->handle($this->currentUser());

        if ($language === null) {
            return Inertia::render('progress/Share', ['snapshot' => null, 'shareUrl' => null, 'languageId' => null]);
        }

        $share = $getOrCreateProgressShare->handle($this->currentUser(), $language);

        return Inertia::render('progress/Share', [
            'snapshot' => $buildProgressSnapshot->handle($this->currentUser(), $language),
            'shareUrl' => route('progress.public', $share->token),
            'languageId' => $language->id,
        ]);
    }

    /**
     * Acts on the language_id the Share page actually has on screen, rather
     * than re-resolving "current language" — the user's current language can
     * change (another tab, a switch mid-visit) between loading the page and
     * clicking regenerate, and this must invalidate the link they were
     * looking at, not whichever language happens to be current now.
     */
    public function regenerate(
        RegenerateProgressShareRequest $request,
        RevokeProgressShare $revokeProgressShare,
        GetOrCreateProgressShare $getOrCreateProgressShare,
    ): RedirectResponse {
        $language = Language::query()->where('id', $request->validated('language_id'))->firstOrFail();

        $revokeProgressShare->handle($this->currentUser(), $language);
        $getOrCreateProgressShare->handle($this->currentUser(), $language);

        return to_route('progress.share.show');
    }
}
