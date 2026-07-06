<?php

namespace App\Enums;

enum ErrorTagCategory: string
{
    case WrongGender = 'wrong_gender';
    case SerEstarConfusion = 'ser_estar_confusion';
    case FalseFriend = 'false_friend';
    case WrongTense = 'wrong_tense';
    case PortunolSlip = 'portunol_slip';
    case Other = 'other';
}
