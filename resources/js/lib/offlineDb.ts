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
    };
}

let dbPromise: Promise<IDBPDatabase<HablasOfflineDb>> | null = null;

function getDb(): Promise<IDBPDatabase<HablasOfflineDb>> {
    dbPromise ??= openDB<HablasOfflineDb>('hablas-offline', 1, {
        upgrade(db) {
            db.createObjectStore('pendingSubmissions', {
                keyPath: 'id',
                autoIncrement: true,
            });
        },
    });

    return dbPromise;
}

export async function queuePendingSubmission(
    url: string,
    body: string,
): Promise<void> {
    const db = await getDb();

    await db.add('pendingSubmissions', {
        url,
        body,
        queuedAt: Date.now(),
    } as PendingSubmission);
}

/** Returns queued submissions oldest-first, so replay preserves order. */
export async function getPendingSubmissions(): Promise<PendingSubmission[]> {
    const db = await getDb();

    return db.getAll('pendingSubmissions');
}

export async function removePendingSubmission(id: number): Promise<void> {
    const db = await getDb();

    await db.delete('pendingSubmissions', id);
}
