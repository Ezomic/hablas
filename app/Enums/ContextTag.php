<?php

namespace App\Enums;

use LogicException;

enum ContextTag: string
{
    case Travel = 'travel';
    case EverydaySocial = 'everyday_social';
    case Professional = 'professional';

    /**
     * Scheduling priority, derived from declaration order (travel-weighted
     * first, per the priority-goal decision) so it can't drift out of sync
     * with the case list above the way a separate lookup table could.
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
}
