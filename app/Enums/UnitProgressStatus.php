<?php

declare(strict_types=1);

namespace App\Enums;

enum UnitProgressStatus: string
{
    case Available = 'available';
    case InProgress = 'in_progress';
    case Completed = 'completed';
}
