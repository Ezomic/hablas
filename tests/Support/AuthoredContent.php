<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Contracts\TextNormalizer;
use App\Enums\LessonExerciseFormat as Format;
use App\Enums\LessonStage;
use App\Lessons\AuthoredExercise;
use App\Lessons\LessonDefinition;
use App\Lessons\SpokenTexts;
use Illuminate\Support\Collection;

final class AuthoredContent
{
    /** @return array<string, int> graded answers per family of the original exercises of a lesson */
    public static function graded(LessonDefinition $lesson): array
    {
        $counts = ['choice' => 0, 'writing' => 0, 'listening' => 0, 'speaking' => 0];

        foreach ($lesson->exercises as $exercise) {
            $family = $exercise->format->family();

            if ($exercise->substituteForKey !== null || $family === null) {
                continue;
            }

            $counts[$family->value] += $exercise->format->isPassage() ? count($exercise->payload['questions']) : 1;
        }

        return $counts;
    }

    /** @return list<string> the complete sentences an authored exercise shows or accepts, as comparable keys */
    public static function sentencesOf(AuthoredExercise $exercise, TextNormalizer $normalizer): array
    {
        $payload = $exercise->payload;
        $texts = match ($exercise->format) {
            Format::TypeGap, Format::ChooseGap => [str_replace('___', (string) ($exercise->accepted[0] ?? $payload['answer'] ?? ''), (string) $payload['prompt'])],
            Format::TranslateSentence, Format::BuildSentence, Format::ListenType => [$exercise->accepted[0]],
            Format::TransformSentence => [(string) $payload['source'], $exercise->accepted[0]],
            Format::SpeakRepeat, Format::ListenChoose => [(string) $payload['text']],
            Format::SpeakAnswer => [(string) $payload['prompt']],
            Format::ReadPassage, Format::ListenPassage => array_merge(...array_map(fn (string $line): array => preg_split('/(?<=[.?!])\s+/u', $line) ?: [], SpokenTexts::dialogue($payload))),
            default => [],
        };

        $keys = [];

        foreach ($texts as $text) {
            $key = $normalizer->answerKey($text);

            if (count(explode(' ', $key)) > 2) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    /**
     * @param  Collection<int, AuthoredExercise>  $authored
     * @return list<string>
     */
    public static function stageSentences(Collection $authored, TextNormalizer $normalizer, LessonStage $stage, ?string $set = null): array
    {
        return $authored
            ->filter(fn (AuthoredExercise $exercise): bool => $exercise->stage === $stage && $exercise->probeSet === $set)
            ->flatMap(fn (AuthoredExercise $exercise): array => self::sentencesOf($exercise, $normalizer))
            ->unique()->values()->all();
    }
}
