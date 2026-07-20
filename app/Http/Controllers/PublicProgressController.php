<?php

namespace App\Http\Controllers;

use App\Actions\Progress\BuildProgressSnapshot;
use App\Models\ProgressShare;
use Inertia\Inertia;
use Inertia\Response;

class PublicProgressController extends Controller
{
    public function show(string $token, BuildProgressSnapshot $buildProgressSnapshot): Response
    {
        $share = ProgressShare::active()
            ->where('token', $token)
            ->firstOrFail();

        $user = $share->user;
        $language = $share->language;

        abort_if($user === null || $language === null, 404);

        return Inertia::render('progress/Public', [
            'snapshot' => $buildProgressSnapshot->handle($user, $language),
            'ownerName' => $user->name,
        ]);
    }
}
