<?php

declare(strict_types=1);

use App\Enums\CefrLevel;
use App\Enums\CefrSubLevel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * A row takes the tier of the newest completed placement that covers its
     * skill, when that tier belongs to the level now stored (practice may
     * have moved the level since). Otherwise it starts at the first tier of
     * its level, and a level without tiers stays null.
     *
     * An attempt stores either skill => "A2" (no tier) or
     * skill => {cefr_level, sub_level}.
     */
    public function up(): void
    {
        DB::table('user_skill_levels')
            ->whereNull('sub_level')
            ->get()
            ->each(function (stdClass $row): void {
                $level = is_string($row->cefr_level) ? CefrLevel::tryFrom($row->cefr_level) : null;

                if ($level === null || ! is_string($row->skill)) {
                    return;
                }

                $tier = $this->placedTier($row, $row->skill) ?? CefrSubLevel::firstOf($level);

                if ($tier === null || $tier->parentLevel() !== $level) {
                    $tier = CefrSubLevel::firstOf($level);
                }

                if ($tier !== null) {
                    DB::table('user_skill_levels')->where('id', $row->id)->update(['sub_level' => $tier->value]);
                }
            });
    }

    private function placedTier(stdClass $row, string $skill): ?CefrSubLevel
    {
        $attempts = DB::table('placement_test_attempts')
            ->where('user_id', $row->user_id)
            ->where('language_id', $row->language_id)
            ->whereNotNull('completed_at')
            ->where(fn (Builder $query): Builder => $query->whereNull('skill')->orWhere('skill', $skill))
            ->orderByDesc('completed_at')
            ->orderByDesc('id')
            ->pluck('resulting_skill_levels');

        foreach ($attempts as $json) {
            $resulting = json_decode(is_string($json) ? $json : '[]', true);
            $entry = is_array($resulting) ? ($resulting[$skill] ?? null) : null;

            if ($entry === null) {
                continue;
            }

            $value = is_array($entry) ? ($entry['sub_level'] ?? null) : null;

            return is_string($value) ? CefrSubLevel::tryFrom($value) : null;
        }

        return null;
    }
};
