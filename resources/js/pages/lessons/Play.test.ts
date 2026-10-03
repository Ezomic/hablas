import 'fake-indexeddb/auto';
import { flushPromises, mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { nextTick, ref } from 'vue';
import type { PlayProps } from '@/types/lesson';

const mocks = vi.hoisted(() => ({
    owner: vi.fn(),
    reload: vi.fn(),
    post: vi.fn(),
    visit: vi.fn(),
    cachePage: vi.fn(),
    submitOrQueue: vi.fn(),
}));

const sync = vi.hoisted(() => ({
    online: { value: true },
    pending: { value: 0 },
}));

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    Link: { props: ['href'], template: '<a :href="href"><slot /></a>' },
    router: { reload: mocks.reload, post: mocks.post, visit: mocks.visit },
    usePage: () => ({ props: { auth: { user: { id: 1 } } } }),
}));

vi.mock('@/composables/useOfflineSync', () => ({
    deviceBelongsToSomeoneElse: (userId: number) => mocks.owner(userId),
    useOfflineSync: () => ({
        submitOrQueue: mocks.submitOrQueue,
        isOnline: sync.online,
        pendingCount: sync.pending,
    }),
}));

vi.mock('@/lib/pageCache', () => ({ cachePage: mocks.cachePage }));
vi.mock('@/routes/lesson-runs', () => ({
    show: (id: number) => ({ url: `/lesson-runs/${id}` }),
}));
vi.mock('@/routes/lesson-runs/answers', () => ({
    store: (args: { lessonRun: number; step: string }) => ({
        url: `/lesson-runs/${args.lessonRun}/answers/${args.step}`,
    }),
}));
vi.mock('@/routes/lesson-runs/answers/flag', () => ({
    store: (args: { lessonRun: number; step: string }) => ({
        url: `/lesson-runs/${args.lessonRun}/answers/${args.step}/flag`,
    }),
}));
vi.mock('@/routes/lesson-runs/speaking-tries', () => ({
    store: (args: { lessonRun: number; lessonExercise: number }) => ({
        url: `/lesson-runs/${args.lessonRun}/exercises/${args.lessonExercise}/speaking-tries`,
    }),
}));
vi.mock('@/routes/exercise-pauses', () => ({
    store: (args: { family: string }) => ({
        url: `/exercise-pauses/${args.family}`,
    }),
}));
vi.mock('@/routes/lessons/runs', () => ({
    store: () => ({ url: '/units/3/lessons/9/runs' }),
}));
vi.mock('@/routes/units', () => ({
    show: (id: number) => ({ url: `/units/${id}` }),
}));

import Play from './Play.vue';

const choose = (
    id: number,
    prompt: string,
    answer: string,
    options: string[],
) => ({
    id,
    key: `k${id}`,
    block: 'b',
    format: 'choose_meaning',
    payload: { prompt, options, answer },
    origin: 'lesson' as const,
    substitute: null,
});

const typed = {
    id: 3,
    key: 'k3',
    block: 'b',
    format: 'type_word',
    payload: {
        prompt: 'key',
        english: 'key',
        hint: 'la  l _ _ _ _',
        accepted: ['la llave'],
    },
    origin: 'lesson' as const,
    substitute: null,
};

let runId = 100;

function props(overrides: Partial<PlayProps> = {}): PlayProps {
    return {
        unit: { id: 3, title: 'Checking into a hotel' },
        run: {
            id: runId,
            kind: 'lesson',
            status: 'in_progress',
            probeSet: null,
            seed: 1,
            startedAt: '2026-10-01T10:00:00Z',
            result: null,
            summary: null,
            next: null,
            summarySeen: false,
            remediation: null,
        },
        lesson: {
            id: 9,
            unitId: 3,
            stage: 'meet',
            title: 'Meet the words',
            position: 1,
        },
        settings: {
            feedback: true,
            hintsAreFree: true,
            audioSpeed: 0.75,
            replayLimit: null,
            offersSlowerAudio: true,
            speechLocale: 'es-ES',
            pauses: { listening: null, speaking: null },
        },
        plan: [
            choose(1, 'el hotel', 'hotel', ['hotel', 'room', 'key', 'night']),
            typed,
        ],
        answers: [],
        ...overrides,
    };
}

function json(body: unknown, ok = true) {
    return { queued: false, response: { ok, json: async () => body } };
}

function mountPlay(overrides: Partial<PlayProps> = {}) {
    return mount(Play, { props: props(overrides), attachTo: document.body });
}

const run = {
    completed: false,
    unitCompleted: false,
    mastery: { mastered: 0, total: 10 },
};

