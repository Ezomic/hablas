import { openDB } from 'idb';
import type { DBSchema, IDBPDatabase } from 'idb';
import type { JournalAnswer } from '@/types/lesson';

export interface PendingSubmission {
    id: number;
    userId: number;
    url: string;
    body: string;
    queuedAt: number;
}

interface HablasOfflineDb extends DBSchema {
    pendingSubmissions: {
        key: number;
        value: PendingSubmission;
        indexes: { url: string };
    };
    lessonAnswers: {
        key: string;
        value: JournalAnswer;
        indexes: { runId: number };
    };
}

let dbPromise: Promise<IDBPDatabase<HablasOfflineDb>> | null = null;

function getDb(): Promise<IDBPDatabase<HablasOfflineDb>> {
    dbPromise ??= openDB<HablasOfflineDb>('hablas-offline', 3, {
        upgrade(db, oldVersion, _newVersion, transaction) {
            if (oldVersion < 1) {
                db.createObjectStore('pendingSubmissions', {
                    keyPath: 'id',
                    autoIncrement: true,
                });
            }

            // Not unique: a version 1 queue can already hold two rows for one
            // url, and a unique index over those would abort the upgrade.
            if (oldVersion < 2) {
                transaction
                    .objectStore('pendingSubmissions')
                    .createIndex('url', 'url');
            }

            if (oldVersion < 3) {
                db.createObjectStore('lessonAnswers', {
                    keyPath: 'step',
                }).createIndex('runId', 'runId');
            }
        },
    });

    return dbPromise;
}

/**
 * Each queued url is one exercise's or card's attempt endpoint, so the same
 * user queuing it again replaces their earlier answer in its original place in
 * the queue rather than replaying both and recording the attempt twice.
 */
export async function queuePendingSubmission(
    userId: number,
    url: string,
    body: string,
): Promise<void> {
    const db = await getDb();
    const transaction = db.transaction('pendingSubmissions', 'readwrite');
    const existing = (await transaction.store.index('url').getAll(url)).find(
        (submission) => submission.userId === userId,
    );

    await transaction.store.put({
        ...(existing ? { id: existing.id } : {}),
        userId,
        url,
        body,
        queuedAt: Date.now(),
    } as PendingSubmission);
    await transaction.done;
}

/** Returns a user's queued submissions oldest-first, so replay preserves order. */
export async function getPendingSubmissions(
    userId: number,
    limit?: number,
): Promise<PendingSubmission[]> {
    const db = await getDb();
    const submissions = await db.getAll('pendingSubmissions');

    return submissions
        .filter((submission) => submission.userId === userId)
        .slice(0, limit);
}

export async function countPendingSubmissions(userId: number): Promise<number> {
    return (await getPendingSubmissions(userId)).length;
}

/**
 * Drops everything queued by anyone but this user, including rows from
 * before submissions carried a user, whose owner is unknown.
 */
export async function removeOtherUsersSubmissions(
    userId: number,
): Promise<void> {
    const db = await getDb();
    const transaction = db.transaction('pendingSubmissions', 'readwrite');

    for await (const cursor of transaction.store) {
        if (cursor.value.userId !== userId) {
            await cursor.delete();
        }
    }

    await transaction.done;
}

/**
 * Removes a submission once replay has sent it, unless its url was queued
 * again with a different answer while the request was in flight. That answer
 * took over the same row and hasn't been sent yet. Returns whether the row
 * was removed.
 */
export async function removeSentSubmission(
    sent: PendingSubmission,
): Promise<boolean> {
    const db = await getDb();
    const transaction = db.transaction('pendingSubmissions', 'readwrite');
    const current = await transaction.store.get(sent.id);
    const unchanged = current?.body === sent.body;

    if (unchanged) {
        await transaction.store.delete(sent.id);
    }

    await transaction.done;

    return unchanged;
}

export async function clearPendingSubmissions(): Promise<void> {
    const db = await getDb();

    await db.clear('pendingSubmissions');
}

/**
 * The device's own record of a lesson answer, keyed by its step, written
 * before the answer is sent or queued. A reload offline then loses nothing,
 * including answers sent online after the page was cached.
 */
export async function putLessonAnswer(answer: JournalAnswer): Promise<void> {
    const db = await getDb();

    await db.put('lessonAnswers', answer);
}

export async function getLessonAnswers(
    userId: number,
    runId: number,
): Promise<JournalAnswer[]> {
    const db = await getDb();
    const answers = await db.getAllFromIndex('lessonAnswers', 'runId', runId);

    return answers
        .filter((answer) => answer.userId === userId)
        .sort((first, second) => first.answeredAt - second.answeredAt);
}

export async function deleteLessonAnswers(
    userId: number,
    runId: number,
): Promise<void> {
    const db = await getDb();
    const transaction = db.transaction('lessonAnswers', 'readwrite');

    for (const answer of await transaction.store.index('runId').getAll(runId)) {
        if (answer.userId === userId) {
            await transaction.store.delete(answer.step);
        }
    }

    await transaction.done;
}

export async function removeOtherUsersLessonAnswers(
    userId: number,
): Promise<void> {
    const db = await getDb();
    const transaction = db.transaction('lessonAnswers', 'readwrite');

    for await (const cursor of transaction.store) {
        if (cursor.value.userId !== userId) {
            await cursor.delete();
        }
    }

    await transaction.done;
}

export async function clearLessonAnswers(): Promise<void> {
    const db = await getDb();

    await db.clear('lessonAnswers');
}
