<?php

declare(strict_types=1);

namespace App\Actions\Dutch;

use App\Models\DutchMeaningSuggestion;
use App\Models\User;
use App\Models\VocabularyItem;

final class SuggestDutchMeaning
{
    /** Saves the reader's suggestion for an item, or clears it when both fields are empty. */
    public function handle(User $user, VocabularyItem $item, ?string $suggestion, ?string $note): void
    {
        $suggestion = $this->clean($suggestion);
        $note = $this->clean($note);
        $existing = DutchMeaningSuggestion::query()->where('user_id', $user->id)->where('vocabulary_item_id', $item->id);

        if ($suggestion === null && $note === null) {
            $existing->delete();

            return;
        }

        DutchMeaningSuggestion::query()->updateOrCreate(
            ['user_id' => $user->id, 'vocabulary_item_id' => $item->id],
            ['suggestion' => $suggestion, 'note' => $note],
        );
    }

    private function clean(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
