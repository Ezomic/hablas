<?php

declare(strict_types=1);

use App\Models\VocabularyItem;
use Database\Seeders\ContentSeeder;
use Database\Seeders\DutchMeaningSeeder;

it('gives every vocabulary item of the course a Dutch meaning', function () {
    $this->seed(ContentSeeder::class);

    expect(VocabularyItem::query()->whereNull('translation_nl')->pluck('term')->all())->toBe([])
        ->and(VocabularyItem::query()->where('term', 'hola')->value('translation_nl'))->toBe('hallo');
});

it('lists a Dutch meaning only for words the course has', function () {
    $this->seed(ContentSeeder::class);

    $known = VocabularyItem::query()
        ->join('languages', 'languages.id', '=', 'vocabulary_items.language_id')
        ->join('units', 'units.id', '=', 'vocabulary_items.unit_id')
        ->get(['languages.code as language_code', 'units.slug as unit_slug', 'vocabulary_items.term'])
        ->map(fn ($item): string => $item->getAttribute('language_code').'|'.$item->getAttribute('unit_slug').'|'.$item->term)
        ->all();

    foreach (require database_path('seeders/data/dutch-meanings.php') as [$language, $unit, $term, $dutch]) {
        expect($known)->toContain($language.'|'.$unit.'|'.$term)
            ->and($dutch)->not->toBe('')
            ->and($dutch)->not->toContain('—');
    }
});

it('writes nothing the second time', function () {
    $this->seed(ContentSeeder::class);
    $updated = VocabularyItem::query()->where('term', 'hola')->value('updated_at');

    $this->travel(5)->minutes();
    $this->seed(DutchMeaningSeeder::class);

    expect(VocabularyItem::query()->where('term', 'hola')->value('updated_at'))->toEqual($updated);
});
