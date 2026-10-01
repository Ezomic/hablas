<?php

declare(strict_types=1);

namespace App\Enums;

enum LessonRunStatus: string
{
    case InProgress = 'in_progress';
    case Completed = 'completed';
}
