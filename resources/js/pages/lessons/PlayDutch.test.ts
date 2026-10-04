import 'fake-indexeddb/auto';
import { flushPromises, mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { ref } from 'vue';
import { setLocale } from '@/i18n';
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
    substitute: {
        id: 11,
        key: 's11',
        block: 'b',
        format: 'choose_meaning',
        payload: {
            prompt: 'la llave',
            options: ['key', 'room'],
            answer: 'key',
        },
    },
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
    substitute: {
        id: 13,
        key: 's13',
        block: 'b',
        format: 'type_word',
        payload: { prompt: 'the key', accepted: ['la llave'] },
    },
};

const typed: PlanExercise = {
    id: 5,
    key: 't5',
    block: 'b',
    format: 'type_word',
    payload: {
        prompt: 'key',
        english: 'key',
        hint: 'la  l _ _ _ _',
        accepted: ['la llave'],
    },
    origin: 'lesson',
    substitute: null,
};

const choose: PlanExercise = {
    id: 6,
    key: 'c6',
    block: 'b',
    format: 'choose_word',
    payload: {
        prompt: 'key',
        options: ['la llave', 'la hora'],
        answer: 'la llave',
    },
    origin: 'lesson',
    substitute: null,
};

const run = {
    completed: false,
    unitCompleted: false,
    mastery: { mastered: 0, total: 10 },
};
let runId = 900;

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
            hintsAreFree: true,
            audioSpeed: 1,
            replayLimit: 3,
            offersSlowerAudio: true,
            speechLocale: 'es-ES',
            pauses: { listening: null, speaking: null },
        },
        plan: [choose],
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
    setLocale('nl');
});

