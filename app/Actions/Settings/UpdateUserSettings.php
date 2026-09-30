<?php

declare(strict_types=1);

namespace App\Actions\Settings;

use App\Enums\ContextTag;
use App\Enums\NotificationFrequency;
use App\Enums\ReviewMode;
use App\Models\User;
use App\Models\UserSetting;

final class UpdateUserSettings
{
    public function __construct(
        private readonly GetUserSettings $getUserSettings = new GetUserSettings,
    ) {}

    public function handle(
        User $user,
        NotificationFrequency $notificationFrequency,
        ?int $newItemCapOverride,
        ?ContextTag $contextEmphasis,
        ReviewMode $reviewMode,
    ): UserSetting {
        $settings = $this->getUserSettings->handle($user);

        $settings->forceFill([
            'notification_frequency' => $notificationFrequency,
            'new_item_cap_override' => $newItemCapOverride,
            'context_emphasis' => $contextEmphasis,
            'review_mode' => $reviewMode,
        ])->save();

        return $settings;
    }
}
