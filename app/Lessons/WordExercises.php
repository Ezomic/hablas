<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\ExerciseFamily;
use App\Enums\LessonExerciseFormat;
use Illuminate\Support\Str;

/**
 * The exercises generated from the reviewed word data: lesson 1, the word
 * half of lesson 2 and the typed recall of every check set. Each part of
 * speech has its own distractor rule, so no unit can be laid out differently
 * from another.
 */
final class WordExercises
{
    /**
     * The block of the generated typed recall in a check. A check holding any
     * other block has authored content, which is what makes it a full check.
     */
    public const CHECK_RECALL_BLOCK = 'recall';

    private const BATCH_SIZES = [4, 3, 3];

    /**
     * Lesson 1 teaches in batches: each word is taught and recognised at once,
     * then the batch is typed with its letters shown, and a mixed block matches
     * all the words at the end.
     */
    public function meet(BuildContext $context, ExerciseSink $sink): void
    {
        $extras = $this->extraFamilies($context);
        $offset = 0;

        foreach ($this->batches($context->items) as $number => $batch) {
            $block = 'batch-'.($number + 1);

            foreach ($batch as $index => $info) {
                $sink->add('meet.teach_word.'.$this->slug($info), $block, LessonExerciseFormat::TeachWord, [
                    'term' => $info->item->term,
                    'translation' => $info->item->translation_en,
                    'part_of_speech' => $info->partOfSpeech(),
                    'is_cognate' => $info->item->is_cognate,
                    'contrast_note' => $info->data->note ?? $info->item->contrast_note,
                    'number' => $index + 1,
                    'of' => count($batch),
                ], [$this->target($info)]);

                [$options, $answer] = $this->meaningOptions($context, $info);
                $sink->add('meet.choose_meaning.'.$this->slug($info), $block, LessonExerciseFormat::ChooseMeaning, [
                    'prompt' => $info->item->term,
                    'options' => $options,
                    'answer' => $answer,
                ], [$this->target($info)]);
            }

            if ($extras !== []) {
                foreach ($batch as $index => $info) {
                    $this->extra($context, $sink, $info, $extras[($offset + $index) % count($extras)], $block);
                }
            }

            foreach ($batch as $info) {
                $pattern = $this->pattern($context, $info);
                $sink->add('meet.type_word.'.$this->slug($info), $block, LessonExerciseFormat::TypeWord, [
                    'prompt' => $info->cue,
                    'english' => $info->cue,
                    'hint' => $pattern,
                    'accepted' => $this->accepted($context, $this->lenientAnswers($context, $info), $info),
                    'portunol_slips' => $info->data->portunolSlips,
                ], [$this->target($info)]);
            }

            $offset += count($batch);
        }

        foreach ($this->matchGroups($context->items) as $number => $group) {
            $sink->add('meet.match_pairs.'.($number + 1), 'mixed', LessonExerciseFormat::MatchPairs, [
                'pairs' => array_map(fn (ItemInfo $info): array => [
                    'target' => $info->key(),
                    'left' => $info->item->term,
                    'right' => $info->item->translation_en,
                ], $group),
            ], array_map(fn (ItemInfo $info): TargetDefinition => $this->target($info), $group));
        }
    }

    /**
     * Lesson 2: every word recalled from an English cue, chosen and typed,
     * with a spoken or heard exercise in between.
     */
    public function recall(BuildContext $context, ExerciseSink $sink): void
    {
        $extras = $this->extraFamilies($context);

        foreach ($context->items as $info) {
            [$options, $answer] = $this->termOptions($context, $info);
            $sink->add('recall.choose_word.'.$this->slug($info), 'main', LessonExerciseFormat::ChooseWord, [
                'prompt' => $info->cue,
                'options' => $options,
                'answer' => $answer,
            ], [$this->target($info)]);
        }

        foreach ($context->items as $info) {
            $sink->add('recall.type_word.'.$this->slug($info), 'main', LessonExerciseFormat::TypeWord, [
                'prompt' => $info->cue,
                'english' => $info->cue,
                'accepted' => $this->accepted($context, $info->accepted, $info),
                'portunol_slips' => $info->data->portunolSlips,
            ], [$this->target($info)]);
        }

        foreach ($context->items as $index => $info) {
            if ($extras !== []) {
                $this->recallExtra($context, $sink, $info, $extras[$index % count($extras)]);
            }
        }
    }

    /**
     * Typed recall of every word, once per check set: the article for a noun
     * and every accent exact, with no cue beyond the English.
     */
    public function checkRecall(BuildContext $context, ExerciseSink $sink, string $set): void
    {
        foreach ($context->items as $info) {
            $sink->add('check.'.$set.'.type_word.'.$this->slug($info), self::CHECK_RECALL_BLOCK, LessonExerciseFormat::TypeWord, [
                'prompt' => $info->cue,
                'english' => $info->cue,
                'accepted' => $this->accepted($context, $info->accepted, $info),
                'portunol_slips' => $info->data->portunolSlips,
            ], [$this->target($info, true)], $set);
        }
    }

