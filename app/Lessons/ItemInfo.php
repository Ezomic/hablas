<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Models\VocabularyItem;

/**
 * A vocabulary item together with its reviewed word data, resolved once per
 * build so every generated exercise sees the same cue and accepted answers.
 */
final readonly class ItemInfo
{
    /**
     * @param  list<string>  $accepted  every accepted typed answer, the shown one first
     * @param  int  $index  the item's place in the unit, which sets where distractors start and where the answer sits
     */
    public function __construct(
        public VocabularyItem $item,
        public WordData $data,
        public string $cue,
        public array $accepted,
        public int $index,
    ) {}

    public function ref(): TargetRef
    {
        return TargetRef::for($this->item);
    }

    public function key(): string
    {
        return $this->ref()->key();
    }

    public function isNoun(): bool
    {
        return $this->item->part_of_speech === 'noun';
    }

    public function partOfSpeech(): string
    {
        return $this->item->part_of_speech;
    }
}
