<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Enums\LessonRunStatus;
use App\Enums\LessonState;
use App\Models\Lesson;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\Unit;
use App\Services\LessonProgress;
use App\Services\SpeechLocaleResolver;
use LogicException;

final class PresentLessonRun
{
    public function __construct(
        private readonly SpeechLocaleResolver $speechLocaleResolver = new SpeechLocaleResolver,
        private readonly SummarizeLessonRun $summarizeLessonRun = new SummarizeLessonRun,
        private readonly LessonProgress $lessonProgress = new LessonProgress,
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
            ->with('substitute')
            ->get()
            ->keyBy('id');

        $plan = [];

        foreach ($run->plan as $entry) {
            $exercise = $exercises->get($entry['id']);

            if ($exercise === null) {
                continue;
            }

            $plan[] = [
                ...$this->exercise($exercise, $hidesAnswers),
                'origin' => $entry['origin'],
                'substitute' => $exercise->substitute === null ? null : $this->exercise($exercise->substitute, $hidesAnswers),
            ];
        }

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
                'summarySeen' => $run->summary_seen_at !== null,
            ],
            'lesson' => ['id' => $lesson->id, 'unitId' => $unit->id, 'stage' => $stage->value, 'title' => $lesson->title, 'position' => $lesson->position],
            'settings' => [
                'feedback' => ! $run->kind->isCheck(),
                'hintsAreFree' => $stage->hintsAreFree(),
                'audioSpeed' => $stage->audioSpeed(),
                'replayLimit' => $stage->replayLimit(),
                'offersSlowerAudio' => $stage->offersSlowerAudio(),
                'speechLocale' => $this->speechLocaleResolver->forLanguage($language),
            ],
            'plan' => $plan,
            'answers' => $run->answers()->orderBy('id')->get()->map(fn (LessonAnswer $answer): array => [
                'step' => $answer->step,
                'exerciseId' => $answer->lesson_exercise_id,
                'attempt' => $answer->attempt,
                'hinted' => $answer->hinted,
                'skipped' => $answer->skipped,
                'correct' => $hidesAnswers ? null : $answer->is_correct,
                'flagged' => $answer->flagged_at !== null,
                'settled' => $answer->skipped ? false : ($hidesAnswers || $answer->settlesExercise()),
            ])->all(),
        ];
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
                return ['lessonId' => $lesson->id, 'title' => $lesson->title, 'position' => $lesson->position, 'stage' => $lesson->stage->value, 'state' => $state->value];
            }
        }

        return null;
    }

    /**
     * @return array{id: int, key: string, block: string, format: string, payload: array<string, mixed>}
     */
    private function exercise(LessonExercise $exercise, bool $hidesAnswers): array
    {
        return [
            'id' => $exercise->id,
            'key' => $hidesAnswers ? (string) $exercise->id : $exercise->key,
            'block' => $exercise->block,
            'format' => $exercise->format->value,
            'payload' => $hidesAnswers ? $this->withoutKeys($exercise->payload) : $this->withoutSpans($exercise->payload),
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function withoutSpans(array $payload): array
    {
        if (is_array($payload['accepted'] ?? null)) {
            $payload['accepted'] = array_values(array_map(fn (mixed $entry): mixed => is_array($entry) ? ($entry['text'] ?? '') : $entry, $payload['accepted']));
        }

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function withoutKeys(array $payload): array
    {
        unset($payload['accepted'], $payload['answer'], $payload['slots'], $payload['required'], $payload['substitute_questions']);

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
