<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\SkipReason;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreLessonAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * There is no attempt number: the server derives it from the answers it
     * already holds. answered_at is checked to be a date and then clamped by
     * the action, because an offline device clock drifts.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'exercise_id' => ['required', 'integer'],
            'hinted' => ['nullable', 'boolean'],
            'skipped' => ['nullable', 'boolean'],
            'skip_reason' => [Rule::requiredIf(fn (): bool => $this->boolean('skipped')), 'nullable', Rule::enum(SkipReason::class)],
            'response' => ['nullable', 'array'],
            'response.text' => ['nullable', 'string', 'max:1000'],
            'self_graded_correct' => ['nullable', 'boolean'],
            'answered_at' => ['nullable', 'date'],
        ];
    }

    /**
     * @return array{exercise_id: int, hinted: bool, skipped: bool, skip_reason: string|null, response: array<string, mixed>|null, self_graded_correct: bool|null, answered_at: string|null}
     */
    public function answer(): array
    {
        $response = $this->input('response');
        $selfGraded = $this->input('self_graded_correct');
        $skipReason = $this->string('skip_reason')->toString();
        $answeredAt = $this->string('answered_at')->toString();

        return [
            'exercise_id' => $this->integer('exercise_id'),
            'hinted' => $this->boolean('hinted'),
            'skipped' => $this->boolean('skipped'),
            'skip_reason' => $skipReason === '' ? null : $skipReason,
            'response' => is_array($response) ? array_filter($response, is_string(...), ARRAY_FILTER_USE_KEY) : null,
            'self_graded_correct' => $selfGraded === null ? null : $this->boolean('self_graded_correct'),
            'answered_at' => $answeredAt === '' ? null : $answeredAt,
        ];
    }
}
