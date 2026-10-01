<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Models\LessonRun;
use App\Services\UnitMasteryReader;
use LogicException;

final class PresentLessonProgress
{
    public function __construct(
        private readonly UnitMasteryReader $unitMasteryReader = new UnitMasteryReader,
    ) {}

    /**
     * What an answer or a flag tells the device about its run.
     *
     * @return array{completed: bool, unitCompleted: bool, mastery: array{mastered: int, total: int}}
     */
    public function handle(LessonRun $run, bool $completed): array
    {
        $lesson = $run->lesson ?? throw new LogicException("Run {$run->id} has no lesson.");
        $unit = $lesson->unit ?? throw new LogicException("Lesson {$lesson->id} has no unit.");
        $user = $run->user ?? throw new LogicException("Run {$run->id} has no user.");
        $total = count($this->unitMasteryReader->items($unit));

        return [
            'completed' => $completed,
            'unitCompleted' => $completed && ($run->result['unit_completed'] ?? false) === true,
            'mastery' => ['mastered' => $total - count($this->unitMasteryReader->missing($user, $unit)), 'total' => $total],
        ];
    }
}
