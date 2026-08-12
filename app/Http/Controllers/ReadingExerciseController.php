<?php

namespace App\Http\Controllers;

use App\Actions\GetUserSkillLevels;
use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\RecordReadingAttempt;
use App\Actions\SelectExerciseForUser;
use App\Concerns\InteractsWithCurrentUser;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Http\Requests\StoreReadingAttemptRequest;
use App\Models\Language;
use App\Models\ReadingPassage;
use App\Models\User;
use App\Models\UserSkillLevel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReadingExerciseController extends Controller
{
    use InteractsWithCurrentUser;

    public function index(
        Request $request,
        GetCurrentLanguage $getCurrentLanguage,
        SelectExerciseForUser $selectExercise,
        GetUserSkillLevels $getUserSkillLevels,
    ): Response {
        $language = $getCurrentLanguage->handle($this->currentUser());

        if ($language === null) {
            return Inertia::render('reading/Index', ['passage' => null]);
        }

        $passage = $selectExercise->handle(
            ReadingPassage::query()
                ->where('language_id', $language->id)
                ->whereIn('cefr_level', $this->readableLevels($this->currentUser(), $language, $getUserSkillLevels)),
            $this->currentUser(),
        );

        return Inertia::render('reading/Index', [
            'passage' => $passage === null ? null : [
                'id' => $passage->id,
                'title' => $passage->title,
                'body' => $passage->body,
                'cefrLevel' => $passage->cefr_level->value,
                // The answer key stays server side: sending correct_answer to
                // the client would put the whole comprehension check in the
                // page source.
                'questions' => collect($passage->questions)
                    ->map(fn (array $question): array => [
                        'prompt' => $question['prompt'],
                        'options' => $question['options'],
                    ])
                    ->values(),
            ],
        ]);
    }

    public function store(
        StoreReadingAttemptRequest $request,
        ReadingPassage $readingPassage,
        RecordReadingAttempt $recordReadingAttempt,
    ): JsonResponse {
        $result = $recordReadingAttempt->handle(
            $this->currentUser(),
            $readingPassage,
            $request->answers(),
        );

        return response()->json([
            'score' => $result['attempt']->score,
            'milestone' => $result['milestone'],
        ]);
    }

    /**
     * Comprehensible input means reading at or just below the level already
     * reached, not being handed C1 prose on day one. Passages above the user's
     * reading level stay out of the pool until that level moves.
     *
     * @return array<int, string>
     */
    private function readableLevels(User $user, Language $language, GetUserSkillLevels $getUserSkillLevels): array
    {
        $reading = $getUserSkillLevels->handle($user, $language)
            ->firstWhere('skill', Skill::Reading);

        $ceiling = $reading instanceof UserSkillLevel ? $reading->cefr_level : CefrLevel::A1;

        return collect(CefrLevel::cases())
            ->filter(fn (CefrLevel $level): bool => $level->sortOrder() <= $ceiling->sortOrder())
            ->map(fn (CefrLevel $level): string => $level->value)
            ->values()
            ->all();
    }
}
