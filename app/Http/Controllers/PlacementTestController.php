<?php

namespace App\Http\Controllers;

use App\Actions\ComputeBlendedCefrLevel;
use App\Actions\GetUserSkillLevels;
use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\NotifyOnBlendedLevelIncrease;
use App\Actions\ScorePlacementTest;
use App\Actions\SkipPlacementTest;
use App\Http\Requests\StorePlacementTestRequest;
use App\Models\PlacementTestItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PlacementTestController extends Controller
{
    public function index(Request $request, GetCurrentLanguage $getCurrentLanguage): Response
    {
        $language = $getCurrentLanguage->handle($request->user()) ?? abort(404);

        $items = PlacementTestItem::query()
            ->where('language_id', $language->id)
            ->orderBy('sort_order')
            ->get(['id', 'skill', 'prompt', 'options', 'sort_order']);

        return Inertia::render('placement/Index', [
            'items' => $items,
        ]);
    }

    public function store(
        StorePlacementTestRequest $request,
        ScorePlacementTest $scorePlacementTest,
        GetCurrentLanguage $getCurrentLanguage,
        GetUserSkillLevels $getUserSkillLevels,
        ComputeBlendedCefrLevel $computeBlendedCefrLevel,
        NotifyOnBlendedLevelIncrease $notifyOnBlendedLevelIncrease,
    ): RedirectResponse {
        $language = $getCurrentLanguage->handle($request->user()) ?? abort(404);
        $levelBefore = $computeBlendedCefrLevel->handle($getUserSkillLevels->handle($request->user(), $language));

        $scorePlacementTest->handle($request->user(), $language, $request->validated('responses'));

        $notifyOnBlendedLevelIncrease->handle($request->user(), $language, $levelBefore);

        return redirect()->route('dashboard');
    }

    public function skip(Request $request, SkipPlacementTest $skipPlacementTest, GetCurrentLanguage $getCurrentLanguage): RedirectResponse
    {
        $language = $getCurrentLanguage->handle($request->user()) ?? abort(404);

        $skipPlacementTest->handle($request->user(), $language);

        return redirect()->route('dashboard');
    }
}
