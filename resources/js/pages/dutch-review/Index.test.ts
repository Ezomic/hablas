import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import type {
    DutchReviewItem,
    DutchReviewLanguage,
    DutchReviewUnit,
} from '@/types/dutchReview';
import Index from './Index.vue';

const { routerGet, routerPost } = vi.hoisted(() => ({
    routerGet: vi.fn(),
    routerPost: vi.fn(),
}));

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    setLayoutProps: vi.fn(),
    Link: { props: ['href'], template: '<a :href="href"><slot /></a>' },
    router: { get: routerGet, post: routerPost },
}));

const languages: DutchReviewLanguage[] = [
    { code: 'es', name: 'Spanish' },
    { code: 'fr', name: 'French' },
];

const units: DutchReviewUnit[] = [
    { slug: 'one', title: 'One', level: 'A1', suggestions: 1 },
    { slug: 'two', title: 'Two', level: 'A1', suggestions: 0 },
];

const hola: DutchReviewItem = {
    id: 7,
    term: 'hola',
    english: 'hello',
    dutch: 'hallo',
    suggestion: null,
    note: null,
};

function mountPage(props: Record<string, unknown> = {}) {
    return mount(Index, {
        props: {
            languages,
            language: 'es',
            units,
            unit: 'one',
            previous: null,
            next: 'two',
            items: [hola],
            suggestionCount: 0,
            ...props,
        },
    });
}

beforeEach(() => {
    routerGet.mockReset();
    routerPost.mockReset();
});

describe('dutch review page', () => {
    it('shows each word with its English and Dutch meaning', () => {
        const text = mountPage().text();

        expect(text).toContain('hola');
        expect(text).toContain('hello');
        expect(text).toContain('hallo');
    });

    it('says so when a word has no Dutch meaning yet', () => {
        const wrapper = mountPage({ items: [{ ...hola, dutch: null }] });

        expect(wrapper.text()).toContain('No Dutch meaning yet');
    });

    it('shows the reader their saved suggestion and the count', () => {
        const wrapper = mountPage({
            items: [{ ...hola, suggestion: 'hoi', note: 'informeler' }],
            suggestionCount: 1,
        });

        expect(wrapper.text()).toContain('Your suggestion');
        expect(wrapper.text()).toContain('hoi');
        expect(wrapper.text()).toContain('informeler');
        expect(wrapper.find('[data-test="suggestion-count"]').text()).toContain(
            '1 suggestion',
        );
    });

    it('posts the suggestion for the word and closes the form on success', async () => {
        routerPost.mockImplementation(
            (
                _url: string,
                _data: unknown,
                options: { onSuccess?: () => void },
            ) => options.onSuccess?.(),
        );
        const wrapper = mountPage();

        await wrapper.find('[data-test="suggest"]').trigger('click');
        await wrapper.find('[data-test="suggestion-input"]').setValue('hoi');
        await wrapper.find('[data-test="note-input"]').setValue('informeler');
        await wrapper.find('form').trigger('submit');

        expect(routerPost).toHaveBeenCalledTimes(1);
        expect(routerPost.mock.calls[0][0]).toContain('/dutch-review/7');
        expect(routerPost.mock.calls[0][1]).toEqual({
            suggestion: 'hoi',
            note: 'informeler',
        });
        expect(wrapper.find('[data-test="suggestion-input"]').exists()).toBe(
            false,
        );
    });

    it('goes to another language and to the next unit', async () => {
        const wrapper = mountPage();

        await wrapper.find('[data-test="language-fr"]').trigger('click');
        expect(routerGet.mock.calls[0][0]).toContain('language=fr');
        expect(routerGet.mock.calls[0][0]).not.toContain('unit=');

        await wrapper.find('[data-test="next-unit"]').trigger('click');
        expect(routerGet.mock.calls[1][0]).toContain('unit=two');
    });

    it('cannot go to a unit before the first', () => {
        const wrapper = mountPage();

        expect(
            wrapper.find('[data-test="previous-unit"]').attributes('disabled'),
        ).toBeDefined();
    });
});
