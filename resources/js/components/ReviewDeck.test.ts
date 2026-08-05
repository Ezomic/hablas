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

describe('review flow', () => {
    async function reveal(wrapper: ReturnType<typeof mountDeck>) {
        await wrapper.find('button').trigger('click');
    }

    function ratingButtons(wrapper: ReturnType<typeof mountDeck>) {
        return wrapper.findAll('button');
    }

    it('hides the answer until it is revealed', async () => {
        const wrapper = mountDeck([vocabularyCard(1)]);

        expect(wrapper.text()).toContain('front 1');
        expect(wrapper.text()).not.toContain('back 1');

        await reveal(wrapper);

        expect(wrapper.text()).toContain('back 1');
    });

    it('submits the chosen rating and advances', async () => {
        const wrapper = mountDeck([vocabularyCard(1), vocabularyCard(2)]);

        await reveal(wrapper);
        await ratingButtons(wrapper)[2].trigger('click');
        await nextTick();

        expect(submitOrQueue).toHaveBeenCalledWith('/review/1/reviews', {
            rating: 'good',
            error_tag_category: null,
        });
        expect(wrapper.text()).toContain('front 2');
    });

    it('counts down the cards left', async () => {
        const wrapper = mountDeck([vocabularyCard(1), vocabularyCard(2)]);

        expect(wrapper.text()).toContain('2 cards left');

        await reveal(wrapper);
        await ratingButtons(wrapper)[2].trigger('click');
        await nextTick();

        expect(wrapper.text()).toContain('1 card left');
    });

    it('surfaces a failed submit and keeps the card in place', async () => {
        submitOrQueue.mockResolvedValue({
            queued: false,
            response: { ok: false } as Response,
        });

        const wrapper = mountDeck([vocabularyCard(1)]);

        await reveal(wrapper);
        await ratingButtons(wrapper)[2].trigger('click');
        await nextTick();

        expect(wrapper.text()).toContain("Couldn't save that rating");
        expect(wrapper.text()).toContain('front 1');
        expect(wrapper.text()).toContain('1 card left');
    });

    it('lets a failed rating be retried', async () => {
        submitOrQueue.mockResolvedValueOnce({
            queued: false,
            response: { ok: false } as Response,
        });

        const wrapper = mountDeck([vocabularyCard(1)]);

        await reveal(wrapper);
        await ratingButtons(wrapper)[2].trigger('click');
        await nextTick();
        await ratingButtons(wrapper)[2].trigger('click');
        await nextTick();

        expect(submitOrQueue).toHaveBeenCalledTimes(2);
        expect(wrapper.text()).not.toContain("Couldn't save that rating");
    });

    it('queues offline and still advances', async () => {
        submitOrQueue.mockResolvedValue({ queued: true });

        const wrapper = mountDeck([vocabularyCard(1), vocabularyCard(2)]);

        await reveal(wrapper);
        await ratingButtons(wrapper)[2].trigger('click');
        await nextTick();

        expect(wrapper.text()).toContain("You're offline");
        expect(wrapper.text()).toContain('front 2');
    });

    it('renders the empty state when there was nothing to review', () => {
        const wrapper = mountDeck([]);

        expect(wrapper.text()).toContain('All caught up.');
        expect(wrapper.text()).not.toContain('Session complete');
    });

    it('shows a session summary with the rating breakdown once done', async () => {
        const wrapper = mountDeck([vocabularyCard(1), vocabularyCard(2)]);

        await reveal(wrapper);
        await ratingButtons(wrapper)[2].trigger('click');
        await nextTick();
        await reveal(wrapper);
        await ratingButtons(wrapper)[3].trigger('click');
        await nextTick();

        expect(wrapper.text()).toContain('Session complete');
        expect(wrapper.text()).toContain('2 cards reviewed');
        expect(wrapper.text()).toContain('Nothing else due right now');
        expect(wrapper.text()).not.toContain('All caught up.');
    });

    it('reports what is still due after a capped session', async () => {
        const wrapper = mount(ReviewDeck, {
            props: {
                cards: [vocabularyCard(1)],
                reviewUrl: (cardId: number) => `/review/${cardId}/reviews`,
                countNoun: 'card',
                emptyMessage: 'All caught up.',
                dueRemaining: 12,
            },
            attachTo: document.body,
        });
        mounted.push(wrapper);

        await wrapper.find('button').trigger('click');
        await wrapper.findAll('button')[2].trigger('click');
        await nextTick();

        expect(wrapper.text()).toContain('12 more cards still due');
    });

    it('asks what went wrong when a grammar card is missed', async () => {
        const wrapper = mountDeck([grammarCard(9)]);

        await reveal(wrapper);
        await ratingButtons(wrapper)[0].trigger('click');
        await nextTick();

        expect(submitOrQueue).not.toHaveBeenCalled();
        expect(wrapper.text()).toContain('What went wrong?');

        const tagButton = wrapper
            .findAll('button')
            .find((button) => button.text() === 'Ser vs estar');
        await tagButton?.trigger('click');
        await nextTick();

        expect(submitOrQueue).toHaveBeenCalledWith('/review/9/reviews', {
            rating: 'again',
            error_tag_category: 'ser_estar_confusion',
        });
    });

    it('lets a missed grammar card go untagged', async () => {
        const wrapper = mountDeck([grammarCard(9)]);

        await reveal(wrapper);
        await ratingButtons(wrapper)[0].trigger('click');
        await nextTick();

        const skip = wrapper
            .findAll('button')
            .find((button) => button.text() === 'Not sure');
        await skip?.trigger('click');
        await nextTick();

        expect(submitOrQueue).toHaveBeenCalledWith('/review/9/reviews', {
            rating: 'again',
            error_tag_category: null,
        });
    });

    it('does not ask what went wrong for a missed vocabulary card', async () => {
        const wrapper = mountDeck([vocabularyCard(1)]);

        await reveal(wrapper);
        await ratingButtons(wrapper)[0].trigger('click');
        await nextTick();

        expect(wrapper.text()).not.toContain('What went wrong?');
        expect(submitOrQueue).toHaveBeenCalledWith('/review/1/reviews', {
            rating: 'again',
            error_tag_category: null,
        });
    });
});