beforeEach(async () => {
    runId++;
    vi.resetAllMocks();
    sync.online = ref(true) as unknown as { value: boolean };
    sync.pending = ref(0) as unknown as { value: number };
    mocks.submitOrQueue.mockResolvedValue(json({ saved: true, run }));
    document.body.innerHTML = '';
});

async function check(wrapper: ReturnType<typeof mountPlay>) {
    await wrapper.get('[data-testid="lesson-footer"] button').trigger('click');
    await flushPromises();
}

describe('the player', () => {
    it('puts its own page into the cache on mount', async () => {
        mountPlay();
        await flushPromises();

        expect(mocks.cachePage).toHaveBeenCalledWith(`/lesson-runs/${runId}`);
    });

    it('shows the first exercise with its instruction, with no sidebar chrome', async () => {
        const wrapper = mountPlay();

        expect(wrapper.text()).toContain('Choose the meaning');
        expect(wrapper.get('[data-testid="prompt"]').text()).toBe('el hotel');
        expect(
            wrapper
                .get('[data-testid="lesson-footer"] button')
                .attributes('disabled'),
        ).toBeDefined();
    });

    it('grades a choice on the device, shows the feedback at once and sends the answer', async () => {
        const wrapper = mountPlay();

        await wrapper.findAll('[role="radio"]')[1].trigger('click');
        await check(wrapper);

        expect(wrapper.get('[data-testid="feedback"]').text()).toContain(
            'Correct answer: hotel',
        );
        const [url, body] = mocks.submitOrQueue.mock.calls[0];

        expect(url).toMatch(
            new RegExp(`^/lesson-runs/${runId}/answers/[0-9a-f-]{36}$`),
        );
        expect(body).toMatchObject({
            exercise_id: 1,
            response: { choice: 'room' },
            hinted: false,
        });
        expect(Date.parse(body.answered_at)).not.toBeNaN();
    });

    it('moves on after Continue and brings a mistake back three steps later', async () => {
        const wrapper = mountPlay({
            plan: [
                choose(1, 'a', 'x', ['x', 'y']),
                choose(2, 'b', 'x', ['x', 'y']),
                choose(4, 'c', 'x', ['x', 'y']),
                choose(5, 'd', 'x', ['x', 'y']),
            ],
        });

        await wrapper.findAll('[role="radio"]')[1].trigger('click');
        await check(wrapper);
        await wrapper
            .get('[data-testid="lesson-footer"] button:last-of-type')
            .trigger('click');
        const seen: string[] = [];

        for (let index = 0; index < 5; index++) {
            seen.push(wrapper.get('[data-testid="prompt"]').text());
            await wrapper.findAll('[role="radio"]')[0].trigger('click');
            await check(wrapper);
            await wrapper
                .get('[data-testid="lesson-footer"] button:last-of-type')
                .trigger('click');

            if (!wrapper.find('[data-testid="prompt"]').exists()) {
                break;
            }
        }

        expect(seen).toEqual(['b', 'c', 'd', 'a']);
    });

    it("waits for the server's verdict on a typed answer and shows its note and expected answer", async () => {
        mocks.submitOrQueue.mockResolvedValue(
            json({
                correct: false,
                expected: 'la llave',
                note: 'accent',
                score: null,
                run,
                milestone: null,
            }),
        );
        const wrapper = mountPlay({ plan: [typed] });

        await wrapper.get('input').setValue('la llavé');
        await check(wrapper);

        expect(wrapper.get('[data-testid="feedback"]').text()).toContain(
            'Correct answer: la llave',
        );
        expect(wrapper.text()).toContain('My answer should count');
        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            response: { text: 'la llavé' },
        });
    });

    it('flags a wrong answer through its own url', async () => {
        mocks.submitOrQueue.mockResolvedValueOnce(
            json({ correct: false, expected: 'la llave', note: null, run }),
        );
        mocks.submitOrQueue.mockResolvedValueOnce(json({ flagged: true, run }));
        const wrapper = mountPlay({ plan: [typed] });

        await wrapper.get('input').setValue('zzz');
        await check(wrapper);
        const step = mocks.submitOrQueue.mock.calls[0][0].split('/').pop();
        await wrapper
            .findAll('button')
            .find((button) => button.text().includes('should count'))
            ?.trigger('click');
        await flushPromises();

        expect(mocks.submitOrQueue.mock.calls[1][0]).toBe(
            `/lesson-runs/${runId}/answers/${step}/flag`,
        );
        expect(wrapper.text()).toContain('Thanks');
    });

    it('falls back to a self-check when a typed answer is queued offline, and sends the self-check', async () => {
        mocks.submitOrQueue.mockResolvedValueOnce({ queued: true });
        mocks.submitOrQueue.mockResolvedValueOnce({ queued: true });
        const wrapper = mountPlay({ plan: [typed] });

        await wrapper.get('input').setValue('la llave');
        await check(wrapper);

        expect(wrapper.text()).toContain('Did you have it?');

        await wrapper
            .findAll('[data-testid="lesson-footer"] button')
            .find((button) => button.text() === 'Yes')
            ?.trigger('click');
        await flushPromises();

        const [firstUrl] = mocks.submitOrQueue.mock.calls[0];
        const [secondUrl, secondBody] = mocks.submitOrQueue.mock.calls[1];

        expect(secondUrl).toBe(firstUrl);
        expect(secondBody).toMatchObject({ self_graded_correct: true });
    });

    it('shows a hint only on request after lesson 1 and sends the answer as hinted', async () => {
        mocks.submitOrQueue.mockResolvedValue(
            json({ correct: true, expected: 'la llave', note: null, run }),
        );
        const wrapper = mountPlay({ plan: [typed] });

        await wrapper
            .findAll('button')
            .find((button) => button.text().includes('hint'))
            ?.trigger('click');

        expect(wrapper.get('[data-testid="hint"]').text()).toContain(
            'Starts with "l"',
        );

        await wrapper.get('input').setValue('la llave');
        await check(wrapper);

        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            hinted: true,
        });
    });

    it('offers no hint and gives no verdict in a check, and says the answer was saved', async () => {
        const wrapper = mountPlay({
            settings: { ...props().settings, feedback: false },
            plan: [{ ...typed, payload: { prompt: 'key', english: 'key' } }],
        });

        expect(wrapper.text()).not.toContain('hint');

        await wrapper.get('input').setValue('la llave');
        await check(wrapper);

        expect(wrapper.find('[data-testid="feedback"]').exists()).toBe(false);
        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            hinted: false,
            response: { text: 'la llave' },
        });
        expect(wrapper.find('[data-testid="finishing"]').exists()).toBe(true);
    });

    it('restores the answers the journal holds after a reload, so nothing answered offline is lost', async () => {
        const { recordJournal } = await import('@/lib/lessonJournal');
        await recordJournal(1, runId + 0, {
            step: 's1',
            exerciseId: 1,
            hinted: false,
            skipped: false,
            correct: true,
            settled: true,
            flagged: false,
        });

        const wrapper = mountPlay();

        await vi.waitFor(() =>
            expect(wrapper.get('[data-testid="prompt"]').text()).toBe('key'),
        );
    });

    it('skips an exercise the device cannot play for its substitute', async () => {
        const wrapper = mountPlay({
            plan: [
                {
                    id: 1,
                    key: 'l',
                    block: 'b',
                    format: 'listen_choose',
                    payload: {
                        text: 'la llave',
                        options: ['key'],
                        answer: 'key',
                    },
                    origin: 'lesson',
                    substitute: {
                        id: 11,
                        key: 'l.sub',
                        block: 'b',
                        format: 'choose_meaning',
                        payload: {
                            prompt: 'la llave',
                            options: ['key', 'room'],
                            answer: 'key',
                        },
                    },
                },
            ],
        });
        await flushPromises();

        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            exercise_id: 1,
            skipped: true,
            skip_reason: 'unsupported',
        });
        expect(wrapper.get('[data-testid="prompt"]').text()).toBe('la llave');
    });

    it('reloads its props from the server once every answer has been sent', async () => {
        const wrapper = mountPlay({ plan: [choose(1, 'a', 'x', ['x', 'y'])] });

        await wrapper.findAll('[role="radio"]')[0].trigger('click');
        await check(wrapper);
        await wrapper
            .get('[data-testid="lesson-footer"] button:last-of-type')
            .trigger('click');
        await vi.waitFor(() => expect(mocks.reload).toHaveBeenCalled());
        await flushPromises();

        expect(wrapper.find('[data-testid="finishing"]').exists()).toBe(true);
    });

    it('waits while answers are still queued, and says so offline, then reloads once they drain', async () => {
        sync.online.value = false;
        sync.pending.value = 1;
        const wrapper = mountPlay({ plan: [choose(1, 'a', 'x', ['x', 'y'])] });

        await wrapper.findAll('[role="radio"]')[0].trigger('click');
        await check(wrapper);
        await wrapper
            .get('[data-testid="lesson-footer"] button:last-of-type')
            .trigger('click');
        await flushPromises();

        expect(mocks.reload).not.toHaveBeenCalled();
        expect(wrapper.get('[data-testid="finishing"]').text()).toContain(
            'back online',
        );

        sync.online.value = true;
        sync.pending.value = 0;

        await vi.waitFor(() => expect(mocks.reload).toHaveBeenCalled());
    });

    it('shows the summary of a completed run and starts the next lesson from it', async () => {
        const wrapper = mountPlay({
            run: {
                ...props().run,
                status: 'completed',
                summary: {
                    accuracy: { choice: 1 },
                    retried: [],
                    items: [],
                    answers: [],
                    cardsEnrolled: 0,
                    unitCompleted: false,
                },
                next: {
                    lessonId: 9,
                    title: 'Recall the words',
                    position: 2,
                    stage: 'recall',
                    state: 'available',
                },
            },
            answers: [],
        });

        expect(wrapper.get('[data-testid="summary"]').text()).toContain(
            'Lesson complete',
        );

        await wrapper
            .findAll('button')
            .find((button) => button.text().includes('Next lesson'))
            ?.trigger('click');

        expect(mocks.post).toHaveBeenCalledWith(
            '/units/3/lessons/9/runs',
            {},
            expect.anything(),
        );
    });

    it('shows an error with a retry when the answer could not be saved', async () => {
        mocks.submitOrQueue.mockResolvedValueOnce({
            queued: false,
            response: { ok: false, json: async () => ({ message: 'Boom' }) },
        });
        mocks.submitOrQueue.mockResolvedValueOnce(json({ saved: true, run }));
        const wrapper = mountPlay({
            plan: [
                choose(1, 'a', 'x', ['x', 'y']),
                choose(2, 'b', 'x', ['x', 'y']),
            ],
        });

        await wrapper.findAll('[role="radio"]')[0].trigger('click');
        await check(wrapper);

        expect(wrapper.get('[role="alert"]').text()).toContain('Boom');

        await wrapper
            .findAll('[data-testid="lesson-footer"] button')
            .find((button) => button.text() === 'Try again')
            ?.trigger('click');
        await flushPromises();

        expect(mocks.submitOrQueue).toHaveBeenCalledTimes(2);
        expect(wrapper.find('[role="alert"]').exists()).toBe(false);
    });

    it('presses Enter to check and to continue', async () => {
        const wrapper = mountPlay({
            plan: [
                choose(1, 'a', 'x', ['x', 'y']),
                choose(2, 'b', 'x', ['x', 'y']),
            ],
        });

        await wrapper.findAll('[role="radio"]')[0].trigger('click');
        window.dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter' }));
        await flushPromises();

        expect(wrapper.find('[data-testid="feedback"]').exists()).toBe(true);

        window.dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter' }));
        await flushPromises();

        expect(wrapper.get('[data-testid="prompt"]').text()).toBe('b');
    });
});

