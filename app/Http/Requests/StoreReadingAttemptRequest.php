<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReadingAttemptRequest extends FormRequest
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
            'answers' => ['present', 'array'],
            'answers.*' => ['nullable', 'string'],
        ];
    }

    /**
     * Positional, one entry per question. Anything unanswered comes through as
     * an empty string so the grader counts it wrong rather than shrinking the
     * denominator.
     *
     * @return array<int, string>
     */
    public function answers(): array
    {
        return $this->collect('answers')
            ->map(fn (mixed $answer): string => is_string($answer) ? $answer : '')
            ->values()
            ->all();
    }
}
