<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Enums\Skill;
use App\Models\LessonRun;
use App\Models\LessonSkillScore;
use App\Models\UserSkillLevel;
use App\Services\RunOutcomes;
use LogicException;

final class RecordLessonSkillScores
{
    /**
     * A skill needs at least this many first-try, unhinted, unscaffolded
     * answers in a run to get a score: skipping all speaking leaves speaking
     * untouched rather than lowering or raising it.
     */
    private const MINIMUM_GRADED = 3;

    public function __construct(
        private readonly RunOutcomes $runOutcomes = new RunOutcomes,
    ) {}

    /**
     * Writes one score row per skill with enough evidence, counted toward the
     * level only when the unit's level is at or above the learner's level in
     * that skill, so A1 lessons cannot move an A2 skill.
     *
     * @return list<Skill> the skills whose score counts toward their level
     */
    public function handle(LessonRun $run): array
    {
        $lesson = $run->lesson ?? throw new LogicException("Run {$run->id} has no lesson.");
        $unit = $lesson->unit ?? throw new LogicException("Lesson {$lesson->id} has no unit.");
        $weights = array_fill_keys(array_column(Skill::cases(), 'value'), 0);
        $corrects = array_fill_keys(array_column(Skill::cases(), 'value'), 0.0);

        foreach ($this->runOutcomes->handle($run) as $outcome) {
            $skill = $outcome['exercise']->format->skill();

            if ($outcome['origin'] !== 'lesson' || $skill === null || $outcome['exercise']->format->isScaffolded() || $outcome['answer']->hinted) {
                continue;
            }

            $weights[$skill->value] += $outcome['weight'];
            $corrects[$skill->value] += $outcome['correct'];
        }

        $counted = [];

        foreach (Skill::cases() as $skill) {
            $weight = $weights[$skill->value];

            if ($weight < self::MINIMUM_GRADED) {
                continue;
            }

            $level = UserSkillLevel::query()->where('user_id', $run->user_id)->where('language_id', $unit->language_id)->where('skill', $skill)->first();
            $counts = $level !== null && $unit->cefr_level->sortOrder() >= $level->cefr_level->sortOrder();

            LessonSkillScore::query()->updateOrCreate(['lesson_run_id' => $run->id, 'skill' => $skill], [
                'user_id' => $run->user_id,
                'language_id' => $unit->language_id,
                'score' => round($corrects[$skill->value] / $weight * 100, 1),
                'graded_count' => min($weight, 255),
                'counts_toward_level' => $counts,
                'scored_at' => now(),
            ]);

            if ($counts) {
                $counted[] = $skill;
            }
        }

        return $counted;
    }
}
