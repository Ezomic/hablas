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

        return Inertia::render('progress/Public', [
            'snapshot' => $buildProgressSnapshot->handle($share->user, $share->language),
            'ownerName' => $share->user->name,
        ]);
    }
}
