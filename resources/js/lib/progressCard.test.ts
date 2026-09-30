import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import type { ProgressSnapshot } from '@/components/ProgressSnapshotSummary.vue';
import {
    PROGRESS_CARD_HEIGHT,
    PROGRESS_CARD_WIDTH,
    progressCardFileName,
    progressCardText,
    renderProgressCard,
} from './progressCard';

function snapshot(overrides: Partial<ProgressSnapshot> = {}): ProgressSnapshot {
    return {
        language: { code: 'es', name: 'Spanish' },
        blendedLevel: 'A2',
        skillLevels: {
            reading: 'A2',
            listening: 'A2',
            speaking: 'A2',
            writing: 'B1',
        },
        streak: { currentLength: 12, longestLength: 20 },
        unitCompletionPercentage: 25,
        topErrorTags: [],
        ...overrides,
    };
}

describe('progressCardText', () => {
    it('summarizes the language, level, streak and unit completion', () => {
        expect(progressCardText(snapshot())).toEqual({
            language: 'Spanish',
            level: 'A2',
            streak: '12 days',
            completion: '25%',
        });
    });

    it('says one day, not one days', () => {
        const text = progressCardText(
            snapshot({ streak: { currentLength: 1, longestLength: 3 } }),
        );

        expect(text.streak).toBe('1 day');
    });

    it('counts an empty streak in days', () => {
        const text = progressCardText(
            snapshot({ streak: { currentLength: 0, longestLength: 0 } }),
        );

        expect(text.streak).toBe('0 days');
    });

    it('says there is no level yet instead of leaving the headline empty', () => {
        const text = progressCardText(snapshot({ blendedLevel: null }));

        expect(text.level).toBe('No level yet');
    });
});

describe('progressCardFileName', () => {
    it('names the file after the language and the local date', () => {
        const name = progressCardFileName(
            snapshot({ language: { code: 'pt', name: 'Portuguese' } }),
            new Date(2026, 8, 3, 23, 30),
        );

        expect(name).toBe('hablas-progress-pt-2026-09-03.png');
    });
});

type Call = {
    method: string;
    args: unknown[];
    font: string;
    fillStyle: string;
};

function fakeContext() {
    const calls: Call[] = [];
    const context = {
        font: '',
        fillStyle: '',
        strokeStyle: '',
        lineWidth: 1,
        textAlign: 'left',
        textBaseline: 'alphabetic',
    } as Record<string, unknown>;

    for (const method of [
        'fillRect',
        'fillText',
        'beginPath',
        'moveTo',
        'lineTo',
        'stroke',
    ]) {
        context[method] = (...args: unknown[]) => {
            calls.push({
                method,
                args,
                font: context.font as string,
                fillStyle: context.fillStyle as string,
            });
        };
    }

    // Roughly half an em per character, enough to make long text overflow.
    context.measureText = (text: string) => ({
        width:
            text.length *
            Number(/(\d+)px/.exec(context.font as string)?.[1]) *
            0.5,
    });

    return { context, calls };
}

describe('renderProgressCard', () => {
    let fontLoads: string[];
    let blob: Blob | null;
    let canvases: HTMLCanvasElement[];
    let context: Record<string, unknown> | null;
    let calls: Call[];

    beforeEach(() => {
        fontLoads = [];
        blob = new Blob(['png'], { type: 'image/png' });
        canvases = [];
        ({ context, calls } = fakeContext());

        Object.defineProperty(document, 'fonts', {
            configurable: true,
            value: {
                load: vi.fn(async (font: string) => {
                    fontLoads.push(font);

                    return [];
                }),
            },
        });

        vi.spyOn(HTMLCanvasElement.prototype, 'getContext').mockImplementation(
            function (this: HTMLCanvasElement) {
                canvases.push(this);

                return context as unknown as CanvasRenderingContext2D;
            } as unknown as HTMLCanvasElement['getContext'],
        );

        vi.spyOn(HTMLCanvasElement.prototype, 'toBlob').mockImplementation(
            (callback: BlobCallback, type?: string) => {
                expect(type).toBe('image/png');
                callback(blob);
            },
        );
    });

    afterEach(() => {
        vi.restoreAllMocks();
        Reflect.deleteProperty(document, 'fonts');
    });

    it('draws the card at 1200 by 630 pixels and returns it as a PNG', async () => {
        const result = await renderProgressCard(snapshot());

        expect(result).toBe(blob);
        expect(canvases).toHaveLength(1);
        expect(canvases[0].width).toBe(PROGRESS_CARD_WIDTH);
        expect(canvases[0].height).toBe(PROGRESS_CARD_HEIGHT);
        expect(PROGRESS_CARD_WIDTH).toBe(1200);
        expect(PROGRESS_CARD_HEIGHT).toBe(630);
    });

    it('loads the app font in every weight it draws with before drawing', async () => {
        await renderProgressCard(snapshot());

        expect(fontLoads).toHaveLength(2);
        expect(
            fontLoads.every((font) => font.includes('"Instrument Sans"')),
        ).toBe(true);

        const fontsDrawn = new Set(
            calls
                .filter((call) => call.method === 'fillText')
                .map((call) => call.font.split(' ')[0]),
        );
        const fontsLoaded = new Set(
            fontLoads.map((font) => font.split(' ')[0]),
        );

        expect(fontsDrawn).toEqual(fontsLoaded);
    });

    it('writes the level, language, streak and completion on the card', async () => {
        await renderProgressCard(snapshot());

        const texts = calls
            .filter((call) => call.method === 'fillText')
            .map((call) => call.args[0]);

        expect(texts).toEqual(
            expect.arrayContaining([
                'Hablas',
                'Spanish',
                'A2',
                'Streak',
                '12 days',
                'Units completed',
                '25%',
            ]),
        );
    });

    it('paints a fixed palette whatever the page theme is', async () => {
        document.documentElement.classList.add('dark');
        await renderProgressCard(snapshot());
        const dark = calls.map((call) => call.fillStyle);

        document.documentElement.classList.remove('dark');
        ({ context, calls } = fakeContext());
        await renderProgressCard(snapshot());
        const light = calls.map((call) => call.fillStyle);

        expect(dark).toEqual(light);
        expect(calls[0]).toMatchObject({
            method: 'fillRect',
            args: [0, 0, PROGRESS_CARD_WIDTH, PROGRESS_CARD_HEIGHT],
        });
    });

    it('shrinks a headline that would not fit its half of the card', async () => {
        const fontSizeOf = (text: string) => {
            const call = calls.find(
                (candidate) =>
                    candidate.method === 'fillText' &&
                    candidate.args[0] === text,
            );

            return Number(/(\d+)px/.exec(call?.font ?? '')?.[1]);
        };

        await renderProgressCard(snapshot());
        const levelSize = fontSizeOf('A2');

        ({ context, calls } = fakeContext());
        await renderProgressCard(snapshot({ blendedLevel: null }));
        const headlineSize = fontSizeOf('No level yet');

        expect(headlineSize).toBeLessThan(levelSize);
        expect('No level yet'.length * headlineSize * 0.5).toBeLessThanOrEqual(
            PROGRESS_CARD_WIDTH / 2,
        );
    });

    it('fails when the browser has no 2D canvas', async () => {
        context = null;

        await expect(renderProgressCard(snapshot())).rejects.toThrow();
    });

    it('fails when the canvas produces no image', async () => {
        blob = null;

        await expect(renderProgressCard(snapshot())).rejects.toThrow();
    });
});
