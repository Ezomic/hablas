import 'fake-indexeddb/auto';
import { flushPromises, mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { ref } from 'vue';
import { FakeAudio } from '@/test/fakeAudio';
import type { PlanExercise, PlayProps } from '@/types/lesson';

const mocks = vi.hoisted(() => ({
    owner: vi.fn(),
    submitOrQueue: vi.fn(),
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

const exercise = (
    id: number,
    format: string,
    payload: Record<string, unknown>,
    substitute: PlanExercise['substitute'] = null,
): PlanExercise => ({
    id,
    key: `k${id}`,
    block: 'b',
    format,
    payload,
    origin: 'lesson',
    substitute,
});

const run = {
    completed: false,
    unitCompleted: false,
    mastery: { mastered: 0, total: 10 },
};

let runId = 700;

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
        plan: [],
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

function checkButton(wrapper: ReturnType<typeof mountPlay>) {
    return wrapper.get('[data-testid="lesson-footer"] button');
}

function verdict(overrides: Record<string, unknown> = {}) {
    return json({
        correct: true,
        expected: null,
        note: null,
        score: null,
        run,
        milestone: null,
        ...overrides,
    });
}

beforeEach(() => {
    runId++;
    vi.resetAllMocks();
    vi.unstubAllGlobals();
    FakeAudio.reset();
    vi.stubGlobal('Audio', FakeAudio);
    vi.stubGlobal('SpeechSynthesisUtterance', class {});
    vi.stubGlobal('speechSynthesis', {
        speak: vi.fn(),
        cancel: vi.fn(),
        getVoices: () => [{ lang: 'es-ES' }],
        addEventListener: vi.fn(),
        removeEventListener: vi.fn(),
    });
    sync.online = ref(true) as unknown as { value: boolean };
    sync.pending = ref(0) as unknown as { value: number };
    mocks.submitOrQueue.mockResolvedValue(json({ saved: true, run }));
    document.body.innerHTML = '';
});

const build = exercise(1, 'build_sentence', {
    prompt: 'The key is in the room.',
    english: 'The key is in the room.',
    tiles: ['en', 'la', 'llave', 'está', 'la', 'habitación', 'tiene'],
    accepted: ['la llave está en la habitación'],
    why: 'Location takes estar',
    glosses: { llave: 'key' },
});

describe('build_sentence', () => {
    it('builds the answer from tapped tiles, sends it as text and shows the verdict and why', async () => {
        mocks.submitOrQueue.mockResolvedValue(
            verdict({
                correct: false,
                expected: 'la llave está en la habitación',
            }),
        );
        const wrapper = mountPlay({ plan: [build] });

        expect(wrapper.text()).toContain('Tap the words to build the sentence');
        expect(wrapper.find('[data-testid="english"]').exists()).toBe(false);
        expect(wrapper.get('[data-testid="glosses"]').text()).toBe(
            'llave: key',
        );
        expect(checkButton(wrapper).attributes('disabled')).toBeDefined();
        expect(wrapper.text()).not.toContain('Show a hint');

        for (const word of ['la', 'llave', 'está']) {
            await wrapper
                .findAll('[data-testid="tile"]')
                .find((tile) => tile.text() === word)
                ?.trigger('click');
        }

        expect(checkButton(wrapper).attributes('disabled')).toBeUndefined();

        await check(wrapper);

        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            response: { text: 'la llave está' },
        });
        expect(wrapper.get('[data-testid="feedback"]').text()).toContain(
            'Correct answer: la llave está en la habitación',
        );
        expect(wrapper.get('[data-testid="why"]').text()).toBe(
            'Location takes estar',
        );
        expect(
            wrapper.findAll('[data-testid="tile"]')[0].attributes('disabled'),
        ).toBeDefined();
    });

    it('falls back to a self-check offline using the first accepted sentence', async () => {
        mocks.submitOrQueue.mockResolvedValue({ queued: true });
        const wrapper = mountPlay({ plan: [build] });

        await wrapper.get('[data-testid="tile"]').trigger('click');
        await check(wrapper);

        expect(wrapper.text()).toContain('Did you have it?');
        expect(wrapper.text()).toContain('la llave está en la habitación');
    });

    it('is answered in a check without a verdict', async () => {
        const wrapper = mountPlay({
            settings: { ...props().settings, feedback: false },
            plan: [
                exercise(1, 'build_sentence', {
                    prompt: 'The key is in the room.',
                    tiles: ['la', 'llave'],
                }),
            ],
        });

        await wrapper.get('[data-testid="tile"]').trigger('click');
        await check(wrapper);

        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            response: { text: 'la' },
            hinted: false,
        });
        expect(wrapper.find('[data-testid="feedback"]').exists()).toBe(false);
    });
});

