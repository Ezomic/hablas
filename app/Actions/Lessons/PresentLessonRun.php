<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Actions\Settings\GetUserSettings;
use App\Enums\ExerciseFamily;
use App\Enums\LessonExerciseFormat;
use App\Enums\LessonRunKind;
use App\Enums\LessonRunStatus;
use App\Enums\LessonState;
use App\Lessons\SpokenTexts;
use App\Models\Lesson;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\Unit;
use App\Models\User;
use App\Models\VocabularyItem;
use App\Services\LessonProgress;
use App\Services\SpeechLocaleResolver;
use App\Services\TypingSupport;
use App\Speech\SpeechClipResolver;
use LogicException;

final class PresentLessonRun
{
    public function __construct(
        private readonly SpeechClipResolver $speechClipResolver,
        private readonly SpeechLocaleResolver $speechLocaleResolver = new SpeechLocaleResolver,
        private readonly SummarizeLessonRun $summarizeLessonRun = new SummarizeLessonRun,
        private readonly LessonProgress $lessonProgress = new LessonProgress,
        private readonly GetUserSettings $getUserSettings = new GetUserSettings,
        private readonly TypingSupport $typingSupport = new TypingSupport,
    ) {}

    /**
     * What the player needs to play a run and to rebuild its queue: the plan
     * with each exercise's payload and substitute, the answers so far, the
     * stage's settings and, once completed, the stored result.
     *
     * A check-kind run gives no verdict, so its payloads carry no answer
     * keys: nothing on the device can show or grade them, and the exercise
     * key, which names the word, is replaced by the id. Once a run is
     * completed it also carries its summary and the lesson to play next.
     *
     * What a learner has to hear is sent as audio, never as readable text: a
     * dictation never carries its text, and neither does a spoken answer, and
     * a check strips the spoken text of every listening and speaking exercise
     * as well as the answers.
     *
     * @return array<string, mixed>
     */
    public function handle(LessonRun $run): array
    {
        $lesson = $run->lesson ?? throw new LogicException("Run {$run->id} has no lesson.");
        $unit = $lesson->unit ?? throw new LogicException("Lesson {$lesson->id} has no unit.");
        $language = $unit->language ?? throw new LogicException("Unit {$unit->id} has no language.");
        $stage = $lesson->stage;
        $hidesAnswers = $run->kind->isCheck();

        $exercises = LessonExercise::query()
            ->whereIn('id', $run->planExerciseIds())
            ->with(['substitute.targets.targetable', 'targets.targetable'])
            ->get()
            ->keyBy('id');

        $clips = $this->speechClipResolver->resolveBoth($language->code, $this->spokenTexts(array_values($exercises->all()), $hidesAnswers));
        $user = $run->user ?? throw new LogicException("Run {$run->id} has no user.");
        $settings = $this->getUserSettings->handle($user);

        $this->typingSupport->preload($user->id, $this->wordIds($exercises->values()->all()));

        $plan = [];

        foreach ($run->plan as $entry) {
            $exercise = $exercises->get($entry['id']);

            if ($exercise === null) {
                continue;
            }

            $plan[] = [
                ...$this->exercise($exercise, $hidesAnswers, $clips, $user),
                'origin' => $entry['origin'],
                'substitute' => $exercise->substitute === null ? null : $this->exercise($exercise->substitute, $hidesAnswers, $clips, $user),
            ];
        }

        $plan = $hidesAnswers ? $plan : $this->introducingNewForms($plan);

        $completed = $run->status === LessonRunStatus::Completed;

        return [
            'unit' => ['id' => $unit->id, 'title' => $unit->title],
            'run' => [
                'id' => $run->id,
                'kind' => $run->kind->value,
                'status' => $run->status->value,
                'probeSet' => $run->probe_set,
                'seed' => $run->seed,
                'startedAt' => $run->started_at->toIso8601String(),
                'result' => $completed ? $run->result : null,
                'summary' => $completed ? $this->summarizeLessonRun->handle($run) : null,
                'next' => $completed ? $this->next($run, $unit) : null,
                'remediation' => $completed && $run->kind !== LessonRunKind::Lesson ? $this->lessonProgress->remediation($user, $unit) : null,
                'summarySeen' => $run->summary_seen_at !== null,
            ],
            'lesson' => ['id' => $lesson->id, 'unitId' => $unit->id, 'stage' => $stage->value, 'title' => $stage->label(), 'position' => $lesson->position],
            'settings' => [
                'feedback' => ! $run->kind->isCheck(),
                'hintsAreFree' => $stage->hintsAreFree(),
                'audioSpeed' => $stage->audioSpeed(),
                'replayLimit' => $stage->replayLimit(),
                'offersSlowerAudio' => $stage->offersSlowerAudio(),
                'speechLocale' => $this->speechLocaleResolver->forLanguage($language),
                'pauses' => [
                    'listening' => $settings->activePause(ExerciseFamily::Listening)?->toIso8601String(),
                    'speaking' => $settings->activePause(ExerciseFamily::Speaking)?->toIso8601String(),
                ],
            ],
            'plan' => $plan,
            'answers' => $run->answers()->orderBy('id')->get()->map(fn (LessonAnswer $answer): array => [
                'step' => $answer->step,
                'exerciseId' => $answer->lesson_exercise_id,
                'attempt' => $answer->attempt,
                'hinted' => $answer->hinted,
                'skipped' => $answer->skipped,
                'skipReason' => $answer->skip_reason?->value,
                'correct' => $hidesAnswers ? null : $answer->is_correct,
                'flagged' => $answer->flagged_at !== null,
                'settled' => $answer->skipped ? false : ($hidesAnswers || $answer->settlesExercise()),
            ])->all(),
        ];
    }

