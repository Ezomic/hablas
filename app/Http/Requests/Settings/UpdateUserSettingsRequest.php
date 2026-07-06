<?php

namespace App\Http\Requests\Settings;

use App\Enums\ContextTag;
use App\Enums\NotificationFrequency;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserSettingsRequest extends FormRequest
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
            'notification_frequency' => ['required', Rule::enum(NotificationFrequency::class)],
            'new_item_cap_override' => ['nullable', 'integer', 'min:0', 'max:100'],
            'context_emphasis' => ['nullable', Rule::enum(ContextTag::class)],
        ];
    }
}
