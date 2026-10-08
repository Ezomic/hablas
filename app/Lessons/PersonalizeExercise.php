<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Models\LessonExercise;

/**
 * Fills the learner's own name into the answers that ask for it: a form
 * written {name} in a keyword slot or a required form accepts whatever name
 * the learner uses in lessons, next to the names listed. The stored payload
 * is untouched; the loaded model is changed in memory.
 */
final class PersonalizeExercise
{
    public const PLACEHOLDER = '{name}';

    public function handle(LessonExercise $exercise, ?string $name): void
    {
        $payload = $exercise->payload;

        foreach (['slots', 'required'] as $key) {
            if (is_array($payload[$key] ?? null)) {
                $payload[$key] = $this->fill($payload[$key], $name);
            }
        }

        $exercise->payload = $payload;
        $exercise->syncOriginalAttribute('payload');
    }

    /**
     * @param  array<array-key, mixed>  $value
     * @return array<array-key, mixed>
     */
    private function fill(array $value, ?string $name): array
    {
        $filled = [];

        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $filled[$key] = $this->fill($item, $name);
            } elseif ($item === self::PLACEHOLDER) {
                if ($name !== null) {
                    $filled[$key] = $name;
                }
            } else {
                $filled[$key] = $item;
            }
        }

        return array_is_list($value) ? array_values($filled) : $filled;
    }
}
