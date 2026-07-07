<?php

namespace Database\Factories;

use App\Enums\NotificationFrequency;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserSetting>
 */
class UserSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'notification_frequency' => NotificationFrequency::Daily,
            'new_item_cap_override' => null,
            'context_emphasis' => null,
            'last_digest_sent_at' => null,
        ];
    }
}
