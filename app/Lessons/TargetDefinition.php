<?php

declare(strict_types=1);

namespace App\Lessons;

final readonly class TargetDefinition
{
    public function __construct(
        public TargetRef $ref,
        public bool $isProbe,
        public bool $isContrast,
        public ?string $form,
    ) {}

    /** @return array{type: string, id: int, probe: bool, contrast: bool, form: string|null} */
    public function toArray(): array
    {
        return [
            'type' => $this->ref->type,
            'id' => $this->ref->id,
            'probe' => $this->isProbe,
            'contrast' => $this->isContrast,
            'form' => $this->form,
        ];
    }
}
