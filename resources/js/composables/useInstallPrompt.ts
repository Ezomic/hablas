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
let teardown: (() => void) | null = null;

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

    const isIpadOs = /Macintosh/.test(ua) && navigator.maxTouchPoints > 1;

    if (/iPad|iPhone|iPod/.test(ua) || isIpadOs) {
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

    const onPrompt = (event: Event) => {
        event.preventDefault();
        deferredPrompt.value = event as BeforeInstallPromptEvent;
    };
    const onInstalled = () => {
        installed.value = true;
        deferredPrompt.value = null;
    };
    const onDisplayMode = (event: MediaQueryListEvent) => {
        installed.value = event.matches;
    };
    const displayMode =
        typeof window.matchMedia === 'function'
            ? window.matchMedia('(display-mode: standalone)')
            : null;

    window.addEventListener('beforeinstallprompt', onPrompt);
    window.addEventListener('appinstalled', onInstalled);
    displayMode?.addEventListener('change', onDisplayMode);

    // Chrome fires beforeinstallprompt shortly after load, so the manual
    // Android hint waits to avoid flashing before the real button appears.
    const timer = window.setTimeout(() => {
        androidHintReady.value = true;
    }, ANDROID_HINT_DELAY_MS);

    void detectInstalledRelatedApp();

    teardown = () => {
        window.removeEventListener('beforeinstallprompt', onPrompt);
        window.removeEventListener('appinstalled', onInstalled);
        displayMode?.removeEventListener('change', onDisplayMode);
        window.clearTimeout(timer);
    };
}

async function detectInstalledRelatedApp(): Promise<void> {
    const nav = navigator as Navigator & {
        getInstalledRelatedApps?: () => Promise<unknown[]>;
    };

    if (typeof nav.getInstalledRelatedApps !== 'function') {
        return;
    }

    try {
        if ((await nav.getInstalledRelatedApps()).length > 0) {
            installed.value = true;
        }
    } catch {
        // Best effort only, the hint just stays visible.
    }
}

export function resetInstallPromptForTests(): void {
    teardown?.();
    teardown = null;
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
