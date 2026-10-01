<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Contracts\TextNormalizer;
use App\Enums\ExerciseFamily;
use App\Models\GrammarPoint;
use App\Models\Unit;

final readonly class BuildContext
{
    /**
     * @param  list<ItemInfo>  $items  in the unit's own order
     * @param  list<ExerciseFamily>  $families  the exercise families to build
     */
    public function __construct(
        public Unit $unit,
        public UnitContent $content,
        public TextNormalizer $normalizer,
        public array $items,
        public ?GrammarPoint $grammar,
        public array $families,
    ) {}

    public function builds(ExerciseFamily $family): bool
    {
        return in_array($family, $this->families, true);
    }

    public function itemForTerm(string $term): ItemInfo
    {
        foreach ($this->items as $info) {
            if ($info->item->term === $term) {
                return $info;
            }
        }

        throw new InvalidLessonContent("Unit {$this->unit->slug} has no vocabulary item '{$term}'.");
    }
}
