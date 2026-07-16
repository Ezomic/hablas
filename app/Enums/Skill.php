<?php

namespace App\Enums;

enum Skill: string
{
    case Reading = 'reading';
    case Listening = 'listening';
    case Speaking = 'speaking';
    case Writing = 'writing';

    /**
     * Whether this skill's CEFR level can only be set by the placement test.
     * In Milestone 1 only Writing and Speaking have a live practice mechanism
     * that feeds ReassessSkillLevel; Reading and Listening have none, so their
     * level is fixed at whatever the one-time placement test scored.
     */
    public function isPlacementOnly(): bool
    {
        return match ($this) {
            self::Reading, self::Listening => true,
            self::Speaking, self::Writing => false,
        };
    }
}