describe('the player in Dutch', () => {
    it('shows the header, the instruction with the language name and the check button in Dutch', async () => {
        const wrapper = mountPlay();

        expect(
            wrapper
                .get('[data-testid="lesson-header"] a')
                .attributes('aria-label'),
        ).toBe('De les verlaten');
        expect(wrapper.text()).toContain('Kies het woord in het Spaans');
        expect(wrapper.get('[data-testid="lesson-footer"] button').text()).toBe(
            'Controleren',
        );
    });

    it('shows the wrong verdict with the correct answer and the continue button in Dutch', async () => {
        mocks.submitOrQueue.mockResolvedValue(json({ saved: true, run }));
        const wrapper = mountPlay();

        await wrapper.findAll('[role="radio"]')[1].trigger('click');
        await check(wrapper);
        const feedback = wrapper.get('[data-testid="feedback"]').text();

        expect(feedback).toContain('Nog niet helemaal');
        expect(feedback).toContain('Goed antwoord: la llave');
        expect(feedback).toContain('Jouw antwoord:');
        expect(
            wrapper
                .get('[data-testid="lesson-footer"] button:last-of-type')
                .text(),
        ).toBe('Doorgaan');
    });

    it.each([
        ['accent', 'Let op het accent: la llave'],
        ['article', 'Controleer het lidwoord: la llave'],
        ['other_word', 'Dat is een ander woord. Controleer het accent.'],
        ['portunol', 'Dat is Spaans, geen Portugees.'],
    ])('explains the %s note in Dutch', async (note, sentence) => {
        mocks.submitOrQueue.mockResolvedValue(
            json({ correct: false, expected: 'la llave', note, run }),
        );
        const wrapper = mountPlay({ plan: [typed] });

        await wrapper.get('input').setValue('la llavé');
        await check(wrapper);

        expect(wrapper.get('[data-testid="feedback"]').text()).toContain(
            sentence,
        );
    });

    it('says thanks in Dutch once an answer is flagged', async () => {
        mocks.submitOrQueue.mockResolvedValueOnce(
            json({ correct: false, expected: 'la llave', note: null, run }),
        );
        mocks.submitOrQueue.mockResolvedValueOnce(json({ flagged: true, run }));
        const wrapper = mountPlay({ plan: [typed] });

        await wrapper.get('input').setValue('zzz');
        await check(wrapper);
        await wrapper
            .findAll('button')
            .find((button) => button.text() === 'Mijn antwoord moet meetellen')
            ?.trigger('click');
        await flushPromises();

        expect(wrapper.text()).toContain('Bedankt, we kijken ernaar');
    });

    it('offers the hint in Dutch and labels the answer field and accent keys', async () => {
        const wrapper = mountPlay({ plan: [typed] });

        await wrapper
            .findAll('button')
            .find((button) => button.text() === 'Toon een hint')
            ?.trigger('click');

        expect(wrapper.get('[data-testid="hint"]').text()).toBe(
            'Begint met "l", 7 letters',
        );
        expect(wrapper.find('[aria-label="Jouw antwoord"]').exists()).toBe(
            true,
        );
        expect(wrapper.find('[aria-label="Accenttoetsen"]').exists()).toBe(
            true,
        );
    });

    it('falls back to a self-check in Dutch when an answer is queued offline', async () => {
        mocks.submitOrQueue.mockResolvedValue({ queued: true });
        const wrapper = mountPlay({ plan: [typed] });

        await wrapper.get('input').setValue('la llave');
        await check(wrapper);
        const footer = wrapper.get('[data-testid="lesson-footer"]').text();

        expect(footer).toContain(
            'Je bent offline, dus dit antwoord wordt beoordeeld zodra het is gesynchroniseerd. Het antwoord is la llave. Had je het goed?',
        );
        expect(footer).toContain('Nee');
        expect(footer).toContain('Ja');
    });

    it('shows the save error in Dutch with a retry', async () => {
        mocks.submitOrQueue.mockResolvedValueOnce({
            queued: false,
            response: { ok: false, json: async () => ({}) },
        });
        const wrapper = mountPlay();

        await wrapper.findAll('[role="radio"]')[0].trigger('click');
        await check(wrapper);

        expect(wrapper.get('[role="alert"]').text()).toBe(
            'Dit kon niet worden opgeslagen. Probeer het opnieuw.',
        );
        expect(wrapper.get('[data-testid="lesson-footer"] button').text()).toBe(
            'Opnieuw proberen',
        );
    });

    it('says in Dutch that answers are saved on the device while offline', async () => {
        sync.online.value = false;
        sync.pending.value = 1;
        const wrapper = mountPlay();

        await wrapper.findAll('[role="radio"]')[0].trigger('click');
        await check(wrapper);
        await wrapper
            .get('[data-testid="lesson-footer"] button:last-of-type')
            .trigger('click');
        await flushPromises();

        expect(wrapper.get('[data-testid="finishing"]').text()).toContain(
            'Alle antwoorden zijn op dit toestel opgeslagen.',
        );
        expect(wrapper.get('[data-testid="finishing"]').text()).toContain(
            'Je resultaten verschijnen zodra je weer online bent.',
        );
    });

    it('says in Dutch that it is finishing up while online', async () => {
        const wrapper = mountPlay();

        await wrapper.findAll('[role="radio"]')[0].trigger('click');
        await check(wrapper);
        await wrapper
            .get('[data-testid="lesson-footer"] button:last-of-type')
            .trigger('click');
        await flushPromises();

        expect(wrapper.get('[data-testid="finishing"]').text()).toBe(
            'Even afronden',
        );
    });
});

describe('listening and speaking in Dutch', () => {
    it('labels the listen controls, the replay count and the skip links in Dutch', async () => {
        const wrapper = mountPlay({ plan: [listenChoose, typed] });

        await flushPromises();

        expect(
            wrapper.get('[data-testid="listen-play"]').attributes('aria-label'),
        ).toMatch(/Afspelen|Opnieuw afspelen/);
        expect(wrapper.get('[data-testid="listen-slower"]').text()).toBe(
            'Langzamer',
        );
        expect(wrapper.get('[data-testid="listen-left"]').text()).toMatch(
            /^Nog \d+ herhalingen$/,
        );
        expect(wrapper.get('[data-testid="skip"]').text()).toBe('Overslaan');
        expect(wrapper.get('[data-testid="skip-family"]').text()).toBe(
            'Ik kan nu niet luisteren',
        );
        expect(wrapper.text()).toContain('Luister en kies de betekenis');
    });

    it('shows the pause banner with a local time and the swap notice in Dutch', async () => {
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

        expect(wrapper.get('[data-testid="paused-listening"]').text()).toMatch(
            /^Luisteren staat uit tot \d{2}:\d{2}\s*Zet het weer aan$/,
        );
        expect(wrapper.get('[data-testid="swap-notice"]').text()).toBe(
            'Je hebt dit gepauzeerd, dus je krijgt een andere oefening met dezelfde woorden.',
        );
    });

    it('shows the speaking exercise in Dutch', async () => {
        vi.stubGlobal('webkitSpeechRecognition', class {});
        const wrapper = mountPlay({ plan: [speakRepeat, typed] });

        expect(wrapper.get('[data-testid="speak-exercise"]').text()).toContain(
            'Zeg het hardop',
        );
        expect(wrapper.text()).toContain('Eerst luisteren');
        expect(wrapper.text()).toContain('Tik en spreek');
        expect(wrapper.get('[data-testid="skip-family"]').text()).toBe(
            'Ik kan nu niet praten',
        );
        expect(wrapper.get('[data-testid="prompt"]').attributes('lang')).toBe(
            'es-ES',
        );
    });
});

