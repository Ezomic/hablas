import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import ReviewDeck from '@/components/ReviewDeck.vue';
import type { ReviewCard } from '@/types/review';
import Index from './Index.vue';

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
    setLayoutProps: vi.fn(),
}));

vi.mock('@/routes/review/reviews', () => ({
    store: (cardId: number) => ({ url: `/review/${cardId}/reviews` }),
}));

vi.mock('@/routes/review/answers', () => ({
    check: (cardId: number) => ({ url: `/review/${cardId}/answers` }),
}));

const card: ReviewCard = {
    id: 3,
    front: 'hola',
    back: 'hello',
    kind: 'vocabulary',
    direction: 'recognition',
    needsArticle: false,
    suggestedErrorTag: null,
    audioUrl: null,
    audioSlowUrl: null,
};

function mountPage(cards: ReviewCard[], dueRemaining = 0) {
    return mount(Index, {
        props: { cards, dueRemaining, speechLocale: 'es-ES' },
        global: { stubs: { ReviewDeck: true } },
    });
}

describe('review page', () => {
    it('hands the deck the normal review endpoint', () => {
        const wrapper = mountPage([card]);
        const deck = wrapper.findComponent(ReviewDeck);

        expect(deck.props('reviewUrl')(3)).toBe('/review/3/reviews');
    });

    it('hands the deck the answer check endpoint', () => {
        const deck = mountPage([card]).findComponent(ReviewDeck);

        expect(deck.props('answerUrl')(3)).toBe('/review/3/answers');
    });

    it('passes the cards and remaining count through', () => {
        const wrapper = mountPage([card], 12);
        const deck = wrapper.findComponent(ReviewDeck);

        expect(deck.props('cards')).toEqual([card]);
        expect(deck.props('dueRemaining')).toBe(12);
    });

    it('labels the deck for cards', () => {
        const deck = mountPage([card]).findComponent(ReviewDeck);

        expect(deck.props('countNoun')).toBe('card');
        expect(deck.props('emptyMessage')).toContain('All caught up');
    });
});
