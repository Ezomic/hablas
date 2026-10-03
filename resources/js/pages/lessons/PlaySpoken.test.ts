import 'fake-indexeddb/auto';
import { flushPromises, mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { nextTick, ref } from 'vue';
import { FakeAudio } from '@/test/fakeAudio';
import type { PlanExercise, PlayProps } from '@/types/lesson';

const mocks = vi.hoisted(() => ({
    owner: vi.fn(),
    submitOrQueue: vi.fn(),
    fetchJson: vi.fn(),
}));

const sync = vi.hoisted(() => ({
    online: { value: true },
    pending: { value: 0 },
}));

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    Link: { props: ['href'], template: '<a :href="href"><slot /></a>' },
    router: { reload: vi.fn(), post: vi.fn(), visit: vi.fn() },
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

vi.mock('@/lib/http', async (original) => ({
    ...(await original<Record<string, unknown>>()),
    fetchJson: mocks.fetchJson,
}));

vi.mock('@/lib/pageCache', () => ({ cachePage: vi.fn() }));
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
vi.mock('@/components/ui/dropdown-menu', () => ({
    DropdownMenu: { template: '<div><slot /></div>' },
    DropdownMenuTrigger: { template: '<div><slot /></div>' },
    DropdownMenuContent: { template: '<div><slot /></div>' },
    DropdownMenuItem: {
        emits: ['select'],
        template: '<button @click="$emit(\'select\')"><slot /></button>',
    },
}));

import Play from './Play.vue';

class FakeRecognition {
    static last: FakeRecognition | null = null;

    lang = '';
    interimResults = false;
    maxAlternatives = 1;
    onresult: ((event: unknown) => void) | null = null;
    onerror: ((event: unknown) => void) | null = null;
    onend: (() => void) | null = null;
    start = vi.fn();
    stop = vi.fn();
    abort = vi.fn();

    constructor() {
        FakeRecognition.last = this;
    }

    hear(transcript: string) {
        this.onresult?.({ results: [[{ transcript }]] });
        this.onend?.();
    }
}

const sub = (id: number, format: string, payload: Record<string, unknown>) => ({
    id,
    key: `s${id}`,
    block: 'b',
    format,
    payload,
});

const listenChoose: PlanExercise = {
    id: 1,
    key: 'l1',
    block: 'b',
    format: 'listen_choose',
    payload: {
        text: 'la llave',
        options: ['key', 'room', 'hotel', 'night'],
        answer: 'key',
        audioUrl: '/clips/la-llave.mp3',
        audioSlowUrl: '/clips/la-llave-slow.mp3',
        audioRole: 'prompt',
    },
    origin: 'lesson',
    substitute: sub(11, 'choose_meaning', {
        prompt: 'la llave',
        options: ['key', 'room'],
        answer: 'key',
    }),
};

const listenType: PlanExercise = {
    id: 2,
    key: 'l2',
    block: 'b',
    format: 'listen_type',
    payload: {
        english: 'The reservation is for two nights.',
        audioUrl: '/clips/reserva.mp3',
        audioSlowUrl: '/clips/reserva-slow.mp3',
        audioRole: 'prompt',
    },
    origin: 'lesson',
    substitute: sub(12, 'translate_sentence', {
        prompt: 'The reservation is for two nights.',
        accepted: ['La reserva es para dos noches.'],
    }),
};

const speakRepeat: PlanExercise = {
    id: 3,
    key: 's3',
    block: 'b',
    format: 'speak_repeat',
    payload: {
        text: 'la llave',
        english: 'the key',
        audioUrl: '/clips/la-llave.mp3',
        audioRole: 'prompt',
    },
    origin: 'lesson',
    substitute: sub(13, 'type_word', {
        prompt: 'the key',
        english: 'the key',
        hint: 'la  l _ _ _ _',
        accepted: ['la llave'],
    }),
};

const speakAnswer: PlanExercise = {
    id: 4,
    key: 's4',
    block: 'b',
    format: 'speak_answer',
    payload: {
        prompt: 'key',
        english: 'key',
        audioUrl: '/clips/la-llave.mp3',
        audioRole: 'model',
    },
    origin: 'lesson',
    substitute: sub(14, 'type_word', {
        prompt: 'key',
        english: 'key',
        accepted: ['la llave'],
    }),
};

const typed: PlanExercise = {
    id: 5,
    key: 't5',
    block: 'b',
    format: 'type_word',
    payload: { prompt: 'room', english: 'room', accepted: ['la habitación'] },
    origin: 'lesson',
    substitute: null,
};

const run = {
    completed: false,
    unitCompleted: false,
    mastery: { mastered: 0, total: 10 },
};

let runId = 500;

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
            stage: 'sentences',
            title: 'Build sentences',
            position: 3,
        },
        settings: {
            feedback: true,
            hintsAreFree: false,
            audioSpeed: 1,
            replayLimit: 3,
            offersSlowerAudio: true,
            speechLocale: 'es-ES',
            pauses: { listening: null, speaking: null },
        },
        plan: [listenChoose],
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

