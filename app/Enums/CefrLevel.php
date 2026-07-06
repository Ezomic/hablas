<?php

namespace App\Enums;

enum CefrLevel: string
{
    case A1 = 'A1';
    case A2 = 'A2';
    case B1 = 'B1';
    case B2 = 'B2';
    case C1 = 'C1';
    case C2 = 'C2';

    public function sortOrder(): int
    {
        return match ($this) {
            self::A1 => 1,
            self::A2 => 2,
            self::B1 => 3,
            self::B2 => 4,
            self::C1 => 5,
            self::C2 => 6,
        };
    }

    public static function lowest(CefrLevel ...$levels): self
    {
        return collect($levels)->sortBy(fn (self $level) => $level->sortOrder())->firstOrFail();
    }
}
