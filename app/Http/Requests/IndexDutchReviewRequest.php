<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class IndexDutchReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'language' => ['nullable', 'string', 'max:5'],
            'unit' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function languageCode(): ?string
    {
        return $this->filled('language') ? $this->string('language')->toString() : null;
    }

    public function unitSlug(): ?string
    {
        return $this->filled('unit') ? $this->string('unit')->toString() : null;
    }
}
