<?php

declare(strict_types=1);

namespace App\Enums;

enum Skill: string
{
    case Reading = 'reading';
    case Listening = 'listening';
    case Speaking = 'speaking';
    case Writing = 'writing';

    public function label(): string
    {
        return match ($this) {
            self::Reading => __('reading'),
            self::Listening => __('listening'),
            self::Speaking => __('speaking'),
            self::Writing => __('writing'),
        };
    }
}
