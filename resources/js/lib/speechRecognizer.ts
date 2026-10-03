export type RecognitionFailure = 'denied' | 'failed';

export interface RecognizerHandlers {
    onResult: (transcript: string) => void;
    onFailure: (failure: RecognitionFailure) => void;
    onEnd: () => void;
}

export interface Recognizer {
    start(): void;
    stop(): void;
}

// The seam where the recogniser is chosen: the browser's own today, another
// engine later, without the composable or the exercise changing.
export type RecognizerFactory = (
    locale: string | null,
    handlers: RecognizerHandlers,
) => Recognizer;

export function isBrowserRecognitionSupported(): boolean {
    return (
        typeof window !== 'undefined' &&
        ('SpeechRecognition' in window || 'webkitSpeechRecognition' in window)
    );
}

export const browserRecognizer: RecognizerFactory = (locale, handlers) => {
    const Recognition =
        window.SpeechRecognition ?? window.webkitSpeechRecognition;

    if (!Recognition) {
        throw new Error('Speech recognition is not supported here.');
    }

    const recognition = new Recognition();

    // Left on the browser default when the server has no tag for the
    // language, rather than asserting one that would mistranscribe.
    if (locale) {
        recognition.lang = locale;
    }

    recognition.interimResults = false;
    recognition.maxAlternatives = 1;
    recognition.onresult = (event) =>
        handlers.onResult(event.results[0][0].transcript);
    recognition.onerror = (event) => {
        const error = (event as Event & { error?: string }).error;

        handlers.onFailure(
            error === 'not-allowed' || error === 'service-not-allowed'
                ? 'denied'
                : 'failed',
        );
    };
    recognition.onend = handlers.onEnd;

    return recognition;
};
