<?php

namespace App\Actions\Srs;

use App\Enums\SrsCardState;
use App\Models\Language;
use App\Models\SrsCard;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EnrollInSrs
{
    public function handle(User $user, Language $language, Model $cardable): SrsCard
    {
        return SrsCard::query()->firstOrCreate(
            [
                'user_id' => $user->id,
                'cardable_type' => $cardable->getMorphClass(),
                'cardable_id' => $cardable->getKey(),
            ],
            [
                'language_id' => $language->id,
                'state' => SrsCardState::New,
                'stability' => 0,
                'difficulty' => 0,
                'reps' => 0,
                'lapses' => 0,
                'consecutive_lapses' => 0,
                'is_weak_spot' => false,
                'due_at' => now(),
                'last_reviewed_at' => null,
            ],
        );
    }
}
