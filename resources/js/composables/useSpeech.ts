import { onUnmounted, ref } from 'vue';

/**
 * Reads target-language text aloud through the browser's speech synthesis.
 *
 * The same reasoning CLAUDE.md records for pronunciation scoring applies to
 * output: SpeechSynthesisUtterance is free and client side, so vocabulary and
 * shadowing prompts get audio without a recording pipeline, storage or
 * licensing. A real recording still wins when one exists, which is what the
 * audioUrl argument is for.
 */
export function useSpeech(locale: () => string | null) {
    const isSupported =
        typeof window !== 'undefined' && 'speechSynthesis' in window;

    const isSpeaking = ref(false);
    let audio: HTMLAudioElement | null = null;

    /**
     * Prefers an exact tag match, then any voice for the same base language, so
     * a browser shipping only pt-BR still reads Portuguese rather than falling
     * back to the user's system language.
     */
    function pickVoice(tag: string): SpeechSynthesisVoice | null {
        const voices = window.speechSynthesis.getVoices();
        const base = tag.split('-')[0];

        return (
            voices.find((voice) => voice.lang === tag) ??
            voices.find((voice) => voice.lang.startsWith(base)) ??
            null
        );
    }

    function speak(text: string, audioUrl: string | null = null): void {
        cancel();

        if (audioUrl) {
            audio = new Audio(audioUrl);
            audio.onended = () => (isSpeaking.value = false);
            audio.onerror = () => (isSpeaking.value = false);
            isSpeaking.value = true;
            void audio.play().catch(() => (isSpeaking.value = false));

            return;
        }

        if (!isSupported || !text.trim()) {
            return;
        }

        const utterance = new SpeechSynthesisUtterance(text);
        const tag = locale();

        if (tag) {
            utterance.lang = tag;

            const voice = pickVoice(tag);

            if (voice) {
                utterance.voice = voice;
            }
        }

        utterance.onend = () => (isSpeaking.value = false);
        utterance.onerror = () => (isSpeaking.value = false);

        isSpeaking.value = true;
        window.speechSynthesis.speak(utterance);
    }

    function cancel(): void {
        if (audio) {
            audio.pause();
            audio = null;
        }

        if (isSupported) {
            window.speechSynthesis.cancel();
        }

        isSpeaking.value = false;
    }

    onUnmounted(cancel);

    return { isSupported, isSpeaking, speak, cancel };
}
