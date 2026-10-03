<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Models\GrammarPoint;
use App\Models\LessonAnswer;
use App\Models\LessonExercise;
use App\Models\LessonRun;
use App\Models\VocabularyItem;
use App\Services\RunOutcomes;

final class SummarizeLessonRun
{
    public function __construct(
        private readonly RunOutcomes $runOutcomes = new RunOutcomes,
    ) {}

    /**
     * What the summary of a completed run shows: first-try accuracy per type,
     * the items that needed a second go and, after a check, every answer with
     * its correct one and the items mastered and missing. The answers are
     * safe to show only now that the run is over.
     *
     * @return array{
     *     accuracy: array<string, float>,
     *     retried: list<string>,
     *     items: list<array{term: string, translation: string|null, mastered: bool}>,
     *     answers: list<array{prompt: string, given: string, expected: string, correct: bool}>,
     *     cardsEnrolled: int,
     *     unitCompleted: bool
     * }
     */
    public function handle(LessonRun $run): array
    {
        $result = $run->result ?? [];
        $isCheck = $run->kind->isCheck();

        return [
            'accuracy' => $this->accuracy($run),
            'retried' => $isCheck ? [] : $this->retried($run),
            'items' => $isCheck ? $this->items($result) : [],
            'answers' => $isCheck ? $this->answers($run) : [],
            'cardsEnrolled' => is_int($result['enrolled'] ?? null) ? $result['enrolled'] : 0,
            'unitCompleted' => ($result['unit_completed'] ?? false) === true,
        ];
    }

    /** @return array<string, float> */
    private function accuracy(LessonRun $run): array
    {
        $weights = [];
        $corrects = [];

        foreach ($this->runOutcomes->handle($run) as $outcome) {
            if (! in_array($outcome['origin'], ['lesson', 'practice'], true)) {
                continue;
            }

            $family = $outcome['exercise']->format->family()->value ?? 'other';

            $weights[$family] = ($weights[$family] ?? 0) + $outcome['weight'];
            $corrects[$family] = ($corrects[$family] ?? 0.0) + $outcome['correct'];
        }

        $accuracy = [];

        foreach ($weights as $family => $weight) {
            $accuracy[$family] = round(($corrects[$family] ?? 0.0) / $weight, 4);
        }

        return $accuracy;
    }

    /** @return list<string> */
    private function retried(LessonRun $run): array
    {
        $terms = [];

        foreach ($this->runOutcomes->handle($run) as $outcome) {
            if ($outcome['correct'] >= 1.0 || $outcome['origin'] !== 'lesson') {
                continue;
            }

            foreach ($outcome['exercise']->vocabularyItems()->get() as $item) {
                $terms[$item->term] = true;
            }
        }

        return array_keys($terms);
    }

    /**
     * @param  array<string, mixed>  $result
     * @return list<array{term: string, translation: string|null, mastered: bool}>
     */
    private function items(array $result): array
    {
        $items = [];

        foreach (['mastered' => true, 'missing' => false] as $key => $mastered) {
            foreach (is_array($result[$key] ?? null) ? $result[$key] : [] as $ref) {
                $found = $this->describe($ref);

                if ($found !== null) {
                    $items[] = [...$found, 'mastered' => $mastered];
                }
            }
        }

        return $items;
    }

    /** @return array{term: string, translation: string|null}|null */
    private function describe(mixed $ref): ?array
    {
        if (! is_array($ref) || ! is_int($ref['id'] ?? null)) {
            return null;
        }

        if (($ref['type'] ?? null) === (new GrammarPoint)->getMorphClass()) {
            $point = GrammarPoint::query()->find($ref['id']);

            return $point === null ? null : ['term' => $point->title, 'translation' => null];
        }

        $item = VocabularyItem::query()->find($ref['id']);

        return $item === null ? null : ['term' => $item->term, 'translation' => $item->translation_en];
    }

    /** @return list<array{prompt: string, given: string, expected: string, correct: bool}> */
    private function answers(LessonRun $run): array
    {
        $last = [];

        foreach ($run->answers()->with('lessonExercise')->where('skipped', false)->orderBy('id')->get() as $answer) {
            $last[$answer->lesson_exercise_id] = $answer;
        }

        $rows = [];

        foreach ($last as $answer) {
            $exercise = $answer->lessonExercise;

            if ($exercise !== null) {
                $rows[] = [
                    'prompt' => $this->prompt($exercise),
                    'given' => $this->given($answer),
                    'expected' => $this->expected($exercise),
                    'correct' => $answer->is_correct === true && $answer->flagged_at === null,
                ];
            }
        }

        return $rows;
    }

    private function prompt(LessonExercise $exercise): string
    {
        foreach (['prompt', 'english', 'text'] as $key) {
            if (is_string($exercise->payload[$key] ?? null)) {
                return $exercise->payload[$key];
            }
        }

        return '';
    }

    private function given(LessonAnswer $answer): string
    {
        $response = $answer->response ?? [];

        foreach (['text', 'choice'] as $key) {
            if (is_string($response[$key] ?? null)) {
                return $response[$key];
            }
        }

        $transcripts = is_array($response['transcripts'] ?? null) ? array_values(array_filter($response['transcripts'], is_string(...))) : [];

        return $transcripts === [] ? '' : $transcripts[array_key_last($transcripts)];
    }

    private function expected(LessonExercise $exercise): string
    {
        $accepted = $exercise->payload['accepted'] ?? null;

        if (is_array($accepted) && is_array($accepted[0] ?? null) && is_string($accepted[0]['text'] ?? null)) {
            return $accepted[0]['text'];
        }

        foreach (['answer', 'text'] as $key) {
            if (is_string($exercise->payload[$key] ?? null)) {
                return $exercise->payload[$key];
            }
        }

        return '';
    }
}
