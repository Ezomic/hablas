const STORAGE_KEY = 'stale-build-reload-at';
const LOOP_GUARD_MS = 15_000;

function lastReload(): number {
    try {
        return Number(window.sessionStorage.getItem(STORAGE_KEY) ?? 0);
    } catch {
        return 0;
    }
}

function rememberReload(now: number): void {
    try {
        window.sessionStorage.setItem(STORAGE_KEY, String(now));
    } catch {
        // Without storage the guard cannot remember, and one reload is still right.
    }
}

/**
 * A page that was loaded before a deploy still asks for the chunks of its own
 * build, and every release is immutable, so those files are gone. Reloading
 * fetches the current build. A second failure within the guard window is a
 * real error and is left to surface rather than looping.
 */
export function handleChunkLoadFailure(
    event: Event,
    reload: () => void = () => window.location.reload(),
    now: number = Date.now(),
): void {
    if (now - lastReload() < LOOP_GUARD_MS) {
        return;
    }

    event.preventDefault();
    rememberReload(now);
    reload();
}

export function initializeStaleBuildReload(): void {
    window.addEventListener('vite:preloadError', (event) =>
        handleChunkLoadFailure(event),
    );
}
