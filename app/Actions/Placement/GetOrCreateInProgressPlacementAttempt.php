<?php

namespace App\Actions\Placement;

use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GetOrCreateInProgressPlacementAttempt
{
    public function handle(User $user, Language $language): PlacementTestAttempt
    {
        return DB::transaction(function () use ($user, $language): PlacementTestAttempt {
            $attempt = PlacementTestAttempt::query()
                ->where('user_id', $user->id)
                ->where('language_id', $language->id)
                ->whereNull('completed_at')
                ->lockForUpdate()
                ->first();

            if ($attempt !== null) {
                return $attempt;
            }

            return PlacementTestAttempt::query()->create([
                'user_id' => $user->id,
                'language_id' => $language->id,
                'started_at' => now(),
            ]);
        });
    }
}
