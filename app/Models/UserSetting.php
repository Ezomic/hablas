<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContextTag;
use App\Enums\NotificationFrequency;
use App\Enums\ReviewMode;
use Carbon\CarbonImmutable;
use Database\Factories\UserSettingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property NotificationFrequency $notification_frequency
 * @property int|null $new_item_cap_override
 * @property ContextTag|null $context_emphasis
 * @property ReviewMode $review_mode
 * @property CarbonImmutable|null $last_digest_sent_at
 * @property CarbonImmutable|null $last_due_reminder_sent_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['user_id', 'notification_frequency', 'new_item_cap_override', 'context_emphasis', 'review_mode'])]
class UserSetting extends Model
{
    /** @use HasFactory<UserSettingFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'notification_frequency' => NotificationFrequency::class,
            'context_emphasis' => ContextTag::class,
            'review_mode' => ReviewMode::class,
            'last_digest_sent_at' => 'datetime',
            'last_due_reminder_sent_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