function failed(status: number) {
    return {
        queued: false,
        response: {
            ok: false,
            status,
            json: async () => ({ message: 'Boom' }),
        },
    };
}

async function journalRows() {
    const { readJournal } = await import('@/lib/lessonJournal');

    return readJournal(1, runId);
}

describe('an answer the server did not take', () => {
    const plan = [
        choose(1, 'a', 'x', ['x', 'y']),
        choose(2, 'b', 'x', ['x', 'y']),
    ];

    async function answerAndFail(status: number) {
        mocks.submitOrQueue.mockResolvedValueOnce(failed(status));
        const first = mountPlay({ plan });

        await first.findAll('[role="radio"]')[0].trigger('click');
        await check(first);

        expect(first.get('[role="alert"]').text()).toContain('Boom');
        first.unmount();
        mocks.submitOrQueue.mockResolvedValue(json({ saved: true, run }));

        return mocks.submitOrQueue.mock.calls[0];
    }

    it.each([500, 503, 419, 401])(
        'is resent when the lesson is reopened after a %i, never skipped',
        async (status) => {
            const [url, body] = await answerAndFail(status);

            mountPlay({ plan });
            await vi.waitFor(() =>
                expect(mocks.submitOrQueue).toHaveBeenCalledTimes(2),
            );

            expect(mocks.submitOrQueue.mock.calls[1]).toEqual([url, body]);
        },
    );

    it('is sent once the server has it, and not again on the next opening', async () => {
        await answerAndFail(500);

        mountPlay({ plan });
        await vi.waitFor(async () =>
            expect((await journalRows())[0].request).toBeNull(),
        );
        document.body.innerHTML = '';
        mountPlay({ plan });
        await flushPromises();

        expect(mocks.submitOrQueue).toHaveBeenCalledTimes(2);
    });

    it('is given up on when the server refuses it outright', async () => {
        await answerAndFail(500);
        mocks.submitOrQueue.mockResolvedValue(failed(422));

        mountPlay({ plan });
        await vi.waitFor(async () =>
            expect((await journalRows())[0].request).toBeNull(),
        );
        mountPlay({ plan });
        await flushPromises();

        expect(mocks.submitOrQueue).toHaveBeenCalledTimes(2);
    });

    it('is resent again when the server still fails, and when the device comes back online', async () => {
        await answerAndFail(500);
        mocks.submitOrQueue.mockResolvedValue(failed(500));

        mountPlay({ plan });
        await vi.waitFor(() =>
            expect(mocks.submitOrQueue).toHaveBeenCalledTimes(2),
        );
        await flushPromises();

        expect((await journalRows())[0].request).not.toBeNull();

        mocks.submitOrQueue.mockResolvedValue(json({ saved: true, run }));
        sync.online.value = false;
        await nextTick();
        sync.online.value = true;

        await vi.waitFor(() =>
            expect(mocks.submitOrQueue).toHaveBeenCalledTimes(3),
        );
    });

    it('is never resent as the signed-in user once someone else has taken over the device, and is kept for its own user', async () => {
        await answerAndFail(500);
        mocks.owner.mockReturnValue(true);

        mountPlay({ plan });
        await flushPromises();
        sync.online.value = false;
        await nextTick();
        sync.online.value = true;
        await flushPromises();

        expect(mocks.owner).toHaveBeenCalledWith(1);
        expect(mocks.submitOrQueue).toHaveBeenCalledTimes(1);
        expect((await journalRows())[0].request).not.toBeNull();

        mocks.owner.mockReturnValue(false);
        document.body.innerHTML = '';
        mountPlay({ plan });

        await vi.waitFor(() =>
            expect(mocks.submitOrQueue).toHaveBeenCalledTimes(2),
        );
    });

    it('is not resent when it was queued for the device to sync', async () => {
        mocks.submitOrQueue.mockResolvedValueOnce({ queued: true });
        const wrapper = mountPlay({ plan });

        await wrapper.findAll('[role="radio"]')[0].trigger('click');
        await check(wrapper);
        await vi.waitFor(async () =>
            expect((await journalRows())[0].request).toBeNull(),
        );
        wrapper.unmount();
        mountPlay({ plan });
        await flushPromises();

        expect(mocks.submitOrQueue).toHaveBeenCalledTimes(1);
    });

    it('is not resent when the server already holds it', async () => {
        await answerAndFail(500);
        const [url] = mocks.submitOrQueue.mock.calls[0];

        mountPlay({
            plan,
            answers: [
                {
                    step: url.split('/').pop(),
                    exerciseId: 1,
                    attempt: 1,
                    hinted: false,
                    skipped: false,
                    skipReason: null,
                    correct: true,
                    flagged: false,
                    settled: true,
                },
            ],
        });
        await flushPromises();

        expect(mocks.submitOrQueue).toHaveBeenCalledTimes(1);
    });
});

