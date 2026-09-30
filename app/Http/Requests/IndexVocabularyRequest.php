<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\VocabularySort;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class IndexVocabularyRequest extends FormRequest
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
            'q' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', Rule::enum(VocabularySort::class)],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function searchTerm(): string
    {
        return $this->string('q')->trim()->toString();
    }

    public function vocabularySort(): VocabularySort
    {
        return VocabularySort::tryFrom($this->string('sort')->toString()) ?? VocabularySort::Recent;
    }

    public function pageNumber(): int
    {
        return max(1, $this->integer('page', 1));
    }
}
