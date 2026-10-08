import { flushPromises, mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { nextTick } from 'vue';
import { i18n, setLocale } from '@/i18n';
import Show from './Show.vue';

const { submitOrQueue } = vi.hoisted(() => ({ submitOrQueue: vi.fn() }));

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    Link: { template: '<a><slot /></a>' },
    setLayoutProps: vi.fn(),
}));

vi.mock('@/composables/useOfflineSync', () => ({
    useOfflineSync: () => ({ submitOrQueue }),
}));

vi.mock('@/routes/reading', () => ({
    index: () => ({ url: '/reading', method: 'get' }),
    show: (id: number) => ({ url: `/reading/${id}`, method: 'get' }),
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
    glosses: { soy: 'I am' },
    locale: 'es-ES',
    segments: [] as {
        speaker: string;
        text: string;
        audioUrl: string | null;
    }[],
    questions: [{ prompt: 'Who?', options: ['Carmen', 'Ana'] }],
};

function mountPage(value: typeof passage = passage) {
    return mount(Show, { props: { passage: value } });
}

async function answerAndSubmit(wrapper: ReturnType<typeof mountPage>) {
    await wrapper.get('[role="radio"]').trigger('click');
    await wrapper.get('form').trigger('submit');
    await flushPromises();
}

beforeEach(() => {
    submitOrQueue.mockReset();
});

describe('story page', () => {
    it('shows the meaning of a glossed word when it is tapped', async () => {
        const wrapper = mountPage();

        expect(wrapper.get('[data-testid="gloss"]').text()).toBe(
            i18n.global.t('reading.tapWord'),
        );

        await wrapper.get('button[type="button"]').trigger('click');

        expect(wrapper.get('[data-testid="gloss"]').text()).toBe(
            'soy means I am',
        );
    });

    it('marks the answers once checked', async () => {
        submitOrQueue.mockResolvedValue({
            queued: false,
            response: {
                ok: true,
                json: async () => ({ score: 0, correct: ['Ana'] }),
            },
        });

        const wrapper = mountPage();
        await answerAndSubmit(wrapper);

        expect(wrapper.find('[data-testid="wrong"]').text()).toContain('Ana');
        expect(wrapper.text()).toContain('You scored 0%.');
    });

    it('says a right answer is correct', async () => {
        submitOrQueue.mockResolvedValue({
            queued: false,
            response: {
                ok: true,
                json: async () => ({ score: 100, correct: ['Carmen'] }),
            },
        });

        const wrapper = mountPage();
        await answerAndSubmit(wrapper);

        expect(wrapper.find('[data-testid="right"]').exists()).toBe(true);
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

    it('renders the story as it is in Dutch', async () => {
        setLocale('nl');

        const wrapper = mountPage();
        await nextTick();

        expect(wrapper.text()).toContain('Hola, soy Carmen.');
        expect(wrapper.text()).toContain(i18n.global.t('reading.tapWord'));
        expect(i18n.global.t('reading.tapWord')).not.toBe(
            'Tap a word you do not know.',
        );
    });

    it('plays the story in a voice per character when it has clips, else the browser voice', () => {
        const withClips = mountPage({
            ...passage,
            segments: [{ speaker: 'Ana', text: 'Hola.', audioUrl: '/a.mp3' }],
        });

        expect(withClips.find('[data-testid="story-player"]').exists()).toBe(
            true,
        );
        expect(mountPage().find('[data-testid="story-player"]').exists()).toBe(
            false,
        );
    });
});
