import { flushPromises, mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { defineComponent } from 'vue';
import { useExercisePauses } from './useExercisePauses';

vi.mock('@/routes/exercise-pauses', () => ({
    store: (args: { family: string }) => ({
        url: `/exercise-pauses/${args.family}`,
    }),
}));

const submit = vi.fn();

function harness(
    initial = { listening: null, speaking: null } as {
        listening: string | null;
        speaking: string | null;
    },
) {
    let api!: ReturnType<typeof useExercisePauses>;
    const wrapper = mount(
        defineComponent({
            setup() {
                api = useExercisePauses(initial, submit);

                return () => null;
            },
        }),
    );

    return { api, wrapper };
}

function ok(until: string | null) {
    return {
        queued: false,
        response: { ok: true, json: async () => ({ until }) },
    };
}

beforeEach(() => {
    vi.useFakeTimers();
    vi.setSystemTime(new Date('2026-10-03T14:00:00Z'));
    submit.mockReset();
});

afterEach(() => {
    vi.useRealTimers();
});

describe('useExercisePauses', () => {
    it('starts with the pauses the server says are running', () => {
        const { api } = harness({
            listening: '2026-10-03T14:40:00Z',
            speaking: null,
        });

        expect(api.isPaused('listening')).toBe(true);
        expect(api.isPaused('speaking')).toBe(false);
    });

    it('pauses a family for an hour at once, and sends it', async () => {
        submit.mockResolvedValue(ok('2026-10-03T15:00:00Z'));
        const { api } = harness();

        const sent = api.pause('speaking');

        expect(api.isPaused('speaking')).toBe(true);

        await sent;

        expect(submit).toHaveBeenCalledWith('/exercise-pauses/speaking', {
            minutes: 60,
        });
        expect(api.isPaused('listening')).toBe(false);
    });

    it('keeps the pause when it was queued for later', async () => {
        submit.mockResolvedValue({ queued: true });
        const { api } = harness();

        await api.pause('listening');

        expect(api.isPaused('listening')).toBe(true);
    });

    it('keeps the pause when the server cannot take it right now', async () => {
        submit.mockResolvedValue({ queued: false, response: { ok: false } });
        const { api } = harness();

        await api.pause('listening');

        expect(api.isPaused('listening')).toBe(true);
    });

    it('turns a pause back on at once, with zero minutes', async () => {
        submit.mockResolvedValue(ok(null));
        const { api } = harness({
            listening: '2026-10-03T14:40:00Z',
            speaking: null,
        });

        const sent = api.resume('listening');

        expect(api.isPaused('listening')).toBe(false);

        await sent;

        expect(submit).toHaveBeenCalledWith('/exercise-pauses/listening', {
            minutes: 0,
        });
    });

    it('lets a pause end by itself', async () => {
        const { api } = harness({
            listening: '2026-10-03T14:00:20Z',
            speaking: null,
        });

        expect(api.isPaused('listening')).toBe(true);

        await vi.advanceTimersByTimeAsync(30000);
        await flushPromises();

        expect(api.isPaused('listening')).toBe(false);
    });

    it('says when a pause ends, as a time of day', () => {
        const { api } = harness({
            listening: '2026-10-03T14:40:00Z',
            speaking: null,
        });

        expect(api.endsAt('listening')).toMatch(/\d{1,2}[:.]\d{2}/);
        expect(api.endsAt('speaking')).toMatch(/\d{1,2}[:.]\d{2}/);
    });

    it('stops its clock when unmounted', () => {
        const { wrapper } = harness();

        wrapper.unmount();

        expect(vi.getTimerCount()).toBe(0);
    });
});
