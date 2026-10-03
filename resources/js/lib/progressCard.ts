import type { ProgressSnapshot } from '@/components/ProgressSnapshotSummary.vue';
import { i18n } from '@/i18n';

export const PROGRESS_CARD_WIDTH = 1200;
export const PROGRESS_CARD_HEIGHT = 630;

export interface ProgressCardText {
    language: string;
    level: string;
    streak: string;
    completion: string;
}

const FONT_FAMILY = '"Instrument Sans", system-ui, sans-serif';
const REGULAR = 400;
const SEMIBOLD = 600;

// Fixed rather than read from the theme, so a card downloaded in dark mode
// looks the same as one downloaded in light mode.
const PALETTE = {
    background: '#171115',
    foreground: '#f3eaef',
    muted: '#b8a0ad',
    divider: '#40313a',
    accent: '#f472b6',
};

const MARGIN = 80;
const DIVIDER_X = 640;
const RIGHT_COLUMN_X = DIVIDER_X + MARGIN;

export function progressCardText(snapshot: ProgressSnapshot): ProgressCardText {
    const { t } = i18n.global;

    return {
        language: snapshot.language.name,
        level: snapshot.blendedLevel ?? t('progress.card.noLevel'),
        streak: t('common.days', snapshot.streak.currentLength),
        completion: `${snapshot.unitCompletionPercentage}%`,
    };
}

export function progressCardFileName(
    snapshot: ProgressSnapshot,
    date: Date = new Date(),
): string {
    const day = [
        date.getFullYear(),
        String(date.getMonth() + 1).padStart(2, '0'),
        String(date.getDate()).padStart(2, '0'),
    ].join('-');

    return `hablas-progress-${snapshot.language.code}-${day}.png`;
}

function font(weight: number, size: number): string {
    return `${weight} ${size}px ${FONT_FAMILY}`;
}

function fitFont(
    context: CanvasRenderingContext2D,
    text: string,
    weight: number,
    size: number,
    maxWidth: number,
): void {
    context.font = font(weight, size);

    while (size > 24 && context.measureText(text).width > maxWidth) {
        size -= 4;
        context.font = font(weight, size);
    }
}

function drawText(
    context: CanvasRenderingContext2D,
    text: string,
    x: number,
    y: number,
    style: { weight: number; size: number; color: string; maxWidth: number },
): void {
    fitFont(context, text, style.weight, style.size, style.maxWidth);
    context.fillStyle = style.color;
    context.fillText(text, x, y);
}

export function drawProgressCard(
    context: CanvasRenderingContext2D,
    text: ProgressCardText,
): void {
    const leftWidth = DIVIDER_X - 2 * MARGIN;
    const rightWidth = PROGRESS_CARD_WIDTH - RIGHT_COLUMN_X - MARGIN;
    const { t } = i18n.global;
    const label = { weight: REGULAR, size: 32, color: PALETTE.muted };
    const value = { weight: SEMIBOLD, size: 72, color: PALETTE.foreground };

    context.fillStyle = PALETTE.background;
    context.fillRect(0, 0, PROGRESS_CARD_WIDTH, PROGRESS_CARD_HEIGHT);
    context.fillStyle = PALETTE.accent;
    context.fillRect(0, 0, PROGRESS_CARD_WIDTH, 12);
    context.textBaseline = 'alphabetic';

    context.textAlign = 'left';
    drawText(context, 'Hablas', MARGIN, 140, {
        weight: SEMIBOLD,
        size: 40,
        color: PALETTE.foreground,
        maxWidth: leftWidth,
    });

    context.textAlign = 'right';
    drawText(context, text.language, PROGRESS_CARD_WIDTH - MARGIN, 140, {
        ...label,
        size: 36,
        maxWidth: rightWidth,
    });

    context.textAlign = 'left';
    drawText(context, t('progress.card.level'), MARGIN, 280, {
        ...label,
        maxWidth: leftWidth,
    });
    // Centred rather than on a baseline, so "No level yet", shrunk to fit,
    // doesn't sink to the bottom of the space a two-character level fills.
    context.textBaseline = 'middle';
    drawText(context, text.level, MARGIN, 415, {
        weight: SEMIBOLD,
        size: 200,
        color: PALETTE.accent,
        maxWidth: leftWidth,
    });
    context.textBaseline = 'alphabetic';

    context.strokeStyle = PALETTE.divider;
    context.lineWidth = 2;
    context.beginPath();
    context.moveTo(DIVIDER_X, 240);
    context.lineTo(DIVIDER_X, 520);
    context.stroke();

    drawText(context, t('progress.card.streak'), RIGHT_COLUMN_X, 280, {
        ...label,
        maxWidth: rightWidth,
    });
    drawText(context, text.streak, RIGHT_COLUMN_X, 360, {
        ...value,
        maxWidth: rightWidth,
    });
    drawText(context, t('progress.card.units'), RIGHT_COLUMN_X, 440, {
        ...label,
        maxWidth: rightWidth,
    });
    drawText(context, text.completion, RIGHT_COLUMN_X, 520, {
        ...value,
        maxWidth: rightWidth,
    });
}

// A canvas draws with whatever face is loaded at that moment and never
// redraws, so every weight has to be loaded first or the card falls back to
// the system font.
async function loadFonts(): Promise<void> {
    await Promise.all(
        [REGULAR, SEMIBOLD].map((weight) =>
            document.fonts.load(font(weight, 32)),
        ),
    );
}

export async function renderProgressCard(
    snapshot: ProgressSnapshot,
): Promise<Blob> {
    await loadFonts();

    const canvas = document.createElement('canvas');
    canvas.width = PROGRESS_CARD_WIDTH;
    canvas.height = PROGRESS_CARD_HEIGHT;

    const context = canvas.getContext('2d');

    if (context === null) {
        throw new Error('This browser cannot draw on a canvas.');
    }

    drawProgressCard(context, progressCardText(snapshot));

    return new Promise((resolve, reject) => {
        canvas.toBlob((blob) => {
            if (blob === null) {
                reject(new Error('The canvas produced no image.'));

                return;
            }

            resolve(blob);
        }, 'image/png');
    });
}
