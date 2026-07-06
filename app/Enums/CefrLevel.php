<?php

namespace App\Enums;

use InvalidArgumentException;
use LogicException;

enum CefrLevel: string
{
    case A1 = 'A1';
    case A2 = 'A2';
    case B1 = 'B1';
    case B2 = 'B2';
    case C1 = 'C1';
    case C2 = 'C2';

    /**
     * Position in the six-level scale, derived from declaration order so it
     * can't drift out of sync with the case list above.
     */
    public function sortOrder(): int
    {
        foreach (self::cases() as $index => $case) {
            if ($case === $this) {
                return $index;
            }
        }

        throw new LogicException('Unreachable: enum case not found in its own cases() list.');
    }

    public static function lowest(CefrLevel ...$levels): self
    {
        if ($levels === []) {
            throw new InvalidArgumentException('CefrLevel::lowest() requires at least one level.');
        }

        return array_reduce(
            $levels,
            fn (CefrLevel $lowest, CefrLevel $level): CefrLevel => $level->sortOrder() < $lowest->sortOrder() ? $level : $lowest,
            $levels[0],
        );
    }
}
