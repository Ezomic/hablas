import { flushPromises, mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { nextTick } from 'vue';
import type { ReviewCard } from '@/types/review';
import ReviewDeck from './ReviewDeck.vue';
import SpeakButton from './SpeakButton.vue';

const { submitOrQueue, fetchJson } = vi.hoisted(() => ({
    submitOrQueue: vi.fn(),
    fetchJson: vi.fn(),
}));

vi.mock('@/composables/useOfflineSync', () => ({
    useOfflineSync: () => ({ submitOrQueue }),
}));

vi.mock('@/lib/http', () => ({ fetchJson }));

function vocabularyCard(id: number): ReviewCard {
    return {
        id,
        front: `front ${id}`,
        back: `back ${id}`,
        kind: 'vocabulary',
        direction: 'recognition',
        needsArticle: false,
        suggestedErrorTag: null,
    };
}

function grammarCard(id: number): ReviewCard {
    return {
        id,
        front: `grammar ${id}`,
        back: `explanation ${id}`,
        kind: 'grammar',
        direction: 'recognition',
        needsArticle: false,
        suggestedErrorTag: 'ser_estar_confusion',
    };
}

function productionCard(id: number): ReviewCard {
    return {
        id,
        front: 'airport',
        back: 'el aeropuerto',
        kind: 'vocabulary',
        direction: 'production',
        needsArticle: true,
        suggestedErrorTag: null,
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
            answerUrl: (cardId: number) => `/review/${cardId}/answers`,
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
    fetchJson.mockReset();
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
                answerUrl: (cardId: number) => `/review/${cardId}/answers`,
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

describe('typed recall', () => {
    function checksAs(correct: boolean) {
        fetchJson.mockResolvedValue({
            ok: true,
            json: () => Promise.resolve({ correct }),
        } as Response);
    }

    async function answer(
        wrapper: ReturnType<typeof mountDeck>,
        typed: string,
    ) {
        await wrapper.find('input').setValue(typed);
        await wrapper.find('form').trigger('submit');
        await flushPromises();
    }

    function ratingButton(
        wrapper: ReturnType<typeof mountDeck>,
        label: string,
    ) {
        const button = wrapper
            .findAll('button')
            .find((candidate) => candidate.text().startsWith(label));

        if (!button) {
            throw new Error(`No ${label} button`);
        }

        return button;
    }

    it('shows the translation and a focused input instead of the word', () => {
        const wrapper = mountDeck([productionCard(5)]);

        expect(wrapper.text()).toContain('airport');
        expect(wrapper.text()).toContain('Type the word, with its article');
        expect(wrapper.text()).not.toContain('el aeropuerto');
        expect(document.activeElement).toBe(wrapper.find('input').element);
    });

    it('only mentions the article for a noun', () => {
        const wrapper = mountDeck([
            { ...productionCard(5), needsArticle: false },
        ]);

        expect(wrapper.text()).toContain('Type the word');
        expect(wrapper.text()).not.toContain('article');
    });

    it('checks the typed answer with the server', async () => {
        checksAs(true);
        const wrapper = mountDeck([productionCard(5)]);

        await answer(wrapper, ' el aeropuerto ');

        expect(fetchJson).toHaveBeenCalledWith(
            '/review/5/answers',
            'POST',
            JSON.stringify({ answer: 'el aeropuerto' }),
        );
    });

    it('does not check a blank answer', async () => {
        const wrapper = mountDeck([productionCard(5)]);

        await answer(wrapper, '   ');

        expect(fetchJson).not.toHaveBeenCalled();
        expect(wrapper.text()).not.toContain('el aeropuerto');
    });

    it('leaves enter and the rating keys to the input while typing', async () => {
        const wrapper = mountDeck([productionCard(5)]);
        const input = wrapper.find('input').element;

        for (const key of ['Enter', ' ', '1', '3']) {
            input.dispatchEvent(
                new KeyboardEvent('keydown', { key, bubbles: true }),
            );
        }

        await nextTick();

        expect(wrapper.text()).not.toContain('el aeropuerto');
        expect(submitOrQueue).not.toHaveBeenCalled();
    });

    it('does not reveal a production card through the space or enter shortcut', async () => {
        const wrapper = mountDeck([productionCard(5)]);

        await press(' ');
        await press('Enter');

        expect(wrapper.text()).not.toContain('el aeropuerto');
    });

    it('reveals the word and pre-selects Good for a correct answer', async () => {
        checksAs(true);
        const wrapper = mountDeck([productionCard(5)]);

        await answer(wrapper, 'el aeropuerto');

        expect(wrapper.text()).toContain('Correct');
        expect(wrapper.text()).toContain('el aeropuerto');
        expect(wrapper.find('input').exists()).toBe(false);
        expect(ratingButton(wrapper, 'Good').attributes('data-variant')).toBe(
            'default',
        );
        expect(ratingButton(wrapper, 'Again').attributes('data-variant')).toBe(
            'outline',
        );

        await press('Enter');
        await nextTick();

        expect(submitOrQueue).toHaveBeenCalledWith('/review/5/reviews', {
            rating: 'good',
            error_tag_category: null,
        });
    });

    it('shows what was typed next to the word and pre-selects Again for a wrong answer', async () => {
        checksAs(false);
        const wrapper = mountDeck([productionCard(5)]);

        await answer(wrapper, 'aeropuerto');

        expect(wrapper.text()).toContain('el aeropuerto');
        expect(wrapper.text()).toContain('You wrote “aeropuerto”');
        expect(wrapper.text()).not.toContain('Correct');
        expect(ratingButton(wrapper, 'Again').attributes('data-variant')).toBe(
            'default',
        );

        await press('Enter');
        await nextTick();

        expect(submitOrQueue).toHaveBeenCalledWith('/review/5/reviews', {
            rating: 'again',
            error_tag_category: null,
        });
    });

    it.each([
        [
            'offline',
            () => fetchJson.mockRejectedValue(new TypeError('offline')),
        ],
        [
            'refused',
            () => fetchJson.mockResolvedValue({ ok: false } as Response),
        ],
    ])(
        'reveals the word with nothing pre-selected when the check is %s',
        async (_, failCheck) => {
            failCheck();
            const wrapper = mountDeck([productionCard(5)]);

            await answer(wrapper, 'el aeropuerto');

            expect(wrapper.text()).toContain("Couldn't check your answer");
            expect(wrapper.text()).toContain('el aeropuerto');
            expect(
                wrapper
                    .findAll('[data-variant="default"]')
                    .filter((button) => button.text().match(/Again|Good/)),
            ).toHaveLength(0);

            await press('Enter');
            await nextTick();

            expect(submitOrQueue).not.toHaveBeenCalled();

            await press('3');
            await nextTick();

            expect(submitOrQueue).toHaveBeenCalledWith('/review/5/reviews', {
                rating: 'good',
                error_tag_category: null,
            });
        },
    );

    it('ignores enter and a second submit while the check is in flight', async () => {
        fetchJson.mockReturnValue(new Promise(() => {}));
        const wrapper = mountDeck([productionCard(5)]);

        await wrapper.find('input').setValue('el aeropuerto');
        await wrapper.find('form').trigger('submit');
        await wrapper.find('form').trigger('submit');
        await press('Enter');
        await press('1');

        expect(fetchJson).toHaveBeenCalledTimes(1);
        expect(wrapper.text()).not.toContain('el aeropuerto');
        expect(submitOrQueue).not.toHaveBeenCalled();
    });

    it('lets the learner give up and pre-selects Again', async () => {
        const wrapper = mountDeck([productionCard(5)]);

        await ratingButton(wrapper, 'Show answer').trigger('click');

        expect(fetchJson).not.toHaveBeenCalled();
        expect(wrapper.text()).toContain('el aeropuerto');
        expect(ratingButton(wrapper, 'Again').attributes('data-variant')).toBe(
            'default',
        );
    });

    it('keeps the speak button hidden until the word is revealed', async () => {
        checksAs(true);
        const wrapper = mount(ReviewDeck, {
            props: {
                cards: [productionCard(5)],
                reviewUrl: (cardId: number) => `/review/${cardId}/reviews`,
                answerUrl: (cardId: number) => `/review/${cardId}/answers`,
                countNoun: 'card',
                emptyMessage: 'All caught up.',
                speechLocale: 'es-ES',
            },
            attachTo: document.body,
            global: { stubs: { SpeakButton: true } },
        });
        mounted.push(wrapper);

        expect(wrapper.findComponent(SpeakButton).exists()).toBe(false);

        await answer(wrapper, 'el aeropuerto');

        expect(wrapper.findComponent(SpeakButton).props('text')).toBe(
            'el aeropuerto',
        );
    });

    it('starts the next production card with an empty, focused input', async () => {
        checksAs(true);
        const wrapper = mountDeck([productionCard(5), productionCard(6)]);

        await answer(wrapper, 'el aeropuerto');
        await press('Enter');
        await flushPromises();

        const input = wrapper.find('input');

        expect(fetchJson).toHaveBeenCalledTimes(1);
        expect(input.element.value).toBe('');
        expect(document.activeElement).toBe(input.element);
        expect(wrapper.text()).not.toContain('Correct');
    });
});
