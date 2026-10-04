import { flushPromises, mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
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

vi.mock('@/routes/scripted-prompts', () => ({
    index: () => ({ url: '/scripted-prompts', method: 'get' }),
}));

vi.mock('@/routes/scripted-prompts/attempts', () => ({
    store: (id: number) => ({ url: `/scripted-prompts/${id}/attempts` }),
}));

vi.mock('@/lib/milestone', () => ({ showMilestone: vi.fn() }));

class FakeRecognition {
    static last: FakeRecognition;
    lang = '';
    interimResults = false;
    maxAlternatives = 1;
    onresult: ((event: unknown) => void) | null = null;
    onerror: (() => void) | null = null;
    onend: (() => void) | null = null;
    start = vi.fn();

    constructor() {
        FakeRecognition.last = this;
    }
}

const exercise = {
    id: 1,
    prompt_text: 'Preséntate.',
};

function mountPage(value: typeof exercise | null = exercise) {
    return mount(Index, {
        props: { exercise: value, speechLocale: 'es-ES' },
    });
}

beforeEach(() => {
    submitOrQueue.mockReset();
    vi.stubGlobal('SpeechRecognition', FakeRecognition);
});

afterEach(() => {
    vi.unstubAllGlobals();
});

describe('scripted prompts page', () => {
    it('keeps the English text through a scored attempt', async () => {
        submitOrQueue.mockResolvedValue({
            queued: false,
            response: { ok: true, json: async () => ({ score: 90 }) },
        });

        const wrapper = mountPage();

        expect(wrapper.get('h1').text()).toBe('Scripted prompts');
        expect(wrapper.get('button').text()).toBe('Answer out loud');

        await wrapper.get('button').trigger('click');

        expect(wrapper.get('button').text()).toBe('Listening…');

        FakeRecognition.last.onresult?.({
            results: [[{ transcript: 'buenos dias' }]],
        });
        await flushPromises();

        expect(wrapper.text()).toContain('You said: "buenos dias"');
        expect(wrapper.text()).toContain('Keyword match: 90%');
    });

    it('reports a recognition error and a rejected submit', async () => {
        submitOrQueue.mockResolvedValue({
            queued: false,
            response: { ok: false },
        });

        const wrapper = mountPage();
        await wrapper.get('button').trigger('click');
        FakeRecognition.last.onerror?.();
        await flushPromises();

        expect(wrapper.text()).toContain("We couldn't hear that clearly.");

        await wrapper.get('button').trigger('click');
        FakeRecognition.last.onresult?.({ results: [[{ transcript: 'x' }]] });
        await flushPromises();

        expect(wrapper.text()).toContain("Couldn't submit that attempt.");
    });

    it('says an offline attempt is saved', async () => {
        submitOrQueue.mockResolvedValue({ queued: true });

        const wrapper = mountPage();
        await wrapper.get('button').trigger('click');
        FakeRecognition.last.onresult?.({ results: [[{ transcript: 'x' }]] });
        await flushPromises();

        expect(wrapper.text()).toContain(
            i18n.global.t('practice.offlineAttempt'),
        );
    });

    it('shows the empty state and Dutch chrome', () => {
        setLocale('nl');

        expect(mountPage(null).text()).toContain(
            i18n.global.t('scriptedPrompts.empty'),
        );
        expect(mountPage().get('button').text()).toBe(
            i18n.global.t('scriptedPrompts.answer'),
        );
    });
});
