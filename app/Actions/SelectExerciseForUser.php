<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SelectExerciseForUser
{
    /**
     * Prefers an exercise the user hasn't attempted yet, falling back to any
     * exercise once they've done them all — shared by every practice mode
     * (shadowing, writing, scripted prompts) that serves one random exercise
     * per visit. Takes an already-scoped query (e.g. filtered to a language)
     * so this action only owns the attempted/unattempted preference, not
     * model-specific scoping.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return TModel|null
     */
    public function handle(Builder $query, User $user): ?Model
    {
        return (clone $query)
            ->whereDoesntHave('attempts', fn ($attempts) => $attempts->where('user_id', $user->id))
            ->inRandomOrder()
            ->first()
            ?? (clone $query)->inRandomOrder()->first();
    }
}
