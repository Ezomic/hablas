<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Contracts\TextNormalizer;
use App\Enums\AccentPolicy;
use App\Enums\AccentVerdict;
use App\Enums\ErrorTagCategory;
use App\Enums\LessonExerciseFormat;
use App\Lessons\AlignedWord;
use App\Lessons\Grade;
use App\Lessons\TargetRef;
use App\Models\GrammarPoint;
use App\Models\LessonExercise;
use App\Services\AccentComparer;
use App\Services\TextNormalizerResolver;
use LogicException;

final class GradeLessonAnswer
{
    public function __construct(
        private readonly AlignAnswer $alignAnswer = new AlignAnswer,
        private readonly AccentComparer $accentComparer = new AccentComparer,
        private readonly TextNormalizerResolver $textNormalizerResolver = new TextNormalizerResolver,
        private readonly ScoreSpeakingTry $scoreSpeakingTry = new ScoreSpeakingTry,
    ) {}

    /**
     * Grades one answer on the server, whole exercise and each target, so a
     * misspelt word fails only that word and nothing else in the sentence.
     *
     * @param  array<string, mixed>  $response
     */
    public function handle(LessonExercise $exercise, array $response): Grade
    {
        $format = $exercise->format;

        return match (true) {
            $format->isTeach() => new Grade(true, null, null, null, $this->verdicts($exercise, fn (): bool => true), null),
            $format->isSpeaking() => $this->gradeSpeaking($exercise, $response),
            $format->isChoice() => $this->gradeChoice($exercise, $response),
            $format === LessonExerciseFormat::MatchPairs => $this->gradeMatching($exercise, $response),
            $format->isPassage() => $this->gradePassage($exercise, $response),
            $format === LessonExerciseFormat::WriteGuided => $this->gradeGuided($exercise, $response),
            default => $this->gradeTyped($exercise, $response),
        };
    }

    /**
     * The best of up to three spoken tries counts. A mispronounced word is
     * not a grammar mistake, so a spoken answer carries no error tag.
     *
     * @param  array<string, mixed>  $response
     */
    private function gradeSpeaking(LessonExercise $exercise, array $response): Grade
    {
        $best = 0.0;
        $correct = false;

        foreach (array_slice(array_values(array_filter($this->list($response['transcripts'] ?? []), is_string(...))), 0, 3) as $transcript) {
            $scored = $this->scoreSpeakingTry->handle($exercise, $transcript);
            $best = max($best, $scored['score']);
            $correct = $correct || $scored['correct'];
        }

        return new Grade($correct, $this->scoreSpeakingTry->model($exercise), null, $best, $this->verdicts($exercise, fn (): bool => $correct), null);
    }

    /** @param  array<string, mixed>  $response */
    private function gradeChoice(LessonExercise $exercise, array $response): Grade
    {
        $answer = $this->string($exercise->payload['answer'] ?? '');
        $correct = $this->string($response['choice'] ?? '') === $answer;

        return $this->finish($exercise, $correct, $answer, null, null, $this->verdicts($exercise, fn (): bool => $correct));
    }

    /** @param  array<string, mixed>  $response */
    private function gradeMatching(LessonExercise $exercise, array $response): Grade
    {
        $wrong = array_map($this->string(...), $this->list($response['wrong'] ?? []));
        $correct = $wrong === [];

        return $this->finish($exercise, $correct, null, null, null, $this->verdicts($exercise, fn (string $key): bool => ! in_array($key, $wrong, true)));
    }

    /** @param  array<string, mixed>  $response */
    private function gradePassage(LessonExercise $exercise, array $response): Grade
    {
        $questions = $this->list($exercise->payload['questions'] ?? []);
        $choices = $this->list($response['choices'] ?? []);
        $right = 0;
        $answers = [];

        foreach ($questions as $index => $question) {
            $answer = is_array($question) ? $this->string($question['answer'] ?? '') : '';
            $answers[] = $answer;

            if ($this->string($choices[$index] ?? '') === $answer) {
                $right++;
            }
        }

        $total = count($questions);
        $correct = $total > 0 && $right === $total;
        $score = $total === 0 ? 0.0 : round($right / $total * 100, 1);

        return $this->finish($exercise, $correct, implode(' / ', $answers), null, $score, $this->verdicts($exercise, fn (): bool => $correct));
    }

