<?php

namespace App\Http\Controllers;

use App\Actions\ComputeBlendedCefrLevel;
use App\Actions\GetUserSkillLevels;
use App\Actions\Languages\EvaluatePortugueseActivationEligibility;
use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\SelectNextUnit;
use App\Actions\Srs\EvaluateSessionHealth;
use App\Actions\Srs\GetDueSrsCards;
use App\Actions\Srs\GetWeakSpotCards;
use App\Actions\Streaks\ReconcileStreak;
use App\Models\UserSkillLevel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(
        Request $request,
        GetUserSkillLevels $getUserSkillLevels,
        ComputeBlendedCefrLevel $computeBlendedCefrLevel,
        ReconcileStreak $reconcileStreak,
        GetDueSrsCards $getDueSrsCards,
        GetWeakSpotCards $getWeakSpotCards,
        GetCurrentLanguage $getCurrentLanguage,
        EvaluateSessionHealth $evaluateSessionHealth,
        SelectNextUnit $selectNextUnit,
        EvaluatePortugueseActivationEligibility $evaluatePortugueseActivationEligibility,
    ): Response {
        $language = $getCurrentLanguage->handle($request->user());
        $streak = $reconcileStreak->handle($request->user());
        $canActivatePortuguese = $evaluatePortugueseActivationEligibility->handle($request->user());

        $streakProp = [
            'currentLength' => $streak->current_length,
            'longestLength' => $streak->longest_length,
            'freezeDaysRemaining' => $streak->freeze_days_remaining,
        ];

        if ($language === null) {
            return Inertia::render('Dashboard', [
                'language' => null,
                'streak' => $streakProp,
                'dueReviewCount' => 0,
                'weakSpotReviewCount' => 0,
                'canActivatePortuguese' => $canActivatePortuguese,
            ]);
        }

        $skillLevels = $getUserSkillLevels->handle($request->user(), $language);
        $sessionNeedsRemediation = $evaluateSessionHealth->handle($request->user(), $language);
        $nextUnit = $sessionNeedsRemediation ? null : $selectNextUnit->handle($request->user(), $language);

        return Inertia::render('Dashboard', [
            'language' => ['code' => $language->code, 'name' => $language->name],
            'blendedLevel' => $computeBlendedCefrLevel->handle($skillLevels)?->value,
            'skillLevels' => $skillLevels->mapWithKeys(fn (UserSkillLevel $skillLevel): array => [
                $skillLevel->skill->value => $skillLevel->cefr_level->value,
            ]),
            'streak' => $streakProp,
            'dueReviewCount' => $getDueSrsCards->count($request->user(), $language),
            'weakSpotReviewCount' => $getWeakSpotCards->count($request->user(), $language),
            'sessionNeedsRemediation' => $sessionNeedsRemediation,
            'nextUnit' => $nextUnit === null ? null : [
                'id' => $nextUnit->id,
                'title' => $nextUnit->title,
                'taskDescription' => $nextUnit->task_description,
            ],
            'canActivatePortuguese' => $canActivatePortuguese,
        ]);
    }
}
