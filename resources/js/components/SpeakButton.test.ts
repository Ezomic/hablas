import { flushPromises, mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { FakeAudio } from '@/test/fakeAudio';
import SpeakButton from './SpeakButton.vue';

const spoken: { text: string; rate: number }[] = [];

class FakeUtterance {
    lang = '';
    rate = 1;
    voice = null;
    onstart: (() => void) | null = null;
    onend: (() => void) | null = null;
    onerror: (() => void) | null = null;

    constructor(public text: string) {}
}

function mountButton(props: Record<string, unknown> = {}) {
    return mount(SpeakButton, {
        props: { text: 'hola', locale: 'es-ES', ...props },
    });
}

beforeEach(() => {
    spoken.length = 0;
    FakeAudio.reset();

    vi.stubGlobal('Audio', FakeAudio);
    vi.stubGlobal('SpeechSynthesisUtterance', FakeUtterance);
    vi.stubGlobal('speechSynthesis', {
        speak: (utterance: FakeUtterance) => spoken.push(utterance),
        cancel: vi.fn(),
        getVoices: () => [],
    });
});

afterEach(() => {
    vi.unstubAllGlobals();
});

describe('SpeakButton', () => {
    it('shows only the listen button when there is no slow clip', () => {
        const wrapper = mountButton({ audioUrl: '/n.mp3' });

        expect(wrapper.findAll('button')).toHaveLength(1);
        expect(wrapper.get('button').attributes('aria-label')).toBe(
            'Listen to hola',
        );
    });

    it('shows a loading state on the listen button until the clip plays', async () => {
        const wrapper = mountButton({ audioUrl: '/n.mp3' });

        await wrapper.get('button').trigger('click');

        const loading = wrapper.get('button');
        expect(loading.attributes('aria-busy')).toBe('true');
        expect(loading.attributes('aria-disabled')).toBe('true');
        expect(loading.attributes('disabled')).toBeUndefined();
        expect(loading.find('.animate-spin').exists()).toBe(true);
        expect(loading.get('.sr-only').text()).toBe('Loading audio');

        FakeAudio.last().onplaying?.();
        await flushPromises();

        const playing = wrapper.get('button');
        expect(playing.attributes('aria-busy')).toBe('false');
        expect(playing.find('.animate-spin').exists()).toBe(false);
        expect(playing.find('.sr-only').exists()).toBe(false);
    });

    it('plays the normal clip from the listen button', async () => {
        const wrapper = mountButton({
            audioUrl: '/n.mp3',
            audioSlowUrl: '/s.mp3',
        });

        await wrapper.findAll('button')[0].trigger('click');

        expect(FakeAudio.last().src).toBe('/n.mp3');
    });

    it('plays the slow clip, and only that one, from the slow button', async () => {
        const wrapper = mountButton({
            audioUrl: '/n.mp3',
            audioSlowUrl: '/s.mp3',
        });

        const slow = wrapper.get('button[aria-label="Listen to hola slowly"]');
        await slow.trigger('click');

        expect(FakeAudio.instances.map((audio) => audio.src)).toEqual([
            '/s.mp3',
        ]);
        expect(slow.attributes('aria-busy')).toBe('true');
        expect(wrapper.findAll('button')[0].attributes('aria-busy')).toBe(
            'false',
        );
    });

    it('reads slowly in the browser when the slow clip is missing, never playing the normal one', async () => {
        const wrapper = mountButton({ audioUrl: '/n.mp3' });

        expect(wrapper.findAll('button')).toHaveLength(1);

        const withSlow = mountButton({ audioSlowUrl: '/s.mp3' });
        FakeAudio.playError = new DOMException('x', 'NotSupportedError');
        await withSlow.get('button[aria-label$="slowly"]').trigger('click');
        await flushPromises();

        expect(spoken).toHaveLength(1);
        expect(spoken[0].rate).toBe(0.75);
        expect(FakeAudio.instances.map((audio) => audio.src)).toEqual([
            '/s.mp3',
        ]);
    });

    it('uses the browser voice at normal speed when there is no clip', async () => {
        await mountButton().get('button').trigger('click');

        expect(spoken).toHaveLength(1);
        expect(spoken[0].rate).toBe(1);
        expect(FakeAudio.instances).toHaveLength(0);
    });

    it('falls back to the browser voice when the clip fails to load', async () => {
        const wrapper = mountButton({ audioUrl: '/n.mp3' });

        await wrapper.get('button').trigger('click');
        FakeAudio.last().onerror?.();
        await flushPromises();

        expect(spoken.map((utterance) => utterance.text)).toEqual(['hola']);
        expect(wrapper.get('button').attributes('aria-busy')).toBe('false');
    });

    it('prefetches the clip a learner is about to press', async () => {
        const wrapper = mountButton({
            audioUrl: '/n.mp3',
            audioSlowUrl: '/s.mp3',
        });

        await wrapper.findAll('button')[1].trigger('focus');

        expect(FakeAudio.instances.map((audio) => audio.src)).toEqual([
            '/s.mp3',
        ]);
    });

    it('ignores a second press while a clip is loading, without losing focus', async () => {
        const wrapper = mountButton({
            audioUrl: '/n.mp3',
            audioSlowUrl: '/s.mp3',
        });

        await wrapper.get('button').trigger('click');
        await wrapper.findAll('button')[1].trigger('click');

        expect(FakeAudio.instances.map((audio) => audio.src)).toEqual([
            '/n.mp3',
        ]);
        expect(wrapper.get('button').element.hasAttribute('disabled')).toBe(
            false,
        );
    });
});
