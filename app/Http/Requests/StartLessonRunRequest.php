<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\LessonRunKind;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StartLessonRunRequest extends FormRequest
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
            'kind' => ['nullable', Rule::in([LessonRunKind::Lesson->value, LessonRunKind::TestOut->value])],
        ];
    }

    public function kind(): LessonRunKind
    {
        return $this->string('kind')->toString() === LessonRunKind::TestOut->value ? LessonRunKind::TestOut : LessonRunKind::Lesson;
    }
}