describe('type_gap', () => {
    const gap = exercise(2, 'type_gap', {
        prompt: 'La llave ___ en la habitación.',
        english: 'The key is in the room.',
        accepted: ['está'],
    });

    it('types the answer inline, submits on Enter and shows the English line', async () => {
        mocks.submitOrQueue.mockResolvedValue(verdict({ expected: 'está' }));
        const wrapper = mountPlay({ plan: [gap] });

        expect(wrapper.find('[data-testid="prompt"] input').exists()).toBe(
            true,
        );
        expect(wrapper.get('[data-testid="english"]').text()).toBe(
            'The key is in the room.',
        );

        await wrapper.get('[data-testid="gap-input"]').setValue('está');
        await wrapper
            .get('[data-testid="gap-input"]')
            .trigger('keydown', { key: 'Enter' });
        await flushPromises();

        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            response: { text: 'está' },
        });
        expect(wrapper.get('[data-testid="feedback"]').text()).toContain(
            'Right',
        );
    });

    it('uses the plain layout when the prompt has no gap', () => {
        const wrapper = mountPlay({
            plan: [
                exercise(2, 'type_gap', {
                    prompt: 'La llave',
                    accepted: ['x'],
                }),
            ],
        });

        expect(wrapper.find('[data-testid="gap-input"]').exists()).toBe(false);
        expect(wrapper.findAll('input')).toHaveLength(1);
    });
});

describe('transform_sentence', () => {
    it('shows the instruction and the source sentence and sends the typed text', async () => {
        const wrapper = mountPlay({
            plan: [
                exercise(3, 'transform_sentence', {
                    prompt: 'Make it plural.',
                    source: 'La habitación está disponible.',
                    accepted: ['Las habitaciones están disponibles.'],
                }),
            ],
        });

        expect(wrapper.text()).toContain('Make it plural.');
        expect(wrapper.get('[data-testid="prompt"]').text()).toBe(
            'La habitación está disponible.',
        );

        await wrapper
            .get('input')
            .setValue('Las habitaciones están disponibles.');
        await check(wrapper);

        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            response: { text: 'Las habitaciones están disponibles.' },
        });
    });
});

