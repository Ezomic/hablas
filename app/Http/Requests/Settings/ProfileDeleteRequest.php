<?php

namespace App\Http\Requests\Settings;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileDeleteRequest extends FormRequest
{
    /**
     * Deleting the account is gated by the password.confirm middleware, which
     * re-authenticates with an emailed code, so there is nothing to validate
     * on the request body itself.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }
}
