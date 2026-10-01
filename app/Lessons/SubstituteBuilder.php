<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\LessonExerciseFormat;
use App\Enums\LessonStage;

/**
 * What stands in for a skipped listening or speaking exercise: the same
 * material and targets in another family, at the level of the stage, so a
 * skip never makes a lesson easier or harder.
 */
final class SubstituteBuilder
{
    /**
     * @param  array<string, mixed>  $payload
     * @param  list<TargetDefinition>  $targets
     * @return array{format: LessonExerciseFormat, payload: array<string, mixed>, targets: list<TargetDefinition>}|null
     */
    public function handle(LessonStage $stage, LessonExerciseFormat $format, array $payload, array $targets): ?array
    {
        $early = $stage->position() <= 2;

        $substitute = match ($format) {
            LessonExerciseFormat::ListenChoose => [LessonExerciseFormat::ChooseMeaning, $this->only($payload, ['options', 'answer']) + ['prompt' => $payload['text'] ?? '']],
            LessonExerciseFormat::ListenPair => [LessonExerciseFormat::ChooseWord, $this->only($payload, ['options', 'answer']) + ['prompt' => $payload['english'] ?? '']],
            LessonExerciseFormat::ListenType => $stage === LessonStage::Recall
                ? [LessonExerciseFormat::BuildSentence, $this->tiles($payload)]
                : [LessonExerciseFormat::TranslateSentence, $this->only($payload, ['english', 'accepted']) + ['prompt' => $payload['english'] ?? '']],
            LessonExerciseFormat::ListenPassage => [LessonExerciseFormat::ReadPassage, [
                'dialogue' => $payload['dialogue'] ?? [],
                'questions' => $payload['substitute_questions'] ?? throw new InvalidLessonContent('A listening passage needs a second question set for its substitute.'),
            ]],
            LessonExerciseFormat::SpeakRepeat => match (true) {
                $stage === LessonStage::Meet => [LessonExerciseFormat::TypeWord, $this->only($payload, ['english', 'accepted', 'pattern']) + ['prompt' => $payload['english'] ?? '', 'hint' => $payload['pattern'] ?? null]],
                $stage === LessonStage::Recall => [LessonExerciseFormat::BuildSentence, $this->tiles($payload)],
                default => [LessonExerciseFormat::TranslateSentence, $this->only($payload, ['english', 'accepted']) + ['prompt' => $payload['english'] ?? '']],
            },
            LessonExerciseFormat::SpeakAnswer => $early
                ? [LessonExerciseFormat::TypeWord, $this->only($payload, ['english', 'accepted']) + ['prompt' => $payload['english'] ?? $payload['prompt'] ?? '']]
                : [LessonExerciseFormat::WriteGuided, ['prompt' => $payload['prompt'] ?? '', 'required' => array_map(
                    fn (mixed $slot): array => ['forms' => is_array($slot) ? $slot : [], 'target' => null],
                    is_array($payload['slots'] ?? null) ? $payload['slots'] : [],
                )]],
            default => null,
        };

        if ($substitute === null) {
            return null;
        }

        [$substituteFormat, $substitutePayload] = $substitute;

        return [
            'format' => $substituteFormat,
            'payload' => $substitutePayload,
            'targets' => array_map(
                fn (TargetDefinition $target): TargetDefinition => new TargetDefinition(
                    $target->ref,
                    $stage === LessonStage::Check && $format === LessonExerciseFormat::ListenType && $substituteFormat->canProbe(),
                    $target->isContrast,
                    $target->form,
                ),
                $targets,
            ),
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  list<string>  $keys
     * @return array<string, mixed>
     */
    private function only(array $payload, array $keys): array
    {
        return array_intersect_key($payload, array_flip($keys));
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function tiles(array $payload): array
    {
        $text = is_string($payload['text'] ?? null) ? $payload['text'] : '';
        $tiles = preg_split('/\s+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        usort($tiles, fn (string $a, string $b): int => strcmp(md5($text.$a), md5($text.$b)));

        return ['prompt' => $payload['english'] ?? '', 'english' => $payload['english'] ?? '', 'tiles' => $tiles, 'accepted' => $payload['accepted'] ?? []];
    }
}
