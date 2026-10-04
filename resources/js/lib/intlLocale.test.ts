import { describe, expect, it } from 'vitest';
import { setLocale } from '@/i18n';
import { dateFormatter, intlTag, joinList } from './intlLocale';
import { retakeDate } from './retakeDate';

describe('intl locale', () => {
    it('follows the interface locale', () => {
        expect(intlTag()).toBe('en-GB');

        setLocale('nl');

        expect(intlTag()).toBe('nl-NL');
    });

    it('formats dates with the interface locale', () => {
        const date = new Date('2026-10-05T00:00:00Z');
        const options = { weekday: 'long', timeZone: 'UTC' } as const;

        expect(dateFormatter(options).format(date)).toBe('Monday');

        setLocale('nl');

        expect(dateFormatter(options).format(date)).toBe('maandag');
    });

    it('joins a list with the interface locale conjunction', () => {
        expect(joinList(['reading', 'writing'])).toBe('reading and writing');
        expect(joinList(['a', 'b', 'c'])).toBe('a, b and c');

        setLocale('nl');

        expect(joinList(['a', 'b', 'c'])).toBe('a, b en c');
    });

    it('labels the retake date in the interface language', () => {
        expect(retakeDate('2026-10-05')).toBe('5 October');

        setLocale('nl');

        expect(retakeDate('2026-10-05')).toBe('5 oktober');
    });
});
