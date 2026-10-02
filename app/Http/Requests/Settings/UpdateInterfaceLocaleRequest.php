<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings;

use App\Actions\Settings\SupportedInterfaceLocales;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateInterfaceLocaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'interface_locale' => ['required', 'string', Rule::in((new SupportedInterfaceLocales)->handle())],
        ];
    }
}
