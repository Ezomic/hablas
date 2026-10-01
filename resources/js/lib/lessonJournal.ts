import {
    clearLessonAnswerRequest,
    deleteLessonAnswers,
    getLessonAnswers,
    putLessonAnswer,
} from '@/lib/offlineDb';
import type { AnswerRecord, JournalAnswer, ServerAnswer } from '@/types/lesson';

export type JournalEntry = Omit<
    JournalAnswer,
    'userId' | 'runId' | 'answeredAt'
>;

export function recordJournal(
    userId: number,
    runId: number,
    entry: JournalEntry,
): Promise<void> {
    return putLessonAnswer({
        ...entry,
        userId,
        runId,
        answeredAt: Date.now(),
    });
}

export async function readJournal(
    userId: number,
    runId: number,
): Promise<JournalAnswer[]> {
    return getLessonAnswers(userId, runId);
}

export function markDelivered(userId: number, step: string): Promise<void> {
    return clearLessonAnswerRequest(userId, step);
}

export function forgetJournal(userId: number, runId: number): Promise<void> {
    return deleteLessonAnswers(userId, runId);
}

function toRecord(answer: ServerAnswer | JournalAnswer): AnswerRecord {
    return {
        step: answer.step,
        exerciseId: answer.exerciseId,
        hinted: answer.hinted,
        skipped: answer.skipped,
        correct: answer.correct,
        flagged: answer.flagged,
        settled: answer.settled,
    };
}

/**
 * The answers of a run as the player replays them: the server's, then the
 * device's own for steps the server's copy does not hold yet. A step the
 * server has wins, since its grade is the one that counts, but a flag made on
 * the device stays.
 */
export function mergeAnswers(
    server: ServerAnswer[],
    journal: JournalAnswer[],
): AnswerRecord[] {
    const journalByStep = new Map(
        journal.map((answer) => [answer.step, answer]),
    );
    const known = new Set(server.map((answer) => answer.step));

    const merged = server.map((answer): AnswerRecord => {
        const record = toRecord(answer);
        const local = journalByStep.get(answer.step);

        if (local?.flagged && !record.flagged) {
            return { ...record, flagged: true, settled: true };
        }

        return record;
    });

    for (const answer of journal) {
        if (!known.has(answer.step)) {
            merged.push(toRecord(answer));
        }
    }

    return merged;
}
