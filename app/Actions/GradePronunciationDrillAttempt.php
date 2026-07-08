<?php

namespace App\Actions;

use App\Models\PronunciationDrillExercise;
use App\Services\PortugueseTextNormalizer;

class GradePronunciationDrillAttempt
{
    /**
     * A minimal-pair discrimination check — did the transcript contain the
     * nasal-marked target word, not the other word of the pair — rather than
     * a word-overlap percentage. Grades on transcribed text, not phonemes:
     * browser Web Speech API STT may itself auto-correct or normalize a
     * mispronounced nasal vowel before the transcript reaches this code
     * (e.g. transcribing a mispronounced "pau" attempt as "pão" because
     * that's the dictionary word STT expects) — a known, accepted
     * limitation per the Milestone 2 content-fidelity notes; real
     * native-speaker recordings and phoneme-level scoring would be needed
     * to close this gap, which is out of scope here.
     *
     * @return array{is_correct: bool, score: float}
     */
    public function handle(PronunciationDrillExercise $exercise, string $transcriptGuess): array
    {
        $normalizer = new PortugueseTextNormalizer;
        $target = $normalizer->foldAccents($exercise->target_word);
        $isCorrect = $normalizer->uniqueWords($transcriptGuess)->contains($target);

        return ['is_correct' => $isCorrect, 'score' => $isCorrect ? 100.0 : 0.0];
    }
}
