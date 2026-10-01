import 'fake-indexeddb/auto';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import type { ServerAnswer } from '@/types/lesson';
import type * as Journal from './lessonJournal';
import type { JournalEntry } from './lessonJournal';

let journal: typeof Journal;

function entry(
    step: string,
    overrides: Partial<JournalEntry> = {},
): JournalEntry {
    return {
        step,
        exerciseId: 1,
        hinted: false,
        skipped: false,
        correct: true,
        settled: true,
        flagged: false,
        ...overrides,
    };
}

function serverAnswer(
    step: string,
    overrides: Partial<ServerAnswer> = {},
): ServerAnswer {
    return {
        step,
        exerciseId: 1,
        attempt: 1,
        hinted: false,
        skipped: false,
        correct: true,
        flagged: false,
        settled: true,
        ...overrides,
    };
}

beforeEach(async () => {
    indexedDB = new IDBFactory();
    vi.resetModules();
    journal = await import('./lessonJournal');
});

describe('the answer journal', () => {
    it("keeps a run's answers for one user, oldest first", async () => {
        await journal.recordJournal(1, 7, entry('a'));
        await journal.recordJournal(1, 7, entry('b'));
        await journal.recordJournal(1, 8, entry('other-run'));
        await journal.recordJournal(2, 7, entry('other-user'));

        expect(
            (await journal.readJournal(1, 7)).map((answer) => answer.step),
        ).toEqual(['a', 'b']);
    });

    it('forgets a run once the server has completed it', async () => {
        await journal.recordJournal(1, 7, entry('a'));
        await journal.recordJournal(1, 8, entry('b'));
        await journal.recordJournal(2, 7, entry('c'));

        await journal.forgetJournal(1, 7);

        expect(await journal.readJournal(1, 7)).toEqual([]);
        expect(await journal.readJournal(1, 8)).toHaveLength(1);
        expect(await journal.readJournal(2, 7)).toHaveLength(1);
    });

    it('is cleared with everything else when someone signs out, and for other users on a claim', async () => {
        const db = await import('./offlineDb');

        await journal.recordJournal(1, 7, entry('a'));
        await journal.recordJournal(2, 7, entry('b'));
        await db.removeOtherUsersLessonAnswers(1);

        expect(await journal.readJournal(2, 7)).toEqual([]);
        expect(await journal.readJournal(1, 7)).toHaveLength(1);

        await db.clearLessonAnswers();

        expect(await journal.readJournal(1, 7)).toEqual([]);
    });
});

describe('merging the journal into the answers in the props', () => {
    it("adds the answers the server does not hold yet, after the server's", async () => {
        await journal.recordJournal(1, 7, entry('known'));
        await journal.recordJournal(1, 7, entry('new', { exerciseId: 2 }));

        const merged = journal.mergeAnswers(
            [serverAnswer('known')],
            await journal.readJournal(1, 7),
        );

        expect(merged.map((answer) => answer.step)).toEqual(['known', 'new']);
    });

    it("lets the server's grade win over the device's for the same step", async () => {
        await journal.recordJournal(
            1,
            7,
            entry('a', { correct: true, settled: true }),
        );

        const merged = journal.mergeAnswers(
            [serverAnswer('a', { correct: false, settled: false })],
            await journal.readJournal(1, 7),
        );

        expect(merged).toHaveLength(1);
        expect(merged[0]).toMatchObject({ correct: false, settled: false });
    });

    it('keeps a flag made on the device that the server has not seen yet', async () => {
        await journal.recordJournal(
            1,
            7,
            entry('a', { correct: false, settled: true, flagged: true }),
        );

        const merged = journal.mergeAnswers(
            [serverAnswer('a', { correct: false, settled: false })],
            await journal.readJournal(1, 7),
        );

        expect(merged[0]).toMatchObject({ flagged: true, settled: true });
    });

    it("returns the server's answers alone when the journal is empty", () => {
        expect(journal.mergeAnswers([serverAnswer('a')], [])).toHaveLength(1);
    });
});
