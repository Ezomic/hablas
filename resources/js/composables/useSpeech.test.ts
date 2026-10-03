import { mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { defineComponent } from 'vue';
import { FakeAudio } from '@/test/fakeAudio';
import { hasExactVoice, hasExactVoiceNow, useSpeech } from './useSpeech';

type Utterance = {
    text: string;
    lang?: string;
    voice?: { lang: string; name: string } | null;
};

const spoken: Utterance[] = [];
let voices: { lang: string; name: string }[] = [];
const cancel = vi.fn();

class FakeUtterance {
    lang = '';
    rate = 1;
    voice: { lang: string; name: string } | null = null;
    onstart: (() => void) | null = null;
    onend: (() => void) | null = null;
    onerror: (() => void) | null = null;

    constructor(public text: string) {}
}

// Captures the composable's own return rather than reading it off vm, which
// unwraps refs and would hide whether isSpeaking is reactive at all.
function harness(locale: string | null) {
    let api!: ReturnType<typeof useSpeech>;

    const component = defineComponent({
        setup() {
            api = useSpeech(() => locale);

            return () => null;
        },
    });

    const wrapper = mount(component);

    return { wrapper, api };
}

beforeEach(() => {
    spoken.length = 0;
    voices = [];
    cancel.mockClear();

    FakeAudio.reset();

    vi.stubGlobal('Audio', FakeAudio);
    vi.stubGlobal('SpeechSynthesisUtterance', FakeUtterance);
    vi.stubGlobal('speechSynthesis', {
        speak: (utterance: FakeUtterance) => spoken.push(utterance),
        cancel,
        getVoices: () => voices,
    });
});

afterEach(() => {
    vi.unstubAllGlobals();
});

describe('useSpeech', () => {
    it('reports support when the browser has speech synthesis', () => {
        expect(harness('es-ES').api.isSupported).toBe(true);
    });

    it('speaks the given text', () => {
        harness('es-ES').api.speak('hola');

        expect(spoken).toHaveLength(1);
        expect(spoken[0].text).toBe('hola');
    });

    it('tags the utterance with the language', () => {
        harness('pt-PT').api.speak('obrigado');

        expect(spoken[0].lang).toBe('pt-PT');
    });

    it('picks an exact voice match when one exists', () => {
        voices = [
            { lang: 'en-GB', name: 'Daniel' },
            { lang: 'es-ES', name: 'Monica' },
        ];

        harness('es-ES').api.speak('hola');

        expect(spoken[0].voice?.name).toBe('Monica');
    });

    it('falls back to any voice for the same base language', () => {
        voices = [{ lang: 'pt-BR', name: 'Luciana' }];

        harness('pt-PT').api.speak('obrigado');

        expect(spoken[0].voice?.name).toBe('Luciana');
    });

    it('still speaks when the browser offers no matching voice', () => {
        voices = [{ lang: 'en-GB', name: 'Daniel' }];

        harness('es-ES').api.speak('hola');

        expect(spoken).toHaveLength(1);
        expect(spoken[0].voice).toBeNull();
        expect(spoken[0].lang).toBe('es-ES');
    });

    it('leaves the language unset when there is no tag', () => {
        harness(null).api.speak('hola');

        expect(spoken[0].lang).toBe('');
    });

    it('ignores empty text', () => {
        harness('es-ES').api.speak('   ');

        expect(spoken).toHaveLength(0);
    });

    it('cancels anything already speaking before starting', () => {
        harness('es-ES').api.speak('hola');

        expect(cancel).toHaveBeenCalled();
    });

    it('clears the speaking flag when the utterance ends', () => {
        const { api } = harness('es-ES');

        api.speak('hola');
        expect(api.isSpeaking.value).toBe(true);

        (spoken[0] as unknown as FakeUtterance).onend?.();
        expect(api.isSpeaking.value).toBe(false);
    });

    it('degrades quietly when the browser has no speech synthesis at all', () => {
        vi.unstubAllGlobals();

        const { api } = harness('es-ES');

        expect(api.isSupported).toBe(false);
        expect(() => api.speak('hola')).not.toThrow();
    });

    it('stops speaking when the component goes away', () => {
        const { wrapper, api } = harness('es-ES');

        api.speak('hola');
        wrapper.unmount();

        expect(cancel).toHaveBeenCalledTimes(2);
    });

    describe('with a clip', () => {
        const flush = () => new Promise((resolve) => setTimeout(resolve, 0));

        it('is loading until the clip is playing, then resolves true', async () => {
            const { api } = harness('es-ES');
            const started = api.speak('hola', '/clips/hola.mp3');

            expect(api.isLoading.value).toBe(true);
            expect(api.isSpeaking.value).toBe(false);
            expect(FakeAudio.last().src).toBe('/clips/hola.mp3');
            expect(spoken).toHaveLength(0);

            FakeAudio.last().onplaying?.();

            expect(await started).toBe(true);
            expect(api.isLoading.value).toBe(false);
            expect(api.isSpeaking.value).toBe(true);

            FakeAudio.last().onended?.();
            expect(api.isSpeaking.value).toBe(false);
        });

        it('falls back to the browser voice when the clip errors', async () => {
            const { api } = harness('es-ES');
            const started = api.speak('hola', '/clips/hola.mp3');

            FakeAudio.last().onerror?.();

            expect(api.isLoading.value).toBe(false);
            expect(spoken).toHaveLength(1);
            expect(spoken[0].text).toBe('hola');

            (spoken[0] as unknown as FakeUtterance).onstart?.();
            expect(await started).toBe(true);
        });

        it('falls back when play() is rejected for another reason', async () => {
            FakeAudio.playError = new DOMException(
                'no source',
                'NotSupportedError',
            );

            const { api } = harness('es-ES');
            const started = api.speak('hola', '/clips/hola.mp3');

            await flush();
            (spoken[0] as unknown as FakeUtterance).onstart?.();

            expect(spoken).toHaveLength(1);
            expect(await started).toBe(true);
        });

        it('does not fall back to the browser voice when autoplay is blocked', async () => {
            FakeAudio.playError = new DOMException(
                'blocked',
                'NotAllowedError',
            );

            const { api } = harness('es-ES');
            const started = api.speak('hola', '/clips/hola.mp3');

            expect(await started).toBe(false);
            expect(spoken).toHaveLength(0);
            expect(api.isLoading.value).toBe(false);
            expect(api.isSpeaking.value).toBe(false);
        });

        it('resolves false when nothing can be played at all', async () => {
            vi.unstubAllGlobals();
            vi.stubGlobal('Audio', FakeAudio);

            const { api } = harness('es-ES');
            const started = api.speak('hola', '/clips/hola.mp3');

            FakeAudio.last().onerror?.();

            expect(await started).toBe(false);
        });

        it('resolves false when a newer speak replaces a pending one', async () => {
            const { api } = harness('es-ES');
            const first = api.speak('hola', '/clips/hola.mp3');

            void api.speak('adios', '/clips/adios.mp3');

            expect(await first).toBe(false);
            expect(FakeAudio.instances[0].pause).toHaveBeenCalled();
        });

        it('reads the browser voice slowly for the slow speed', () => {
            harness('es-ES').api.speak('hola', null, { speed: 'slow' });

            expect(spoken[0].text).toBe('hola');
            expect((spoken[0] as unknown as FakeUtterance).rate).toBe(0.75);
        });

        it('reuses a prefetched element instead of loading the clip again', () => {
            const { api } = harness('es-ES');

            api.prefetch('/clips/hola.mp3');
            api.prefetch('/clips/hola.mp3');
            void api.speak('hola', '/clips/hola.mp3');

            expect(FakeAudio.instances).toHaveLength(1);
            expect(FakeAudio.instances[0].preload).toBe('auto');
            expect(FakeAudio.instances[0].play).toHaveBeenCalledTimes(1);
        });

        it('ignores a prefetch without a url', () => {
            harness('es-ES').api.prefetch(null);

            expect(FakeAudio.instances).toHaveLength(0);
        });

        it('keeps only a few clips cached', () => {
            const { api } = harness('es-ES');

            for (const word of ['a', 'b', 'c', 'd', 'e']) {
                api.prefetch(`/clips/${word}.mp3`);
            }

            void api.speak('a', '/clips/a.mp3');

            expect(FakeAudio.instances).toHaveLength(6);
        });

        it('treats a clip that never loads as errored and uses the browser voice', async () => {
            vi.useFakeTimers();

            try {
                const { api } = harness('es-ES');
                const started = api.speak('hola', '/clips/hola.mp3');

                vi.advanceTimersByTime(6999);
                expect(spoken).toHaveLength(0);
                expect(api.isLoading.value).toBe(true);

                vi.advanceTimersByTime(1);
                expect(spoken).toHaveLength(1);
                expect(api.isLoading.value).toBe(false);

                (spoken[0] as unknown as FakeUtterance).onstart?.();
                expect(await started).toBe(true);
            } finally {
                vi.useRealTimers();
            }
        });

        it('does not time out once the clip is playing or after a cancel', () => {
            vi.useFakeTimers();

            try {
                const { api } = harness('es-ES');

                void api.speak('hola', '/clips/hola.mp3');
                FakeAudio.last().onplaying?.();
                vi.advanceTimersByTime(10000);

                void api.speak('adios', '/clips/adios.mp3');
                api.cancel();
                vi.advanceTimersByTime(10000);

                expect(spoken).toHaveLength(0);
            } finally {
                vi.useRealTimers();
            }
        });

        it('ignores an abort from a play that a cancel already replaced', async () => {
            const { api } = harness('es-ES');
            let rejectFirst!: (error: unknown) => void;

            api.prefetch('/clips/hola.mp3');
            FakeAudio.last().play.mockImplementationOnce(
                () => new Promise<void>((_, reject) => (rejectFirst = reject)),
            );

            void api.speak('hola', '/clips/hola.mp3');
            api.cancel();

            const second = api.speak('hola', '/clips/hola.mp3');

            rejectFirst(new DOMException('interrupted', 'AbortError'));
            await new Promise((resolve) => setTimeout(resolve, 0));

            expect(spoken).toHaveLength(0);
            expect(api.isLoading.value).toBe(true);

            FakeAudio.last().onplaying?.();

            expect(await second).toBe(true);
            expect(FakeAudio.instances).toHaveLength(1);
        });

        it('ignores an abort on the current play instead of falling back', async () => {
            FakeAudio.playError = new DOMException('interrupted', 'AbortError');

            const { api } = harness('es-ES');

            void api.speak('hola', '/clips/hola.mp3');
            await new Promise((resolve) => setTimeout(resolve, 0));

            expect(spoken).toHaveLength(0);
        });

        it('evicts a prefetched clip that failed so a later tap loads it again', () => {
            const { api } = harness('es-ES');

            api.prefetch('/clips/hola.mp3');
            FakeAudio.last().onerror?.();
            void api.speak('hola', '/clips/hola.mp3');

            expect(FakeAudio.instances).toHaveLength(2);
        });
    });
});

describe('hasExactVoice', () => {
    const listeners = new Set<() => void>();

    beforeEach(() => {
        vi.useFakeTimers();
        listeners.clear();
        vi.stubGlobal('speechSynthesis', {
            speak: vi.fn(),
            cancel,
            getVoices: () => voices,
            addEventListener: (_type: string, listener: () => void) =>
                listeners.add(listener),
            removeEventListener: (_type: string, listener: () => void) =>
                listeners.delete(listener),
        });
    });

    afterEach(() => {
        vi.useRealTimers();
    });

    it('is true at once for a voice with exactly the tag, even when the browser writes it es_ES', async () => {
        voices = [{ lang: 'es_ES', name: 'Monica' }];

        expect(hasExactVoiceNow('es-ES')).toBe(true);
        expect(await hasExactVoice('es-ES')).toBe(true);
    });

    it('does not count a voice of the same language for another country', async () => {
        voices = [{ lang: 'pt-BR', name: 'Luciana' }];

        const result = hasExactVoice('pt-PT');

        await vi.advanceTimersByTimeAsync(2000);

        expect(await result).toBe(false);
    });

    it('waits for the voices to be listed, then answers', async () => {
        const result = hasExactVoice('es-ES');

        voices = [{ lang: 'es-ES', name: 'Monica' }];
        [...listeners].forEach((listener) => listener());

        expect(await result).toBe(true);
        expect(listeners.size).toBe(0);
    });

    it('ignores a voices event that brings no exact voice and gives up after two seconds', async () => {
        const result = hasExactVoice('es-ES');

        voices = [{ lang: 'es-MX', name: 'Paulina' }];
        [...listeners].forEach((listener) => listener());
        await vi.advanceTimersByTimeAsync(1999);

        expect(listeners.size).toBe(1);

        await vi.advanceTimersByTimeAsync(1);

        expect(await result).toBe(false);
        expect(listeners.size).toBe(0);
    });

    it('is false without a tag or without speech synthesis', async () => {
        expect(await hasExactVoice(null)).toBe(false);

        vi.stubGlobal('speechSynthesis', undefined);
        delete (window as unknown as Record<string, unknown>).speechSynthesis;

        expect(await hasExactVoice('es-ES')).toBe(false);
    });
});
