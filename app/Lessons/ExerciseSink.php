<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\LessonExerciseFormat;
use App\Enums\LessonStage;

/**
 * Collects the exercises of one lesson in order, and puts a substitute next
 * to every listening and speaking exercise as it is added.
 */
final class ExerciseSink
{
    /** @var list<ExerciseDefinition> */
    private array $originals = [];

    /** @var list<ExerciseDefinition> */
    private array $substitutes = [];

    /** @var array<string, true> */
    private array $keys = [];

    public function __construct(
        private readonly LessonStage $stage,
        private readonly SubstituteBuilder $substituteBuilder,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     * @param  list<TargetDefinition>  $targets
     */
    public function add(string $key, string $block, LessonExerciseFormat $format, array $payload, array $targets, ?string $probeSet = null): void
    {
        $this->remember($key);

        $position = count($this->originals) + 1;
        $this->originals[] = new ExerciseDefinition($key, $position, $block, $format, $probeSet, $payload, $targets);

        $substitute = $this->substituteBuilder->handle($this->stage, $format, $payload, $targets);

        if ($substitute === null) {
            return;
        }

        $this->remember($key.'.sub');
        $this->substitutes[] = new ExerciseDefinition(
            $key.'.sub',
            $position,
            $block,
            $substitute['format'],
            $probeSet,
            $substitute['payload'],
            $substitute['targets'],
            $key,
        );
    }

    /** @return list<ExerciseDefinition> */
    public function all(): array
    {
        return [...$this->originals, ...$this->substitutes];
    }

    public function isEmpty(): bool
    {
        return $this->originals === [];
    }

    private function remember(string $key): void
    {
        if (isset($this->keys[$key])) {
            throw new InvalidLessonContent("Exercise key '{$key}' is used twice in the {$this->stage->value} lesson.");
        }

        $this->keys[$key] = true;
    }
}
