<?php

namespace App\Enums;

enum EmailCodePurpose: string
{
    /**
     * Proves control of the inbox in place of a password on POST /login.
     */
    case Login = 'login';

    /**
     * Re-authenticates an already logged-in user before a sensitive action
     * (managing passkeys, enabling 2FA, deleting the account). Kept separate
     * from Login so a code minted for one can never satisfy the other.
     */
    case Confirm = 'confirm';
}
