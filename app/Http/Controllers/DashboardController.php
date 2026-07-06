<?php

namespace App\Http\Controllers;

use App\Actions\ComputeBlendedCefrLevel;
use App\Actions\GetUserSkillLevels;
use App\Actions\Srs\GetDueSrsCards;
use App\Actions\Streaks\ReconcileStreak;
use App\Models\Language;
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
    ): Response {
        $language = Language::active();
        $streak = $reconcileStreak->handle($request->user());

        $streakProp = [
            'currentLength' => $streak->current_length,
            'longestLength' => $streak->longest_length,
            'freezeDaysRemaining' => $streak->freeze_days_remaining,
        ];

        if ($language === null) {
            return Inertia::render('Dashboard', ['language' => null, 'streak' => $streakProp, 'dueReviewCount' => 0]);
        }

        $skillLevels = $getUserSkillLevels->handle($request->user(), $language);

        return Inertia::render('Dashboard', [
            'language' => ['code' => $language->code, 'name' => $language->name],
            'blendedLevel' => $computeBlendedCefrLevel->handle($skillLevels)?->value,
            'skillLevels' => $skillLevels->mapWithKeys(fn (UserSkillLevel $skillLevel): array => [
                $skillLevel->skill->value => $skillLevel->cefr_level->value,
            ]),
            'streak' => $streakProp,
            'dueReviewCount' => $getDueSrsCards->handle($request->user(), $language)->count(),
        ]);
    }
}
