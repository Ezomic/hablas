import { mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { defineComponent } from 'vue';
import { useSpeech } from './useSpeech';

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
    voice: { lang: string; name: string } | null = null;
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
});