describe('write_guided', () => {
    const guided = exercise(4, 'write_guided', {
        prompt: 'Say hello and ask for a room.',
        chips: ['hola', 'habitación'],
        required: ['hola', 'habitación'],
    });

    it('shows chips and a textarea, does not submit on Enter, and shows the details and model answer', async () => {
        mocks.submitOrQueue.mockResolvedValue(
            verdict({
                correct: false,
                expected: 'Hola, quiero una habitación.',
                details: { found: ['hola'], missing: ['habitación'] },
            }),
        );
        const wrapper = mountPlay({ plan: [guided] });

        expect(wrapper.text()).not.toContain('Use these words');
        expect(wrapper.text()).toContain(
            'Write it in the language you are learning',
        );

        await wrapper
            .findAll('button')
            .find((button) => button.text() === 'Show a hint')!
            .trigger('click');

        expect(wrapper.text()).toContain('Use these words');

        await wrapper.get('textarea').setValue('hola');
        wrapper
            .get('textarea')
            .element.dispatchEvent(
                new KeyboardEvent('keydown', { key: 'Enter', bubbles: true }),
            );
        await flushPromises();

        expect(mocks.submitOrQueue).not.toHaveBeenCalled();

        await check(wrapper);

        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            response: { text: 'hola' },
        });
        const feedback = wrapper.get('[data-testid="feedback"]').text();

        expect(feedback).toContain('Not quite');
        expect(feedback).toContain('Used: hola');
        expect(feedback).toContain('Still missing: habitación');
        expect(feedback).toContain(
            'Model answer: Hola, quiero una habitación.',
        );
    });

    it('shows the model answer on a right answer too', async () => {
        mocks.submitOrQueue.mockResolvedValue(
            verdict({
                expected: 'Hola, quiero una habitación.',
                details: { found: ['hola', 'habitación'], missing: [] },
            }),
        );
        const wrapper = mountPlay({ plan: [guided] });

        await wrapper.get('textarea').setValue('hola habitación');
        await check(wrapper);

        const feedback = wrapper.get('[data-testid="feedback"]').text();

        expect(feedback).toContain('Right');
        expect(feedback).toContain('Model answer');
        expect(feedback).not.toContain('Still missing');
    });

    it('is queued and settled without a verdict when offline', async () => {
        mocks.submitOrQueue.mockResolvedValue({ queued: true });
        const wrapper = mountPlay({ plan: [guided] });

        await wrapper.get('textarea').setValue('hola');
        await check(wrapper);

        expect(wrapper.text()).not.toContain('Did you have it?');
        expect(wrapper.find('[data-testid="feedback"]').exists()).toBe(false);
    });

    it('takes a Spanish prompt with no chips', () => {
        const wrapper = mountPlay({
            plan: [
                exercise(5, 'write_guided', {
                    prompt: 'Saluda a la recepcionista.',
                }),
            ],
        });

        expect(wrapper.find('[data-testid="chips"]').exists()).toBe(false);
        expect(wrapper.get('[data-testid="prompt"]').text()).toBe(
            'Saluda a la recepcionista.',
        );
    });
});

describe('choose_gap', () => {
    it('shows the English line muted under the prompt', () => {
        const wrapper = mountPlay({
            plan: [
                exercise(6, 'choose_gap', {
                    prompt: 'La llave ___ en la habitación.',
                    english: 'The key is in the room.',
                    options: ['está', 'es'],
                    answer: 'está',
                }),
            ],
        });

        expect(wrapper.get('[data-testid="english"]').text()).toBe(
            'The key is in the room.',
        );
    });
});

const read = exercise(7, 'read_passage', {
    dialogue: [
        { speaker: 'Ana', text: 'Buenas tardes.' },
        { speaker: 'Luis', text: 'Hola, tengo una reserva.' },
    ],
    questions: [
        {
            prompt: 'Who has a booking?',
            options: ['Ana', 'Luis'],
            answer: 'Luis',
        },
        {
            prompt: 'When?',
            options: ['morning', 'afternoon'],
            answer: 'afternoon',
        },
    ],
    glosses: { reserva: 'booking' },
    why: 'Luis speaks second',
});

