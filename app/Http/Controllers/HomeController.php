<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class HomeController extends Controller
{
    public function __invoke(Request $request): Response|RedirectResponse
    {
        if ($request->query('source') === 'pwa' && $request->user() !== null) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Welcome');
    }
}