async function check(wrapper: ReturnType<typeof mountPlay>) {
    await wrapper.get('[data-testid="lesson-footer"] button').trigger('click');
    await flushPromises();
}

async function proceed(wrapper: ReturnType<typeof mountPlay>) {
    await wrapper
        .findAll('[data-testid="lesson-footer"] button')
        .find((button) => button.text() === 'Continue')
        ?.trigger('click');
    await flushPromises();
}

function skips() {
    return mocks.submitOrQueue.mock.calls
        .map(([url, body]) => ({ url, body }))
        .filter((call) => call.body.skipped === true)
        .map((call) => call.body);
}

function setVoices(voices: { lang: string }[]) {
    vi.stubGlobal('speechSynthesis', {
        speak: vi.fn(),
        cancel: vi.fn(),
        getVoices: () => voices,
        addEventListener: vi.fn(),
        removeEventListener: vi.fn(),
    });
}

beforeEach(() => {
    runId++;
    vi.resetAllMocks();
    vi.unstubAllGlobals();
    FakeAudio.reset();
    FakeRecognition.last = null;
    vi.stubGlobal('Audio', FakeAudio);
    vi.stubGlobal('SpeechSynthesisUtterance', class {});
    vi.stubGlobal('webkitSpeechRecognition', FakeRecognition);
    setVoices([{ lang: 'es-ES' }]);
    sync.online = ref(true) as unknown as { value: boolean };
    sync.pending = ref(0) as unknown as { value: number };
    mocks.submitOrQueue.mockResolvedValue(json({ saved: true, run }));
    document.body.innerHTML = '';
});

describe('a word heard and chosen', () => {
    it('plays its clip on arrival, shows no text before the answer, and grades the choice on the device', async () => {
        const wrapper = mountPlay();

        await flushPromises();

        expect(FakeAudio.last().src).toBe('/clips/la-llave.mp3');
        expect(wrapper.text()).toContain('Listen and choose the meaning');
        expect(wrapper.find('[data-testid="heard"]').exists()).toBe(false);

        await wrapper.findAll('[role="radio"]')[0].trigger('click');
        await check(wrapper);

        expect(wrapper.get('[data-testid="feedback"]').text()).toContain(
            'Right',
        );
        expect(wrapper.get('[data-testid="heard"]').text()).toBe('la llave');
        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            response: { choice: 'key' },
        });
    });

    it('limits the replays to what the stage gives', async () => {
        const wrapper = mountPlay();

        await flushPromises();
        FakeAudio.last().onplaying?.();
        FakeAudio.last().onended?.();
        await flushPromises();

        expect(wrapper.get('[data-testid="listen-left"]').text()).toBe(
            '3 replays left',
        );
    });

    it('offers a slower play in a stage that plays at normal speed, and none where it already plays slowly', async () => {
        const normal = mountPlay();

        expect(normal.find('[data-testid="listen-slower"]').exists()).toBe(
            true,
        );

        const slow = mountPlay({
            settings: {
                ...props().settings,
                audioSpeed: 0.75,
                replayLimit: null,
            },
        });
        await flushPromises();

        expect(slow.find('[data-testid="listen-slower"]').exists()).toBe(false);
        expect(slow.find('[data-testid="listen-left"]').exists()).toBe(false);
        expect(
            FakeAudio.instances.find(
                (audio) =>
                    audio.play.mock.calls.length > 0 &&
                    audio.src.includes('slow'),
            )?.src,
        ).toBe('/clips/la-llave-slow.mp3');
    });

    it('is swapped for its substitute where the browser has no exact voice and there is no clip', async () => {
        setVoices([{ lang: 'es-MX' }]);
        const wrapper = mountPlay({
            plan: [
                {
                    ...listenChoose,
                    payload: {
                        ...listenChoose.payload,
                        audioUrl: null,
                        audioSlowUrl: null,
                    },
                },
            ],
        });

        await vi.waitFor(() => expect(skips()).toHaveLength(1), {
            timeout: 3000,
        });
        await flushPromises();

        expect(skips()[0]).toMatchObject({
            exercise_id: 1,
            skip_reason: 'unsupported',
        });
        expect(wrapper.get('[data-testid="prompt"]').text()).toBe('la llave');
        expect(wrapper.get('[data-testid="swap-notice"]').text()).toContain(
            "can't play this one",
        );
    });

    it('is played as it is, with a clip, on a device with no exact voice', async () => {
        setVoices([{ lang: 'es-MX' }]);
        const wrapper = mountPlay();

        await new Promise((resolve) => setTimeout(resolve, 2100));
        await flushPromises();

        expect(skips()).toEqual([]);
        expect(wrapper.find('[data-testid="listen-exercise"]').exists()).toBe(
            true,
        );
    });
});

