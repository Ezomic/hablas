<?php

namespace App\Actions\Settings;

use App\Enums\InterestTag;
use App\Models\User;

class UpdateInterestPreferences
{
    /**
     * @param  list<InterestTag>  $interestTags
     */
    public function handle(User $user, array $interestTags): void
    {
        $user->interestPreferences()->delete();

        $user->interestPreferences()->createMany(
            collect($interestTags)->map(fn (InterestTag $tag): array => ['interest_tag' => $tag])->all(),
        );
    }
}
