import { onMounted, onUnmounted, ref } from 'vue';
import { fetchJson } from '@/lib/http';
import {
    clearPendingSubmissions,
    countPendingSubmissions,
    getPendingSubmissions,
    queuePendingSubmission,
    removeSentSubmission,
} from '@/lib/offlineDb';
import { clearPageCache } from '@/lib/pageCache';

export type SubmitResult =
    { queued: true } | { queued: false; response: Response };

// Module-level so the layout's sync banner and the page that queued an
// attempt read the same numbers.
const pendingCount = ref(0);
const rejectedCount = ref(0);

let replayInFlight: Promise<void> | null = null;

function postJson(url: string, body: string): Promise<Response> {
    return fetchJson(url, 'POST', body);
}

// A server error, an expired session or CSRF token, a timeout or rate
// limiting can all succeed on a later attempt. Any other 4xx fails the same
// way every time, so keeping it would wedge everything queued behind it.
function isRetryable(status: number): boolean {
    return status >= 500 || [401, 408, 419, 429].includes(status);
}

async function refreshPendingCount(): Promise<void> {
    pendingCount.value = await countPendingSubmissions();
}

// Web Locks isn't supported in every browser (or test environment) — fall
// back to running the callback unguarded rather than throwing.
async function withReplayLock(callback: () => Promise<void>): Promise<void> {
    if (!navigator.locks) {
        await callback();

        return;
    }

    await navigator.locks.request('hablas-offline-replay', callback);
}

// Reads the oldest row afresh on every pass instead of working from a
// snapshot: an answer queued while a request is in flight replaces its url's
// row, and that newer answer is what has to be sent.
async function drainQueue(): Promise<void> {
    await refreshPendingCount();

    for (;;) {
        const [submission] = await getPendingSubmissions(1);

        if (!submission) {
            break;
        }

        let response: Response;

        try {
            response = await postJson(submission.url, submission.body);
        } catch {
            // Still offline, so stop here rather than replaying out of order.
            break;
        }

        if (isRetryable(response.status)) {
            break;
        }

        const removed = await removeSentSubmission(submission);

        if (removed && !response.ok) {
            rejectedCount.value++;
        }
    }

    await refreshPendingCount();
}

// The lock guards against two tabs racing to replay the same queued rows; the
// in-flight promise does the same for the several components in one tab that
// mount together.
function replayQueue(): Promise<void> {
    replayInFlight ??= withReplayLock(drainQueue).finally(() => {
        replayInFlight = null;
    });

    return replayInFlight;
}

/**
 * Forgets everything this device holds for the signed-in user: attempts
 * still waiting to sync, which would otherwise replay under whoever signs in
 * next, and the cached pages rendered with their data.
 */
export async function clearOfflineData(): Promise<void> {
    await Promise.all([clearPendingSubmissions(), clearPageCache()]);
    pendingCount.value = 0;
    rejectedCount.value = 0;
}

/**
 * Submits a POST as JSON, falling back to an IndexedDB queue when offline
 * (or when the request fails outright) rather than losing the attempt. The
 * queue is replayed in order whenever the browser comes back online.
 */
export function useOfflineSync() {
    const isOnline = ref(navigator.onLine);

    async function queue(url: string, body: string): Promise<SubmitResult> {
        await queuePendingSubmission(url, body);
        await refreshPendingCount();

        return { queued: true };
    }

    async function submitOrQueue(
        url: string,
        payload: unknown,
    ): Promise<SubmitResult> {
        const body = JSON.stringify(payload);

        if (!navigator.onLine) {
            return queue(url, body);
        }

        try {
            const response = await postJson(url, body);

            return { queued: false, response };
        } catch {
            return queue(url, body);
        }
    }

    function handleOnline() {
        isOnline.value = true;
        void replayQueue();
    }

    function handleOffline() {
        isOnline.value = false;
    }

    onMounted(() => {
        window.addEventListener('online', handleOnline);
        window.addEventListener('offline', handleOffline);

        if (isOnline.value) {
            void replayQueue();
        } else {
            void refreshPendingCount();
        }
    });

    onUnmounted(() => {
        window.removeEventListener('online', handleOnline);
        window.removeEventListener('offline', handleOffline);
    });

    return { isOnline, pendingCount, rejectedCount, submitOrQueue };
}
