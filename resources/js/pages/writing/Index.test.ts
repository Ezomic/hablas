import { flushPromises, mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { i18n, setLocale } from '@/i18n';
import Index from './Index.vue';

const { submitOrQueue } = vi.hoisted(() => ({ submitOrQueue: vi.fn() }));

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    setLayoutProps: vi.fn(),
}));

vi.mock('@/composables/useOfflineSync', () => ({
    useOfflineSync: () => ({ submitOrQueue }),
}));

vi.mock('@/routes/writing', () => ({
    index: () => ({ url: '/writing', method: 'get' }),
}));

vi.mock('@/routes/writing/attempts', () => ({
    store: (id: number) => ({ url: `/writing/${id}/attempts` }),
}));

vi.mock('@/lib/milestone', () => ({ showMilestone: vi.fn() }));

const exercise = {
    id: 1,
    type: 'fill_in_template' as const,
    prompt: 'Presenta a tu familia.',
    template: { text: 'Mi ___ se llama ___.' },
};

function mountPage(value: typeof exercise | null = exercise) {
    return mount(Index, { props: { exercise: value } });
}

async function submitAnswer(wrapper: ReturnType<typeof mountPage>) {
    await wrapper.get('input').setValue('hermana');
    await wrapper.get('button').trigger('click');
    await flushPromises();
}

beforeEach(() => {
    submitOrQueue.mockReset();
});

describe('writing page', () => {
    it('keeps the English text and the Spanish exercise text', async () => {
        submitOrQueue.mockResolvedValue({
            queued: false,
            response: { ok: true, json: async () => ({ is_correct: false }) },
        });

        const wrapper = mountPage();

        expect(wrapper.get('h1').text()).toBe('Writing practice');
        expect(wrapper.get('input').attributes('placeholder')).toBe(
            'Escribe tu respuesta...',
        );
        expect(wrapper.get('button').text()).toBe('Submit');

        await submitAnswer(wrapper);

        expect(wrapper.text()).toContain('Not quite — try again.');
    });

    it('cheers in Spanish when the answer is right', async () => {
        submitOrQueue.mockResolvedValue({
            queued: false,
            response: { ok: true, json: async () => ({ is_correct: true }) },
        });

        const wrapper = mountPage();
        await submitAnswer(wrapper);

        expect(wrapper.text()).toContain('¡Correcto!');
    });

    it('reports a failed submit and an offline save', async () => {
        submitOrQueue.mockResolvedValueOnce({
            queued: false,
            response: { ok: false },
        });

        const wrapper = mountPage();
        await submitAnswer(wrapper);

        expect(wrapper.text()).toContain(i18n.global.t('writing.answerFailed'));

        submitOrQueue.mockResolvedValueOnce({ queued: true });
        await wrapper.get('button').trigger('click');
        await flushPromises();

        expect(wrapper.text()).toContain(
            i18n.global.t('practice.offlineAnswer'),
        );
    });

    it('shows the empty state and the Dutch chrome', () => {
        setLocale('nl');

        expect(mountPage(null).text()).toContain(
            i18n.global.t('writing.empty'),
        );
        expect(mountPage().get('button').text()).toBe(
            i18n.global.t('writing.submit'),
        );
    });
});
