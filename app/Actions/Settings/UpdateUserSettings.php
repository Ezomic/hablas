<?php

namespace App\Actions\Settings;

use App\Enums\ContextTag;
use App\Enums\NotificationFrequency;
use App\Models\User;
use App\Models\UserSetting;

class UpdateUserSettings
{
    public function __construct(
        private readonly GetUserSettings $getUserSettings = new GetUserSettings,
    ) {}

    public function handle(
        User $user,
        NotificationFrequency $notificationFrequency,
        ?int $newItemCapOverride,
        ?ContextTag $contextEmphasis,
    ): UserSetting {
        $settings = $this->getUserSettings->handle($user);

        $settings->forceFill([
            'notification_frequency' => $notificationFrequency,
            'new_item_cap_override' => $newItemCapOverride,
            'context_emphasis' => $contextEmphasis,
        ])->save();

        return $settings;
    }
}
