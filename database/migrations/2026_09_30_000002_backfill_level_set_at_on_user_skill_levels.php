<?php

declare(strict_types=1);

use Carbon\CarbonInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * A level was last set by the newest completed placement for its language
     * (every placement so far covers all four skills), or by a practice
     * level-up after it, the only other write to the row, which shows in
     * updated_at. updated_at alone would miss a placement that measured the
     * same level again, because that leaves the row untouched.
     */
    public function up(): void
    {
        DB::table('user_skill_levels')
            ->whereNull('level_set_at')
            ->select(['id', 'updated_at'])
            ->selectSub(fn (Builder $query): Builder => $query->from('placement_test_attempts')
                ->select('completed_at')
                ->whereColumn('placement_test_attempts.user_id', 'user_skill_levels.user_id')
                ->whereColumn('placement_test_attempts.language_id', 'user_skill_levels.language_id')
                ->whereNotNull('completed_at')
                ->orderByDesc('completed_at')
                ->limit(1), 'placed_at')
            ->get()
            ->each(function (stdClass $level): void {
                $setAt = collect([$level->placed_at, $level->updated_at])
                    ->map(fn (mixed $at): ?CarbonInterface => is_string($at) ? Date::parse($at) : null)
                    ->filter()
                    ->max();

                DB::table('user_skill_levels')->where('id', $level->id)->update(['level_set_at' => $setAt]);
            });
    }
};
