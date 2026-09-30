<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * What the learner can do with a unit right now. Derived on every request and
 * never stored, unlike UnitProgressStatus, which records what they have done.
 */
enum UnitAvailability: string
{
    case Completed = 'completed';
    case Available = 'available';
    case HeldBack = 'held_back';
    case Locked = 'locked';
}
