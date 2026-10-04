import { afterEach, describe, expect, it } from 'vitest';
import { i18n, setLocale } from '@/i18n';

describe('i18n', () => {
    it('starts in English', () => {
        expect(i18n.global.t('common.save')).toBe('Save');
    });

    it('switches the messages and the document language together', () => {
        setLocale('nl');

        expect(i18n.global.t('common.save')).toBe('Opslaan');
        expect(document.documentElement.lang).toBe('nl');
    });

    it('resets to English between tests', () => {
        expect(i18n.global.locale.value).toBe('en');
    });
});

describe('Dutch begin wording and plurals of corrected keys', () => {
    afterEach(() => setLocale('en'));

    it.each([
        ['unitLessons.start', 'Begin'],
        ['dashboard.startReview', 'Begin met herhalen'],
    ])('uses begin for %s', (key, text) => {
        setLocale('nl');

        expect(i18n.global.t(key)).toBe(text);
        setLocale('en');
        expect(i18n.global.t(key)).not.toBe(text);
    });

    it.each([
        [
            'review.deck.stillDue.card',
            1,
            'Nog 1 kaart te herhalen. Begin gerust',
        ],
        [
            'review.deck.stillDue.card',
            2,
            'Nog 2 kaarten te herhalen. Begin gerust',
        ],
        [
            'review.deck.stillDue.weakSpot',
            1,
            'Nog 1 zwak punt te herhalen. Begin gerust',
        ],
        [
            'review.deck.stillDue.weakSpot',
            2,
            'Nog 2 zwakke punten te herhalen. Begin gerust',
        ],
        ['unitLessons.skippedListeningPart', 1, '1 luisteroefening'],
        ['unitLessons.skippedListeningPart', 3, '3 luisteroefeningen'],
        ['unitLessons.skippedSpeakingPart', 1, '1 spreekoefening'],
        ['unitLessons.skippedSpeakingPart', 3, '3 spreekoefeningen'],
    ])('pluralises %s for %i', (key, count, text) => {
        setLocale('nl');

        expect(i18n.global.t(key, { count }, count)).toContain(text);
    });

    it('keeps the English skipped note byte-identical', () => {
        const part = (key: string, count: number) =>
            i18n.global.t(key, { count }, count);

        expect(
            i18n.global.t('unitLessons.skippedBoth', {
                listening: part('unitLessons.skippedListeningPart', 2),
                speaking: part('unitLessons.skippedSpeakingPart', 3),
            }),
        ).toBe('Skipped listening in 2 and speaking in 3 exercises.');
        expect(
            i18n.global.t('unitLessons.skippedBoth', {
                listening: part('unitLessons.skippedListeningPart', 1),
                speaking: part('unitLessons.skippedSpeakingPart', 1),
            }),
        ).toBe('Skipped listening in 1 and speaking in 1 exercises.');
    });
});
