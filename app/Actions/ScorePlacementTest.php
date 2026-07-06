<?php

namespace App\Actions;

use App\Enums\CefrLevel;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestItem;
use App\Models\User;
use App\Models\UserSkillLevel;

class ScorePlacementTest
{
    /**
     * @param  array<int, string>  $responses  Placement test item ID => selected answer.
     */
    public function handle(User $user, Language $language, array $responses): PlacementTestAttempt
    {
        $items = PlacementTestItem::query()->where('language_id', $language->id)->get();

        $tally = [];

        foreach ($items as $item) {
            $skillValue = $item->skill->value;
            $tally[$skillValue] ??= ['correct' => 0, 'total' => 0];
            $tally[$skillValue]['total']++;

            if (($responses[$item->id] ?? null) === $item->correct_answer) {
                $tally[$skillValue]['correct']++;
            }
        }

        $resultingLevels = [];

        foreach ($tally as $skillValue => $score) {
            $resultingLevels[$skillValue] = $this->levelForScore($score['correct'], $score['total'])->value;
        }

        $attempt = PlacementTestAttempt::create([
            'user_id' => $user->id,
            'language_id' => $language->id,
            'started_at' => now(),
            'completed_at' => now(),
            'resulting_skill_levels' => $resultingLevels,
        ]);

        foreach ($resultingLevels as $skillValue => $cefrLevelValue) {
            UserSkillLevel::query()->updateOrCreate(
                ['user_id' => $user->id, 'language_id' => $language->id, 'skill' => $skillValue],
                ['cefr_level' => $cefrLevelValue],
            );
        }

        return $attempt;
    }

    /**
     * Authored threshold, not adaptive/IRT scoring: getting most of a skill's
     * A1-tagged items right suggests that skill is ready to move past A1.
     */
    private function levelForScore(int $correct, int $total): CefrLevel
    {
        if ($total === 0) {
            return CefrLevel::A1;
        }

        return ($correct / $total) >= 0.8 ? CefrLevel::A2 : CefrLevel::A1;
    }
}
