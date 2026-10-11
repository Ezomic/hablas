<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A reader's proposed correction of a Dutch word meaning. Never applied
 * automatically: the meanings are re-seeded from the data file on every deploy.
 *
 * @property int $id
 * @property int $user_id
 * @property int $vocabulary_item_id
 * @property string|null $suggestion
 * @property string|null $note
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['user_id', 'vocabulary_item_id', 'suggestion', 'note'])]
class DutchMeaningSuggestion extends Model
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
