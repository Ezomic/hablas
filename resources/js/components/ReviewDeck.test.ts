import { mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { nextTick } from 'vue';
import type { ReviewCard } from '@/types/review';
import ReviewDeck from './ReviewDeck.vue';

const { submitOrQueue } = vi.hoisted(() => ({
    submitOrQueue: vi.fn(),
}));

vi.mock('@/composables/useOfflineSync', () => ({
    useOfflineSync: () => ({ submitOrQueue }),
}));

function vocabularyCard(id: number): ReviewCard {
    return {
        id,
        front: `front ${id}`,
        back: `back ${id}`,
        kind: 'vocabulary',
        suggestedErrorTag: null,
    };
}

function grammarCard(id: number): ReviewCard {
    return {
        id,
        front: `grammar ${id}`,
        back: `explanation ${id}`,
        kind: 'grammar',
        suggestedErrorTag: 'ser_estar_confusion',
    };
}

// Each deck listens on window, so a wrapper left mounted would keep reacting
// to the next test's key presses.
const mounted: { unmount: () => void }[] = [];

function mountDeck(cards: ReviewCard[]) {
    const wrapper = mount(ReviewDeck, {
        props: {
            cards,
            reviewUrl: (cardId: number) => `/review/${cardId}/reviews`,
            countNoun: 'card',
            emptyMessage: 'All caught up.',
        },
        attachTo: document.body,
    });

    mounted.push(wrapper);

    return wrapper;
}

function press(key: string) {
    window.dispatchEvent(new KeyboardEvent('keydown', { key, bubbles: true }));

    return nextTick();
}

afterEach(() => {
    mounted.splice(0).forEach((wrapper) => wrapper.unmount());
});

beforeEach(() => {
    submitOrQueue.mockReset();
    submitOrQueue.mockResolvedValue({
        queued: false,
        response: { ok: true } as Response,
    });
});

describe('keyboard shortcuts', () => {
    it('reveals the answer on space', async () => {
        const wrapper = mountDeck([vocabularyCard(1)]);

        expect(wrapper.text()).not.toContain('back 1');

        await press(' ');

        expect(wrapper.text()).toContain('back 1');
    });

    it('reveals the answer on enter', async () => {
        const wrapper = mountDeck([vocabularyCard(1)]);

        await press('Enter');

        expect(wrapper.text()).toContain('back 1');
    });

    it.each([
        ['1', 'again'],
        ['2', 'hard'],
        ['3', 'good'],
        ['4', 'easy'],
    ])('rates with %s once revealed', async (key, rating) => {
        mountDeck([vocabularyCard(7)]);

        await press(' ');
        await press(key);
        await nextTick();

        expect(submitOrQueue).toHaveBeenCalledWith('/review/7/reviews', {
            rating,
            error_tag_category: null,
        });
    });

    it('ignores rating keys before the answer is revealed', async () => {
        mountDeck([vocabularyCard(1)]);

        await press('3');

        expect(submitOrQueue).not.toHaveBeenCalled();
    });

    it('ignores keys outside the one-to-four range', async () => {
        mountDeck([vocabularyCard(1)]);

        await press(' ');
        await press('5');
        await press('0');

        expect(submitOrQueue).not.toHaveBeenCalled();
    });

    it('advances to the next card after a keyboard rating', async () => {
        const wrapper = mountDeck([vocabularyCard(1), vocabularyCard(2)]);

        await press(' ');
        await press('3');
        await nextTick();

        expect(wrapper.text()).toContain('front 2');
        expect(wrapper.text()).not.toContain('back 2');
    });

    it('leaves shortcuts alone while typing in a field', async () => {
        const input = document.createElement('input');
        document.body.appendChild(input);

        const wrapper = mountDeck([vocabularyCard(1)]);

        input.dispatchEvent(
            new KeyboardEvent('keydown', { key: ' ', bubbles: true }),
        );
        await nextTick();

        expect(wrapper.text()).not.toContain('back 1');

        input.remove();
    });

    it('ignores shortcuts with a modifier held', async () => {
        const wrapper = mountDeck([vocabularyCard(1)]);

        window.dispatchEvent(
            new KeyboardEvent('keydown', { key: ' ', metaKey: true }),
        );
        await nextTick();

        expect(wrapper.text()).not.toContain('back 1');
    });

    it('does not rate while the mistake picker is open', async () => {
        mountDeck([grammarCard(4)]);

        await press(' ');
        await press('1');
        await nextTick();

        expect(submitOrQueue).not.toHaveBeenCalled();

        await press('2');

        expect(submitOrQueue).not.toHaveBeenCalled();
    });

    it('stops listening once unmounted', async () => {
        const wrapper = mountDeck([vocabularyCard(1)]);
        mounted.pop();
        wrapper.unmount();

        await press(' ');
        await press('3');

        expect(submitOrQueue).not.toHaveBeenCalled();
    });
});
