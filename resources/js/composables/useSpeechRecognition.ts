import { onUnmounted, ref } from 'vue';
import {
    browserRecognizer,
    isBrowserRecognitionSupported,
} from '@/lib/speechRecognizer';
import type {
    Recognizer,
    RecognitionFailure,
    RecognizerFactory,
} from '@/lib/speechRecognizer';

/**
 * Listens once for a spoken try. Which engine recognises the speech is the
 * factory's business, so swapping the browser's for another is one argument.
 */
export function useSpeechRecognition(
    locale: () => string | null,
    factory: RecognizerFactory = browserRecognizer,
    supported: () => boolean = isBrowserRecognitionSupported,
) {
    const isSupported = supported();
    const isListening = ref(false);
    const failure = ref<RecognitionFailure | null>(null);

    let recognizer: Recognizer | null = null;
    let onTranscript: ((transcript: string) => void) | null = null;

    function start(callback: (transcript: string) => void): void {
        if (!isSupported || isListening.value) {
            return;
        }

        failure.value = null;
        onTranscript = callback;
        recognizer = factory(locale(), {
            onResult: (transcript) => {
                const callback = onTranscript;

                onTranscript = null;
                callback?.(transcript);
            },
            onFailure: (reason) => {
                failure.value = reason;
                isListening.value = false;
            },
            onEnd: () => {
                isListening.value = false;
            },
        });
        isListening.value = true;

        try {
            recognizer.start();
        } catch {
            recognizer = null;
            failure.value = 'failed';
            isListening.value = false;
        }
    }

    function stop(): void {
        onTranscript = null;
        recognizer?.stop();
        recognizer = null;
        isListening.value = false;
    }

    onUnmounted(() => {
        onTranscript = null;
        recognizer?.abort();
        recognizer = null;
    });

    return { isSupported, isListening, failure, start, stop };
}