    /** @param  array<string, mixed>  $response */
    private function gradeGuided(LessonExercise $exercise, array $response): Grade
    {
        $normalizer = $this->normalizer($exercise);
        $policy = $this->policy($exercise);
        $words = $this->words($normalizer, $this->string($response['text'] ?? ''));
        $required = $this->list($exercise->payload['required'] ?? []);
        $found = 0;
        $byTarget = [];
        $accentSlip = false;

        foreach ($required as $entry) {
            $forms = is_array($entry) ? $this->list($entry['forms'] ?? []) : [];
            $match = $this->findForm($normalizer, $words, $forms, $policy);

            if ($match !== null) {
                $found++;
                $accentSlip = $accentSlip || $match === AccentVerdict::Missing;
            }

            if (is_array($entry) && is_string($entry['target'] ?? null)) {
                $byTarget[$entry['target']] = ($byTarget[$entry['target']] ?? true) && $match !== null;
            }
        }

        $total = count($required);
        $correct = $total > 0 && $found === $total;
        $score = $total === 0 ? 0.0 : round($found / $total * 100, 1);
        $verdicts = $this->verdicts($exercise, fn (string $key): bool => $byTarget[$key] ?? $correct);

        return $this->finish($exercise, $correct, null, $accentSlip ? 'accent' : null, $score, $verdicts);
    }

    /** @param  array<string, mixed>  $response */
    private function gradeTyped(LessonExercise $exercise, array $response): Grade
    {
        $normalizer = $this->normalizer($exercise);
        $policy = $this->policy($exercise);
        $accepted = $this->acceptedAnswers($exercise);
        $text = $this->string($response['text'] ?? '');
        $alignment = $this->alignAnswer->handle($normalizer, $text, array_column($accepted, 'text'));

        $right = [];
        $slipped = false;
        $otherWord = false;

        foreach ($alignment->words as $index => $word) {
            $right[$index] = match ($word->verdict) {
                AccentVerdict::Exact => true,
                AccentVerdict::Missing => $this->takeMissing($policy, $slipped),
                AccentVerdict::OtherWord => $this->takeOtherWord($otherWord),
                null => false,
            };
        }

        $correct = ! in_array(false, $right, true) && $alignment->extra === [];
        $spans = $accepted[$alignment->acceptedIndex]['spans'];

        $verdicts = $this->verdicts($exercise, function (string $key) use ($spans, $right, $correct): bool {
            if (! isset($spans[$key])) {
                return $correct;
            }

            [$start, $length] = $spans[$key];

            return ! in_array(false, array_slice($right, $start, $length), true) && count(array_slice($right, $start, $length)) === $length;
        });

        $note = match (true) {
            $otherWord => 'other_word',
            $slipped => 'accent',
            default => null,
        };

        if (! $correct) {
            $note = $this->wrongNote($normalizer, $exercise, $alignment->words, $alignment->extra, $right) ?? $note;
        }

        return $this->finish($exercise, $correct, $accepted[0]['text'], $note, null, $verdicts);
    }

    /**
     * @param  list<AlignedWord>  $words
     * @param  list<string>  $extra
     * @param  array<int, bool>  $right
     */
    private function wrongNote(TextNormalizer $normalizer, LessonExercise $exercise, array $words, array $extra, array $right): ?string
    {
        $slips = array_map($normalizer->exactKey(...), array_map($this->string(...), $this->list($exercise->payload['portunol_slips'] ?? [])));
        $typed = [...$extra];

        foreach ($words as $index => $word) {
            if (! $right[$index] && $word->given !== null) {
                $typed[] = $word->given;
            }
        }

        if (array_intersect($typed, $slips) !== []) {
            return 'portunol';
        }

        $wrongIndexes = array_keys(array_filter($right, fn (bool $isRight): bool => ! $isRight));

        if (count($wrongIndexes) === 1 && $extra === []) {
            $word = $words[$wrongIndexes[0]];
            $articles = $normalizer->articles();

            if ($word->given !== null && in_array($word->expected, $articles, true) && in_array($word->given, $articles, true)) {
                return 'article';
            }
        }

        return null;
    }

    private function takeMissing(AccentPolicy $policy, bool &$slipped): bool
    {
        $slipped = true;

        return $policy !== AccentPolicy::Reject;
    }

