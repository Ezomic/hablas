<?php

namespace App\Http\Controllers\Settings;

use App\Actions\Settings\GetUserSettings;
use App\Actions\Settings\UpdateUserSettings;
use App\Enums\ContextTag;
use App\Enums\InterestTag;
use App\Enums\NotificationFrequency;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateUserSettingsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LearningController extends Controller
{
    public function edit(Request $request, GetUserSettings $getUserSettings): Response
    {
        $settings = $getUserSettings->handle($request->user());

        return Inertia::render('settings/Learning', [
            'settings' => [
                'notificationFrequency' => $settings->notification_frequency->value,
                'newItemCapOverride' => $settings->new_item_cap_override,
                'contextEmphasis' => $settings->context_emphasis?->value,
            ],
            'interestTags' => $request->user()->interestPreferences()->get()
                ->map(fn ($preference): string => $preference->interest_tag->value),
            'availableInterestTags' => collect(InterestTag::cases())->map(fn (InterestTag $tag): string => $tag->value),
        ]);
    }

    public function update(UpdateUserSettingsRequest $request, UpdateUserSettings $updateUserSettings): RedirectResponse
    {
        $contextEmphasis = $request->validated('context_emphasis');

        $updateUserSettings->handle(
            $request->user(),
            notificationFrequency: NotificationFrequency::from($request->validated('notification_frequency')),
            newItemCapOverride: $request->validated('new_item_cap_override'),
            contextEmphasis: $contextEmphasis === null ? null : ContextTag::from($contextEmphasis),
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Learning settings updated.')]);

        return to_route('learning.edit');
    }
}
