import { flushPromises, mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import StoryPlayer from './StoryPlayer.vue';

class FakeAudio {
    static played: string[] = [];
    static last: FakeAudio | null = null;
    listeners: Record<string, () => void> = {};

    constructor(public src: string) {
        FakeAudio.last = this;
    }

    addEventListener(name: string, listener: () => void) {
        this.listeners[name] = listener;
    }

    play() {
        FakeAudio.played.push(this.src);

        return Promise.resolve();
    }

    pause() {}
}

const segments = [
    { speaker: 'Ana', text: 'Hola.', audioUrl: '/a1.mp3' },
    { speaker: 'Luis', text: 'Encantado.', audioUrl: '/l1.mp3' },
    { speaker: 'Ana', text: 'Adiós.', audioUrl: null },
    { speaker: 'Ana', text: 'Fin.', audioUrl: '/a2.mp3' },
];

beforeEach(() => {
    FakeAudio.played = [];
    vi.stubGlobal('Audio', FakeAudio);
});

afterEach(() => {
    vi.unstubAllGlobals();
});

describe('story player', () => {
    it('plays every line in turn, each in its own clip, naming who speaks', async () => {
        const wrapper = mount(StoryPlayer, { props: { segments } });

        await wrapper.get('[data-testid="story-play"]').trigger('click');
        await flushPromises();

        expect(FakeAudio.played).toEqual(['/a1.mp3']);
        expect(wrapper.get('[data-testid="story-speaker"]').text()).toBe('Ana');

        FakeAudio.last?.listeners.ended();
        await flushPromises();

        expect(FakeAudio.played).toEqual(['/a1.mp3', '/l1.mp3']);
        expect(wrapper.get('[data-testid="story-speaker"]').text()).toBe(
            'Luis',
        );

        FakeAudio.last?.listeners.ended();
        await flushPromises();

        expect(FakeAudio.played.at(-1)).toBe('/a2.mp3');

        FakeAudio.last?.listeners.ended();
        await flushPromises();

        expect(wrapper.find('[data-testid="story-speaker"]').exists()).toBe(
            false,
        );
    });

    it('stops when tapped again', async () => {
        const wrapper = mount(StoryPlayer, { props: { segments } });

        await wrapper.get('[data-testid="story-play"]').trigger('click');
        await wrapper.get('[data-testid="story-play"]').trigger('click');

        expect(wrapper.find('[data-testid="story-speaker"]').exists()).toBe(
            false,
        );
    });
});