    /**
     * @param  list<ItemInfo>  $items
     * @return list<list<ItemInfo>>
     */
    private function batches(array $items): array
    {
        $batches = [];
        $position = 0;

        for ($i = 0; $position < count($items); $i++) {
            $size = self::BATCH_SIZES[$i % count(self::BATCH_SIZES)];
            $batches[] = array_slice($items, $position, $size);
            $position += $size;
        }

        return $batches;
    }

    /**
     * @param  list<ItemInfo>  $items
     * @return list<list<ItemInfo>>
     */
    private function matchGroups(array $items): array
    {
        $groups = array_chunk($items, 5);
        $last = count($groups) - 1;

        if ($last > 0 && count($groups[$last]) === 1) {
            $groups[$last - 1][] = $groups[$last][0];
            array_pop($groups);
        }

        return $groups;
    }

    /** @return list<ExerciseFamily> */
    private function extraFamilies(BuildContext $context): array
    {
        return array_values(array_filter(
            [ExerciseFamily::Listening, ExerciseFamily::Speaking],
            fn (ExerciseFamily $family): bool => $context->builds($family),
        ));
    }

    private function extra(BuildContext $context, ExerciseSink $sink, ItemInfo $info, ExerciseFamily $family, string $block): void
    {
        $slug = $this->slug($info);

        if ($family === ExerciseFamily::Listening) {
            [$options, $answer] = $this->meaningOptions($context, $info);
            $sink->add('meet.listen_choose.'.$slug, $block, LessonExerciseFormat::ListenChoose, [
                'text' => $info->item->term,
                'options' => $options,
                'answer' => $answer,
            ], [$this->target($info)]);

            return;
        }

        $sink->add('meet.speak_repeat.'.$slug, $block, LessonExerciseFormat::SpeakRepeat, [
            'text' => $info->item->term,
            'english' => $info->cue,
            'pattern' => $this->pattern($context, $info),
            'accepted' => $this->accepted($context, $this->lenientAnswers($context, $info), $info),
        ], [$this->target($info)]);
    }

    private function recallExtra(BuildContext $context, ExerciseSink $sink, ItemInfo $info, ExerciseFamily $family): void
    {
        $slug = $this->slug($info);
        $accepted = $this->accepted($context, $info->accepted, $info);

        if ($family === ExerciseFamily::Speaking) {
            $sink->add('recall.speak_answer.'.$slug, 'main', LessonExerciseFormat::SpeakAnswer, [
                'prompt' => $info->cue,
                'english' => $info->cue,
                'text' => $info->item->term,
                'slots' => [$info->accepted],
                'accepted' => $accepted,
            ], [$this->target($info)]);

            return;
        }

        if ($this->isPhrase($context, $info)) {
            $sink->add('recall.listen_type.'.$slug, 'main', LessonExerciseFormat::ListenType, [
                'text' => $info->item->term,
                'english' => $info->cue,
                'accepted' => $accepted,
            ], [$this->target($info)]);

            return;
        }

        [$options, $answer] = $this->meaningOptions($context, $info);
        $sink->add('recall.listen_choose.'.$slug, 'main', LessonExerciseFormat::ListenChoose, [
            'text' => $info->item->term,
            'options' => $options,
            'answer' => $answer,
        ], [$this->target($info)]);
    }

    /**
     * Dictation starts at phrases of two to four words: a single word heard
     * can still be spelt as a homophone, so context has to tell them apart.
     */
    private function isPhrase(BuildContext $context, ItemInfo $info): bool
    {
        $count = count(explode(' ', $context->normalizer->exactKey($info->item->term)));

        return $info->partOfSpeech() === 'phrase' && $count >= 2 && $count <= 4;
    }

    private function target(ItemInfo $info, bool $probe = false): TargetDefinition
    {
        return new TargetDefinition($info->ref(), $probe, false, $info->item->term);
    }

    private function slug(ItemInfo $info): string
    {
        return Str::slug($info->item->term);
    }

    /**
     * @param  list<string>  $answers
     * @return list<array{text: string, spans: array<string, array{int, int}>}>
     */
    private function accepted(BuildContext $context, array $answers, ItemInfo $info): array
    {
        $accepted = [];

        foreach ($answers as $answer) {
            $words = count(explode(' ', $context->normalizer->exactKey($answer)));
            $accepted[] = ['text' => $answer, 'spans' => [$info->key() => [0, $words]]];
        }

        return $accepted;
    }

    /**
     * Lesson 1 shows the article in the letters hint, so a bare noun is also
     * accepted there: the article starts to count from lesson 2.
     *
     * @return list<string>
     */
    private function lenientAnswers(BuildContext $context, ItemInfo $info): array
    {
        $answers = $info->accepted;

        if (! $info->isNoun()) {
            return $answers;
        }

        foreach ($info->accepted as $answer) {
            $bare = $this->withoutArticle($context, $answer);

            if ($bare !== null) {
                $answers[] = $bare;
            }
        }

        return array_values(array_unique($answers));
    }

