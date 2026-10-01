<?php

declare(strict_types=1);

namespace App\Actions\Progress;

use App\Enums\ErrorTagCategory;
use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class GetMostFrequentErrorTags
{
    /** @return Collection<int, array{error_tag_category: ErrorTagCategory, count: int}> */
    public function handle(User $user, Language $language, int $limit = 3): Collection
    {
        // DB::table (query builder), not SrsReview::query() (Eloquent) — an
        // Eloquent query still hydrates full models and auto-casts
        // error_tag_category to the enum, which then breaks the explicit
        // ErrorTagCategory::from() cast below on an already-cast value.
        $reviewTags = DB::table('srs_reviews')
            ->join('srs_cards', 'srs_cards.id', '=', 'srs_reviews.srs_card_id')
            ->where('srs_reviews.user_id', $user->id)
            ->where('srs_cards.language_id', $language->id)
            ->whereNotNull('srs_reviews.error_tag_category')
            ->selectRaw('srs_reviews.error_tag_category as category, count(*) as count')
            ->groupBy('srs_reviews.error_tag_category')
            ->get();

        $lessonTags = DB::table('lesson_answers')
            ->join('lesson_runs', 'lesson_runs.id', '=', 'lesson_answers.lesson_run_id')
            ->join('lessons', 'lessons.id', '=', 'lesson_runs.lesson_id')
            ->join('units', 'units.id', '=', 'lessons.unit_id')
            ->where('lesson_runs.user_id', $user->id)
            ->where('units.language_id', $language->id)
            ->whereNotNull('lesson_answers.error_tag_category')
            ->selectRaw('lesson_answers.error_tag_category as category, count(*) as count')
            ->groupBy('lesson_answers.error_tag_category')
            ->get();

        $counts = [];

        foreach ($reviewTags->concat($lessonTags) as $row) {
            $category = is_string($row->category) ? $row->category : '';
            $counts[$category] = ($counts[$category] ?? 0) + (is_numeric($row->count) ? (int) $row->count : 0);
        }

        arsort($counts);

        return collect(array_slice($counts, 0, $limit, true))
            ->map(fn (int $count, string $category): array => [
                'error_tag_category' => ErrorTagCategory::from($category),
                'count' => $count,
            ])
            ->values();
    }
}
