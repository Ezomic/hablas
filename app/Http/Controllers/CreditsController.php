<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Speech\SpeechCredits;
use Inertia\Inertia;
use Inertia\Response;

final class CreditsController extends Controller
{
    public function __invoke(SpeechCredits $credits): Response
    {
        return Inertia::render('Credits', ['credits' => $credits->handle()]);
    }
}
