import { flushPromises, mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { FakeAudio } from '@/test/fakeAudio';
import Index from './Index.vue';

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
}));

vi.mock('@/composables/useOfflineSync', () => ({
    useOfflineSync: () => ({ submitOrQueue: vi.fn() }),
}));

vi.mock('@/routes/listening/attempts', () => ({
    store: (id: number) => ({ url: `/listening/${id}/attempts` }),
}));

vi.mock('@/lib/milestone', () => ({ showMilestone: vi.fn() }));

const exercise = {
    id: 1,
    title: 'At the hotel',
    cefrLevel: 'A1',
    transcript: 'Hola, soy Carmen.',
    audioUrl: '/clips/carmen.mp3',
    audioSlowUrl: '/clips/carmen-slow.mp3',
    questions: [{ prompt: 'Who?', options: ['Carmen', 'Ana'] }],
};

class FakeUtterance {
    onstart: (() => void) | null = null;
    onend: (() => void) | null = null;
    onerror: (() => void) | null = null;
    lang = '';
    rate = 1;
    voice = null;

    constructor(public text: string) {}
}

const utterances: FakeUtterance[] = [];

function mountPage(overrides: Record<string, unknown> = {}) {
    return mount(Index, {
        props: {
            exercise,
            speechLocale: 'es-ES',
            maxReplays: 2,
            ...overrides,
        },
    });
}

async function press(wrapper: ReturnType<typeof mountPage>) {
    await wrapper.get('button').trigger('click');
    await flushPromises();
}

beforeEach(() => {
    utterances.length = 0;
    FakeAudio.reset();

    vi.stubGlobal('Audio', FakeAudio);
    vi.stubGlobal('SpeechSynthesisUtterance', FakeUtterance);
    vi.stubGlobal('speechSynthesis', {
        speak: (utterance: FakeUtterance) => utterances.push(utterance),
        cancel: vi.fn(),
        getVoices: () => [],
    });
});

afterEach(() => {
    vi.unstubAllGlobals();
});

describe('listening page replays', () => {
    it('prefetches the clip and plays the normal speed only', async () => {
        const wrapper = mountPage();

        expect(FakeAudio.instances.map((audio) => audio.src)).toEqual([
            '/clips/carmen.mp3',
        ]);

        await press(wrapper);

        expect(FakeAudio.instances.map((audio) => audio.src)).toEqual([
            '/clips/carmen.mp3',
        ]);
    });

    it('shows a loading state and does not count the play until the clip is playing', async () => {
        const wrapper = mountPage();

        await press(wrapper);

        const button = wrapper.get('button');
        expect(button.attributes('aria-busy')).toBe('true');
        expect(button.text()).toContain('Loading clip');
        expect(wrapper.text()).toContain('2 replays left');
        expect(wrapper.find('form').exists()).toBe(false);

        FakeAudio.last().onplaying?.();
        await flushPromises();

        expect(wrapper.get('button').text()).toContain('Play again');
        expect(wrapper.find('form').exists()).toBe(true);
        expect(wrapper.text()).toContain('2 replays left');
    });

    it('charges a replay only for the plays after the first', async () => {
        const wrapper = mountPage();

        await press(wrapper);
        FakeAudio.last().onplaying?.();
        FakeAudio.last().onended?.();
        await flushPromises();
        expect(wrapper.text()).toContain('2 replays left');

        await press(wrapper);
        FakeAudio.last().onplaying?.();
        FakeAudio.last().onended?.();
        await flushPromises();
        expect(wrapper.text()).toContain('1 replay left');
    });

    it('does not burn a replay when the clip fails and the browser voice has to read it', async () => {
        const wrapper = mountPage({ speechLocale: 'es-ES' });

        await press(wrapper);
        FakeAudio.last().onerror?.();
        await flushPromises();

        expect(utterances).toHaveLength(1);
        utterances[0].onstart?.();
        await flushPromises();

        expect(wrapper.text()).toContain('2 replays left');
    });

    it('does not count a play that never started', async () => {
        vi.unstubAllGlobals();
        vi.stubGlobal('Audio', FakeAudio);

        const wrapper = mountPage();

        await press(wrapper);
        FakeAudio.last().onerror?.();
        await flushPromises();

        expect(wrapper.find('form').exists()).toBe(false);
        expect(wrapper.get('button').text()).toContain('Play the clip');
        expect(wrapper.text()).toContain('2 replays left');
    });

    it('does not count a play blocked by the browser', async () => {
        FakeAudio.playError = new DOMException('blocked', 'NotAllowedError');

        const wrapper = mountPage();

        await press(wrapper);

        expect(utterances).toHaveLength(0);
        expect(wrapper.find('form').exists()).toBe(false);
        expect(wrapper.get('button').text()).toContain('Play the clip');
    });
});
