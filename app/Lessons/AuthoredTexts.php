<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\LessonExerciseFormat;

/**
 * The target-language text an authored exercise puts in front of the learner
 * or accepts from him, field by field. Which fields hold the language and
 * which hold English depends on the format, so spelling passes and the
 * known-words lint read the exercise through here.
 */
final class AuthoredTexts
{
    /**
     * @return list<string>
     */
    public static function of(AuthoredExercise $exercise): array
    {
        $payload = $exercise->payload;
        $texts = [...$exercise->accepted];

        match ($exercise->format) {
            LessonExerciseFormat::ChooseGap => array_push($texts, ...self::strings([$payload['prompt'] ?? null]), ...self::list($payload['options'] ?? null)),
            LessonExerciseFormat::TypeGap => array_push($texts, ...self::strings([$payload['prompt'] ?? null])),
            LessonExerciseFormat::TransformSentence => array_push($texts, ...self::strings([$payload['source'] ?? null])),
            LessonExerciseFormat::BuildSentence => array_push($texts, ...self::list($payload['distractors'] ?? null)),
            LessonExerciseFormat::ListenChoose, LessonExerciseFormat::ListenType, LessonExerciseFormat::SpeakRepeat => array_push($texts, ...self::strings([$payload['text'] ?? null])),
            LessonExerciseFormat::SpeakAnswer => array_push($texts, ...self::strings([$payload['prompt'] ?? null, $payload['model'] ?? null]), ...self::slots($payload['slots'] ?? null)),
            LessonExerciseFormat::ReadPassage, LessonExerciseFormat::ListenPassage => array_push($texts, ...SpokenTexts::dialogue($payload)),
            LessonExerciseFormat::WriteGuided => array_push($texts, ...self::strings([$payload['model'] ?? null]), ...self::list($payload['chips'] ?? null), ...self::required($payload['required'] ?? null)),
            default => null,
        };

        return array_values(array_filter($texts, fn (string $text): bool => trim($text) !== ''));
    }

    /**
     * @param  array<array-key, mixed>  $values
     * @return list<string>
     */
    private static function strings(array $values): array
    {
        return array_values(array_filter($values, is_string(...)));
    }

    /** @return list<string> */
    private static function list(mixed $value): array
    {
        return is_array($value) ? self::strings($value) : [];
    }

    /** @return list<string> */
    private static function slots(mixed $slots): array
    {
        $forms = [];

        foreach (is_array($slots) ? $slots : [] as $slot) {
            array_push($forms, ...self::list($slot));
        }

        return $forms;
    }

    /** @return list<string> */
    private static function required(mixed $required): array
    {
        $forms = [];

        foreach (is_array($required) ? $required : [] as $entry) {
            if (is_array($entry)) {
                array_push($forms, ...self::list($entry['forms'] ?? null));
            }
        }

        return $forms;
    }
}
