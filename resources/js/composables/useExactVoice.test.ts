import { mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { defineComponent } from 'vue';
import { useExactVoice } from './useExactVoice';

const listeners = new Set<() => void>();
let voices: { lang: string }[] = [];

function fire() {
    [...listeners].forEach((listener) => listener());
}

function harness(locale: string | null) {
    let api!: ReturnType<typeof useExactVoice>;
    const wrapper = mount(
        defineComponent({
            setup() {
                api = useExactVoice(() => locale);

                return () => null;
            },
        }),
    );

    return { wrapper, api };
}

beforeEach(() => {
    vi.useFakeTimers();
    listeners.clear();
    voices = [];
    vi.stubGlobal('speechSynthesis', {
        getVoices: () => voices,
        addEventListener: (_type: string, listener: () => void) =>
            listeners.add(listener),
        removeEventListener: (_type: string, listener: () => void) =>
            listeners.delete(listener),
    });
});

afterEach(() => {
    vi.useRealTimers();
    vi.unstubAllGlobals();
});

describe('useExactVoice', () => {
    it('is undecided while the voices load, then false once two seconds have passed', async () => {
        const { api } = harness('es-ES');

        expect(api.exactVoice.value).toBeNull();

        await vi.advanceTimersByTimeAsync(2000);

        expect(api.exactVoice.value).toBe(false);
    });

    it('is true at once when the voice is already listed', async () => {
        voices = [{ lang: 'es-ES' }];
        const { api } = harness('es-ES');

        await vi.advanceTimersByTimeAsync(0);

        expect(api.exactVoice.value).toBe(true);
    });

    it('turns true when the voice arrives after it was ruled out', async () => {
        const { api } = harness('es-ES');

        await vi.advanceTimersByTimeAsync(2000);

        expect(api.exactVoice.value).toBe(false);

        voices = [{ lang: 'es-ES' }];
        fire();

        expect(api.exactVoice.value).toBe(true);
    });

    it('stays false for a voice of another country, and stops listening when unmounted', async () => {
        const { api, wrapper } = harness('pt-PT');

        voices = [{ lang: 'pt-BR' }];
        fire();
        await vi.advanceTimersByTimeAsync(2000);

        expect(api.exactVoice.value).toBe(false);

        wrapper.unmount();

        expect(listeners.size).toBe(0);
    });

    it('has no voice without a language, or without speech synthesis', async () => {
        const none = harness(null);

        await vi.advanceTimersByTimeAsync(0);

        expect(none.api.exactVoice.value).toBe(false);

        vi.stubGlobal('speechSynthesis', undefined);
        delete (window as unknown as Record<string, unknown>).speechSynthesis;
        const unsupported = harness('es-ES');

        await vi.advanceTimersByTimeAsync(0);

        expect(unsupported.api.exactVoice.value).toBe(false);
    });
});
