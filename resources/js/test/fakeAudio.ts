import { vi } from 'vitest';

export class FakeAudio {
    static instances: FakeAudio[] = [];
    static playError: DOMException | null = null;

    onplaying: (() => void) | null = null;
    onended: (() => void) | null = null;
    onerror: (() => void) | null = null;
    currentTime = 0;
    preload = '';
    pause = vi.fn();
    play = vi.fn(() =>
        FakeAudio.playError
            ? Promise.reject(FakeAudio.playError)
            : Promise.resolve(),
    );

    constructor(public src: string) {
        FakeAudio.instances.push(this);
    }

    static reset(): void {
        FakeAudio.instances = [];
        FakeAudio.playError = null;
    }

    static last(): FakeAudio {
        return FakeAudio.instances[FakeAudio.instances.length - 1];
    }
}
