<?php

declare(strict_types=1);

namespace App\Actions\Placement;

use App\Enums\Skill;
use App\Models\Language;
use App\Models\PlacementTestAttempt;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class StartSkillPlacement
{
    public function __construct(
        private readonly DetermineRetakeAvailability $determineRetakeAvailability = new DetermineRetakeAvailability,
    ) {}

    /**
     * Opens a placement attempt for one skill, reusing the full test's
     * staircase. An attempt already in progress is returned instead, so a
     * double submit or a second tab never opens two. Null when the learner has
     * not finished a placement yet: the full test comes first.
     *
     * @throws ValidationException when the skill was placed too recently.
     */
    public function handle(User $user, Language $language, Skill $skill): ?PlacementTestAttempt
    {
        return DB::transaction(function () use ($user, $language, $skill): ?PlacementTestAttempt {
            $attempts = PlacementTestAttempt::query()
                ->where('user_id', $user->id)
                ->where('language_id', $language->id);

            $inProgress = (clone $attempts)->whereNull('completed_at')->lockForUpdate()->first();

            if ($inProgress !== null) {
                return $inProgress;
            }

            if ((clone $attempts)->whereNotNull('completed_at')->doesntExist()) {
                return null;
            }

            $availableOn = $this->determineRetakeAvailability->handle($user, $language)[$skill->value];

            if ($availableOn !== null) {
                $date = CarbonImmutable::parse($availableOn)->format('j F');

                throw ValidationException::withMessages([
                    'skill' => "You can re-take {$skill->value} on {$date}.",
                ]);
            }

            return PlacementTestAttempt::query()->create([
                'user_id' => $user->id,
                'language_id' => $language->id,
                'skill' => $skill,
                'started_at' => now(),
            ]);
        });
    }
}
