<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\UserSetting;

/**
 * The name a learner uses in lessons, to introduce themselves: the one set in
 * their learning settings, else the first word of their account name.
 */
final class LearnerName
{
    public function for(User $user): ?string
    {
        $chosen = UserSetting::query()->where('user_id', $user->id)->value('lesson_name');

        if (is_string($chosen) && trim($chosen) !== '') {
            return trim($chosen);
        }

        $first = preg_split('/\s+/', trim($user->name))[0] ?? '';

        return $first === '' ? null : $first;
    }
}
