<?php

declare(strict_types=1);

namespace Database\Seeders\Concerns;

use App\Enums\CefrLevel;
use App\Models\GrammarPoint;
use App\Models\Language;
use App\Models\Unit;
use App\Models\UnitInterestTag;
use App\Models\VocabularyItem;
use Database\Seeders\SpanishA1Seeder;

/**
 * @phpstan-import-type UnitDefinition from SpanishA1Seeder
 */
trait SeedsUnitDefinitions
{
    /**
     * @param  list<UnitDefinition>  $definitions
     */
    private function seedUnitDefinitions(string $languageCode, CefrLevel $level, array $definitions, int $firstSortOrder): void
    {
        $language = Language::query()->where('code', $languageCode)->firstOrFail();

        foreach ($definitions as $index => $definition) {
            $unit = Unit::query()->updateOrCreate(
                ['language_id' => $language->id, 'slug' => $definition['slug']],
                [
                    'title' => $definition['title'],
                    'cefr_level' => $level,
                    'context_tag' => $definition['context_tag'],
                    'primary_skill' => $definition['primary_skill'],
                    'secondary_skill' => $definition['secondary_skill'],
                    'task_description' => $definition['task_description'],
                    'sort_order' => $firstSortOrder + $index,
                ],
            );

            foreach ($definition['vocabulary'] as $vocabulary) {
                VocabularyItem::query()->updateOrCreate(
                    ['language_id' => $language->id, 'unit_id' => $unit->id, 'term' => $vocabulary['term']],
                    $vocabulary,
                );
            }

            foreach ($definition['grammar'] as $grammar) {
                GrammarPoint::query()->updateOrCreate(
                    ['language_id' => $language->id, 'unit_id' => $unit->id, 'title' => $grammar['title']],
                    $grammar,
                );
            }

            foreach ($definition['interest_tags'] as $interestTag) {
                UnitInterestTag::query()->updateOrCreate(
                    ['unit_id' => $unit->id, 'interest_tag' => $interestTag],
                );
            }
        }
    }
}
