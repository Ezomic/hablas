<?php

declare(strict_types=1);

namespace App\Enums;

enum ExerciseFamily: string
{
    case Choice = 'choice';
    case Writing = 'writing';
    case Listening = 'listening';
    case Speaking = 'speaking';

    public function skill(): Skill
    {
        return match ($this) {
            self::Choice => Skill::Reading,
            self::Writing => Skill::Writing,
            self::Listening => Skill::Listening,
            self::Speaking => Skill::Speaking,
        };
    }
}
