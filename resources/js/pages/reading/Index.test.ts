import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { i18n, setLocale } from '@/i18n';
import Index from './Index.vue';

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    Link: { template: '<a><slot /></a>' },
    setLayoutProps: vi.fn(),
}));

vi.mock('@/routes/reading', () => ({
    index: () => ({ url: '/reading', method: 'get' }),
    show: (id: number) => ({ url: `/reading/${id}`, method: 'get' }),
}));

const stories = [
    { id: 1, title: 'Me llamo Ana', cefrLevel: 'A1', questions: 5, best: 80 },
    { id: 2, title: 'Mi familia', cefrLevel: 'A1', questions: 4, best: null },
];

beforeEach(() => setLocale('en'));

describe('stories page', () => {
    it('lists the stories with the best score of the ones read', () => {
        const wrapper = mount(Index, { props: { stories } });

        expect(wrapper.get('h1').text()).toBe('Stories');
        expect(wrapper.findAll('[data-testid="story"]')).toHaveLength(2);
        expect(wrapper.text()).toContain('Me llamo Ana');
        expect(wrapper.text()).toContain('5 questions');
        expect(wrapper.text()).toContain('Best 80%');
        expect(wrapper.text()).not.toContain('Best null');
    });

    it('says when nothing is available', () => {
        expect(mount(Index, { props: { stories: [] } }).text()).toContain(
            'No stories available at your level yet.',
        );
    });

    it('renders in Dutch', () => {
        setLocale('nl');

        expect(mount(Index, { props: { stories } }).get('h1').text()).toBe(
            i18n.global.t('reading.title'),
        );
    });
});
