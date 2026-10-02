import { describe, expect, it } from 'vitest';
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
