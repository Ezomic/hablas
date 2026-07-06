<?php

namespace App\Http\Controllers;

use App\Actions\ScorePlacementTest;
use App\Actions\SkipPlacementTest;
use App\Http\Requests\StorePlacementTestRequest;
use App\Models\Language;
use App\Models\PlacementTestItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PlacementTestController extends Controller
{
    public function index(): Response
    {
        $language = Language::query()->where('is_active', true)->firstOrFail();

        $items = PlacementTestItem::query()
            ->where('language_id', $language->id)
            ->orderBy('sort_order')
            ->get(['id', 'skill', 'prompt', 'options', 'sort_order']);

        return Inertia::render('placement/Index', [
            'items' => $items,
        ]);
    }

    public function store(StorePlacementTestRequest $request, ScorePlacementTest $scorePlacementTest): RedirectResponse
    {
        $language = Language::query()->where('is_active', true)->firstOrFail();

        $scorePlacementTest->handle($request->user(), $language, $request->validated('responses'));

        return redirect()->route('dashboard');
    }

    public function skip(Request $request, SkipPlacementTest $skipPlacementTest): RedirectResponse
    {
        $language = Language::query()->where('is_active', true)->firstOrFail();

        $skipPlacementTest->handle($request->user(), $language);

        return redirect()->route('dashboard');
    }
}
