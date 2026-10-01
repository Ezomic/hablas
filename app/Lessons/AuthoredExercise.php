<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\LessonExerciseFormat;
use App\Enums\LessonStage;

/**
 * One hand-authored exercise from a unit's content class: sentences,
 * grammar gaps, dialogues and check items. The builder adds the accepted
 * answer spans, the probe flags and the substitute.
 */
final readonly class AuthoredExercise
{
    /**
     * @param  array<string, mixed>  $payload  prompt, english, options, answer, tiles and the like
     * @param  list<string>  $accepted  accepted answers of an exact-match format, the first being the one shown
     * @param  list<TargetSpec>  $targets
     * @param  list<string>  $portunolSlips
     */
    public function __construct(
        public LessonStage $stage,
        public LessonExerciseFormat $format,
        public string $key,
        public array $payload = [],
        public array $accepted = [],
        public array $targets = [],
        public ?string $probeSet = null,
        public string $block = 'main',
        public array $portunolSlips = [],
    ) {}
}