describe('read_passage', () => {
    it('needs every question answered, grades on the device and colours the options', async () => {
        const wrapper = mountPlay({ plan: [read] });
        const radios = () => wrapper.findAll('[role="radio"]');

        expect(wrapper.text()).toContain('Read the dialogue');
        expect(wrapper.get('[data-testid="dialogue"]').text()).toContain(
            'Luis: Hola, tengo una reserva.',
        );
        expect(wrapper.get('[data-testid="glosses"]').text()).toBe(
            'reserva: booking',
        );

        await radios()[1].trigger('click');

        expect(checkButton(wrapper).attributes('disabled')).toBeDefined();

        await radios()[2].trigger('click');

        expect(checkButton(wrapper).attributes('disabled')).toBeUndefined();

        await check(wrapper);

        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            response: { choices: ['Luis', 'morning'] },
        });
        const feedback = wrapper.get('[data-testid="feedback"]').text();

        expect(feedback).toContain('Not quite');
        expect(feedback).toContain('Correct answer: Luis / afternoon');
        expect(wrapper.get('[data-testid="why"]').text()).toBe(
            'Luis speaks second',
        );
        const classes = radios().map((button) => button.classes().join(' '));

        expect(classes[1]).toContain('border-green-600');
        expect(classes[2]).toContain('border-red-600');
        expect(classes[3]).toContain('border-green-600');
    });

    it('is right only when every choice is right', async () => {
        const wrapper = mountPlay({ plan: [read] });

        await wrapper.findAll('[role="radio"]')[1].trigger('click');
        await wrapper.findAll('[role="radio"]')[3].trigger('click');
        await check(wrapper);

        expect(wrapper.get('[data-testid="feedback"]').text()).toContain(
            'Right',
        );
        expect(wrapper.find('[data-testid="why"]').exists()).toBe(false);
    });

    it('shows no dialogue answers or verdict in a check, and sends the choices', async () => {
        const stripped = exercise(7, 'read_passage', {
            dialogue: [{ speaker: 'Ana', text: 'Buenas tardes.' }],
            questions: [
                { prompt: 'Who?', options: ['Ana', 'Luis'] },
                { prompt: 'When?', options: ['morning', 'afternoon'] },
            ],
        });
        const wrapper = mountPlay({
            settings: { ...props().settings, feedback: false },
            plan: [stripped],
        });

        await wrapper.findAll('[role="radio"]')[0].trigger('click');
        await wrapper.findAll('[role="radio"]')[3].trigger('click');
        await check(wrapper);

        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            response: { choices: ['Ana', 'afternoon'] },
        });
        expect(wrapper.find('[data-testid="feedback"]').exists()).toBe(false);
        expect(
            wrapper
                .findAll('[role="radio"]')
                .some((button) =>
                    button.classes().includes('border-green-600'),
                ),
        ).toBe(false);
    });
});

describe('listen_passage', () => {
    const lines = [
        {
            speaker: 'Ana',
            text: 'Hola.',
            audioUrl: '/a.mp3',
            audioSlowUrl: null,
        },
        {
            speaker: 'Luis',
            text: 'Buenas.',
            audioUrl: '/b.mp3',
            audioSlowUrl: null,
        },
    ];
    const questions = [
        {
            prompt: 'Who greets first?',
            options: ['Ana', 'Luis'],
            answer: 'Ana',
        },
    ];
    const substitute = {
        id: 21,
        key: 's21',
        block: 'b',
        format: 'read_passage',
        payload: {
            dialogue: [{ speaker: 'Ana', text: 'Hola.' }],
            questions,
        },
    };

    async function finishLine(index: number) {
        FakeAudio.instances[index].onplaying?.();
        await flushPromises();
        FakeAudio.instances[index].onended?.();
        await flushPromises();
    }

    it('plays the lines on a tap, hides the text until the answer is checked, then shows it', async () => {
        const wrapper = mountPlay({
            plan: [
                exercise(8, 'listen_passage', { lines, questions }, substitute),
            ],
        });

        expect(wrapper.text()).toContain('Listen to the dialogue');
        expect(wrapper.find('[data-testid="dialogue"]').exists()).toBe(false);

        await wrapper.get('[data-testid="listen-play"]').trigger('click');
        await finishLine(0);
        await finishLine(1);

        expect(FakeAudio.instances.map((audio) => audio.src)).toEqual([
            '/a.mp3',
            '/b.mp3',
        ]);
        expect(wrapper.find('[data-testid="dialogue"]').exists()).toBe(false);

        await wrapper.findAll('[role="radio"]')[0].trigger('click');
        await check(wrapper);

        expect(wrapper.get('[data-testid="feedback"]').text()).toContain(
            'Right',
        );
        expect(wrapper.get('[data-testid="dialogue"]').text()).toContain(
            'Ana: Hola.',
        );
        expect(wrapper.find('[data-testid="skip"]').exists()).toBe(false);
    });

    it('offers the skip link, and the substitute takes its place', async () => {
        const wrapper = mountPlay({
            plan: [
                exercise(8, 'listen_passage', { lines, questions }, substitute),
            ],
        });

        await wrapper.get('[data-testid="skip"]').trigger('click');
        await flushPromises();

        expect(
            wrapper.find('[data-testid="listen-passage-player"]').exists(),
        ).toBe(false);
        expect(wrapper.get('[data-testid="dialogue"]').text()).toContain(
            'Ana: Hola.',
        );
        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            skipped: true,
            skip_reason: 'chosen',
        });
    });

    it('is swapped for its substitute when no line can be played', async () => {
        const silent = lines.map((line) => ({
            ...line,
            text: '',
            audioUrl: null,
        }));
        const wrapper = mountPlay({
            plan: [
                exercise(
                    8,
                    'listen_passage',
                    { lines: silent, questions },
                    substitute,
                ),
            ],
        });

        await flushPromises();

        expect(
            wrapper.find('[data-testid="listen-passage-player"]').exists(),
        ).toBe(false);
        expect(wrapper.get('[data-testid="swap-notice"]').text()).toContain(
            "can't play this one",
        );
        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            skipped: true,
            skip_reason: 'unsupported',
        });
    });

    it('is swapped while listening is paused', async () => {
        const wrapper = mountPlay({
            settings: {
                ...props().settings,
                pauses: { listening: '2099-01-01T00:00:00Z', speaking: null },
            },
            plan: [
                exercise(8, 'listen_passage', { lines, questions }, substitute),
            ],
        });

        await flushPromises();

        expect(wrapper.get('[data-testid="swap-notice"]').text()).toContain(
            'You paused this',
        );
    });

    it('never shows text in a check, and sends the choices', async () => {
        const wrapper = mountPlay({
            settings: { ...props().settings, feedback: false },
            plan: [
                exercise(8, 'listen_passage', {
                    lines: lines.map((line) => ({ ...line, text: undefined })),
                    questions: [{ prompt: 'Who?', options: ['Ana', 'Luis'] }],
                }),
            ],
        });

        await wrapper.findAll('[role="radio"]')[1].trigger('click');
        await check(wrapper);

        expect(mocks.submitOrQueue.mock.calls[0][1]).toMatchObject({
            response: { choices: ['Luis'] },
        });
        expect(wrapper.find('[data-testid="dialogue"]').exists()).toBe(false);
        expect(wrapper.find('[data-testid="feedback"]').exists()).toBe(false);
    });
});

