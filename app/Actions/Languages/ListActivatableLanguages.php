<?php

declare(strict_types=1);

namespace App\Actions\Languages;

use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Collection;

final class ListActivatableLanguages
{
    public function __construct(
        private readonly EvaluateLanguageActivationEligibility $evaluate = new EvaluateLanguageActivationEligibility,
    ) {}

    /**
     * @return Collection<int, Language>
     */
    public function handle(User $user): Collection
    {
        return Language::query()
            ->whereIn('code', config()->array('languages.activatable'))
            ->orderBy('name')
            ->get()
            ->filter(fn (Language $language): bool => $this->evaluate->handle($user, $language))
            ->values();
    }
}
