import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { reactive } from 'vue';
import Show from './Show.vue';

const { forms } = vi.hoisted(() => ({
    forms: [] as Record<string, unknown>[],
}));

const router = vi.hoisted(() => ({ post: vi.fn() }));

vi.mock('@/routes/lessons/runs', () => ({
    store: (args: { unit: number; lesson: number }) => ({
        url: `/units/${args.unit}/lessons/${args.lesson}/runs`,
    }),
}));

vi.mock('@inertiajs/vue3', () => ({
    router,
    Head: { render: () => null },
    Link: { props: ['href'], template: '<a :href="href"><slot /></a>' },
    setLayoutProps: vi.fn(),
    useForm: () => {
        const form = reactive({ processing: false, post: vi.fn() });
        forms.push(form);

        return form;
    },
}));

vi.mock('@/routes/review', () => ({
    index: () => ({ url: '/review', method: 'get' }),
}));

vi.mock('@/routes/units', () => ({
    index: () => ({ url: '/units', method: 'get' }),
}));

vi.mock('@/routes/units/completion', () => ({
    store: (unitId: number) => ({ url: `/units/${unitId}/completion` }),
}));

const unit = {
    id: 4,
    title: 'Ordering coffee',
    taskDescription: 'Order a drink and pay for it.',
    cefrLevel: 'A1',
    primarySkill: 'speaking',
    contrastNote: null as string | null,
};

const vocabularyItems = [
    {
        id: 1,
        term: 'el café',
        translation: 'the coffee',
        partOfSpeech: 'noun',
        isCognate: true,
        contrastNote: null as string | null,
        audioUrl: null,
        audioSlowUrl: null,
    },
    {
        id: 2,
        term: 'la cuenta',
        translation: 'the bill',
        partOfSpeech: 'noun',
        isCognate: false,
        contrastNote: 'Not the same as "cuento".',
        audioUrl: null,
        audioSlowUrl: null,
    },
];

const grammarPoints = [
    {
        id: 1,
        title: 'Definite articles',
        explanation: 'El for masculine, la for feminine.',
    },
];

const progress = {
    percent: 30,
    known: 3,
    total: 10,
    stars: 1,
    words: [
        { id: 1, state: 'known' },
        { id: 2, state: 'new' },
    ],
    level: { code: 'A1', known: 12, total: 80, percent: 15 },
};

function mountPage(overrides: Record<string, unknown> = {}) {
    forms.length = 0;

    return mount(Show, {
        props: {
            unit,
            vocabularyItems,
            grammarPoints,
            isCompleted: false,
            speechLocale: 'es-ES',
            progress,
            day: { words: 4, goal: 10, streak: 3, due: 7 },
            ...overrides,
        },
    });
}

describe('unit page', () => {
    it('renders the unit heading with its level and skill', () => {
        const text = mountPage().text();

        expect(text).toContain('Ordering coffee');
        expect(text).toContain('Order a drink and pay for it.');
        expect(text).toContain('A1');
        expect(text).toContain('Speaking');
    });

    it('renders every vocabulary item with its translation', () => {
        const text = mountPage().text();

        expect(text).toContain('el café');
        expect(text).toContain('the coffee');
        expect(text).toContain('la cuenta');
        expect(text).toContain('the bill');
        expect(text).toContain('noun');
    });

    it('flags a cognate and shows a contrast note only where there is one', () => {
        const text = mountPage().text();

        expect(text).toContain('cognate');
        expect(text).toContain('Not the same as "cuento".');
    });

    it('renders the grammar points', () => {
        const text = mountPage().text();

        expect(text).toContain('Definite articles');
        expect(text).toContain('El for masculine, la for feminine.');
    });

    it('shows the unit contrast note when the unit has one', () => {
        const text = mountPage({
            unit: { ...unit, contrastNote: 'Ser and estar both mean to be.' },
        }).text();

        expect(text).toContain('Watch out for');
        expect(text).toContain('Ser and estar both mean to be.');
    });

    it('omits the contrast section when there is no note', () => {
        expect(mountPage().text()).not.toContain('Watch out for');
    });

    it('drops the empty sections when a unit has no content', () => {
        const text = mountPage({
            vocabularyItems: [],
            grammarPoints: [],
        }).text();

        expect(text).not.toContain('Vocabulary');
        expect(text).not.toContain('Grammar');
    });

    it('offers to complete a fresh unit', () => {
        const text = mountPage().text();

        expect(text).toContain('Complete unit and add to review deck');
        expect(text).not.toContain("You've already completed this unit");
    });

    it('reports an already completed unit and offers a top-up', () => {
        const text = mountPage({ isCompleted: true }).text();

        expect(text).toContain("You've already completed this unit");
        expect(text).toContain('Mark complete again');
    });

    it('posts to the completion endpoint for this unit', async () => {
        const wrapper = mountPage();

        const button = wrapper
            .findAll('button')
            .find((candidate) => candidate.text().includes('Complete unit'));
        await button?.trigger('click');

        expect(forms[0].post).toHaveBeenCalledWith('/units/4/completion');
    });
});

