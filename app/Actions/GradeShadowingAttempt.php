<?php

namespace App\Actions;

use App\Models\ShadowingExercise;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GradeShadowingAttempt
{
    /**
     * Rough word-overlap match against the target transcript — not
     * phoneme-level pronunciation scoring, per the tier-1 shadowing scope.
     * Returns a percentage from 0 to 100.
     */
    public function handle(ShadowingExercise $exercise, string $transcriptGuess): float
    {
        $targetWords = $this->normalizeWords($exercise->target_transcript);

        if ($targetWords->isEmpty()) {
            return 0.0;
        }

        $guessWords = $this->normalizeWords($transcriptGuess);
        $matched = $targetWords->intersect($guessWords)->count();

        return round(($matched / $targetWords->count()) * 100, 1);
    }

    /**
     * Lowercases, strips accents, and strips punctuation before splitting
     * into unique words — speech-recognition output varies in how (or
     * whether) it renders diacritics, so folding them keeps the match rough
     * rather than penalizing an otherwise-correct answer for an accent mark.
     *
     * @return Collection<int, non-empty-string>
     */
    private function normalizeWords(string $text): Collection
    {
        $normalized = Str::ascii(Str::lower(trim($text)));
        $normalized = preg_replace('/[^a-z0-9\s]/', '', $normalized) ?? '';

        $words = preg_split('/\s+/', $normalized, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return collect($words)->unique();
    }
}
