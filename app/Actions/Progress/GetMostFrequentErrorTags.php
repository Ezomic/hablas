<?php

namespace App\Actions\Progress;

use App\Enums\ErrorTagCategory;
use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GetMostFrequentErrorTags
{
    /** @return Collection<int, array{error_tag_category: ErrorTagCategory, count: int}> */
    public function handle(User $user, Language $language, int $limit = 3): Collection
    {
        // DB::table (query builder), not SrsReview::query() (Eloquent) — an
        // Eloquent query still hydrates full models and auto-casts
        // error_tag_category to the enum, which then breaks the explicit
        // ErrorTagCategory::from() cast below on an already-cast value.
        return DB::table('srs_reviews')
            ->join('srs_cards', 'srs_cards.id', '=', 'srs_reviews.srs_card_id')
            ->where('srs_reviews.user_id', $user->id)
            ->where('srs_cards.language_id', $language->id)
            ->whereNotNull('srs_reviews.error_tag_category')
            ->selectRaw('srs_reviews.error_tag_category, count(*) as count')
            ->groupBy('srs_reviews.error_tag_category')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->map(fn (object $row): array => [
                'error_tag_category' => ErrorTagCategory::from($row->error_tag_category),
                'count' => (int) $row->count,
            ]);
    }
}