    private function takeOtherWord(bool &$otherWord): bool
    {
        $otherWord = true;

        return false;
    }

    /**
     * @param  list<string>  $words
     * @param  array<array-key, mixed>  $forms
     */
    private function findForm(TextNormalizer $normalizer, array $words, array $forms, AccentPolicy $policy): ?AccentVerdict
    {
        $best = null;

        foreach ($forms as $form) {
            foreach ($words as $word) {
                $verdict = $this->accentComparer->compare($normalizer, $this->string($form), $word);

                if ($verdict === AccentVerdict::Exact) {
                    return $verdict;
                }

                if ($verdict === AccentVerdict::Missing && $policy !== AccentPolicy::Reject) {
                    $best = $verdict;
                }
            }
        }

        return $best;
    }

    /**
     * @param  callable(string): bool  $isCorrect  by target key
     * @return list<array{type: string, id: int, correct: bool}>
     */
    private function verdicts(LessonExercise $exercise, callable $isCorrect): array
    {
        $verdicts = [];

        foreach ($exercise->targets as $target) {
            $verdicts[] = [
                'type' => $target->targetable_type,
                'id' => $target->targetable_id,
                'correct' => $isCorrect(TargetRef::keyFor($target->targetable_type, $target->targetable_id)),
            ];
        }

        return $verdicts;
    }

    /**
     * @param  list<array{type: string, id: int, correct: bool}>  $verdicts
     */
    private function finish(LessonExercise $exercise, bool $correct, ?string $expected, ?string $note, ?float $score, array $verdicts): Grade
    {
        $tag = null;

        if (! $correct) {
            $tag = match ($note) {
                'portunol' => ErrorTagCategory::PortunolSlip,
                'article' => ErrorTagCategory::WrongGender,
                default => $this->grammarTag($exercise, $verdicts),
            };
        }

        return new Grade($correct, $expected, $note, $score, $verdicts, $tag);
    }

    /**
     * @param  list<array{type: string, id: int, correct: bool}>  $verdicts
     */
    private function grammarTag(LessonExercise $exercise, array $verdicts): ?ErrorTagCategory
    {
        foreach ($verdicts as $verdict) {
            if ($verdict['correct'] || $verdict['type'] !== (new GrammarPoint)->getMorphClass()) {
                continue;
            }

            $point = $exercise->grammarPoints->firstWhere('id', $verdict['id']);

            if ($point instanceof GrammarPoint && $point->error_tag_category !== null) {
                return $point->error_tag_category;
            }
        }

        return null;
    }

    /**
     * @return list<array{text: string, spans: array<string, array{int, int}>}>
     */
    private function acceptedAnswers(LessonExercise $exercise): array
    {
        $accepted = [];

        foreach ($this->list($exercise->payload['accepted'] ?? []) as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $spans = [];

            foreach ($this->list($entry['spans'] ?? []) as $key => $span) {
                if (is_array($span) && count($span) === 2) {
                    $spans[(string) $key] = [(int) (is_numeric($span[0]) ? $span[0] : 0), (int) (is_numeric($span[1]) ? $span[1] : 0)];
                }
            }

            $accepted[] = ['text' => $this->string($entry['text'] ?? ''), 'spans' => $spans];
        }

        if ($accepted === []) {
            throw new LogicException("Exercise {$exercise->id} has no accepted answers.");
        }

        return $accepted;
    }

    private function policy(LessonExercise $exercise): AccentPolicy
    {
        $lesson = $exercise->lesson ?? throw new LogicException("Exercise {$exercise->id} has no lesson.");

        return $lesson->stage->accentPolicy();
    }

    private function normalizer(LessonExercise $exercise): TextNormalizer
    {
        $language = $exercise->lesson?->unit->language ?? throw new LogicException("Exercise {$exercise->id} has no language.");

        return $this->textNormalizerResolver->forLanguage($language);
    }

    /** @return list<string> */
    private function words(TextNormalizer $normalizer, string $text): array
    {
        $key = $normalizer->exactKey($text);

        return $key === '' ? [] : explode(' ', $key);
    }

    /** @return array<array-key, mixed> */
    private function list(mixed $value): array
    {
        return is_array($value) ? $value : [];
    }

    private function string(mixed $value): string
    {
        return is_string($value) ? $value : (is_scalar($value) ? (string) $value : '');
    }
}
