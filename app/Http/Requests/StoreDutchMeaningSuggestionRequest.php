<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class StoreDutchMeaningSuggestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'suggestion' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function suggestion(): ?string
    {
        return $this->filled('suggestion') ? $this->string('suggestion')->toString() : null;
    }

    public function note(): ?string
    {
        return $this->filled('note') ? $this->string('note')->toString() : null;
    }
}