describe('the summary and the remediation in Dutch', () => {
    const summary = {
        accuracy: { choice: 0.5, listening: 1 },
        retried: ['la llave'],
        items: [
            { term: 'el hotel', translation: 'hotel', mastered: true },
            { term: 'la llave', translation: 'key', mastered: false },
        ],
        answers: [
            { prompt: 'key', given: '', expected: 'la llave', correct: false },
        ],
        cardsEnrolled: 2,
        unitCompleted: false,
    };

    function mountSummary(settings = {}, overrides = {}) {
        return mountPlay({
            settings: { ...props().settings, ...settings },
            run: {
                ...props().run,
                status: 'completed',
                summary,
                next: {
                    lessonId: 10,
                    title: 'Recall the words',
                    position: 4,
                    stage: 'check',
                    state: 'available',
                },
                remediation: {
                    lessonId: 11,
                    missing: 1,
                    retake: 'opens_tomorrow',
                },
                ...overrides,
            },
        });
    }

    it('summarises a check in Dutch, with the plural cards and the remediation', async () => {
        const wrapper = mountSummary({ feedback: false });
        const text = wrapper.get('[data-testid="summary"]').text();

        expect(text).toContain('Toets afgerond');
        expect(text).toContain('Meteen goed');
        expect(text).toContain('Meerkeuze');
        expect(text).toContain('Luisteren');
        expect(text).toContain('Had een tweede poging nodig');
        expect(text).toContain('Bewezen (1)');
        expect(text).toContain('Nog niet bewezen (1)');
        expect(text).toContain('Elk antwoord');
        expect(text).toContain('Geen antwoord');
        expect(text).toContain('Goed: la llave');
        expect(text).toContain(
            '2 kaarten zijn toegevoegd aan je herhaalstapel.',
        );
        expect(text).toContain('Doe de eenheidstoets');
        expect(text).toContain('Terug naar de eenheid');
        expect(wrapper.get('[data-testid="remediation"]').text()).toContain(
            '1 item nog niet bewezen',
        );
        expect(wrapper.get('[data-testid="remediation"]').text()).toContain(
            'Opnieuw doen kan vanaf morgen',
        );
    });

    it('uses the singular card, the lesson title and the unit headings in Dutch', async () => {
        const wrapper = mountSummary(
            { feedback: false },
            {
                summary: { ...summary, cardsEnrolled: 1, unitCompleted: true },
                next: {
                    lessonId: 10,
                    title: 'Recall the words',
                    position: 4,
                    stage: 'recall',
                    state: 'available',
                },
            },
        );
        const text = wrapper.get('[data-testid="summary"]').text();

        expect(text).toContain('Eenheid voltooid');
        expect(text).toContain('Elk woord en het grammaticapunt zijn bewezen.');
        expect(text).toContain('1 kaart is toegevoegd aan je herhaalstapel.');
        expect(text).toContain('Volgende les: Recall the words');
    });

    it('says in Dutch that the check opens tomorrow and names a finished lesson', async () => {
        const wrapper = mountSummary(
            {},
            {
                next: {
                    lessonId: 10,
                    title: 'Check',
                    position: 5,
                    stage: 'check',
                    state: 'opens_tomorrow',
                },
            },
        );
        const text = wrapper.get('[data-testid="summary"]').text();

        expect(text).toContain('Les voltooid');
        expect(text).toContain(
            'De eenheidstoets opent morgen, zodat wat je hebt geleerd de tijd krijgt om te beklijven.',
        );
    });
});
