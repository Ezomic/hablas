import type { AnswerRecord, SkipReason } from '@/types/lesson';

// The pure half of the player: which steps are still to do, in what order and
// in what mode, and when a run is settled. The server stores the plan and the
// answers and decides settling itself with the same rule (SettleLessonRun), so
// tests/Fixtures/Lessons/settle-cases.json runs through both.

export interface QueueExercise {
    id: number;
    format: string;
    substituteId: number | null;
    substituteFormat?: string | null;
}

export type StepMode = 'normal' | 'hint' | 'study';

export interface QueueStep {
    exerciseId: number;
    // The exercise actually shown: the original, or its substitute once the
    // original was skipped.
    shownId: number;
    format: string;
    attempt: number;
    mode: StepMode;
    // A listening or speaking exercise the device cannot play: it is skipped
    // for its substitute before anything is shown.
    skip: boolean;
}

export interface QueueOptions {
    // Check-kind runs give no feedback, so nothing returns.
    check: boolean;
    // Families the learner or the device cannot play now, such as 'listening'
    // while it is paused or 'speaking' in a browser that cannot recognise it.
    skipFamilies?: string[];
    // Single exercises that cannot be played, such as a dictation with no
    // clip to hear it from.
    skipExerciseIds?: number[];
}

const RETURN_AFTER = 3;

export function familyOf(format: string): string | null {
    if (format.startsWith('teach_')) {
        return null;
    }

    if (format.startsWith('listen_')) {
        return 'listening';
    }

    if (format.startsWith('speak_')) {
        return 'speaking';
    }

    if (
        [
            'type_word',
            'type_gap',
            'build_sentence',
            'translate_sentence',
            'transform_sentence',
            'write_guided',
        ].includes(format)
    ) {
        return 'writing';
    }

    return 'choice';
}

export function isSkippable(format: string): boolean {
    const family = familyOf(format);

    return family === 'listening' || family === 'speaking';
}

export function answerRecord(input: {
    step?: string;
    exerciseId: number;
    correct?: boolean | null;
    self?: boolean;
    flagged?: boolean;
    skipped?: boolean;
    skipReason?: SkipReason | null;
    hinted?: boolean;
}): AnswerRecord {
    const correct = input.correct ?? null;
    const flagged = input.flagged ?? false;

    return {
        step: input.step ?? '',
        exerciseId: input.exerciseId,
        hinted: input.hinted ?? false,
        skipped: input.skipped ?? false,
        skipReason: input.skipReason ?? null,
        correct,
        flagged,
        settled:
            !input.skipped &&
            (correct === true || input.self === true || flagged),
    };
}

function settles(answers: AnswerRecord[], check: boolean): boolean {
    return answers.some(
        (answer) => !answer.skipped && (check || answer.settled),
    );
}

function groupByExercise(answers: AnswerRecord[]): Map<number, AnswerRecord[]> {
    const grouped = new Map<number, AnswerRecord[]>();

    for (const answer of answers) {
        grouped.set(answer.exerciseId, [
            ...(grouped.get(answer.exerciseId) ?? []),
            answer,
        ]);
    }

    return grouped;
}

/**
 * Whether one exercise of the plan is settled: a right, self-checked or
 * flagged answer (any answer in a check), or a skip whose substitute is
 * settled. A skip only counts for an exercise that may be skipped and has a
 * substitute.
 */
export function isExerciseSettled(
    exercise: QueueExercise,
    grouped: Map<number, AnswerRecord[]>,
    check: boolean,
): boolean {
    const own = grouped.get(exercise.id) ?? [];

    if (own.length === 0) {
        return false;
    }

    if (settles(own, check)) {
        return true;
    }

    if (
        !own.some((answer) => answer.skipped) ||
        !isSkippable(exercise.format) ||
        exercise.substituteId === null
    ) {
        return false;
    }

    return settles(grouped.get(exercise.substituteId) ?? [], check);
}

export function isSettled(
    plan: QueueExercise[],
    answers: AnswerRecord[],
    check: boolean,
): boolean {
    if (plan.length === 0) {
        return false;
    }

    const grouped = groupByExercise(answers);

    return plan.every((exercise) =>
        isExerciseSettled(exercise, grouped, check),
    );
}

export function progress(
    plan: QueueExercise[],
    answers: AnswerRecord[],
    check: boolean,
): { settled: number; total: number } {
    const grouped = groupByExercise(answers);

    return {
        settled: plan.filter((exercise) =>
            isExerciseSettled(exercise, grouped, check),
        ).length,
        total: plan.length,
    };
}

interface Pending {
    exercise: QueueExercise;
    tries: number;
    swapped: boolean;
}

function stepOf(item: Pending, options: QueueOptions): QueueStep {
    const { exercise } = item;
    const family = familyOf(exercise.format);
    const skip =
        !item.swapped &&
        family !== null &&
        exercise.substituteId !== null &&
        ((options.skipFamilies ?? []).includes(family) ||
            (options.skipExerciseIds ?? []).includes(exercise.id));
    const useSubstitute = item.swapped && exercise.substituteId !== null;
    const mode: StepMode =
        item.tries >= 3 ? 'study' : item.tries === 2 ? 'hint' : 'normal';

    return {
        exerciseId: exercise.id,
        shownId: useSubstitute
            ? (exercise.substituteId as number)
            : exercise.id,
        format: useSubstitute
            ? (exercise.substituteFormat ?? exercise.format)
            : exercise.format,
        attempt: item.tries + 1,
        mode: options.check ? 'normal' : mode,
        skip,
    };
}

/**
 * Replays the answers so far over the plan and returns the steps still to do.
 * A mistake comes back three steps later, with a hint on its second return and
 * the answer first from its third, so nobody is stuck in a loop. A skip puts
 * the substitute in its place. In a check nothing comes back.
 */
export function buildQueue(
    plan: QueueExercise[],
    answers: AnswerRecord[],
    options: QueueOptions,
): QueueStep[] {
    const queue: Pending[] = plan.map((exercise) => ({
        exercise,
        tries: 0,
        swapped: false,
    }));
    const owner = new Map<number, Pending>();

    for (const item of queue) {
        owner.set(item.exercise.id, item);

        if (item.exercise.substituteId !== null) {
            owner.set(item.exercise.substituteId, item);
        }
    }

    for (const answer of answers) {
        const item = owner.get(answer.exerciseId);

        if (item === undefined || !queue.includes(item)) {
            continue;
        }

        if (answer.skipped) {
            item.swapped = true;

            continue;
        }

        item.tries++;

        const index = queue.indexOf(item);

        queue.splice(index, 1);

        if (options.check || answer.settled) {
            continue;
        }

        queue.splice(Math.min(RETURN_AFTER, queue.length), 0, item);
    }

    return queue.map((item) => stepOf(item, options));
}

/**
 * A stable shuffle for the tiles of one exercise, so a reload shows them in
 * the same order.
 */
export function seededShuffle<T>(items: T[], seed: number): T[] {
    const result = [...items];
    let state = seed >>> 0 || 1;

    for (let index = result.length - 1; index > 0; index--) {
        state = (Math.imul(state, 1664525) + 1013904223) >>> 0;

        const other = state % (index + 1);

        [result[index], result[other]] = [result[other], result[index]];
    }

    return result;
}
