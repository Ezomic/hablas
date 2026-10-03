<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\LessonExerciseFormat;

/**
 * What a clip is made from. The player and the speech corpus both ask this
 * one place, so no text the player may request can be missing from the clips
 * that speech:generate makes.
 */
final class SpokenTexts
{
    /**
     * Every text the player may ask a clip for in an exercise of this format.
     *
     * @param  array<string, mixed>  $payload
     * @return list<string>
     */
    public static function ofPayload(LessonExerciseFormat $format, array $payload): array
    {
        return match ($format) {
            LessonExerciseFormat::TeachWord => self::strings([$payload['term'] ?? null]),
            LessonExerciseFormat::TeachGrammar => self::strings(array_map(
                fn (mixed $example): mixed => is_array($example) ? ($example['text'] ?? null) : null,
                is_array($payload['examples'] ?? null) ? $payload['examples'] : [],
            )),
            LessonExerciseFormat::ListenChoose, LessonExerciseFormat::ListenPair, LessonExerciseFormat::ListenType, LessonExerciseFormat::SpeakRepeat, LessonExerciseFormat::SpeakAnswer => self::strings([self::clipText($format, $payload)]),
            LessonExerciseFormat::ListenPassage => self::dialogue($payload),
            default => [],
        };
    }

    /**
     * The text of the one clip a listening or speaking exercise plays. A
     * spoken answer to an English cue plays its model answer once it is over;
     * one that answers a question in the language plays the question.
     *
     * @param  array<string, mixed>  $payload
     */
    public static function clipText(LessonExerciseFormat $format, array $payload): ?string
    {
        $text = $payload['text'] ?? ($format === LessonExerciseFormat::SpeakAnswer ? $payload['prompt'] ?? null : null);

        return is_string($text) ? $text : null;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return list<string>
     */
    public static function dialogue(array $payload): array
    {
        $texts = [];

        foreach (is_array($payload['dialogue'] ?? null) ? $payload['dialogue'] : [] as $line) {
            if (is_array($line) && is_string($line['text'] ?? null)) {
                $texts[] = $line['text'];
            }
        }

        return $texts;
    }

    /**
     * @param  array<array-key, mixed>  $values
     * @return list<string>
     */
    private static function strings(array $values): array
    {
        return array_values(array_filter($values, is_string(...)));
    }
}
