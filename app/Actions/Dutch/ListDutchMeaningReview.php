<?php

declare(strict_types=1);

namespace App\Actions\Dutch;

use App\Models\DutchMeaningSuggestion;
use App\Models\Language;
use App\Models\Unit;
use App\Models\User;
use App\Models\VocabularyItem;

final class ListDutchMeaningReview
{
    /**
     * One unit of one language at a time: every word with its English and
     * Dutch meaning and the reader's own suggestion, plus what is needed to
     * move between units and languages.
     *
     * @return array{
     *     languages: list<array{code: string, name: string}>,
     *     language: string|null,
     *     units: list<array{slug: string, title: string, level: string, suggestions: int}>,
     *     unit: string|null,
     *     previous: string|null,
     *     next: string|null,
     *     items: list<array{id: int, term: string, english: string, dutch: string|null, suggestion: string|null, note: string|null}>,
     *     suggestionCount: int,
     * }
     */
    public function handle(User $user, ?string $languageCode, ?string $unitSlug): array
    {
        $languages = Language::query()->whereHas('vocabularyItems', fn ($query) => $query->whereNotNull('translation_nl'))->orderBy('name')->get();
        $language = $languages->firstWhere('code', $languageCode) ?? $languages->firstWhere('code', 'es') ?? $languages->first();

        if ($language === null) {
            return ['languages' => [], 'language' => null, 'units' => [], 'unit' => null, 'previous' => null, 'next' => null, 'items' => [], 'suggestionCount' => 0];
        }

        $suggested = DutchMeaningSuggestion::query()
            ->where('user_id', $user->id)
            ->whereHas('vocabularyItem', fn ($query) => $query->where('language_id', $language->id))
            ->with('vocabularyItem:id,unit_id')
            ->get();
        $perUnit = $suggested->countBy(fn (DutchMeaningSuggestion $row): int => (int) $row->vocabularyItem?->unit_id);

        $units = Unit::query()->where('language_id', $language->id)->whereHas('vocabularyItems')->orderBy('sort_order')->get();
        $current = $units->firstWhere('slug', $unitSlug) ?? $units->first();
        $position = $current === null ? null : $units->search(fn (Unit $unit): bool => $unit->id === $current->id);

        return [
            'languages' => array_values($languages->map(fn (Language $row): array => ['code' => $row->code, 'name' => $row->localizedName()])->all()),
            'language' => $language->code,
            'units' => array_values($units->map(fn (Unit $unit): array => [
                'slug' => $unit->slug,
                'title' => $unit->title,
                'level' => $unit->cefr_level->value,
                'suggestions' => $perUnit->get($unit->id, 0),
            ])->all()),
            'unit' => $current?->slug,
            'previous' => $position === null || $position === 0 ? null : $units->get($position - 1)?->slug,
            'next' => $position === null ? null : $units->get($position + 1)?->slug,
            'items' => $current === null ? [] : $this->items($user, $current),
            'suggestionCount' => $suggested->count(),
        ];
    }

    /** @return list<array{id: int, term: string, english: string, dutch: string|null, suggestion: string|null, note: string|null}> */
    private function items(User $user, Unit $unit): array
    {
        $suggestions = DutchMeaningSuggestion::query()->where('user_id', $user->id)->whereHas('vocabularyItem', fn ($query) => $query->where('unit_id', $unit->id))->get()->keyBy('vocabulary_item_id');

        return array_values(VocabularyItem::query()->where('unit_id', $unit->id)->orderBy('id')->get()->map(fn (VocabularyItem $item): array => [
            'id' => $item->id,
            'term' => $item->term,
            'english' => $item->translation_en,
            'dutch' => $item->translation_nl,
            'suggestion' => $suggestions->get($item->id)?->suggestion,
            'note' => $suggestions->get($item->id)?->note,
        ])->all());
    }
}
