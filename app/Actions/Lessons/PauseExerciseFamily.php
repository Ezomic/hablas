<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Actions\Settings\GetUserSettings;
use App\Enums\ExerciseFamily;
use App\Models\User;
use Carbon\CarbonImmutable;
use InvalidArgumentException;

final class PauseExerciseFamily
{
    public function __construct(
        private readonly GetUserSettings $getUserSettings = new GetUserSettings,
    ) {}

    /**
     * Pauses listening or speaking for the given minutes, across lessons and
     * devices, or turns it back on when the minutes are zero.
     */
    public function handle(User $user, ExerciseFamily $family, int $minutes): ?CarbonImmutable
    {
        $column = match ($family) {
            ExerciseFamily::Listening => 'listening_paused_until',
            ExerciseFamily::Speaking => 'speaking_paused_until',
            default => throw new InvalidArgumentException("{$family->value} exercises cannot be paused."),
        };

        $until = $minutes > 0 ? CarbonImmutable::now()->addMinutes($minutes) : null;

        $this->getUserSettings->handle($user)->forceFill([$column => $until])->save();

        return $until;
    }
}
