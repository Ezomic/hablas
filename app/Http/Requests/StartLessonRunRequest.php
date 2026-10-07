<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\LessonRunKind;
use App\Enums\Skill;
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
            'skill' => ['nullable', Rule::in(array_map(fn (Skill $skill): string => $skill->value, [Skill::Reading, Skill::Listening, Skill::Speaking, Skill::Writing]))],
            'kind' => ['nullable', Rule::in([LessonRunKind::Lesson->value, LessonRunKind::TestOut->value, LessonRunKind::Practice->value, LessonRunKind::Retake->value, LessonRunKind::SkillTest->value])],
        ];
    }

    public function skill(): ?Skill
    {
        return Skill::tryFrom($this->string('skill')->toString());
    }

    public function kind(): LessonRunKind
    {
        return LessonRunKind::tryFrom($this->string('kind')->toString()) ?? LessonRunKind::Lesson;
    }
}
