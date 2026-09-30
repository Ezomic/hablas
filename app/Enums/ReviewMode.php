<?php

declare(strict_types=1);

namespace App\Enums;

enum ReviewMode: string
{
    case Recognition = 'recognition';
    case Production = 'production';
    case Mix = 'mix';

    /**
     * Mix keeps typing for words that have graduated to the Review state, so
     * a word is recognised a few times before it has to be produced.
     */
    public function asksToType(SrsCardState $state): bool
    {
        return match ($this) {
            self::Recognition => false,
            self::Production => true,
            self::Mix => $state === SrsCardState::Review,
        };
    }
}
