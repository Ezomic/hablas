<?php

namespace App\Http\Requests\Auth;

use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest;

/**
 * Replaces Fortify's LoginRequest, which hard-requires a `password` field.
 * There are no passwords here — POST /login carries a one-time `code` — so
 * without this the request would fail validation before ever reaching the
 * authenticateUsing callback.
 *
 * Bound over the vendor class in FortifyServiceProvider::register().
 */
class LoginCodeRequest extends LoginRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            Fortify::username() => ['required', 'string', 'email'],
            'code' => ['required', 'string'],
            'remember' => ['sometimes'],
        ];
    }
}
