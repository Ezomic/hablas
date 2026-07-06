<?php

namespace App\Enums;

enum UnitProgressStatus: string
{
    case Available = 'available';
    case InProgress = 'in_progress';
    case Completed = 'completed';
}
