import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';
import ReviewDeck from '@/components/ReviewDeck.vue';
import type { ReviewCard } from '@/types/review';
import WeakSpots from './WeakSpots.vue';

vi.mock('@inertiajs/vue3', () => ({
    Head: { render: () => null },
}));

vi.mock('@/routes/review/weak-spots/reviews', () => ({
    store: (cardId: number) => ({
        url: `/review/weak-spots/${cardId}/reviews`,
    }),
}));

const card: ReviewCard = {
    id: 5,
    front: 'Ser vs estar',
    back: 'Ser is for permanent traits.',
    kind: 'grammar',
    suggestedErrorTag: 'ser_estar_confusion',
};

function mountPage(cards: ReviewCard[]) {
    return mount(WeakSpots, {
        props: { cards, speechLocale: 'es-ES' },
        global: { stubs: { ReviewDeck: true } },
    });
}

describe('weak spots page', () => {
    it('hands the deck the weak-spot endpoint, not the normal one', () => {
        const deck = mountPage([card]).findComponent(ReviewDeck);

        expect(deck.props('reviewUrl')(5)).toBe('/review/weak-spots/5/reviews');
    });

    it('labels the deck for weak spots', () => {
        const deck = mountPage([card]).findComponent(ReviewDeck);

        expect(deck.props('countNoun')).toBe('weak spot');
        expect(deck.props('emptyMessage')).toContain('No weak spots');
    });

    it('explains what a weak spot is', () => {
        expect(mountPage([card]).text()).toContain(
            'missed a few times in a row',
        );
    });

    it('does not claim a session cap it was not given', () => {
        const deck = mountPage([card]).findComponent(ReviewDeck);

        expect(deck.props('dueRemaining')).toBe(0);
    });
});
