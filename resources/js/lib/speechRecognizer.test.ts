import { afterEach, describe, expect, it, vi } from 'vitest';
import {
    browserRecognizer,
    isBrowserRecognitionSupported,
} from './speechRecognizer';

class FakeRecognition {
    static last: FakeRecognition;

    lang = '';
    interimResults = true;
    maxAlternatives = 5;
    onresult: ((event: unknown) => void) | null = null;
    onerror: ((event: unknown) => void) | null = null;
    onend: (() => void) | null = null;
    start = vi.fn();
    stop = vi.fn();

    constructor() {
        FakeRecognition.last = this;
    }
}

const handlers = () => ({
    onResult: vi.fn(),
    onFailure: vi.fn(),
    onEnd: vi.fn(),
});

afterEach(() => {
    vi.unstubAllGlobals();
    delete (window as unknown as Record<string, unknown>).SpeechRecognition;
    delete (window as unknown as Record<string, unknown>)
        .webkitSpeechRecognition;
});

describe('the browser recogniser', () => {
    it('knows whether the browser can recognise speech', () => {
        expect(isBrowserRecognitionSupported()).toBe(false);

        vi.stubGlobal('webkitSpeechRecognition', FakeRecognition);

        expect(isBrowserRecognitionSupported()).toBe(true);
    });

    it('listens once, for one result, in the language it was given', () => {
        vi.stubGlobal('SpeechRecognition', FakeRecognition);
        const events = handlers();

        const recognizer = browserRecognizer('pt-PT', events);
        const raw = FakeRecognition.last;
        raw.onresult?.({ results: [[{ transcript: 'obrigado' }]] });
        raw.onend?.();
        recognizer.start();
        recognizer.stop();

        expect(raw.lang).toBe('pt-PT');
        expect(raw.interimResults).toBe(false);
        expect(raw.maxAlternatives).toBe(1);
        expect(events.onResult).toHaveBeenCalledWith('obrigado');
        expect(events.onEnd).toHaveBeenCalled();
        expect(raw.start).toHaveBeenCalled();
        expect(raw.stop).toHaveBeenCalled();
    });

    it('leaves the browser default language when there is none', () => {
        vi.stubGlobal('webkitSpeechRecognition', FakeRecognition);

        browserRecognizer(null, handlers());

        expect(FakeRecognition.last.lang).toBe('');
    });

    it('tells a refused microphone from a try that could not be heard', () => {
        vi.stubGlobal('SpeechRecognition', FakeRecognition);
        const events = handlers();

        browserRecognizer('es-ES', events);
        const raw = FakeRecognition.last;
        raw.onerror?.({ error: 'not-allowed' });
        raw.onerror?.({ error: 'service-not-allowed' });
        raw.onerror?.({ error: 'no-speech' });

        expect(events.onFailure.mock.calls).toEqual([
            ['denied'],
            ['denied'],
            ['failed'],
        ]);
    });

    it('refuses to start where there is no recogniser', () => {
        expect(() => browserRecognizer('es-ES', handlers())).toThrow();
    });
});
