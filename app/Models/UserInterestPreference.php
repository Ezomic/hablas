<?php

namespace App\Models;

use App\Enums\InterestTag;
use Database\Factories\UserInterestPreferenceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property InterestTag $interest_tag
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['user_id', 'interest_tag'])]
class UserInterestPreference extends Model
{
    /** @use HasFactory<UserInterestPreferenceFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'interest_tag' => InterestTag::class,
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