describe('teach_word', () => {
    const word = exercise(1, 'teach_word', {
        term: 'el recepcionista',
        translation: 'receptionist',
        part_of_speech: 'noun',
        is_cognate: true,
        number: 2,
        of: 4,
    });

    it('asks for the word to be typed before it can be continued', async () => {
        const wrapper = mountPlay({ plan: [word] });

        expect(wrapper.text()).toContain('New word 2 of 4');
        expect(checkButton(wrapper).attributes('disabled')).toBeDefined();
        expect(
            wrapper.get('[data-testid="teach-gem"]').attributes('data-lit'),
        ).toBe('false');

        await wrapper.get('input').setValue('el recepcio');
        expect(checkButton(wrapper).attributes('disabled')).toBeDefined();
        expect(wrapper.find('[data-testid="copy-miss"]').exists()).toBe(false);

        await wrapper.get('input').setValue('el recepcionisto');
        expect(wrapper.find('[data-testid="copy-miss"]').exists()).toBe(true);
        expect(checkButton(wrapper).attributes('disabled')).toBeDefined();
    });

    it('accepts the word without its accents or capitals, lights the gem and submits', async () => {
        const accent = exercise(1, 'teach_word', {
            term: 'la habitación',
            translation: 'room',
            number: 1,
            of: 3,
        });
        const wrapper = mountPlay({ plan: [accent] });

        await wrapper.get('input').setValue('La Habitacion');

        expect(
            wrapper.get('[data-testid="teach-gem"]').attributes('data-lit'),
        ).toBe('true');
        expect(checkButton(wrapper).attributes('disabled')).toBeUndefined();

        await check(wrapper);

        expect(mocks.submitOrQueue).toHaveBeenCalledTimes(1);
    });

    it('plays the word as the card arrives', () => {
        const wrapper = mountPlay({
            plan: [
                { ...word, payload: { ...word.payload, audioUrl: '/n.mp3' } },
            ],
        });

        expect(wrapper.exists()).toBe(true);
        expect(FakeAudio.instances.length).toBeGreaterThan(0);
    });
});
