<?php

namespace App\Actions\Fortify;

use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * There is no password: the account is claimed by verifying the email
     * address, and every sign-in afterwards uses a one-time code or a passkey.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, $this->profileRules())->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
        ]);
    }
}