    /**
     * A typed gap that asks for a word no earlier exercise has shown as a right
     * answer would ask the learner to produce a form they have never seen, so
     * its answer is sent as what to type. Production comes after recognition:
     * once a form has been shown it is asked for like any other. A check never
     * shows an answer, so it is left as it is.
     *
     * @param  list<array<string, mixed>>  $plan
     * @return list<array<string, mixed>>
     */
    private function introducingNewForms(array $plan): array
    {
        $seen = [];

        foreach ($plan as $index => $entry) {
            $payload = is_array($entry['payload'] ?? null) ? $entry['payload'] : [];
            $format = is_string($entry['format'] ?? null) ? $entry['format'] : '';
            $shown = $this->shownAnswers($format, $payload);

            if ($format === LessonExerciseFormat::TypeGap->value) {
                $answer = $shown[0] ?? '';

                if ($answer !== '' && array_diff($this->wordsOf($answer), $seen) !== []) {
                    $plan[$index]['payload'] = [...$payload, 'introduce' => $answer];
                }
            }

            foreach ($shown as $text) {
                array_push($seen, ...$this->wordsOf($text));
            }
        }

        return $plan;
    }

    /**
     * The target-language text an exercise shows as right: the answer a choice
     * gives, the sentence a build or a repeat has, or the first accepted answer
     * of a typed gap, which the learner is about to be asked for.
     *
     * @param  array<mixed>  $payload
     * @return list<string>
     */
    private function shownAnswers(string $format, array $payload): array
    {
        $accepted = array_values(array_filter(array_map(fn (mixed $entry): string => is_string($entry) ? $entry : '', is_array($payload['accepted'] ?? null) ? $payload['accepted'] : [])));
        $text = is_string($payload['text'] ?? null) ? $payload['text'] : '';
        $answer = is_string($payload['answer'] ?? null) ? $payload['answer'] : '';

        return array_values(array_filter(match ($format) {
            LessonExerciseFormat::ChooseGap->value, LessonExerciseFormat::ChooseWord->value, LessonExerciseFormat::ChooseMeaning->value => [$answer],
            LessonExerciseFormat::ListenChoose->value, LessonExerciseFormat::SpeakRepeat->value => [$text, $answer],
            LessonExerciseFormat::BuildSentence->value, LessonExerciseFormat::TypeGap->value => array_slice($accepted, 0, 1),
            LessonExerciseFormat::TeachWord->value => [is_string($payload['term'] ?? null) ? $payload['term'] : ''],
            default => [],
        }));
    }

    /** @return list<string> */
    private function wordsOf(string $text): array
    {
        return array_values(array_filter(preg_split('/[^\p{L}]+/u', mb_strtolower($text)) ?: [], fn (string $word): bool => $word !== ''));
    }

    /**
     * The first lesson of the unit that is open, or waiting for its day.
     *
     * @return array{lessonId: int, title: string, position: int, stage: string, state: string}|null
     */
    private function next(LessonRun $run, Unit $unit): ?array
    {
        $user = $run->user ?? throw new LogicException("Run {$run->id} has no user.");
        $states = $this->lessonProgress->states($user, $unit);

        foreach (Lesson::query()->where('unit_id', $unit->id)->playable()->orderBy('position')->get() as $lesson) {
            $state = $states[$lesson->id] ?? LessonState::Coming;

            if (in_array($state, [LessonState::Available, LessonState::InProgress, LessonState::OpensTomorrow], true)) {
                return ['lessonId' => $lesson->id, 'title' => $lesson->stage->label(), 'position' => $lesson->position, 'stage' => $lesson->stage->value, 'state' => $state->value];
            }
        }

        return null;
    }

