<?php

namespace App\Http\Controllers;

use App\Actions\GetUserSkillLevels;
use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\RecordListeningAttempt;
use App\Actions\SelectExerciseForUser;
use App\Concerns\InteractsWithCurrentUser;
use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Http\Requests\StoreListeningAttemptRequest;
use App\Models\Language;
use App\Models\ListeningExercise;
use App\Models\User;
use App\Models\UserSkillLevel;
use App\Services\SpeechLocaleResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ListeningExerciseController extends Controller
{
    use InteractsWithCurrentUser;

    /**
     * How many times a clip may be replayed before answering. Listening
     * practice that allows unlimited replays quietly becomes reading practice
     * with extra steps, but one pass is unfairly harsh at A1.
     */
    public const MAX_REPLAYS = 2;

    public function index(
        Request $request,
        GetCurrentLanguage $getCurrentLanguage,
        SelectExerciseForUser $selectExercise,
        GetUserSkillLevels $getUserSkillLevels,
        SpeechLocaleResolver $speechLocaleResolver,
    ): Response {
        $language = $getCurrentLanguage->handle($this->currentUser());

        if ($language === null) {
            return Inertia::render('listening/Index', [
                'exercise' => null,
                'speechLocale' => null,
                'maxReplays' => self::MAX_REPLAYS,
            ]);
        }

        $exercise = $selectExercise->handle(
            ListeningExercise::query()
                ->where('language_id', $language->id)
                ->whereIn('cefr_level', $this->audibleLevels($this->currentUser(), $language, $getUserSkillLevels)),
            $this->currentUser(),
        );

        return Inertia::render('listening/Index', [
            'exercise' => $exercise === null ? null : [
                'id' => $exercise->id,
                'title' => $exercise->title,
                'cefrLevel' => $exercise->cefr_level->value,
                // The transcript is what gets spoken. It is deliberately not
                // rendered anywhere: showing it would turn this into reading
                // practice.
                'transcript' => $exercise->transcript,
                'audioUrl' => $exercise->audio_url,
                'questions' => collect($exercise->questions)
                    ->map(fn (array $question): array => [
                        'prompt' => $question['prompt'],
                        'options' => $question['options'],
                    ])
                    ->values(),
            ],
            'speechLocale' => $speechLocaleResolver->forLanguage($language),
            'maxReplays' => self::MAX_REPLAYS,
        ]);
    }

    public function store(
        StoreListeningAttemptRequest $request,
        ListeningExercise $listeningExercise,
        RecordListeningAttempt $recordListeningAttempt,
    ): JsonResponse {
        $result = $recordListeningAttempt->handle(
            $this->currentUser(),
            $listeningExercise,
            $request->answers(),
            min($request->replaysUsed(), self::MAX_REPLAYS),
        );

        return response()->json([
            'score' => $result['attempt']->score,
            'milestone' => $result['milestone'],
        ]);
    }

    /**
     * Clips at or below the level already reached, for the same
     * comprehensible-input reason the reading page filters its passages.
     *
     * @return array<int, string>
     */
    private function audibleLevels(User $user, Language $language, GetUserSkillLevels $getUserSkillLevels): array
    {
        $listening = $getUserSkillLevels->handle($user, $language)
            ->firstWhere('skill', Skill::Listening);

        $ceiling = $listening instanceof UserSkillLevel ? $listening->cefr_level : CefrLevel::A1;

        return collect(CefrLevel::cases())
            ->filter(fn (CefrLevel $level): bool => $level->sortOrder() <= $ceiling->sortOrder())
            ->map(fn (CefrLevel $level): string => $level->value)
            ->values()
            ->all();
    }
}
