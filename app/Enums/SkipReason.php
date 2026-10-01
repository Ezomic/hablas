<?php

declare(strict_types=1);

namespace App\Enums;

enum SkipReason: string
{
    case Chosen = 'chosen';
    case Paused = 'paused';
    case Unsupported = 'unsupported';
    case Offline = 'offline';
}
