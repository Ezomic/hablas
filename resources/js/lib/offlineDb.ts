import { openDB } from 'idb';
import type { DBSchema, IDBPDatabase } from 'idb';

export interface PendingSubmission {
    id: number;
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
}

let dbPromise: Promise<IDBPDatabase<HablasOfflineDb>> | null = null;

function getDb(): Promise<IDBPDatabase<HablasOfflineDb>> {
    dbPromise ??= openDB<HablasOfflineDb>('hablas-offline', 2, {
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
        },
    });

    return dbPromise;
}

/**
 * Each queued url is one exercise's or card's attempt endpoint, so queuing it
 * again replaces the earlier answer in its original place in the queue rather
 * than replaying both and recording the attempt twice.
 */
export async function queuePendingSubmission(
    url: string,
    body: string,
): Promise<void> {
    const db = await getDb();
    const transaction = db.transaction('pendingSubmissions', 'readwrite');
    const existing = await transaction.store.index('url').get(url);

    await transaction.store.put({
        ...(existing ? { id: existing.id } : {}),
        url,
        body,
        queuedAt: Date.now(),
    } as PendingSubmission);
    await transaction.done;
}

/** Returns queued submissions oldest-first, so replay preserves order. */
export async function getPendingSubmissions(
    limit?: number,
): Promise<PendingSubmission[]> {
    const db = await getDb();

    return db.getAll('pendingSubmissions', undefined, limit);
}

export async function countPendingSubmissions(): Promise<number> {
    const db = await getDb();

    return db.count('pendingSubmissions');
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
