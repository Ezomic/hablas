<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Models\LessonExercise;

/**
 * Every distinct English text of the exercises, with where it sits and one
 * target-language sentence from the same exercise to show what it means.
 */
final class DutchSentenceCollector
{
    public function __construct(private readonly DutchSentences $sentences = new DutchSentences) {}

    /** @return array<string, array{english: string, format: string, field: string, language: string, context: string}> */
    public function handle(bool $onlyMissing = false): array
    {
        $found = [];
        $known = $onlyMissing ? $this->sentences->all() : [];

        LessonExercise::query()
            ->with('lesson.unit.language')
            ->orderBy('id')
            ->chunkById(1000, function ($exercises) use (&$found, $known): void {
                foreach ($exercises as $exercise) {
                    $language = $exercise->lesson?->unit?->language->code ?? '';

                    DutchTextFields::map($exercise->format, $exercise->payload, function (string $text, string $path) use (&$found, $known, $exercise, $language): string {
                        if (! isset($found[$text]) && ! isset($known[$text])) {
                            $found[$text] = ['english' => $text, 'format' => $exercise->format->value, 'field' => $path, 'language' => $language, 'context' => $this->context($exercise->payload)];
                        }

                        return $text;
                    });
                }
            });

        return $found;
    }

    /** @param  array<string, mixed>  $payload */
    private function context(array $payload): string
    {
        foreach (['text', 'model', 'source'] as $key) {
            if (is_string($payload[$key] ?? null)) {
                return $payload[$key];
            }
        }

        $accepted = $payload['accepted'] ?? null;

        if (is_array($accepted) && is_array($accepted[0] ?? null) && is_string($accepted[0]['text'] ?? null)) {
            return $accepted[0]['text'];
        }

        return is_string($payload['prompt'] ?? null) ? $payload['prompt'] : '';
    }
}
