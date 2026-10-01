<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\LessonStage;

final readonly class LessonDefinition
{
    /** @param  list<ExerciseDefinition>  $exercises */
    public function __construct(
        public LessonStage $stage,
        public string $title,
        public int $position,
        public array $exercises,
    ) {}
}
