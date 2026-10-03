import { ref } from 'vue';
import type { SpeechSpeed } from '@/types/speech';

const STORAGE_KEY = 'hablas.speech-speed';

function read(): SpeechSpeed {
    try {
        return window.localStorage.getItem(STORAGE_KEY) === 'slow'
            ? 'slow'
            : 'normal';
    } catch {
        return 'normal';
    }
}

const speed = ref<SpeechSpeed>(read());

export function useSpeechSpeed() {
    function remember(next: SpeechSpeed): void {
        speed.value = next;

        try {
            window.localStorage.setItem(STORAGE_KEY, next);
        } catch {
            // The choice still holds for this page without storage.
        }
    }

    return { speed, remember };
}
