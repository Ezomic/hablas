<?php

namespace App\Http\Requests;

use App\Enums\ErrorTagCategory;
use App\Enums\SrsRating;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSrsReviewRequest extends FormRequest
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
            'rating' => ['required', Rule::enum(SrsRating::class)],
            'error_tag_category' => ['nullable', Rule::enum(ErrorTagCategory::class)],
        ];
    }

    public function rating(): SrsRating
    {
        return SrsRating::from($this->string('rating')->toString());
    }

    /**
     * Only a miss carries a mistake category: a Hard or better answer that
     * arrived with one would otherwise skew the progress snapshot's
     * most-frequent-mistake counts with reviews the user got right.
     */
    public function errorTagCategory(): ?ErrorTagCategory
    {
        if ($this->rating() !== SrsRating::Again) {
            return null;
        }

        $category = $this->string('error_tag_category')->toString();

        return $category === '' ? null : ErrorTagCategory::from($category);
    }
}
