import { describe, expect, it } from 'vitest';
import {
    answerRecord,
    buildQueue,
    isSettled,
    progress,
    seededShuffle,
} from '@/lib/lessonQueue';
import type { QueueExercise } from '@/lib/lessonQueue';
import cases from '../../../tests/Fixtures/Lessons/settle-cases.json';

interface Case {
    name: string;
    kind: 'lesson' | 'check';
    exercises: { id: string; format?: string; substituteFor?: string }[];
    plan: string[];
    answers: {
        exercise: string;
        correct?: boolean;
        self?: boolean;
        flagged?: boolean;
        skipped?: boolean;
    }[];
    settled: boolean;
}

describe('the settling rule, shared with the server', () => {
    it.each((cases as { cases: Case[] }).cases)('$name', (testCase) => {
        const ids = new Map(
            testCase.exercises.map((exercise, index) => [
                exercise.id,
                index + 1,
            ]),
        );
        const exercises = new Map(
            testCase.exercises.map((exercise) => [exercise.id, exercise]),
        );
        const substituteOf = (id: string) =>
            testCase.exercises.find(
                (candidate) => candidate.substituteFor === id,
            );
        const plan: QueueExercise[] = testCase.plan.map((id) => {
            const substitute = substituteOf(id);

            return {
                id: ids.get(id) as number,
                format: exercises.get(id)?.format ?? 'choose_meaning',
                substituteId: substitute
                    ? (ids.get(substitute.id) as number)
                    : null,
            };
        });
        const answers = testCase.answers.map((answer) =>
            answerRecord({
                exerciseId: ids.get(answer.exercise) as number,
                correct: answer.correct,
                self: answer.self,
                flagged: answer.flagged,
                skipped: answer.skipped,
            }),
        );

        expect(isSettled(plan, answers, testCase.kind === 'check')).toBe(
            testCase.settled,
        );
    });
});

const plan: QueueExercise[] = [1, 2, 3, 4, 5].map((id) => ({
    id,
    format: 'choose_meaning',
    substituteId: null,
}));

const right = (exerciseId: number) =>
    answerRecord({ exerciseId, correct: true });
const wrong = (exerciseId: number) =>
    answerRecord({ exerciseId, correct: false });

const lesson = { check: false };

