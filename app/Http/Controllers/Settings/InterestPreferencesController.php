<?php

namespace App\Http\Controllers\Settings;

use App\Actions\Settings\UpdateInterestPreferences;
use App\Concerns\InteractsWithCurrentUser;
use App\Enums\InterestTag;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateInterestPreferencesRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class InterestPreferencesController extends Controller
{
    use InteractsWithCurrentUser;

    public function update(UpdateInterestPreferencesRequest $request, UpdateInterestPreferences $updateInterestPreferences): RedirectResponse
    {
        $interestTags = array_values($request->collect('interest_tags')
            ->filter(fn (mixed $tag): bool => is_string($tag))
            ->map(fn (string $tag): InterestTag => InterestTag::from($tag))
            ->values()
            ->all());

        $updateInterestPreferences->handle($this->currentUser(), $interestTags);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Interest preferences updated.')]);

        return to_route('learning.edit');
    }
}
