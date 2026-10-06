<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetUserSkillLevels;
use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\RecordReadingAttempt;
use App\Concerns\InteractsWithCurrentUser;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Http\Requests\StoreReadingAttemptRequest;
use App\Models\Language;
use App\Models\ReadingAttempt;
use App\Models\ReadingPassage;
use App\Models\User;
use App\Models\UserSkillLevel;
use App\Services\SpeechLocaleResolver;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

final class ReadingExerciseController extends Controller
{
    use InteractsWithCurrentUser;

    public function index(
        GetCurrentLanguage $getCurrentLanguage,
        GetUserSkillLevels $getUserSkillLevels,
    ): Response {
        $user = $this->currentUser();
        $language = $getCurrentLanguage->handle($user);

        if ($language === null) {
            return Inertia::render('reading/Index', ['stories' => []]);
        }

        $stories = ReadingPassage::query()
            ->where('language_id', $language->id)
            ->whereIn('cefr_level', $this->readableLevels($user, $language, $getUserSkillLevels))
            ->orderBy('id')
            ->get();

        $best = [];

        foreach (ReadingAttempt::query()
            ->where('user_id', $user->id)
            ->whereIn('reading_passage_id', $stories->modelKeys())
            ->get() as $attempt) {
            $best[$attempt->reading_passage_id] = max($best[$attempt->reading_passage_id] ?? 0.0, $attempt->score);
        }

        return Inertia::render('reading/Index', [
            'stories' => $stories->map(fn (ReadingPassage $story): array => [
                'id' => $story->id,
                'title' => $story->title,
                'cefrLevel' => $story->cefr_level->value,
                'questions' => count($story->questions),
                'best' => $best[$story->id] ?? null,
            ])->values(),
        ]);
    }

    public function show(
        ReadingPassage $readingPassage,
        GetCurrentLanguage $getCurrentLanguage,
        GetUserSkillLevels $getUserSkillLevels,
        SpeechLocaleResolver $speechLocaleResolver,
    ): Response {
        $user = $this->currentUser();
        $language = $getCurrentLanguage->handle($user);

        abort_if(
            $language === null
                || $readingPassage->language_id !== $language->id
                || ! in_array($readingPassage->cefr_level->value, $this->readableLevels($user, $language, $getUserSkillLevels), true),
            404,
        );

        return Inertia::render('reading/Show', [
            'passage' => [
                'id' => $readingPassage->id,
                'title' => $readingPassage->title,
                'body' => $readingPassage->body,
                'cefrLevel' => $readingPassage->cefr_level->value,
                'glosses' => (object) ($readingPassage->glosses ?? []),
                'locale' => $speechLocaleResolver->forLanguage($language),
                // The answer key stays server side: sending correct_answer to
                // the client would put the whole comprehension check in the
                // page source.
                'questions' => collect($readingPassage->questions)
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
            'correct' => collect($readingPassage->questions)->pluck('correct_answer')->values(),
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