describe('a dictation', () => {
    const dictation = () => mountPlay({ plan: [listenType, typed] });

    it('is heard from its clip, never shows its text, and waits for the verdict', async () => {
        mocks.submitOrQueue.mockResolvedValue(
            json({
                correct: false,
                expected: 'La reserva es para dos noches.',
                note: null,
                run,
                milestone: null,
            }),
        );
        const wrapper = dictation();

        await flushPromises();

        expect(FakeAudio.last().src).toBe('/clips/reserva.mp3');
        expect(wrapper.text()).toContain('Listen and type what you hear');
        expect(wrapper.text()).not.toContain('reserva es para');
        expect(wrapper.find('[data-testid="prompt"]').exists()).toBe(false);

        await wrapper.get('input').setValue('la reserva');
        await check(wrapper);

        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            exercise_id: 2,
            response: { text: 'la reserva' },
        });
        expect(wrapper.get('[data-testid="heard"]').text()).toBe(
            'La reserva es para dos noches.',
        );
        expect(wrapper.text()).toContain('Correct answer');
    });

    it('is swapped for its translation where it has no clip, because its text is never sent', async () => {
        const wrapper = mountPlay({
            plan: [
                {
                    ...listenType,
                    payload: {
                        english: 'x',
                        audioUrl: null,
                        audioSlowUrl: null,
                    },
                },
            ],
        });

        await vi.waitFor(() => expect(skips()).toHaveLength(1));
        await flushPromises();

        expect(skips()[0]).toMatchObject({
            exercise_id: 2,
            skip_reason: 'unsupported',
        });
        expect(wrapper.get('[data-testid="prompt"]').text()).toBe(
            'The reservation is for two nights.',
        );
    });

    it('is swapped while offline, since an answer typed offline cannot be checked against text that is not there', async () => {
        sync.online.value = false;
        const wrapper = dictation();

        await vi.waitFor(() => expect(skips()).toHaveLength(1));

        expect(skips()[0]).toMatchObject({
            exercise_id: 2,
            skip_reason: 'offline',
        });
        expect(wrapper.find('[data-testid="listen-exercise"]').exists()).toBe(
            false,
        );
    });

    it('offers slower audio as its hint, and sends the answer as hinted', async () => {
        mocks.submitOrQueue.mockResolvedValue(
            json({ correct: true, expected: 'x', note: null, run }),
        );
        const wrapper = mountPlay({
            plan: [listenType],
            settings: { ...props().settings, offersSlowerAudio: false },
        });

        await flushPromises();

        expect(wrapper.find('[data-testid="listen-slower"]').exists()).toBe(
            false,
        );

        await wrapper
            .findAll('button')
            .find((button) => button.text().includes('hint'))
            ?.trigger('click');

        expect(wrapper.find('[data-testid="listen-slower"]').exists()).toBe(
            true,
        );

        await wrapper.get('input').setValue('la reserva');
        await check(wrapper);

        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            hinted: true,
        });
    });

    it('is heard and answered in a check without a verdict, a hint or a skip of its probe', async () => {
        const wrapper = mountPlay({
            settings: { ...props().settings, feedback: false },
            plan: [listenType, typed],
        });

        await flushPromises();
        await wrapper.get('input').setValue('la reserva');
        await check(wrapper);

        expect(wrapper.text()).not.toContain('hint');
        expect(wrapper.find('[data-testid="feedback"]').exists()).toBe(false);
        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            hinted: false,
        });
    });

    it('shows the answer first when it comes back a third time', async () => {
        mocks.submitOrQueue.mockResolvedValue(
            json({
                correct: false,
                expected: 'La reserva es para dos noches.',
                note: null,
                run,
            }),
        );
        const wrapper = mountPlay({ plan: [listenType] });

        for (let round = 0; round < 3; round++) {
            await flushPromises();
            await wrapper.get('input').setValue('nada');
            await check(wrapper);
            await proceed(wrapper);
        }

        await flushPromises();

        expect(wrapper.get('[data-testid="study"]').text()).toContain(
            'La reserva es para dos noches.',
        );
    });
});

