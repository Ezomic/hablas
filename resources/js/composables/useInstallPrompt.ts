import { computed, ref } from 'vue';

interface BeforeInstallPromptEvent extends Event {
    prompt: () => Promise<void>;
    userChoice: Promise<{ outcome: 'accepted' | 'dismissed' }>;
}

export type InstallHint = 'ios' | 'android' | null;

const ANDROID_HINT_DELAY_MS = 2500;

const deferredPrompt = ref<BeforeInstallPromptEvent | null>(null);
const installed = ref(false);
const androidHintReady = ref(false);

let initialized = false;

function isStandalone(): boolean {
    const iosStandalone = (navigator as Navigator & { standalone?: boolean })
        .standalone;

    return (
        iosStandalone === true ||
        (typeof window.matchMedia === 'function' &&
            window.matchMedia('(display-mode: standalone)').matches)
    );
}

function platform(): 'ios' | 'android' | 'other' {
    const ua = navigator.userAgent;

    if (/iPad|iPhone|iPod/.test(ua)) {
        return /CriOS|FxiOS|EdgiOS/.test(ua) ? 'other' : 'ios';
    }

    return /Android/.test(ua) ? 'android' : 'other';
}

export function initializeInstallPrompt(): void {
    if (initialized || typeof window === 'undefined') {
        return;
    }

    initialized = true;
    installed.value = isStandalone();

    window.addEventListener('beforeinstallprompt', (event) => {
        event.preventDefault();
        deferredPrompt.value = event as BeforeInstallPromptEvent;
    });

    window.addEventListener('appinstalled', () => {
        installed.value = true;
        deferredPrompt.value = null;
    });

    if (typeof window.matchMedia === 'function') {
        window
            .matchMedia('(display-mode: standalone)')
            .addEventListener('change', (event) => {
                installed.value = event.matches;
            });
    }

    // Chrome fires beforeinstallprompt shortly after load, so the manual
    // Android hint waits to avoid flashing before the real button appears.
    window.setTimeout(() => {
        androidHintReady.value = true;
    }, ANDROID_HINT_DELAY_MS);
}

export function resetInstallPromptForTests(): void {
    initialized = false;
    deferredPrompt.value = null;
    installed.value = false;
    androidHintReady.value = false;
}

export function useInstallPrompt() {
    const canInstall = computed(
        () => !installed.value && deferredPrompt.value !== null,
    );

    const hint = computed<InstallHint>(() => {
        if (installed.value || deferredPrompt.value !== null) {
            return null;
        }

        const current = platform();

        if (current === 'ios') {
            return 'ios';
        }

        return current === 'android' && androidHintReady.value
            ? 'android'
            : null;
    });

    async function install(): Promise<void> {
        const prompt = deferredPrompt.value;

        if (prompt === null) {
            return;
        }

        deferredPrompt.value = null;
        await prompt.prompt();

        const { outcome } = await prompt.userChoice;

        if (outcome === 'accepted') {
            installed.value = true;
        }
    }

    return { canInstall, hint, install };
}