describe('the queue', () => {
    it('plays the plan in order', () => {
        expect(
            buildQueue(plan, [], lesson).map((step) => step.exerciseId),
        ).toEqual([1, 2, 3, 4, 5]);
    });

    it('drops an exercise answered right', () => {
        expect(
            buildQueue(plan, [right(1), right(2)], lesson).map(
                (step) => step.exerciseId,
            ),
        ).toEqual([3, 4, 5]);
    });

    it('brings a mistake back three steps later', () => {
        expect(
            buildQueue(plan, [wrong(1)], lesson).map((step) => step.exerciseId),
        ).toEqual([2, 3, 4, 1, 5]);
    });

    it('brings a mistake back at the end when fewer than three steps remain', () => {
        expect(
            buildQueue(
                plan,
                [right(1), right(2), right(3), wrong(4)],
                lesson,
            ).map((step) => step.exerciseId),
        ).toEqual([5, 4]);
    });

    it('gives a hint on its second return and shows the answer first from its third', () => {
        const modes = (answers: ReturnType<typeof wrong>[]) =>
            buildQueue(plan.slice(0, 1), answers, lesson).map((step) => [
                step.mode,
                step.attempt,
            ]);

        expect(modes([])).toEqual([['normal', 1]]);
        expect(modes([wrong(1)])).toEqual([['normal', 2]]);
        expect(modes([wrong(1), wrong(1)])).toEqual([['hint', 3]]);
        expect(modes([wrong(1), wrong(1), wrong(1)])).toEqual([['study', 4]]);
    });

    it('never ends while a mistake is unanswered, and ends once it is answered right', () => {
        const answers = [wrong(1), wrong(1), wrong(1), wrong(1)];

        expect(buildQueue(plan.slice(0, 1), answers, lesson)).toHaveLength(1);
        expect(
            buildQueue(plan.slice(0, 1), [...answers, right(1)], lesson),
        ).toHaveLength(0);
    });

    it('settles a mistake with a flag, so it does not come back', () => {
        const flagged = answerRecord({
            exerciseId: 1,
            correct: false,
            flagged: true,
        });

        expect(
            buildQueue(plan, [flagged], lesson).map((step) => step.exerciseId),
        ).toEqual([2, 3, 4, 5]);
    });

    it('settles a mistake with a self-check, so it does not come back', () => {
        const self = answerRecord({
            exerciseId: 1,
            correct: false,
            self: true,
        });

        expect(
            buildQueue(plan, [self], lesson).map((step) => step.exerciseId),
        ).toEqual([2, 3, 4, 5]);
    });

    it('returns nothing in a check, whatever the answer', () => {
        const check = { check: true };

        expect(
            buildQueue(plan, [wrong(1), wrong(2)], check).map(
                (step) => step.exerciseId,
            ),
        ).toEqual([3, 4, 5]);
        expect(buildQueue(plan.slice(0, 1), [wrong(1)], check)).toEqual([]);
    });

    it('rebuilding from the stored answers gives the same steps as the live session', () => {
        const live: ReturnType<typeof wrong>[] = [];
        let queue = buildQueue(plan, live, lesson);

        for (const verdict of [false, true, true, false, true, true, true]) {
            const head = queue[0];

            live.push(
                verdict ? right(head.exerciseId) : wrong(head.exerciseId),
            );
            queue = buildQueue(plan, live, lesson);

            expect(buildQueue(plan, [...live], lesson)).toEqual(queue);
        }
    });

    it('puts the substitute in place of a skipped exercise', () => {
        const withSubstitute: QueueExercise[] = [
            {
                id: 1,
                format: 'listen_choose',
                substituteId: 11,
                substituteFormat: 'choose_meaning',
            },
            { id: 2, format: 'choose_meaning', substituteId: null },
        ];
        const skipped = answerRecord({ exerciseId: 1, skipped: true });
        const queue = buildQueue(withSubstitute, [skipped], lesson);

        expect(queue[0]).toMatchObject({
            exerciseId: 1,
            shownId: 11,
            format: 'choose_meaning',
            skip: false,
        });

        expect(
            buildQueue(
                withSubstitute,
                [skipped, answerRecord({ exerciseId: 11, correct: true })],
                lesson,
            ).map((step) => step.exerciseId),
        ).toEqual([2]);
    });

    it('marks a step the device cannot play to be skipped', () => {
        const withSubstitute: QueueExercise[] = [
            {
                id: 1,
                format: 'speak_repeat',
                substituteId: 11,
                substituteFormat: 'type_word',
            },
            { id: 2, format: 'listen_choose', substituteId: null },
        ];
        const queue = buildQueue(withSubstitute, [], {
            check: false,
            skipFamilies: ['speaking', 'listening'],
        });

        expect(queue.map((step) => step.skip)).toEqual([true, false]);
    });

    it('marks one exercise to be skipped, and only one that has a substitute', () => {
        const withSubstitute: QueueExercise[] = [
            {
                id: 1,
                format: 'listen_type',
                substituteId: 11,
                substituteFormat: 'translate_sentence',
            },
            { id: 2, format: 'listen_choose', substituteId: null },
            { id: 3, format: 'type_word', substituteId: null },
        ];
        const queue = buildQueue(withSubstitute, [], {
            check: false,
            skipExerciseIds: [1, 2, 3],
        });

        expect(queue.map((step) => step.skip)).toEqual([true, false, false]);
    });

    it('shows the substitute of a skipped exercise and keeps the reason on the record', () => {
        const withSubstitute: QueueExercise[] = [
            {
                id: 1,
                format: 'speak_repeat',
                substituteId: 11,
                substituteFormat: 'type_word',
            },
        ];
        const skipped = answerRecord({
            exerciseId: 1,
            skipped: true,
            skipReason: 'paused',
        });
        const [step] = buildQueue(withSubstitute, [skipped], {
            check: false,
            skipFamilies: ['speaking'],
        });

        expect(skipped.skipReason).toBe('paused');
        expect(answerRecord({ exerciseId: 1 }).skipReason).toBeNull();
        expect([step.shownId, step.format, step.skip]).toEqual([
            11,
            'type_word',
            false,
        ]);
    });

    it('measures progress as exercises settled over exercises in the plan, never going backwards', () => {
        const sequence = [wrong(1), right(1), right(2), wrong(3), right(3)];
        const settled: number[] = [];

        for (let count = 0; count <= sequence.length; count++) {
            settled.push(
                progress(plan, sequence.slice(0, count), false).settled,
            );
        }

        expect(settled).toEqual([0, 0, 1, 2, 2, 3]);
        expect(settled).toEqual([...settled].sort((a, b) => a - b));
        expect(progress(plan, [], false).total).toBe(5);
    });
});

describe('seededShuffle', () => {
    it('is stable for a seed and keeps every item', () => {
        const items = [1, 2, 3, 4, 5, 6];

        expect(seededShuffle(items, 7)).toEqual(seededShuffle(items, 7));
        expect([...seededShuffle(items, 7)].sort()).toEqual(items);
        expect(seededShuffle(items, 7)).not.toEqual(seededShuffle(items, 8));
    });
});
