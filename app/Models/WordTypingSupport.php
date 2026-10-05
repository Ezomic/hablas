<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $vocabulary_item_id
 * @property int $revealed How many letters of the word are given when the learner types it.
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['user_id', 'vocabulary_item_id', 'revealed'])]
class WordTypingSupport extends Model
{
    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<VocabularyItem, $this> */
    public function vocabularyItem(): BelongsTo
    {
        return $this->belongsTo(VocabularyItem::class);
    }
}
