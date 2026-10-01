<?php

declare(strict_types=1);

namespace App\Enums;

enum LessonState: string
{
    case Locked = 'locked';
    case Available = 'available';
    case OpensTomorrow = 'opens_tomorrow';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Coming = 'coming';
}