describe('answering twice in a row', () => {
    it('records a check answer once when the button is tapped twice', async () => {
        const wrapper = mountPlay({
            settings: { ...props().settings, feedback: false },
            plan: [
                { ...typed, payload: { prompt: 'key', english: 'key' } },
                {
                    ...typed,
                    id: 4,
                    payload: { prompt: 'room', english: 'room' },
                },
            ],
        });

        await wrapper.get('input').setValue('la llave');
        const button = wrapper.get('[data-testid="lesson-footer"] button');

        await Promise.all([button.trigger('click'), button.trigger('click')]);
        await flushPromises();

        expect(mocks.submitOrQueue).toHaveBeenCalledTimes(1);
    });
});

describe('moving between typed exercises', () => {
    it('puts the cursor in the next input', async () => {
        mocks.submitOrQueue.mockResolvedValue(
            json({ correct: true, expected: 'la llave', note: null, run }),
        );
        const wrapper = mountPlay({
            plan: [
                typed,
                {
                    ...typed,
                    id: 4,
                    payload: { ...typed.payload, prompt: 'room' },
                },
            ],
        });

        await wrapper.get('input').setValue('la llave');
        await check(wrapper);
        const focus = vi.spyOn(HTMLInputElement.prototype, 'focus');
        await wrapper
            .get('[data-testid="lesson-footer"] button:last-of-type')
            .trigger('click');
        await flushPromises();

        expect(wrapper.get('[data-testid="prompt"]').text()).toBe('room');
        expect(focus).toHaveBeenCalled();
        expect(document.activeElement).toBe(wrapper.get('input').element);
        focus.mockRestore();
    });
});
