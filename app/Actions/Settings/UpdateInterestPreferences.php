<?php

namespace App\Actions\Settings;

use App\Enums\InterestTag;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateInterestPreferences
{
    /**
     * @param  list<InterestTag>  $interestTags
     */
    public function handle(User $user, array $interestTags): void
    {
        DB::transaction(function () use ($user, $interestTags): void {
            $user->interestPreferences()->delete();

            $user->interestPreferences()->createMany(
                collect($interestTags)->map(fn (InterestTag $tag): array => ['interest_tag' => $tag])->all(),
            );
        });
    }
}
