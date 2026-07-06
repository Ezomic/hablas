<?php

namespace App\Enums;

enum NotificationFrequency: string
{
    case Daily = 'daily';
    case Weekly = 'weekly';
    case Never = 'never';
}
