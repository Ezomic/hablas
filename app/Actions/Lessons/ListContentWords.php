<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Lessons\UnitContent;
use App\Models\Language;
use App\Services\TextNormalizerResolver;

final class ListContentWords
{
    public function __construct(
        private readonly TextNormalizerResolver $textNormalizerResolver = new TextNormalizerResolver,
    ) {}

    /**
     * Every distinct target-language word in a unit's word data, grammar
     * examples and authored answers, lowercased with accents kept, for the
     * spelling-dictionary pass.
     *
     * @return list<string>
     */
    public function handle(UnitContent $content, Language $language): array
    {
        $normalizer = $this->textNormalizerResolver->forLanguage($language);
        $texts = [];

        foreach ($content->words() as $word) {
            array_push($texts, $word->term, ...$word->accepted, ...$word->forms);
        }

        foreach ($content->grammarExamples() as $example) {
            $texts[] = $example['text'];
        }

        foreach ($content->exercises() as $exercise) {
            array_push($texts, ...$exercise->accepted);
        }

        $words = [];

        foreach ($texts as $text) {
            foreach (explode(' ', $normalizer->exactKey($text)) as $word) {
                if ($word !== '') {
                    $words[$word] = true;
                }
            }
        }

        $list = array_keys($words);
        sort($list, SORT_STRING);

        return array_map(strval(...), $list);
    }
}