describe('speaking', () => {
    function scored(body: Record<string, unknown> = {}) {
        mocks.fetchJson.mockResolvedValue({
            ok: true,
            json: async () => ({
                heard: 'la llave',
                score: 100,
                correct: true,
                words: [
                    { word: 'la', verdict: 'exact' },
                    { word: 'llave', verdict: 'exact' },
                ],
                missed: 0,
                ...body,
            }),
        });
    }

    async function speak(
        wrapper: ReturnType<typeof mountPlay>,
        transcript: string,
    ) {
        await wrapper.get('[data-testid="speak-mic"]').trigger('click');
        FakeRecognition.last?.hear(transcript);
        await flushPromises();
    }

    it('scores a try, then sends the answer with every transcript and shows the verdict', async () => {
        scored();
        mocks.submitOrQueue.mockResolvedValue(
            json({
                correct: true,
                expected: 'la llave',
                note: null,
                score: 100,
                run,
                milestone: null,
            }),
        );
        const wrapper = mountPlay({ plan: [speakRepeat] });

        expect(
            wrapper
                .get('[data-testid="lesson-footer"] button')
                .attributes('disabled'),
        ).toBeDefined();

        await speak(wrapper, 'la llave');

        expect(mocks.fetchJson).toHaveBeenCalledWith(
            `/lesson-runs/${runId}/exercises/3/speaking-tries`,
            'POST',
            JSON.stringify({ transcript: 'la llave' }),
        );
        expect(wrapper.get('[data-testid="speak-score"]').text()).toContain(
            '100%',
        );

        await check(wrapper);

        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            exercise_id: 3,
            response: { transcripts: ['la llave'] },
        });
        expect(wrapper.get('[data-testid="feedback"]').text()).toContain(
            'Right',
        );
    });

    it('lets the learner try again before checking, and sends the best of the tries', async () => {
        scored({ score: 40, correct: false });
        mocks.submitOrQueue.mockResolvedValue(
            json({
                correct: true,
                expected: 'la llave',
                note: null,
                score: 100,
                run,
                milestone: null,
            }),
        );
        const wrapper = mountPlay({ plan: [speakRepeat] });

        await speak(wrapper, 'la lave');
        await speak(wrapper, 'la llave');

        expect(wrapper.get('[data-testid="speak-tries"]').text()).toBe(
            'Try 2 of 3',
        );

        await check(wrapper);

        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            response: { transcripts: ['la lave', 'la llave'] },
        });
    });

    it('shows the model answer, and plays it, only once a spoken answer is over', async () => {
        scored({ words: [{ word: 'la llave', verdict: 'exact' }] });
        mocks.submitOrQueue.mockResolvedValue(
            json({
                correct: true,
                expected: 'la llave',
                note: null,
                score: 100,
                run,
                milestone: null,
            }),
        );
        const wrapper = mountPlay({ plan: [speakAnswer] });

        expect(wrapper.text()).not.toContain('la llave');

        await speak(wrapper, 'la llave');
        await check(wrapper);

        expect(wrapper.text()).toContain('Hear it first');
    });

    it('flags a wrong spoken answer through its own url, since a recogniser can be wrong', async () => {
        scored({ score: 20, correct: false });
        mocks.submitOrQueue.mockResolvedValue(
            json({
                correct: false,
                expected: 'la llave',
                note: null,
                score: 20,
                run,
                milestone: null,
            }),
        );
        const wrapper = mountPlay({ plan: [speakRepeat] });

        await speak(wrapper, 'nada');
        await check(wrapper);

        expect(wrapper.text()).toContain('should count');
        expect(wrapper.text()).toContain('nada');
    });

    it('is swapped for its substitute in a browser that cannot recognise speech', async () => {
        vi.unstubAllGlobals();
        vi.stubGlobal('Audio', FakeAudio);
        setVoices([{ lang: 'es-ES' }]);
        delete (window as unknown as Record<string, unknown>)
            .webkitSpeechRecognition;
        const wrapper = mountPlay({ plan: [speakRepeat] });

        await vi.waitFor(() => expect(skips()).toHaveLength(1));
        await flushPromises();

        expect(skips()[0]).toMatchObject({
            exercise_id: 3,
            skip_reason: 'unsupported',
        });
        expect(wrapper.get('[data-testid="prompt"]').text()).toBe('the key');
    });

    it('is swapped while offline, because recognition needs a connection', async () => {
        sync.online.value = false;
        const wrapper = mountPlay({ plan: [speakRepeat] });

        await vi.waitFor(() => expect(skips()).toHaveLength(1));
        await flushPromises();

        expect(skips()[0]).toMatchObject({
            exercise_id: 3,
            skip_reason: 'offline',
        });
        expect(wrapper.get('[data-testid="swap-notice"]').text()).toContain(
            'offline',
        );
    });

    it('is swapped, for good, once the microphone has been refused', async () => {
        const wrapper = mountPlay({ plan: [speakRepeat, speakAnswer] });

        await wrapper.get('[data-testid="speak-mic"]').trigger('click');
        FakeRecognition.last?.onerror?.({ error: 'not-allowed' });
        await vi.waitFor(() => expect(skips()).toHaveLength(1));
        await flushPromises();

        expect(skips()[0]).toMatchObject({
            exercise_id: 3,
            skip_reason: 'unsupported',
        });

        mocks.submitOrQueue.mockResolvedValue(
            json({ correct: true, expected: 'la llave', note: null, run }),
        );
        await wrapper.get('input').setValue('la llave');
        await check(wrapper);
        await proceed(wrapper);
        await flushPromises();
        await vi.waitFor(() => expect(skips()).toHaveLength(2));

        expect(skips()[1]).toMatchObject({
            exercise_id: 4,
            skip_reason: 'unsupported',
        });
    });

    it('saves a spoken answer that could only be queued, and moves on without a verdict', async () => {
        scored();
        mocks.submitOrQueue.mockResolvedValueOnce({ queued: true });
        const wrapper = mountPlay({ plan: [speakRepeat, typed] });

        await speak(wrapper, 'la llave');
        await check(wrapper);

        expect(wrapper.find('[data-testid="feedback"]').exists()).toBe(false);
        expect(wrapper.text()).toContain('room');
    });
});

