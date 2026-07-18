<?php

namespace App\Actions\Placement;

use App\Enums\CefrLevel;
use App\Enums\Skill;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestResponse;
use Illuminate\Support\Collection;

class BuildPlacementResult
{
    /**
     * Assemble the review payload for a completed placement attempt: the
     * blended level, the per-skill CEFR level it set, and a per-question
     * breakdown (prompt, the learner's answer, the correct answer, and
     * whether they got it right, wrong, or abstained).
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

        $levels = [];
        $skills = [];

        foreach (Skill::cases() as $skill) {
            $level = $this->levelFor($resulting, $skill);

            if ($level !== null) {
                $levels[] = $level;
            }

            $skills[] = [
                'skill' => $skill->value,
                'level' => $level?->value,
                'items' => $this->breakdownFor($responsesBySkill->get($skill->value)),
            ];
        }

        return [
            'completedAt' => $attempt->completed_at?->toIso8601String(),
            'blendedLevel' => $levels === [] ? null : CefrLevel::lowest(...$levels)->value,
            'skipped' => $attempt->responses()->doesntExist(),
            'skills' => $skills,
        ];
    }

    /** @param  array<string, mixed>  $resulting */
    private function levelFor(array $resulting, Skill $skill): ?CefrLevel
    {
        $entry = $resulting[$skill->value] ?? null;

        // Old attempts stored skill => "A2"; new ones store the richer
        // skill => {cefr_level, sub_level} shape.
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
