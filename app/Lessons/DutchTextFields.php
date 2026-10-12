<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\LessonExerciseFormat;

/**
 * Where an exercise payload holds text written in English for the learner: the
 * cues, prompts, options and notes. The target-language text is never listed
 * here, so a lookup applied to these fields cannot touch it.
 */
final class DutchTextFields
{
    /** @var array<string, list<string>> */
    private const BY_FORMAT = [
        'speak_repeat' => ['english'],
        'translate_sentence' => ['prompt', 'english'],
        'speak_answer' => ['english'],
        'listen_type' => ['english'],
        'listen_choose' => ['options', 'answer'],
        'type_gap' => ['english'],
        'write_guided' => ['prompt', 'english'],
        'choose_gap' => ['english'],
        'build_sentence' => ['prompt', 'english'],
        'transform_sentence' => ['prompt'],
        'teach_grammar' => ['title', 'explanation'],
        'listen_passage' => ['prompt'],
        'read_passage' => ['prompt'],
    ];

    /** Notes any format may carry. */
    private const NOTES = ['why', 'homophone_note', 'contrast_note'];

    /**
     * Replaces every English text of a payload with what $translate returns for
     * it. $translate receives the text and where it sits ("questions.0.prompt").
     *
     * @param  array<string, mixed>  $payload
     * @param  callable(string, string): string  $translate
     * @return array<string, mixed>
     */
    public static function map(LessonExerciseFormat $format, array $payload, callable $translate): array
    {
        foreach ([...(self::BY_FORMAT[$format->value] ?? []), ...self::NOTES] as $key) {
            if (! array_key_exists($key, $payload) || self::isTargetLanguage($format, $key, $payload)) {
                continue;
            }

            $payload[$key] = self::value($payload[$key], $key, $translate);
        }

        if (is_array($payload['questions'] ?? null)) {
            $payload['questions'] = self::questions($payload['questions'], $translate);
        }

        if (is_array($payload['glosses'] ?? null)) {
            $payload['glosses'] = self::glosses($payload['glosses'], $translate);
        }

        if (is_array($payload['examples'] ?? null)) {
            $payload['examples'] = self::examples($payload['examples'], $translate);
        }

        return $payload;
    }

    /**
     * A guided writing exercise either gives an English instruction as its
     * prompt, or asks a question in the target language and carries the
     * English cue separately.
     *
     * @param  array<string, mixed>  $payload
     */
    private static function isTargetLanguage(LessonExerciseFormat $format, string $key, array $payload): bool
    {
        return $format === LessonExerciseFormat::WriteGuided && $key === 'prompt' && array_key_exists('english', $payload);
    }

    /** @param  callable(string, string): string  $translate */
    private static function value(mixed $value, string $path, callable $translate): mixed
    {
        if (is_string($value)) {
            return $value === '' ? $value : $translate($value, $path);
        }

        if (is_array($value)) {
            return array_map(fn (mixed $item, int|string $index): mixed => self::value($item, "{$path}.{$index}", $translate), $value, array_keys($value));
        }

        return $value;
    }

    /**
     * @param  array<array-key, mixed>  $questions
     * @param  callable(string, string): string  $translate
     * @return array<array-key, mixed>
     */
    private static function questions(array $questions, callable $translate): array
    {
        foreach ($questions as $index => $question) {
            if (! is_array($question)) {
                continue;
            }

            foreach (['prompt', 'options', 'answer'] as $key) {
                if (array_key_exists($key, $question)) {
                    $question[$key] = self::value($question[$key], "questions.{$index}.{$key}", $translate);
                }
            }

            $questions[$index] = $question;
        }

        return $questions;
    }

    /**
     * A gloss maps a target-language word to its English meaning.
     *
     * @param  array<array-key, mixed>  $glosses
     * @param  callable(string, string): string  $translate
     * @return array<array-key, mixed>
     */
    private static function glosses(array $glosses, callable $translate): array
    {
        foreach ($glosses as $word => $meaning) {
            $glosses[$word] = self::value($meaning, "glosses.{$word}", $translate);
        }

        return $glosses;
    }

    /**
     * @param  array<array-key, mixed>  $examples
     * @param  callable(string, string): string  $translate
     * @return array<array-key, mixed>
     */
    private static function examples(array $examples, callable $translate): array
    {
        foreach ($examples as $index => $example) {
            if (is_array($example) && array_key_exists('english', $example)) {
                $examples[$index] = [...$example, 'english' => self::value($example['english'], "examples.{$index}.english", $translate)];
            }
        }

        return $examples;
    }
}
