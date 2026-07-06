<?php

namespace App\Actions\Settings;

use App\Enums\NotificationFrequency;
use App\Models\User;
use App\Models\UserSetting;

class GetUserSettings
{
    /**
     * Read-only: uses firstOrNew rather than createOrFirst so callers that
     * only want to inspect defaults (e.g. AdaptiveNewItemCap, the settings
     * page's own edit view) don't persist a row as a side effect of reading
     * one. UpdateUserSettings is the only path that actually saves.
     */
    public function handle(User $user): UserSetting
    {
        return UserSetting::query()->firstOrNew(
            ['user_id' => $user->id],
            ['notification_frequency' => NotificationFrequency::Daily, 'new_item_cap_override' => null, 'context_emphasis' => null],
        );
    }
}
