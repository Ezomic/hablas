import { onUnmounted, ref } from 'vue';
import type { SpeechSpeed } from '@/types/speech';

const SLOW_RATE = 0.75;
const CACHE_SIZE = 4;
const START_GRACE_MS = 2000;
const LOAD_TIMEOUT_MS = 7000;
const VOICE_WAIT_MS = 2000;

export interface SpeakOptions {
    speed?: SpeechSpeed;
}

// Android lists voices as es_ES, desktop browsers as es-ES.
function sameTag(voice: SpeechSynthesisVoice, tag: string): boolean {
    return voice.lang.replace('_', '-') === tag;
}

export function hasExactVoiceNow(tag: string): boolean {
    return window.speechSynthesis
        .getVoices()
        .some((voice) => sameTag(voice, tag));
}

/**
 * Whether the browser has a voice for exactly this tag, so a Brazilian or
 * Latin American voice never teaches the pronunciation of pt-PT or es-ES.
 * Chrome lists no voices until its voiceschanged event, so a missing voice
 * is waited for, for up to two seconds, before it is ruled out.
 */
export function hasExactVoice(tag: string | null): Promise<boolean> {
    if (
        tag === null ||
        typeof window === 'undefined' ||
        !('speechSynthesis' in window)
    ) {
        return Promise.resolve(false);
    }

    if (hasExactVoiceNow(tag)) {
        return Promise.resolve(true);
    }

    const synthesis = window.speechSynthesis;

    return new Promise<boolean>((resolve) => {
        const finish = (): void => {
            clearTimeout(timer);
            synthesis.removeEventListener('voiceschanged', onChange);
            resolve(hasExactVoiceNow(tag));
        };
        const onChange = (): void => {
            if (hasExactVoiceNow(tag)) {
                finish();
            }
        };
        const timer = setTimeout(finish, VOICE_WAIT_MS);

        synthesis.addEventListener('voiceschanged', onChange);
    });
}

/**
 * Reads target-language text aloud. A pre-generated clip plays when the page
 * has one for the string and speed; otherwise, or when the clip cannot be
 * played, the browser's speech synthesis reads it, so a missing or broken
 * file never leaves the learner without audio.
 *
 * speak() resolves true once playback has actually started, so a caller that
 * counts plays (the listening page's replay limit) is not charged for a clip
 * that never came out.
 */
