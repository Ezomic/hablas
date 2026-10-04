import { flushPromises, mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { nextTick } from 'vue';
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

vi.mock('@/routes/reading', () => ({
    index: () => ({ url: '/reading', method: 'get' }),
}));

vi.mock('@/routes/reading/attempts', () => ({
    store: (id: number) => ({ url: `/reading/${id}/attempts` }),
}));

vi.mock('@/lib/milestone', () => ({ showMilestone: vi.fn() }));

const passage = {
    id: 1,
    title: 'En el hotel',
    body: 'Hola, soy Carmen.',
    cefrLevel: 'A1',
    questions: [{ prompt: 'Who?', options: ['Carmen', 'Ana'] }],
};

function mountPage(value: typeof passage | null = passage) {
    return mount(Index, { props: { passage: value } });
}

async function answerAndSubmit(wrapper: ReturnType<typeof mountPage>) {
    await wrapper.get('[role="radio"]').trigger('click');
    await wrapper.get('form').trigger('submit');
    await flushPromises();
}

beforeEach(() => {
    submitOrQueue.mockReset();
});

describe('reading page', () => {
    it('keeps the English text', async () => {
        submitOrQueue.mockResolvedValue({
            queued: false,
            response: { ok: true, json: async () => ({ score: 80 }) },
        });

        const wrapper = mountPage();

        expect(wrapper.get('h1').text()).toBe('Reading practice');
        expect(wrapper.text()).toContain('Check answers');

        await answerAndSubmit(wrapper);

        expect(wrapper.text()).toContain('You scored 80%.');
    });

    it('says when nothing is available', () => {
        expect(mountPage(null).text()).toBe(
            'Reading practiceNo reading passages available at your level yet.',
        );
    });

    it('explains an offline save and a failed submit in the catalog text', async () => {
        submitOrQueue.mockResolvedValueOnce({ queued: true });

        const wrapper = mountPage();
        await answerAndSubmit(wrapper);

        expect(wrapper.text()).toContain(
            i18n.global.t('practice.offlineQueued'),
        );

        submitOrQueue.mockResolvedValueOnce({
            queued: false,
            response: { ok: false },
        });
        await wrapper.get('form').trigger('submit');
        await flushPromises();

        expect(wrapper.text()).toContain(
            i18n.global.t('practice.submitFailed'),
        );
    });

    it('renders in Dutch and leaves the passage as it is', async () => {
        setLocale('nl');

        const wrapper = mountPage();
        await nextTick();

        expect(wrapper.get('h1').text()).toBe(i18n.global.t('reading.title'));
        expect(wrapper.get('h1').text()).not.toBe('Reading practice');
        expect(wrapper.text()).toContain('Hola, soy Carmen.');
        expect(wrapper.text()).toContain('Carmen');
    });
});
