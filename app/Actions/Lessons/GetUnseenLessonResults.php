<?php

declare(strict_types=1);

namespace App\Actions\Lessons;

use App\Enums\LessonRunStatus;
use App\Models\Language;
use App\Models\LessonRun;
use App\Models\User;

final class GetUnseenLessonResults
{
    /**
     * Runs that finished while their summary was not on screen, for example
     * a lesson completed offline whose answers synced after the player was
     * closed, so the dashboard can point at the results.
     *
     * @return list<array{runId: int, unitTitle: string, lessonTitle: string, isCheck: bool}>
     */
    public function handle(User $user, Language $language): array
    {
        $runs = LessonRun::query()
            ->where('user_id', $user->id)
            ->where('status', LessonRunStatus::Completed)
            ->whereNull('summary_seen_at')
            ->whereHas('lesson.unit', fn ($query) => $query->where('language_id', $language->id))
            ->with('lesson.unit')
            ->orderBy('completed_at')
            ->get();

        $results = [];

        foreach ($runs as $run) {
            $lesson = $run->lesson;
            $unit = $lesson?->unit;

            if ($lesson !== null && $unit !== null) {
                $results[] = ['runId' => $run->id, 'unitTitle' => $unit->title, 'lessonTitle' => $lesson->stage->label(), 'isCheck' => $run->kind->isCheck()];
            }
        }

        return $results;
    }
}
