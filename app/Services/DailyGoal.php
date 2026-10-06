<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Language;
use App\Models\SrsReview;
use App\Models\User;
use App\Models\VocabularyItem;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

final class DailyGoal
{
    public const int WORDS = 10;

    /**
     * The distinct words of a language the learner answered in a lesson or
     * reviewed today, so asking about one word ten times counts once.
     */
    public function wordsToday(User $user, Language $language): int
    {
        $start = CarbonImmutable::today();
        $morph = (new VocabularyItem)->getMorphClass();

        $answered = DB::table('lesson_answer_targets')
            ->join('lesson_answers', 'lesson_answers.id', '=', 'lesson_answer_targets.lesson_answer_id')
            ->join('lesson_runs', 'lesson_runs.id', '=', 'lesson_answers.lesson_run_id')
            ->join('vocabulary_items', 'vocabulary_items.id', '=', 'lesson_answer_targets.targetable_id')
            ->where('lesson_runs.user_id', $user->id)
            ->where('lesson_answer_targets.targetable_type', $morph)
            ->where('vocabulary_items.language_id', $language->id)
            ->where('lesson_answers.answered_at', '>=', $start)
            ->pluck('vocabulary_items.id');

        $reviewed = SrsReview::query()
            ->join('srs_cards', 'srs_cards.id', '=', 'srs_reviews.srs_card_id')
            ->where('srs_reviews.user_id', $user->id)
            ->where('srs_cards.cardable_type', $morph)
            ->where('srs_cards.language_id', $language->id)
            ->where('srs_reviews.reviewed_at', '>=', $start)
            ->pluck('srs_cards.cardable_id');

        return $answered->merge($reviewed)->unique()->count();
    }
}
