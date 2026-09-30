<?php

declare(strict_types=1);

namespace App\Actions\Placement;

use App\Enums\CefrLevel;
use App\Enums\CefrSubLevel;
use App\Enums\Skill;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestResponse;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

final class BuildPlacementResult
{
    /**
     * Assemble the review payload for a learner's completed placement
     * attempts: the blended level, the per-skill CEFR level (as a sub-level
     * like "A2.1" when available), and a per-question breakdown (prompt, the
     * learner's answer, the correct answer, and whether they got it right,
     * wrong, or abstained). Each skill comes from the newest attempt that
     * placed it, so a one-skill re-take shows beside the full test's other
     * three skills.
     *
     * @param  Collection<int, PlacementTestAttempt>  $attempts  Completed, newest first.
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
    public function handle(Collection $attempts): array
    {
        $subLevels = [];
        $parentLevels = [];
        $skills = [];
        $responsesByAttempt = [];

        foreach (Skill::cases() as $skill) {
            $attempt = $attempts->first(fn (PlacementTestAttempt $attempt): bool => in_array($skill, $attempt->skills(), true));
            $resulting = $attempt->resulting_skill_levels ?? [];

            if ($attempt !== null) {
                $responsesByAttempt[$attempt->id] ??= $this->responsesBySkill($attempt);
            }

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
                'items' => $this->breakdownFor($attempt === null ? null : $responsesByAttempt[$attempt->id]->get($skill->value)),
            ];
        }

        return [
            'completedAt' => $attempts->first()?->completed_at?->toIso8601String(),
            'blendedLevel' => $this->blendedLevel($subLevels, $parentLevels),
            'skipped' => collect($responsesByAttempt)->every(fn (Collection $responses): bool => $responses->isEmpty()),
            'skills' => $skills,
        ];
    }

    /** @return Collection<array-key, EloquentCollection<int, PlacementTestResponse>> */
    private function responsesBySkill(PlacementTestAttempt $attempt): Collection
    {
        return $attempt->responses()
            ->with('item')
            ->orderBy('id')
            ->get()
            ->groupBy(fn (PlacementTestResponse $response): string => $response->skill->value);
    }

    /**
     * Prefer the finer sub-level scale ("A2.1") when every skill recorded it;
     * fall back to the parent level when any skill comes from an old attempt
     * that only stored "A2", so that skill still counts towards the minimum.
     *
     * @param  list<CefrSubLevel>  $subLevels
     * @param  list<CefrLevel>  $parentLevels
     */
    private function blendedLevel(array $subLevels, array $parentLevels): ?string
    {
        if ($subLevels !== [] && count($subLevels) === count($parentLevels)) {
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
                'prompt' => $response->item->prompt ?? '',
                'yourAnswer' => $abstained ? null : $response->response,
                'correctAnswer' => $response->item->correct_answer ?? '',
                'status' => match (true) {
                    $abstained => 'dont_know',
                    $response->is_correct => 'correct',
                    default => 'incorrect',
                },
            ];
        })->all());
    }
}
