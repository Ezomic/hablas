<?php

declare(strict_types=1);

namespace App\Lessons;

use App\Enums\LessonExerciseFormat;

final readonly class ExerciseDefinition
{
    public string $hash;

    /**
     * @param  array<string, mixed>  $payload
     * @param  list<TargetDefinition>  $targets
     */
    public function __construct(
        public string $key,
        public int $position,
        public string $block,
        public LessonExerciseFormat $format,
        public ?string $probeSet,
        public array $payload,
        public array $targets,
        public ?string $substituteForKey = null,
    ) {
        $this->hash = hash('sha256', json_encode([
            $this->key,
            $this->position,
            $this->block,
            $this->format->value,
            $this->probeSet,
            $this->payload,
            array_map(fn (TargetDefinition $target): array => $target->toArray(), $this->targets),
            $this->substituteForKey,
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
    }
}
