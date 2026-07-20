<?php

namespace App\Http\Requests;

use App\Concerns\InteractsWithCurrentUser;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCurrentLanguageRequest extends FormRequest
{
    use InteractsWithCurrentUser;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'language_id' => ['required', 'integer', Rule::exists('user_languages', 'language_id')->where('user_id', $this->currentUser()->id)],
        ];
    }
}
