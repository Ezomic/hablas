import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import { i18n, setLocale } from '@/i18n';
import Index from './Index.vue';

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    setLayoutProps: vi.fn(),
    router: { reload: vi.fn(), visit: vi.fn() },
    useForm: () => ({ processing: false, post: vi.fn() }),
}));

vi.mock('@/routes/placement', () => ({
    index: () => ({ url: '/placement', method: 'get' }),
    results: () => ({ url: '/placement/results', method: 'get' }),
    answer: (id: number) => ({ url: `/placement/${id}/answer` }),
    skip: () => ({ url: '/placement/skip' }),
}));

function mountPage(skill: string | null = null) {
    return mount(Index, {
        props: {
            item: {
                id: 1,
                skill: 'listening',
                prompt: '¿Dónde está el aeropuerto?',
                options: ['Airport', 'Hotel'],
            },
            language: { code: 'es', name: 'Spanish' },
            dontKnowResponse: "I don't know",
            progress: 25,
            skill,
            canSkip: true,
        },
    });
}

describe('placement test page', () => {
    it('keeps the English text of the interface chrome', () => {
        const text = mountPage().text();

        expect(text).toContain('Spanish placement test');
        expect(text).toContain(
            "Answer each question — the next one adjusts to how you're doing.",
        );
        expect(text).toContain('Progress');
        expect(text).toContain('Listening');
        expect(text).toContain('Next');
        expect(text).toContain("I don't know");
        expect(text).toContain('Not ready for a test?');
        expect(text).toContain('Skip and start at A1');
    });

    it('names the skill in a re-placement', () => {
        const text = mountPage('speaking').text();

        expect(text).toContain('Spanish speaking re-placement');
        expect(text).toContain('This sets your speaking level again');
    });

    it('leaves the test item itself untranslated in Dutch', () => {
        setLocale('nl');

        const text = mountPage().text();

        expect(text).toContain(
            i18n.global.t('placement.title', { language: 'Spanish' }),
        );
        expect(text).toContain(i18n.global.t('placement.dontKnow'));
        expect(text).toContain('¿Dónde está el aeropuerto?');
        expect(text).toContain('Airport');
        expect(text).toContain('Hotel');
        expect(text).not.toContain('placement test');
    });
});
