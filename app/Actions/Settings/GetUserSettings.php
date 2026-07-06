<?php

namespace App\Actions\Settings;

use App\Enums\NotificationFrequency;
use App\Models\User;
use App\Models\UserSetting;

class GetUserSettings
{
    public function handle(User $user): UserSetting
    {
        return UserSetting::query()->createOrFirst(
            ['user_id' => $user->id],
            ['notification_frequency' => NotificationFrequency::Daily, 'new_item_cap_override' => null, 'context_emphasis' => null],
        );
    }
}
