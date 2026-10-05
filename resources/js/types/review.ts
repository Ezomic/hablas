import type { SpeechClipUrls } from '@/types/speech';

export type Rating = 'again' | 'hard' | 'good' | 'easy';

export type ErrorTag =
    | 'wrong_gender'
    | 'ser_estar_confusion'
    | 'false_friend'
    | 'wrong_tense'
    | 'portunol_slip'
    | 'other';

export interface ReviewCard extends SpeechClipUrls {
    id: number;
    front: string;
    back: string;
    kind: 'vocabulary' | 'grammar';
    direction: 'recognition' | 'production';
    needsArticle: boolean;
    mask?: (string | null)[] | null;
    suggestedErrorTag: ErrorTag | null;
}
