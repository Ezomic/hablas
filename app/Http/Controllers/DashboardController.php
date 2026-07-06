<?php

namespace App\Http\Controllers;

use App\Actions\ComputeBlendedCefrLevel;
use App\Actions\GetUserSkillLevels;
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
    ): Response {
        $language = Language::active();

        if ($language === null) {
            return Inertia::render('Dashboard', ['language' => null]);
        }

        $skillLevels = $getUserSkillLevels->handle($request->user(), $language);

        return Inertia::render('Dashboard', [
            'language' => ['code' => $language->code, 'name' => $language->name],
            'blendedLevel' => $computeBlendedCefrLevel->handle($skillLevels)?->value,
            'skillLevels' => $skillLevels->mapWithKeys(fn (UserSkillLevel $skillLevel): array => [
                $skillLevel->skill->value => $skillLevel->cefr_level->value,
            ]),
        ]);
    }
}
