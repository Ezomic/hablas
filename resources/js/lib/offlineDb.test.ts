import 'fake-indexeddb/auto';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import type * as OfflineDb from './offlineDb';

let offlineDb: typeof OfflineDb;

beforeEach(async () => {
    indexedDB = new IDBFactory();
    vi.resetModules();
    offlineDb = await import('./offlineDb');
});

describe('offlineDb', () => {
    it('returns queued submissions oldest-first', async () => {
        await offlineDb.queuePendingSubmission(
            '/writing/1/attempts',
            '{"a":1}',
        );
        await offlineDb.queuePendingSubmission(
            '/writing/2/attempts',
            '{"a":2}',
        );
        await offlineDb.queuePendingSubmission(
            '/writing/3/attempts',
            '{"a":3}',
        );

        const pending = await offlineDb.getPendingSubmissions();

        expect(pending.map((submission) => submission.url)).toEqual([
            '/writing/1/attempts',
            '/writing/2/attempts',
            '/writing/3/attempts',
        ]);
    });

    it('removes a submission by id without disturbing the others', async () => {
        await offlineDb.queuePendingSubmission(
            '/writing/1/attempts',
            '{"a":1}',
        );
        await offlineDb.queuePendingSubmission(
            '/writing/2/attempts',
            '{"a":2}',
        );

        const [first, second] = await offlineDb.getPendingSubmissions();
        await offlineDb.removePendingSubmission(first.id);

        const remaining = await offlineDb.getPendingSubmissions();

        expect(remaining).toEqual([second]);
    });
});
