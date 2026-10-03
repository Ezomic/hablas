import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import type { Mock } from 'vitest';
import { defineComponent } from 'vue';
import type {
    RecognizerFactory,
    RecognizerHandlers,
} from '@/lib/speechRecognizer';
import { useSpeechRecognition } from './useSpeechRecognition';

function fake() {
    const state: {
        handlers: RecognizerHandlers | null;
        locale: string | null;
        start: Mock<() => void>;
        stop: Mock<() => void>;
    } = { handlers: null, locale: null, start: vi.fn(), stop: vi.fn() };

    const factory: RecognizerFactory = (locale, handlers) => {
        state.locale = locale;
        state.handlers = handlers;

        return { start: state.start, stop: state.stop };
    };

    return { state, factory };
}

function harness(factory: RecognizerFactory, supported = true) {
    let api!: ReturnType<typeof useSpeechRecognition>;
    const wrapper = mount(
        defineComponent({
            setup() {
                api = useSpeechRecognition(
                    () => 'es-ES',
                    factory,
                    () => supported,
                );

                return () => null;
            },
        }),
    );

    return { wrapper, api };
}

describe('useSpeechRecognition', () => {
    it('listens in the language of the course and hands over what it heard', () => {
        const { state, factory } = fake();
        const { api } = harness(factory);
        const heard = vi.fn();

        api.start(heard);
        state.handlers?.onResult('la llave');
        state.handlers?.onEnd();

        expect(state.locale).toBe('es-ES');
        expect(state.start).toHaveBeenCalledOnce();
        expect(heard).toHaveBeenCalledWith('la llave');
        expect(api.isListening.value).toBe(false);
    });

    it('is listening between start and the end of the recogniser, and starts once', () => {
        const { state, factory } = fake();
        const { api } = harness(factory);

        api.start(vi.fn());
        api.start(vi.fn());

        expect(api.isListening.value).toBe(true);
        expect(state.start).toHaveBeenCalledOnce();
    });

    it('says why it failed, and stops listening', () => {
        const { state, factory } = fake();
        const { api } = harness(factory);

        api.start(vi.fn());
        state.handlers?.onFailure('denied');

        expect(api.failure.value).toBe('denied');
        expect(api.isListening.value).toBe(false);

        api.start(vi.fn());

        expect(api.failure.value).toBeNull();
    });

    it('does nothing in a browser that cannot recognise speech', () => {
        const { state, factory } = fake();
        const { api } = harness(factory, false);

        api.start(vi.fn());

        expect(api.isSupported).toBe(false);
        expect(state.start).not.toHaveBeenCalled();
    });

    it('stops listening, and drops the result, when stopped or unmounted', () => {
        const { state, factory } = fake();
        const { api, wrapper } = harness(factory);
        const heard = vi.fn();

        api.start(heard);
        api.stop();
        state.handlers?.onResult('late');

        expect(state.stop).toHaveBeenCalled();
        expect(heard).not.toHaveBeenCalled();

        api.start(vi.fn());
        wrapper.unmount();

        expect(state.stop).toHaveBeenCalledTimes(2);
    });
});