    /**
     * @param  array<array-key, array{audioUrl: string|null, audioSlowUrl: string|null}>  $clips
     * @return array{id: int, key: string, block: string, format: string, payload: array<string, mixed>}
     */
    private function exercise(LessonExercise $exercise, bool $hidesAnswers, array $clips, User $user): array
    {
        if ($exercise->format === LessonExerciseFormat::ListenPassage) {
            return $this->summary($exercise, $hidesAnswers, $this->listenPassage($exercise->payload, $hidesAnswers, $clips));
        }

        $payload = match (true) {
            $this->isSpoken($exercise->format) => $this->allowed($exercise->format, $exercise->payload, $hidesAnswers),
            $hidesAnswers => $this->withoutKeys($exercise->payload),
            default => $this->withoutSpans($exercise->payload),
        };

        $payload = $this->withClips($exercise->format, $exercise->payload, $payload, $hidesAnswers, $clips);

        return $this->summary($exercise, $hidesAnswers, $hidesAnswers ? $payload : $this->withMask($exercise, $payload, $user));
    }

    /**
     * A typed word in a lesson gives some of its letters, fewer each time the
     * learner types it right, so the support fades with what they know. A
     * check gives none.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function withMask(LessonExercise $exercise, array $payload, User $user): array
    {
        if ($exercise->format !== LessonExerciseFormat::TypeWord) {
            return $payload;
        }

        $item = $exercise->targets->map(fn ($target) => $target->targetable)->first(fn ($targetable): bool => $targetable instanceof VocabularyItem);

        if (! $item instanceof VocabularyItem) {
            return $payload;
        }

        unset($payload['hint']);

        $mask = $this->typingSupport->maskFor($user->id, $item);

        if ($mask === null) {
            return $payload;
        }

        $revealed = $this->typingSupport->revealed($user->id, $item);

        return [...$payload, 'mask' => $mask, 'hintLetters' => $this->typingSupport->hintLetters($item->term, $revealed)];
    }

    /**
     * @param  array<int, LessonExercise>  $exercises
     * @return list<int>
     */
    private function wordIds(array $exercises): array
    {
        $ids = [];

        foreach ($exercises as $exercise) {
            foreach ([$exercise, $exercise->substitute] as $candidate) {
                if ($candidate === null || $candidate->format !== LessonExerciseFormat::TypeWord) {
                    continue;
                }

                foreach ($candidate->targets as $target) {
                    if ($target->targetable instanceof VocabularyItem) {
                        $ids[] = $target->targetable->id;
                    }
                }
            }
        }

        return array_values(array_unique($ids));
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array{id: int, key: string, block: string, format: string, payload: array<string, mixed>}
     */
    private function summary(LessonExercise $exercise, bool $hidesAnswers, array $payload): array
    {
        return [
            'id' => $exercise->id,
            'key' => $hidesAnswers ? (string) $exercise->id : $exercise->key,
            'block' => $exercise->block,
            'format' => $exercise->format->value,
            'payload' => $payload,
        ];
    }

    /**
     * A dialogue that is heard goes out as one clip per line. Its text is sent
     * only in a lesson, where the verdict shows the transcript afterwards; a
     * check never sends it, nor the answers.
     *
     * @param  array<string, mixed>  $payload
     * @param  array<array-key, array{audioUrl: string|null, audioSlowUrl: string|null}>  $clips
     * @return array<string, mixed>
     */
    private function listenPassage(array $payload, bool $hidesAnswers, array $clips): array
    {
        $lines = [];

        foreach (is_array($payload['dialogue'] ?? null) ? $payload['dialogue'] : [] as $line) {
            $text = is_array($line) && is_string($line['text'] ?? null) ? $line['text'] : null;

            if ($text === null) {
                continue;
            }

            $lines[] = [
                'speaker' => is_string($line['speaker'] ?? null) ? $line['speaker'] : '',
                ...($hidesAnswers ? [] : ['text' => $text]),
                ...($clips[$text] ?? ['audioUrl' => null, 'audioSlowUrl' => null]),
            ];
        }

        $rest = $hidesAnswers ? $this->withoutKeys($payload) : $this->withoutSpans($payload);
        unset($rest['dialogue']);

        return [...$rest, 'lines' => $lines];
    }

    /**
     * @param  list<LessonExercise>  $exercises
     * @return list<string>
     */
    private function spokenTexts(array $exercises, bool $hidesAnswers): array
    {
        $texts = [];

        foreach ($exercises as $exercise) {
            array_push($texts, ...$this->spoken($exercise->format, $exercise->payload, $hidesAnswers));

            if ($exercise->substitute !== null) {
                array_push($texts, ...$this->spoken($exercise->substitute->format, $exercise->substitute->payload, $hidesAnswers));
            }
        }

        return array_values(array_unique($texts));
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return list<string>
     */
    private function spoken(LessonExerciseFormat $format, array $payload, bool $hidesAnswers): array
    {
        return $this->hidesModelClip($format, $payload, $hidesAnswers) ? [] : SpokenTexts::ofPayload($format, $payload);
    }

    /**
     * @param  array<string, mixed>  $raw  the stored payload
     * @param  array<string, mixed>  $payload  the payload with its answer keys already handled
     * @param  array<array-key, array{audioUrl: string|null, audioSlowUrl: string|null}>  $clips
     * @return array<string, mixed>
     */
    private function withClips(LessonExerciseFormat $format, array $raw, array $payload, bool $hidesAnswers, array $clips): array
    {
        $none = ['audioUrl' => null, 'audioSlowUrl' => null];

        if ($format === LessonExerciseFormat::TeachWord) {
            return [...$payload, ...(is_string($payload['term'] ?? null) ? $clips[$payload['term']] : $none)];
        }

        if ($format === LessonExerciseFormat::TeachGrammar && is_array($payload['examples'] ?? null)) {
            $payload['examples'] = array_map(
                fn (mixed $example): mixed => is_array($example) ? [...$example, ...(is_string($example['text'] ?? null) ? $clips[$example['text']] : $none)] : $example,
                $payload['examples'],
            );
        }

        $spoken = SpokenTexts::clipText($format, $raw);

        if ($spoken === null || ! $this->isSpoken($format) || $this->hidesModelClip($format, $raw, $hidesAnswers)) {
            return $payload;
        }

        $audio = $clips[$spoken];
        $playsFirst = $format !== LessonExerciseFormat::SpeakAnswer || ! isset($raw['text']);

        return [...$payload, ...$audio, 'audioRole' => $playsFirst ? 'prompt' : 'model'];
    }

    /**
     * The clip of a model answer says the keywords, so a check, which shows
     * no verdict afterwards, never sends it. A lesson does: its verdict shows
     * the same answer once the exercise is over, and the clip plays then.
     *
     * @param  array<string, mixed>  $payload
     */
    private function hidesModelClip(LessonExerciseFormat $format, array $payload, bool $hidesAnswers): bool
    {
        return $hidesAnswers && $format === LessonExerciseFormat::SpeakAnswer && isset($payload['text']);
    }

    private function isSpoken(LessonExerciseFormat $format): bool
    {
        return in_array($format, [LessonExerciseFormat::ListenChoose, LessonExerciseFormat::ListenPair, LessonExerciseFormat::ListenType, LessonExerciseFormat::SpeakRepeat, LessonExerciseFormat::SpeakAnswer], true);
    }

    /**
     * What a listening or speaking exercise may send, by name: anything else,
     * today's keys or tomorrow's, never ships. A dictation sends no text and a
     * spoken answer no model answer, because the verdict shows them afterwards.
     * A word heard and chosen keeps its text and answer in a lesson, where its
     * answer is among the options; a check keeps neither, and a question that
     * is only heard keeps no text at all.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function allowed(LessonExerciseFormat $format, array $payload, bool $hidesAnswers): array
    {
        $heardQuestion = $format === LessonExerciseFormat::SpeakAnswer && ! isset($payload['text']);

        $keys = match ($format) {
            LessonExerciseFormat::ListenChoose, LessonExerciseFormat::ListenPair => $hidesAnswers ? ['options'] : ['text', 'options', 'answer'],
            LessonExerciseFormat::ListenType => [],
            LessonExerciseFormat::SpeakRepeat => $hidesAnswers ? ['english'] : ['text', 'english'],
            default => $heardQuestion && $hidesAnswers ? [] : ['prompt', 'english'],
        };

        return array_intersect_key($payload, array_flip($keys));
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function withoutSpans(array $payload): array
    {
        unset($payload['model'], $payload['homophone_note']);

        if (is_array($payload['accepted'] ?? null)) {
            $payload['accepted'] = array_values(array_map(fn (mixed $entry): mixed => is_array($entry) ? ($entry['text'] ?? '') : $entry, $payload['accepted']));
        }

        return $payload;
    }

    /**
     * A check gives no verdict, so nothing in its payload may carry an answer:
     * not the accepted texts, the keyword slots or the question answers.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function withoutKeys(array $payload): array
    {
        unset($payload['accepted'], $payload['answer'], $payload['slots'], $payload['required'], $payload['substitute_questions'], $payload['why'], $payload['model'], $payload['glosses'], $payload['homophone_note']);

        if (is_array($payload['questions'] ?? null)) {
            $payload['questions'] = array_values(array_map(function (mixed $question): mixed {
                if (is_array($question)) {
                    unset($question['answer']);
                }

                return $question;
            }, $payload['questions']));
        }

        return $payload;
    }
}
