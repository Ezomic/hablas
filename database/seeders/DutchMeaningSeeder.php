<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\VocabularyItem;
use Illuminate\Database\Seeder;

/**
 * Fills in the Dutch meaning of every vocabulary item from
 * data/dutch-meanings.php. Idempotent, and only items whose Dutch differs
 * are written, so the usual deploy writes nothing.
 */
class DutchMeaningSeeder extends Seeder
{
    public function run(): void
    {
        /** @var list<array{string, string, string, string}> $meanings */
        $meanings = require __DIR__.'/data/dutch-meanings.php';

        $current = [];

        foreach (VocabularyItem::query()->with(['language', 'unit'])->get() as $item) {
            $current[implode('|', [$item->language?->code, $item->unit?->slug, $item->term])] = $item;
        }

        foreach ($meanings as [$language, $unit, $term, $dutch]) {
            $item = $current[$language.'|'.$unit.'|'.$term] ?? null;

            if ($item !== null && $item->translation_nl !== $dutch) {
                VocabularyItem::query()->whereKey($item->id)->update(['translation_nl' => $dutch]);
            }
        }
    }
}
