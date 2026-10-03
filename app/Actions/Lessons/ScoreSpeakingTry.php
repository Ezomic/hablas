<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Contracts\TextNormalizer;
use App\Enums\AccentVerdict;
use App\Enums\LessonExerciseFormat;
use App\Models\LessonExercise;
use App\Services\TextNormalizerResolver;
use App\Services\TranscriptScorer;
use LogicException;

final class ScoreSpeakingTry
{
    public function __construct(
        private readonly AlignAnswer $alignAnswer = new AlignAnswer,
        private readonly TranscriptScorer $transcriptScorer = new TranscriptScorer,
        private readonly TextNormalizerResolver $textNormalizerResolver = new TextNormalizerResolver,
    ) {}

    /**
     * Scores one spoken try and records nothing. A repeat is graded word by
     * word against the text, so a recogniser's other word, or an accent that
     * makes another word, fails only that word; a single word only has to be
     * present. An answer is graded on its keyword slots, and says only which
     * keywords were heard, so the tries do not give the answer away.
     *
     * @return array{heard: string, score: float, correct: bool, words: list<array{word: string, verdict: string}>, missed: int}
     */
    public function handle(LessonExercise $exercise, string $transcript): array
    {
        $normalizer = $this->normalizer($exercise);

        return match ($exercise->format) {
            LessonExerciseFormat::SpeakRepeat => $this->repeat($normalizer, $exercise, $transcript),
            LessonExerciseFormat::SpeakAnswer => $this->answer($normalizer, $exercise, $transcript),
            default => throw new LogicException("Exercise {$exercise->id} is not a speaking exercise."),
        };
    }

    /**
     * What the learner is shown as the model answer once the exercise is over.
     */
    public function model(LessonExercise $exercise): string
    {
        $payload = $exercise->payload;

        foreach (['model', 'text'] as $key) {
            if (is_string($payload[$key] ?? null)) {
                return $payload[$key];
            }
        }

        $accepted = $this->accepted($exercise);

        if ($accepted !== []) {
            return $accepted[0];
        }

        return implode(' ', array_map(fn (array $forms): string => $forms[0] ?? '', $this->slots($exercise)));
    }

    /**
     * @return array{heard: string, score: float, correct: bool, words: list<array{word: string, verdict: string}>, missed: int}
     */
    private function repeat(TextNormalizer $normalizer, LessonExercise $exercise, string $transcript): array
    {
        $accepted = $this->accepted($exercise);

        if ($accepted === []) {
            throw new LogicException("Exercise {$exercise->id} has no text to repeat.");
        }

        $alignment = $this->alignAnswer->handle($normalizer, $transcript, $accepted);
        $words = [];
        $right = 0;

        foreach ($alignment->words as $word) {
            $isRight = in_array($word->verdict, [AccentVerdict::Exact, AccentVerdict::Missing], true);
            $right += $isRight ? 1 : 0;
            if ($word->given === null) {
                continue;
            }

            $words[] = ['word' => $word->given, 'verdict' => match ($word->verdict) {
                AccentVerdict::Exact => 'exact',
                AccentVerdict::Missing => 'accent',
                AccentVerdict::OtherWord => 'other_word',
                default => 'wrong',
            }];
        }

        $total = count($alignment->words);
        $score = $total === 1 ? ($right === 1 || $this->wordPresent($normalizer, $alignment->words[0]->expected, $transcript) ? 100.0 : 0.0) : $this->transcriptScorer->percentage($right, $total);

        return ['heard' => $transcript, 'score' => $score, 'correct' => $score >= TranscriptScorer::PASS_SCORE, 'words' => $words, 'missed' => $total - $right];
    }

    /**
     * @return array{heard: string, score: float, correct: bool, words: list<array{word: string, verdict: string}>, missed: int}
     */
    private function answer(TextNormalizer $normalizer, LessonExercise $exercise, string $transcript): array
    {
        $slots = $this->slots($exercise);
        $heard = $this->transcriptScorer->keywords($normalizer, $slots, $transcript);
        $found = count(array_filter($heard, is_string(...)));
        $score = $this->transcriptScorer->percentage($found, count($slots));

        return [
            'heard' => $transcript,
            'score' => $score,
            'correct' => $slots !== [] && $score >= TranscriptScorer::PASS_SCORE,
            'words' => [],
            'missed' => count($slots) - $found,
        ];
    }

    private function wordPresent(TextNormalizer $normalizer, string $word, string $transcript): bool
    {
        return $this->transcriptScorer->keywords($normalizer, [[$word]], $transcript)[0] !== null;
    }

    /** @return list<string> */
    private function accepted(LessonExercise $exercise): array
    {
        $texts = [];

        foreach (is_array($exercise->payload['accepted'] ?? null) ? $exercise->payload['accepted'] : [] as $entry) {
            $text = is_array($entry) ? ($entry['text'] ?? null) : $entry;

            if (is_string($text)) {
                $texts[] = $text;
            }
        }

        if ($texts === [] && is_string($exercise->payload['text'] ?? null)) {
            $texts[] = $exercise->payload['text'];
        }

        return $texts;
    }

    /** @return list<list<string>> */
    private function slots(LessonExercise $exercise): array
    {
        $slots = [];

        foreach (is_array($exercise->payload['slots'] ?? null) ? $exercise->payload['slots'] : [] as $slot) {
            $forms = array_values(array_filter(is_array($slot) ? $slot : [], is_string(...)));

            if ($forms !== []) {
                $slots[] = $forms;
            }
        }

        return $slots;
    }

    private function normalizer(LessonExercise $exercise): TextNormalizer
    {
        $language = $exercise->lesson?->unit->language ?? throw new LogicException("Exercise {$exercise->id} has no language.");

        return $this->textNormalizerResolver->forLanguage($language);
    }
}