export function useSpeech(locale: () => string | null) {
    const isSupported =
        typeof window !== 'undefined' && 'speechSynthesis' in window;

    const isSpeaking = ref(false);
    const isLoading = ref(false);

    const cache = new Map<string, HTMLAudioElement>();
    let audio: HTMLAudioElement | null = null;
    let audioUrl: string | null = null;
    let playToken = 0;
    let loadTimer: ReturnType<typeof setTimeout> | null = null;
    let utterance: SpeechSynthesisUtterance | null = null;
    let startTimer: ReturnType<typeof setTimeout> | null = null;
    let pending: ((started: boolean) => void) | null = null;

    function finish(started: boolean): void {
        const resolve = pending;

        pending = null;
        resolve?.(started);
    }

    /**
     * Prefers an exact tag match, then any voice for the same base language, so
     * a browser shipping only pt-BR still reads Portuguese rather than falling
     * back to the user's system language.
     */
    function pickVoice(tag: string): SpeechSynthesisVoice | null {
        const voices = window.speechSynthesis.getVoices();
        const base = tag.split('-')[0];

        return (
            voices.find((voice) => sameTag(voice, tag)) ??
            voices.find((voice) => voice.lang.startsWith(base)) ??
            null
        );
    }

    function remember(url: string, element: HTMLAudioElement): void {
        cache.set(url, element);

        for (const key of cache.keys()) {
            if (cache.size <= CACHE_SIZE) {
                break;
            }

            if (cache.get(key) !== audio) {
                cache.delete(key);
            }
        }
    }

    function evictOnError(url: string, element: HTMLAudioElement): void {
        element.onerror = () => {
            if (cache.get(url) === element) {
                cache.delete(url);
            }
        };
    }

    function clip(url: string): HTMLAudioElement {
        const cached = cache.get(url);

        if (cached) {
            return cached;
        }

        const element = new Audio(url);

        element.preload = 'auto';
        evictOnError(url, element);
        remember(url, element);

        return element;
    }

    function prefetch(url: string | null | undefined): void {
        if (url && !cache.has(url)) {
            clip(url);
        }
    }

    function detach(element: HTMLAudioElement, url: string): void {
        element.onplaying = null;
        element.onended = null;
        evictOnError(url, element);
    }

    function clearLoadTimer(): void {
        if (loadTimer) {
            clearTimeout(loadTimer);
            loadTimer = null;
        }
    }

    function abandonClip(): void {
        clearLoadTimer();

        if (audio && audioUrl) {
            cache.delete(audioUrl);
            detach(audio, audioUrl);
            audio.pause();
        }

        audio = null;
        audioUrl = null;
    }

    function speakWithBrowser(text: string, speed: SpeechSpeed): void {
        if (!isSupported || !text.trim()) {
            isLoading.value = false;
            isSpeaking.value = false;
            finish(false);

            return;
        }

        const next = new SpeechSynthesisUtterance(text);
        const tag = locale();

        if (tag) {
            next.lang = tag;

            const voice = pickVoice(tag);

            if (voice) {
                next.voice = voice;
            }
        }

        if (speed === 'slow') {
            next.rate = SLOW_RATE;
        }

        utterance = next;

        next.onstart = () => {
            if (utterance === next) {
                finish(true);
            }
        };
        next.onend = () => {
            if (utterance === next) {
                isSpeaking.value = false;
            }
        };
        next.onerror = () => {
            if (utterance === next) {
                isSpeaking.value = false;
                finish(false);
            }
        };

        isLoading.value = false;
        isSpeaking.value = true;
        startTimer = setTimeout(() => finish(true), START_GRACE_MS);
        window.speechSynthesis.speak(next);
    }

    function playClip(url: string, text: string, speed: SpeechSpeed): void {
        const element = clip(url);
        const token = ++playToken;

        audio = element;
        audioUrl = url;
        isLoading.value = true;

        const fail = (): void => {
            if (token !== playToken) {
                return;
            }

            abandonClip();
            speakWithBrowser(text, speed);
        };

        element.onplaying = () => {
            if (token !== playToken) {
                return;
            }

            clearLoadTimer();
            isLoading.value = false;
            isSpeaking.value = true;
            finish(true);
        };
        element.onended = () => {
            if (token === playToken) {
                isSpeaking.value = false;
            }
        };
        element.onerror = fail;
        loadTimer = setTimeout(fail, LOAD_TIMEOUT_MS);

        if (element.currentTime > 0) {
            element.currentTime = 0;
        }

        void element.play().catch((error: unknown) => {
            if (token !== playToken || !(error instanceof DOMException)) {
                return fail();
            }

            if (error.name === 'AbortError') {
                return;
            }

            if (error.name === 'NotAllowedError') {
                clearLoadTimer();
                detach(element, url);
                audio = null;
                audioUrl = null;
                isLoading.value = false;
                isSpeaking.value = false;
                finish(false);

                return;
            }

            fail();
        });
    }

    function speak(
        text: string,
        audioUrl: string | null = null,
        options: SpeakOptions = {},
    ): Promise<boolean> {
        cancel();

        return new Promise<boolean>((resolve) => {
            pending = resolve;

            if (audioUrl) {
                playClip(audioUrl, text, options.speed ?? 'normal');
            } else {
                speakWithBrowser(text, options.speed ?? 'normal');
            }
        });
    }

    function cancel(): void {
        finish(false);

        if (startTimer) {
            clearTimeout(startTimer);
            startTimer = null;
        }

        playToken++;
        clearLoadTimer();

        if (audio && audioUrl) {
            detach(audio, audioUrl);
            audio.pause();
        }

        audio = null;
        audioUrl = null;
        utterance = null;

        if (isSupported) {
            window.speechSynthesis.cancel();
        }

        isSpeaking.value = false;
        isLoading.value = false;
    }

    onUnmounted(cancel);

    return { isSupported, isSpeaking, isLoading, speak, cancel, prefetch };
}
