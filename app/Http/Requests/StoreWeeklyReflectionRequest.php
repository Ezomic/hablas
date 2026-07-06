<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWeeklyReflectionRequest extends FormRequest
{
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
            'statement_ids' => ['required', 'array'],
            'statement_ids.*' => ['integer', 'exists:cefr_can_do_statements,id'],
            'can_do_ids' => ['array'],
            'can_do_ids.*' => ['integer', Rule::in($this->input('statement_ids', []))],
        ];
    }
}
