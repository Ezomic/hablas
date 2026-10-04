<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\DescribeBlendedLevel;
use App\Actions\GetUserSkillLevels;
use App\Actions\IdentifyBlendedLevelCeiling;
use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Languages\ListActivatableLanguages;
use App\Actions\Lessons\DescribeNextLesson;
use App\Actions\Lessons\GetUnseenLessonResults;
use App\Actions\Placement\DetermineRetakeAvailability;
use App\Actions\SelectNextUnit;
use App\Actions\Srs\EvaluateSessionHealth;
use App\Actions\Srs\ForecastReviewLoad;
use App\Actions\Srs\GetDueSrsCards;
use App\Actions\Srs\GetWeakSpotCards;
use App\Actions\Streaks\ReconcileStreak;
use App\Concerns\InteractsWithCurrentUser;
use App\Enums\Skill;
use App\Enums\UnitProgressStatus;
use App\Models\Language;
use App\Models\Unit;
use App\Models\UserSkillLevel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController extends Controller
{
    use InteractsWithCurrentUser;

    public function index(
        Request $request,
        GetUserSkillLevels $getUserSkillLevels,
        DescribeBlendedLevel $describeBlendedLevel,
        IdentifyBlendedLevelCeiling $identifyBlendedLevelCeiling,
        ReconcileStreak $reconcileStreak,
        GetDueSrsCards $getDueSrsCards,
        GetWeakSpotCards $getWeakSpotCards,
        ForecastReviewLoad $forecastReviewLoad,
        GetCurrentLanguage $getCurrentLanguage,
        EvaluateSessionHealth $evaluateSessionHealth,
        SelectNextUnit $selectNextUnit,
        ListActivatableLanguages $listActivatableLanguages,
        DetermineRetakeAvailability $determineRetakeAvailability,
        DescribeNextLesson $describeNextLesson,
        GetUnseenLessonResults $getUnseenLessonResults,
    ): Response {
        $language = $getCurrentLanguage->handle($this->currentUser());
        $streak = $reconcileStreak->handle($this->currentUser());
        $activatableLanguages = $listActivatableLanguages->handle($this->currentUser())
            ->map(fn (Language $activatable): array => ['code' => $activatable->code, 'name' => $activatable->localizedName()])
            ->all();

        $streakProp = [
            'currentLength' => $streak->current_length,
            'longestLength' => $streak->longest_length,
            'freezeDaysRemaining' => $streak->freeze_days_remaining,
            'daysUntilNextFreezeDay' => $streak->daysUntilNextFreezeDay(),
        ];

        if ($language === null) {
            return Inertia::render('Dashboard', [
                'language' => null,
                'streak' => $streakProp,
                'dueReviewCount' => 0,
                'weakSpotReviewCount' => 0,
                'activatableLanguages' => $activatableLanguages,
            ]);
        }

        $skillLevels = $getUserSkillLevels->handle($this->currentUser(), $language);
        $ceiling = $identifyBlendedLevelCeiling->handle($skillLevels);
        $sessionNeedsRemediation = $evaluateSessionHealth->handle($this->currentUser(), $language);
        $nextUnit = $selectNextUnit->handle($this->currentUser(), $language);

        if ($nextUnit !== null && $sessionNeedsRemediation && ! $this->isInProgress($nextUnit)) {
            $nextUnit = null;
        }

        $nextLesson = $nextUnit === null ? null : $describeNextLesson->handle($this->currentUser(), $nextUnit);

        return Inertia::render('Dashboard', [
            'language' => ['code' => $language->code, 'name' => $language->localizedName()],
            'blendedLevel' => $describeBlendedLevel->handle($skillLevels),
            'blendedLevelCeiling' => $ceiling->map(fn (Skill $skill): string => $skill->value)->all(),
            'retakeAvailableOn' => $ceiling->isEmpty() ? [] : $determineRetakeAvailability->handle($this->currentUser(), $language),
            'skillLevels' => $skillLevels->mapWithKeys(fn (UserSkillLevel $skillLevel): array => [
                $skillLevel->skill->value => $skillLevel->displayLevel(),
            ]),
            'streak' => $streakProp,
            'dueReviewCount' => $getDueSrsCards->count($this->currentUser(), $language),
            'weakSpotReviewCount' => $getWeakSpotCards->count($this->currentUser(), $language),
            'reviewForecast' => $forecastReviewLoad->handle($this->currentUser(), $language),
            'sessionNeedsRemediation' => $sessionNeedsRemediation,
            'nextUnit' => $nextUnit === null ? null : [
                'id' => $nextUnit->id,
                'title' => $nextUnit->title,
                'taskDescription' => $nextUnit->task_description,
                'lesson' => $nextLesson,
            ],
            'unseenLessonResults' => $getUnseenLessonResults->handle($this->currentUser(), $language),
            'activatableLanguages' => $activatableLanguages,
        ]);
    }

    /**
     * Remediation only holds back a new unit: one the learner already
     * started stays on the dashboard so they can finish what they began.
     */
    private function isInProgress(Unit $unit): bool
    {
        return $this->currentUser()->unitProgress()
            ->where('unit_id', $unit->id)
            ->where('status', UnitProgressStatus::InProgress)
            ->exists();
    }
}
