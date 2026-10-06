import { flushPromises, mount } from '@vue/test-utils';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { nextTick } from 'vue';
import { setLocale } from '@/i18n';
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
        exercise: 'flip',
        options: null,
        direction: 'recognition',
        needsArticle: false,
        suggestedErrorTag: null,
        audioUrl: null,
        audioSlowUrl: null,
    };
}

function grammarCard(id: number): ReviewCard {
    return {
        id,
        front: `grammar ${id}`,
        back: `explanation ${id}`,
        kind: 'grammar',
        exercise: 'flip',
        options: null,
        direction: 'recognition',
        needsArticle: false,
        suggestedErrorTag: 'ser_estar_confusion',
        audioUrl: null,
        audioSlowUrl: null,
    };
}

function productionCard(id: number): ReviewCard {
    return {
        id,
        front: 'airport',
        back: 'el aeropuerto',
        kind: 'vocabulary',
        exercise: 'type',
        options: null,
        direction: 'production',
        needsArticle: true,
        suggestedErrorTag: null,
        audioUrl: null,
        audioSlowUrl: null,
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

function checksAs(correct: boolean) {
    fetchJson.mockResolvedValue({
        ok: true,
        json: () => Promise.resolve({ correct }),
    } as Response);
}

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

    it('ignores a key that is held down and repeating', async () => {
        const wrapper = mountDeck([vocabularyCard(1), vocabularyCard(2)]);

        window.dispatchEvent(
            new KeyboardEvent('keydown', { key: ' ', repeat: true }),
        );
        await nextTick();

        expect(wrapper.text()).not.toContain('back 1');

        await press(' ');
        window.dispatchEvent(
            new KeyboardEvent('keydown', { key: '3', repeat: true }),
        );
        await nextTick();

        expect(submitOrQueue).not.toHaveBeenCalled();
    });

    it.each([['ctrlKey'], ['altKey']])(
        'ignores shortcuts with %s held',
        async (modifier) => {
            const wrapper = mountDeck([vocabularyCard(1)]);

            window.dispatchEvent(
                new KeyboardEvent('keydown', { key: ' ', [modifier]: true }),
            );
            await nextTick();

            expect(wrapper.text()).not.toContain('back 1');
        },
    );

    it('leaves shortcuts alone while typing in an editable element', async () => {
        const editable = document.createElement('div');
        editable.contentEditable = 'true';
        Object.defineProperty(editable, 'isContentEditable', { value: true });
        document.body.appendChild(editable);

        const wrapper = mountDeck([vocabularyCard(1)]);

        editable.dispatchEvent(
            new KeyboardEvent('keydown', { key: ' ', bubbles: true }),
        );
        await nextTick();

        expect(wrapper.text()).not.toContain('back 1');

        editable.remove();
    });

    it('lets a focused rating button take enter instead of the suggested rating', async () => {
        const wrapper = mountDeck([productionCard(5)]);

        await wrapper
            .findAll('button')
            .find((button) => button.text().startsWith('Show answer'))
            ?.trigger('click');

        const hard = wrapper
            .findAll('button')
            .find((button) => button.text().startsWith('Hard'));
        hard?.element.dispatchEvent(
            new KeyboardEvent('keydown', { key: 'Enter', bubbles: true }),
        );
        await nextTick();

        expect(submitOrQueue).not.toHaveBeenCalled();

        await hard?.trigger('click');
        await nextTick();

        expect(submitOrQueue).toHaveBeenCalledTimes(1);
        expect(submitOrQueue).toHaveBeenCalledWith('/review/5/reviews', {
            rating: 'hard',
            error_tag_category: null,
        });
    });

    it.each([
        [
            'a link',
            () => Object.assign(document.createElement('a'), { href: '/x' }),
        ],
        [
            'a role=button element',
            () => {
                const element = document.createElement('div');
                element.setAttribute('role', 'button');
                element.tabIndex = 0;

                return element;
            },
        ],
    ])(
        'does not take the suggested rating when enter is pressed on %s',
        async (_label, make) => {
            checksAs(true);
            const wrapper = mountDeck([productionCard(5)]);

            await wrapper.find('input').setValue('el aeropuerto');
            await wrapper.find('form').trigger('submit');
            await flushPromises();

            const element = make();
            document.body.appendChild(element);
            element.dispatchEvent(
                new KeyboardEvent('keydown', { key: 'Enter', bubbles: true }),
            );
            await nextTick();
            element.remove();

            expect(submitOrQueue).not.toHaveBeenCalled();
        },
    );

    it('does not take the suggested rating again on a repeating enter', async () => {
        checksAs(true);
        const wrapper = mountDeck([productionCard(5), productionCard(6)]);

        await wrapper.find('input').setValue('el aeropuerto');
        await wrapper.find('form').trigger('submit');
        await flushPromises();

        window.dispatchEvent(
            new KeyboardEvent('keydown', { key: 'Enter', repeat: true }),
        );
        await nextTick();

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

describe('card presentation', () => {
    function mountOne(card: ReviewCard, speechLocale: string | null = 'es-ES') {
        const wrapper = mount(ReviewDeck, {
            props: {
                cards: [card],
                reviewUrl: (cardId: number) => `/review/${cardId}/reviews`,
                answerUrl: (cardId: number) => `/review/${cardId}/answers`,
                countNoun: 'card',
                emptyMessage: 'All caught up.',
                speechLocale,
            },
            attachTo: document.body,
            global: { stubs: { SpeakButton: true } },
        });
        mounted.push(wrapper);

        return wrapper;
    }

    it('hands the card clips to the listen control', () => {
        const wrapper = mountOne({
            ...vocabularyCard(1),
            audioUrl: '/n.mp3',
            audioSlowUrl: '/s.mp3',
        });

        expect(wrapper.findComponent(SpeakButton).props()).toMatchObject({
            audioUrl: '/n.mp3',
            audioSlowUrl: '/s.mp3',
        });
    });

    it('speaks the front of a recognition vocabulary card', () => {
        const wrapper = mountOne(vocabularyCard(1));

        expect(wrapper.findComponent(SpeakButton).props('text')).toBe(
            'front 1',
        );
        expect(wrapper.findComponent(SpeakButton).props('locale')).toBe(
            'es-ES',
        );
    });

    it('offers no speech on a grammar card', () => {
        expect(
            mountOne(grammarCard(1)).findComponent(SpeakButton).exists(),
        ).toBe(false);
    });

    it('shows the space hint only on a recognition card', () => {
        expect(mountOne(vocabularyCard(1)).find('kbd').text()).toBe('space');
        expect(mountOne(productionCard(2)).find('kbd').exists()).toBe(false);
    });

    it('highlights the suggested error tag when a grammar card is missed', async () => {
        const wrapper = mountOne(grammarCard(9));

        await wrapper.find('button').trigger('click');
        await wrapper.findAll('button')[0].trigger('click');
        await nextTick();

        const variants = Object.fromEntries(
            wrapper
                .findAll('button')
                .map((button) => [
                    button.text(),
                    button.attributes('data-variant'),
                ]),
        );

        expect(variants['Ser vs estar']).toBe('default');
        expect(variants['Not sure']).toBe('ghost');
        expect(
            Object.values(variants).filter((variant) => variant === 'default'),
        ).toHaveLength(1);
    });

    it('shows the number keys as hints on every rating button of a recognition card', async () => {
        const wrapper = mountOne(vocabularyCard(1));

        await wrapper.find('button').trigger('click');

        expect(wrapper.findAll('kbd').map((hint) => hint.text())).toEqual([
            '1',
            '2',
            '3',
            '4',
        ]);
    });

    it('breaks the session summary down per rating', async () => {
        const wrapper = mountOne(vocabularyCard(1));

        await wrapper.find('button').trigger('click');
        await wrapper.findAll('button')[3].trigger('click');
        await nextTick();

        const cells = wrapper
            .findAll('.rounded-md.border')
            .map((cell) => [
                cell.find('.text-xs').text(),
                cell.find('.text-xl').text(),
            ]);

        expect(cells).toEqual([
            ['Again', '0'],
            ['Hard', '0'],
            ['Good', '0'],
            ['Easy', '1'],
        ]);
    });
});

describe('typed recall', () => {
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
        expect(ratingButton(wrapper, 'Continue').exists()).toBe(true);
        expect(wrapper.text()).not.toContain('Hard');

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
        expect(ratingButton(wrapper, 'Continue').exists()).toBe(true);

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

    describe('a check that never answers', () => {
        beforeEach(() => {
            vi.useFakeTimers();
        });

        afterEach(() => {
            vi.useRealTimers();
        });

        it('gives up after the timeout and leaves the rating to the learner', async () => {
            fetchJson.mockReturnValue(new Promise(() => {}));
            const wrapper = mountDeck([productionCard(5)]);

            await wrapper.find('input').setValue('el aeropuerto');
            await wrapper.find('form').trigger('submit');

            await vi.advanceTimersByTimeAsync(7999);

            expect(wrapper.text()).not.toContain('el aeropuerto');

            await vi.advanceTimersByTimeAsync(1);
            await flushPromises();

            expect(wrapper.text()).toContain("Couldn't check your answer");
            expect(wrapper.text()).toContain('el aeropuerto');

            await press('Enter');

            expect(submitOrQueue).not.toHaveBeenCalled();
        });

        it('does not let a timed-out check overwrite a revealed answer later', async () => {
            fetchJson.mockReturnValue(new Promise(() => {}));
            const wrapper = mountDeck([productionCard(5)]);

            await wrapper.find('input').setValue('el aeropuerto');
            await wrapper.find('form').trigger('submit');
            await ratingButton(wrapper, 'Show answer').trigger('click');
            await vi.advanceTimersByTimeAsync(10000);

            expect(wrapper.text()).not.toContain("Couldn't check your answer");
            expect(wrapper.text()).toContain('el aeropuerto');
        });
    });

    it('lets the learner reveal the word while the check is still running', async () => {
        fetchJson.mockReturnValue(new Promise(() => {}));
        const wrapper = mountDeck([productionCard(5)]);

        await wrapper.find('input').setValue('el aeropuerto');
        await wrapper.find('form').trigger('submit');

        const show = ratingButton(wrapper, 'Show answer');

        expect(show.attributes('disabled')).toBeUndefined();

        await show.trigger('click');

        expect(wrapper.text()).toContain('el aeropuerto');
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

    it('drops a late answer that belongs to a card already moved past', async () => {
        let resolveCheck: (response: Response) => void = () => {};
        fetchJson.mockReturnValue(
            new Promise<Response>((resolve) => {
                resolveCheck = resolve;
            }),
        );
        const wrapper = mountDeck([productionCard(5), productionCard(6)]);

        await wrapper.find('input').setValue('el aeropuerto');
        await wrapper.find('form').trigger('submit');
        await ratingButton(wrapper, 'Show answer').trigger('click');
        await press('Enter');
        await flushPromises();

        resolveCheck({
            ok: true,
            json: () => Promise.resolve({ correct: true }),
        } as Response);
        await flushPromises();

        expect(wrapper.find('input').exists()).toBe(true);
        expect(wrapper.text()).not.toContain('Correct');
    });

    it('disables Check for a blank answer and caps the length', async () => {
        const wrapper = mountDeck([productionCard(5)]);

        expect(ratingButton(wrapper, 'Check').attributes('disabled')).toBe('');
        expect(wrapper.find('input').attributes('maxlength')).toBe('200');

        await wrapper.find('input').setValue('el');

        expect(
            ratingButton(wrapper, 'Check').attributes('disabled'),
        ).toBeUndefined();
    });

    it('shows the typed answer for an unchecked card', async () => {
        fetchJson.mockRejectedValue(new TypeError('offline'));
        const wrapper = mountDeck([productionCard(5)]);

        await answer(wrapper, ' aeropuerto ');

        expect(wrapper.text()).toContain('You wrote “aeropuerto”');
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

describe('choice exercises', () => {
    function choiceCard(
        id: number,
        exercise: 'choose_meaning' | 'choose_word',
    ): ReviewCard {
        const word = exercise === 'choose_word';

        return {
            id,
            front: word ? 'suitcase' : 'la maleta',
            back: word ? 'la maleta' : 'suitcase',
            kind: 'vocabulary',
            exercise,
            options: word
                ? ['la mesa', 'la maleta', 'la playa', 'la puerta']
                : ['suitcase', 'table', 'beach', 'door'],
            direction: word ? 'production' : 'recognition',
            needsArticle: false,
            suggestedErrorTag: null,
            audioUrl: null,
            audioSlowUrl: null,
        };
    }

    function option(wrapper: ReturnType<typeof mountDeck>, text: string) {
        const found = wrapper
            .findAll('[role="radio"]')
            .find((candidate) => candidate.text().includes(text));

        if (!found) {
            throw new Error(`No option ${text}`);
        }

        return found;
    }

    it('shows the options instead of a reveal button', () => {
        const wrapper = mountDeck([choiceCard(7, 'choose_meaning')]);

        expect(wrapper.findAll('[role="radio"]')).toHaveLength(4);
        expect(wrapper.text()).toContain('What does it mean?');
        expect(wrapper.text()).not.toContain('Show answer');
    });

    it('rates a right pick Good through Continue', async () => {
        const wrapper = mountDeck([choiceCard(7, 'choose_meaning')]);

        await option(wrapper, 'suitcase').trigger('click');

        expect(wrapper.text()).toContain('Correct');
        expect(wrapper.text()).not.toContain('Hard');

        await press('Enter');
        await nextTick();

        expect(submitOrQueue).toHaveBeenCalledWith('/review/7/reviews', {
            rating: 'good',
            error_tag_category: null,
        });
    });

    it('rates a wrong pick Again and says what was chosen', async () => {
        const wrapper = mountDeck([choiceCard(7, 'choose_word')]);

        await option(wrapper, 'la mesa').trigger('click');

        expect(wrapper.text()).toContain('You chose “la mesa”');
        expect(wrapper.text()).toContain('la maleta');

        await wrapper
            .findAll('button')
            .find((button) => button.text().startsWith('Continue'))
            ?.trigger('click');
        await nextTick();

        expect(submitOrQueue).toHaveBeenCalledWith('/review/7/reviews', {
            rating: 'again',
            error_tag_category: null,
        });
    });

    it('counts giving up as a miss', async () => {
        const wrapper = mountDeck([choiceCard(7, 'choose_meaning')]);

        await wrapper
            .findAll('button')
            .find((button) => button.text().startsWith("I don't know"))
            ?.trigger('click');
        await press('Enter');
        await nextTick();

        expect(submitOrQueue).toHaveBeenCalledWith('/review/7/reviews', {
            rating: 'again',
            error_tag_category: null,
        });
    });

    it('ignores the rating digits once an option is picked', async () => {
        const wrapper = mountDeck([choiceCard(7, 'choose_meaning')]);

        await option(wrapper, 'suitcase').trigger('click');
        await press('4');
        await nextTick();

        expect(submitOrQueue).not.toHaveBeenCalled();
    });
});

describe('review deck in Dutch', () => {
    it('counts what is left in the interface language and marks the learned-language side', () => {
        setLocale('nl');

        const wrapper = mount(ReviewDeck, {
            props: {
                cards: [vocabularyCard(1), vocabularyCard(2)],
                reviewUrl: (cardId: number) => `/review/${cardId}/reviews`,
                answerUrl: (cardId: number) => `/review/${cardId}/answers`,
                countNoun: 'weakSpot',
                emptyMessage: 'Leeg.',
                speechLocale: 'es-ES',
            },
            attachTo: document.body,
        });

        mounted.push(wrapper);

        expect(wrapper.text()).toContain('2 zwakke punten over');
        expect(wrapper.text()).toContain('Toon antwoord');
        expect(wrapper.find('[lang="es-ES"]').text()).toBe('front 1');
    });
});
