import { onUnmounted, ref } from 'vue';
import { hasExactVoice, hasExactVoiceNow } from '@/composables/useSpeech';

/**
 * Whether the browser has a voice for exactly this tag: null while it is
 * still being looked for, then true or false. A voice that arrives after it
 * was ruled out still turns it true, so steps that come later are no longer
 * swapped.
 */
export function useExactVoice(locale: () => string | null) {
    const exactVoice = ref<boolean | null>(null);
    const tag = locale();
    const synthesis =
        typeof window !== 'undefined' && 'speechSynthesis' in window
            ? window.speechSynthesis
            : null;

    const onChange = (): void => {
        if (tag !== null && hasExactVoiceNow(tag)) {
            exactVoice.value = true;
        }
    };

    void hasExactVoice(tag).then((found) => {
        exactVoice.value ??= found;
    });
    synthesis?.addEventListener('voiceschanged', onChange);
    onUnmounted(() =>
        synthesis?.removeEventListener('voiceschanged', onChange),
    );

    return { exactVoice };
}
