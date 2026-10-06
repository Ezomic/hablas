<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\LessonExerciseFormat;
use App\Models\LessonExercise;
use App\Models\VocabularyItem;

/**
 * Shows an exercise's English word meanings in the interface language. The
 * stored payload stays English; the loaded model is changed in memory, both
 * when the exercise is presented and when it is graded, so what a learner
 * picks is compared with the same text they were shown. A meaning without a
 * translation stays English.
 */
final class LocalizeExercise
{
    /** @var array<int, array<string, string>> */
    private array $meanings = [];

    public function handle(LessonExercise $exercise, ?string $locale = null): void
    {
        $locale ??= app()->getLocale();

        if ($locale !== 'nl' || $exercise->lesson === null) {
            return;
        }

        $meanings = $this->meanings($exercise->lesson->unit_id);

        if ($meanings === []) {
            return;
        }

        $exercise->payload = $this->payload($exercise->format, $exercise->payload, $meanings);
        $exercise->syncOriginalAttribute('payload');
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, string>  $meanings
     * @return array<string, mixed>
     */
    private function payload(LessonExerciseFormat $format, array $payload, array $meanings): array
    {
        $swap = fn (mixed $value): mixed => is_string($value) ? ($meanings[$value] ?? $value) : $value;

        foreach (['translation', 'english'] as $key) {
            if (array_key_exists($key, $payload)) {
                $payload[$key] = $swap($payload[$key]);
            }
        }

        if (in_array($format, [LessonExerciseFormat::TypeWord, LessonExerciseFormat::ChooseWord], true) && array_key_exists('prompt', $payload)) {
            $payload['prompt'] = $swap($payload['prompt']);
        }

        if ($format === LessonExerciseFormat::ChooseMeaning) {
            $payload['answer'] = $swap($payload['answer'] ?? null);
            $payload['options'] = is_array($payload['options'] ?? null) ? array_map($swap, $payload['options']) : [];
        }

        if ($format === LessonExerciseFormat::MatchPairs && is_array($payload['pairs'] ?? null)) {
            $payload['pairs'] = array_map(function (mixed $pair) use ($swap): mixed {
                return is_array($pair) && array_key_exists('right', $pair) ? [...$pair, 'right' => $swap($pair['right'])] : $pair;
            }, $payload['pairs']);
        }

        return $payload;
    }

    /** @return array<string, string> */
    private function meanings(int $unitId): array
    {
        if (! isset($this->meanings[$unitId])) {
            $this->meanings[$unitId] = [];

            foreach (VocabularyItem::query()->where('unit_id', $unitId)->whereNotNull('translation_nl')->get(['translation_en', 'translation_nl']) as $item) {
                $this->meanings[$unitId][$item->translation_en] = (string) $item->translation_nl;
            }
        }

        return $this->meanings[$unitId];
    }
}
