<?php

namespace App\Http\Controllers;

use App\Actions\ComputeBlendedCefrLevel;
use App\Models\Language;
use App\Models\UserSkillLevel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request, ComputeBlendedCefrLevel $computeBlendedCefrLevel): Response
    {
        $language = Language::active();

        if ($language === null) {
            return Inertia::render('Dashboard', ['language' => null]);
        }

        $blendedLevel = $computeBlendedCefrLevel->handle($request->user(), $language);

        $skillLevels = $request->user()->skillLevels()
            ->where('language_id', $language->id)
            ->get()
            ->mapWithKeys(fn (UserSkillLevel $skillLevel): array => [
                $skillLevel->skill->value => $skillLevel->cefr_level->value,
            ]);

        return Inertia::render('Dashboard', [
            'language' => ['code' => $language->code, 'name' => $language->name],
            'blendedLevel' => $blendedLevel?->value,
            'skillLevels' => $skillLevels,
        ]);
    }
}
