import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import {
    initializeInstallPrompt,
    resetInstallPromptForTests,
    useInstallPrompt,
} from './useInstallPrompt';

const IOS_UA =
    'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Version/17.0 Mobile/15E148 Safari/604.1';
const ANDROID_UA =
    'Mozilla/5.0 (Linux; Android 14) AppleWebKit/537.36 Chrome/120.0 Mobile Safari/537.36';

function stubEnvironment(
    userAgent: string,
    standalone = false,
    extra: Record<string, unknown> = {},
): void {
    vi.stubGlobal('navigator', { userAgent, ...extra });
    window.matchMedia = vi.fn().mockReturnValue({
        matches: standalone,
        addEventListener: vi.fn(),
        removeEventListener: vi.fn(),
    }) as never;
}

function promptEvent(outcome: 'accepted' | 'dismissed' = 'accepted') {
    const event = new Event('beforeinstallprompt', { cancelable: true });

    return Object.assign(event, {
        prompt: vi.fn().mockResolvedValue(undefined),
        userChoice: Promise.resolve({ outcome }),
    });
}

beforeEach(() => {
    vi.useFakeTimers();
    resetInstallPromptForTests();
});

afterEach(() => {
    vi.useRealTimers();
    vi.unstubAllGlobals();
});

describe('useInstallPrompt', () => {
    it('is not installable until the browser offers the prompt', () => {
        stubEnvironment('Mozilla/5.0 Chrome/120');
        initializeInstallPrompt();
        const { canInstall, hint } = useInstallPrompt();

        expect(canInstall.value).toBe(false);
        expect(hint.value).toBeNull();

        const event = promptEvent();
        window.dispatchEvent(event);

        expect(event.defaultPrevented).toBe(true);
        expect(canInstall.value).toBe(true);
    });

    it('triggers the saved prompt and hides the button once accepted', async () => {
        stubEnvironment('Mozilla/5.0 Chrome/120');
        initializeInstallPrompt();
        const { canInstall, install } = useInstallPrompt();
        const event = promptEvent('accepted');
        window.dispatchEvent(event);

        await install();

        expect(event.prompt).toHaveBeenCalledOnce();
        expect(canInstall.value).toBe(false);
    });

    it('does nothing when there is no saved prompt', async () => {
        stubEnvironment('Mozilla/5.0 Chrome/120');
        initializeInstallPrompt();

        await expect(useInstallPrompt().install()).resolves.toBeUndefined();
    });

    it('hides everything when already running standalone', () => {
        stubEnvironment(ANDROID_UA, true);
        initializeInstallPrompt();
        window.dispatchEvent(promptEvent());
        vi.advanceTimersByTime(5000);
        const { canInstall, hint } = useInstallPrompt();

        expect(canInstall.value).toBe(false);
        expect(hint.value).toBeNull();
    });

    it('hides the button after the appinstalled event', () => {
        stubEnvironment('Mozilla/5.0 Chrome/120');
        initializeInstallPrompt();
        window.dispatchEvent(promptEvent());
        window.dispatchEvent(new Event('appinstalled'));

        expect(useInstallPrompt().canInstall.value).toBe(false);
    });

    it('shows the Share hint on iOS Safari straight away', () => {
        stubEnvironment(IOS_UA);
        initializeInstallPrompt();

        expect(useInstallPrompt().hint.value).toBe('ios');
    });

    it('gives other iOS browsers no hint', () => {
        stubEnvironment(`${IOS_UA} CriOS/120`);
        initializeInstallPrompt();

        expect(useInstallPrompt().hint.value).toBeNull();
    });

    it('shows the browser menu hint on Android only after the prompt did not arrive', () => {
        stubEnvironment(ANDROID_UA);
        initializeInstallPrompt();
        const { hint } = useInstallPrompt();

        expect(hint.value).toBeNull();

        vi.advanceTimersByTime(3000);

        expect(hint.value).toBe('android');
    });

    it('prefers the real prompt over the Android hint', () => {
        stubEnvironment(ANDROID_UA);
        initializeInstallPrompt();
        vi.advanceTimersByTime(3000);
        window.dispatchEvent(promptEvent());

        expect(useInstallPrompt().hint.value).toBeNull();
    });

    it('treats an iPad that reports as a Mac as iOS', () => {
        stubEnvironment(
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605 Version/17 Safari/605',
            false,
            { maxTouchPoints: 5 },
        );
        initializeInstallPrompt();

        expect(useInstallPrompt().hint.value).toBe('ios');
    });

    it('gives a real Mac no hint', () => {
        stubEnvironment(
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
            false,
            {
                maxTouchPoints: 0,
            },
        );
        initializeInstallPrompt();

        expect(useInstallPrompt().hint.value).toBeNull();
    });

    it('suppresses the Android hint when the app is already installed', async () => {
        stubEnvironment(ANDROID_UA, false, {
            getInstalledRelatedApps: vi.fn().mockResolvedValue([{}]),
        });
        initializeInstallPrompt();
        await vi.advanceTimersByTimeAsync(3000);

        expect(useInstallPrompt().hint.value).toBeNull();
    });

    it('keeps the Android hint when the installed apps lookup fails', async () => {
        stubEnvironment(ANDROID_UA, false, {
            getInstalledRelatedApps: vi.fn().mockRejectedValue(new Error('x')),
        });
        initializeInstallPrompt();
        await vi.advanceTimersByTimeAsync(3000);

        expect(useInstallPrompt().hint.value).toBe('android');
    });

    it('removes its listeners on reset', () => {
        stubEnvironment('Mozilla/5.0 Chrome/120');
        initializeInstallPrompt();
        resetInstallPromptForTests();
        window.dispatchEvent(promptEvent());

        expect(useInstallPrompt().canInstall.value).toBe(false);
    });
});
