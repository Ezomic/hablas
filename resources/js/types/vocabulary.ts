export type VocabularySort = 'recent' | 'due' | 'alphabetical';

export type SrsCardState = 'new' | 'learning' | 'review' | 'relearning';

export interface VocabularyEntry {
    id: number;
    kind: 'vocabulary' | 'grammar';
    term: string;
    translation: string;
    state: SrsCardState;
    dueAt: string;
    isWeakSpot: boolean;
}

export interface VocabularyPagination {
    currentPage: number;
    lastPage: number;
    total: number;
}

export interface VocabularyFilters {
    q: string;
    sort: VocabularySort;
}
