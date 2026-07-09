<?php

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Progress\BuildProgressSnapshot;
use App\Actions\Progress\GetOrCreateProgressShare;
use App\Actions\Progress\RevokeProgressShare;
use App\Models\ProgressShare;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProgressShareController extends Controller
{
    public function show(
        Request $request,
        GetCurrentLanguage $getCurrentLanguage,
        GetOrCreateProgressShare $getOrCreateProgressShare,
        BuildProgressSnapshot $buildProgressSnapshot,
    ): Response {
        $language = $getCurrentLanguage->handle($request->user());

        if ($language === null) {
            return Inertia::render('progress/Share', ['snapshot' => null, 'shareUrl' => null]);
        }

        $share = $getOrCreateProgressShare->handle($request->user(), $language);

        return Inertia::render('progress/Share', [
            'snapshot' => $buildProgressSnapshot->handle($request->user(), $language),
            'shareUrl' => route('progress.public', $share->token),
        ]);
    }

    public function regenerate(
        Request $request,
        GetCurrentLanguage $getCurrentLanguage,
        RevokeProgressShare $revokeProgressShare,
        GetOrCreateProgressShare $getOrCreateProgressShare,
    ): RedirectResponse {
        $language = $getCurrentLanguage->handle($request->user());

        if ($language === null) {
            return back();
        }

        $existing = ProgressShare::query()
            ->where('user_id', $request->user()->id)
            ->where('language_id', $language->id)
            ->whereNull('revoked_at')
            ->first();

        if ($existing !== null) {
            $revokeProgressShare->handle($existing);
        }

        $getOrCreateProgressShare->handle($request->user(), $language);

        return to_route('progress.share.show');
    }
}
