import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { reactive } from 'vue';
import Show from './Show.vue';

const { forms } = vi.hoisted(() => ({
    forms: [] as Record<string, unknown>[],
}));

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    useForm: () => {
        const form = reactive({ processing: false, post: vi.fn() });
        forms.push(form);

        return form;
    },
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
    },
    {
        id: 2,
        term: 'la cuenta',
        translation: 'the bill',
        partOfSpeech: 'noun',
        isCognate: false,
        contrastNote: 'Not the same as "cuento".',
    },
];

const grammarPoints = [
    {
        id: 1,
        title: 'Definite articles',
        explanation: 'El for masculine, la for feminine.',
    },
];

function mountPage(overrides: Record<string, unknown> = {}) {
    forms.length = 0;

    return mount(Show, {
        props: {
            unit,
            vocabularyItems,
            grammarPoints,
            isCompleted: false,
            speechLocale: 'es-ES',
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
