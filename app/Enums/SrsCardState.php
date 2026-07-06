<?php

namespace App\Enums;

enum SrsCardState: string
{
    case New = 'new';
    case Learning = 'learning';
    case Review = 'review';
    case Relearning = 'relearning';
}
