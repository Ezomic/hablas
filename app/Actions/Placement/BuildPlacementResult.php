<?php

namespace App\Actions\Placement;

use App\Enums\CefrLevel;
use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestResponse;
use Illuminate\Support\Collection;

class BuildPlacementResult
{
    /**
     * Assemble the review payload for a completed placement attempt: the
     * blended level, the per-skill CEFR level it set (as a sub-level like
     * "A2.1" when available), and a per-question breakdown (prompt, the
     * learner's answer, the correct answer, and whether they got it right,
     * wrong, or abstained).
     *
     * @return array{
     *     completedAt: string|null,
     *     blendedLevel: string|null,
     *     skipped: bool,
     *     skills: list<array{
     *         skill: string,
     *         level: string|null,
     *         items: list<array{prompt: string, yourAnswer: string|null, correctAnswer: string, status: string}>
     *     }>
     * }
     */
    public function handle(PlacementTestAttempt $attempt): array
    {
        $resulting = $attempt->resulting_skill_levels ?? [];

        $responsesBySkill = $attempt->responses()
            ->with('item')
            ->orderBy('id')
            ->get()
            ->groupBy(fn (PlacementTestResponse $response): string => $response->skill->value);

        $subLevels = [];
        $parentLevels = [];
        $skills = [];

        foreach (Skill::cases() as $skill) {
            $subLevel = $this->subLevelFor($resulting, $skill);
            $parentLevel = $this->parentLevelFor($resulting, $skill);

            if ($subLevel !== null) {
                $subLevels[] = $subLevel;
            }

            if ($parentLevel !== null) {
                $parentLevels[] = $parentLevel;
            }

            $skills[] = [
                'skill' => $skill->value,
                'level' => $subLevel !== null ? $subLevel->value : $parentLevel?->value,
                'items' => $this->breakdownFor($responsesBySkill->get($skill->value)),
            ];
        }

        return [
            'completedAt' => $attempt->completed_at?->toIso8601String(),
            'blendedLevel' => $this->blendedLevel($subLevels, $parentLevels),
            'skipped' => $attempt->responses()->doesntExist(),
            'skills' => $skills,
        ];
    }

    /**
     * Prefer the finer sub-level scale ("A2.1") when the attempt recorded it;
     * fall back to the parent level for old attempts that only stored "A2".
     *
     * @param  list<CefrSubLevel>  $subLevels
     * @param  list<CefrLevel>  $parentLevels
     */
    private function blendedLevel(array $subLevels, array $parentLevels): ?string
    {
        if ($subLevels !== []) {
            return CefrSubLevel::lowest(...$subLevels)->value;
        }

        return $parentLevels === [] ? null : CefrLevel::lowest(...$parentLevels)->value;
    }

    /** @param  array<string, mixed>  $resulting */
    private function subLevelFor(array $resulting, Skill $skill): ?CefrSubLevel
    {
        $entry = $resulting[$skill->value] ?? null;

        // Only the newer skill => {cefr_level, sub_level} shape carries a
        // sub-level; the old skill => "A2" string does not.
        $value = is_array($entry) ? ($entry['sub_level'] ?? null) : null;

        return is_string($value) ? CefrSubLevel::tryFrom($value) : null;
    }

    /** @param  array<string, mixed>  $resulting */
    private function parentLevelFor(array $resulting, Skill $skill): ?CefrLevel
    {
        $entry = $resulting[$skill->value] ?? null;

        $value = is_array($entry) ? ($entry['cefr_level'] ?? null) : $entry;

        return is_string($value) ? CefrLevel::tryFrom($value) : null;
    }

    /**
     * @param  Collection<int, PlacementTestResponse>|null  $responses
     * @return list<array{prompt: string, yourAnswer: string|null, correctAnswer: string, status: string}>
     */
    private function breakdownFor(?Collection $responses): array
    {
        if ($responses === null) {
            return [];
        }

        return array_values($responses->map(function (PlacementTestResponse $response): array {
            $abstained = $response->response === PlacementTestResponse::DONT_KNOW;

            return [
                'prompt' => $response->item->prompt,
                'yourAnswer' => $abstained ? null : $response->response,
                'correctAnswer' => $response->item->correct_answer,
                'status' => match (true) {
                    $abstained => 'dont_know',
                    $response->is_correct => 'correct',
                    default => 'incorrect',
                },
            ];
        })->all());
    }
}
