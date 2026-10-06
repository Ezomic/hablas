import { beforeEach, describe, expect, it, vi } from 'vitest';
import {
    handleChunkLoadFailure,
    initializeStaleBuildReload,
} from './staleBuildReload';

function preloadError(): Event {
    return new Event('vite:preloadError', { cancelable: true });
}

describe('handleChunkLoadFailure', () => {
    beforeEach(() => {
        window.sessionStorage.clear();
    });

    it('reloads the page and keeps the error from surfacing', () => {
        const reload = vi.fn();
        const event = preloadError();

        handleChunkLoadFailure(event, reload, 1_000_000);

        expect(reload).toHaveBeenCalledTimes(1);
        expect(event.defaultPrevented).toBe(true);
    });

    it('does not reload again straight after a reload, so a real error cannot loop', () => {
        const reload = vi.fn();

        handleChunkLoadFailure(preloadError(), reload, 1_000_000);

        const second = preloadError();
        handleChunkLoadFailure(second, reload, 1_005_000);

        expect(reload).toHaveBeenCalledTimes(1);
        expect(second.defaultPrevented).toBe(false);
    });

    it('reloads again once the guard window has passed', () => {
        const reload = vi.fn();

        handleChunkLoadFailure(preloadError(), reload, 1_000_000);
        handleChunkLoadFailure(preloadError(), reload, 1_020_000);

        expect(reload).toHaveBeenCalledTimes(2);
    });

    it('still reloads when session storage is not available', () => {
        const reload = vi.fn();
        const spy = vi
            .spyOn(Storage.prototype, 'getItem')
            .mockImplementation(() => {
                throw new Error('blocked');
            });

        handleChunkLoadFailure(preloadError(), reload, 1_000_000);

        expect(reload).toHaveBeenCalledTimes(1);
        spy.mockRestore();
    });
});

describe('initializeStaleBuildReload', () => {
    it('listens for failed chunk loads on the window', () => {
        window.sessionStorage.clear();
        const reload = vi.fn();
        vi.stubGlobal('location', { ...window.location, reload });

        initializeStaleBuildReload();
        const event = preloadError();
        window.dispatchEvent(event);

        expect(event.defaultPrevented).toBe(true);
        vi.unstubAllGlobals();
    });
});
