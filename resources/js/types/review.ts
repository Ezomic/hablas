export type Rating = 'again' | 'hard' | 'good' | 'easy';

export type ErrorTag =
    | 'wrong_gender'
    | 'ser_estar_confusion'
    | 'false_friend'
    | 'wrong_tense'
    | 'portunol_slip'
    | 'other';

export interface ReviewCard {
    id: number;
    front: string;
    back: string;
    kind: 'vocabulary' | 'grammar';
    suggestedErrorTag: ErrorTag | null;
}