const overview = {
    lessons: [
        {
            stage: 'meet',
            title: 'Meet the words',
            position: 1,
            lessonId: 11,
            state: 'completed',
            bestAccuracy: 0.9,
        },
        {
            stage: 'recall',
            title: 'Recall the words',
            position: 2,
            lessonId: 12,
            state: 'in_progress',
            bestAccuracy: null,
        },
        {
            stage: 'sentences',
            title: 'Build sentences',
            position: 3,
            lessonId: null,
            state: 'coming',
            bestAccuracy: null,
        },
        {
            stage: 'task',
            title: 'Do the task',
            position: 4,
            lessonId: null,
            state: 'coming',
            bestAccuracy: null,
        },
        {
            stage: 'check',
            title: 'Unit check',
            position: 5,
            lessonId: 15,
            state: 'locked',
            bestAccuracy: null,
        },
    ],
    mastery: { mastered: 4, total: 10 },
    skipped: { listening: 2, speaking: 1 },
    contentPending: true,
    canTestOut: false,
    remediation: null,
};

describe('unit page with lessons', () => {
    it('shows the lessons, mastery and skipped counts, and no Complete button', () => {
        const text = mountPage({ lessons: overview }).text();

        expect(text).toContain('Meet the words');
        expect(text).toContain('Done, best 90% first time');
        expect(text).toContain('Continue');
        expect(text).toContain('Coming soon');
        expect(text).toContain('Opens after the lessons before it');
        expect(text).toContain('4 of 10 mastered');
        expect(text).toContain(
            'Skipped listening in 2 and speaking in 1 exercises.',
        );
        expect(text).toContain(
            'Sentence and grammar lessons for this unit are coming',
        );
        expect(text).not.toContain('Complete unit and add to review deck');
    });

    it('keeps the words and grammar as a collapsible reference', () => {
        expect(mountPage({ lessons: overview }).text()).toContain(
            'Words and grammar in this unit',
        );
    });

    it('starts or resumes a lesson through its run url', async () => {
        const wrapper = mountPage({ lessons: overview });
        router.post.mockClear();

        await wrapper
            .findAll('button')
            .find((button) => button.text().includes('Continue'))
            ?.trigger('click');

        expect(router.post).toHaveBeenCalledWith(
            '/units/4/lessons/12/runs',
            {},
            expect.anything(),
        );
    });

    it('offers the unit check now for a unit not started, as a test-out', async () => {
        const wrapper = mountPage({
            lessons: { ...overview, canTestOut: true },
        });
        router.post.mockClear();

        await wrapper
            .findAll('button')
            .find((button) => button.text().includes('Take the unit check now'))
            ?.trigger('click');

        expect(router.post).toHaveBeenCalledWith(
            '/units/4/lessons/15/runs',
            { kind: 'test_out' },
            expect.anything(),
        );
    });

    it('shows the message the server refused a start with', async () => {
        const wrapper = mountPage({ lessons: overview });
        router.post.mockImplementation((_url, _data, options) =>
            options.onError({ lesson: 'The check opens tomorrow.' }),
        );

        await wrapper
            .findAll('button')
            .filter((button) => button.attributes('data-testid') === undefined)
            .find((button) => button.text().includes('Continue'))
            ?.trigger('click');

        expect(wrapper.text()).toContain('The check opens tomorrow.');
    });

    describe('remediation', () => {
        const remediated = (retake: 'open' | 'opens_tomorrow') => ({
            ...overview,
            lessons: overview.lessons.map((row) =>
                row.stage === 'check' ? { ...row, state: 'remediation' } : row,
            ),
            remediation: { lessonId: 15, missing: 2, retake },
        });

        it('says what to do on the check row and offers no start button for it', () => {
            const wrapper = mountPage({ lessons: remediated('open') });
            const row = wrapper.get('[data-testid="lesson-check"]');

            expect(row.text()).toContain(
                'Practise the missed items, then retake',
            );
            expect(row.find('button').exists()).toBe(false);
        });

        it('starts practice or the retake with its kind', async () => {
            const wrapper = mountPage({ lessons: remediated('open') });
            const card = wrapper.get('[data-testid="remediation"]');
            router.post.mockReset();
            router.post.mockImplementation((_url, _data, options) =>
                options.onFinish(),
            );

            expect(card.text()).toContain('2 items not proven yet');

            for (const label of ['Practise the missed items', 'Retake']) {
                await card
                    .findAll('button')
                    .find((button) => button.text() === label)
                    ?.trigger('click');
            }

            expect(
                router.post.mock.calls.map((call) => call.slice(0, 2)),
            ).toEqual([
                ['/units/4/lessons/15/runs', { kind: 'practice' }],
                ['/units/4/lessons/15/runs', { kind: 'retake' }],
            ]);
        });

        it('explains that the retake opens tomorrow instead of offering it', () => {
            const card = mountPage({
                lessons: remediated('opens_tomorrow'),
            }).get('[data-testid="remediation"]');

            expect(card.text()).toContain('The retake opens tomorrow');
            expect(
                card.findAll('button').map((button) => button.text()),
            ).toEqual(['Practise the missed items']);
        });

        it('shows the refusal of a remediation start', async () => {
            const wrapper = mountPage({ lessons: remediated('open') });
            router.post.mockImplementation((_url, _data, options) =>
                options.onError({ lesson: 'The retake opens tomorrow.' }),
            );

            await wrapper
                .get('[data-testid="remediation"] button')
                .trigger('click');

            expect(wrapper.text()).toContain('The retake opens tomorrow.');
        });

        it('shows nothing of it without remediation', () => {
            expect(
                mountPage({ lessons: overview })
                    .find('[data-testid="remediation"]')
                    .exists(),
            ).toBe(false);
        });
    });

    it('tells a held-back learner to clear their reviews first', () => {
        expect(
            mountPage({ lessons: overview, availability: 'held_back' }).text(),
        ).toContain('Clear your reviews first');
    });
});

