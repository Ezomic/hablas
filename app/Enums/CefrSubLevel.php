<?php

namespace App\Enums;

use LogicException;

enum CefrSubLevel: string
{
    case A1_1 = 'A1.1';
    case A1_2 = 'A1.2';
    case A1_3 = 'A1.3';
    case A2_1 = 'A2.1';
    case A2_2 = 'A2.2';
    case B1_1 = 'B1.1';
    case B1_2 = 'B1.2';
    case B2 = 'B2';

    /**
     * Position in the eight-tier scale, derived from declaration order so it
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

    /**
     * Clamps at B2 — a correct answer at the ceiling is a no-op step.
     */
    public function stepUp(): self
    {
        return self::cases()[$this->sortOrder() + 1] ?? $this;
    }

    /**
     * Clamps at A1.1 — an incorrect answer at the floor is a no-op step.
     */
    public function stepDown(): self
    {
        $previous = $this->sortOrder() - 1;

        return $previous >= 0 ? self::cases()[$previous] : $this;
    }

    public function parentLevel(): CefrLevel
    {
        return match ($this) {
            self::A1_1, self::A1_2, self::A1_3 => CefrLevel::A1,
            self::A2_1, self::A2_2 => CefrLevel::A2,
            self::B1_1, self::B1_2 => CefrLevel::B1,
            self::B2 => CefrLevel::B2,
        };
    }
}
