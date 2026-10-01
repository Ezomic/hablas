import { router, usePage } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import { fetchJson } from '@/lib/http';
import {
    clearLessonAnswers,
    clearPendingSubmissions,
    countPendingSubmissions,
    getPendingSubmissions,
    queuePendingSubmission,
    removeOtherUsersLessonAnswers,
    removeOtherUsersSubmissions,
    removeSentSubmission,
} from '@/lib/offlineDb';
import { clearPageCache } from '@/lib/pageCache';
import type { User } from '@/types';

export type SubmitResult =
    { queued: true } | { queued: false; response: Response };

// Module-level so the layout's sync banner and the page that queued an
// attempt read the same numbers.
const pendingCount = ref(0);
const rejectedCount = ref(0);

let replayInFlight: Promise<void> | null = null;

// The last user any tab on this device saw signed in. The page cache holds
// their pages, and only their tabs may replay the queue: the browser sends
// every request with the latest session, whoever this tab's page shows.
const OWNER_KEY = 'hablas-offline-owner';

function deviceOwner(): number | null {
    const owner = localStorage.getItem(OWNER_KEY);

    return owner === null ? null : Number(owner);
}

function signedInUserId(): number | null {
    const user: User | null = usePage().props.auth.user;

    return user?.id ?? null;
}

function postJson(url: string, body: string): Promise<Response> {
    return fetchJson(url, 'POST', body);
}

// A server error, an expired session or CSRF token, a timeout or rate
// limiting can all succeed on a later attempt. Any other 4xx fails the same
// way every time, so keeping it would wedge everything queued behind it.
function isRetryable(status: number): boolean {
    return status >= 500 || [401, 408, 419, 429].includes(status);
}

async function refreshPendingCount(userId: number | null): Promise<void> {
    pendingCount.value =
        userId === null ? 0 : await countPendingSubmissions(userId);
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
// row, and that newer answer is what has to be sent. Stops once any tab has
// seen someone else signed in, as the next request would be sent as them.
async function drainQueue(userId: number): Promise<void> {
    await refreshPendingCount(userId);

    while (deviceOwner() === userId) {
        const [submission] = await getPendingSubmissions(userId, 1);

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

    await refreshPendingCount(userId);
}

// The lock guards against two tabs racing to replay the same queued rows; the
// in-flight promise does the same for the several components in one tab that
// mount together.
function replayQueue(): Promise<void> {
    const userId = signedInUserId();

    if (userId === null) {
        return Promise.resolve();
    }

    replayInFlight ??= withReplayLock(() => drainQueue(userId)).finally(() => {
        replayInFlight = null;
    });

    return replayInFlight;
}

/**
 * Forgets everything this device holds for the user signing out: attempts
 * still waiting to sync, lesson answers kept for a reload, and the cached
 * pages rendered with their data.
 */
export async function clearOfflineData(): Promise<void> {
    await Promise.all([
        clearPendingSubmissions(),
        clearLessonAnswers(),
        clearPageCache(),
    ]);
    pendingCount.value = 0;
    rejectedCount.value = 0;
}

/**
 * Hands this device's offline data to whoever the page says is signed in,
 * however the previous session ended: signing out through ID, an expired
 * session, another tab, or another account signing in. Attempts anyone else
 * queued are dropped, and so is the page cache once it may hold someone
 * else's pages. With nobody signed in the queue stays, since only the user
 * who queued it can replay it.
 */
export async function claimOfflineData(userId: number | null): Promise<void> {
    if (userId === null) {
        await clearPageCache();
        pendingCount.value = 0;
        rejectedCount.value = 0;

        return;
    }

    if (deviceOwner() !== userId) {
        await clearPageCache();
        localStorage.setItem(OWNER_KEY, String(userId));
        rejectedCount.value = 0;
    }

    await removeOtherUsersSubmissions(userId);
    await removeOtherUsersLessonAnswers(userId);
    await refreshPendingCount(userId);
}

/** Claims the offline data for the signed-in user on every page load and visit. */
export function initializeOfflineSync(): void {
    router.on('navigate', (event) => {
        const user: User | null = event.detail.page.props.auth.user;

        void claimOfflineData(user?.id ?? null);
    });
}

/**
 * Submits a POST as JSON, falling back to an IndexedDB queue when offline
 * (or when the request fails outright) rather than losing the attempt. The
 * queue is replayed in order whenever the browser comes back online.
 */
export function useOfflineSync() {
    const isOnline = ref(navigator.onLine);

    async function queue(url: string, body: string): Promise<SubmitResult> {
        // Attempts are only submitted from pages that need a signed-in user.
        const userId = signedInUserId()!;

        await queuePendingSubmission(userId, url, body);
        await refreshPendingCount(userId);

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
            void refreshPendingCount(signedInUserId());
        }
    });

    onUnmounted(() => {
        window.removeEventListener('online', handleOnline);
        window.removeEventListener('offline', handleOffline);
    });

    return { isOnline, pendingCount, rejectedCount, submitOrQueue };
}