describe('day strip', () => {
    it('sits on the unit page with the words of today, the streak and the reviews due', () => {
        const wrapper = mountPage();

        expect(wrapper.get('[data-testid="day-goal"]').text()).toBe(
            '4 of 10 words today',
        );
        expect(wrapper.get('[data-testid="day-streak"]').text()).toBe('3');
        expect(wrapper.get('[data-testid="day-due"]').text()).toBe('7');
    });
});

describe('unit hero', () => {
    const lessons = {
        lessons: [
            {
                stage: 'meet',
                title: 'Meet',
                position: 1,
                lessonId: 11,
                state: 'completed',
                bestAccuracy: 90,
            },
            {
                stage: 'recall',
                title: 'Recall',
                position: 2,
                lessonId: 12,
                state: 'available',
                bestAccuracy: null,
            },
        ],
        mastery: { mastered: 3, total: 10 },
        skipped: { listening: 0, speaking: 0 },
        contentPending: false,
        canTestOut: false,
        remediation: null,
    };

    it('shows the share known, the stars and the level', () => {
        const wrapper = mountPage({ lessons });

        expect(
            wrapper
                .find('[data-testid="unit-hero"] [data-testid="progress-ring"]')
                .text(),
        ).toContain('30%');
        expect(wrapper.text()).toContain('3 of 10 words known');
        expect(wrapper.find('[data-testid="level-progress"]').text()).toContain(
            'A1: 12 of 80 words (15%)',
        );
    });

    it('continues with the next lesson that is open', async () => {
        router.post.mockReset();
        const wrapper = mountPage({ lessons, availability: 'in_progress' });

        await wrapper.find('[data-testid="continue-button"]').trigger('click');

        expect(router.post).toHaveBeenCalledWith(
            '/units/4/lessons/12/runs',
            {},
            expect.anything(),
        );
    });

    it('has no continue button for a unit held back', () => {
        expect(
            mountPage({ lessons, availability: 'held_back' })
                .find('[data-testid="continue-button"]')
                .exists(),
        ).toBe(false);
    });
});
