<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings;

use App\Concerns\InteractsWithCurrentUser;
use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    use InteractsWithCurrentUser;
    use ProfileValidationRules;

    protected function prepareForValidation(): void
    {
        $email = $this->input('email');

        if (is_string($email)) {
            $this->merge(['email' => $this->normalizeEmail($email)]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->profileRules($this->currentUser()->id);
    }
}