describe('skipping and pausing', () => {
    it('skips an exercise on request, for its substitute, and says nothing about it', async () => {
        const wrapper = mountPlay({ plan: [speakRepeat] });

        await wrapper.get('[data-testid="skip"]').trigger('click');
        await flushPromises();

        expect(skips()).toHaveLength(1);
        expect(skips()[0]).toMatchObject({
            exercise_id: 3,
            skip_reason: 'chosen',
        });
        expect(wrapper.get('[data-testid="prompt"]').text()).toBe('the key');
        expect(wrapper.find('[data-testid="swap-notice"]').exists()).toBe(
            false,
        );
        expect(wrapper.find('[data-testid="skip-links"]').exists()).toBe(false);
    });

    it('offers no skip on an exercise that cannot be skipped, or in its substitute', async () => {
        const wrapper = mountPlay({ plan: [typed] });

        expect(wrapper.find('[data-testid="skip-links"]').exists()).toBe(false);

        const skipped = mountPlay({
            plan: [speakRepeat],
            answers: [
                {
                    step: 'a',
                    exerciseId: 3,
                    attempt: 1,
                    hinted: false,
                    skipped: true,
                    skipReason: 'chosen',
                    correct: null,
                    flagged: false,
                    settled: false,
                },
            ],
        });

        await flushPromises();

        expect(skipped.find('[data-testid="skip-links"]').exists()).toBe(false);
    });

    it('pauses a family from the skip links, swaps the exercise at once and says so', async () => {
        mocks.submitOrQueue.mockImplementation(async (url: string) =>
            url.startsWith('/exercise-pauses')
                ? json({
                      family: 'listening',
                      until: new Date(Date.now() + 3600000).toISOString(),
                  })
                : json({ saved: true, run }),
        );
        const wrapper = mountPlay({ plan: [listenChoose, typed] });

        await flushPromises();
        await wrapper.get('[data-testid="skip-family"]').trigger('click');
        await flushPromises();

        const pause = mocks.submitOrQueue.mock.calls.find(([url]) =>
            url.startsWith('/exercise-pauses'),
        );

        expect(pause).toEqual(['/exercise-pauses/listening', { minutes: 60 }]);
        expect(skips()[0]).toMatchObject({
            exercise_id: 1,
            skip_reason: 'paused',
        });
        expect(
            wrapper.get('[data-testid="paused-listening"]').text(),
        ).toContain('Listening is off until');
        expect(wrapper.get('[data-testid="swap-notice"]').text()).toContain(
            'You paused this',
        );
    });

    it('swaps every listening exercise it reaches while a pause lasts, and turns back on', async () => {
        mocks.submitOrQueue.mockImplementation(async (url: string) =>
            url.startsWith('/exercise-pauses')
                ? json({ family: 'listening', until: null })
                : json({ saved: true, run }),
        );
        const second = {
            ...listenChoose,
            id: 21,
            substitute: { ...listenChoose.substitute!, id: 31 },
        };
        const wrapper = mountPlay({
            plan: [listenChoose, second],
            settings: {
                ...props().settings,
                pauses: {
                    listening: new Date(Date.now() + 1800000).toISOString(),
                    speaking: null,
                },
            },
        });

        await vi.waitFor(() => expect(skips()).toHaveLength(1));
        await flushPromises();
        await wrapper.findAll('[role="radio"]')[0].trigger('click');
        await check(wrapper);
        await proceed(wrapper);
        await vi.waitFor(() => expect(skips()).toHaveLength(2));

        expect(skips().map((body) => body.skip_reason)).toEqual([
            'paused',
            'paused',
        ]);

        await wrapper
            .get('[data-testid="paused-listening"] button')
            .trigger('click');
        await flushPromises();

        expect(
            mocks.submitOrQueue.mock.calls.find(([url]) =>
                url.startsWith('/exercise-pauses'),
            ),
        ).toEqual(['/exercise-pauses/listening', { minutes: 0 }]);
        expect(wrapper.find('[data-testid="paused-listening"]').exists()).toBe(
            false,
        );
    });

    it('keeps pausing speaking separate from listening', async () => {
        mocks.submitOrQueue.mockImplementation(async (url: string) =>
            url.startsWith('/exercise-pauses')
                ? json({
                      family: 'speaking',
                      until: new Date(Date.now() + 3600000).toISOString(),
                  })
                : json({ saved: true, run }),
        );
        const wrapper = mountPlay({ plan: [speakRepeat, listenChoose] });

        await wrapper.get('[data-testid="pause-speaking"]').trigger('click');
        await flushPromises();
        await vi.waitFor(() => expect(skips()).toHaveLength(1));

        expect(skips()[0]).toMatchObject({
            exercise_id: 3,
            skip_reason: 'paused',
        });
        expect(wrapper.find('[data-testid="paused-listening"]').exists()).toBe(
            false,
        );
        expect(wrapper.get('[data-testid="paused-speaking"]').text()).toContain(
            'Speaking is off until',
        );
    });

    it('cleans the swap notice off once the exercise that was swapped is done', async () => {
        const wrapper = mountPlay({ plan: [speakRepeat, typed] });

        await wrapper.get('[data-testid="pause-speaking"]').trigger('click');
        await flushPromises();
        await wrapper.get('input').setValue('la llave');
        mocks.submitOrQueue.mockResolvedValue(
            json({
                correct: true,
                expected: 'la llave',
                note: null,
                run,
                milestone: null,
            }),
        );
        await check(wrapper);
        await proceed(wrapper);
        await nextTick();

        expect(wrapper.find('[data-testid="swap-notice"]').exists()).toBe(
            false,
        );
    });
});
