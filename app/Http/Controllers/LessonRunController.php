<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Lessons\PresentLessonRun;
use App\Actions\Lessons\SettleLessonRun;
use App\Actions\Lessons\StartLessonRun;
use App\Concerns\InteractsWithCurrentUser;
use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Enums\LessonStage;
use App\Http\Requests\StartLessonRunRequest;
use App\Models\Lesson;
use App\Models\LessonRun;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class LessonRunController extends Controller
{
    use InteractsWithCurrentUser;

    public function store(StartLessonRunRequest $request, Unit $unit, Lesson $lesson, StartLessonRun $startLessonRun): RedirectResponse
    {
        $kind = $request->kind();

        if ($lesson->stage === LessonStage::Check && $kind === LessonRunKind::Lesson) {
            $kind = $startLessonRun->openKind($this->currentUser(), $lesson) ?? LessonRunKind::Check;
        }

        return to_route('lesson-runs.show', $startLessonRun->handle($this->currentUser(), $lesson, $kind));
    }

    public function show(LessonRun $lessonRun, GetCurrentLanguage $getCurrentLanguage, SettleLessonRun $settleLessonRun, PresentLessonRun $presentLessonRun): Response
    {
        $user = $this->currentUser();
        abort_unless($lessonRun->user_id === $user->id, 404);

        $language = $getCurrentLanguage->handle($user);
        $unit = $lessonRun->lesson?->unit;

        abort_if($language === null || $unit === null || $unit->language_id !== $language->id, 404);

        $settleLessonRun->handle($lessonRun);
        $lessonRun->refresh();

        $props = $presentLessonRun->handle($lessonRun);

        if ($lessonRun->status === LessonRunStatus::Completed && $lessonRun->summary_seen_at === null) {
            $lessonRun->forceFill(['summary_seen_at' => now()])->save();
        }

        return Inertia::render('lessons/Play', $props);
    }
}
