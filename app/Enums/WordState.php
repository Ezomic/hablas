<?php

declare(strict_types=1);

namespace App\Enums;

enum WordState: string
{
    case New = 'new';
    case Seen = 'seen';
    case Typing = 'typing';
    case Known = 'known';
    case Solid = 'solid';

    public function isKnown(): bool
    {
        return $this === self::Known || $this === self::Solid;
    }
}
