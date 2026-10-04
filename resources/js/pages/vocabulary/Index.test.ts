import { mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import type {
    VocabularyEntry,
    VocabularyFilters,
    VocabularyPagination,
} from '@/types/vocabulary';
import Index from './Index.vue';

const { routerGet } = vi.hoisted(() => ({ routerGet: vi.fn() }));

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    setLayoutProps: vi.fn(),
    Link: {
        props: ['href'],
        template: '<a :href="href"><slot /></a>',
    },
    router: { get: routerGet },
}));

vi.mock('@/components/SpeakButton.vue', () => ({
    default: {
        props: ['text', 'locale', 'audioUrl', 'audioSlowUrl'],
        template:
            '<button data-test="speak" :data-text="text" :data-url="audioUrl" :data-slow="audioSlowUrl" />',
    },
}));

const word: VocabularyEntry = {
    id: 1,
    kind: 'vocabulary',
    term: 'la llave',
    translation: 'key',
    state: 'relearning',
    dueAt: '2026-09-29T08:00:00+00:00',
    isWeakSpot: true,
    audioUrl: null,
    audioSlowUrl: null,
};

const grammar: VocabularyEntry = {
    id: 2,
    kind: 'grammar',
    term: 'Ser vs estar',
    translation: 'Ser is for permanent traits.',
    state: 'new',
    dueAt: '2026-09-29T08:00:00+00:00',
    isWeakSpot: false,
    audioUrl: null,
    audioSlowUrl: null,
};

function mountPage(
    items: VocabularyEntry[],
    filters: VocabularyFilters = { q: '', sort: 'recent' },
    pagination: VocabularyPagination = {
        currentPage: 1,
        lastPage: 1,
        total: items.length,
    },
) {
    return mount(Index, {
        props: { items, pagination, filters, speechLocale: 'es-ES' },
    });
}

function hrefs(wrapper: ReturnType<typeof mountPage>): string[] {
    return wrapper
        .findAll('a')
        .map((anchor) => anchor.attributes('href') ?? '');
}

beforeEach(() => {
    vi.useFakeTimers();
    vi.setSystemTime(new Date('2026-09-30T12:00:00Z'));
    routerGet.mockReset();
});

afterEach(() => {
    vi.useRealTimers();
});

describe('vocabulary page', () => {
    it('shows each item with its state, due date and weak-spot flag', () => {
        const text = mountPage([word, grammar]).text();

        expect(text).toContain('la llave');
        expect(text).toContain('key');
        expect(text).toContain('Relearning');
        expect(text).toContain('Overdue');
        expect(text).toContain('Weak spot');
        expect(text).toContain('Ser vs estar');
        expect(text).toContain('New');
        expect(text).toContain('Not studied yet');
    });

    it('offers pronunciation for words but not grammar points', () => {
        const speak = mountPage([word, grammar]).findAll('[data-test="speak"]');

        expect(speak.map((button) => button.attributes('data-text'))).toEqual([
            'la llave',
        ]);
    });

    it('hands each word its clips', () => {
        const speak = mountPage([
            { ...word, audioUrl: '/n.mp3', audioSlowUrl: '/s.mp3' },
        ]).get('[data-test="speak"]');

        expect(speak.attributes('data-url')).toBe('/n.mp3');
        expect(speak.attributes('data-slow')).toBe('/s.mp3');
    });

    it('tells a new learner how to fill the deck', () => {
        expect(mountPage([]).text()).toContain(
            'Complete a unit to start your deck.',
        );
    });

    it('says when a search finds nothing', () => {
        const text = mountPage([], { q: 'xyz', sort: 'recent' }).text();

        expect(text).toContain('Nothing matches that search.');
        expect(text).not.toContain('Complete a unit');
    });

    it('keeps the search and sort in the page links', () => {
        const links = hrefs(
            mountPage(
                [word],
                { q: 'lla', sort: 'alphabetical' },
                { currentPage: 2, lastPage: 3, total: 60 },
            ),
        );

        expect(links).toContain('/vocabulary?q=lla&sort=alphabetical');
        expect(links).toContain('/vocabulary?q=lla&sort=alphabetical&page=3');
    });

    it('hides the page links when everything fits on one page', () => {
        expect(hrefs(mountPage([word]))).toEqual([]);
    });

    it('searches once typing pauses, from the first page', async () => {
        const wrapper = mountPage([word], { q: '', sort: 'due' });

        await wrapper.find('input[type="search"]').setValue('lla');
        vi.advanceTimersByTime(299);
        expect(routerGet).not.toHaveBeenCalled();

        vi.advanceTimersByTime(1);
        expect(routerGet).toHaveBeenCalledTimes(1);
        expect(routerGet.mock.calls[0][0]).toBe('/vocabulary?q=lla&sort=due');
        expect(routerGet.mock.calls[0][2]).toMatchObject({
            preserveState: true,
            replace: true,
        });
    });
});