    private function withoutArticle(BuildContext $context, string $text): ?string
    {
        $words = explode(' ', trim($text));

        if (count($words) < 2 || ! in_array($context->normalizer->exactKey($words[0]), $context->normalizer->articles(), true)) {
            return null;
        }

        return implode(' ', array_slice($words, 1));
    }

    /**
     * The first letter and the letter slots of what has to be typed. A leading
     * article is shown whole, because it is part of the answer from lesson 2.
     */
    private function pattern(BuildContext $context, ItemInfo $info): string
    {
        $words = explode(' ', trim($info->item->term));
        $shown = [];

        foreach ($words as $index => $word) {
            if ($index === 0 && count($words) > 1 && $info->isNoun() && in_array($context->normalizer->exactKey($word), $context->normalizer->articles(), true)) {
                $shown[] = $word;

                continue;
            }

            $shown[] = $this->maskWord($word);
        }

        return implode('  ', $shown);
    }

    /**
     * The first letter kept and every later letter a slot, with punctuation
     * such as the opening question mark left as it is.
     */
    private function maskWord(string $word): string
    {
        if (preg_match('/^([^\p{L}]*)(\p{L})(.*?)([^\p{L}]*)$/u', $word, $parts) !== 1) {
            return $word;
        }

        $slots = array_map(fn (string $char): string => preg_match('/\p{L}/u', $char) === 1 ? '_' : $char, mb_str_split($parts[3]));

        return $parts[1].trim($parts[2].' '.implode(' ', $slots)).$parts[4];
    }

    /**
     * @return array{list<string>, string} the options with the answer placed by the item's position, and the answer
     */
    private function meaningOptions(BuildContext $context, ItemInfo $info): array
    {
        $answer = $info->item->translation_en;
        $position = $info->index;
        $taken = [mb_strtolower($answer) => true];
        $same = [];
        $other = [];

        foreach ($this->othersAfter($context, $info) as $candidate) {
            $translation = $candidate->item->translation_en;

            if (isset($taken[mb_strtolower($translation)])) {
                continue;
            }

            $taken[mb_strtolower($translation)] = true;

            if ($candidate->partOfSpeech() === $info->partOfSpeech()) {
                $same[] = $translation;
            } else {
                $other[] = $translation;
            }
        }

        return $this->place($info, [...$same, ...$other], $answer, $position);
    }

    /**
     * A noun's options share its article: an option that is the same word with
     * the other article gives the answer away, as it is clearly one of the two.
     *
     * @param  list<string>  $terms
     * @return list<string>
     */
    private function sameArticleFirst(string $answer, array $terms): array
    {
        $article = strtok($answer, ' ');
        $matching = array_values(array_filter($terms, fn (string $term): bool => strtok($term, ' ') === $article));
        $rest = array_values(array_diff($terms, $matching));

        return [...$matching, ...$rest];
    }

    /**
     * @return array{list<string>, string}
     */
    private function termOptions(BuildContext $context, ItemInfo $info): array
    {
        $answer = $info->item->term;
        $accepted = array_map($context->normalizer->answerKey(...), $info->accepted);
        $candidates = [];

        if (in_array($info->partOfSpeech(), ['adjective', 'verb'], true)) {
            array_push($candidates, ...$info->data->forms);
        }

        $same = [];
        $other = [];

        foreach ($this->othersAfter($context, $info) as $candidate) {
            if ($candidate->partOfSpeech() === $info->partOfSpeech()) {
                $same[] = $candidate->item->term;
            } else {
                $other[] = $candidate->item->term;
            }
        }

        if ($info->isNoun()) {
            $same = $this->sameArticleFirst($answer, $same);
        }

        $options = [];
        $seen = array_flip($accepted);

        foreach ([...$candidates, ...$same, ...$other] as $candidate) {
            $key = $context->normalizer->answerKey($candidate);

            if (isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $options[] = $candidate;
        }

        return $this->place($info, $options, $answer, $info->index);
    }

    /**
     * @param  list<string>  $distractors
     * @return array{list<string>, string}
     */
    private function place(ItemInfo $info, array $distractors, string $answer, int $position): array
    {
        $options = array_slice($distractors, 0, 3);

        if ($options === []) {
            throw new InvalidLessonContent("'{$info->item->term}' has no distractors: a unit needs at least two items.");
        }

        array_splice($options, $position % (count($options) + 1), 0, [$answer]);

        return [$options, $answer];
    }

    /** @return list<ItemInfo> the other items in a cycle starting after this one */
    private function othersAfter(BuildContext $context, ItemInfo $info): array
    {
        $count = count($context->items);
        $start = $info->index;
        $others = [];

        for ($step = 1; $step < $count; $step++) {
            $others[] = $context->items[($start + $step) % $count];
        }

        return $others;
    }
}
