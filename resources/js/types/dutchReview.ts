export interface DutchReviewLanguage {
    code: string;
    name: string;
}

export interface DutchReviewUnit {
    slug: string;
    title: string;
    level: string;
    suggestions: number;
}

export interface DutchReviewItem {
    id: number;
    term: string;
    english: string;
    dutch: string | null;
    suggestion: string | null;
    note: string | null;
}
